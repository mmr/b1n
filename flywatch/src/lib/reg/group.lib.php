<?
/* $Id: group.lib.php,v 1.9 2003/03/15 19:38:29 binary Exp $ */

function b1n_regAddGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "group", "Group", "b1n_regAddGroupPlus");
}

function b1n_regCheckGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "group", "Group", "b1n_regChangeGroupPlus");
}

function b1n_regCheckDeleteGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteGroup($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "group", "Group", "Groups");
}

function b1n_regLoadGroup($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "group");

    $reg_data["functions"]  = array();
    $reg_data["users"]      = array();

    /* Functions */
    $query = "SELECT DISTINCT fnc_id FROM \"grp_fnc\" WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs && is_array($rs))
    {
        foreach ($rs as $i)
        {
            array_push($reg_data['functions'], $i['fnc_id']);
        }
    }
    else
    {
        $ret = false;
    }

    /* Users */
    $query = "SELECT DISTINCT usr_id FROM \"grp_usr\" WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs && is_array($rs))
    {
        foreach ($rs as $i)
        {
            array_push($reg_data['users'], $i['usr_id']);
        }
    }
    else
    {
        $ret = false;
    }

    return $ret;
}


/* -------------------- Plus Functions -------------------- */

function b1n_regAddGroupPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    /* Functions */
    if(is_array($reg_data["functions"]))
    {
        foreach ($reg_data["functions"] as $i)
        {
            if(!$sql->query("INSERT INTO \"grp_fnc\" (grp_id, fnc_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in grp_fnc.");
                return false;
            } 
        }
    }

    /* Users */
    if(is_array($reg_data["users"]))
    {
        foreach ($reg_data["users"] as $i)
        {
            if(!$sql->query("INSERT INTO \"grp_usr\" (grp_id, usr_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in grp_usr.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangeGroupPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Deleting Functions
    if($sql->query("DELETE FROM \"grp_fnc\" WHERE grp_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Deleting Users
        if($sql->query("DELETE FROM \"grp_usr\" WHERE grp_id = '" . b1n_inBd($reg_data["id"]) . "'"))
        {
            // Adding New
            $ret = b1n_regAddGroupPlus($sql, $ret_msgs, $reg_data, $reg_config);
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in grp_usr.");
            $ret = false;
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in grp_fnc.");
        $ret = false;
    }

    return $ret;
}
?>
