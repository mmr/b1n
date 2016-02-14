<?
/* $Id: airport.lib.php,v 1.11 2003/06/20 19:43:00 mmr Exp $ */

function b1n_regAddAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if($reg_data['apt_timezone'] > 0 && !strstr($reg_data['apt_timezone'], '+'))
    {
        $reg_data['apt_timezone'] = '+' . $reg_data['apt_timezone'];    
    }

    $reg_data['apt_icao'] = strtoupper($reg_data['apt_icao']);
    $reg_data['apt_iata'] = strtoupper($reg_data['apt_iata']);

    return b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "airport", "Airport");
}

function b1n_regCheckAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        if($reg_data['apt_timezone'] > 14 || $reg_data['apt_timezone'] < -12)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Invalid <b>TimeZone</b> (valid values are between +14 and -12).');
            $ret = false;
        }

        $dst_start = mktime(0, 0, 0, $reg_data['apt_dst_start_dt']['month'], $reg_data['apt_dst_start_dt']['day'], $reg_data['apt_dst_start_dt']['year']);
        $dst_end   = mktime(0, 0, 0, $reg_data['apt_dst_end_dt']['month'], $reg_data['apt_dst_end_dt']['day'], $reg_data['apt_dst_end_dt']['year']);

        if($dst_start > $dst_end)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>DST Start</b> cannot be bigger than <b>DST End</b>.');
            $ret = false;
        }
        elseif(!empty($dst_start['month']) && $dst_start == $dst_end)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>DST Start</b> cannot be equal to <b>DST End</b>.');
            $ret = false;
        }
    }

    return $ret;
}

function b1n_regCheckChangeAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckChange($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        if($reg_data['apt_timezone'] > 12 || $reg_data['apt_timezone'] < -12)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Invalid <b>TimeZone</b> (valid values are between +12 and -12).');
            $ret = false;
        }

        $dst_start = mktime(0, 0, 0, $reg_data['apt_dst_start_dt']['month'], $reg_data['apt_dst_start_dt']['day'], $reg_data['apt_dst_start_dt']['year']);
        $dst_end   = mktime(0, 0, 0, $reg_data['apt_dst_end_dt']['month'], $reg_data['apt_dst_end_dt']['day'], $reg_data['apt_dst_end_dt']['year']);

        if($dst_start > $dst_end)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>DST Start</b> cannot be bigger than <b>DST End</b>.');
            $ret = false;
        }
        elseif(!empty($dst_start['month']) && $dst_start == $dst_end)
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, '<b>DST Start</b> cannot be equal to <b>DST End</b>.');
            $ret = false;
        }
    }

    return $ret;
}

function b1n_regChangeAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    if($reg_data['apt_timezone'] > 0 && !strstr($reg_data['apt_timezone'], '+'))
    {
        $reg_data['apt_timezone'] = '+' . $reg_data['apt_timezone'];    
    }

    $reg_data['apt_icao'] = strtoupper($reg_data['apt_icao']);
    $reg_data['apt_iata'] = strtoupper($reg_data['apt_iata']);

    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, "airport", "Airport");
}

function b1n_regCheckDeleteAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $ret = b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);

    if($ret)
    {
        $rel = array(
                    array(
                        'title'     => 'Aircraft',
                        'as'        => 'HomeBase',
                        'table'     => 'aircraft',
                        'col_ref_id'=> $reg_config['ID']['db'],
                        'col_id'    => 'acf_id',
                        'col_name'  => 'acf_model'), 
                    array(
                        'title'     => 'Leg',
                        'as'        => 'Depart Airport',
                        'table'     => 'leg',
                        'col_ref_id'=> 'apt_id_depart',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'),
                    array(
                        'title'     => 'Leg',
                        'as'        => 'Arrive Airport',
                        'table'     => 'leg',
                        'col_ref_id'=> 'apt_id_arrive',
                        'col_id'    => 'leg_id',
                        'col_name'  => 'leg_id'));


        $ret = b1n_regCheckRelationship($sql, $ret_msgs, $reg_data['ids'], $rel, 'airport', $reg_config['ID']['db'], 'apt_name', 'Airport');
    }

    return $ret;
}

function b1n_regDeleteAirport($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regDelete($sql, $ret_msgs, $reg_data, $reg_config, "airport", "Airport", "Airports");
}

function b1n_regLoadAirport($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "airport");
}
?>
