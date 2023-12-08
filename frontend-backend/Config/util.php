<?php
class Util
{
  public static $controller = null;

  /**
   * Helper to resize images... only simple ops!
   */
  public static function resizeImage($photo, $config = array())
  {
    $img_size = getImageSize($photo);

    if (!empty($config['w']))
    {
      $config['width'] = $config['w'];
    }

    if (!empty($config['h']))
    {
      $config['height'] = $config['h'];
    }

    if (!empty($config['q']))
    {
      $config['quality'] = $config['q'];
    }

    if (empty($config['width']) && empty($config['height']))
    {
      $config['width'] = $img_size[0];
      $config['height'] = $img_size[1];
    }

    if (empty($config['height']))
    {
      $config['height'] = round($img_size[1] * ($config['width'] / $img_size[0]));
    }

    if (empty($config['width']))
    {
      $config['width'] = round($img_size[0] * ($config['height'] / $img_size[1]));
    }

    if (empty($config['quality']))
    {
      $config['quality'] = 95;
    }

    $resizeMethod = class_exists("imagick") ? "__resizeImageImagick" : "__resizeImageGD";

    return self::{$resizeMethod}($photo, $config);
  }

  /**
   * Helper to resize images using GD extension...
   */
  private static function __resizeImageGD($photo, $config = array())
  {
    $str_image = @file_get_contents($photo) or die("Image not found");
    $original_image = imagecreatefromstring($str_image);
    $final_image = imagecreatetruecolor($config['width'], $config['height']);

    if (strpos($photo, ".gif") !== false)
    {
      $transpIndex = imagecolortransparent($original_image);
      $transpColor = array('red' => 255, 'green' => 255, 'blue' => 255);

      if ($transpIndex >= 0)
      {
        $transpColor = imagecolorsforindex($original_image, $transpIndex);
      }

      $transpIndex = imagecolorallocate($final_image, $transpColor['red'], $transpColor['green'], $transpColor['blue']);
      imagefill($final_image, 0, 0, $transpIndex);
      imagecolortransparent($final_image, $transpIndex);

      imagecopyresampled($final_image, $original_image, 0, 0, 0, 0, $config['width'], $config['height'], imagesx($original_image), imagesy($original_image));

      imagegif($final_image, !empty($config['dest']) ? $config['dest'] : $photo);
    }
    elseif (strpos($photo, ".png") !== false)
    {
      imagealphablending($final_image, false);
      imagesavealpha($final_image, true);

      imagealphablending($original_image, false);
      imagesavealpha($original_image, true);

      imagecopyresampled($final_image, $original_image, 0, 0, 0, 0, $config['width'], $config['height'], imagesx($original_image), imagesy($original_image));

      imagepng($final_image, !empty($config['dest']) ? $config['dest'] : $photo);
    }
    else
    {
      imagefill($final_image, 0, 0, imagecolorallocate($final_image, 255, 255, 255));

      imagecopyresampled($final_image, $original_image, 0, 0, 0, 0, $config['width'], $config['height'], imagesx($original_image), imagesy($original_image));

      imagejpeg($final_image, !empty($config['dest']) ? $config['dest'] : $photo, $config['quality']);
    }

    imagedestroy($original_image);
    imagedestroy($final_image);

    return !empty($config['dest']) ? $config['dest'] : $photo;
  }

  /**
   * Helper to resize images using ImageMagick extension...
   */
  private static function __resizeImageImagick($photo, $config = array())
  {
    if (!file_exists($photo))
    {
      return false;
    }

    $imagickVersion = phpversion('imagick');

    $image = new Imagick();
    $image->readImage($photo);
    $image->thumbnailImage($config['width'], $config['height'], !($imagickVersion[0] == 3));
    $image->setImageCompressionQuality($config['quality']);

    if (strpos($photo, ".gif") !== false)
    {
      $image->setImageFormat("gif");
    }
    elseif (strpos($photo, ".png") !== false)
    {
      $image->setImageFormat("png");
    }
    else
    {
      $image->setImageFormat("jpg");
    }

    if (!$image->writeImage(!empty($config['dest']) ? $config['dest'] : $photo))
    {
      return false;
    }

    $image->clear();
    $image->destroy();

    return !empty($config['dest']) ? $config['dest'] : $photo;
  }

