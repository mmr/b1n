<?
/* $Id: add.php,v 1.26 2004/09/28 22:35:22 mmr Exp $ */ 

if(isset($reg_data['ids']) && is_array($reg_data['ids']))
{
    $reg_data['last_leg_id'] = $reg_data['ids'][sizeof($reg_data['ids']) - 1];
}
else
{
    b1n_getVar("last_leg_id", $reg_data['last_leg_id']);

    if(!b1n_checkNumeric($reg_data['last_leg_id'], true))
    {
        $query = "SELECT leg_id FROM \"leg\" ORDER BY leg_keeptrack_dt DESC";
        $rs = $sql->singleQuery($query);
        if($rs && is_array($rs))
        {
            $reg_data['last_leg_id'] = $rs['leg_id'];
        }
        else
        {
            // Prolly very first leg, so, ETD is mandatory
            $reg_config['ETD']['mand'] = true;
        }
    }
}

$colspan = 3;
$disable_etd_dt = false;

if(!empty($reg_data['last_leg_id']) && b1n_checkNumeric($reg_data['last_leg_id']))
{
    $query = "
        SELECT
            acf_id,
            apt_id_depart,
            apt_id_arrive,

            leg_groundtime_i IS NOT NULL AS leg_groundtime_i,
            leg_keeptrack_dt::timestamp
            +
                CASE WHEN (leg_ete_i IS NULL) THEN
                    '00:00'::interval
                ELSE
                    leg_ete_i::interval
                END
            +
                CASE WHEN (leg_groundtime_i IS NULL) THEN
                    '00:00'::interval
                ELSE
                    leg_groundtime_i::interval
                END AS leg_keeptrack_dt,

            cmb_id_pic,
            cmb_id_sic,
            cmb_id_extra1,
            cmb_id_extra2
        FROM
            \"leg\" 
        WHERE
            leg_id = '" . b1n_inBd($reg_data['last_leg_id']) . "'";

    $rs = $sql->singleQuery($query);

    switch($action0)
    {
    case 'getdefaults':
        // Pax List from Last Leg
        $reg_data['paxs'] = b1n_regDefaultLegPaxList($sql, $reg_data['last_leg_id']);

        if($rs && is_array($rs))
        {
            // Default depart airport is the Last Arrive Airport
            $reg_data['apt_id_depart'] = $rs['apt_id_arrive'];

            // Default arrive airport is the Last Depart Airport
            $reg_data['apt_id_arrive'] = $rs['apt_id_depart'];

            // Default Depart date is keeptrack
            // $reg_data['leg_etd_dt'] = b1n_formatDateHourFromDb($rs['leg_keeptrack_dt']);

            // PIC/SIC/Extra1/Extra2 from Last Leg
            $reg_data['cmb_id_pic']     = $rs['cmb_id_pic'];
            $reg_data['cmb_id_sic']     = $rs['cmb_id_sic'];
            $reg_data['cmb_id_extra1']  = $rs['cmb_id_extra1'];
            $reg_data['cmb_id_extra2']  = $rs['cmb_id_extra2'];

            // Default Aircraft is the one setted as default (duh!).
            $reg_data['acf_id'] = b1n_regDefaultLegAircraft($sql);

            // If none, use the one from last leg.
            if(!$reg_data['acf_id'])
            {
                $reg_data['acf_id'] = $rs['acf_id'];
            }
        }

        // Default GroundTime is 45 minutes
        $reg_data['leg_groundtime_i'] = '00:45';

        // 'No Break' on purpose to get throught and get similar leg data :)
        // break;
    case 'similarleg':
        if($reg_data['apt_id_depart'] && $reg_data['apt_id_arrive'])
        {
            $aux = b1n_regDefaultLegSearchSimilar($sql, $reg_data['apt_id_depart'], $reg_data['apt_id_arrive']);

            if($aux)
            {
                list($reg_data['leg_ete_i'], $reg_data['leg_distance'], $reg_data['leg_fuel']) = explode('|', $aux);

                if($reg_data['leg_fuel'] == 0)
                {
                    $reg_data['leg_fuel'] = '';
                }

                $reg_data['leg_ete_i'] = b1n_formatHourFromDb($reg_data['leg_ete_i']);
            }
        }
        break;
    }

    if($rs && is_array($rs))
    {
        // If we do have groundtime in last leg we should disable Depart Date input.
        $disable_etd_dt = ($rs['leg_groundtime_i'] == 't');

        // Default Depart date is keeptrack
        $reg_data['leg_etd_dt'] = b1n_formatDateHourFromDb($rs['leg_keeptrack_dt']);
    }
}
?>
<script language="JavaScript">
function b1n_verifyAirports(f)
{
    var d = f.apt_id_depart.options[f.apt_id_depart.selectedIndex]; 
    var a = f.apt_id_arrive.options[f.apt_id_arrive.selectedIndex]; 

    if(d.value.length > 0 && a.value.length > 0)
    {
        // Verifying if the airports selected isnt equal to the ones we already have
        if(d.value != '<?= $reg_data['apt_id_depart'] ?>' || a.value != '<?= $reg_data['apt_id_arrive'] ?>')
        {
            f.action0.value = 'similarleg';
            f.submit();
        }
    }
}
</script>
<br />
<br />
<center>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <form name='form_leg' method="post" action="<?= b1n_URL ?>">
                    <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                    <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                    <input type="hidden" name="action0" value="<?= $action1 ?>" />
                    <input type="hidden" name="action1" value="<?= $action1 ?>" />
                    <input type="hidden" name="last_leg_id" value="<?= $reg_data['last_leg_id'] ?>" />
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="retfizzles" colspan="<?= $colspan ?>" align='center'>
                            <i>Items with the '<b>*</b>' are mandatory</i>
                        <?
                        if($disable_etd_dt)
                        {
                        ?>
                            <br /><b><i>ETD</i></b> is <i>disabled</i> because the last leg have <b>GroundTime</b> defined.
                        <?
                        }

                        if($reg_config['ETD']['mand'])
                        {
                        ?>
                            <br /><b><i>ETD</i></b> is <i>mandatory</i> because this must be the <b>very first leg</b> (special situation indeed).
                        <?
                        }
                        ?>
                        </td>
                    </tr>
