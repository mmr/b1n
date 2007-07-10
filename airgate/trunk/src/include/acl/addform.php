<?
    $fixed = list_group_by_type($sql,"FIXO");
    $wles = list_group_by_type($sql,"WLES");
?>
<tr>
    <td width="30%" bgcolor="#FFFFFF" class="text">New ACL:</td>
    <td bgcolor="#FFFFFF" class="text">
    Groups
    <select name="dvg1">
        <?=html_option($fixed,$dvg1);?> 
    </select> and
    <select name="dvg2">
        <?=html_option($wles,$dvg2);?>
    </select> may communicate
</tr>
<tr>
    <td colspan="2" bgcolor="#FFFFFF">
        <input type="hidden" name="action" value="add">
        <input type="submit" value="OK">&nbsp;&nbsp;<input type="button" value="Cancel" OnClick="location='<?= $back_page ?>'">
    </td>
</tr>
