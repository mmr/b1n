<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$page_title = 'Usu&aacute;rio';

$d    = date('Y');
$dinc = $d + b1n_DEFAULT_DATE_INC;

// Configuration Hash
$reg_config = array(
  'ID' => array(
    'reg_data'  => 'id',
    'db'      => 'usr_id',
    'check'   => 'none',
    'type'    => 'none',
    'search'  => false,
    'select'  => false,
    'load'    => false,
    'mand'    => false),
  'Delete IDs'  => array(
    'reg_data'  => 'ids',
    'db'    => 'none',
    'check' => 'none',
    'type'  => 'none',
    'search'  => false,
    'select'  => false,
    'load'    => false,
    'mand'    => false),
  'Nome' => array(
    'reg_data'  => 'usr_nome',
    'db'    => 'usr_nome',
    'check' => 'none',
    'type'  => 'text',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => false),
  'Login' => array(
    'reg_data'  => 'usr_login',
    'db'    => 'usr_login',
    'check' => 'unique',
    'type'  => 'text',
    'extra' => array(
      'table'   => $page,
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Senha'  => array(
    'reg_data'  => 'usr_senha',
    'db'    => 'usr_senha',
    'check' => 'none',
    'type'  => 'password',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => 32),
    'search'  => false,
    'select'  => false,
    'load'    => false,
    'mand'    => true),
  'Confirma&ccedil;&atilde;o Senha' => array(
    'reg_data'  => 'usr_senha2',
    'db'      => 'none',
    'check'   => 'none',
    'type'    => 'password',
    'extra'   => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => 32),
    'search'  => false,
    'select'  => false,
    'load'    => false,
    'mand'    => true),
  'Grupos' => array(
    'reg_data'  => 'grupos',
    'db'    => 'none',
    'check' => 'none',
    'type'  => 'select',
    'extra' => array(
      'seltype' => 'fk',
      'table'   => 'grupo',
      'text'    => 'grp_nome',
      'value'   => 'grp_id',
      'name'    => 'grupos[]',
      'params'  => array(
        'multiple' => 'multiple')),
    'search'  => false,
    'select'  => false,
    'load'    => true,
    'mand'    => false),
  'Data de Expira' => array(
    'reg_data'  => 'usr_exp_dt',
    'db'    => 'usr_exp_dt',
    'check' => 'date_hour',
    'type'  => 'select',
    'extra' => array(
      'seltype'     => 'date_hour',
      'year_start'  => $d,
      'year_end'    => $dinc),
    'search'  => false,
    'select'  => false,
    'load'    => true,
    'mand'    => false));

unset($d);
unset($dinc);

$activate_field = 'usr_ativo';

// getVars from $_REQUEST and put them in $reg_data hash
$reg_data = b1n_regExtract($reg_config);

require(b1n_PATH_INC . '/reg.inc.php');
?>
