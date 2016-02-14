<html>
<head>
    <title></title>
    <link rel='stylesheet' href='<?= b1n_CSS ?>' />
</head>
<body>
<?
/* $Id: print.php,v 1.3 2003/03/20 22:57:37 binary Exp $ */
$search_config = 
    array('possible_fields'    => array('Trip'              => 'leg_trip',
                                        'Airport (Depart)'  => 'apt_name_depart',
                                        'Airport (Arrive)'  => 'apt_name_arrive',
                                        'Remarks'           => 'leg_remarks'),
          'select_fields'      => array('Trip'              => 'leg_trip',
                                        'Airport (Depart)'  => 'apt_name_depart',
                                        'Airport (Arrive)'  => 'apt_name_arrive',
                                        'TimeZone (Depart)' => 'apt_timezone_depart',
                                        'TimeZone (Arrive)' => 'apt_timezone_arrive',
                                        'During DST(Depart)'=> 'apt_dst_depart',
                                        'During DST(Arrive)'=> 'apt_dst_arrive',
                                        'Ground Time'       => 'leg_groundtime_i',
                                        'ETD'               => 'leg_etd_dt',
                                        'ETE'               => 'leg_ete_i',
                                        'Distance'          => 'leg_distance',
                                        'Wind'              => 'leg_wind',
                                        'Fuel'              => 'leg_fuel',
                                        'Remarks'           => 'leg_remarks'),
          'possible_quantities'=> array('10'  => '10',
                                        '20'  => '20',
                                        '30'  => '30',
                                        '50'  => '50',
                                        '100' => '100',
                                        'All' => 'all'),
          'session_hash_name'  => 'leg');

$search['search_order'] = 'leg_trip';
$search['search_order_type'] = 'asc';

