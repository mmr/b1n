<?
/* $Id: index.php,v 1.73 2004/08/04 02:05:15 mmr Exp $ */
if(get_magic_quotes_gpc() || get_magic_quotes_runtime()){
    die('You need to turn off magic_quote_gpc and magic_quote_runtime in your php.ini');
}

define('b1n_LIBPATH', 'lib');
define('b1n_INCPATH', 'include');

require(b1n_LIBPATH . '/config.lib.php');       // General Configuration
require(b1n_LIBPATH . '/sqllink.lib.php');      // DataBase Connection
require(b1n_LIBPATH . '/permission.lib.php');   // Login / Permission Control
require(b1n_LIBPATH . '/data.lib.php');         // Get/Set Data
require(b1n_LIBPATH . '/formatdata.lib.php');   // Format  Data
require(b1n_LIBPATH . '/checkdata.lib.php');    // Check   Data
require(b1n_LIBPATH . '/search.lib.php');       // Search
require(b1n_LIBPATH . '/select.lib.php');       // <SELECT> Build
require(b1n_LIBPATH . '/reg.lib.php');          // Registries Magic
require(b1n_LIBPATH . '/debug.lib.php');        // Debug System

$sql = new sqlLink();
$inc = '';
$ret_msgs = array();
$logging  = false;
$page0_title = '';
$page1_title = '';

b1n_getVar('page0',     $page0);
b1n_getVar('page1',     $page1);
b1n_getVar('action0',   $action0);
b1n_getVar('action1',   $action1);

session_start();

header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if(!b1n_isLogged() && !b1n_doLogin($sql, $ret_msgs, $logging)){
    $inc = b1n_INCPATH . '/login.inc.php';
}
else {
    switch($page0){
    case 'admin':
    case 'itinerary':
    case 'agent':
    case 'pax':
    case 'data':
    case 'bookmark':
    case 'docs':
        $inc = $page0 . '/index.php';
        break;
    case 'init':
    case 'topmenu':
    case 'footer':
    case 'statusbar':
        $inc  = b1n_INCPATH . '/' . $page0 . '.inc.php';
        break;
    case 'logout':
        b1n_logOut($sql);
        $inc = b1n_INCPATH . '/login.inc.php';
        break;
    case 'blank':
        break;
    default:
        if($logging){
            header('Location: ' . b1n_URL);
        }
        else {
            require(b1n_INCPATH . '/frame.inc.php');
        }
        exit();
    }
}

if(!empty($inc)){
    require($inc);
    unset($inc);
}

if(isset($page1_title) && !empty($page1_title)){
    showDebug();
}
?>
