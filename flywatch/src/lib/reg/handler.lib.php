<?
/* $Id: handler.lib.php,v 1.7 2003/03/15 18:23:46 binary Exp $ */

function b1n_regAddHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "handler", "Handler", "b1n_regAddHandlerPlus");
}

function b1n_regCheckHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "handler", "Handler", "b1n_regChangeHandlerPlus");
}

function b1n_regCheckDeleteHandler($sql, &$ret_msgs, $reg_data, $reg_config)
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

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'handler', $reg_config['ID']['db'], 'hdl_name', 'Handler');
    }

    return $ret;
}

function b1n_regDeleteHandler($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "handler", "Handler", "Handlers");
}

function b1n_regLoadHandler($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "handler");

    $reg_data["airports"] = array();
    $reg_data["contacts"] = array();

    /* Contacts */
    $query = "SELECT DISTINCT ctc_id FROM \"hdl_ctc\" WHERE hdl_id = '" . b1n_inBd($reg_data['id']) . "'";
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

function b1n_regAddHandlerPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Contacts
    if(is_array($reg_data["contacts"]))
    {
        foreach ($reg_data["contacts"] as $i)
        {
            if(!$sql->query("INSERT INTO \"hdl_ctc\" (hdl_id, ctc_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in hdl_ctc.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangeHandlerPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Deleting Contacts
    if($sql->query("DELETE FROM \"hdl_ctc\" WHERE hdl_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Adding New
        $ret = b1n_regAddHandlerPlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in hdl_ctc.");
        $ret = false;
    }

    return $ret;
}
?>
