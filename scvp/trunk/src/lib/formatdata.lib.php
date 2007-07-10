<?
// $Id: formatdata.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
function b1n_formatDateShow($var)
{
  if(ereg('([0-9]{4})-([0-9]{2})-([0-9]{2})[[:space:]]([0-9]{2}):([0-9]{2}):([0-9]{2})', $var, $match))
  {
    list(,$y, $m, $d, $h, $i, $s) = $match;
    return "$d/$m/$y";
  }
  return false;
}
?>
