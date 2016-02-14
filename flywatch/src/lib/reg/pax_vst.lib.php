<?
/* $Id: pax_vst.lib.php,v 1.3 2003/02/21 23:29:00 binary Exp $ */

function b1n_regAddPax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "pax_vst", "Pax Visa");
}

function b1n_regCheckPax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
    
    if($ret)
    {
        $query = "SELECT pvs_id FROM \"pax_vst\" WHERE pax_id = '" . b1n_inBd($reg_data['pax_id']) . "' AND vst_id = '" . b1n_inBd($reg_data['vst_id']) . "'";

        $rs = $sql->singleQuery($query);

        if(is_array($rs))
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "This combination of <b>Pax</b> and <b>Visa Type</b> already exists.");
            $ret = false;
        }
    }
    
    return $ret;
}

function b1n_regCheckChangePax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $query = "SELECT pvs_id FROM \"pax_vst\" WHERE pax_id = '" . b1n_inBd($reg_data['pax_id']) . "' AND vst_id = '" . b1n_inBd($reg_data['vst_id']) . "' AND pvs_id != '" . $reg_data['id'] . "'";

        $rs = $sql->singleQuery($query);

        if(is_array($rs))
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "This combination of <b>Pax</b> and <b>Visa Type</b> already exists.");
            $ret = false;
        }
    }

    return $ret;
}

function b1n_regChangePax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "pax_vst", "Pax Visa");
}

function b1n_regCheckDeletePax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeletePax_vst($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "pax_vst", "Pax Visa", "Pax Visas");
}

function b1n_regLoadPax_vst($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "pax_vst");
}
?>
