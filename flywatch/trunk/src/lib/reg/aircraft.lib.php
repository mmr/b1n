<?
/* $Id: aircraft.lib.php,v 1.6 2003/02/22 05:29:15 binary Exp $ */

function b1n_regAddAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "aircraft", "Aircraft");
}

function b1n_regCheckAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "aircraft", "Aircraft");
}

function b1n_regCheckDeleteAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Leg',
                        'as'        => '',
                        'table'     => 'leg',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'aircraft', $reg_config['ID']['db'], 'acf_model', 'Aircraft');
    }

    return $ret;
}

function b1n_regDeleteAircraft($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "aircraft", "Aircraft", "Aircrafts");
}

function b1n_regLoadAircraft($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "aircraft");
}
?>
