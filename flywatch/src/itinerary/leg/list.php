<?
/* $Id: list.php,v 1.32 2004/11/24 13:48:02 mmr Exp $ */

$search_config = 
    array('possible_fields'    => array('Trip'              => 'leg_trip',
                                        'Airport (Depart)'  => 'apt_name_depart',
                                        'Airport (Arrive)'  => 'apt_name_arrive',
                                        'Remarks'           => 'leg_remarks',
                                        'KeepTrack'         => 'leg_keeptrack_dt'),
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

b1n_getVar('search_text',       $search['search_text']);
b1n_getVar('search_field',      $search['search_field']);
b1n_getVar('search_quantity',   $search['search_quantity']);
b1n_getVar('pg_actual',         $search['pg_actual']);

#$search['search_order'] = 'leg_trip';
$search['search_order'] = 'leg_keeptrack_dt';
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
    <br />
    <br />
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <tr>
                        <td class="box" colspan="2"><?= $page1_title ?> - Search</td>
                    </tr>
                    <tr>
                        <td class='formitem'>Search Field
                            <form method="post"  name="form_search" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="" />
                            <input type="hidden" name="pg_actual" value="1" />
                        </td>
                        <td class='forminput'>
                            <?= b1n_buildSelect($search["possible_fields"], array($search["search"]["search_field"]), array("name" => "search_field")); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Quantity</td>
                        <td class='forminput'>
                            <?= b1n_buildSelect($search["possible_quantities"], array($search["search"]["search_quantity"]), array("name" => "search_quantity")); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Search</td>
                        <td class='forminput'>
                            <input type='text' name='search_text' value="<?= b1n_inHtml($search["search"]["search_text"])?>" size="<?= b1n_DEFAULT_SIZE ?>" maxlength="<?= b1n_DEFAULT_MAXLEN ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class='forminput' colspan='2' align='center'>
                            <input type="submit" value=' Search >>' />
                            <input type="button" value=' Show All >>' onClick='this.form.search_text.value = ""; this.form.submit();' />
                        </td>
                    </tr>
                    </form>
                    <tr><td class="box" colspan="2">&nbsp;</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <br />

<?
// Colspan = trip(1) + checkbox(1) + number(1) + depart/arrive(1) + select_fields(10) + functions(2)
// Colspan = 1 + 1 + 1 + 1 + 10 + 2
$colspan = 16;

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
                    <tr>
                        <td class='searchtitle'>Trip</td>
                        <td class='searchtitle' width='1'>
                            <form method="post" name="form_checkbox" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0" value="<?= $page0 ?>" />
                            <input type="hidden" name="page1" value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="" />
                        </td>
                        <script language="JavaScript">
                        function b1n_verifyCheckBox(c)
                        {
                            var f = c.form;
                            var l = f.elements['ids[]'].length;

                            // verifying if the checkall checkbox exists
                            if(f.checkall)
                            {
                                if(!c.checked)
                                {
                                    f.checkall.checked = false;
                                }
                            }

                            var add = f.elements['add'];
                            var del = f.elements['delete'];
                            var cat = f.elements['caterer'];
                            var hdl = f.elements['handler'];
                            var pmt = f.elements['permit'];
                            var gdc = f.elements['gedec'];

                            // Verifying if elements['ids[]'] is an array
                            if(l)
                            {
                                var j = 0;

                                // getting how many checkbox are checked
                                for(i=0; i<l; i++)
                                {
                                    if(f.elements['ids[]'][i].checked)
                                    {
                                        j++;
                                    }
                                }

                                // If NONE is checked, disable all buttons, but 'add'
                                if(j <= 0)
                                {
                                    add.disabled = false;
                                    del.disabled = true;

                                    // We should always check if the buttons exist first
                                    // We do not want our clients having js erros, do ya?
                                    if(cat)
                                    {
                                        cat.disabled = true;
                                        hdl.disabled = true;
                                        pmt.disabled = true;
                                        gdc.disabled = true;
                                    }
                                }
                                // If ONLY ONE is checked, enable All
                                else if(j == 1)
                                {
                                    add.disabled = false;
                                    del.disabled = false;

                                    if(cat)
                                    {
                                        cat.disabled = false;
                                        hdl.disabled = false;
                                        pmt.disabled = false;
                                        gdc.disabled = false;
                                    }
                                }
                                // If MORE than one is checked
                                // Disable 'add' and 'caterer', enable others
                                else if(j > 1)
                                {
                                    if(j == l)
                                    {
                                        f.elements['checkall'].checked = true;
                                    }
                                    
                                    add.disabled = true;
                                    cat.disabled = true;
                                    hdl.disabled = false;
                                    pmt.disabled = false;
                                    gdc.disabled = false;
                                    del.disabled = false;
                                }
                            }
                            else
                            {
                                if(!c.checked)
                                {
                                    add.disabled = false;
                                }

                                cat.disabled = !c.checked;
                                hdl.disabled = !c.checked;
                                pmt.disabled = !c.checked;
                                gdc.disabled = !c.checked;
                                del.disabled = !c.checked;
                            }
                        }

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
                        <td class="searchtitle">&nbsp;</td>
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
                        <td class="searchtitle" colspan="2">Functions</td>
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
                        <td class='searchtitle' width='1'><input type="checkbox" name="ids[]" value="<?= $item["id"] ?>" class="noborder" onClick="b1n_verifyCheckBox(this)" /></td>
                        <td class='searchtitle' width='1'><?= $i ?></td>

                        <td class='leg_depart'>Depart</td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['apt_name_depart']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_etd_localtime_dt']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['apt_timezone_depart']) ?></td>
                        <td class='leg_depart_gmt'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_etd_dt']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_distance']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_wind']) ?></td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_ete_i']) ?></td>
                        <td class='leg_depart'>&nbsp;</td>
                        <td class='leg_depart'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_fuel']) ?></td>
                        <td class='leg_depart'>&nbsp;</td>
                        <td colspan='2' class='searchitem'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>Arrive</td>
                        <td class='leg_arrive'>&nbsp;<?= b1n_inHtmlNoBr($item['apt_name_arrive']) ?></td>
                        <td class='leg_arrive'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_eta_localtime_dt']) ?></td>
                        <td class='leg_arrive'>&nbsp;<?= b1n_inHtmlNoBr($item['apt_timezone_arrive']) ?></td>
                        <td class='leg_arrive_gmt'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_eta_dt']) ?></td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;<?= b1n_inHtmlNoBr($item['leg_groundtime_i']) ?></td>
                        <td class='leg_arrive'>&nbsp;</td>
                        <td class='leg_arrive'>&nbsp;<?= b1n_inHtml($item['leg_remarks']) ?></td>
                        <td class='leg_arrive'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=view&id=" . $item["id"] ?>">View</a></td>
                        <td class='searchitem'><a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&action0=load&action1=change&id=" . $item["id"] ?>">Change</a></td>
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
                        <td colspan='4' class='searchtitle'>Total</td>
                        <td colspan='4' class='searchitem'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_distance ?>M</td>
                        <td class='searchitem' style='font-weight: bold;'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_flight_time ?></td>
                        <td class='searchitem' style='font-weight: bold;'>&nbsp;</td>
                        <td class='searchitem' style='font-weight: bold;'><?= $total_fuel ?>L</td>
                        <td colspan='4' class='searchitem'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='<?= $colspan ?>' class='formitem'>
                            <script language='JavaScript'>
                            function b1n_checkAll(f)
                            {
                                var i;

                                // Checking all CheckBoxes
                                for(i=0; i<f.elements["ids[]"].length; i++)
                                {
                                    f.elements["ids[]"][i].checked = f.elements["checkall"].checked;
                                }

                                // Changing buttons
                                f.elements['add'].disabled     = f.elements['checkall'].checked;
                                f.elements['caterer'].disabled = true;
                                f.elements['handler'].disabled = !f.elements['checkall'].checked;
                                f.elements['permit'].disabled  = !f.elements['checkall'].checked;
                                f.elements['gedec'].disabled   = !f.elements['checkall'].checked;
                                f.elements['delete'].disabled  = !f.elements['checkall'].checked;
                            }
                            </script>
                            <input type='checkbox' name='checkall' class='noborder' onClick='b1n_checkAll(this.form)' />
                            <a href='#' onClick='var x = document.form_checkbox.checkall; x.checked = !x.checked; b1n_checkAll(x.form);'>Check All</a>
                        </td>
                    </tr>
