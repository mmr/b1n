<?
/* $Id: data.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $ */
function b1n_getVar($var, &$dest, $default='')
{
  $dest = $default;

  $ret = isset($_REQUEST[$var]);

  if($ret)
  {
    $dest = $_REQUEST[$var];
  }

  return $ret;
}

function b1n_retMsg(&$ret_msgs, $status, $msg)
{
  array_push($ret_msgs, array('status' => $status, 'msg' => $msg));
}

function b1n_cmp($str1, $str2)
{
  return (strcmp($str1, $str2) === 0);
}

function b1n_cleanArray($a = array())
{
  if(is_array($a))
  {
    foreach($a as $k=>$v)
    {
      $a[$k] = '';
    }
  }
  else
  {
    $a = array();
  }

  return $a;
}

function b1n_inBd($var)
{
  if(is_null($var))
  {
    return '';
  }

  $var = trim($var);
  // SQL Server ('')
  return str_replace("'", "''", $var);
  // Common
  #return addslashes($var);
}

function b1n_inHtml($var)
{
  return nl2br(htmlspecialchars($var, ENT_QUOTES));
}

function b1n_inHtmlLimit($var)
{
  return b1n_inHtml((strlen($var) <= b1n_LIST_MAX_CHARS)? $var : substr($var, 0, b1n_LIST_MAX_CHARS) . '...');
}
?>
