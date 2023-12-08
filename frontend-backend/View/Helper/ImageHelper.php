<?php
App::uses('AppHelper', 'View/Helper');
App::uses('HtmlHelper', 'View/Helper');

class ImageHelper extends AppHelper
{

  private static $_svgCache = array();

  public $thumbs_controller_url = "/image";
  public $useSerializable = false;

  public function __construct(View $view, $settings = array())
  {
    if (!isset($this->html))
    {
      $this->html = new HtmlHelper($view);
    }

    parent::__construct($view, $settings);
  }

  public function admin_tag($src = null, $conf = array(), $htmlAttributes = array())
  {
    $html_tpl = '<div class="input col-md-3"><div class="thumbnail image">%s</div></div>';
    $html_tpl_a = '<a href="%s" class="btn btn-sm btn-danger btn-block" role="button">%s</a>';

    $defaultConf = array('width' => 360, 'r' => true);

    if (!empty($conf['id']) && !empty($conf['model']))
    {
      $field = !empty($conf['field']) ? $conf['field'] : 'img';
      $mode = !empty($conf['mode']) ? $conf['mode'] : 'file';

      $link_exclude = sprintf($html_tpl_a, Router::url('/admin/cmanager/image_delete/' . $conf['model'] . '/' . $conf['id'] . '/' . $field . '/' . $mode . '.json'), __('Excluir esta Imagem'));
    }
    else
    {
      $link_exclude = null;
    }

    $new_html = $this->tag($src, array_merge($defaultConf, $conf), array_merge($htmlAttributes, array("style" => "width: 100%;")));

    $html = sprintf($html_tpl, $new_html . $link_exclude);

    return $this->output($html);
  }

  public function tag($src = null, $conf = array(), $htmlAttributes = array())
  {
    if (empty($src))
    {
      return false;
    }

    $url = $this->src($src, $conf);

    if (!isset($htmlAttributes['alt']))
    {
      $htmlAttributes['alt'] = '';
    }

    $html = sprintf('<img src="%s" %s/>', $url, $this->_parseAttributes($htmlAttributes, null, '', ' '));

    return $this->output($html);
  }

  public function svg($src, $htmlAttributes = array(), $keepOriginalClassName = true, $tabSpace = "  ")
  {
    $cache_key = md5(serialize(func_get_args()));
    if (isset(self::$_svgCache[$cache_key]) && !empty(self::$_svgCache[$cache_key]))
    {
      return self::$_svgCache[$cache_key];
    }

    $svg_file_content = file_get_contents(WWW_ROOT . $src);

    $xmlSvg = new SimpleXMLElement($svg_file_content, 0, false);
    if (!empty($htmlAttributes) && is_array($htmlAttributes))
    {
      foreach ($htmlAttributes as $keyAttr => $value)
      {
        if (isset($xmlSvg->attributes()->{$keyAttr}))
        {
          if ($keyAttr === "class" && $keepOriginalClassName)
          {
            $value = $xmlSvg->attributes()->{$keyAttr} . " " . $value;
          }

          $xmlSvg->attributes()->{$keyAttr} = $value;
        }
        else
        {
          $xmlSvg->addAttribute($keyAttr, $value);
        }
      }
    }

    $svg = $xmlSvg->asXML();
    $svg = str_replace("\r\n", "\n", $svg);
    $svg = str_replace("\t", $tabSpace, $svg);

    // remove a <?xml tag from the svg
    $svg = preg_replace('/<\?xml.*?\?>/', '', $svg);

    // remove the DOCTYPE tag from the $svg string
    $svg = preg_replace('/<!DOCTYPE.*?>/', '', $svg);

    // remove xmlns:xlink attribute from the $svg string
    $svg = preg_replace('/ xmlns:xlink=[\'"].*?[\'"]/', '', $svg);
    $svg = preg_replace('/ xml:space=[\'"].*?[\'"]/', '', $svg);

    self::$_svgCache[$cache_key] = $this->output($svg);

    return self::$_svgCache[$cache_key];
  }

  public function src($src = null, $conf = array())
  {
    if (empty($src))
    {
      return false;
    }

    $query_conf = array();

    $conf["src"] = $src;

    if (!empty($conf['r']) && $conf['r'])
    {
      if (is_bool($conf['r']) && !isset($conf['nowebroot']))
      {
        $conf['r'] = file_exists(WWW_ROOT . $conf["src"]) ? filemtime(WWW_ROOT . $conf["src"]) : date("U");
      }
      else
      {
        $query_conf["_"] = $conf['r'];

        unset($conf['r']);
      }
    }
    elseif (!isset($conf['nowebroot']))
    {
      $conf['r'] = file_exists(WWW_ROOT . $conf["src"]) ? filemtime(WWW_ROOT . $conf["src"]) : date("U");
    }

    if (isset($conf['width']))
    {
      $conf['w'] = $conf['width'];
      unset($conf['width']);
    }

    if (isset($conf['height']))
    {
      $conf['h'] = $conf['height'];
      unset($conf['height']);
    }

    $aext = explode(".", $conf["src"]);
    $ext = end($aext);

    if (!isset($conf['tag']))
    {
      if (file_exists(WWW_ROOT . $conf["src"]))
      {
        $filename = basename(WWW_ROOT . $conf["src"]);
      }
      else
      {
        $filename = "img." . $ext;
      }
    }
    else
    {
      $tag = substr($conf['tag'], 0, 251);
      $tag = Util::slug($tag, null, "_");

      $filename = $tag . "." . $ext;

      unset($conf['tag']);
    }

    if ($this->useSerializable)
    {
      $hash_conf = base64_encode(serialize($conf)) . "/{$filename}";
    }
    else
    {
      $str_conf = array();
      foreach ($conf as $key => $val)
      {
        $str_conf[] = "$key=$val";
      }

      $hash_conf = base64_encode(implode("&", $str_conf)) . "/{$filename}";
    }

    if (!isset($conf["only_image_name"]))
    {
      $url = $this->html->url($this->thumbs_controller_url . "/" . $hash_conf);
    }
    else
    {
      $url = $this->thumbs_controller_url . "/" . $hash_conf;
    }

    if (!empty($query_conf))
    {
      $url = $url . "?" . http_build_query($query_conf);
    }

    return $url;
  }

}
?>
