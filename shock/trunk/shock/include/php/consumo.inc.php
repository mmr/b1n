<?
require(INC_PATH_PHP . "/func.inc.php");
require(INC_PATH_PHP . "/func_cns.inc.php");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch_cns.inc.php");
?>

<!-- Java Script para tratamento dos campos -->
<script language='JavaScript' src='<?= INC_PATH_JS ?>/form.js'></script>

<h1><?= $titulo ?></h1>

<table class='text2'>
<?
if($formulario)
{
	print "
		$formulario
		<input type='hidden' name='sn_inc'  value='$sn_inc'>
		<input type='hidden' name='sn_acao' value='$sn_acao'>
		<input type='hidden' name='sn_id'   value='$sn_id'>
	</form>
	";
}
?>
</table>

<?= $coisas; ?>
