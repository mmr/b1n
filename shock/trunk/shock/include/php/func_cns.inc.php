<?

define(CNS_CAD_SUC,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Cadastrando produto para <b>$sn_nome</b></font>");
define(CNS_CAD_ERR,"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro ao cadastrar consumo para <b>$sn_nome</b></font>");
define(CNS_LST_SUC,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Listando consumos...</font>");
define(CNS_LST_ERR,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Não há consumos pendentes</font>");
define(CNS_LST_JOG_ERR,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Não há jogos pendentes</font>");
define(CNS_BAX_SUC,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Baixa em <b>$sn_nome</b></font>");
define(CNS_BAX_ERR,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Nada à ser pago por <b>$sn_nome</b></font>");
define(CNS_PAG_CNS_ERR,"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro ao pagar consumo para <b>$sn_nome</b></font>");
define(CNS_PAG_JOG_ERR,"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro ao pagar tempo jogo para <b>$sn_nome</b></font>");
define(CNS_PAG_SUC,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Pago com sucesso para <b>$sn_nome</b></font>");

/* funcoes especificas pra CoNSumo */
function usr_busca($usr_busca,$pg)
{
	global $sql;

	if(strlen(ereg_replace(" ","",$usr_busca))<3)
	{
		$retorno = "Mínimo de 3 caracteres não brancos";
		return $retorno;
	}

	$usr_busca = str_replace(" ","%",$usr_busca);

	$sqlquery = "SELECT usr_id, usr_nome FROM usuario WHERE usr_id LIKE '%$usr_busca%' OR  usr_nome LIKE '%$usr_busca%'";

	$ret = $sql->query($sqlquery);

	$retorno  = "\n<table class='text'>";

	if(is_array($ret))
	{
		$retorno .= "\n\t<tr>\n\t\t<td>Login</td>\n\t\t<td>Nome</td>\n\t</tr>";

		$aux = (sizeof($ret[0]))/4;

		foreach($ret as $d)
		{
			for($i=0; $i < $aux; $i++)
				$retorno .= "\n\t<tr>\n\t\t<td>$d[0]</td>\n\t\t<td>$d[1]</td>\n\t\t<td><a href='" . $PHP_SELF . "?sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Novo Consumo</a>&nbsp;&nbsp;&nbsp;<a href='" . $PHP_SELF . "?sn_inc=consumo&sn_acao=Baixa&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Baixa</a></td>\n\t</tr>";
		}
	}
	else
		$retorno = "\n\t<tr>\n\t\t<td>Nada foi encontrado...</td>\n\t</tr>";

	$retorno .= "\n</table>";

	return $retorno;
}

function form_busca($usr_busca)
{
	global $PHP_SELF;

	$formulario = "
	<form action='$PHP_SELF' method='post' name='f_b' OnSubmit='Verifica(this,\"usr_busca\");'>
		<tr>
			<td>* Nome do usuário</td><td><input type='text' name='usr_busca' value='$usr_busca' size='30' maxlength='100'></td>
		</tr>
		<tr>
			<td colspan='2' align='center'><input type='submit' name='bt_ok' value=' Buscar '></td>
		</tr>";

	return $formulario;
}

function form_prd($prd_id, $qt)
{
	global $PHP_SELF;

	$formulario = "
	<form action='$PHP_SELF' method='post' name='f_l' OnSubmit='Verifica(this,\"prd_id\");'>
		<tr>
			<td align='center'>* Produto</td>
			<td>" . select_produto($prd_id, $qt) . "</td>
		</tr>
		<tr>
			<td align='center'>* Quantidade</td>
			<td><input type='text' name='prd_qtde' value='" . ($prd_qtde ? $prd_qtde : "1") . "' size='3' maxlength='3'></td>
		</tr>
		<tr>
			<td colspan='2' align='center'><input type='submit' name='bt_ok' value=' Cadastrar '></td>
		</tr>";

	return $formulario;
}

function lst_baixa($usr_id, $usr_nome)
{
	global $sql;
	global $PHP_SELF;

	/* CONSUMO */
	
	//$sqlquery = "SELECT p.prd_desc, p.prd_preco, c.prd_qtde, p.prd_preco * c.prd_qtde AS preco_qtde FROM consumo c, produto p WHERE c.cns_baixa = 'f' AND c.usr_id = '" . $usr_id . "' AND c.prd_id = p.prd_id ORDER BY c.cns_ts DESC";
	$sqlquery = "SELECT prd_desc, prd_preco, prd_qtde, prd_preco * prd_qtde AS preco_qtde FROM consumo NATURAL JOIN produto WHERE cns_baixa = 'f' AND usr_id = '" . $usr_id . "' ORDER BY cns_ts DESC";

	$ret_cns = $sql->query($sqlquery);

	if(is_array($ret_cns))
	{
		$retorno = "yeah";

		$r_cns  = "\n\t<hr><h4>Consumo de Produtos</h4>\n\t<table width='100%' border='1'class='text2'>\n\t\t<tr>\n\t\t\t<td align='center'>Produto</td>\n\t\t\t<td align='center'>P. Unitário</td>\n\t\t\t<td align='center'>Quantidade</td>\n\t\t\t<td align='center'>Preço</td>\n\t\t</tr>";
		foreach($ret_cns as $d)
		{
			$r_cns .= "\n<tr>";
			for($i=0; $i<sizeof($d)/8; $i++)
			{
				$r_cns .= "<td align='center'>$d[0]</td><td align='right'>" . formata_dinheiro($d[1]) . "</td><td align='center'>" . $d[2] . "</td><td align='right'>" . formata_dinheiro($d[3]) . "</td>";
				$p_total += $d[3];
			}

		}
		$r_cns .= "</tr><tr><td bgcolor='#77a7d7' align='center' colspan='4'>Total Consumo: <b>" . formata_dinheiro($p_total) . "</b></td></tr>";
		$r_cns .= "</table>";
	}


	/* JOGO */

	//$sqlquery = "SELECT maq_id, TO_CHAR(evt_ts_ini, 'HH24:MI:SS') as ini, TO_CHAR(evt_ts_fim, 'HH24:MI:SS') as fim, (evt_ts_fim - evt_ts_ini) as periodo, (evt_uts_fim - evt_uts_ini) AS u_periodo , evt_tipo FROM evento_u WHERE evt_baixa = 'f' AND usr_id = '" . $usr_id . "' ORDER BY periodo DESC";
	$sqlquery = "SELECT maq_id, TO_CHAR(evt_ts_ini, 'HH24:MI:SS') as ini, TO_CHAR(evt_ts_fim, 'HH24:MI:SS') as fim, (evt_uts_fim - evt_uts_ini) AS periodo , evt_tipo FROM evento_u WHERE evt_baixa = 'f' AND usr_id = '" . $usr_id . "' ORDER BY periodo DESC";

	$ret_evt = $sql->query($sqlquery);

	if(is_array($ret_evt))
	{
		$retorno = "yeah";
		
		$sqlquery = "SELECT cfg_int, cfg_money FROM config WHERE cfg_id = 'time_unit' OR cfg_id = 'price_per_unit' OR cfg_id = 'tolerance'";
		$cfg = $sql->query($sqlquery);

		$unidade     = $cfg[0][cfg_int];
		$por_unidade = $cfg[1][cfg_money];
		$tolerancia  = ($unidade * $cfg[2][cfg_int]) / 100;

		$r_evt .= "<h4>Tempo de Jogo</h4><font size='2' color='#0000ff' face='verdana, helvetica, arial, sans-serif'>Período Mínimo: <b>" . formata_data($unidade) . "</b></font>";
		$r_evt .= "<br><font size='2' color='#0000ff' face='verdana, helvetica, arial, sans-serif'>Preço por Período: <b>" . formata_dinheiro($por_unidade) . "</b></font>\n<br>";

		$r_evt .= "\n\t<table border='1' width='100%' class='text2'>\n\t\t<tr>\n\t\t\t<td align='center'>Máquina</td>\n\t\t\t<td align='center'>Início</td>\n\t\t\t<td align='center'>Fim</td>\n\t\t\t<td align='center'>Tempo</td>\n\t\t\t<td align='center'>Preço</td>\n\t\t</tr>";

		foreach($ret_evt as $d)
		{
			$r_evt .= "\n<tr>";
			for($i=0; $i<sizeof($d)/12; $i++)
			{
				$periodo = $d[periodo];

				$u_cara   = $periodo / $unidade;
				$u_i_cara = (int)$u_cara;
				$u_f_cara = $u_cara - $u_i_cara;

				if($u_f_cara > $tolerancia)
					$u_i_cara++;

				$preco = $u_i_cara * $por_unidade;

				$r_evt .= "\n\t\t\t<td>" . $d[maq_id] . "</td>\n\t\t\t<td align='right'>" . $d[ini] . "</td>\n\t\t\t<td align='right'>" . $d[fim] . "</td>\n\t\t\t<td>" . formata_data($d[periodo]) . "</td>\n\t\t\t<td align='right'>" . formata_dinheiro($preco) . "</td>";

				$j_total += $preco;
			}
		}
		$r_evt .= "\n\t\t</tr>\n\t\t<tr>\n\t\t\t<td bgcolor='#77a7d7' colspan='6' align='center'>Total Jogo: <b>" . formata_dinheiro($j_total) . "</b></td>\n\t\t</tr>";
		$r_evt .= "</table>";
	}

	/* TOTAL */
	$r_total = $p_total + $j_total;

	$r_total  = "\n\t<table border='1' width='100%'>\n\t\t<tr>\n\t\t\t<td bgcolor='#d7a777' color='#ffffff' align='center'>Total: <b>" . formata_dinheiro($r_total) . "</b></td>\n\t\t</tr></table>";

	$r_total .= "\n\t<br>\n\t<table border='0' width='100%'>\n\t\t<tr>\n\t\t\t<td align='center'><form action='$PHP_SELF' name='f_bax' method='post' OnSubmit='this.bt_ok.disabled = true;'><input type='hidden' name='sn_inc' value='consumo'><input type='hidden' name='sn_acao' value='Pagar'><input type='hidden' name='sn_nome' value='" . $usr_nome . "'><input type='hidden' name='sn_id' value='" . $usr_id . "'><input type='submit' name='bt_ok' value=' Pago (Usuário: $usr_nome) '></form></td>\n\t\t</tr>\n\t</table>";

	if(!$retorno)
		$retorno = array(CNS_BAX_ERR);
	else
	{
		$formulario = "<form action='$PHP_SELF' method='post' name='f_l'><input type='submit' name='bt_ok' value=' Dar baixa em usúario \'$sn_nome\'";

		if($r_cns)
			$r_cns .= "\n\t<hr>";
		if($r_evt)
			$r_evt .= "\n\t<hr>";

		$retorno = "\n<table border='0' width='480'>\n\t<tr>\n\t\t<td>" . $r_cns . $r_evt . $r_total . "</td>\n\t</tr>\n</table>";
		$retorno = array(CNS_BAX_SUC,$retorno);
	}

	return $retorno;
}

function lst_jogo($pg)
{
	global $sql;
	global $PHP_SELF;

	$sqlquery = "SELECT usr_id, usr_nome, maq_id, evt_tipo FROM evento NATURAL JOIN usuario WHERE evt_baixa = 'f'"; 

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$retorno  = "<table class='text2'><tr><td>Usuário</td><td>Máquina</td><td>Status</td></tr>";
		foreach($ret as $d)
		{
			$retorno .= "\n<tr>";
			for($i=0; $i<sizeof($d)/8; $i++)
			{
				if($d[evt_tipo] != "onl")
					$status="<font color='#ff0000'><b>DOWN</b></font>";
				else
					$status="Online";

				$retorno .= "<td align='center'>" . $d[usr_id] . "</td>";
				$retorno .= "<td align='center'>" . $d[maq_desc] . "</td>";
				$retorno .= "<td align='center'>" . $status . "</td>";
			}
			$retorno .= "<td><a href='$PHP_SELF?sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Novo Consumo</a></td>";

			$retorno .= "<td><a href='$PHP_SELF?sn_inc=consumo&sn_acao=Baixa&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Baixa</a></td></tr>";
		}
		$retorno .= "</table>";
	}

	if(!$retorno)
		$retorno = array(CNS_LST_JOG_ERR);
	else
		$retorno = array(CNS_LST_JOG_SUC,$retorno);

	return $retorno;
}

function lst_consumo($pg)
{
	global $sql;
	global $PHP_SELF;

	$sqlquery = "SELECT usr_id, usr_nome, TO_CHAR(SUM(prd_preco * prd_qtde),'999G999G999D99') AS preco_total FROM consumo NATURAL JOIN usuario NATURAL JOIN produto WHERE cns_baixa = 'f' GROUP BY usr_id, usr_nome";
	
	//$sqlquery = "SELECT u.usr_id, u.usr_nome, TO_CHAR(SUM(p.prd_preco * c.prd_qtde),'999G999G999D99') AS preco_total FROM consumo c, produto p, usuario u WHERE c.cns_baixa = 'f' AND c.usr_id = u.usr_id AND c.prd_id = p.prd_id GROUP BY u.usr_id, u.usr_nome";

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$retorno  = "<table class='text2'><tr><td>Usuário</td><td>Valor</td></tr>";
		foreach($ret as $d)
		{
			$retorno .= "\n<tr>";
			for($i=0; $i<sizeof($d)/8; $i++)
				$retorno .= "<td align='center'>$d[1]</td><td align='center'>" . formata_dinheiro($d[2]) . "</td>";
			$retorno .= "<td><a href='$PHP_SELF?sn_inc=consumo&sn_acao=Listar+Produtos&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Novo Consumo</a></td>";

			$retorno .= "<td><a href='$PHP_SELF?sn_inc=consumo&sn_acao=Baixa&sn_nome=" . urlencode($d[1]) . "&sn_id=" . $d[0] . "'>Baixa</a></td></tr>";
		}
		$retorno .= "</table>";
	}

	if(!$retorno)
		$retorno = array(CNS_LST_ERR);
	else
		$retorno = array(CNS_LST_SUC,$retorno);

	return $retorno;
}

function cad_consumo($usr_id, $prd_id, $prd_qtde)
{
	global $sql;
	
	$sqlquery = "INSERT INTO consumo (usr_id, prd_id, prd_qtde) VALUES ('$usr_id', '$prd_id', '$prd_qtde')";

	$ret = $sql->query($sqlquery);

	$retorno  = "";

	if(!$retorno)
		$retorno = CNS_CAD_ERR;
	else
		$retorno = CNS_CAD_SUC;
}

function pagar($usr_id)
{
	global $sql;

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
		return CNS_PAG_JOG_ERR;

	return CNS_PAG_SUC;
}

function select_produto($prd_id, $qt)
{
	global $sql;

	if($qt)
		$qt = "LIMIT $qt";

	$sqlquery = "SELECT prd_id, prd_desc FROM produto $qt";

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
