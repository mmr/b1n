<?
// $Id: config.lib.php,v 1.1 2004/08/04 02:04:22 mmr Exp $
define('b1n_REGLIBPATH', b1n_LIBPATH . '/reg');
define('b1n_REGINCPATH', b1n_INCPATH . '/reg');

define('b1n_VERSION', '1.1');
define('b1n_CSS', 'img/flywatch.css');
define('b1n_URL', $_SERVER['SCRIPT_NAME']);

define('b1n_USEICONS', false);
define('b1n_SUCCESS',  0);
define('b1n_FIZZLES',  666);

define('b1n_FRAMESET_MAIN_ROWS',    '72, 63, *, 50');
define('b1n_FRAMESET_MIDDLE_COLS',  '175, *');

define('b1n_DEBUG_MODE', 0);
/*
    DEBUG Modes:
    0 - Turned Off
    1 - Queries
    2 - 1 && $_SESSION
    3 - 1 && $reg_data
    4 - 1 && $reg_config
    5 - 1 && $search_config
    6 - 1 && ALL_VARS && ALL_CONSTANTS
*/

define('b1n_SYSTEMADMIN_NAME',  'Luis Nardella');
define('b1n_SYSTEMADMIN_EMAIL', 'luis@b4br.net');
//define('b1n_SYSTEMADMIN_NAME',  'Francisco A. Lyra');
//define('b1n_SYSTEMADMIN_EMAIL', 'lyra@b4br.net');
?>
