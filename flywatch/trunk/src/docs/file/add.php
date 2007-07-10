<?
/* $Id: add.php,v 1.5 2003/03/21 00:28:04 binary Exp $ */ 
$colspan = 3;
?>
<script>
function b1n_tryToIdentify(o)
{
    var f = o.form;
    var img = new RegExp("\.(([Jj][Pp][Gg])|([Gg][Ii][Ff])|([Pp][Nn][Gg])|([Bb][Mm][Pp]))$");
    var pdf = new RegExp("\.[Pp][Dd][Ff]$");

    // Assuming: 0- ---, 1-Img, 2-PDF, 3-Misc
    if(img.test(o.value))
    {
        f.fil_type.selectedIndex = 1;
    }
    else if(pdf.test(o.value))
    {
        f.fil_type.selectedIndex = 2;
    }
    else
    {
        f.fil_type.selectedIndex = 3;
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
                    <form method="post" action="<?= b1n_URL ?>" enctype="multipart/form-data">
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
                            <br />
                            <?
                            if($action1 == 'change')
                            {
                            ?>
                                <i>If you do not want to change the current file, just leave <b>File</b> field blank.</i>
                                <input type='hidden' name='id' value='<?= $reg_data['id'] ?>' />
                                </td>
                            </tr>
                            <? 
                                if(strlen($reg_data['fil_fake_name']))
                                {
                            ?>
                    <tr>
                        <td class='formitem'>&nbsp;</td>
                        <td class='formitem'>Current File</td>
                        <td class='forminput'>
                            <?= "<a href='" . b1n_URL . "?page0=" . $page0 . "&page1=download&id=" . $reg_data['id'] . "'>" . $reg_data['fil_fake_name'] . "</a>" ?>
                            <input type='hidden' name='fil_fake_name' value='<?= $reg_data['fil_fake_name'] ?>' />
                            <?
                                }
                            }
                            else
                            {
                                $current = "";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem' width='1'>*</td>
                        <td class='formitem'>File</td>
                        <td class='forminput'>
                            <input type='file' name='file' onChange='b1n_tryToIdentify(this);' />
                        </td>
                    </tr>
                    <tr>
                        <td class='formitem' width='1'>&nbsp;</td>
                        <td class='formitem'>Type</td>
                        <td class='forminput'>
<?
$options = array("Image" => "I",
                 "PDF"   => "P",
                 "Misc"  => "M");

echo b1n_buildSelect($options, $reg_data['fil_type'], array('name' => 'fil_type'))
?>
                        </td>

                    </tr>
                    <tr>
                        <td class='formitem' width='1'>&nbsp;</td>
                        <td class='formitem'>Desc</td>
                        <td class='forminput'>
                            <textarea name="fil_desc" rows="<?= b1n_DEFAULT_ROWS ?>" cols="<?= b1n_DEFAULT_COLS ?>" wrap="<?= b1n_DEFAULT_WRAP ?>"><?= b1n_inHtml($reg_data['fil_desc']) ?></textarea>
                        </td>
                    </tr>
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
