<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
{
  die('Turn magic_quote_gpc and magic_quote_runtime off.');
}

define('b1n_PATH_LIB',  'lib');
require(b1n_PATH_LIB . '/config.lib.php');

// Headers
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

session_start();
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
      <a href='<?= b1n_URL ?>'><img src='../img/logo_adm.gif' alt='Pediatria (São Paulo)' width='256px' height='40px' /></a>
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
    <td style='width:50%'><a href='index_pub.php'>Publica&ccedil;&otilde;es</a></td>
    <td>|</td>
    <td style='width:50%'><a href='index_cms.php'>Site</a></td>
  </tr>
</table>
<div class='rodape'><a href='http://www.caboverde.com.br/' rel='_blank'><img src='../img/copy.gif' alt='CaboVerde' width='549px' height='14px' /></a></div>
<script type='text/javascript' src='<?= b1n_PATH_JS ?>/targets.js'></script>
</body>
</html>