$search = b1n_regSearchLeg($sql, $search_config, $search, false);
?>
<style>
.leg_depart {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 11px; COLOR: #FF5151; BACKGROUND-COLOR: #FFFFFF; TEXT-DECORATION: none; TEXT-ALIGN: center}
.leg_depart_gmt {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 9px; COLOR: #FF5151; BACKGROUND-COLOR: #FFFFFF; TEXT-DECORATION: none; TEXT-ALIGN: center}
.leg_arrive {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 11px; COLOR: #000000; BACKGROUND-COLOR: #FFFFFF; TEXT-DECORATION: none; TEXT-ALIGN: center}
.leg_arrive_gmt {FONT-FAMILY: Verdana,Helvetica; FONT-SIZE: 9px; COLOR: #000000; BACKGROUND-COLOR: #FFFFFF; TEXT-DECORATION: none; TEXT-ALIGN: center}
</style>

<center>
<?
// Colspan = trip(1) + checkbox(1) + number(1) + depart/arrive(1) + select_fields(10)
// Colspan = 1 + 1 + 1 + 1 + 10
$colspan = 14;

if(isset($search["result"]))
{
?>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
<?
    if(is_array($search["result"]) && sizeof($search["result"]))
    {
?>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>"><?= $page1_title ?></td>
                    </tr>
                    <script language='JavaScript'>
                        function b1n_fixRowspanTrip(trip, total)
                        {
                            // Veryfing if the browser support getElementById
                            if(document.getElementById)
                            {
                                // The TD Id is composed by the 'td' string concatenated with the Trip Number
                                var td = document.getElementById('td' + trip); 

                                // IE
                                if(document.all)
                                {
                                    td.attributes['rowSpan'].value = parseInt(total * 2);
                                }
                                else
                                {
                                    td.attributes['rowspan'].value = parseInt(total * 2);
                                }
                            }
                        }
                    </script>
                    <tr>
                        <td class='searchtitle'>Trip</td>
                        <td class='searchtitle' width='1'>&nbsp;</td>
                        <td class="searchtitle">&nbsp;</td>
                        <td class="searchtitle">Airport</td>
                        <td class="searchtitle">Local Time</td>
                        <td class="searchtitle">TZ</td>
                        <td class="searchtitle">GMT Time</td>
                        <td class="searchtitle">Distance</td>
                        <td class="searchtitle">Wind</td>
                        <td class="searchtitle">Flight Time</td>
                        <td class="searchtitle">Ground Time</td>
                        <td class="searchtitle">Fuel</td>
                        <td class="searchtitle">Remarks</td>
                    </tr>
<?
        $i = ($search['pg_actual'] * $search['search']['search_quantity']) - $search['search']['search_quantity'] + 1;

        // Totals
        $total_distance = 0;
        $total_fuel = 0;
        $total_flight_time = "'00:00'";

        $total_last_trip = 0;
        $last_trip = $search['result'][0]['leg_trip'];

        foreach($search['result'] as $item)
        {
            // If changed Trip we should Fix the Rowspan of the Last Trip
            // And set the other *Special* TD (to the Current Trip) Up
            if($item['leg_trip'] != $last_trip)
            {
                $td  = "<script language='JavaScript'>b1n_fixRowspanTrip('" . $last_trip . "', '" . $total_last_trip . "');</script>";
                $td .= "<td id='td" .  $item['leg_trip'] . "' class='searchtitle' align='center' valign='middle' rowspan='1'>" . $item['leg_trip'] . "</td>";
                $total_last_trip = 0;
            }
            // If its the very first leg
            elseif($item['id'] == $search['result'][0]['id'])
            {
                $td = "<td id='td" .  $item['leg_trip'] . "' class='searchtitle' align='center' valign='middle' rowspan='1'>" . $item['leg_trip'] . "</td>";
            }
            else
            {
                // The Trip did not change
                $td = "";
            }
?>
                    <tr>
                        <?= $td ?>
                        <td class='searchtitle' width='1'><?= $i ?></td>

                        <td class='leg_depart'>Depart</td>
                        <td class='leg_depart'>&nbsp;<?= $item['apt_name_depart'] ?></td>
                        <td class='leg_depart'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_etd_localtime_dt']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= $item['apt_timezone_depart'] ?></td>
                        <td class='leg_depart_gmt'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_etd_dt']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= $item['leg_distance'] ?></td>
                        <td class='leg_depart'>&nbsp;<?= $item['leg_wind'] ?></td>
                        <td class='leg_depart'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_ete_i']) ?></td>
                        <td class='leg_depart'>&nbsp;</td>
                        <td class='leg_depart'>&nbsp;<?= $item['leg_fuel'] ?></td>
                        <td class='leg_depart'>&nbsp;<?= ?></td>
                    </tr>
                    <tr>
                        <td class='searchitem'>&nbsp;</td>
                        <td class='leg_arrive'>Arrive</td>
                        <td class='leg_arrive'>&nbsp;<?= $item['apt_name_arrive'] ?></td>
                        <td class='leg_arrive'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_eta_localtime_dt']) ?></td>
                        <td class='leg_arrive'>&nbsp;<?= $item['apt_timezone_arrive'] ?></td>
                        <td class='leg_arrive_gmt'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_eta_dt']) ?></td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;<?= str_replace(" ", "&nbsp;", $item['leg_groundtime_i']) ?></td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;<?= $item['leg_remarks'] ?></td>
                    </tr>
<?
            $total_distance += $item['leg_distance'];
            $total_fuel += $item['leg_fuel'];
            $total_flight_time .= " + '" . $item['leg_ete_i'] . "'";
            $total_last_trip++;
            $last_trip = $item['leg_trip'];
            $i++;
        }
        // Fix the Rowspan of the Last Trip
        echo "<script>b1n_fixRowspanTrip(" . $last_trip . ", " . $total_last_trip . ");</script>";

        // Converting fuel from LBS to Liters
        $total_fuel *= 0.567;

        // Summing flight time and converting to Interval
        $total_flight_time = "SELECT INTERVAL " . $total_flight_time . " AS total_flight_time";
        $total_flight_time = $sql->query($total_flight_time);
        $total_flight_time = $total_flight_time[0]['total_flight_time'];

        if(sizeof($search["result"]) > 1)
        {
?>
                    <tr>
                        <td colspan='3' class='searchtitle'>Total</td>
                        <td colspan='4' class='searchitem'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_distance ?>M</td>
                        <td class='searchitem' style='font-weight: bold;'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_flight_time ?></td>
                        <td class='searchitem' style='font-weight: bold;'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_fuel ?>L</td>
                        <td colspan='2' class='searchitem'>&nbsp;</td>
                    </tr>
<?
        }
    }
?>
                </table>
            </td>
        </tr>
    </table>
<?
}
?>
    <br />
    <br />
</center>
</body>
</html>
