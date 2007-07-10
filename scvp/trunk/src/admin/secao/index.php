<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
$page_title = 'Se&ccedil;&atilde;o';

$reg_config = array(
  'ID' => array(
    'reg_data'  => 'id',
    'db'      => 'sec_id',
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
    'mand'    => false));

$activate_field = 'sec_ativo';

switch($action1)
{
case 'add':
case 'change':
case 'view':
  if(isset($_REQUEST['sec_nome']))
  {
    $reg_config += array(
      'Nome' => array(
        'reg_data'  => 'sec_nome',
        'db'    => 'sec_nome',
        'check' => 'none',
        'type'  => 'text',
        'extra' => array(
          'size'    => b1n_DEFAULT_SIZE,
          'maxlen'  => b1n_DEFAULT_MAXLEN),
        'load'    => true,
        'mand'    => false),
      'Desc' => array(
        'reg_data'  => 'sec_desc',
        'db'    => 'sec_desc',
        'check' => 'none',
        'type'  => 'text',
        'extra' => array(
          'size'    => b1n_DEFAULT_SIZE,
          'maxlen'  => b1n_DEFAULT_MAXLEN),
        'load'    => true,
        'mand'    => false));
    break;
  }
  $idiomas = $sql->sqlQuery('
    SELECT
      idi_id, idi_nome, idi_padrao_pub
    FROM
      idioma
    WHERE
      idi_ativo = 1
    ORDER BY
      idi_padrao_pub DESC, idi_nome');

  if(is_array($idiomas) && sizeof($idiomas))
  {
    foreach($idiomas as $idi)
    {
      $reg_config[$idi['idi_nome'] . ' - Nome'] = array(
        'reg_data'  => 'sec_nome_' . $idi['idi_id'],
        'db'    => 'sec_nome',
        'check' => 'none',
        'type'  => 'text',
        'extra' => array(
          'size'    => b1n_DEFAULT_SIZE,
          'maxlen'  => b1n_DEFAULT_MAXLEN),
        'load'    => true,
        'mand'    => $idi['idi_padrao_pub']);

      $reg_config[$idi['idi_nome'] . ' - Desc'] = array(
        'reg_data'  => 'sec_desc_' . $idi['idi_id'],
        'db'    => 'sec_desc',
        'check' => 'none',
        'type'  => 'text',
        'extra' => array(
          'size'    => b1n_DEFAULT_SIZE,
          'maxlen'  => b1n_DEFAULT_MAXLEN),
        'search'  => false,
        'select'  => false,
        'load'    => true,
        'mand'    => false);
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'N&atilde;o h&aacute; idiomas cadastrados.<br />Cadastre algum idioma e volte.');
    $reg_config = array();
  }
  break;
}

$reg_data = b1n_regExtract($reg_config);

// getVars from $_REQUEST and put them in $reg_data hash
require(b1n_PATH_INC . '/reg.inc.php');
?>
