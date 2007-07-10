<?
/*
 *
 * Esse arquivo (switch.inc.php), é parte integrante dos 'eventos' (sn_acao),
 * e não pode ser vendido separadamente
 *
 */

switch($sn_acao)
{
	case "Cadastrar":
		$titulo  = cadastra($sn_inc, $campos, $valores, $campos_obrigatorios);

		if(strstr($titulo, "Erro"))
			$cad_erro = "erro";

		break;
	case "Editar":
		list($titulo,$vals)  = edita($sn_inc, $nome_id, $sn_id, $campos);

		$aux  = sizeof($vals);

		for($i=0; $i<$aux; $i++)
			$$campos[$i] = $vals[$i];

		$sn_acao = "Alterar";
		$itens   = "\n<a href='$PHP_SELF?item=$item&sn_inc=$sn_inc'>Listar</a>";
		break;
	case "Alterar":
		$titulo = altera($sn_inc, $nome_id, $sn_id, $campos, $valores, $campos_obrigatorios);

		/* Verificando se deu erro, se sim, tem de editar de novo, por isso nao muda o sn_acao (continuando 'Alterar') */
		if(!strstr($titulo, "Erro"))
			$sn_acao = "Cadastrar";
		break;
	case "Excluir":
		$titulo = exclui($sn_inc, $nome_id, $sn_id);
		$sn_acao = "Cadastrar";
		break;
	default:
		$titulo  = "";
		$sn_acao = "Cadastrar";
		break;
}

/* Pega lista de itens */
if($sn_acao != "Alterar" )
	$itens = lista($sn_inc, $dados, $sn_pg, $nome_id, $prd);

/* Limpa vars globais */
if($sn_acao != "Alterar" && $sn_acao != "Editar" && !$cad_erro)
	limpa_vars($campos);
?>
