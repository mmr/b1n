<?
    $result = view_company($sql,$id);
    $result2 = list_device_by_company($sql, $page, M_FULL_NP, $id);
    $result3 = list_group_by_company($sql,  $id);
?>
<tr>
        <td  bgcolor="#FFFFFF" class="textb"><?=$result[1]?></td>
</tr>
<tr>
    <td width="40%" bgcolor="#FFFFFF" class="textb"><br>
        <input type="button" value="Add New Device" OnClick="javascript:location='<?=$_SERVER['SCRIPT_NAME']?>?item=device&action=addform&company=<?=$id?>'">&nbsp;
        <input type="button" value="Create New Group" OnClick="javascript:location='<?=$_SERVER['SCRIPT_NAME']?>?item=group&action=addform&company=<?=$id?>'">
    </td>
</tr>
<tr>
    <td bgcolor="#FFFFFF">
    <hr>
    <table width="100%" cellspacing="0" cellpadding="5">
        <? 
        for($i=0;$i<sizeof($result2);$i++)
            print build_line(array($result2[$i][0],$result2[$i][1],$result2[$i][2],$result2[$i][3],$result2[$i][4],$result2[$i][5],$result2[$i][6]),1,1,1,0,'device'); 
        ?>
    </table>
</tr>
<tr>
    <td bgcolor="#FFFFFF">
    <hr>
    <table width="100%" cellspacing="0" cellpadding="5">
        <?
        for($i=0;$i<sizeof($result3);$i++)
            print build_line(array($result3[$i][0],$result3[$i][1],$result3[$i][2],$result3[$i][3]),1,1,1,0,'group'); 
        ?>
    </table>
</tr>
<?
    unset($result);
    unset($result2);
    unset($result3);
?>
