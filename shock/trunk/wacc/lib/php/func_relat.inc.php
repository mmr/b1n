<?

function lst_por_usuario($dt_ini="", $dt_fim="", $pg=0)
{
	global $sql;
	global $PHP_SELF;

	if(!$dt_ini)
		$dt_ini = date("d/m/Y");

	if(!$dt_fim)
		$dt_fim = date("d/m/Y");

	list($dt_ini_d, $dt_ini_m, $dt_ini_a) = split("/", $dt_ini);
	list($dt_fim_d, $dt_fim_m, $dt_fim_a) = split("/", $dt_fim);

	$dt_ini_ts = mktime(0, 0, 0, $dt_ini_m, $dt_ini_d, $dt_ini_a);
	$dt_fim_ts = mktime(0, 0, 0, $dt_fim_m, $dt_fim_d, $dt_fim_a);

	if($dt_ini_ts > $dt_fim_ts)
	{
		$titulo = "A data inicial ($dt_ini) não pode ser maior que a final ($dt_fim)";
		return array($titulo);
	}

	$sqlquery = "SELECT cfg_int, cfg_money FROM config WHERE cfg_id = 'time_unit' OR cfg_id = 'price_per_unit' OR cfg_id = 'tolerance'";
	$cfg = $sql->query($sqlquery);

	$unidade     = $cfg[0][cfg_int];
	$por_unidade = $cfg[1][cfg_money];
	$tolerancia  = $cfg[2][cfg_int];


	$sqlquery = "
	SELECT
		usr_nome,
		usr_id,
		COUNT(prd_id),
		SUM(cns_qtde) AS produtos,
		SUM(prd_preco * cns_qtde) AS total_produtos
	FROM
		produto NATURAL LEFT JOIN consumo
			NATURAL LEFT JOIN usuario
	WHERE
		cns_baixa = 't'
		AND cns_ts > '$dt_ini 00:00:00'
		AND cns_ts < '$dt_fim 23:59:59'
	GROUP BY
		usr_id,
		usr_nome";

	$res_prd = $sql->query($sqlquery);

	$sqlquery = "
	SELECT
		usr_nome,
		usr_id,
		SUM(evt_uts_fim - evt_uts_ini) AS tempos,
		CASE WHEN
		SUM((evt_uts_fim - evt_uts_ini) / $unidade) - 
		TRUNC(SUM(((evt_uts_fim - evt_uts_ini) / $unidade))) <= $tolerancia.0 / $unidade.0 THEN
			TRUNC(SUM((evt_uts_fim - evt_uts_ini) / $unidade))
		ELSE
			TRUNC(SUM((evt_uts_fim - evt_uts_ini) / $unidade)) + 1
		END * $por_unidade AS total_jogos
	FROM
		evento_u NATURAL LEFT JOIN usuario
	WHERE
		evt_baixa = 't'
		AND evt_ts_fim > '$dt_ini 00:00:00'
		AND evt_ts_fim < '$dt_fim 23:59:59'
	GROUP BY
		usr_id,
		usr_nome";

	$res_evt = $sql->query($sqlquery);

	$retorno  = "<table><tr>";
	$retorno .= "<td class='textb'>Data Inicial:</td><td class='text'>$dt_ini</td></tr>";
	$retorno .= "<td class='textb'>Data Final:</td><td class='text'>$dt_fim</td><tr></table>";

	if(is_array($res_prd) || is_array($res_evt))
	{
		$retorno .= '
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">';
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Nome</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Login</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Produtos</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Tempo</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Total Produtos</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Total Jogos</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Total Usuário</td>";
		$retorno .= "</tr>";
		$i=0;
		while($res_prd[$i] != "" || $res_evt[$i] != "")
		{
				if($res_evt[$i]['usr_id'])
				{
					$id = $res_evt[$i]['usr_id'];
					$nome = $res_evt[$i]['usr_nome'];
				}
				else
				{
					$id = $res_prd[$i]['usr_id'];
					$nome = $res_prd[$i]['usr_nome'];
				}

				$retorno .= "<tr>";

/*
				$retorno .= "<td class='text' bgcolor='#e1e1e1'><a href='$PHP_ELF?item=$item&sc_inc=relatorio&sn_acao=Relatorio+Usuario&usr_id=$id'>" . $nome . "</a></td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'><a href='$PHP_ELF?item=$item&sc_inc=relatorio&sn_acao=Relatorio+Usuario&usr_id=$id'>" . $id   . "</a></td>";
*/

				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . $nome . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . $id   . "</td>";

				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . (int)($res_prd[$i]['produtos']) . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_tempo($res_evt[$i]['tempos']) . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($res_prd[$i]['total_produtos']) . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($res_evt[$i]['total_jogos']) . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($res_prd[$i]['total_produtos'] + $res_evt[$i]['total_jogos']) . "</td>";
				$retorno .= "</tr>";

				$total_prd += $res_prd[$i]['total_produtos'];
				$total_evt += $res_evt[$i]['total_jogos'];
				$i++;
		}
		$retorno .= "<tr><td class='textb' bgcolor='#e1e1e1' colspan='4'>&nbsp;</td><td class='textb' bgcolor='#e1e1e1'>" . formata_dinheiro($total_prd) . "</td><td class='textb' bgcolor='#e1e1e1'>". formata_dinheiro($total_evt) . "</td><td class='textb' bgcolor='#e1e1e1'>&nbsp;</td></tr>";
		$retorno .= "<tr><td class='textb' bgcolor='#e1e1e1' align='center' colspan='7'>Total: " . formata_dinheiro($total_prd + $total_evt) . "</td></tr>";
		$retorno .= "</table></td></tr></table>";
		$retorno_formatado = strip_tags($retorno, "<table><tr><td><b><font><br><p>");
		$retorno_formatado = str_replace("\n", "", $retorno_formatado);
		$retorno_formatado = str_replace("\"", "\\\"", $retorno_formatado);

		$retorno .= '
			<script language="JavaScript">
			<!--
			function janelaImprimir()
			{
				janela = window.open("", "janela", "status=yes, menubar=yes, scrollbars=yes, width=640, height=480"); 
				janela.document.write("<html><head><title>Relatório / Data: ' . $dt_ini . ' - ' . $dt_fim . '</title><link rel=\"stylesheet\" type=\"text/css\" href=\"/style/koewy.css\"></head><body leftmargin=\"0\" topmargin=\"0\" onload=\"this.focus();\">");
				janela.document.write("<center>");
				janela.document.write("' . $retorno_formatado . '");
				janela.document.write("</center></body></html>");
			}
			//-->
			</script>';

		$msg = "Listando por Usuário... <a href='javascript:janelaImprimir()'>Imprimir</a>";
	}
	else
		$msg = "Listando por Usuário..."; 

	return array($msg, $retorno);
}

