<?
/* $Id: country.lib.php,v 1.3 2003/02/22 04:54:26 binary Exp $ */

function b1n_regAddCountry($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "country", "Country");
}

function b1n_regCheckCountry($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeCountry($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeCountry($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "country", "Country");
}

function b1n_regCheckDeleteCountry($sql, &$ret_msgs, $reg_data, $reg_config)
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
                        'col_name'  => 'apt_name'),
                    array(
                        'title'     => 'Operator',
                        'as'        => '',
                        'table'     => 'operator',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'opr_id',
                        'col_name'  => 'opr_name'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'country', $reg_config['ID']['db'], 'cty_name', 'Country');
    }

    return $ret;
}

function b1n_regDeleteCountry($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "country", "Country", "Countries");
}

function b1n_regLoadCountry($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "country");
}
?>
