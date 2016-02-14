<?
/* $Id: citizenship.lib.php,v 1.4 2003/02/22 05:29:15 binary Exp $ */

function b1n_regAddCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "citizenship", "Citizenship");
}

function b1n_regCheckCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "citizenship", "Citizenship");
}

function b1n_regCheckDeleteCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
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
                        'col_name'  => 'cmb_name'),
                    array(
                        'title'     => 'Pax',
                        'as'        => '',
                        'table'     => 'pax',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'pax_id',
                        'col_name'  => 'pax_name'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'citizenship', $reg_config['ID']['db'], 'cts_name', 'Citizenship');
    }

    return $ret;
}

function b1n_regDeleteCitizenship($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "citizenship", "Citizenship", "Citizenships");
}

function b1n_regLoadCitizenship($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "citizenship");
}
?>
