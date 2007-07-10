<?
require(INC_PATH_PHP . "/func.inc.php");

$nome_id = "prd_id";
$dados = array( 'prd_id'	=> 'Código',
		'prd_desc'	=> 'Descrição',
		'prd_preco'	=> 'Preço',
		'prd_ativo'	=> 'Ativo');


$campos  = array("prd_desc", "prd_preco", "prd_ativo");
$valores = array("$prd_desc", "$prd_preco", "$prd_ativo");
$campos_obrigatorios = array("prd_preco","prd_ativo");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch.inc.php");
?>

<!-- Java Script para tratamento dos campos -->
<script language='JavaScript' src='<?= INC_PATH_JS ?>/form.js'></script>

<h1><?= $titulo ?></h1>

<table>
<!-- Formulário -->
<form action='<?= $PHP_SELF ?>' method='post' name='f_a' OnSubmit='return Verifica(this,"<?= implode(",",$campos_obrigatorios); ?>");'>
	<tr>
		<td>Descrição</td>
		<td><input type='text' name='prd_desc' value='<?= $prd_desc ?>' size='30' maxlength='300' OnBlur='Caps(this);'></td>
	</tr>
	<tr>
		<td>* Preço</td>
		<td><input type='text' name='prd_preco' value='<?= $prd_preco ?>' OnBlur='ChecaNum(this);' size='30' maxlength='12'></td>
	</tr>
	<tr>
		<td>* Ativo</td>
		<td>
			<input type='radio' name='prd_ativo' value='t'<? if($prd_ativo == "t" || !$prd_ativo) print " checked"; ?>>Sim
			&nbsp;&nbsp;&nbsp;
			<input type='radio' name='prd_ativo' value='f'<? if($prd_ativo == "f") print " checked"; ?>>Não
		</td>
	</tr>
	<tr>
		<td><input type='submit' name='bt_ok'  value=' <?= $sn_acao ?> '></td>
		<td><input type='reset'  name='bt_rst' value=' <?= $sn_acao == "Alterar" ? "Restaurar" : "Limpar" ?> '></td>
	</tr>
	<input type='hidden' name='sn_inc'  value='<?= $sn_inc  ?>'>
	<input type='hidden' name='sn_acao' value='<?= $sn_acao ?>'>
	<input type='hidden' name='sn_id'   value='<?= $sn_id   ?>'>
</form>
</table>

<?= $itens ?>
