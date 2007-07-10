<?
/*
 *
 * Esse arquivo (switch_relat.inc.php), é parte integrante dos 'eventos' (sn_acao),
 * e não pode ser vendido separadamente
 *
 */

if(checkdate($dt_ini_m, $dt_ini_d, $dt_ini_a))
	$dt_ini = "$dt_ini_d/$dt_ini_m/$dt_ini_a";
else
	list($dt_ini_d, $dt_ini_m, $dt_ini_a) = split("/", date("d/m/Y"));

if(checkdate($dt_fim_m, $dt_fim_d, $dt_fim_a))
	$dt_fim = "$dt_fim_d/$dt_fim_m/$dt_fim_a";
else
	list($dt_fim_d, $dt_fim_m, $dt_fim_a) = split("/", date("d/m/Y"));

$dt_ini = "$dt_ini_d/$dt_ini_m/$dt_ini_a";
$dt_fim = "$dt_fim_d/$dt_fim_m/$dt_fim_a";

switch($sn_acao)
{
	case "Por Usuario":
		list($titulo, $coisas)  = lst_por_usuario($dt_ini, $dt_fim, $pg);

		$coisas .= '
		<form name="f_dt" action="' . $PHP_SELF . '">
		<input type="hidden" name="item" value="' . $item . '">
		<input type="hidden" name="sn_inc" value="' . $sn_inc . '">
		<input type="hidden" name="sn_acao" value="' . $sn_acao . '">

		<br>
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<tr>
			<td class="textb" bgcolor="#e1e1e1">&nbsp;</td>
			<td class="textb" bgcolor="#e1e1e1">Dia</td>
			<td class="textb" bgcolor="#e1e1e1">Mês</td>
			<td class="textb" bgcolor="#e1e1e1">Ano</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1">Data Inicial:</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_dias("dt_ini_d",  $dt_ini_d) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_meses("dt_ini_m", $dt_ini_m) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_anos("dt_ini_a",  $dt_ini_a) . '</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1">Data Final:</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_dias("dt_fim_d",  $dt_fim_d) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_meses("dt_fim_m", $dt_fim_m) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_anos("dt_fim_a",  $dt_fim_a) . '</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1" align="center" colspan="4"><input type="submit" value=" Mostrar Relatório "></td>
		</tr>
	</table>
	</td></tr></table></form>';

		break;
	case "Por Produto":
		list($titulo, $coisas) = lst_por_produto($dt_ini, $dt_fim, $pg);

		$coisas .= '
		<form name="f_dt" action="' . $PHP_SELF . '">
		<input type="hidden" name="item" value="' . $item . '">
		<input type="hidden" name="sn_inc" value="' . $sn_inc . '">
		<input type="hidden" name="sn_acao" value="' . $sn_acao . '">

		<br>
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<tr>
			<td class="textb" bgcolor="#e1e1e1">&nbsp;</td>
			<td class="textb" bgcolor="#e1e1e1">Dia</td>
			<td class="textb" bgcolor="#e1e1e1">Mês</td>
			<td class="textb" bgcolor="#e1e1e1">Ano</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1">Data Inicial:</td>

			<td class="text" bgcolor="#e1e1e1">' . mostra_dias("dt_ini_d",  $dt_ini_d) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_meses("dt_ini_m", $dt_ini_m) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_anos("dt_ini_a",  $dt_ini_a) . '</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1">Data Final:</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_dias("dt_fim_d",  $dt_fim_d) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_meses("dt_fim_m", $dt_fim_m) . '</td>
			<td class="text" bgcolor="#e1e1e1">' . mostra_anos("dt_fim_a",  $dt_fim_a) . '</td>
		</tr>
		<tr>
			<td class="textb" bgcolor="#e1e1e1" align="center" colspan="4"><input type="submit" value=" Mostrar Relatório "></td>
		</tr>
	</table>
	</td></tr></table></form>';

		break;
	default:
		$tiulo  = "Escolha o Relatório";

		$coisas = '	
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%"><tr>';
		$coisas .= "<td class='text' bgcolor='#e1e1e1' align='center'>";
		$coisas .= "<a href='$PHP_SELF?item=$item&sn_inc=relatorio&sn_acao=Por+Usuario'>Por Usuário</a></td>";
		$coisas .= "<td class='text' bgcolor='#e1e1e1' align='center'>";
		$coisas .= "<a href='$PHP_SELF?item=$item&sn_inc=relatorio&sn_acao=Por+Produto'>Por Produto</a></td>";
		$coisas .= "</tr></table></td></tr></table>";
		break;

}

?>
