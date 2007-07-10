<?
    $result = view_device($sql,$id);
?>
<tr>
        <td  bgcolor="#FFFFFF" class="textb">Company: <?=$result[1]?></td>
</tr>
<tr>
        <td  bgcolor="#FFFFFF" class="text">Device: <?=$result[2]?></td>
</tr>
<tr>
        <td bgcolor="#FFFFFF" class="text">Address: <?=$result[3]?></td>
</tr>
<tr>
        <td bgcolor="#FFFFFF" class="text">NAT: <?=$result[4]?$result[4]:"NONE"?></td>
</tr>
<tr>
        <td bgcolor="#FFFFFF" class="text">Interface: <?=$result[5]?></td>
</tr>
<tr>
        <td bgcolor="#FFFFFF" class="text">Type: <?=$result[6]?></td>
</tr>
<?
    unset($result);
?>
