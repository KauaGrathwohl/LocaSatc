<?php
App::uses('AppHelper', 'View/Helper');

class UtilHelper extends AppHelper
{
  public function url($url = "", $comp = "")
  {
    if (strlen($comp) > 0)
    {
      $comp = $comp . "?";
    }

    $v = new Validation();
    if ($v->email($url))
    {
      return "mailto:" . $url;
    }
    elseif (!strstr($url, "http://") && !strstr($url, "https://"))
    {
      return $comp . "http://" . $url;
    }

    return $comp . $url;
  }

  public function time_offset($d, $f = 'h:ma M. j Y T')
  {
    $o = time() - strtotime($d);
    switch (true)
    {
      case ($o <= 1): return __('agora');
      case ($o < 20): return $o . ' ' . __('segundos');
      case ($o < 40): return __('meio minuto');
      case ($o < 60): return __('quase um minuto');
      case ($o <= 90): return '1 ' . __('minuto');
      case ($o <= 59 * 60): return round($o / 60) . ' ' . __('minutos');
      case ($o <= 60 * 60 * 1.5): return '1 ' . __('hora');
      case ($o <= 60 * 60 * 24): return round($o / 60 / 60) . ' ' . __('horas');
      case ($o <= 60 * 60 * 24 * 1.5): return '1 ' . __('dia');
      case ($o < 60 * 60 * 24 * 7): return round($o / 60 / 60 / 24) . ' ' . __('dias');
      case ($o <= 60 * 60 * 24 * 9): return '1 ' . __('semana');
      default:return $this->date($d);
    }
  }

  public function date($d = null, $p = "d/m/Y")
  {
    return date($p, strtotime($d));
  }

  public function datetime($d = null, $p = "d/m/Y H:i")
  {
    return $this->date($d, $p);
  }

  public function autop($pee, $br = true)
  {
    return Util::autop($pee, $br);
  }

  public function corta($texto = "", $n = 10, $cmpl = "...")
  {
    return Util::truncate($texto, $n, $cmpl);
  }

  public function stripLatin($v)
  {
    return Util::stripLatin($v);
  }

  public function slug($str = null, $id = null)
  {
    return Util::slug($str, $id);
  }
}