  public static function extract_size_and_pixels_with_gd($src)
  {
    $image = imagecreatefromjpeg($src);

    $width = imagesx($image);
    $height = imagesy($image);

    $pixels = array();
    for ($y = 0; $y < $height; $y++)
    {
      for ($x = 0; $x < $width; $x++)
      {
        $color_index = imagecolorat($image, $x, $y);
        $color = imagecolorsforindex($image, $color_index);
        $pixels[] = $color['red'];
        $pixels[] = $color['green'];
        $pixels[] = $color['blue'];
        $pixels[] = 255;
      }
    }

    $size = max($width, $height);
    $width = round(100 * $width / $size);
    $height = round(100 * $height / $size);

    return array($width, $height, $pixels);
  }

  public static function createThumbhash($src)
  {
    $src = WWW_ROOT . $src;
    if (!file_exists($src))
    {
      return array("thumbhash" => null, "source" => "error", "src" => $src);
    }

    $dest = TMP . "cache" . DS . "phpthumb" . DS . "thumb_" . basename($src);

    // internal cache...
    $dest_thumbhash = TMP . "cache" . DS . "phpthumb" . DS . "thumbhash_" . filemtime($src) . "_" . basename($src) . ".txt";
    if (file_exists($dest_thumbhash))
    {
      $cached_thumbhash = trim(file_get_contents($dest_thumbhash));
      if (!empty($cached_thumbhash))
      {
        return array("thumbhash" => $cached_thumbhash, "source" => "cache");
      }
    }

    self::resizeImage($src, array('w' => 100, 'q' => 90, 'dest' => $dest));

    list($width, $height, $pixels) = self::extract_size_and_pixels_with_gd($dest);

    $hash = Thumbhash::RGBAToHash($width, $height, $pixels);
    $key = Thumbhash::convertHashToString($hash);

    @file_put_contents($dest_thumbhash, $key);
    @unlink($dest);

    return array("thumbhash" => $key, "source" => "fresh");
  }

  public static function getCep($cep)
  {
    $cache_key = 'cep_' . $cep;
    if (($dadosCep = Cache::read($cache_key, 'temp')) !== false)
    {
      return $dadosCep;
    }

    $cepData = Util::get_web_page("https://burnutils.appspot.com/correios/cep.json?cep={$cep}");
    if (!empty($cepData))
    {
      $cepData = json_decode($cepData, true);
      if (empty($cepData['erro']))
      {
        $cepReturn = array(
          'Erro' => null,
          'Cep' => $cepData,
        );

        Cache::write($cache_key, $cepReturn, 'temp');

        return $cepReturn;
      }
    }

    return array(
      'Erro' => 'CEP Inválido',
      'Cep' => null,
      'Response' => $cepData,
    );
  }

  public static function isDirEmpty($dirname)
  {
    if (!is_dir($dirname))
    {
      return false;
    }

    foreach (scandir($dirname) as $file)
    {
      if (!in_array($file, array('.', '..', '.svn', '.git')))
      {
        return false;
      }

    }
    return true;
  }

  public static function clearSpecificCache($type = "Noticia", $id = null)
  {
    $path = '/';

    if ($type === "Noticia" && !empty($id))
    {
      if (!isset(self::$controller->Noticia))
      {
        self::$controller->loadModel("Noticia");
      }

      $noticia = self::$controller->Noticia->find('first', array(
        'fields' => array(
          'Noticia.id',
          'Noticia.created',
          'Noticia.modified',
          'Noticia.name',
          'Noticia.data',
        ),
        'conditions' => array('Noticia.id' => $id),
        'recursive' => 0,
      ));
      if (empty($noticia))
      {
        return null;
      }

      $noticia_url = sprintf("noticia/%s", $noticia['Noticia']['slug']);
      $path = Router::url("/" . $noticia_url);
    }

    if ($path === '/')
    {
      $path = 'home';
    }

    $cache = strtolower(Inflector::slug($path));

    if (empty($cache))
    {
      return null;
    }

    $files = glob(CACHE . "views" . DS . '*' . $cache . '*.php');
    if (!empty($files))
    {
      foreach ($files as &$cached)
      {
        self::exclude($cached);
      }
    }

    return true;
  }

