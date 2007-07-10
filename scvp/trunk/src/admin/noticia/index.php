<?
// $Id: index.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $
require(b1n_PATH_REGLIB . '/' . $page . '.lib.php');

// monta uma estrutura com os dados da busca.
b1n_getVar('id',  $reg_data['id']);
b1n_getVar('ids', $reg_data['ids']);

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
  b1n_getVar('not_dt', $reg_data['not_dt']);
  foreach($idiomas as $idi)
  {
    b1n_getVar('not_nome_'.$idi['idi_id'], $reg_data['not_nome_'.$idi['idi_id']]);
    b1n_getVar('not_desc_'.$idi['idi_id'], $reg_data['not_desc_'.$idi['idi_id']]);
    b1n_getVar('not_cont_'.$idi['idi_id'], $reg_data['not_cont_'.$idi['idi_id']]);
  }
}

$page_title = 'Not&iacute;cia';

// Mexendo no BD
switch($action0)
{
case 'add':
  if(b1n_havePermission(b1n_FUNC_ADD_NOTICIA))
  {
    if(b1n_regCheckNoticia($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regAddNoticia($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_NOTICIA))
  {
    if(b1n_regCheckChangeNoticia($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regChangeNoticia($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_ACTIVATE_NOTICIA))
  {
    if(b1n_regToggleActivationNoticia($sql, $ret_msgs, $reg_data['id']))
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
  if(b1n_havePermission(b1n_FUNC_DELETE_NOTICIA))
  {
    if(b1n_regCheckDeleteNoticia($sql, $ret_msgs, $reg_data))
    {
      if(b1n_regDeleteNoticia($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_VIEW_NOTICIA) ||
     b1n_havePermission(b1n_FUNC_CHANGE_NOTICIA))
  {
    if(!b1n_regLoadNoticia($sql, $ret_msgs, $reg_data))
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
  if(b1n_havePermission(b1n_FUNC_ADD_NOTICIA))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_NOTICIA))
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
  if(b1n_havePermission(b1n_FUNC_CHANGE_NOTICIA))
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
  if(b1n_havePermission(b1n_FUNC_LIST_NOTICIA))
  {
    if(b1n_havePermission(b1n_FUNC_VIEW_NOTICIA))
    {
      $functions += array(
        'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_CHANGE_NOTICIA))
    {
      $functions += array(
        'Altera' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;');
    }

    if(b1n_havePermission(b1n_FUNC_ACTIVATE_NOTICIA))
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
