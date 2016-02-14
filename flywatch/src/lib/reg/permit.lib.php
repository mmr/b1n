<?
/* $Id: permit.lib.php,v 1.8 2003/03/15 19:38:29 binary Exp $ */

function b1n_regAddPermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "permit", "Permit", "b1n_regAddPermitPlus");
}

function b1n_regCheckPermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangePermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangePermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "permit", "Permit", "b1n_regChangePermitPlus");
}

function b1n_regCheckDeletePermit($sql, &$ret_msgs, $reg_data, $reg_config)
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

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'permit', $reg_config['ID']['db'], 'pmt_name', 'Permit');
    }

    return $ret;
}

function b1n_regDeletePermit($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "permit", "Permit", "Permits");
}

function b1n_regLoadPermit($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "permit");

    $reg_data["airports"] = array();
    $reg_data["contacts"] = array();

    /* Contacts */
    $query = "SELECT DISTINCT ctc_id FROM \"pmt_ctc\" WHERE pmt_id = '" . b1n_inBd($reg_data['id']) . "'";
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

function b1n_regAddPermitPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Contacts
    if(is_array($reg_data["contacts"]))
    {
        foreach ($reg_data["contacts"] as $i)
        {
            if(!$sql->query("INSERT INTO \"pmt_ctc\" (pmt_id, ctc_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in pmt_ctc.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangePermitPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Deleting Contacts
    if($sql->query("DELETE FROM \"pmt_ctc\" WHERE pmt_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Adding New
        $ret = b1n_regAddPermitPlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in pmt_ctc.");
        $ret = false;
    }

    return $ret;
}
?>