<?
foreach($reg_config as $title => $reg)
{
    if($reg['type'] == 'none')
    {
        continue;
    }
?>
                    <tr>
                        <td class='formitem' width='1'><?= $reg['mand'] ? "*" : "&nbsp;" ?></td>
                        <td class='formitem'><?= $title ?></td>
                        <td class='forminput'>
<?
    switch($reg['type'])
    {
    case "text":
?>
                            <input type="<?= $reg['type'] ?>" name="<?= $reg['reg_data'] ?>" value="<?= $reg_data[$reg['reg_data']] ?>" size="<?= $reg['extra']['size'] ?>" maxlength="<?= $reg['extra']['maxlen'] ?>" />
<?
        break;
    case "select":
        if(!isset($reg['extra']['params']))
        {
            $reg['extra']['params'] = array();
        }

        switch($reg['extra']['seltype'])
        {
        case "date":
            echo b1n_buildSelectFromDate($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
            break;
        case "date_hour":
            echo b1n_buildSelectFromDateHour($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['year_start'], $reg['extra']['year_end'], $reg['extra']['params']);
            break;
        case "fk":
            if(!isset($reg['extra']['where']))
            {
                $reg['extra']['where'] = array();
            }

            echo b1n_buildSelectCommon($sql, $reg['extra']['name'], $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['where']);
            break;
        case "defined":
            echo b1n_buildSelect($reg['extra']['options'], $reg_data[$reg['reg_data']], $reg['extra']['params'] + array("name" => $reg['reg_data']));
            break;
        case "hour":
            echo b1n_buildSelectFromHour($reg['reg_data'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['max_hour']);
            break;
        }
        break;
    case "textarea":
?>
                            <textarea name="<?= $reg['reg_data'] ?>" rows="<?= $reg['extra']['rows'] ?>" cols="<?= $reg['extra']['cols'] ?>" wrap="<?= $reg['extra']['wrap'] ?>"><?= b1n_inHtml($reg_data[$reg['reg_data']]) ?></textarea>
<?
        break;
    }
?>
                        </td>
                    </tr>
<?
}
?>
                    <tr>
                        <td colspan='<?= $colspan ?>' class="forminput" align='center'>
                            <script language='JavaScript'>
                            var aux = <?= (($disable_etd_dt)?"true":"false") ?>;
                            var f = document.form_leg;

                            f.elements['leg_etd_dt[month]'].disabled = aux;
                            f.elements['leg_etd_dt[day]'].disabled  = aux;
                            f.elements['leg_etd_dt[year]'].disabled = aux;
                            f.elements['leg_etd_dt[hour]'].disabled = aux;
                            f.elements['leg_etd_dt[min]'].disabled  = aux;
                            </script>
                            <input type="submit" value=" <?= ucfirst($action1) ?> >>" />
                            <input type="button" value=" Cancel >>" onClick="location='<?= b1n_URL . '?page0=' . $page0 . '&page1=' . $page1 ?>'" />
                        </td>
                    </tr>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
</center>
<br />
<br />
