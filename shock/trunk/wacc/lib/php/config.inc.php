<?
require(INC_PATH_PHP . "/func.inc.php");
require(INC_PATH_PHP . "/func_cfg.inc.php");

$campos_obrigatorios = array("price_per_unit","time_unit","tolerance");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch_cfg.inc.php");
?>

<table>
<? 
	array_push($msgs,$titulo);
	print_messages($msgs);
?>
</table>


<form action='<?= $PHP_SELF ?>' method='post' name='f_a' OnSubmit='return Verifica(this,"<?= implode(",",$campos_obrigatorios); ?>");'>
<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
	<tr>
		<td class="textb" BGCOLOR="#E1E1E1">* Unidade de Tempo</td>
		<td class="text" BGCOLOR="#E1E1E1"><input type='text' name='time_unit' value='<?= $time_unit ?>' OnBlur='ChecaNum(this);' size='30' maxlength='5'> Minutos</td>
	</tr>
	<tr>
		<td class="textb" BGCOLOR="#E1E1E1">* Preço por Unidade de Tempo</td>
		<td class="text" BGCOLOR="#E1E1E1"><input type='text' name='price_per_unit' value='<?= $price_per_unit ?>' size='30' maxlength='30' OnBlur='ChecaNum(this);'> Reais</td>
	</tr>
	<tr>
		<td class="textb" BGCOLOR="#E1E1E1">* Tolerância</td>
		<td class="text" BGCOLOR="#E1E1E1"><input type='text' name='tolerance' value='<?= $tolerance ?>' OnBlur='ChecaNum(this);' size='30' maxlength='5'> Minutos</td>
	</tr>
	<tr>
		<td class="textb" BGCOLOR="#E1E1E1">&nbsp;</td>
		<td class="text" BGCOLOR="#E1E1E1">
	 	  <input type='submit' name='bt_ok'  value=' <?= $sn_acao ?> '>&nbsp;&nbsp;
		  <input type='reset'  name='bt_rst' value=' Restaurar '>
		</td>
	</tr>
</table></td></tr></table>
	<input type='hidden' name='sn_inc'  value='<?= $sn_inc  ?>'>
	<input type='hidden' name='sn_acao' value='<?= $sn_acao ?>'>
	<input type="hidden" name="item"    value="<?= $item    ?>">
</form>
