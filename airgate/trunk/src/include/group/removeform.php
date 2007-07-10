<?
    /*
     * expect the following vars:
     * $item -> "group"
     * $id -> id do device a ser removido
     * $remove -> id do grupo do qual o device sera removido
     *
     */

    $result = view_group($sql,$remove,M_SIMPLE);
    $result2 = view_device($sql,$id,M_SIMPLE);
?>

<tr>
    <td  bgcolor="#FFFFFF" class="textb">
        <?message("You're about to remove device &quot;".$result2['cpy_name']." - ".$result2['dev_name']."&quot; from group &quot;".$result['cpy_name']." - ".$result['dvg_name']."&quot;. Do you want to proceed ?",WARNING)?>
        <table><tr><td>
        <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
            <input type="hidden" value="<?=$id?>" name="dev_id">
            <input type="hidden" value="<?=$remove?>" name="dvg_id">
            <input type="hidden" name="action" value="remove">
            <input type="hidden" name="item" value="<?=$item?>">
            <input type="submit" value="Yes">
        </form></td><td>
        <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
            <input type="submit" value="No">
            <input type="hidden" name="item" value="<?=$item?>">
        </form>
        </td></tr></table>
    </td>
</tr>
<?  unset($result);
    unset($result2);
?>


