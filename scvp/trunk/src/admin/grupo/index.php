<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
$page_title = 'Grupo';

$d    = date('Y');
$dinc = $d + b1n_DEFAULT_DATE_INC;

// Configuration Hash
$reg_config = array(
  'ID' => array(
    'reg_data'  => 'id',
    'db'      => 'grp_id',
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
    'reg_data'  => 'grp_nome',
    'db'    => 'grp_nome',
    'check' => 'unique',
    'type'  => 'text',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN,
      'table'   => $page),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Desc' => array(
    'reg_data'  => 'grp_desc',
    'db'    => 'grp_desc',
    'check' => 'none',
    'type'  => 'text',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => false),
  'Usu&aacute;rios' => array(
    'reg_data'  => 'usuarios',
    'db'    => 'none',
    'check' => 'none',
    'type'  => 'select',
    'extra' => array(
      'seltype' => 'fk',
      'table'   => 'usuario',
      'text'    => 'usr_login + \' (\' + usr_nome + \')\'',
      'value'   => 'usr_id',
      'name'    => 'usuarios[]',
      'params'  => array(
        'multiple' => 'multiple')),
    'search'  => false,
    'select'  => false,
    'load'    => true,
    'mand'    => false),
  'Fun&ccedil;&otilde;es' => array(
    'reg_data'  => 'funcoes',
    'db'    => 'none',
    'check' => 'none',
    'type'  => 'select',
    'extra' => array(
      'seltype' => 'fk',
      'table'   => 'funcao',
      'text'    => 'fnc_nome',
      'value'   => 'fnc_id',
      'name'    => 'funcoes[]',
      'params'  => array(
        'multiple' => 'multiple')),
    'search'  => false,
    'select'  => false,
    'load'    => true,
    'mand'    => false),
  'Data de Expira' => array(
    'reg_data'  => 'grp_exp_dt',
    'db'    => 'grp_exp_dt',
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

$activate_field = 'grp_ativo';

// getVars from $_REQUEST and put them in $reg_data hash
$reg_data = b1n_regExtract($reg_config);

require(b1n_PATH_INC . '/reg.inc.php');
?>
