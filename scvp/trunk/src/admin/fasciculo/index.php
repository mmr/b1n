<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
require(b1n_PATH_REGLIB . '/' . $page . '.lib.php');

// monta uma estrutura com os dados da busca.
b1n_getVar('id',  $reg_data['id']);
b1n_getVar('ids', $reg_data['ids']);
b1n_getVar('fas_vol_num', $reg_data['fas_vol_num']);
b1n_getVar('fas_vol_num_sup', $reg_data['fas_vol_num_sup']);
b1n_getVar('fas_vol_ano', $reg_data['fas_vol_ano']);
b1n_getVar('fas_num',     $reg_data['fas_num']);
b1n_getVar('fas_num_sup', $reg_data['fas_num_sup']);
b1n_getVar('fas_seq_num', $reg_data['fas_seq_num']);

$page_title = 'Fasc&iacute;culo';
$functions = array();

// Mexendo no BD
switch($action0)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_FASCICULO))
  {
    if(b1n_regCheckFasciculo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regAddFasciculo($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_FASCICULO))
  {
    if(b1n_regCheckChangeFasciculo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regChangeFasciculo($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_ACTIVATE_FASCICULO))
  {
    if(b1n_regToggleActivationFasciculo($sql, $ret_msgs, $reg_data['id']))
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
  if(b1n_havePermission(b1n_FUNC_DELETE_FASCICULO))
  {
    if(b1n_regCheckDeleteFasciculo($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regDeleteFasciculo($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_VIEW_FASCICULO) || 
     b1n_havePermission(b1n_FUNC_CHANGE_FASCICULO))
  {
    if(!b1n_regLoadFasciculo($sql, $ret_msgs, $reg_data))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
  break;
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
  if(b1n_havePermission(b1n_FUNC_ADD_FASCICULO))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_FASCICULO))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_FASCICULO))
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
  if(b1n_havePermission(b1n_FUNC_LIST_FASCICULO))
  {
    if(b1n_havePermission(b1n_FUNC_LIST_ARTIGO))
    {
      $functions += array(
        'Artigos' => b1n_URL . '?page=artigo&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_VIEW_FASCICULO))
    {
      $functions += array(
        'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_CHANGE_FASCICULO))
    {
      $functions += array(
        'Altera' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_ACTIVATE_FASCICULO))
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