  public static function clearCache($clearViews = false)
  {
    clearCache(null, ".");    // cache
    clearCache(null, "temp"); // cache + temp

    if (!!$clearViews)
    {
      clearCache(null, "views"); // cache / views
    }
  }

  public static function sortDatesBR($dates = array())
  {
    function sortDateBR($adate, $bdate)
    {
      $adate = Util::normalizeDate($adate);
      $bdate = Util::normalizeDate($bdate);

      return $adate > $bdate;
    }

    uasort($dates, "sortDateBR");

    return $dates;
  }

  public static function extractImages($content)
  {
    $images = array();

    preg_match_all('/<img[^>]+>/i', $content, $extracted_images);
    if (!empty($extracted_images[0]))
    {
      foreach ($extracted_images[0] as $img_tag)
      {
        preg_match_all('/src=("[^"]*"|\'[^\']*\')/i', $img_tag, $img_info);
        if (!empty($img_info[1][0]))
        {
          $images[] = preg_replace('/[\'"]/i', "", $img_info[1][0]);
        }
      }
    }

    return array_unique($images);
  }

  public static function startsWith($haystack, $needle)
  {
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
  }

  public static function endsWith($haystack, $needle)
  {
    $length = strlen($needle);
    if ($length == 0)
    {
      return true;
    }

    return (substr($haystack, -$length) === $needle);
  }

  /* @from: https://stackoverflow.com/a/30529991/1683407 */
  public static function removeWhiteSpace($text)
  {
    $text = preg_replace('/[\t\n\r\0\x0B]/', '', $text);
    $text = preg_replace('/([\s])\1+/', ' ', $text);
    $text = trim($text);

    return $text;
  }

  public static function normalizeDate($dataEntry)
  {
    $newDate = null;
    if (!empty($dataEntry))
    {
      if (is_array($dataEntry) && isset($dataEntry['year']) && isset($dataEntry['month']) && isset($dataEntry['day']))
      {
        $newDate = implode("-", array($dataEntry['year'], $dataEntry['month'], $dataEntry['day']));
      }
      elseif (is_string($dataEntry))
      {
        if (preg_match("/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})$/", trim($dataEntry)))
        {
          $newDate = implode("-", array_reverse(explode("/", trim($dataEntry))));
        }
        else
        {
          $newDate = $dataEntry;
        }

        if (!empty($newDate))
        {
          $dateValidate = explode("-", $newDate);
          if (!(!empty($dateValidate[2]) && !empty($dateValidate[1]) && !empty($dateValidate[0])
            && checkdate($dateValidate[1], $dateValidate[2], $dateValidate[0])))
          {
            $newDate = null;
          }
        }
      }
    }

    return $newDate;
  }

  /* strpos that takes an array of values to match against a string
   * note the stupid argument order (to match strpos)
   */
  public static function strpos_arr($haystack, $needle)
  {
    if (!is_array($needle))
    {
      $needle = array($needle);
    }

    foreach ($needle as $what)
    {
      if (($pos = strpos($haystack, $what)) !== false)
      {
        return $pos;
      }

    }
    return false;
  }

  public static function bot_detected()
  {
    if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT']))
    {
      return true;
    }

    return false;
  }

  /* @from: http://stackoverflow.com/questions/6914912/streaming-a-large-file-using-php */
  public static function readfile_chunked($filename, $retbytes = true)
  {
    $buffer = '';
    $cnt = 0;
    $handle = fopen($filename, 'rb');
    if ($handle === false)
    {
      return false;
    }
    while (!feof($handle))
    {
      $buffer = fread($handle, 1024 * 1024);
      echo $buffer;
      ob_flush();
      flush();
      if ($retbytes)
      {
        $cnt += strlen($buffer);
      }
    }
    $status = fclose($handle);
    if ($retbytes && $status)
    {
      return $cnt; // return num. bytes delivered like readfile() does.
    }
    return $status;
  }

