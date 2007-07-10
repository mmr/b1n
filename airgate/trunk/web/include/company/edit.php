<?
$a = view_company($sql,$id);
$name = $a[1];
?>
<tr>
    <td bgcolor="#FFFFFF" class="text">Name:</td>
    <td bgcolor="#FFFFFF"><input name="name" type="text" size="35" value="<?=htmlspecialchars($name)?>"></td>
</tr>
<tr>
    <td colspan="<?=$colspan?>" bgcolor="#FFFFFF">
    <input type="hidden" name="action" value="<?=($action=='addform')?'add':'edit'?>">
    <input type="submit" value="OK">&nbsp;&nbsp;<input type="button" value="Cancel" OnClick="location='<?= $back_page ?>'">
</tr>
<?
unset($a);
unset($name);
?>
