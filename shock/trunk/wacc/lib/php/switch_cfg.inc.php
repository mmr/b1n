<?
/*
 *
 * Esse arquivo (switch_cfg.inc.php), � parte integrante dos 'eventos' (sn_acao),
 * e n�o pode ser vendido separadamente
 *
 */

switch($sn_acao)
{
	case "Alterar":
		$titulo  = altera_cfg($sn_inc, $campos_obrigatorios, $price_per_unit, $time_unit, $tolerance);
		break;
	case "Listar Conf":
		list($titulo, $price_per_unit, $time_unit, $tolerance) = lista_cfg($sn_inc);
		$sn_acao = "Alterar";
		break;
	default:
		$titulo  = "";
		$sn_acao = "Alterar";
		break;
}
?>
