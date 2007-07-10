<?

/* funcoes especificas pra CoNSumo */
function usr_busca($usr_busca,$pg)
{
	global $sql,$item;

	if(strlen(ereg_replace(" ","",$usr_busca))<3)
	{
		$retorno = "<font color='#ff0000' size='2' face='verdana, arial, helvetica, sans-serif'>Mínimo de 3 caracteres não brancos</font>";
		return $retorno;
	}

	$usr_busca = str_replace(" ","%",$usr_busca);

	$sqlquery = "SELECT usr_id, usr_nome FROM usuario WHERE usr_id ILIKE '%$usr_busca%' OR  usr_nome ILIKE '%$usr_busca%'";

	$ret = $sql->query($sqlquery);

	$retorno  = '<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td><table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">';

	if(is_array($ret))
	{
		$retorno .= "\n\t<tr>\n\t\t<td class=\"textb\" BGCOLOR=\"#E1E1E1\">Login</td>\n\t\t<td class=\"textb\" BGCOLOR=\"#E1E1E1\">Nome</td><td class=\"textb\" BGCOLOR=\"#E1E1E1\">&nbsp;</td></tr>";

		$aux = (sizeof($ret[0]))/4;

		foreach($ret as $d)
		{
			for($i=0; $i < $aux; $i++)
				$retorno .= "<tr><td class=\"text\" BGCOLOR=\"#E1E1E1\">$d[0]</td>
					    <td class=\"text\" BGCOLOR=\"#E1E1E1\">$d[1]</td>
					    <td class=\"text\" BGCOLOR=\"#E1E1E1\">
						<a href='$PHP_SELF?item=$item&sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=".urlencode($d[1])."&sn_id=$d[0]'>Novo Consumo</a>
						&nbsp;&nbsp;&nbsp;<a href='$PHP_SELF?item=$item&sn_inc=consumo&sn_acao=Baixa&sn_nome=".urlencode($d[1])."&sn_id=$d[0]'>Baixa</a></td>\n\t</tr>";
		}
	}
	else
		$retorno .= "\n\t<tr>\n\t\t<td class=\"text\" BGCOLOR=\"#E1E1E1\"><font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Nada foi encontrado...</font></td>\n\t</tr>";

	$retorno .= "\n</table></td></tr></table>";

	return $retorno;
}

function form_busca($usr_busca)
{
	global $PHP_SELF, $item;

	$formulario = "
		<tr>
			<td class=\"textb\" BGCOLOR=\"#E1E1E1\">* Nome do usuário</td><td class=\"text\" BGCOLOR=\"#E1E1E1\"><input type='text' name='usr_busca' value='$usr_busca' size='30' maxlength='100'></td>
		</tr>
		<tr>
			<td class=\"textb\" BGCOLOR=\"#E1E1E1\" colspan='2' align='center'><input type='submit' name='bt_ok' value=' Buscar '></td>
		</tr>";

	return $formulario;
}

function form_prd($prd_id, $qt)
{
	global $PHP_SELF,$item;

	$formulario = "
		<tr>
			<td class=\"textb\" BGCOLOR=\"#E1E1E1\" align='center'>* Produto</td>
			<td class=\"text\" BGCOLOR=\"#E1E1E1\" >" . select_produto($prd_id, $qt) . "</td>
		</tr>
		<tr>
			<td class=\"textb\" BGCOLOR=\"#E1E1E1\" align='center'>* Quantidade</td>
			<td class=\"text\" BGCOLOR=\"#E1E1E1\" ><input type='text' name='cns_qtde' value='" . ($cns_qtde ? $cns_qtde : "1") . "' size='3' maxlength='3'></td>
		</tr>
		<tr>
			<td class=\"textb\" BGCOLOR=\"#E1E1E1\" colspan='2' align='center'><input type='submit' name='bt_ok' value=' Cadastrar '></td>
		</tr>";

	return $formulario;
}

