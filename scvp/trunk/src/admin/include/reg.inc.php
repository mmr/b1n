<?
// $Id: reg.inc.php,v 1.1.1.1 2004/01/25 15:18:51 mmr Exp $

require(b1n_PATH_REGLIB . '/' . $page . '.lib.php');

$Module = ucfirst($page);
$MODULE = strtoupper($Module);

eval("
\$perm = array(
  'add'     => b1n_FUNC_ADD_$MODULE,
  'list'    => b1n_FUNC_LIST_$MODULE,
  'change'  => b1n_FUNC_CHANGE_$MODULE,
  'activate'=> b1n_FUNC_ACTIVATE_$MODULE,
  'delete'  => b1n_FUNC_DELETE_$MODULE,
  'view'    => b1n_FUNC_VIEW_$MODULE);");

$func = array(
  'add'     => 'b1n_regAdd'     . $Module,
  'check'   => 'b1n_regCheck'   . $Module,
  'change'  => 'b1n_regChange'  . $Module,
  'activate'=> 'b1n_regToggleActivation'  . $Module,
  'delete'  => 'b1n_regDelete'  . $Module,
  'view'    => 'b1n_regView'    . $Module,
  'load'    => 'b1n_regLoad'    . $Module,
  'checkChange' => 'b1n_regCheckChange' . $Module,
  'checkDelete' => 'b1n_regCheckDelete' . $Module);

$functions = array();

unset($Module, $MODULE);

switch($action0)
{
case 'add':
  if(b1n_havePermission($perm[$action0]))
  {
    if($func['check']($sql, $ret_msgs, $reg_data, $reg_config))
    {
      if($func[$action0]($sql, $ret_msgs, $reg_data, $reg_config))
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
  if(b1n_havePermission($perm[$action0]))
  {
    if($func['checkChange']($sql, $ret_msgs, $reg_data, $reg_config))
    {
      if($func[$action0]($sql, $ret_msgs, $reg_data, $reg_config))
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
  if(b1n_havePermission($perm[$action0]))
  {
    if($func[$action0]($sql, $ret_msgs, $reg_config['ID']['db'], $reg_data['id'], $activate_field))
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
  if(b1n_havePermission($perm[$action0]))
  {
    if($func['checkDelete']($sql, $ret_msgs, $reg_data, $reg_config))
    {
      if($func[$action0]($sql, $ret_msgs, $reg_data, $reg_config))
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
  if(b1n_havePermission($perm['view']) || 
     b1n_havePermission($perm['change']))
  {
    if(!$func[$action0]($sql, $ret_msgs, $reg_data, $reg_config))
    {
      $action1 = 'list';
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, b1n_MSG_ACCESS_DENIED);
  }
}

unset($func);

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

switch($action1)
{
case 'add':
case 'change':
case 'view':
  $colspan = 3;
  if(b1n_havePermission($perm[$action1]))
  {
    $file = $page . '/' . $action1 . '.php';
    if(is_readable($file))
    {
      require($file);
    }
    else
    {
      require(b1n_PATH_REGINC . '/' . $action1 . '.inc.php');
    }
    break;
  }
  else
  {
    $already_denied = true;
    require(b1n_PATH_INC . '/denied.inc.php');
  }
default:
  if(b1n_havePermission($perm['list']))
  {
    if(b1n_havePermission($perm['view']))
    {
      $functions += array(
        'Visualiza' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=view&amp;');
    }

    if(b1n_havePermission($perm['change']))
    {
      $functions += array(
        'Altera' => b1n_URL . '?page='.$page.'&amp;action0=load&amp;action1=change&amp;');
    }

    if(b1n_havePermission($perm['activate']))
    {
      $functions += array(
        'Ativa/Desativa' => b1n_URL . '?page='.$page.'&amp;action0=activate&amp;');
    }

    $file = $page . '/list.php';
    if(is_readable($file))
    {
      require($file);
    }
    else
    {
      require(b1n_PATH_REGINC . '/list.inc.php');
    }
  }
  else
  {
    if(!$already_denied)
    {
      require(b1n_PATH_INC . '/denied.inc.php');
    }
  }
  break;
}
unset($perm);
?>
