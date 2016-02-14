<?
/* $Id: pax.lib.php,v 1.2 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddPax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "pax", "Pax");
}

function b1n_regCheckPax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangePax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangePax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "pax", "Pax");
}

function b1n_regCheckDeletePax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeletePax($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "pax", "Pax", "Paxs");
}

function b1n_regLoadPax($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "pax");
}
?>