function lst_baixa($usr_id, $usr_nome)
{
	global $sql, $item;
	global $PHP_SELF;

	/* CONSUMO */
	
	$sqlquery = "SELECT prd_desc, prd_preco, cns_qtde, prd_preco * cns_qtde AS preco_qtde FROM consumo NATURAL JOIN produto WHERE cns_baixa = 'f' AND usr_id = '" . $usr_id . "' ORDER BY cns_ts DESC";

	$ret_cns = $sql->query($sqlquery);

	if(is_array($ret_cns))
	{
		$retorno = "yeah";

		$r_cns  = '
			<table><tr><td><img src="images/pixel_div.gif"  height="3" width="550"></td></tr></table>
			<p class="textb">Consumo de Produtos</p>
			<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
			            <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
				<tr>
			  		<td class="textb" bgcolor="#E1E1E1">Produto</td>
					<td class="textb" bgcolor="#E1E1E1">P. Unitário</td>
					<td class="textb" bgcolor="#E1E1E1">Quantidade</td>
					<td class="textb" bgcolor="#E1E1E1">Preço</td>
				</tr>';
		foreach($ret_cns as $d)
		{
			$r_cns .= "\n<tr>";
			for($i=0; $i<sizeof($d)/8; $i++)
			{
				$r_cns .= '<td class="text" bgcolor="#E1E1E1">' . $d[0] . '</td><td class="text" bgcolor="#E1E1E1" align="right">' . formata_dinheiro($d[1]) . '</td><td class="text" align="center" bgcolor="#E1E1E1">' . $d[2] . '</td><td class="text" bgcolor="#E1E1E1" align="right">' . formata_dinheiro($d[3]) . '</td>';
				$p_total += $d[3];
			}

		}
		$r_cns .= '</tr><tr><td class="text" bgcolor="#E1E1E1" colspan="4" align="center">Total Consumo: <b>' . formata_dinheiro($p_total) . '</b></td></tr>';
		$r_cns .= '</table></td></tr></table>';
	}


	/* JOGO */

	$sqlquery = "SELECT maq_id, TO_CHAR(evt_ts_ini, 'DD/MM - HH24:MI:SS') AS ini, TO_CHAR(evt_ts_fim, 'DD/MM - HH24:MI:SS') AS fim, (evt_uts_fim - evt_uts_ini) AS periodo , evt_tipo FROM evento_u WHERE evt_baixa = 'f' AND usr_id = '" . $usr_id . "' ORDER BY periodo DESC";

	$ret_evt = $sql->query($sqlquery);

	if(is_array($ret_evt))
	{
		$retorno = "yeah";
		
		$sqlquery = "SELECT cfg_int, cfg_money FROM config WHERE cfg_id = 'time_unit' OR cfg_id = 'price_per_unit' OR cfg_id = 'tolerance'";
		$cfg = $sql->query($sqlquery);

		$unidade     = $cfg[0][cfg_int];
		$por_unidade = $cfg[1][cfg_money];
		$tolerancia  = $cfg[2][cfg_int];

		$r_evt .= '<p class="textb">Tempo de Jogo</p><font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Unidade de Tempo: <b>' . formata_tempo($unidade) . '</b></font>';
		$r_evt .= '<br><font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Tolerância: <b>' . (int)($tolerancia / 60) . 'm</b></font>';

		$r_evt .= '<br><font size="2" color="#0000ff" face="verdana, helvetica, arial, sans-serif">Preço por Período: <b>' . formata_dinheiro($por_unidade) . '</b></font>';

		$r_evt .= '
			<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
			<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
			<tr>
				<td class="textb" bgcolor="#E1E1E1">Máquina</td>
				<td class="textb" bgcolor="#E1E1E1">Início</td>
				<td class="textb" bgcolor="#E1E1E1">Fim</td>
				<td class="textb" bgcolor="#E1E1E1">Tempo</td>
				<td class="textb" bgcolor="#E1E1E1">Preço</td>
				</tr>';

		foreach($ret_evt as $d)
		{
			$r_evt .= "\n<tr>";
			for($i=0; $i<sizeof($d)/12; $i++)
			{
				$periodo = $d[periodo];

				$u_cara   = $periodo / $unidade;
				$u_i_cara = (int)$u_cara;
				$u_f_cara = (int)(($u_cara - $u_i_cara) * $unidade);

				if($u_f_cara > $tolerancia)
					$u_i_cara++;
				elseif($u_i_cara == 0)
					$u_i_cara++;

				$preco = $u_i_cara * $por_unidade;

				$r_evt .= '
				<td class="text" bgcolor="#E1E1E1">' . $d[maq_id] . '</td>
				<td class="text" bgcolor="#E1E1E1">' . $d[ini] . '</td>
				<td class="text" bgcolor="#E1E1E1">' . $d[fim] . '</td>
				<td class="text" bgcolor="#E1E1E1" align="right">' . formata_tempo($d[periodo]) . '</td>
				<td class="text" bgcolor="#E1E1E1" align="right">' . formata_dinheiro($preco) . '</td>';

				$j_total += $preco;
			}
		}
		$r_evt .= '</tr>
			<tr>
				<td class="textb" bgcolor="#E1E1E1" colspan="6" align="center">Total Jogo: ' . formata_dinheiro($j_total) . '</td>
			</tr>';
		$r_evt .= '</table></td></tr></table>';
	}

	/* TOTAL */
	$r_total = $p_total + $j_total;

	if($usr_nome)
		$usuario = "(Usuário: " . $usr_nome . ")";
	elseif($usr_id)
		$usuario = "(Login: " . $usr_id . ")";
	
	$r_total  = '
	<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
	<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<tr>
			<td class="textb" bgcolor="#E1E1E1" align="center">Total: ' . formata_dinheiro($r_total) . '</td>
		</tr>
		<tr>
			<td class="text" bgcolor="#E1E1E1">
			<form action="' . $PHP_SELF . '" name="f_bax" method="post" OnSubmit="this.bt_ok.disabled = true;">
			<input type="hidden" name="item" value="' . $item . '">
			<input type="hidden" name="sn_inc" value="consumo">
			<input type="hidden" name="sn_acao" value="Pagar">
			<input type="hidden" name="sn_nome" value="' . $usr_nome . '">
			<input type="hidden" name="sn_id" value="' . $usr_id . '">
			<center><input type="submit" name="bt_ok" value=" Pago ' . $usuario . '"></center>
		</form></td>
		</tr>
	</table></td></tr></table>';

	if(!$retorno)
		$retorno = array(CNS_BAX_ERR);
	else
	{
		$formulario = "<form action='$PHP_SELF' method='post' name='f_l'><input type='submit' name='bt_ok' value=' Dar baixa em usúario \'$usr_id : $usr_nome\'";

		if($r_cns)
			$r_cns .= '<table><tr><td><img src="images/pixel_div.gif"  height="3" width="550"></td></tr></table>';
		if($r_evt)
			$r_evt .= '<table><tr><td><img src="images/pixel_div.gif"  height="3" width="550"></td></tr></table>';

		$retorno = $r_cns . $r_evt . $r_total;
		$retorno = array(CNS_BAX_SUC,$retorno);
	}

	return $retorno;
}

