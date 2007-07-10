<?
// $Id: formatdata.lib.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $
function b1n_formatDateShow($var)
{
  if(ereg('([0-9]{4})-([0-9]{2})-([0-9]{2})[[:space:]]([0-9]{2}):([0-9]{2}):([0-9]{2})', $var, $match))
  {
    list(,$y, $m, $d, $h, $i, $s) = $match;
    return "$d/$m/$y";
  }
  return false;
}

function b1n_formatDateHourShow($var)
{
  if(ereg('([0-9]{4})-([0-9]{2})-([0-9]{2})[[:space:]]([0-9]{2}):([0-9]{2})', $var, $match))
  {
    list(,$y, $m, $d, $h, $i) = $match;
    return "$d/$m/$y $h:$i";
  }
  return false;
}

function b1n_formatDateToDb($var)
{
  if(is_array($var))
  {
    return "'" . b1n_inBd($var['year'] . '-' . $var['month'] . '-' . $var['day']) . "'";
  }
  else
  {
    return 'NULL';
  }
}

function b1n_formatDateHourToDb($var)
{
  if(is_array($var))
  {
    return b1n_inBd($var['year'] . '-' . $var['month'] . '-' . $var['day'] . " " . $var['hour'] . ':' . $var['min']);
  }
  else
  {
    return 'NULL';
  }
}

function b1n_formatDateFromDb($var)
{
  $y = $m = $d = $h = $i = $s = '';
  if(ereg('([0-9]{4})-([0-9]{2})-([0-9]{2})[[:space:]]([0-9]{2}):([0-9]{2}):([0-9]{2})', $var, $match))
  {
    list(,$y, $m, $d, $h, $i, $s) = $match;
  }

  return array(
    'year'   => $y, 
    'month'  => $m,
    'day'    => $d,
    'hour'   => $h,
    'minute' => $i,
    'second' => $s);
}

function b1n_formatHour($a = array())
{
  if(is_array($a))
  {
    if(!empty($a['hour']) && !empty($a['min']))
    {
      return sprintf('%02d:%02d', $a['hour'], $a['min']);
    }
  }
}

function b1n_formatDateHour($a = array())
{
  if(is_array($a))
  {
    if(!empty($a['year'])  &&
       !empty($a['month']) &&
       !empty($a['day']))
    {
      if(empty($a['hour']))
      {
        $a['hour'] = '0';
      }

      if(empty($a['min']))
      {
        $a['min'] = '0';
      }

      return sprintf('%04d-%02d-%02d %02d:%02d', $a['year'], $a['month'], $a['day'], $a['hour'], $a['min']);
    }
  }
}

function b1n_formatDateHourFromDb($a)
{
  if($a)
  {
    list($ret['year'], $ret['month'], $ret['day']) = explode('-', strtok($a, ' '));
    list($ret['hour'],  $ret['min']) = explode(':', strtok(' '));
    return $ret;
  }
}
?>