  /* @from: http://stackoverflow.com/a/5727401/1683407 */
  public static function parse_crontab($frequency = '* * * * *', $time = false)
  {
    $time = is_string($time) ? strtotime($time) : time();
    $time = explode(' ', date('i G j n w', $time));
    $crontab = explode(' ', $frequency);
    foreach ($crontab as $k => &$v)
    {
      $v = explode(',', $v);
      $regexps = array(
        '/^\*$/', # every
        '/^\d+$/', # digit
        '/^(\d+)\-(\d+)$/', # range
        '/^\*\/(\d+)$/', # every digit
      );
      $content = array(
        "true", # every
        "{$time[$k]} === 0", # digit
        "($1 <= {$time[$k]} && {$time[$k]} <= $2)", # range
        "{$time[$k]} % $1 === 0", # every digit
      );
      foreach ($v as &$v1)
      {
        $v1 = preg_replace($regexps, $content, $v1);
      }

      $v = '(' . implode(' || ', $v) . ')';
    }
    $crontab = implode(' && ', $crontab);
    return eval("return {$crontab};");
  }

  public static function getKeys($string)
  {
    $string = str_replace(array("/", ";", "|", "-", "#", "\\", "..", ":"), ",", $string);
    $string = array_map("trim", explode(",", $string));

    return $string;
  }

  public static function getLike($model, $field, $item)
  {
    $item = Util::stripLatin($item);

    return (!empty($item)) ? array("{$model}.{$field} LIKE" => "%{$item}%") : array();
  }

  public static function getSoundex($model, $field, $item)
  {
    return sprintf("SOUNDEX(%s.%s) = SOUNDEX('%s')", $model, $field, $item);
  }

  public static function stripLatin($v)
  {
    $from = array('á', 'à', 'ã', 'â', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï', 'ó', 'ò', 'õ', 'ô', 'ö', 'ú', 'ù', 'û', 'ü', 'ç',
      'Á', 'À', 'Ã', 'Â', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï', 'Ó', 'Ò', 'Õ', 'Ô', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç');

    $to = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c',
      'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'c');

    $v = str_replace($from, $to, $v);

    return $v;
  }

  public static function address2geo($address)
  {
    if (empty($address))
    {
      return false;
    }

    if (!function_exists("check_short_name"))
    {
      function check_short_name($sn)
      {
        if (strlen($sn) == 2)
        {
          return $sn;
        }

        $sn = str_replace(" do ", " ", $sn);
        $sn = explode(" ", $sn);

        if (count($sn) == 1)
        {
          $sn = strtoupper(substr($sn[0], 0, 2));
        }
        else
        {
          $sns = array();
          foreach ($sn as $s)
          {
            $sns[] = strtoupper(substr($s, 0, 1));
          }
          $sn = $sns[0] . $sns[count($sns) - 1];
        }

        return $sn;
      }
    }

    $geolocation = false;
    $geolocator_url = "http://maps.google.com/maps/api/geocode/%s?address=%s&sensor=false";

    if (function_exists("json_decode"))
    {
      $json_geocode = Util::get_web_page(sprintf($geolocator_url, "json", urlencode($address)));
      $json_geocode = @json_decode($json_geocode);
      if (!empty($json_geocode) && !empty($json_geocode->results))
      {
        $result = $json_geocode->results[0];
        $geolocation = $result->geometry->location;

        $geolocation = array('lat' => (float) $geolocation->lat, 'lng' => (float) $geolocation->lng, 'cep' => $result->address_components[0]->long_name);
        foreach ($result->address_components as $c)
        {
          if (!empty($c->types[0]))
          {
            switch ($c->types[0])
            {
              case "locality":
                $geolocation['cidade'] = $c->long_name;
                break;
              case "administrative_area_level_1":
                $geolocation['estado'] = $c->long_name;
                $geolocation['uf'] = check_short_name($c->short_name);
                break;
              case "country":
                $geolocation['pais'] = $c->long_name;
                $geolocation['pais_uf'] = check_short_name($c->short_name);
                break;
            }
          }
        }
      }
    }

    return $geolocation;
  }