function lst_jogo($pg)
{
	global $sql,$item;
	global $PHP_SELF;


	$sqlquery = "SELECT cfg_int FROM config WHERE cfg_id = 'session_timeout'";
	list($timeout) = $sql->squery($sqlquery);
	$ts_atual = time();

	$sqlquery = "SELECT usr_id, usr_nome, maq_id, evt_tipo, CASE WHEN ($ts_atual - $timeout > evt_uts_fim) THEN '<font color=\'#505000\'>" . CNS_MSG_OFFLINE . "</font>' ELSE '<font color=\'#00aa00\'>" . CNS_MSG_ONLINE . "</font>' END AS status FROM evento_u NATURAL LEFT JOIN usuario WHERE evt_baixa = 'f' ORDER BY usr_nome, status"; 
	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$retorno  = '
		<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
		<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
		<tr>
			<td class="textb" bgcolor="#E1E1E1">Login</td>
			<td class="textb" bgcolor="#E1E1E1">Nome</td>
			<td class="textb" bgcolor="#E1E1E1">Máquina</td>
			<td class="textb" bgcolor="#E1E1E1">Status</td>
			<td class="textb" bgcolor="#E1E1E1" colspan="2">&nbsp;</td>
		</tr>';


		foreach($ret as $d)
		{
			$retorno .= "\n<tr>";
			for($i=0; $i<sizeof($d)/10; $i++)
			{
				if($d[evt_tipo] == "kil")
					$status = "<font color='#ff0000'><b>" . CNS_MSG_DOWN . "</b></font>";
				else
					$status = $d[status];
	
				$retorno .= '<td class="text" bgcolor="#E1E1E1">&nbsp;' . $d[usr_id] . "</td>";
				$retorno .= '<td class="text" bgcolor="#E1E1E1">&nbsp;' . $d[usr_nome] . "</td>";
				$retorno .= '<td class="text" bgcolor="#E1E1E1">&nbsp;' . $d[maq_id] . "</td>";
				$retorno .= '<td class="text" bgcolor="#E1E1E1">&nbsp;' . $status . "</td>";
			}

			if(!is_null($d[usr_nome]))
				$novocns = '<a href="' . $PHP_SELF . '?item=' . $item . '&sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=' . urlencode($d[1]) . '&sn_id=' . $d[0] . '">Novo Consumo</a>';
			else
				$novocns = "&nbsp;";

			$retorno .= '<td class="text" bgcolor="#E1E1E1">' . $novocns . '</td>';
	
			$retorno .= '<td class="text" bgcolor="#E1E1E1"><a href="' . $PHP_SELF . '?item=' . $item . '&sn_inc=consumo&sn_acao=Baixa&sn_nome=' . urlencode($d[1]) . '&sn_id=' . $d[0] . '">Baixa</a></td></tr>';
		}
		$retorno .= '</table></td></tr></table>';
	}

	if(!$retorno)
		$retorno = array(CNS_LST_JOG_ERR);
	else
		$retorno = array(CNS_LST_JOG_SUC,$retorno);

	return $retorno;
}

