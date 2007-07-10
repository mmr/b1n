<?
require(INC_PATH_PHP . "/func.inc.php");
require(INC_PATH_PHP . "/func_cns.inc.php");

/* inclui switch das ações */
include(INC_PATH_PHP . "/switch_cns.inc.php");

print "<table>";
array_push($msgs,$titulo);
print_messages($msgs);
print "</table>";
if($formulario)
{
?>
<form action='<?= $PHP_SELF ?>' method='post' name='f_b' OnSubmit="Verifica(this,'usr_busca');">
  <table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550">
    <tr>
      <td>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<?=$formulario?>
	</table>
      </td>
    </tr>
  </table>
<input type="hidden" name="sn_inc"  value="<?=$sn_inc?>">
<input type="hidden" name="sn_acao" value="<?=$sn_acao?>">
<input type="hidden" name="sn_id"   value="<?=$sn_id?>">
<input type=hidden name=item value='<?= $item ?>'>
</form>
<?
}
?>

<?= $coisas ?>