  public static function get_web_page($url, $cache = true, $cache_key = null)
  {
    if (empty($cache_key))
    {
      $cache_key = md5($url . "_" . $url);
    }

    if ($cache && ($content_cache = Cache::read($cache_key, 'temp')) !== false)
    {
      return $content_cache;
    }

    $options = array(
      CURLOPT_RETURNTRANSFER => true, // return web page
      CURLOPT_HEADER => false,        // don't return headers
      CURLOPT_FOLLOWLOCATION => true, // follow redirects
      CURLOPT_ENCODING => "",         // handle all encodings
      CURLOPT_USERAGENT => "spider",  // who am i
      CURLOPT_AUTOREFERER => true,    // set referer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,  // timeout on connect
      CURLOPT_TIMEOUT => 120,         // timeout on response
      CURLOPT_MAXREDIRS => 10,        // stop after 10 redirects
    );

    $ch = curl_init($url);
    curl_setopt_array($ch, $options);
    $content = curl_exec($ch);
    curl_close($ch);

    if ($cache)
    {
      Cache::write($cache_key, $content, 'temp');
    }

    return $content;
  }

  public static function do_post_request($url, $data = array(), $timeout = 60)
  {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $result = trim(curl_exec($curl));

    curl_close($curl);

    return $result;
  }

  public static function slug($string = null, $id = null, $replacement = '-', $map = array())
  {
    if (is_array($string))
    {
      if (empty($string['name']) || empty($string['id']))
      {
        return false;
      }

      $string = $string['name'] . $replacement . $string['id'];
      $id = null;
    }

    $map = array_merge($map, array(
      "/\n/" => '',
      '/"/' => '',
      '/\'/' => '',
      '/"/' => '',
      '/¹/' => '1up',
      '/²/' => '2up',
      '/³/' => '3up',
      '/¼/' => '0dot25',
      '/½/' => '0dot5',
      '/¾/' => '0dot75',
      '/À|Á|Å|Â|Ã|Ä|à|á|å|â|ã|ä|ª/' => 'a',
      '/@/' => 'at',
      '/Æ|æ/' => 'ae',
      '/©|Ç|ç|¢/' => 'c',
      '/Ð/' => 'd',
      '/\./' => '',
      '/È|É|Ê|Ë|è|é|ê|\?|ë/' => 'e',
      '/ƒ/' => 'f',
      '/Ì|Í|Î|Ï|ì|í|î|ï/' => 'i',
      '/£/' => 'l',
      '/Ñ|ñ/' => 'n',
      '/Ò|Ó|Ô|Ø|Õ|Ö|ò|ó|ô|ø|õ|ö|ð|º|°/' => 'o',
      '/Œ|œ/' => 'oe',
      '/®/' => 'registred',
      '/Š|\$|š|ß/' => 's',
      '/™/' => 'trademark',
      '/Ù|Ú|Û|Ü|Ü|ù|ú|u|û|µ|ü/' => 'u',
      '/×/' => 'x',
      '/¥/' => 'yen',
      '/ÿ|ý/' => 'y',
      '/Ž|ž/' => 'z',
    ));

    $string = Inflector::slug(Util::stripLatin(trim($string)), $replacement, $map);
    $string = preg_replace("/[^a-zA-Z0-9\s\-_]/", "", $string);
    $string = function_exists("mb_strtolower") ? mb_strtolower($string) : strtolower($string);

    if (!empty($id))
    {
      $string .= $replacement . $id;
    }

    return $string;
  }

  public static function truncate($s2 = "", $n = 10, $e = "...")
  {
    $s = trim(strip_tags($s2));
    $tmp = strrpos(substr($s, 0, $n), chr(32));
    if ($tmp > 0 && strlen($s) >= $n)
    {
      $s = substr($s, 0, $tmp);
    }

    $s = trim($s);

    return ($s == $s2) ? $s : $s . $e;
  }

