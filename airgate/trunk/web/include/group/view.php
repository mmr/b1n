<?
/*
 * expect the following vars:
 * $colspan -> numero de colunas da tabela onde este bloco sera inserido
 * $id -> id do device a ser exibido
 */
$result = view_group($sql,$id);
$result2 = list_device_by_company($sql, $page, M_FULL_NP, $result[ 4 ]);
?>
<tr>
    <td  bgcolor="#FFFFFF" class="textb">Name: <?=$result[1]?></td>
</tr>
<tr>
    <td  bgcolor="#FFFFFF" class="text">Company: <?=$result[2]?></td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" class="text">Desc: <?=$result[3]?></td>
</tr>

<tr>
    <td bgcolor="#FFFFFF">
    <hr>
    Devices
    <table width="100%" cellspacing="0" cellpadding="5">
        <? 
        for($i=0;$i<sizeof($result2);$i++)
            print build_line(array($result2[$i][0],$result2[$i][1],$result2[$i][2],$result2[$i][3],$result2[$i][4],$result2[$i][5],$result2[$i][6]),1,1,1,0,'device'); 
        ?>
    </table>
</tr>
<tr>
    <td bgcolor="#FFFFFF" class='text'>
    <input type="button" value=" Back " OnClick="location='<?= $back_page ?>'">
    </td>
</tr>

<?
unset($result);
?>
