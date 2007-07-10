<? 
    /*
     * expect the following vars:
     * $colspan -> numero de colunas da tabela onde o search sera inserido
     * $item -> secao onde o search esta sendo feito (group, device, company, acl)
     */
?>
<tr>
    <td colspan="<?=$colspan?>" bgcolor="#FFFFFF" class="textb">Search: <input type="text" size="35"> &nbsp; <input type="button" value="OK">
    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
        <input type="hidden" name="item" value="<?=$item?>">
    </form></td>
</tr>

