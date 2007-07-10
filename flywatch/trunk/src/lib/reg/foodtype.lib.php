<?
/* $Id: foodtype.lib.php,v 1.4 2003/03/15 18:23:46 binary Exp $ */

function b1n_regAddFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "foodtype", "Food Type", "b1n_regAddFoodtypePlus");
}

function b1n_regCheckFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "foodtype", "Food Type", "b1n_regChangeFoodtypePlus");
}

function b1n_regCheckDeleteFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteFoodtype($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "foodtype", "Food Type", "Food Types");
}

function b1n_regLoadFoodtype($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "foodtype");

    $reg_data["foods"]  = array();

    /* Foods */
    $query = "SELECT DISTINCT fod_id FROM \"fdt_fod\" WHERE fdt_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs)
    {
        if(is_array($rs))
        {
            foreach ($rs as $i)
            {
                array_push($reg_data['foods'], $i['fod_id']);
            }
        }
    }
    else
    {
        $ret = false;
    }

    return $ret;
}

/* -------------------- Plus Functions -------------------- */

function b1n_regAddFoodtypePlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Foods
    if(is_array($reg_data["foods"]))
    {
        foreach ($reg_data["foods"] as $i)
        {
            if(!$sql->query("INSERT INTO \"fdt_fod\" (fdt_id, fod_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in fdt_fod.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangeFoodtypePlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = false;

    // Deleting Functions
    if($sql->query("DELETE FROM \"fdt_fod\" WHERE fdt_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Adding New
        $ret = b1n_regAddFoodtypePlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in fdt_fod.");
        $ret = false;
    }

    return $ret;
}
?>
