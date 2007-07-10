<?
require(INC_PATH_PHP . "/func.inc.php");
require(INC_PATH_PHP . "/func_usr.inc.php");

$nome_id = "usr_id";
$dados = array( 'usr_id'	=> 'Login',
		'usr_nome'	=> 'Nome');

$campos  = array("usr_id", "usr_senha", "usr_nome");
$valores = array("$usr_id", "$usr_senha", "$usr_senha2", "$usr_nome");
$campos_obrigatorios = array("usr_id","usr_senha");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch_usr.inc.php");

trata_str_out($sn_id);
?>

<!-- Java Script para tratamento dos campos -->
<script language='JavaScript' src='<?= INC_PATH_JS ?>/form.js'></script>

<h1><?= $titulo ?></h1>

<table class='text2'>
<!-- Formulário -->
<form action='<?= $PHP_SELF ?>' method='post' name='f_a' OnSubmit='return ChecaSenha(this) && Verifica(this,"<?= implode(",",$campos_obrigatorios); ?>");'>
	<tr>
		<td>* Login</td>
		<td><input type='text' name='usr_id' value='<?= $usr_id ?>' size='30' maxlength='300'></td>
	</tr>
	<tr>
		<td>Nome</td>
		<td><input type='text' name='usr_nome' value='<?= $usr_nome ?>' size='30' maxlength='300' OnBlur='Caps(this);'></td>
	</tr>
	<tr>
		<td>* Senha</td>
		<td><input type='password' name='usr_senha'  value='<?= $usr_senha || (!$usr_senha && $usr_id) ? "******" : "" ?>' size='30' maxlength='300'></td>
	</tr>
	<tr>
		<td>* Confirmação da Senha</td>
		<td><input type='password' name='usr_senha2' value='<?= $usr_senha || (!$usr_senha && $usr_id) ? "******" : "" ?>' size='30' maxlength='300'></td>
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
