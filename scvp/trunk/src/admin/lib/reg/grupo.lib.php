<?
// $Id: grupo.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

function b1n_regAddGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, 'grupo', 'Grupo', 'b1n_regAddGrupoPlus');
}

function b1n_regCheckGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, 'grupo', 'Grupo', 'b1n_regChangeGrupoPlus');
}

function b1n_regCheckDeleteGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteGrupo($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, 'grupo', 'Grupo', 'Grupos');
}

function b1n_regLoadGrupo($sql, &$ret_msgs, &$reg_data, $reg_config)
{
  $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, 'grupo');

  $reg_data['usuarios'] = array();

  $query = "SELECT usr_id FROM usr_grp WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'";
  $rs  = $sql->sqlQuery($query);

  if($rs && is_array($rs))
  {
    foreach ($rs as $i)
    {
      array_push($reg_data['usuarios'], $i['usr_id']);
    }
  }

  $reg_data['funcoes'] = array();

  $query = "SELECT fnc_id FROM grp_fnc WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'";
  $rs  = $sql->sqlQuery($query);

  if($rs && is_array($rs))
  {
    foreach ($rs as $i)
    {
      array_push($reg_data['funcoes'], $i['fnc_id']);
    }
  }
  return $ret;
}

function b1n_regToggleActivationGrupo($sql, &$ret_msgs, $col_id, $id, $activate_field, $table = '') 
{
  return b1n_regToggleActivation($sql, $ret_msgs, $col_id, $id, $activate_field, $table);
}

function b1n_regAddGrupoPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
  if(is_array($reg_data['usuarios']))
  {
    foreach ($reg_data['usuarios'] as $i)
    {
      if(!$sql->sqlQuery("INSERT INTO usr_grp (grp_id, usr_id) VALUES ('" . b1n_inBd($reg_data['id']) . "', '" . b1n_inBd($i) . "')"))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Cannot add relationship.');
        return false;
      } 
    }
  }

  if(is_array($reg_data['funcoes']))
  {
    foreach ($reg_data['funcoes'] as $i)
    {
      if(!$sql->sqlQuery("INSERT INTO grp_fnc (grp_id, fnc_id) VALUES ('" . b1n_inBd($reg_data['id']) . "', '" . b1n_inBd($i) . "')"))
      {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Cannot add relationship.');
        return false;
      } 
    }
  }

  return true;
}

function b1n_regChangeGrupoPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
  if($sql->sqlQuery("DELETE FROM usr_grp WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'", 'del'))
  {
    if($sql->sqlQuery("DELETE FROM grp_fnc WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'", 'del'))
    {
      $ret = b1n_regAddGrupoPlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
      b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not delete relationship.');
      $ret = false;
    }
  }
  else
  {
    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not delete relationship.');
    $ret = false;
  }

  return $ret;
}
?>
