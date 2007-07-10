<?
/* $Id: occupation.lib.php,v 1.4 2003/02/22 04:54:27 binary Exp $ */

function b1n_regAddOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "occupation", "Occupation");
}

function b1n_regCheckOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "occupation", "Occupation");
}

function b1n_regCheckDeleteOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Crew Member',
                        'as'        => '',
                        'table'     => 'cmb',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'cmb_id',
                        'col_name'  => 'cmb_name'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'occupation', $reg_config['ID']['db'], 'occ_name', 'Occupation');
    }

    return $ret;
}

function b1n_regDeleteOccupation($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "occupation", "Occupation", "Occupations");
}

function b1n_regLoadOccupation($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "occupation");
}
?>
