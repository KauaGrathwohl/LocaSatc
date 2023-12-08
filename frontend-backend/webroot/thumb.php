<?php
if (!defined('DS'))
{
  define('DS', '/');
}

if (!defined('WWW_ROOT'))
{
  define('WWW_ROOT', dirname(__FILE__) . DS);
}

if (!defined('ROOT'))
{
  define('ROOT', dirname(dirname(dirname(__FILE__))));
}

if (!defined('APP_DIR'))
{
  define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

if (!defined('IMG_WEBP'))
{
  define('IMG_WEBP', 0);
}

error_reporting(false);

$thumb = null;

$use_serializable = false;

$config_temp_directory = ROOT . DS . APP_DIR . DS . 'tmp' . DS;
$config_cache_directory = $config_temp_directory . 'cache' . DS . 'phpthumb' . DS;
$config_output_format = 'jpeg';

class Configure
{

  public static $_self = null;

  public static function create()
  {
    if (empty(Configure::$_self))
    {
      Configure::$_self = new Configure();
    }

    return Configure::$_self;
  }

  public static function write($_var = null, $_vallue = null)
  {

    $_self = Configure::create();

    if (empty($_var))
    {
      return false;
    }
    $_var = str_replace('.', '_', $_var);

    if (empty($_self->$_var))
    {
      $_self->$_var = $_vallue;
    }

  }

  public static function read($_var = null)
  {

    $_self = Configure::create();

    if (empty($_var))
    {
      return false;
    }
    $_var = str_replace('.', '_', $_var);

    if (!empty($_self->$_var))
    {
      return $_self->$_var;
    }
  }

}

class Cache
{
  public static function config()
  {
    return false;
  }
}

// Load Composer autoload.
require_once ROOT . DS . APP_DIR . DS . 'Vendor' . DS . 'autoload.php';
require_once ROOT . DS . APP_DIR . DS . 'Config' . DS . 'core.php';

$no_cache = Configure::read('Cache.disable');

function createThumbObject()
{
  global $thumb, $no_cache, $config_temp_directory, $config_cache_directory, $config_output_format;

  if (empty($thumb))
  {
    $thumb = new \phpThumb();

    //output
    $thumb->setParameter('config_output_format', $config_output_format);
    $thumb->setParameter('config_allow_src_above_docroot', true);
    $thumb->setParameter('config_allow_src_above_phpthumb', true);
    //cache files
    $thumb->setParameter('config_cache_maxage', 86400 * 30);
    $thumb->setParameter('config_cache_maxsize', 50 * 1024 * 1024);
    $thumb->setParameter('config_cache_maxfiles', 1000);
    //cache dir
    $thumb->setParameter('config_temp_directory', $config_temp_directory);
    $thumb->setParameter('config_cache_directory', $config_cache_directory);
    //error handler
    $thumb->setParameter('config_error_bgcolor', 'EEEEEE');
    $thumb->setParameter('config_error_textcolor', '000000');
    $thumb->setParameter('config_error_fontsize', '2');
    $thumb->setParameter('config_error_die_on_error', true);
    $thumb->setParameter('config_disable_debug', true);
    //end config
  }

  return $thumb;
}

function generateThumb($conf = null)
{
  global $thumb, $no_cache, $config_temp_directory, $config_cache_directory, $config_output_format;

  if (!empty($conf))
  {
    if (empty($conf))
    {
      createThumbObject();
      $thumb->ErrorImage(utf8_decode('Parâmetros incorretos!'));
    }

    foreach ($conf as $k => $v)
    {
      $k = strtolower($k);

      if ($k == "src")
      {
        $v = str_replace(array("$", "|", ";"), "/", $v);
      }

      if ($k == "width")
      {
        $k = "w";
      }

      if ($k == "height")
      {
        $k = "h";
      }

      if ($k == "format")
      {
        $k = "f";
      }

      if ($k == "enlarge")
      {
        $k = "aoe";
      }

      if ($k == "saveas")
      {
        $k = "sia";
      }

      if ($k == "outputformat")
      {
        $k = "of";
      }

      if ($k == "crop")
      {
        $v = explode(",", $v);
        $_GET["sx"] = $_REQUEST["sx"] = $v[0];
        $_GET["sy"] = $_REQUEST["sy"] = $v[1];
        $_GET["sw"] = $_REQUEST["sw"] = $v[2];
        $_GET["sh"] = $_REQUEST["sh"] = $v[3];
      }
      elseif ($k == "watermark" || $k == "wm")
      {
        if (!isset($_GET["fltr"]))
        {
          $_GET["fltr"] = array();
        }

        if (!is_array($v))
        {
          $v = array($v);
        }

        foreach ($v as $vvv)
        {
          $_GET["fltr"][] = "wmi|" . $vvv;
        }

      }
      elseif (!isset($_GET[$k]))
      {
        $_GET[$k] = $_REQUEST[$k] = $v;
      }
    }
  }

  if (!empty($_GET['of']))
  {
    $config_output_format = $_GET['of'];
  }
  else
  {
    $config_output_format = end(explode(".", $_GET['src']));
  }

  $config_output_format = ($config_output_format == "jpg") ? "jpeg" : $config_output_format;

  if (!empty($_GET['src']))
  {
    $sourceFilename = (isset($_GET['nowebroot']) && $_GET['nowebroot']) ? $_GET['src'] : WWW_ROOT . $_GET['src'];
    $sourceFilename = str_replace(array("$", "|", ";"), "/", $sourceFilename);

    if (strpos($sourceFilename, "http://") !== false || strpos($sourceFilename, "https://") !== false || is_readable($sourceFilename))
    {
      if (!is_dir($config_cache_directory))
      {
        mkdir($config_cache_directory);
        chmod($config_cache_directory, 0777);
      }

      //cache nome do arquivo
      if (strpos($sourceFilename, "http://") === false && strpos($sourceFilename, "https://") === false)
      {
        $cacheFilename = array($_SERVER['REQUEST_URI'], filesize($sourceFilename), filemtime($sourceFilename));
      }
      else
      {
        $cacheFilename = array($_SERVER['REQUEST_URI']);
      }
      $cacheFilename = md5(implode("_", $cacheFilename)) . '.' . $config_output_format;
      $cache_filename = $config_cache_directory . $cacheFilename;

      //usa o cache
      if (!$no_cache && is_file($cache_filename))
      {
        $cachedImage = getimagesize($cache_filename);
        header('Content-Type: image/' . $config_output_format);

        sendSaveAsFileHeaderIfNeeded();

        setHeadersOfFile($cache_filename, filesize($cache_filename));

        if (!readfile($cache_filename))
        {
          createThumbObject();
          $thumb->ErrorImage(utf8_decode('Erro ao ler a imagem do Cache!'));
        }

      }
      else
      {
        //gera um nova imagem
        createThumbObject();

        //configura as opções de parametro pelo GET
        foreach ($_GET as $key => $value)
        {
          $key = strtolower($key);
          if ($key != 'url')
          {
            $thumb->setParameter($key, $value);
          }
        }

        //src of file...
        $thumb->src = $sourceFilename;

        if ($thumb->GenerateThumbnail())
        {
          //gera a imagem no buffer
          $thumb->RenderOutput();

          header('Content-Type: image/' . $config_output_format);

          sendSaveAsFileHeaderIfNeeded();

          setHeadersOfFile($thumb->src, strlen($thumb->outputImageData));

          //cria a imagem no cache
          if (!$no_cache)
          {
            $thumb->RenderToFile($cache_filename);
          }

          //envia a imagem para a saida
          echo $thumb->outputImageData;

        }
        else
        {
          createThumbObject();
          $thumb->ErrorImage(utf8_decode('Erro Interno: ' . $thumb->error));
        }
      }

    }
    else
    {
      createThumbObject();
      $thumb->ErrorImage(utf8_decode("Não foi possível ler a imagem de origem"));
    }
  }
  else
  {
    createThumbObject();
    $thumb->ErrorImage(utf8_decode("Parâmetros insuficientes: faltando imagem de entrada!"));
  }
}

function setHeadersOfFile($filename = null, $file_size = null)
{
  global $no_cache;

  if (!empty($filename))
  {
    $one_year = (366 * 86400);
    $file_last_modified = gmdate("D, d M Y H:i:s", filemtime($filename)) . " GMT";
    $file_expires = gmdate("D, d M Y H:i:s", time() + $one_year) . " GMT";

    if (!$no_cache)
    {
      header("Cache-Control: max-age={$one_year}, pre-check={$one_year}", true);
      header("Expires: " . $file_expires, true);
      header("ETag: " . md5($filename . "_" . $file_last_modified), true);
      header("Last-Modified: " . $file_last_modified, true);
    }
    if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($filename)))
    {
      header('HTTP/1.1 304 Not Modified', true);
      exit;
    }
    if (!empty($file_size))
    {
      header("Accept-Ranges: bytes");
      header("Content-Length: " . $file_size);
    }
  }

  return true;
}

function sendSaveAsFileHeaderIfNeeded()
{
  $downloadfilename = isset($_GET['sia']) ? $_GET['sia'] : (isset($_GET['down']) ? $_GET['down'] : basename($_GET['src']));

  if (!empty($downloadfilename))
  {
    header('Content-Disposition: ' . (isset($_GET['down']) ? 'attachment' : 'inline') . '; filename="' . $downloadfilename . '"');
  }

  return true;
}

$_GET['f'] = $_SERVER['QUERY_STRING'];

if (($ap = strpos($_GET['f'], "&")) !== false)
{
  $_GET['f'] = substr($_GET['f'], 0, $ap);
}

if (!empty($_GET['f']))
{
  $f = $_GET['f'];

  $conf = base64_decode($f);
  if ($use_serializable)
  {
    $conf = @unserialize($conf);
  }
  else
  {
    parse_str($conf, $conf);
  }

  if (!empty($conf))
  {
    unset($_GET['f']);

    generateThumb($conf);
  }
}
else
{
  generateThumb(array());
}
exit;
