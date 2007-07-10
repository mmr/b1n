<?
/*
 *
 * Esse arquivo (switch_cns.inc.php), é parte integrante dos 'eventos' (sn_acao),
 * e não pode ser vendido separadamente
 *
 */

switch($sn_acao)
{
	case "Buscar":
		$formulario = form_busca($usr_busca);

		if($usr_busca)
		{
			trata_str_in($usr_busca);
			$titulo  = usr_busca($usr_busca, $pg);
		}

		$sn_acao = "Buscar";
		break;
	case "Listar Jogos":
		list($titulo, $coisas) = lst_jogo($pg);
		break;
	case "Listar Consumos":
		list($titulo, $coisas) = lst_consumo($pg);
		break;
	case "Listar Produtos":
		$formulario = form_prd($prd_id, $qt);
		$titulo     = "Cadastrar consumo de produto para <b>$sn_nome</b>";
		$sn_acao = "Cadastrar Consumo";
		break;
	case "Cadastrar Consumo":
		$formulario = form_busca($usr_busca);
		$titulo     = cad_consumo($sn_id, $prd_id, $cns_qtde);
		$sn_acao = "Buscar";
		break;
	case "Baixa":
		list($titulo, $coisas) = lst_baixa($sn_id, $sn_nome);
		$titulo.=$sn_nome;
		break;
	case "Pagar":
		$titulo = pagar($sn_id).$sn_nome;
		$formulario = form_busca($usr_busca);
		$sn_acao = "Buscar";
		break;
	default:
		list($titulo, $coisas) = lst_jogo($pg);
		break;

}

?>
