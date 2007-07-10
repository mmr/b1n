<?
// $Id: usuario.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

function b1n_regAddUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, 'usuario', 'Usu&aacute;rio', 'b1n_regAddUsuarioPlus');
}

function b1n_regCheckUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, 'usuario', 'Usu&aacute;rio', 'b1n_regChangeUsuarioPlus');
}

function b1n_regCheckDeleteUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteUsuario($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, 'usuario', 'Usu&aacute;rio', 'Usu&aacute;rios');
}

function b1n_regLoadUsuario($sql, &$ret_msgs, &$reg_data, $reg_config)
{
  $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, 'usuario');

  $reg_data['grupos'] = array();

  $query = "SELECT grp_id FROM usr_grp WHERE usr_id = '" . b1n_inBd($reg_data['id']) . "'";
  $rs  = $sql->sqlQuery($query);

  if($rs && is_array($rs))
  {
    foreach ($rs as $i)
    {
      array_push($reg_data['grupos'], $i['grp_id']);
    }
  }

  return $ret;
}

function b1n_regToggleActivationUsuario($sql, &$ret_msgs, $col_id, $id, $activate_field, $table = '') 
{
  return b1n_regToggleActivation($sql, $ret_msgs, $col_id, $id, $activate_field, $table);
}

function b1n_regAddUsuarioPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
  if(is_array($reg_data['grupos']))
  {
    foreach ($reg_data['grupos'] as $i)
    {
      if(!$sql->sqlQuery("INSERT INTO usr_grp (usr_id, grp_id) VALUES ('" . b1n_inBd($reg_data['id']) . "', '" . b1n_inBd($i) . "')"))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Cannot add relationship in usr_grp.');
        return false;
      } 
    }
  }

  return true;
}

function b1n_regChangeUsuarioPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
  if($sql->sqlQuery("DELETE FROM usr_grp WHERE usr_id = '" . b1n_inBd($reg_data['id']) . "'", 'del'))
  {
    $ret = b1n_regAddUsuarioPlus($sql, $ret_msgs, $reg_data, $reg_config);
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not delete entries in usr_grp.');
    $ret = false;
  }

  return $ret;
}
?>
