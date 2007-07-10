<?
require(INC_PATH_PHP . "/func.inc.php");

$nome_id = "maq_id";
$dados = array( 'maq_id'	=> 'Nome NetBIOS',
		'maq_desc'	=> 'Descrição');

$campos  = array("maq_id", "maq_desc");
$valores = array("$maq_id", "$maq_desc");
$campos_obrigatorios = array("maq_id");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch.inc.php");

trata_str_out($sn_id);
?>

<!-- Java Script para tratamento dos campos -->
<script language='JavaScript' src='<?= INC_PATH_JS ?>/form.js'></script>

<h1><?= $titulo ?></h1>

<table class='text2'>
<!-- Formulário -->
<form action='<?= $PHP_SELF ?>' method='post' name='f_a' OnSubmit='return Verifica(this,"<?= implode(",",$campos_obrigatorios); ?>");'>
	<tr>
		<td>* Nome NetBIOS</td>
		<td><input type='text' name='maq_id' value='<?= $maq_id ?>' size='30' maxlength='300' OnBlur='Caps(this);'></td>
	</tr>
	<tr>
		<td>Descrição</td>
		<td><input type='text' name='maq_desc' value='<?= $maq_desc ?>' size='30' maxlength='300' OnBlur='Caps(this);'></td>
	</tr>
	<tr>
		<td><input type='submit' name='bt_ok'  value=' <?= $sn_acao ?> '></td>
		<td><input type='reset'  name='bt_rst' value=' <?= $sn_acao == "Alterar" ? "Restaurar" : "Limpar" ?> '></td>
	</tr>
	<input type='hidden' name='sn_inc'  value='<?= $sn_inc;  ?>'>
	<input type='hidden' name='sn_acao' value='<?= $sn_acao; ?>'>
	<input type='hidden' name='sn_id'   value='<?= $sn_id; ?>'>
</form>
</table>

<?= $itens ?>
