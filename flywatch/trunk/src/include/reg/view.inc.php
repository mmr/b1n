<?
/* $Id: view.inc.php,v 1.20 2002/12/25 22:55:29 binary Exp $ */ 
?>
<center>
    <br />
    <br />
    <table cellspacing="0" cellpadding="0" class="maintable">
        <tr>
            <td>
                <table cellspacing="1" cellpadding="5" class="inttable">
                    <input type="hidden" name="action0" value="<?= $action1 ?>" />
                    <input type="hidden" name="action1" value="<?= $action1 ?>" />
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;&nbsp;<?= $page1_title . " - " . ucfirst($action1) ?>
                        </td>
                    </tr>
<?
foreach($reg_config as $title => $reg)
{
    if($reg['type'] == 'none' || !$reg['load'])
    {
        continue;
    }
?>
                    <tr>
                        <td class='formitem'><?= $title ?></td>
                        <td class='forminput'>
<?
    switch($reg['type'])
    {
    case "text":
    case "textarea":
        if($reg['check'] == 'email' &&
           !empty($reg_data[$reg['reg_data']]))
        {
            echo "&nbsp;<a href='mailto:" . $reg_data[$reg['reg_data']] . "'>" . $reg_data[$reg['reg_data']] . "</a>";
        }
        else
        {
            echo "&nbsp;" . b1n_inHtml($reg_data[$reg['reg_data']]);
        }
        break;
    case "select":
        switch($reg['extra']['seltype'])
        {
        case "date":
            echo b1n_formatDateShow($reg_data[$reg['reg_data']]);
            break;
        case "date_hour":
            echo b1n_formatDateHourShow($reg_data[$reg['reg_data']]);
            break;
        case "defined":
            foreach($reg['extra']['options'] as $opt_title => $opt_value)
            {
                if($reg_data[$reg['reg_data']] == $opt_value)
                {
                    echo $opt_title;
                    break;
                }
            }
            break;
        case "fk":
            if(!isset($reg['extra']['params']))
            {
                echo b1n_viewSelected($sql, $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']]);
            }
            else
            {
                if(!isset($reg['extra']['where']))
                {
                    $reg['extra']['where'] = array();
                }
                echo b1n_buildSelectCommon($sql, $reg['extra']['name'], $reg['extra']['value'], $reg['extra']['text'], $reg['extra']['table'], $reg_data[$reg['reg_data']], $reg['extra']['params'], $reg['extra']['where']);
            }
            break;
        case "date_check_exp":
            echo b1n_formatDateCheckExpShow($reg_data[$reg['reg_data']]);
            break;
        case "date_check_dob":
            echo b1n_formatDateCheckDobShow($reg_data[$reg['reg_data']]);
            break;
        }
        break;
    case "radio":
        foreach($reg['extra']['options'] as $opt_title => $opt_value)
        {
            if($reg_data[$reg['reg_data']] == $opt_value)
            {
                echo $opt_title;
                break;
            }
        }
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
                            <form method="post" action="<?= b1n_URL ?>">
                            <input type="hidden" name="page0"   value="<?= $page0 ?>" />
                            <input type="hidden" name="page1"   value="<?= $page1 ?>" />
                            <input type="hidden" name="action0" value="" />
                            <input type="hidden" name="action1" value="" />
                            <input type="submit" value=" << Back" />
                        </td>
                    </tr>
                    </form>
                    <tr>
                        <td class="box" colspan="<?= $colspan ?>">&nbsp;</td>
                    </tr>
                    </form>
                </table>
            </td>
        </tr>
    </table>
    <br />
    <br />
</center>
