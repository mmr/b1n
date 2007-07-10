<?
    /*
     * expect the following vars:
     * $colspan -> numero de colunas da tabela onde este bloco sera inserido
     */


    $r = list_company($sql,1,M_SIMPLE);
    $r_dvtype   = list_dvtype($sql,1,M_SIMPLE);

    if( $id != '0' && $editing == '' )
    {
        $a = view_group($sql,$id);
        $name = $a[1];
        $descr = $a[3];
        $company = $a[4];
        $dvtype = $a[5];
    }
?>
<tr>
    <td bgcolor="#FFFFFF" class="text">Name:</td>
    <td bgcolor="#FFFFFF"><input name="name" type="text" size="35" value="<?=htmlspecialchars($name)?>"></td>
</tr>
<tr>
    <td class="text" bgcolor="#FFFFFF">Description:</td>
    <td bgcolor="#FFFFFF"><input name="descr" type="text" size="35" value="<?=htmlspecialchars($descr)?>"></td>
</tr>
<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">Company:</td>
    <td bgcolor="#FFFFFF">
        <select name="company">
            <?=html_option($r,$company);?>
        </select>
    </td>
</tr>

<script language='JavaScript'>
function device_type( f )
{
    if( f.action.value == 'add' )
        f.action.value = 'addform';
    else if( f.action.value == 'edit' )
        f.action.value = 'editform';
    else
        return false;

    f.submit();
}
</script>

<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">Type:</td>
    <td bgcolor="#FFFFFF">
        <select name="dvtype" OnChange="if( this.value != '' ) { device_type( this.form ); } else { alert( 'You need to choose a Type for the Group' ); }" >
            <?=html_option($r_dvtype, $dvtype);?>
        </select>
    </td>
</tr>

<?
if( $dvtype )
{
?>
<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">Devices:</td>
    <td bgcolor="#FFFFFF">
        <?= build_select_device( $sql, $dvtype, $id ) ?>
    </td>
</tr>
<?
}
?>

<tr>
    <td colspan="<?=$colspan?>" bgcolor="#FFFFFF">
    <?
    if( $action == 'editform' )
    {
    ?>
        <input type="hidden" name="editing" value="yeah">
        <input type="hidden" name="action" value="edit">
    <?
    }
    elseif( $action == 'addform' )
    {
    ?>
        <input type="hidden" name="action" value="add">
    <?
    }
    ?>

    <input type="submit" value="OK">&nbsp;&nbsp;<input type="button" value="Cancel" OnClick="location='<?= "abb" ?>'">
</tr>

<?
    unset($a);
    unset($name);
    unset($descr);
    unset($company);
    unset($r_dvtype);
?>
