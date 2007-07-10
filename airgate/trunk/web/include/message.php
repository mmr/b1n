<? 
    /*
     * expect the following vars:
     * $colspan -> numero de colunas da tabela onde este bloco sera inserido
     * $message -> array no formato usado pela funcao message
     */
?>
<tr colspan="<?=$colspan?>">
    <td bgcolor="#FFFFFF" class="text" colspan="2"><?message($message[0],$message[1])?></td>
</tr>

