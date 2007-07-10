<?
//$Id: index.php,v 1.2 2004/05/09 23:29:32 mmr Exp $

// Defines
define('b1n_PATH_LIB', 'lib');
define('b1n_PATH_INC', 'include');
define('b1n_DEBUG', false);
define('b1n_URL',   $_SERVER['SCRIPT_NAME']);

// Libs
require(b1n_PATH_LIB . '/sqllink.lib.php');
require(b1n_PATH_LIB . '/data.lib.php');
require(b1n_PATH_LIB . '/calc.lib.php');

// Headers
header('Expires: Wed, 06 Aug 2003 15:50:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  // HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
  // HTTP/1.0
header('Pragma: no-cache');

$sql = new b1n_sqlLink(b1n_PATH_LIB . '/db_conf.lib.php');

b1n_getVar('p', $d['p']);
b1n_getVar('ini', $d['ini']);
b1n_getVar('fin', $d['fin']);

switch($d['p']){
case 'inf':
case 'calc':
  $inc = $d['p'];
  break;
case 'graph':
  require(b1n_PATH_INC.'/'.$d['p'].'.php');
  exit;
default:
  $inc = 'calc';
}

echo "<? xml version='1.0' encoding='ISO-8859-1'?>";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' '/comum/dtd/xhtml11.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' >
<head>
  <title>MegaSena</title>
  <link rel='stylesheet' href='css/sena.css' />
</head>
<body>
<?
if(!empty($inc)){
  $inc = b1n_PATH_INC.'/'.$inc.'.php';
  if(is_readable($inc)){
    require($inc);
  }
}
?>
</body>
</html>