function lst_consumo($pg)
{
	global $sql, $item;
	global $PHP_SELF;

	$sqlquery = "SELECT usr_id, usr_nome, TO_CHAR(SUM(prd_preco * cns_qtde),'999999999D99') AS preco_total FROM consumo NATURAL JOIN usuario NATURAL JOIN produto WHERE cns_baixa = 'f' AND prd_id = prd_id AND usr_id = usr_id GROUP BY usr_id, usr_nome";

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$retorno = '<table border="0" CELLSPACING="0" CELLPADDING="0" BGCOLOR="#4A4A4A" WIDTH="550"><tr><td>
			<table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%">
			<tr>
			    <td class="textb" BGCOLOR="#E1E1E1">Login</td>
			    <td class="textb" BGCOLOR="#E1E1E1">Nome</td>
			    <td class="textb" BGCOLOR="#E1E1E1">Valor</td>
			    <td class="textb" BGCOLOR="#E1E1E1" colspan=2>&nbsp;</td>
			</tr>';

		foreach($ret as $d)
		{
			$retorno .= '<tr>';
			for($i=0; $i<sizeof($d)/8; $i++)
				$retorno .= "
				<td class='text' BGCOLOR='#E1E1E1'>&nbsp;$d[0]</td>
				<td class='text' BGCOLOR='#E1E1E1'>&nbsp;$d[1]</td>
				<td class='text' BGCOLOR='#E1E1E1'>&nbsp;" . formata_dinheiro($d[2]) . "</td>";
			$retorno .= "<td class='text' BGCOLOR='#E1E1E1'><a href='$PHP_SELF?item=$item&sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Novo Consumo</a></td>";

			$retorno .= "<td class='text' BGCOLOR='#E1E1E1'><a href='$PHP_SELF?item=$item&sn_inc=consumo&sn_acao=Baixa&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Baixa</a></td></tr>";
		}
		$retorno .= "</table></td></tr></table>";
	}

	if(!$retorno)
		$retorno = array(CNS_LST_ERR);
	else
		$retorno = array(CNS_LST_SUC,$retorno);

	return $retorno;
}

function cad_consumo($usr_id, $prd_id, $cns_qtde)
{
	global $sql;
	
	$sqlquery = "INSERT INTO consumo (usr_id, prd_id, cns_qtde) VALUES ('$usr_id', '$prd_id', '$cns_qtde')";

	$ret = $sql->query($sqlquery);

	$retorno  = "";

	if(!$retorno)
		$retorno = CNS_CAD_ERR;
	else
		$retorno = CNS_CAD_SUC;
}

function pagar($usr_id)
{
	global $sql,$sn_user;

	if(!$usr_id)
		return "ERRO: usr_id não passado...";

	/* PRODUTO */
	$sqlquery = "UPDATE consumo SET cns_baixa = 't' WHERE usr_id = '$usr_id'";

	$ret = $sql->query($sqlquery);

	if(!$ret)
		return CNS_PAG_CNS_ERR;

	/* JOGO */
	$sqlquery = "UPDATE evento SET evt_baixa = 't' WHERE usr_id = '$usr_id'";

	$ret = $sql->query($sqlquery);

	if(!$ret)
		return CNS_PAG_JOG_ERR." ".$sn_user;

	return CNS_PAG_SUC." ".$sn_user;
}

function select_produto($prd_id, $qt)
{
	global $sql;

	if($qt)
		$qt = "LIMIT $qt";

	$sqlquery = "SELECT prd_id, prd_desc FROM produto $qt WHERE prd_ativo = 't'";

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{

		$retorno  = "\n<select name='prd_id'>";
		$retorno .= "\n\t<option value=''>-- Escolha --</option>";

		$aux = (sizeof($ret[0]))/4;
		foreach($ret as $d)
		{
			for($i=0; $i < $aux; $i++)
				$retorno .= "\n\t<option value='" . $d[0] . "'>" . $d[1] . "</option>";
		}

		$retorno .= "\n</select>";
		$retorno  = str_replace("value='" . $prd_id . "'", "value='" . $prd_id . "' selected", $retorno);
	}
	else
		$retorno .= "\n: --> Não há produtos cadastrados...";

	return $retorno;
}

?>