  public static function exclude($file)
  {
    if (!file_exists($file))
    {
      $file = WWW_ROOT . $file;
    }

    if (is_readable($file))
    {
      $i = 0;
      do
      {
        $i++;
        if (@unlink($file))
        {
          return true;
        }

        sleep(1);
      } while ($i < 5);
    }

    return false;
  }

  public static function autop($pee, $br = 1) //wpautop function - from (C)wordpress.com
  {
    $pee = $pee . "\n"; // just to make things a little easier, pad the end
    $pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
    // Space things out a little
    $pee = preg_replace('!(<(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|p|h[1-6])[^>]*>)!', "\n$1", $pee);
    $pee = preg_replace('!(</(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|p|h[1-6])>)!', "$1\n\n", $pee);
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee);                                                                                                                                          // cross-platform newlines
    $pee = preg_replace("/\n\n+/", "\n\n", $pee);                                                                                                                                                 // take care of duplicates
    $pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee);                                                                                                                        // make paragraphs, including one at the end
    $pee = preg_replace('|<p>\s*?</p>|', '', $pee);                                                                                                                                               // under certain strange conditions it could create a P of entirely whitespace
    $pee = preg_replace('!<p>\s*(</?(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|hr|pre|select|form|blockquote|address|math|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);                                                                                                                                         // problem with nested lists
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
    $pee = preg_replace('!<p>\s*(</?(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|hr|pre|select|form|blockquote|address|math|p|h[1-6])[^>]*>)!', "$1", $pee);
    $pee = preg_replace('!(</?(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|p|h[1-6])[^>]*>)\s*</p>!', "$1", $pee);
    if ($br)
    {
      $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
    }
    $pee = preg_replace('!(</?(?:table|thead|tfoot|caption|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|p|h[1-6])[^>]*>)\s*<br />!', "$1", $pee);
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)>)!', '$1', $pee);
    $pee = preg_replace('!(<pre.*?>)(.*?)</pre>!ise', " stripslashes('$1') .  stripslashes(clean_pre('$2'))  . '</pre>' ", $pee);

    return $pee;
  }

  public static function downloadImage($img_uri, $target_path)
  {
    try
    {
      $img_data = Util::get_web_page($img_uri, false);
      if (!$img_data || $img_data == null || strpos($img_data, "404") !== false)
      {
        return null;
      }

      $a = WWW_ROOT . $target_path;
      if (file_exists($a))
      {
        @unlink($a);
      }

      $ai = fopen($a, "w");
      fwrite($ai, $img_data, strlen($img_data));
      fclose($ai);

      return $target_path;
    }
    catch (Exception $e)
    {
      return null;
    }
  }

  public static function convert_txt2csv($txt, $csv)
  {
    if (($handle = fopen($txt, "r")) === false)
    {
      return;
    }

    $fp = fopen($csv, 'w');
    while (($cols = fgetcsv($handle, 0, "\t")) !== false)
    {
      foreach ($cols as $key => $val)
      {
        $cols[$key] = trim($cols[$key]);
        $cols[$key] = utf8_encode($cols[$key]);
        $cols[$key] = str_replace('""', '"', $cols[$key]);
        $cols[$key] = preg_replace("/^\"(.*)\"$/sim", "$1", $cols[$key]);
        $cols[$key] = trim($cols[$key]);
      }

      fputcsv($fp, $cols);
    }

    fclose($handle);
    fclose($fp);

    @unlink($txt);
  }

  public static function utf8_encode($string)
  {
    if (function_exists("mb_convert_encoding"))
    {
      return mb_convert_encoding($string, "UTF-8");
    }

    if (function_exists("mb_detect_encoding") && function_exists("iconv"))
    {
      return iconv(mb_detect_encoding($string), 'UTF-8//TRANSLIT', $string);
    }

    return utf8_encode($string);
  }

  public static function utf8_detect($string)
  {
    if (function_exists("mb_detect_encoding"))
    {
      return (mb_detect_encoding($string) == "UTF-8");
    }

    return preg_match('%(?:
      [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
      |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
      |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
      |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
      |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
      |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
      |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
      )+%xs', $string);
  }

}
