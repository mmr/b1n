<?
// $Id: idioma.lib.php,v 1.1.1.1 2004/01/25 15:18:52 mmr Exp $

function b1n_regAddIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, 'idioma', 'Idioma');
}

function b1n_regCheckIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, 'idioma', 'Idioma');
}

function b1n_regCheckDeleteIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteIdioma($sql, &$ret_msgs, $reg_data, $reg_config)
{
  return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, 'idioma', 'Idioma', 'Idiomas');
}

function b1n_regLoadIdioma($sql, &$ret_msgs, &$reg_data, $reg_config)
{
  return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, 'idioma');
}

function b1n_regToggleActivationIdioma($sql, &$ret_msgs, $col_id, $id, $activate_field, $table = '') 
{
  return b1n_regToggleActivation($sql, $ret_msgs, $col_id, $id, $activate_field, $table);
}
?>
