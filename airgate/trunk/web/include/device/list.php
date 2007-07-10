<?
    /*
     * expect the following vars:
     * $colspan -> numero de colunas da tabela onde este bloco sera inserido
     */
    $result = list_device($sql,$page);
?>
<input type="hidden" name="action" value="addform">
<tr>
    <td colspan="<?=$colspan?>" bgcolor="#FFFFFF" class="textb">
        <input type="submit" value="Add new">
    </td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" class="text">&nbsp;</td>
    <td bgcolor="#FFFFFF" class="text">&nbsp;</td>
    <td bgcolor="#FFFFFF" class="text">&nbsp;</td>
    <td class="textb" bgcolor="#FFFFFF">Company:</td>
    <td bgcolor="#FFFFFF" class="textb">Name:</td>
    <td bgcolor="#FFFFFF" class="textb">Address:</td>
    <td bgcolor="#FFFFFF" class="textb">NAT:</td>
    <td bgcolor="#FFFFFF" class="textb">Type:</td>
</tr>
<?
for($i=0;$i<sizeof($result[1]);$i++)
    print build_line(array($result[1][$i][0],$result[1][$i][1],$result[1][$i][2],$result[1][$i][3],$result[1][$i][4],$result[1][$i][6]),1,1,1,0,'device');

print build_pagination( $item, $page, $result[0], $colspan );
unset($result);
?>
