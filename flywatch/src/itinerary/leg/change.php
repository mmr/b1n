<?
/* $Id: change.php,v 1.13 2004/09/28 22:35:22 mmr Exp $ */ 
$disable_etd_dt = false;
$colspan = 3;

// Checking if that is the first time
if(empty($reg_data['leg_etd_dt']) || empty($reg_data['leg_etd_dt']['year'])){
    $reg_data['leg_etd_dt'] = b1n_formatDateHourFromDb($reg_data['leg_keeptrack_dt']);
}

// Checking if last leg has groundtime
$query = "
    SELECT
        leg_id as last_leg_id,
        leg_groundtime_i IS NOT NULL AS have_groundtime
    FROM
        \"leg\"
    WHERE
        leg_keeptrack_dt < '" . b1n_inBd(b1n_formatDateHour($reg_data['leg_etd_dt'])) . "'
    ORDER BY
        leg_keeptrack_dt DESC";

$rs = $sql->singleQuery($query);

if($rs && is_array($rs))
{
    $reg_data['last_leg_id'] = $rs['last_leg_id'];
    if($rs['have_groundtime'] == 't')
    {
        // Yes, it has, disable etd_dt select box
        $disable_etd_dt = true;
    }
}
else
{
    // Prolly very first leg, so, ETD is mandatory
    $reg_config['ETD']['mand'] = true;
}

// Seeing if we have to check for leg similarity
if($action0 == 'similarleg' &&
   b1n_checkNumeric($reg_data['apt_id_depart'], true) &&
   b1n_checkNumeric($reg_data['apt_id_arrive'], true))
{
    $aux = b1n_regDefaultLegSearchSimilar($sql, $reg_data['apt_id_depart'], $reg_data['apt_id_arrive']);

    if($aux)
    {
        list($reg_data['leg_ete_i'], $reg_data['leg_distance'], $reg_data['leg_fuel']) = explode('|', $aux);
        unset($aux);

        if($reg_data['leg_fuel'] === 0)
        {
            $reg_data['leg_fuel'] = '';
        }

        $reg_data['leg_ete_i'] = b1n_formatHourFromDb($reg_data['leg_ete_i']);
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
                    <input type="hidden" name="id" value="<?= $reg_data['id'] ?>" />
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
                            var aux = <?= ((bool)$disable_etd_dt) ?>;
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
