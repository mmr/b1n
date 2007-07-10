<?
/* $Id: user.lib.php,v 1.9 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "user", "User");
}

function b1n_regCheckUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);

    if($reg_data["usr_passwd"] != $reg_data["usr_passwd2"])
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>Password</b> and <b>Confirmation</b> do not match.');
        $ret = false;
    }

    return $ret; 
}

function b1n_regCheckChangeUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);

    if($reg_data["usr_passwd"] != $reg_data["usr_passwd2"])
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>Password</b> and <b>Confirmation</b> do not match.');
        $ret = false;
    }

    return $ret; 
}

function b1n_regChangeUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "user", "User");
}

function b1n_regCheckDeleteUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteUser($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "user", "User", "Users");
}

function b1n_regLoadUser($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "user");
}


function b1n_regPagesUser($sql)
{
    $ret = array();
    $query = "SELECT page FROM \"view_page\"";
    $rs = $sql->query($query);

    if($rs && is_array($rs))
    {
        foreach ($rs as $i)
        {
            $ret[ucwords($i['page'])] = $i['page'];
        }
    }

    return $ret;
}
?>
