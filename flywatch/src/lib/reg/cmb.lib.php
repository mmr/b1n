<?
/* $Id: cmb.lib.php,v 1.3 2003/02/22 05:29:15 binary Exp $ */

function b1n_regAddCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "cmb", "Crew Member");
}

function b1n_regCheckCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "cmb", "Crew Member");
}

function b1n_regCheckDeleteCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Leg',
                        'as'        => 'PIC',
                        'table'     => 'leg',
                        'col_ref_id'=> 'cmb_id_pic',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'), 
                    array(
                        'title'     => 'Leg',
                        'as'        => 'SIC',
                        'table'     => 'leg',
                        'col_ref_id'=> 'cmb_id_sic',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'),
                    array(
                        'title'     => 'Leg',
                        'as'        => 'Extra1',
                        'table'     => 'leg',
                        'col_ref_id'=> 'cmb_id_extra1',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'),
                    array(
                        'title'     => 'Leg',
                        'as'        => 'Extra2',
                        'table'     => 'leg',
                        'col_ref_id'=> 'cmb_id_extra2',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'cmb', $reg_config['ID']['db'], 'cmb_name', 'Crew Member');
    }

    return $ret;
}

function b1n_regDeleteCmb($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "cmb", "Crew Member", "Crew Members");
}

function b1n_regLoadCmb($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "cmb");
}
?>
