<?
require(INC_PATH_PHP . "/func.inc.php");

$nome_id = "prd_id";
$dados = array( 'prd_id'	=> 'Código',
		'prd_desc'	=> 'Descrição',
		'prd_preco'	=> 'Preço',
		'prd_ativo'	=> 'Ativo');


$prd_preco = reconhece_dinheiro($prd_preco);

$campos  = array("prd_desc", "prd_preco", "prd_ativo");
$valores = array("$prd_desc", "$prd_preco", "$prd_ativo");
$campos_obrigatorios = array("prd_preco","prd_ativo");

/* inclui switch das ações */
$prd='yeah';
include(INC_PATH_PHP . "/switch.inc.php");

trata_str_out($sn_id);
?>

<table>
<? 
    array_push($msgs,$titulo);
    print_messages($msgs);
?>
</table>


<form action='<?= $PHP_SELF ?>' method='post' name='f_a' OnSubmit='return Verifica(this,"<?= implode(",",$campos_obrigatorios); ?>");'>
	<input type=hidden name=item value="<?=$item?>">
<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
<!-- Formulário -->
	<tr>
		<td class=textb BGCOLOR="#E1E1E1">Descrição</td>
		<td class=text BGCOLOR="#E1E1E1"><input type='text' name='prd_desc' value='<?= $prd_desc ?>' size='30' maxlength='300' OnBlur='Caps(this);'></td>
	</tr>
	<tr>
		<td class=textb BGCOLOR="#E1E1E1">* Preço</td>
		<td class=text BGCOLOR="#E1E1E1"><input type='text' name='prd_preco' value='<?= $prd_preco ?>' OnBlur='ChecaNum(this);' size='30' maxlength='12'></td>
	</tr>
	<tr>
		<td class=textb BGCOLOR="#E1E1E1">* Ativo</td>
		<td class=text BGCOLOR="#E1E1E1">
			<input type='radio' name='prd_ativo' value='t'<? if($prd_ativo == "t" || !$prd_ativo) print " checked"; ?>>Sim
			&nbsp;&nbsp;&nbsp;
			<input type='radio' name='prd_ativo' value='f'<? if($prd_ativo == "f") print " checked"; ?>>Não
		</td>
	</tr>
	<tr>
		<td class=textb BGCOLOR="#E1E1E1">&nbsp;</td>
		<td class=text BGCOLOR="#E1E1E1"><input type='submit' name='bt_ok'  value=' <?= $sn_acao ?> '>&nbsp;&nbsp;
		    <input type='reset'  name='bt_rst' value=' <?= $sn_acao == "Alterar" ? "Restaurar" : "Limpar" ?> '></td>
	</tr>
</table>
</td></tr>
</table>
<input type='hidden' name='sn_inc'  value='<?= $sn_inc  ?>'>
<input type='hidden' name='sn_acao' value='<?= $sn_acao ?>'>
<input type='hidden' name='sn_id'   value='<?= $sn_id   ?>'>
</form>

<?= $itens ?>
