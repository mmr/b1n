<?
/* $Id: operator.lib.php,v 1.3 2003/02/22 04:54:27 binary Exp $ */

function b1n_regAddOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "operator", "Operator");
}

function b1n_regCheckOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regCheckChangeOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "operator", "Operator");
}

function b1n_regCheckDeleteOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Aircraft',
                        'as'        => '',
                        'table'     => 'aircraft',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'acf_id',
                        'col_name'  => 'acf_model'));

        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'operator', $reg_config['ID']['db'], 'opr_name', 'Operator');
    }

    return $ret;
}

function b1n_regDeleteOperator($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "operator", "Operator", "Operators");
}

function b1n_regLoadOperator($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "operator");
}
?>
