<?
// $Id: destruir.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
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
session_destroy();
?>
<a href='index.php'>volta</a>
