<?
/* $Id: caterer.lib.php,v 1.8 2003/03/15 18:23:46 binary Exp $ */

function b1n_regAddCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "caterer", "Caterer", "b1n_regAddCatererPlus");
}

function b1n_regCheckCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "caterer", "Caterer", "b1n_regChangeCatererPlus");
}

function b1n_regCheckDeleteCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Airport',
                        'as'        => '',
                        'table'     => 'airport',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'apt_id',
                        'col_name'  => 'apt_name'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'caterer', $reg_config['ID']['db'], 'cat_name', 'Caterer');
    }

    return $ret;
}

function b1n_regDeleteCaterer($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "caterer", "Caterer", "Caterers");
}

function b1n_regLoadCaterer($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "caterer");

    $reg_data["airports"]   = array();
    $reg_data["contacts"]   = array();

    /* Contacts */
    $query = "SELECT DISTINCT ctc_id FROM \"cat_ctc\" WHERE cat_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs)
    {
        if(is_array($rs))
        {
            foreach ($rs as $i)
            {
                array_push($reg_data['contacts'], $i['ctc_id']);
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

function b1n_regAddCatererPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Contacts
    if(is_array($reg_data["contacts"]))
    {
        foreach ($reg_data["contacts"] as $i)
        {
            if(!$sql->query("INSERT INTO \"cat_ctc\" (cat_id, ctc_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in cat_ctc.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangeCatererPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Deleting Contacts
    if($sql->query("DELETE FROM \"cat_ctc\" WHERE cat_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Adding New
        $ret = b1n_regAddCatererPlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in cat_ctc.");
        $ret = false;
    }

    return $ret;
}
?>
