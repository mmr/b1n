<?
// Prog & Author
define('b1n_VERSION',     '0.9');
define('b1n_PROGNAME',    'Pediatria (S&atilde;o Paulo) Admin (v'.b1n_VERSION.')');
define('b1n_AUTHOR_MAIL', 'mmr@caboverde.com.br');
define('b1n_AUTHOR_NAME', 'Marcio Ribeiro');

// PATHs
define('b1n_PATH_INC',  'include');
define('b1n_PATH_COMMON_INC', '../include');
define('b1n_PATH_CSS',  '../css');
define('b1n_PATH_JS',   '../js');

// Misc
define('b1n_UPLOAD_DIR_CAPA',         '../upload/capa');
define('b1n_UPLOAD_DIR_ARTIGO_PDF',   '../upload/pdf');
define('b1n_UPLOAD_DIR_ARTIGO_HTML',  '../upload/html');

define('b1n_URL',       $_SERVER['SCRIPT_NAME']);
define('b1n_DEBUG',     false);
define('b1n_PAGE_INC',  20);
define('b1n_FIZZLES',   666);
define('b1n_SUCCESS',   69);
define('b1n_DESC_MAX_CHARS',        25);
define('b1n_DEFAULT_SELECT_SIZE',   5);
define('b1n_DEFAULT_SELECT_RATIO',  0.2);
define('b1n_SECRETKEY_FILE',    'secured/secretkey.php');
define('b1n_SYSTEMADMIN_EMAIL', 'mmr@caboverde.com.br');
define('b1n_SYSTEMADMIN_NAME',  'Marcio Ribeiro');

// RegLib
define('b1n_PATH_REGINC',     'include/reg');
define('b1n_PATH_REGLIB',     'lib/reg');
define('b1n_LIST_MAX_CHARS',  35);
define('b1n_DEFAULT_SIZE',    35);
define('b1n_DEFAULT_MAXLEN',  200);
define('b1n_DEFAULT_ROWS',    5);
define('b1n_DEFAULT_COLS',    35);
define('b1n_DEFAULT_QUANTITY', 20);
define('b1n_DEFAULT_DATE_START_YEAR', 1900);
define('b1n_DEFAULT_DATE_INC', 7);
define('b1n_DEFAULT_DATE_DEC', 7);

define('b1n_MSG_ACCESS_DENIED', 
       'Voc&ecirc; n&atilde;o tem permiss&atilde;o para executar essa opera&ccedil;&atilde;o.<br />Para mais informa&ccedil;&otilde;es, contate o administrador do sistema<br /><a href="mailto:' . b1n_SYSTEMADMIN_EMAIL . '">' . b1n_SYSTEMADMIN_NAME . '</a>');
?>
