<?php
App::uses("HtmlHelper", "View/Helper");

class xHtmlHelper extends HtmlHelper
{
  public function favicon($options = array())
  {
    $options = array_merge(array(
      "iconsPath" => "/",
      "favicon" => "favicon.ico",
      "svg" => "icon.svg",
      "apple" => "apple-touch-icon.png",
      "manifest" => "manifest.webmanifest",
    ), $options);

    extract($options);

    if (substr($iconsPath, -1) !== "/")
    {
      $iconsPath .= "/";
    }

    $localIconsPath = WWW_ROOT . str_replace("/", DS, $iconsPath);

    $_tags = array();
    if (file_exists($localIconsPath . $favicon))
    {
      $_tags[] = '<link rel="icon" href="' . Router::url("{$iconsPath}{$favicon}") . '" sizes="any" />';
    }
    if (file_exists($localIconsPath . $svg))
    {
      $_tags[] = '<link rel="icon" href="' . Router::url("{$iconsPath}{$svg}") . '" type="image/svg+xml" />';
    }
    if (file_exists($localIconsPath . $apple))
    {
      $_tags[] = '<link rel="apple-touch-icon" href="' . Router::url("{$iconsPath}{$apple}") . '" />';
    }
    if (file_exists($localIconsPath . $manifest))
    {
      $_tags[] = '<link rel="manifest" href="' . Router::url("{$iconsPath}{$manifest}") . '" />';
    }

    return $this->output(implode(chr(13), $_tags));
  }

  public function active_param($custom = array(), $klass = "active")
  {
    $base_url = array_merge(array('controller' => $this->params['controller'], 'action' => $this->params['action']), $this->params['pass'], $this->params['named']);

    foreach ($custom as $k => $v)
    {
      if (isset($base_url[$k]) && $base_url[$k] == $v)
      {
        return $klass;
      }

    }

    return null;
  }

  public function params_url($custom = array(), $toggle = false)
  {
    $base_url = array_merge(array('controller' => $this->params['controller'], 'action' => $this->params['action']), $this->params['pass'], $this->params['named']);
    if (!isset($custom['limit']))
    {
      $base_url['?'] = $this->request->query;
    }

    if (!$toggle)
    {
      $base_url = array_merge($base_url, $custom);
    }
    else
    {
      foreach ($custom as $k => $v)
      {
        if (isset($base_url[$k]))
        {
          $base_url[$k] = null;
          unset($base_url[$k]);
        }
        else
        {
          $base_url[$k] = $v;
        }
      }
    }

    return $this->url($base_url);
  }

  public function admin_image_tag($src = null, $conf = array(), $htmlAttributes = array())
  {
    $html_tpl = '<div class="col-xs-12 col-md-5 input image"><div class="thumbnail">%s</div></div>';
    $html_tpl_a = '<a href="%s" class="btn btn-danger btn-block">%s</a>';

    if (!empty($conf['id']) && !empty($conf['model']))
    {
      $field = !empty($conf['field']) ? $conf['field'] : 'img';
      $mode = !empty($conf['mode']) ? $conf['mode'] : 'file';

      $link_exclude = sprintf($html_tpl_a, Router::url(array('admin' => true, 'controller' => 'cmanager', 'action' => 'image_delete', 'ext' => 'json', STORE_SSK, $conf['model'], $conf['id'], $field, $mode)), __('Excluir esta Imagem', true));
    }
    else
    {
      $link_exclude = null;
    }

    $new_html = sprintf("<img src='%s' alt='' />", $this->webroot . $src);

    $html = sprintf($html_tpl, $new_html . $link_exclude);

    return $this->output($html);
  }
}