<?
        }
?>
                    <tr>
                        <td class='forminput' colspan='<?= $colspan - 1 ?>' align='center'>
                            <script language = "JavaScript">
                            function b1n_goNinja(f, action)
                            {
                                switch(action)
                                {
                                case 'add':
                                    f.action0.value = "getdefaults";
                                    f.action1.value = "add";
                                    break;
                                case 'delete':
                                    if(confirm("Do you really want to delete this Registry(ies)?"))
                                    {
                                        f.action0.value = "delete";
                                        f.action1.value = "";
                                        f.submit();
                                    }
                                    return true;
                                    break;
                                case 'handler':
                                case 'permit':
                                case 'gedec':
                                case 'caterer':
                                    f.page1.value   = 'pdf';
                                    f.action0.value = action;
                                    f.action1.value = 'config';
                                    break;
                                }
                                f.submit();
                            }
                            </script>
                            <input type="button" name="add"     value=" Add New >> " onClick='b1n_goNinja(this.form, "add")' />
                            <input type="button" name="caterer" value=" Catering Order >> " onClick='b1n_goNinja(this.form, "caterer")' />
                            <input type="button" name="handler" value=" Handling Request >> " onClick='b1n_goNinja(this.form, "handler")' />
                            <input type="button" name="permit"  value=" Permit Request >> " onClick='b1n_goNinja(this.form, "permit")' />
                            <input type="button" name="gedec"   value=" GeDec >> " onClick='b1n_goNinja(this.form, "gedec")' />
                            <input type="button" name="delete"  value=" Delete >> " onClick='b1n_goNinja(this.form, "delete")' />
                            <script>
                                var f = document.form_checkbox;
                                var c;
                                if(f.elements['ids[]'].length)
                                {
                                    c = f.elements['ids[]'][0];
                                }
                                else
                                {
                                    c = f.elements['ids[]'];
                                }
                                b1n_verifyCheckBox(c);
                            </script>
                        </td>
                        <td class='forminput' align='center'>
