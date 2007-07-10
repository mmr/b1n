<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $
$page_title = 'Idioma';

// Configuration Hash
$reg_config = array(
  'ID' => array(
    'reg_data'  => 'id',
    'db'      => 'idi_id',
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
    'reg_data'  => 'idi_nome',
    'db'    => 'idi_nome',
    'check' => 'none',
    'type'  => 'text',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Nome no Idioma' => array(
    'reg_data'  => 'idi_nome_no_idioma',
    'db'    => 'idi_nome_no_idioma',
    'check' => 'none',
    'type'  => 'text',
    'extra' => array(
      'size'    => b1n_DEFAULT_SIZE,
      'maxlen'  => b1n_DEFAULT_MAXLEN),
    'search'  => true,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Site' => array(
    'reg_data'  => 'idi_site',
    'db'    => 'idi_site',
    'check' => 'radio',
    'type'  => 'radio',
    'extra' => array(
      'options' => array(
        'Sim' => 1,
        'Não' => 0)),
    'search'  => false,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Padr&atilde;o Pub' => array(
    'reg_data'  => 'idi_padrao_pub',
    'db'    => 'idi_padrao_pub',
    'check' => 'radio',
    'type'  => 'radio',
    'extra' => array(
      'options' => array(
        'Sim' => 1,
        'Não' => 0)),
    'search'  => false,
    'select'  => true,
    'load'    => true,
    'mand'    => true),
  'Padr&atilde;o Site' => array(
    'reg_data'  => 'idi_padrao_site',
    'db'    => 'idi_padrao_site',
    'check' => 'radio',
    'type'  => 'radio',
    'extra' => array(
      'options' => array(
        'Sim' => 1,
        'Não' => 0)),
    'search'  => false,
    'select'  => true,
    'load'    => true,
    'mand'    => true));

$activate_field = 'idi_ativo';

// getVars from $_REQUEST and put them in $reg_data hash
$reg_data = b1n_regExtract($reg_config);

require(b1n_PATH_INC . '/reg.inc.php');
?>
