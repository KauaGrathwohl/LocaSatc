<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

@set_time_limit(256);
@ini_set("expose_php", "Off");
@ini_set("memory_limit", "256M");
@ini_set("upload_max_filesize", "192M");
@ini_set("post_max_size", "192M");
@ini_set('allow_url_fopen', 1);
@ini_set('allow_url_include', 1);

setlocale(LC_ALL, "pt_BR.UTF-8", "pt_BR");

define('APENAS_NUMEROS', '/[^0-9]/');
define('ONLY_NUMBERS', '/[^0-9]/');
define('DEFAULT_URL', str_replace('app/webroot/index.php', '', $_SERVER['PHP_SELF']));

$useragent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "Desktop";
define('IS_MOBILE', preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)));

/* CONFIG ADICIONAL PARA SEPARAR O CACHE MOBILE DO DESKTOP */
if (defined("IS_MOBILE") && !!IS_MOBILE)
{
  Configure::write('Cache.viewPrefix', 'mobile_');
}

// Load Composer autoload.
require APP . 'Vendor' . DS . 'autoload.php';

// Remove and re-prepend CakePHP's autoloader as Composer thinks it is the
// most important.
// See: http://goo.gl/kKVJO7
spl_autoload_unregister(array('App', 'load'));
spl_autoload_register(array('App', 'load'), true, true);

function fz($n, $z = 2)
{
  $z = (strlen($n) < $z ? $z : strlen($n));

  for ($i = 0; $i < $z; $i++)
  {
    $n = "0" . $n;
  }

  $n = substr($n, ($z * -1));

  return $n;
}

function n($p)
{
  return number_format($p, 2, ",", ".");
}

function __is_md5($md5)
{
  return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}

function __is_url($url = null)
{
  return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function is_valid_callback($subject)
{
  $identifier_syntax = '/^[$_\p{L}][$_\p{L}\p{Mn}\p{Mc}\p{Nd}\p{Pc}\x{200C}\x{200D}]*+$/u';

  $reserved_words = array('break', 'do', 'instanceof', 'typeof', 'case',
    'else', 'new', 'var', 'catch', 'finally', 'return', 'void', 'continue',
    'for', 'switch', 'while', 'debugger', 'function', 'this', 'with',
    'default', 'if', 'throw', 'delete', 'in', 'try', 'class', 'enum',
    'extends', 'super', 'const', 'export', 'import', 'implements', 'let',
    'private', 'public', 'yield', 'interface', 'package', 'protected',
    'static', 'null', 'true', 'false');

  return preg_match($identifier_syntax, $subject)
  && !in_array(mb_strtolower($subject, 'UTF-8'), $reserved_words);
}

if (!function_exists("classnames"))
{
  function classnames($klasses = array())
  {
    $applyKlasses = array();
    if (!empty($klasses))
    {
      foreach ($klasses as $klass => $conditionOrKlass)
      {
        if (is_numeric($klass) && is_string($conditionOrKlass))
        {
          $applyKlasses[] = $conditionOrKlass;
        }
        elseif (!!$conditionOrKlass)
        {
          $applyKlasses[] = $klass;
        }
      }
    }

    return implode(" ", $applyKlasses);
  }
}

if (!function_exists('str_getcsv'))
{
  function str_getcsv($input, $delimiter = ",", $enclosure = '"', $escape = "\\")
  {
    $fiveMBs = 5 * 1024 * 1024;

    $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
    fputs($fp, $input);
    rewind($fp);

    $data = fgetcsv($fp, 1000, $delimiter, $enclosure); //  $escape only got added in 5.3.0

    fclose($fp);

    return $data;
  }
}

if (!function_exists('str_putcsv'))
{
  function str_putcsv($row, $delimiter = ',', $enclosure = '"', $eol = "\n")
  {
    $fiveMBs = 5 * 1024 * 1024;

    static $fp = false;
    if ($fp === false)
    {
      $fp = fopen("php://temp/maxmemory:$fiveMBs", 'r+');
    }
    else
    {
      rewind($fp);
    }

    if (fputcsv($fp, $row, $delimiter, $enclosure) === false)
    {
      return false;
    }

    rewind($fp);
    $csv = fgets($fp);

    if ($eol != PHP_EOL)
    {
      $csv = substr($csv, 0, (0 - strlen(PHP_EOL))) . $eol;
    }

    return $csv;
  }
}

if (!function_exists('array_values_recursive'))
{
  function array_values_recursive($array)
  {
    $temp = array();
    foreach ($array as $key => $value)
    {
      if (is_numeric($key))
      {
        $temp[] = is_array($value) ? array_values_recursive($value) : $value;
      }
      else
      {
        $temp[$key] = is_array($value) ? array_values_recursive($value) : $value;
      }
    }
    return $temp;
  }
}

function mysql_escape_mimic($data)
{
  if (!isset($data) or empty($data))
  {
    return '';
  }

  if (is_numeric($data))
  {
    return $data;
  }

  $non_displayables = array(
    '/%0[0-8bcef]/', // url encoded 00-08, 11, 12, 14, 15
    '/%1[0-9a-f]/',  // url encoded 16-31
    '/[\x00-\x08]/', // 00-08
    '/\x0b/',        // 11
    '/\x0c/',        // 12
    '/[\x0e-\x1f]/', // 14-31
  );
  foreach ($non_displayables as $regex)
  {
    $data = preg_replace($regex, '', $data);
  }

  $data = str_replace("'", "''", $data);
  return $data;
}

if (!function_exists('mysql_escape_string'))
{
  function mysql_escape_string($data)
  {
    return mysql_escape_mimic($data);
  }
}

/* INFLECTIONS */
$uninflectedPlural = array(
  "aovivo",
  "admin",
  "faq",
);

$irregularPlural = array(
  'cao' => 'caes',
  'pao' => 'paes',
  'mao' => 'maos',
  'noticia' => 'noticias',
  'categoria' => 'categorias',
  'alemao' => 'alemaes',
  'excecao' => 'excecoes',
  'imovel' => 'imoveis',
  'indicador' => 'indicadores',
  'superficie' => 'superficies',
  'cor' => 'cores',
  'promocao' => 'promocoes',
  'inscricao' => 'inscricoes',
  'opiniao' => 'opinioes',
  'autor' => 'autores',
);

$uninflectedSingular = $uninflectedPlural;

$irregularSingular = array_flip($irregularPlural);

Inflector::rules('singular', array('irregular' => $irregularSingular, 'uninflected' => $uninflectedSingular));

Inflector::rules('plural', array('irregular' => $irregularPlural, 'uninflected' => $uninflectedPlural));

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

CakePlugin::loadAll();

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *    'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *    'MyCacheFilter' => array('prefix' => 'my_cache_'), //  will use MyCacheFilter class from the Routing/Filter package in your app with settings array.
 *    'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 *    array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *    array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
  'AssetDispatcher',
  'CacheDispatcher',
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');

CakeLog::config('default', array(
  'engine' => 'File',
));
CakeLog::config('debug', array(
  'engine' => 'File',
  'types' => array('notice', 'info', 'debug'),
  'file' => 'debug',
));
CakeLog::config('error', array(
  'engine' => 'File',
  'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
  'file' => 'error',
));
