<?
    $result = list_acl($sql,$page,M_SIMPLE);
?>
<input type="hidden" name="action" value="addform"> 
<tr>
    <td colspan="<?=$colspan?>" bgcolor="#FFFFFF" class="textb">
        <input type="submit" value="Add new">
    </td>
<tr>
    <td bgcolor="#FFFFFF" class="text">&nbsp;</td>
    <td class="textb" bgcolor="#FFFFFF">ACLs:</td>
</tr>
<?
for($i=0;$i<sizeof($result[1]);$i++)
    print build_line(array($result[1][$i][0],$result[1][$i][1]." &lt;--&gt; ".$result[1][$i][2]),0,0,1,0,'acl');



print build_pagination( $item, $page, $result[ 0 ], $colspan );
unset($result);
?>
