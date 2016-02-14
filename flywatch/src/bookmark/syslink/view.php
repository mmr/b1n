<?
/* $Id: view.php,v 1.2 2002/12/19 02:51:23 binary Exp $ */
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
                        <td class='formitem'>Name</td>
                        <td class='forminput'>&nbsp;<?= "<a href='" . $reg_data['slk_url'] . "' target='_blank'>" . $reg_data['slk_name'] . "</a>" ?></td>
                    </tr>
                    <tr>
                        <td class='formitem'>Desc</td>
                        <td class='forminput'><?= b1n_inHtmlLimit($reg_data['slk_desc']) ?></td>
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
