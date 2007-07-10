<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
define('b1n_PATH_LIB',  'lib');
define('b1n_PATH_COMMON_LIB',  'lib');

// Libs
require(b1n_PATH_LIB . '/sqllink.lib.php');
require(b1n_PATH_LIB . '/config.lib.php');
require(b1n_PATH_LIB . '/data.lib.php');
require(b1n_PATH_LIB . '/formatdata.lib.php');
require(b1n_PATH_LIB . '/menu.lib.php');

// Headers
header('Expires: Wed, 06 Aug 2003 15:50:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  // HTTP/1.1
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Cache-Control: private');
  // HTTP/1.0
header('Pragma: no-cache');

// Iniciando Sessao
session_start();

// Conexao com o Banco de Dados 
$sql = new b1n_sqlLink();

// Mensagens de retorno
$ret_msgs = array();

// Menu
$menu = b1n_buildMenu($sql);

// Pagina (p)
b1n_getVar('p', $d['p']);

// Area (are_id)
b1n_getVar('are_id', $d['are_id']);
?>
<html>
<head>
<title>Pediatria (São Paulo)</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" link='#026e5a' vlink='#026e5a' alink='#026e5a'>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td rowspan="2"><img src="img/top_left.gif" width="129" height="80"></td>
    <td background="img/back_top.gif"><img src="img/shim.gif" width="15" height="40"></td>
    <td width="100%" background="img/back_top.gif"><img src="img/logo.gif" width="240" height="40"></td>
    <td rowspan="2"><img src="img/top_center.jpg" width="80" height="80"></td>
    <td background="img/back_top.gif"><img src="img/shim.gif" width="10" height="40"></td>
    <td rowspan="2"><img src="img/top_right.gif" width="145" height="80"></td>
  </tr>
  <tr> 
    <td background="img/back_top2.gif"><img src="img/shim.gif" width="15" height="40"></td>
    <td background="img/back_top2.gif"><a href='<?= b1n_URL ?>'><img src="img/home.gif" width="17" height="10" alt='home' border='0'></a> 
      <span class="idiomas">
<?      
// Idiomas
if(is_array($menu['idiomas']))
{
  foreach($menu['idiomas'] as $idi)
  {
    echo "&nbsp;|&nbsp;<a href='".b1n_URL."?idi_id=".$idi['idi_id']."&amp;p=".$d['p']."'>".$idi['idi_nome']."</a>";
  }
}
?>
      </span>
    </td>
    <td><img src="img/shim.gif" width="10" height="40"></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td background="img/back_verde.gif" height="1" valign="top"> 
      <table width="129" border="0" cellspacing="0" cellpadding="0">
        <tr> 
          <td width="13" height="18"><img src="img/shim.gif" width="13" height="18"></td>
        </tr>
<?
if(is_array($menu['areas']))
{
  foreach($menu['areas'] as $are)
  {
    if(empty($are['are_codigo']))
    {
      $are['are_codigo'] = 'area';
    }
?>
        <tr>
          <td width="13" height="18"></td>
          <td width='116' height='18' class='menu'>
<?          
    if(b1n_cmp($are['are_codigo'], 'busca_simples'))
    {
?>
            <form action='<?= b1n_URL ?>' method='POST' name='x'>
              <input type='hidden' name='p' value='busca_simples'>
              <input type='text' name='busca' size='10' maxlength='15'>
              <a href='javascript:document.x.submit()'><img src="img/busca.gif" border='0'></a>
            </form>
<?
      continue;
    }

?>
          
          <a class='menu' href='<?= b1n_URL . '?p=' . $are['are_codigo'] . '&amp;are_id=' . $are['are_id'] ?>'><?= $are['are_nome'] ?></a>
          
          
          
          </td>
        </tr>
<?
    if($are['are_separador'])
    {
?>
        <tr>
          <td width="13" height="18"><img src="img/menu_img1.gif" width="13" height="18"></td>
          <td width="116" height="18"><img src="img/menu_img2.gif" width="116" height="18"></td>
        </tr>
<?
    }
  }
}
?>
      </table>
    </td>
    <td width="100%" valign="top"> 
<?
if(empty($d['p']))
{
  require(b1n_PATH_INC . '/home.inc.php');
}
else
{
  require(b1n_PATH_INC . '/conteudo.inc.php');
}
?>
    </td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="img/back_verde.gif"><img src="img/shim.gif" width="129" height="1"></td>
    <td background="img/back_bottom.gif" width="100%"><img src="img/bottom.gif" width="552" height="14"></td>
  </tr>
</table>
</body>
</html>
<br />
<br />
<a href='admin/'>Admin</a> | <a href='destruir.php'>Destruir Sessao</a>
