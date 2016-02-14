<?
/* $Id: syslink.lib.php,v 1.4 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddSyslink($sql, &$ret_msgs, &$reg_data, &$reg_config)
{
    $reg_config['User'] = array("reg_data"  => "usr_id",
                                "db"        => "usr_id", 
                                "check"     => "none",
                                "type"      => "none");
    $reg_data['usr_id'] = $_SESSION['user']['usr_id'];

    $ret = b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "syslink", "Syslink");
    unset($reg_config['User']);
    return $ret;
}

function b1n_regCheckSyslink($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeSyslink($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeSyslink($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Testing if the link really belongs to the current user
    $rs = $sql->singleQuery("SELECT COUNT(slk_id) AS c FROM \"link\" WHERE usr_id = '" . b1n_inBd($_SESSION['user']['usr_id']) . "' AND slk_id = '" . b1n_inBd($reg_data['id']) . "'");

    if($rs['c'] == '1')
    {
        return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "syslink", "SysLink");
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "This Link does not belongs to you, What are you Trying to Do?");
        return false;
    }
}

function b1n_regCheckDeleteSyslink($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteSyslink($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if(!is_array($reg_data["ids"]))
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You need to check something to Delete.');
        return false;
    }

    // Testing if the link really belongs to the current user
    $query = "SELECT COUNT(slk_id) AS c FROM \"syslink\" WHERE usr_id = '" . b1n_inBd($_SESSION['user']['usr_id']) . "' AND (slk_id IS NULL"; 
    foreach($reg_data["ids"] as $id)
    {
        $query .= " OR slk_id = '" . b1n_inBd($id) . "'";
    }
    $query .= ")";

    $rs = $sql->singleQuery($query);

    if($rs['c'] == sizeof($reg_data["ids"]))
    {
        return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "syslink", "Syslink", "Syslinks");
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "You are trying to delete Syslink(s) that does not belongs to you, What are you Trying to Do?");
        return false;
    }
}

function b1n_regLoadSyslink($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    // Testing if the link really belongs to the current user
    $rs = $sql->singleQuery("SELECT COUNT(slk_id) AS c FROM \"syslink\" WHERE usr_id = '" . b1n_inBd($_SESSION['user']['usr_id']) . "' AND slk_id = '" . b1n_inBd($reg_data['id']) . "'");

    if($rs['c'] == '1')
    {
        return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "syslink");
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "This Syslink does not belongs to you, What are you Trying to Do?");
        return false;
    }
}

function b1n_regPossibleSyslink($sql)
{
    $ret = array();
    $query = "
        SELECT
            page
        FROM
            \"view_page\"
        EXCEPT
        (
            SELECT
                slk_name
            FROM
                syslink
            WHERE
                usr_id = '" . b1n_inBd($_SESSION['user']['usr_id']) . "'
        )";

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