function lst_por_produto($dt_ini="", $dt_fim="", $pg=0)
{
	global $sql;
	global $PHP_SELF;

	if(!$dt_ini)
		$dt_ini = date("d/m/Y");

	if(!$dt_fim)
		$dt_fim = date("d/m/Y");

	list($dt_ini_d, $dt_ini_m, $dt_ini_a) = split("/", $dt_ini);
	list($dt_fim_d, $dt_fim_m, $dt_fim_a) = split("/", $dt_fim);

	$dt_ini_ts = mktime(0, 0, 0, $dt_ini_m, $dt_ini_d, $dt_ini_a);
	$dt_fim_ts = mktime(0, 0, 0, $dt_fim_m, $dt_fim_d, $dt_fim_a);

	if($dt_ini_ts > $dt_fim_ts)
	{
		$titulo = "A data inicial ($dt_ini - $dt_ini_ts) não pode ser maior que a final ($dt_fim - $dt_fim_ts)";
		return array($titulo);
	}

	$sqlquery = "SELECT cfg_int, cfg_money FROM config WHERE cfg_id = 'time_unit' OR cfg_id = 'price_per_unit' OR cfg_id = 'tolerance'";
	$cfg = $sql->query($sqlquery);

	$unidade     = $cfg[0][cfg_int];
	$por_unidade = $cfg[1][cfg_money];
	$tolerancia  = $cfg[2][cfg_int];

	$sqlquery = "
	SELECT
		maq_id, SUM(ROUND(evt_uts_fim - evt_uts_ini)) AS tempo_total
	FROM 
		evento_u
	WHERE
		evt_baixa = 't'
		AND evt_ts_fim > '$dt_ini 00:00:00'
		AND evt_ts_fim < '$dt_fim 23:59:59'
	GROUP BY
		maq_id";

	$res_evt = $sql->query($sqlquery);

	$sqlquery = "
	SELECT
		prd_desc,
		SUM(cns_qtde) AS qtde,
		prd_preco,
		SUM(cns_qtde * prd_preco) AS soma_total
	FROM
		produto NATURAL JOIN consumo
	WHERE
		cns_baixa = 't'
		AND cns_ts > '$dt_ini 00:00:00'
		AND cns_ts < '$dt_fim 23:59:59'
	GROUP BY
		prd_id,
		prd_desc,
		prd_preco";

	$res_prd = $sql->query($sqlquery);

	$retorno  = "<table><tr>";
	$retorno .= "<td class='textb'>Data Inicial:</td><td class='text'>$dt_ini</td></tr>";
	$retorno .= "<td class='textb'>Data Final:</td><td class='text'>$dt_fim</td><tr></table>";

	if(is_array($res_prd))
	{
		$retorno .= '
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">';
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Produto</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Quantidade</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Preço Unitário</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Preço Total</td>";
		$retorno .= "</tr>";
		$i=0;
		$p_total=0;
		while($res_prd[$i] != "")
		{
				$retorno .= "<tr>";

				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . $res_prd[$i]['prd_desc'] . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . $res_prd[$i]['qtde'] . "</td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($res_prd[$i]['prd_preco']) . "</a></td>";
				$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($res_prd[$i]['soma_total']) . "</a></td>";

				$retorno .= "</tr>";
				$p_total += $res_prd[$i]['soma_total'];
				$i++;
		}

		$retorno .= "<tr><td class='textb' bgcolor='#e1e1e1'>Total de Produto:</td><td class='textb' bgcolor='#e1e1e1' colspan='2'>&nbsp;</td><td class='textb' bgcolor='#e1e1e1'>" . formata_dinheiro($p_total) . "</a></td>";
		$retorno .= '</table></td></tr></table>';
	}

	if(is_array($res_evt))
	{
		if($retorno)
			$retorno .= "<br>";

		$retorno .= '<font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Unidade de Tempo: <b>' . formata_tempo($unidade) . '</b></font>';
		$retorno .= '<br><font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Tolerância: <b>' . (int)($tolerancia / 60) . 'm</b></font>';

		$retorno .= '<br><font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Preço por Período: <b>' . formata_dinheiro($por_unidade) . '</b></font>';

		$retorno .= '
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">';

		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Máquina</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Tempo de Jogo</td>";
		$retorno .= "<td class='textb' bgcolor='#e1e1e1'>Preço Total</td>";
		$retorno .= "</tr>";

		$i=0;
		$t_total=0;
		while($res_evt[$i] != "")
		{
			$tempo_total = (int)(($res_evt[$i]['tempo_total'] / $unidade) * $por_unidade);

			$retorno .= "<tr>";
			$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . $res_evt[$i]['maq_id'] . "</td>";
			$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_tempo($res_evt[$i]['tempo_total']) . "</td>";
			$retorno .= "<td class='text' bgcolor='#e1e1e1'>" . formata_dinheiro($tempo_total) . "</td>";
			$retorno .= "</tr>";

			$t_total += $res_evt[$i]['tempo_total'];
			$i++;
		}

		$tempo_total = $t_total;
		$t_total = (int)(($t_total / $unidade) * $por_unidade);

		$retorno .= "<tr><td class='textb' bgcolor='#e1e1e1'>Total de Jogos:</td><td class='textb' bgcolor='#e1e1e1'>". formata_tempo($tempo_total) . "</td><td class='textb' bgcolor='#e1e1e1'>" . formata_dinheiro($t_total) . "</a></td>";
		$retorno .= "</table></td></tr></table>";
	}

	if(is_array($res_prd) || is_array($res_evt))
	{
		$retorno .= '
		<br>
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550">
		<tr><td>
			<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
				<tr><td class="textb" bgcolor="#e1e1e1" align="center">Total Geral: ' . formata_dinheiro($p_total + $t_total) . '
				</td></tr>
			</table>
		</td></tr>
	</table>';

		$retorno_formatado = strip_tags($retorno, "<table><tr><td><b><font><br>");
		$retorno_formatado = str_replace("\n", "", $retorno_formatado);
		$retorno_formatado = str_replace("\"", "\\\"", $retorno_formatado);

		$retorno .= '
		<script language="JavaScript">
		<!--
		function janelaImprimir()
		{
			janela = window.open("", "janela", "status=yes, menubar=yes, toolbar=yes, scrollbars=yes, width=640, height=480"); 
			janela.document.write("<html><head><title>Relatório / Data: ' . $dt_ini . ' - ' . $dt_fim . '</title><link rel=\"stylesheet\" type=\"text/css\" href=\"/style/koewy.css\"></head><body leftmargin=\"0\" topmargin=\"0\" onload=\"this.focus();\">");
			janela.document.write("<center>");
			janela.document.write("' . $retorno_formatado . '");
			janela.document.write("</center></body></html>");
		}
		//-->
		</script>';

		$msg = "Listando por Produto... <a href='javascript:janelaImprimir()'>Imprimir</a>";
	}
	else
		$msg = "Listando por Produto...";

	return array($msg, $retorno);
}

function mostra_anos($campo, $valor)
{
	$ano_ini = "2002";
	$ano_fim = "2020";

	$retorno = "<select name='$campo'>";

	for($i=$ano_ini; $i <= $ano_fim; $i++)
		$retorno .= "<option value='$i'>$i</option>";

	$retorno .= "</select>";

	$retorno = str_replace("value='$valor'", "valur='$valor' selected", $retorno);
	return $retorno;
}

function mostra_dias($campo, $valor)
{
	$retorno = "<select name='$campo'>";

	for($i=1; $i <= 31; $i++)
	{
		$d = sprintf("%02d", $i);
		$retorno .= "<option value='$d'>$d</option>";
	}

	$retorno .= "</select>";

	$retorno = str_replace("value='$valor'", "valur='$valor' selected", $retorno);
	return $retorno;
}

function mostra_meses($campo, $valor)
{
	$meses = array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro");
	$retorno = "<select name='$campo'>";

	for($i=0; $i < sizeof($meses); $i++)
	{
		$m = sprintf("%02d", ($i+1));
		$retorno .= "<option value='" . $m . "'>" . $meses[$i] . "</option>";
	}

	$retorno = str_replace("value='$valor'", "value='$valor' selected", $retorno);
	return $retorno;
}

?>
