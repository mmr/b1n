<?
/* $Id: contact.lib.php,v 1.2 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "contact", "Contact");
}

function b1n_regCheckContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "contact", "Contact");
}

function b1n_regCheckDeleteContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteContact($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "contact", "Contact", "Contacts");
}

function b1n_regLoadContact($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "contact");
}
?>
