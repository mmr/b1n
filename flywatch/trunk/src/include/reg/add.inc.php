<?
/* $Id: add.inc.php,v 1.29 2002/12/25 22:55:29 binary Exp $ */ 
?>
<br />
<br />
<center>
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <form method="post" action="<?= b1n_URL ?>">
                    <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                    <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                    <input type="hidden" name="action0" value="<?= $action1 ?>" />
                    <input type="hidden" name="action1" value="<?= $action1 ?>" />
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="retfizzles" colspan="<?= $colspan ?>" align='center'>
                            <i>Items with the '<b>*</b>' are mandatory</i>
                            <?
                                if(isset($title_msg) && !empty($title_msg))
                                {
                                    echo "<br /><i>" . $title_msg . "</i>";
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
    case "password":
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
        case "date_check_exp":
        case "date_check_dob":
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
        }
        break;
    case "radio":
        foreach($reg['extra']['options'] as $opt_title => $opt_value)
        {
?>
                            <input type="radio" name="<?= $reg['reg_data'] ?>" value="<?= $opt_value ?>" class="noborder"<? if($opt_value == $reg_data[$reg['reg_data']]){ echo " checked"; } ?> /> <?= $opt_title ?><br />
<?
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
