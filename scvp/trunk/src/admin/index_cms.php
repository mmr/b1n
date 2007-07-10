<?
// $Id: index_cms.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $

if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
{
  die('Turn magic_quote_gpc and magic_quote_runtime off.');
}

define('b1n_PATH_LIB',  'lib');
define('b1n_PATH_COMMON_LIB',  '../lib');

// Libs
require(b1n_PATH_COMMON_LIB . '/sqllink.lib.php');
require(b1n_PATH_COMMON_LIB . '/data.lib.php');
require(b1n_PATH_LIB . '/config.lib.php');
require(b1n_PATH_LIB . '/formatdata.lib.php');
require(b1n_PATH_LIB . '/checkdata.lib.php');
require(b1n_PATH_LIB . '/permission.lib.php');
require(b1n_PATH_LIB . '/select.lib.php');
require(b1n_PATH_LIB . '/search.lib.php');
require(b1n_PATH_LIB . '/reg.lib.php');

// Headers
header('Expires: Wed, 06 Aug 2003 15:50:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  // HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
  // HTTP/1.0
header('Pragma: no-cache');

session_start();

$sql = new b1n_sqlLink();
$ret_msgs = array();
$logging  = false;

b1n_getVar('page',    $page);
b1n_getVar('action0', $action0);
b1n_getVar('action1', $action1);
b1n_getVar('filtro',  $filtro);

$action0 = strtolower(trim($action0));
$action1 = strtolower(trim($action1));

if(!b1n_isLogged() && !b1n_doLogin($sql, $ret_msgs, $logging))
{
  $inc = b1n_PATH_INC . '/login.inc.php';
}
else
{
  switch($page)
  {
  case 'usuario':
  case 'grupo':
  case 'idioma':
  case 'area':
  case 'noticia':
  case 'link':
    $inc = $page . '/index.php';
    break;
  case 'logout':
    b1n_logOut();
    $inc = b1n_PATH_INC . '/login.inc.php';
    break;
  default:
    if($logging)
    {
      header('Location: ' . b1n_URL);
      exit();
    }
    else
    {
      $inc = b1n_PATH_INC . '/home.inc.php';
    }
  }
}
echo "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' '/comum/dtd/xhtml11.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' >
<head>
  <title><?= b1n_PROGNAME ?> - Admin</title>
  <link rel='stylesheet' href='<?= b1n_PATH_CSS ?>/scvp.css' />
</head>
<body>
<table class='topo'>
  <tr>
    <td class='topo'>
      <a href='index.php'><img src='../img/logo_adm.gif' alt='Pediatria (São Paulo)' width='256px' height='40px' /></a>
    </td>
    <td class='topo_sys_nome'>
      Administra&ccedil;&atilde;o Site (v<?= b1n_VERSION ?>)
    </td>
<?
// Current User
if(isset($_SESSION['user']))
{
  echo '<td class="topo_usr_nome">'.$_SESSION['user']['usr_nome'].'</td>';
}
?>
  </tr>
</table>
<table class='menu'>
  <tr>
    <td>
      <a href='<?= b1n_URL . '?page=usuario' ?>'>Usu&aacute;rio</a>
    </td>
    <td>|</td>
    <td>
      <a href='<?= b1n_URL . '?page=grupo' ?>'>Grupo</a>
    </td>
    <td>|</td>
    <td>
      <a href='<?= b1n_URL . '?page=idioma' ?>'>Idioma</a>
    </td>
    <td>|</td>
    <td>
      <a href='<?= b1n_URL . '?page=area' ?>'>&Aacute;rea</a>
    </td>
    <td>|</td>
    <td>
      <a href='<?= b1n_URL . '?page=logout' ?>'>LogOut</a>
    </td>
  </tr>
</table>
<?
if(sizeof($ret_msgs))
{
?>
<div><br /><br /></div>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box'>Mensagens do Sistema</td>
        </tr>
        <tr>
          <td>
            <? require(b1n_PATH_INC . '/ret.inc.php'); ?>
          </td>
        </tr>
        <tr>
          <td class='box'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?   
}
require($inc);
?>
<div class='rodape'><a href='http://www.caboverde.com.br/' rel='_blank'><img src='../img/copy.gif' alt='CaboVerde' width='549px' height='14px' /></a></div>
<script type='text/javascript' src='<?= b1n_PATH_JS ?>/targets.js'></script>
</body>
</html>
