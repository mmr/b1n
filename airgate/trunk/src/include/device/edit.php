<?
    /*
     * expect the following vars:
     * $colspan -> numero de colunas da tabela onde este bloco sera inserido
     */
    $r_company  = list_company($sql,1,M_SIMPLE);
    $r_iface    = list_iface($sql,1,M_SIMPLE);
    $r_dvtype   = list_dvtype($sql,1,M_SIMPLE);

    if( $id != '0' && $editing == '' )
    {
        $a = view_device($sql, $id);
        $company = $a[7];
        $name = $a[2];
        $address = $a[3];
        $nat = $a[4];
        $iface = $a[8];
        $dvtype = $a[9];
    }
?>
<tr>
    <td bgcolor="#FFFFFF" class="text">Name:</td>
    <td bgcolor="#FFFFFF"><input name="name" type="text" size="35" value="<?=htmlspecialchars($name)?>"></td>
</tr>
<tr>
    <td class="text" bgcolor="#FFFFFF">Address:</td>
    <td bgcolor="#FFFFFF"><input name="address" type="text" size="35" value="<?=htmlspecialchars($address)?>"></td>
</tr>
<tr>
    <td class="text" bgcolor="#FFFFFF">NAT:</td>
    <td bgcolor="#FFFFFF"><input name="nat" type="text" size="35" value="<?=htmlspecialchars($nat)?>"></td>
</tr>
<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">Company:</td>
    <td bgcolor="#FFFFFF">
        <select name="company">
            <?=html_option($r_company,$company);?>
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
        <select name="dvtype" OnChange='device_type( this.form )'>
            <?=html_option($r_dvtype,$dvtype);?>
        </select>
    </td>
</tr>
<?
if( $dvtype == 'PPTP' )
{
    if( $id != '0' )
    {
        $rs = $sql->squery( "SELECT ppt_login, ppt_passwd FROM pptp WHERE dev_id = '" . $id .  "'" );

        if( is_array( $rs ) )
        {
            $ppt_login = $rs[ 'ppt_login' ];
            $ppt_passwd  = $rs[ 'ppt_passwd' ];
        }    
        else
        {
            $ppt_login = '';
            $ppt_passwd  = '';
        }
    }
?>
    <tr>
        <td width="30%" bgcolor="#FFFFFF" class="text">Login:</td>
        <td bgcolor="#FFFFFF" class='text'><input type='text' name='ppt_login' size='35' value='<?= $ppt_login ?>'></td>
    </tr>
    <tr>
        <td width="30%" bgcolor="#FFFFFF" class="text">Password:</td>
        <td bgcolor="#FFFFFF" class='text'>
            <input type='password' name='ppt_passwd' size='35'>
            <input type='hidden' name='ppt_passwd_old' value='<?= $ppt_passwd ?>'>
        </td>
    </tr>
    <tr>
        <td width="30%" bgcolor="#FFFFFF" class="text">Password Confirmation:</td>
        <td bgcolor="#FFFFFF" class='text'><input type='password' name='ppt_passwd2' size='35' onblur='if( this.value != this.form.ppt_passwd.value ){ alert( "Password and Password Confirmation must match" ); this.value = this.form.ppt_passwd.value = ""; this.form.ppt_passwd.focus(); }'></td>
    </tr>
<?
}
elseif( $dvtype == 'MAN' )
{
    if( $id != '0' )
    {
        $rs = $sql->squery( "SELECT man_code FROM man WHERE dev_id = '" . $id .  "'" );

        if( is_array( $rs ) )
        {
            $man_code = $rs[ 'man_code' ];
        }    
        else
        {
            $man_code = '';
        }
    }
?>
    <tr>
        <td width="30%" bgcolor="#FFFFFF" class="text">MAN Code:</td>
        <td bgcolor="#FFFFFF" class='text'><input type='text' name='man_code' size='35' value='<?= $man_code ?>'></td>
    </tr>
<?
}
?>
<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">Interface:</td>
    <td bgcolor="#FFFFFF">
        <select name="iface">
            <?=html_option($r_iface,$iface);?>
        </select>
    </td>
</tr>
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

    <input type="submit" value="OK">&nbsp;&nbsp;<input type="button" value="Cancel" OnClick="location='<?= $back_page ?>'">
</tr>
<?
    unset($r_company);
    unset($r_iface);
    unset($r_dvtype);
    unset($name);
    unset($address);
    unset($nat);
    unset($dvtype);
    unset($iface);
    unset($company);
    unset($a);
    unset($editting);
?>
