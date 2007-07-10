<?
/* $Id: leg.lib.php,v 1.37 2004/09/28 22:35:22 mmr Exp $ */

// 10800s = 21:00h (timestamp 0 points to 21:00)
define('b1n_TIMESTAMP_BEGGINING', 10800);

function b1n_regChangeTripLeg($sql, &$ret_msgs, &$reg_data, &$reg_config)
{
}

function b1n_regAddLeg($sql, &$ret_msgs, &$reg_data, &$reg_config)
{
    $reg_config['Leg Trip'] = array("reg_data" => "leg_trip",
                                    "db"       => "leg_trip", 
                                    "check"    => "none",
                                    "type"     => "none");

    $increment_trip_sequence = false;

    // Getting data from last leg
    if(b1n_checkNumeric($reg_data['last_leg_id'], true))
    {
        // Getting Trip Number from Last Leg and seeing if it is a Trip Closure (Arrival at Aircraft's Homebase)
        $last = $sql->singleQuery("
            SELECT
                leg_trip,
                CASE WHEN apt_id_arrive IN
                (
                    SELECT
                        apt_id
                    FROM
                        \"aircraft\"
                        JOIN \"leg\" ON (aircraft.acf_id = leg.acf_id)
                )
                THEN
                    '1'
                ELSE
                    '0'
                END AS is_trip_closure
            FROM
                \"leg\"
            WHERE
                leg_id = '" . b1n_inBd($reg_data['last_leg_id']) . "'");

        // Could retrieve leg_trip of last leg
        if($last && is_array($last))
        {
            // Veryfing if current leg is a trip closure and this is the last trip (if it is not, this trip have, prolly, been closed already)
            $query = "
                SELECT
                    'is_trip_closure' AS current_leg 
                FROM
                    \"aircraft\",
                    \"seq_trip\"
                WHERE
                    acf_id = '" . $reg_data['acf_id'] . "' AND
                    apt_id = '" . $reg_data['apt_id_arrive'] . "' AND
                    '" . b1n_inBd($last['leg_trip']) . "' >= last_value";

            $cur = $sql->singleQuery($query);

            if($cur && $cur['current_leg'] == 'is_trip_closure')
            {
                // Yes, it is, so... increment Trip :)
                $increment_trip_sequence = true;
            }

            // If last leg was a trip closure, the current trip should be last_trip + 1
            if($last['is_trip_closure'] == '1')
            {
                $reg_data['leg_trip'] = $last['leg_trip'] + 1;
            }
            // Else, it is the current trip (even if its going to increment the trip afterwards)
            else
            {
                $reg_data['leg_trip'] = $last['leg_trip'];
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not get Trip of the Last Leg.");
            return false;
        }
    }
    // Couldnt retrieve leg_trip of last leg
    // Probably is the very first leg or an error
    else
    {
        // Veryfing if it is the first leg
        $rs = $sql->singleQuery("SELECT COUNT(leg_id) AS c FROM \"leg\"");

        if($rs && $rs['c'] == 0)
        {
            // Yes, it is, so... nothing is wrong :)
            // Get the NextVal from Trip Sequence
            $rs = $sql->singleQuery("SELECT NEXTVAL('seq_trip') AS leg_trip");

            if($rs && b1n_checkNumeric($rs['leg_trip'], true))
            {
                $reg_data['leg_trip'] = $rs['leg_trip'];
            }
            else
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not get Current Value from Trip Sequence.");
                return false;
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "It is not the very first leg and could not get trip of last leg.");
            return false;
        }
    }

    // Updating (*PUSH*) Next Legs of same Trip with ETD NULL
    $trip = b1n_inBd($reg_data['leg_trip']);
    $keeptrack = b1n_inBd($reg_data['leg_keeptrack_dt']);

    $query = "
        UPDATE
            \"leg\"
        SET
            leg_keeptrack_dt = leg_keeptrack_dt::timestamp + '" . b1n_formatHour($reg_data['leg_ete_i']) . "'::interval";

    if(!empty($reg_data['leg_groundtime_i']))
    {
        $query .= " + '" . $reg_data['leg_groundtime_i'] . "'::interval";
    }

       // We want to Update just legs of this Trip and with ETD NULL
    $query .= "
        WHERE
            leg_trip = '" . $trip . "' AND
            leg_etd_dt IS NULL AND
            leg_keeptrack_dt > '" . $keeptrack . "'";

            // select leg_id from leg where leg_etd_dt is null and leg_keeptrack_dt > '2003-01-02 07:15' and leg_keeptrack_dt + '10:10'::interval > (select leg_keeptrack_dt from leg where leg_etd_dt is not null and leg_keeptrack_dt > '2003-01-02 07:15');

        // We cannot blow the all scheme up, so, we can increment legs just when the incremented result
        // do not overlaps the leg with non-NULL ETD (supposedelly one scheduled flight that cannot be change and/or overlapped)

        // So... lets get the data of this leg (if it really exists)
    $rs = $sql->singleQuery("
        SELECT
            leg_keeptrack_dt
        FROM
            \"leg\"
        WHERE
            leg_etd_dt IS NOT NULL AND
            leg_keeptrack_dt > '" . $keeptrack . "'");

        // Seeing if we really have legs that have leg_keeptrack bigger than the one being added AND etd non-null
    if($rs && is_array($rs))
    {
            // Yeah baby, we do!
            // Well... now we have another problem
            // If we add the current leg (pushing the ones already there) will ya overlap the not null ETD leg?
            // We have to check for this situation
        if(empty($reg_data['leg_groundtime_i']))
        {
            $groundtime = '00:00';
        }
        else
        {
            $groundtime = $reg_data['leg_groundtime_i'];
        }

        $rs2 = $sql->singleQuery("
            SELECT 
                func_get_leg_push_overflow(
                    '" . $trip . "', 
                    '" . $keeptrack . "', 
                    '" . b1n_formatHour($reg_data['leg_ete_i']) . "', 
                    '" . $groundtime. "') AS overflow");

        unset($groundtime);

            // If we have something from the Function that means we are overlapping someone (hmmm)
        if($rs2 && !empty($rs2['overflow']))
        {
                // Yep, we are trying to overlap someone, lets warn the user and abort the operation
            $msg = 'The leg you are trying to add overlaps a leg with arbitrary ETD in the ';
            $aux = explode('|', $rs2['overflow']);

            if($trip != $aux[0])
            {
                $msg .= $aux[0];
            }
            else
            {
                $msg .= 'current(' . $trip . ')';
            }

            $msg .= ' trip at ' . b1n_formatDateHourShow(b1n_formatDateHourFromDb($aux[1])) . '.<br />Process aborted.';
            b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
            return false;
        }
    }

    $rs = $sql->query($query);

    if($rs)
    {
        if(b1n_regAdd($sql, $ret_msgs, $reg_data, $reg_config, "leg", "Leg", "b1n_regAddLegPlus"))
        {
            // If Add was successful, check if we need to increment the trip sequence 
            if($increment_trip_sequence)
            {
                $sql->query("SELECT NEXTVAL('seq_trip')");
            }

            return true;
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not Add new Leg.");
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not *PUSH* next Legs.<br />Add Process Aborted.");
    }

    return false;
}

function b1n_regCheckLeg($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    $ret  = b1n_regCheck($sql, $ret_msgs, $reg_data, $reg_config); 

    // Checking CrewMembers
    if($ret)
    {
        $ret = b1n_regCheckLegCmb($sql, $ret_msgs, $reg_data);
    }

    if($ret)
    {
        $ret = false;

        // Do we have a valid ETD?
        if(b1n_checkDateHour($reg_data['leg_etd_dt']["month"],
                             $reg_data['leg_etd_dt']["day"],
                             $reg_data['leg_etd_dt']["year"],
                             $reg_data['leg_etd_dt']["hour"],
                             $reg_data['leg_etd_dt']["min"], true))
        {
            // Yes, we do, use it as KeepTrack
            $reg_data['leg_keeptrack_dt'] = b1n_formatDateHour($reg_data['leg_etd_dt']);
            $ret = true;
        }
        // Ok, we do not have ETD so we should calculate it based on the keeptrack of the last leg
        // Getting data from last leg
        elseif(!empty($reg_data['last_leg_id']) && b1n_checkNumeric($reg_data['last_leg_id']))
        {
            // The KeepTrack of the current leg is equal to the KeepTrack of the last Leg, Plus its ETE, Plus its groundtime.
            $rs = $sql->singleQuery("
                SELECT
                    leg_keeptrack_dt::timestamp +
                    CASE WHEN (leg_ete_i IS NULL) THEN
                        '00:00'::interval
                    ELSE
                        leg_ete_i::interval
                    END +
                    CASE WHEN (leg_groundtime_i IS NULL) THEN
                        '00:00'::interval
                    ELSE
                        leg_groundtime_i::interval
                    END AS leg_keeptrack_dt
                FROM
                    \"leg\"
                WHERE
                    leg_id = '" . b1n_inBd($reg_data['last_leg_id']) . "'");

            if($rs && is_array($rs))
            {
                $reg_data['leg_keeptrack_dt'] = $rs['leg_keeptrack_dt'];
                $ret = true;
            }
            else
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not keep track of last leg, something really nasty happened here.');
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not keep track of last leg. Probably this is the very first leg, so, you HAVE to fill the <b>ETD</b> field.');
        }
    }

    return $ret;
}

function b1n_regCheckChangeLeg($sql, &$ret_msgs, &$reg_data, $reg_config)
{
    return b1n_regCheckLeg($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regChangeLeg($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // We will update the Trip (if changed) in the ChangeLegPlus function
    $reg_config['Trip']['db'] = 'none';

    return b1n_regChange($sql, $ret_msgs, $reg_data, $reg_config, 'leg', 'Leg', 'b1n_regChangeLegPlus');
}

function b1n_regCheckDeleteLeg($sql, &$ret_msgs, $reg_data, $reg_config)
{
    return b1n_regCheckDelete($sql, $ret_msgs, $reg_data, $reg_config);
}

function b1n_regDeleteLeg($sql, &$ret_msgs, $reg_data, $reg_config)
{
    $rs = $sql->query('BEGIN TRANSACTION');

    if($rs)
    {
        $ids['update'] = 'leg_id IS NOT NULL'; 
        $ids['delete'] = 'leg_id IS NULL';

        for($i=0; $i<sizeof($reg_data['ids']); $i++)
        {
            $id = b1n_inBd($reg_data['ids'][$i]);
            $reg_data['ids'][$i] = $id;

            $ids['update'] .= " AND leg_id != '" . $id . "'";
            $ids['delete'] .= " OR  leg_id =  '" . $id . "'";
        }

        foreach($reg_data['ids'] as $id)
        {
            // Getting data for this Leg
            $query = '
                SELECT
                    leg_trip,
                    leg_keeptrack_dt,
                    leg_ete_i
                    +
                        CASE WHEN (leg_groundtime_i IS NULL) THEN
                            \'00:00\'::interval
                        ELSE
                            leg_groundtime_i
                        END AS leg_decrement_i
                FROM
                    "leg"
                WHERE
                    leg_id = \'' . $id . '\'';

            $rs = $sql->singleQuery($query);

            if($rs && is_array($rs))
            {
                // Updating (*PULL*) Next Legs of same Trip with ETD NULL
                $query = "
                    UPDATE
                        \"leg\"
                    SET
                        leg_keeptrack_dt = leg_keeptrack_dt::timestamp - '" . $rs['leg_decrement_i'] . "'::interval
                    WHERE
                        " . $ids['update'] . " AND
                        leg_trip = '" . b1n_inBd($rs['leg_trip']) . "' AND
                        leg_etd_dt IS NULL AND
                        leg_keeptrack_dt > '" . b1n_inBd($rs['leg_keeptrack_dt']) . "'";

                $rs = $sql->query($query);

                if(!$rs)
                {
                    b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not Update Leg '" . $id . "'.");
                    return false;
                }
            }
            else
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not Get Data for Leg '" . $id . "'.");
                return false;
            }
        }

        $query = "
            DELETE FROM
                \"leg\" 
            WHERE
                " . $ids['delete']; 

        $rs = $sql->query($query);

        if($rs)
        {
            if(sizeof($reg_data['ids']) > 1)
            {
                $msg = 'Legs';
            }
            else
            {
                $msg = 'Leg';
            }

            b1n_retMsg($ret_msgs, b1n_SUCCESS, $msg . " deleted successfully!");
            return $sql->query('COMMIT TRANSACTION');
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not Delete Legs.");
            return false;
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not Begin Transaction.");
        return false;
    }
    
    $sql->query('ROLLBACK TRANSACTION');
    return false; 
}

function b1n_regLoadLeg($sql, &$ret_msgs, &$reg_data, &$reg_config)
{
    $d = date('Y');
    $reg_config['KeepTrack'] = array('reg_data' => 'leg_keeptrack_dt',
                                     'db'       => 'leg_keeptrack_dt', 
                                     'check'    => 'none',
                                     'type'     => 'select',
                                     'extra'    => array('seltype'      => 'date_hour', 
                                                         'year_start'   => $d,
                                                         'year_end'     => $d + b1n_DEFAULT_DATE_INC),
                                     'load'     => 'true');

    $ret = b1n_regLoad($sql, $ret_msgs, $reg_data, $reg_config, "leg");
    unset($reg_config["KeepTrack"]);

    $reg_data["paxs"] = array();

    // Pax List
    $query = "SELECT DISTINCT pax_id FROM \"leg_pax\" WHERE leg_id = '" . b1n_inBd($reg_data['id']) . "'";
    $rs    = $sql->query($query);

    if($rs && is_array($rs))
    {
        foreach ($rs as $i)
        {
            array_push($reg_data['paxs'], $i['pax_id']);
        }
    }

    // ETD = KeepTrack
    $reg_data["leg_etd_dt"] = $reg_data["leg_keeptrack_dt"];

    // ETE
    $reg_data["leg_ete_i"] = b1n_formatHourFromDb($reg_data["leg_ete_i"]);

    return $ret;
}

/* -------------------- Plus Functions -------------------- */

function b1n_regAddLegPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Paxs
    if(is_array($reg_data["paxs"]))
    {
        foreach ($reg_data["paxs"] as $i)
        {
            if(!$sql->query("INSERT INTO \"leg_pax\" (leg_id, pax_id) VALUES ('" . b1n_inBd($reg_data["id"]) . "', '" . b1n_inBd($i) . "')"))
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot add relationship in leg_pax.");
                return false;
            } 
        }
    }

    return true;
}

function b1n_regChangeLegPlus($sql, &$ret_msgs, $reg_data, $reg_config)
{
    // Deleting Pax
    if($sql->query("DELETE FROM \"leg_pax\" WHERE leg_id = '" . b1n_inBd($reg_data["id"]) . "'"))
    {
        // Adding New
        $ret = b1n_regAddLegPlus($sql, $ret_msgs, $reg_data, $reg_config);
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Could not delete entries in leg_pax.");
        $ret = false;
    }

    if($ret)
    {
        $ete_signal = '';
        $groudtime_signal = '';

        // Seeing if we had no GroundTime and then, changed
        if($reg_data['old_values']['leg_groundtime_i'] == '' && $reg_data['leg_groundtime_i'] != '')
        {
            // TODO
            // Hmmm... special case!
            // We had no groundtime and now the user did set one
            // We have to check if we have legs after this one, if we do, complain and abort operation
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot set GroundTime for this leg (Because it has none).');
            return false;
        }

        $tmp['ete']        = strtotime(b1n_formatHour($reg_data['leg_ete_i']));
        $tmp['groundtime'] = strtotime($reg_data['leg_groundtime_i']);
        $ete_old           = strtotime($reg_data['old_values']['leg_ete_i']);
        $groundtime_old    = strtotime($reg_data['old_values']['leg_groundtime_i']);

        $tmp['ete_signal'] = '';
        $tmp['groundtime_signal'] = '';

        // Seeing if ETE changed
        if($tmp['ete'] > $ete_old)
        {
            // The new is bigger than the older
            // We have to *PUSH*(+) the next legs
            $tmp['ete_signal'] = '+';
        }
        elseif($tmp['ete'] < $ete_old)
        {
            // The new is smaller than the older
            // We have to *PULL*(-) the next legs
            $tmp['ete_signal'] = '-';
        }

        // Seeing if Groundtime changed
        if($tmp['groundtime'] > $groundtime_old)
        {
            // The new is bigger than the older
            // We have to *PUSH*(+) the next legs
            $tmp['groundtime_signal'] = '+';
        }
        elseif($tmp['groundtime'] < $groundtime_old)
        {
            // The new is smaller than the older
            // We have to *PULL*(-) the next legs
            $tmp['groundtime_signal'] = '-';
        }

        if(!empty($tmp['ete_signal']) || !empty($tmp['groundtime_signal']))
        {
            $r['trip'] = $reg_data['old_values']['leg_trip'];
            $r['keeptrack'] = $reg_data['leg_keeptrack_dt'];
            $r['ete_signal'] = $tmp['ete_signal'];
            $r['groundtime_signal'] = $tmp['groundtime_signal'];
            $r['ete_diff_ts'] = abs($tmp['ete'] - $ete_old) + b1n_TIMESTAMP_BEGGINING;
            $r['groundtime_diff_ts'] = abs($tmp['groundtime'] - $groundtime_old) + b1n_TIMESTAMP_BEGGINING;

            $ret = b1n_regUpdateNextLegs($sql, $ret_msgs, $r);
        }

        // Changing leg_trip value
            // Checking if it changed
        $query = "SELECT leg_trip FROM leg WHERE leg_id = '".b1n_inBd($reg_data['id'])."'";

        if($rs = $sql->singleQuery($query)){
            if($rs['leg_trip'] != $reg_data['leg_trip']){
                // Checking if there are trips with there are trips with the
                // trip we are trying to change this leg to
                $query = "SELECT COUNT(leg_id) AS c FROM leg WHERE 
                            leg_trip = '".b1n_inBd($reg_data['leg_trip'])."'";
                $aux = $sql->singleQuery($query);
                if($aux['c'] > 0){
                    // Yes we have legs with this trip number already, aborting
                    b1n_retMsg($ret_msgs, b1n_FIZZLES, "There are legs with trip number '".$reg_data['leg_trip']."' already. Please, change this legs before trying again.");
                    return false;
                }
                else {
                    // No, we don't, update the legs for the new trip
                    $query = "
                        UPDATE leg SET
                            leg_trip = '".b1n_inBd($reg_data['leg_trip'])."'
                        WHERE
                            leg_trip = '".b1n_inBd($rs['leg_trip'])."'";
                    if(!($ret = $sql->query($query))){
                        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot update trip to ".$reg_data['leg_trip']." where trip = ".$rs['leg_trip']);
                    }
                }
            }
        }
        else {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, "Cannot get trip for leg ".$reg_data['id']);
            $ret = false;
        }
    }

    return $ret;
}

/* -------------------- Misc Functions -------------------- */

function b1n_regUpdateNextLegs($sql, &$ret_msgs, $r)
{
    $r['ete_diff']         = strftime('%H:%M', $r['ete_diff_ts']);
    $r['groundtime_diff']  = strftime('%H:%M', $r['groundtime_diff_ts']);

    // If we are PUSHing we have to check for Overflow
    if($r['ete_signal'] == '+')
    {
        $r['ete_signal'] = '';

        if($r['groundtime_signal'] == '+')
        {
            $r['groundtime_signal'] = '';
        }

        // Checking for overflow
        $rs = $sql->singleQuery("
            SELECT
                func_get_leg_push_overflow(
                    '" . $r['trip'] . "',
                    '" . $r['keeptrack'] . "',
                    '" . $r['ete_diff'] . "', 
                    '" . $r['groundtime_diff'] . "') AS overflow");

            // If we have something from the Function that means we are overlapping someone (hmmm)
        if($rs && !empty($rs['overflow']))
        {
                // Yep, we are trying to overlap someone, lets warn the user and abort the operation
            $msg = 'The leg you are trying to change overlaps a leg with arbitrary ETD in the ';
            $aux = explode('|', $rs['overflow']);

            if($r['trip'] != $aux[0])
            {
                $msg .= $aux[0];
            }
            else
            {
                $msg .= 'current(' . $r['trip'] . ')';
            }

            $msg .= ' trip at ' . b1n_formatDateHourShow(b1n_formatDateHourFromDb($aux[1])) . '.<br />Process aborted.';
            b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
            return false;
        }
    }
    elseif($r['groundtime_signal'] == '+' && $r['groundtime_diff_ts'] > $r['ete_diff_ts'])
    {
        $r['groundtime_signal'] = ''; 

        // Checking for overflow
        $rs = $sql->singleQuery("
            SELECT
                func_get_leg_push_overflow(
                    '" . $r['trip'] . "',
                    '" . $r['keeptrack'] . "',
                    '" . $r['ete_diff'] . "', 
                    '" . $r['groundtime_diff'] . "') AS overflow");

            // If we have something from the Function that means we are overlapping someone (hmmm)
        if($rs && !empty($rs['overflow']))
        {
                // Yep, we are trying to overlap someone, lets warn the user and abort the operation
            $msg = 'The leg you are trying to change overlaps a leg with arbitrary ETD in the ';
            $aux = explode('|', $rs['overflow']);

            if($trip != $aux[0])
            {
                $msg .= $aux[0];
            }
            else
            {
                $msg .= 'current(' . $trip . ')';
            }

            $msg .= ' trip at ' . b1n_formatDateHourShow(b1n_formatDateHourFromDb($aux[1])) . '.<br />Process aborted.';
            b1n_retMsg($ret_msgs, b1n_FIZZLES, $msg);
            return false;
        }
    }
    elseif($r['ete_signal'] != '-' && $r['groundtime_signal'] != '-')
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, "Unknown signal requested.");
        return false;
    }

    $query = "
        UPDATE
            \"leg\"
        SET
            leg_keeptrack_dt = leg_keeptrack_dt::timestamp
            + 
                " . $r['ete_signal'] . "'" . b1n_inBd($r['ete_diff']) . "'::interval
            +
                " . $r['groundtime_signal'] . "'" . b1n_inBd($r['groundtime_diff']) . "'::interval
        WHERE
            leg_trip = '" . $r['trip']  . "' AND
            leg_etd_dt IS NULL AND
            leg_keeptrack_dt > '" . $r['keeptrack'] . "'";

    $rs = $sql->query($query);

    if($rs)
    {
        $ret = true;
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'Could not ' . $action . ' next legs.');
        $ret = false;
    }

    return $ret;
}

function b1n_regDefaultLegAircraft($sql)
{
    $query = "SELECT acf_id FROM aircraft WHERE acf_default = '1'"; 
    $ret = $sql->singleQuery($query);

    return $ret['acf_id'];
}

function b1n_regDefaultLegPaxList($sql, $last_leg_id)
{
    $ret["paxs"] = array();

    $query = "SELECT DISTINCT pax_id FROM \"leg_pax\" WHERE leg_id = '" . b1n_inBd($last_leg_id) . "'";
    $rs    = $sql->query($query);

    if($rs)
    {
        if(is_array($rs))
        {
            foreach ($rs as $i)
            {
                array_push($ret['paxs'], $i['pax_id']);
            }
        }
    }

    return $ret["paxs"];
}

function b1n_regDefaultLegSearchSimilar($sql, $apt_id_depart, $apt_id_arrive)
{
    if(b1n_checkNumeric($apt_id_depart) && b1n_checkNumeric($apt_id_arrive))
    {
        $query = "SELECT func_similar_leg('" . b1n_inBd($apt_id_depart) . "', '" . b1n_inBd($apt_id_arrive) . "') AS similar_leg";
        $rs = $sql->singleQuery($query);
        return $rs['similar_leg'];
    }
}

function b1n_regCheckLegCmb($sql, &$ret_msgs, &$reg_data)
{
    $ret = false;

    if($reg_data['cmb_id_pic'] != $reg_data['cmb_id_sic'])
    {
        if($reg_data['cmb_id_pic'] != $reg_data['cmb_id_extra1'])
        {
            if($reg_data['cmb_id_pic'] != $reg_data['cmb_id_extra2'])
            {
                if($reg_data['cmb_id_sic'] != $reg_data['cmb_id_extra1'])
                {
                    if($reg_data['cmb_id_sic'] != $reg_data['cmb_id_extra2'])
                    {
                        if($reg_data['cmb_id_extra2'] && !$reg_data['cmb_id_extra1'])
                        {
                            $reg_data['cmb_id_extra1'] = $reg_data['cmb_id_extra2'];
                            $reg_data['cmb_id_extra2'] = '';
                            $ret = true;
                        }
                        elseif($reg_data['cmb_id_extra1'] && $reg_data['cmb_id_extra1'] == $reg_data['cmb_id_extra2'])
                        {
                            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>Extra1</b> and <b>Extra2</b> Duty.');
                        }
                        else
                        {
                            $ret = true;
                        }
                    }
                    else
                    {
                        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>SIC</b> and <b>Extra 2</b> Duty.');
                    }
                }
                else
                {
                    b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>SIC</b> and <b>Extra 1</b> Duty.');
                }
            }
            else
            {
                b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>PIC</b> and <b>Extra 2</b> Duty.');
            }
        }
        else
        {
            b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>PIC</b> and <b>Extra 1</b> Duty.');
        }
    }
    else
    {
        b1n_retMsg($ret_msgs, b1n_FIZZLES, 'You cannot assign the same Crew Member to <b>PIC</b> and <b>SIC</b> Duty.');
    }

    return $ret;
}

function b1n_regSearchLeg($sql, $search_config, $search)
{
    $ret["select_fields"]       = $search_config["select_fields"];
    $ret["possible_fields"]     = $search_config["possible_fields"];
    $ret["possible_quantities"] = $search_config["possible_quantities"];

    // ---------------------- Checking and Session storing ----------------

    if(!b1n_checkSearch($search, $ret, $search_config["session_hash_name"], true))
    {
        $ret["search"] = $search;
	return $ret;
    }

    $ret["search"] = $search;
    $_SESSION["search"][$search_config["session_hash_name"]] = $search;

    // ---------------------- WHERE ---------------------------
    switch($search["search_field"])
    {
    case "apt_name_depart":
        $field = "
        CASE WHEN (apt_depart.apt_icao IS NOT NULL) THEN
            apt_depart.apt_name || ' (' || apt_depart.apt_icao || ')'
        ELSE
            apt_depart.apt_name
        END";
        break;
    case "apt_name_arrive":
        $field = "
        CASE WHEN (apt_arrive.apt_icao IS NOT NULL) THEN
            apt_arrive.apt_name || ' (' || apt_arrive.apt_icao || ')'
        ELSE    
            apt_arrive.apt_name
        END";
        break;
    default:
        $field = $search["search_field"];
        break;
    }


    // ---------------------- LIMIT & OFFSET ----------------
    if($search["search_quantity"] == 'all')    
    {
        $ret['pg_actual']   = 1;
        $ret['pg_pages']    = 1;

        $limit_quantity = 0;
        $limit_offset   = 0;
    }
    else
    {
        $query = "
            SELECT DISTINCT
                COUNT(leg_id)
            FROM
                \"leg\"
                LEFT OUTER JOIN \"airport\" apt_depart ON (apt_id_depart = apt_depart.apt_id)
                LEFT OUTER JOIN \"airport\" apt_arrive ON (apt_id_arrive = apt_arrive.apt_id)
            WHERE " .  $field . " ILIKE '%" . b1n_inBd($search['search_text']) . "%'"; 

        $rs_count = $sql->singleQuery($query);
        $ret["pg_pages"] = max(1, ceil($rs_count["count"] / $search["search_quantity"]));

        if($search["pg_actual"] > $ret["pg_pages"]) 
        {
            $search["pg_actual"] = $ret["pg_pages"];
        }

        $ret["pg_actual"] = $search["pg_actual"];

        $limit_quantity = $search['search_quantity'];
        $limit_offset   = (($search['pg_actual'] - 1) * $search['search_quantity']);
    }

    // ---------------------- DB Search ---------------------------
    $query = "
        SELECT
            *
        FROM
            func_list_leg (
                '" . b1n_inBd($field) . "', 
                '" . b1n_inBd($search["search_text"])  . "', 
                '" . $search["search_order"] . "', 
                '" . $search["search_order_type"] . "', 
                '" . $limit_quantity . "',
                '" . $limit_offset   . "'
            )
            AS 
            (
                apt_name_depart text,
                apt_name_arrive text,
                apt_timezone_depart text,
                apt_timezone_arrive text,

                leg_etd_dt text,
                leg_etd_localtime_dt text,
    
                leg_ete_i interval,

                leg_eta_dt text,
                leg_eta_localtime_dt text,
                leg_groundtime_i interval,

                id integer,
                leg_trip integer,
                leg_distance integer,
                leg_wind integer,
                leg_fuel integer,
                leg_remarks text
            )";

    $ret["result"] = $sql->query($query);

    return $ret;    
}
?>
