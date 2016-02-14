<?
/* $Id: view.php,v 1.3 2002/12/24 12:04:55 binary Exp $ */
$colspan = 2;
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
                    <tr>
                        <td class='formitem'>File</td>
                        <td class='forminput'>&nbsp;<?= "<a href='" . b1n_URL . "?page0=" . $page0 . "&page1=download&id=" . $reg_data['id'] . "'>" . $reg_data['fil_fake_name'] . "</a>" ?></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Type</td>
                        <td class='forminput'>
<?
foreach($reg_config['Type']['extra']['options'] as $opt_title => $opt_value)
{
    if($reg_data['fil_type'] == $opt_value)
    {
        echo $opt_title;
        break;
    }
}
?>
                        
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem'>Desc</td>
                        <td class='forminput'><?= b1n_inHtml($reg_data['fil_desc']) ?></td>
                    </tr>
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
