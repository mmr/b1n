<?
    /*
     * expect the following vars:
     * $item -> "group"
     * $id -> id do grupo a ser apagado
     *
     */
?>
<tr>
    <td  bgcolor="#FFFFFF" class="textb">
        <?message("You're about to remove the following group. Do you want to proceed ?",WARNING)?>
        <table><tr><td>
        <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
            <input type="hidden" value="<?=$id?>" name="id">
            <input type="hidden" name="action" value="del">
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

