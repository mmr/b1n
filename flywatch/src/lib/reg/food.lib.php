<?
/* $Id: food.lib.php,v 1.3 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "food", "Food");
}

function b1n_regCheckFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "food", "Food");
}

function b1n_regCheckDeleteFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteFood($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "food", "Food", "Foods");
}

function b1n_regLoadFood($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "group");

    $reg_data["functions"]  = array();
    $reg_data["users"]      = array();

    /* Functions */
    $query = "SELECT DISTINCT fnc_id FROM \"grp_fnc\" WHERE grp_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs)
    {
        if(is_array($rs))
        {
            foreach ($rs as $i)
            {
                array_push($reg_data['functions'], $i['fnc_id']);
            }
        }
    }
    else
    {
        $ret = false;
    }

    return $ret;
}
?>