<script>
function legPrint(){
  newWindow = window.open("about:blank", "legPrint", "toolbar=no, location=no, directories=no, status=yes, menubar=no, scrollbars=yes, resizable=yes");
  document.form_checkbox.target = 'legPrint';
  document.form_checkbox.action1.value = 'print';
  document.form_checkbox.submit();
}
</script>

                            <a href='#' onClick='legPrint()'><img src='img/print.gif' border='0' /></a>
                        </td>
                    </tr>
                    </form>
<?
// Pagination System
        if($search['pg_pages'] > 1)
        {
?>
                    <tr>
                        <td colspan="<?= $colspan ?>" class='searchtitle'>
<?
            // Show left arrow if necessary
            if($search['pg_actual'] > 1)
            {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . ($search["pg_actual"] - 1) ?>"> &lt;&lt; </a>
<?
            }
    
            // Show numbered pages
            for($i = 1; $i <= $search["pg_pages"]; $i++)
            { 
                if($i == $search["pg_actual"]) 
                {
                    echo " " . $i . " ";
                }
                else
                {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . $i ?>"> <?= $i ?> </a>
<?
                } 
            }

            // Show left arrow if necessary
            if($search['pg_pages'] > $search['pg_actual'])
            {
?>
                            <a href="<?= b1n_URL . "?page0=" . $page0 . "&page1=" . $page1 . "&pg_actual=" . ($search["pg_actual"] + 1) ?>"> &gt;&gt; </a>
<?
            }
?>
                        </td>
                    </tr>
<?
        }
?>
                    <tr>
                        <td class='box' colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
<?
    }
    else
    {
?>
                    <tr>
                        <td class="box" colspan="2"><?= $page1_title ?></td>
                    </tr>
                    <tr>
                        <td class='searchitem' align="center">No registries</td>
                    </tr>
                    <tr>
                        <td class='forminput' align='center'>
                            <form method="post" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                            <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="getdefaults" />
                            <input type="hidden" name="action1" value="add" />

                            <input type="submit" name="add" value=" Add New >>" />
                        </td>
                    </tr>
                    </form>
                    <tr>
                        <td class='box' colspan="2">&nbsp;</td>
                    </tr>
<?
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
