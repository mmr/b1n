<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
require(b1n_PATH_REGLIB . '/' . $page . '.lib.php');

// monta uma estrutura com os dados da busca.
b1n_getVar('id',  $reg_data['id']);
b1n_getVar('ids', $reg_data['ids']);

b1n_getVar('are_ordem',     $reg_data['are_ordem']);
b1n_getVar('are_separador', $reg_data['are_separador']);

$query = '
  SELECT
    idi_id, idi_nome, idi_padrao_site
  FROM
    idioma
  WHERE
    idi_ativo = 1 AND
    idi_site = 1
  ORDER BY
    idi_padrao_site DESC, idi_nome';

$idiomas = $sql->sqlQuery($query);

if(is_array($idiomas) && sizeof($idiomas))
{
  foreach($idiomas as $idi)
  {
    b1n_getVar('are_nome_'.$idi['idi_id'], $reg_data['are_nome_'.$idi['idi_id']]);
    b1n_getVar('are_cont_'.$idi['idi_id'], $reg_data['are_cont_'.$idi['idi_id']]);
  }
}

$page_title = '&Aacute;rea';
$functions = array();

// Mexendo no BD
switch($action0)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_AREA))
  {
    if(b1n_regCheckArea($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regAddArea($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'change':
  if(b1n_havePermission(b1n_FUNC_CHANGE_AREA))
  {
    if(b1n_regCheckChangeArea($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regChangeArea($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'activate':
  if(b1n_havePermission(b1n_FUNC_ACTIVATE_AREA))
  {
    if(b1n_regToggleActivationArea($sql, $ret_msgs, $reg_data['id']))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'delete':
  if(b1n_havePermission(b1n_FUNC_DELETE_AREA))
  {
    if(b1n_regCheckDeleteArea($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regDeleteArea($sql, $ret_msgs, $reg_data))
      {
        $action1 = 'list';
      }
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
case 'load':
  if(b1n_havePermission(b1n_FUNC_VIEW_AREA) || 
     b1n_havePermission(b1n_FUNC_CHANGE_AREA))
  {
    if(!b1n_regLoadArea($sql, $ret_msgs, $reg_data))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
}

// Mensagens (sucesso/falha)
if(sizeof($ret_msgs))
{
?>
<div><br /><br /></div>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box'>Mensagens do Sistema</td>
        </tr>
        <tr>
          <td>
            <? require(b1n_PATH_INC . '/ret.inc.php'); ?>
          </td>
        </tr>
        <tr>
          <td class='box'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<?   
}

$already_denied = false;
$functions = array();

// Mostrando Form na Tela
switch($action1)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_AREA))
  {
    require_once($page . '/add.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
case 'change':
  if(b1n_havePermission(b1n_FUNC_CHANGE_AREA))
  {
    require_once($page . '/change.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
case 'view':
  if(b1n_havePermission(b1n_FUNC_CHANGE_AREA))
  {
    require_once($page . '/view.php');
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
  break;
default:
  if(b1n_havePermission(b1n_FUNC_LIST_AREA))
  {
    if(b1n_havePermission(b1n_FUNC_VIEW_AREA))
    {
      $functions += array(
        'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_CHANGE_AREA))
    {
      $functions += array(
        'Altera' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_ACTIVATE_AREA))
    {
      $functions += array(
        'Ativa/Desativa' => b1n_URL . '?page='.$page.'&amp;action0=activate&amp;');
    }
    require_once($page . '/list.php');
  }
  else
  {
    if(!$already_denied)
    {
      require_once(b1n_PATH_INC . '/denied.inc.php');
    }
  }
  break;
}
?>
