<?

/* Mensagens de Sucesso */
define(MSG_CADASTRO_SUC,"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Cadastro concluído com sucesso</font>");
define(MSG_EDITA_SUC,	"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Editando cadastro...</font>");
define(MSG_ALTERA_SUC,	"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Alterado com sucesso</font>");
define(MSG_EXCLUI_SUC,	"<font color='#0000ff' size='2' face='verdana, helvetica, sans-serif'>Exclusão concluída com sucesso</font>");

/* Mensagens de Erro */
define(MSG_CADASTRO_ERR,	"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro durante o cadastro</font>");
define(MSG_EDITA_ERR,		"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro durante a edição do cadastro, preencha todos os campos</font>");
define(MSG_ALTERA_ERR,		"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Erro durante a alteração do cadastro, verifique se já não existem registros com esse nome</font>");
define(MSG_EXCLUI_ERR,		"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Verifique se esse cadastro não possui dependências</font>");
define(MSG_CADASTRO_ERR_SENHA,	"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: A senha e a confirmação diferem</font>");
define(MSG_CADASTRO_ERR_CAMPO,	"<font color='#ff0000' size='2' face='verdana, helvetica, sans-serif'>Erro: Os campos com '*' ao lado, são de preenchimento obrigatório</font>");

function lista($tabela, $dados, $pg, $nome_id, $prd)
{
	global $sql;
	global $PHP_SELF;

	$pg = (int)$pg;

	$desc   = array_values($dados);
	$chaves = array_keys($dados);
	$query_campos = implode(",", $chaves);

	$sqlquery = "SELECT $query_campos FROM $tabela ORDER BY $nome_id DESC LIMIT " . PAG_INC . " OFFSET " . ($pg * PAG_INC);

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$retorno  = "\n<hr>\n<table class='text'>";
		$retorno .= "\n<form action='$PHP_SELF' method='post' name='f_l' OnSubmit='return Confirma();'>";
		$retorno .= "\n\t<tr>\n\t\t<td><b>Excluir</b></td>";

		for($i=0; $i<sizeof($desc); $i++)
			$retorno  .= "\n\t\t<td>" . $desc[$i] . "</td>";

		$aux = (sizeof($ret[0]))/2;
		foreach($ret as $d)
		{
			$retorno .= "\n\t</tr>\n\t<tr>";
			$retorno .= "\n\t\t<td><input type='checkbox' name='sn_id[]' value='" . $d[0] . "' OnClick='BotaoExcluir(\"document.f_l\",\"document.f_l.botao_excluir\");'></td>";
			if($prd)
				$d[2] = formata_dinheiro($d[2]);

			for($i=0; $i < $aux; $i++)
				$retorno .= "\n\t\t<td>" . $d[$i] . "</td>";
			$retorno .= "\n\t\t<td><a href='$PHP_SELF?sn_inc=" . $tabela . "&sn_acao=Editar&sn_id=" . urlencode($d[0]) .  "'>Editar</a></td>";
		}
		$retorno .= "\n\t</tr>\n\t<tr>\n\t\t<td align='center' colspan='" . ($aux + 1) . "'><input type='checkbox' name='todos' value='todos' OnClick='ChecarTodos(\"document.f_l\",\"document.f_l.botao_excluir\");'> Checar Todos</td>";
		$retorno .= "\n\t</tr>\n\t<tr>\n\t\t<td align='center' colspan='" . ($aux + 1) . "'><input type='submit' name='botao_excluir' value=' Excluir ' disabled></td>";
		$retorno .= "\n\t</tr>\n</table>";
		$retorno .= "\n<input type='hidden' name='sn_inc' value='$tabela'>";
		$retorno .= "\n<input type='hidden' name='sn_acao' value='Excluir'>";
		$retorno .= "\n</form>";
	}

	return $retorno;
}

function cadastra($tabela, $campos, $valores, $campos_obrigatorios)
{
	global $sql;

	if(!verifica_vazio($campos_obrigatorios))
		return MSG_CADASTRO_ERR_CAMPO;

	trata_str_in($valores[0]);

	$campos = implode(",",$campos);
	$vals   = "'" . $valores[0] . "'";

	for($i=1; $i<sizeof($valores); $i++)
	{
		trata_str_in($valores[$i]);
		$vals .= ",'" . $valores[$i] . "'";	
	}

	$sqlquery = "INSERT INTO " . $tabela . " (" . $campos . ") VALUES (" . $vals . ")";

	$retorno = $sql->query($sqlquery);
	
	if(!$retorno)
		$retorno = MSG_CADASTRO_ERR;
	else
		$retorno = MSG_CADASTRO_SUC;

	return $retorno;
}

function edita($tabela, $nome_id, $id, $campos)
{
	global $sql;

	trata_str_in($id);

	$campos = implode(",",$campos);

	$sqlquery = "SELECT " . $campos . " FROM " . $tabela . " WHERE $nome_id = '$id'";

	$retorno = $sql->squery($sqlquery);

	if(!$retorno)
		$retorno = array(MSG_EDITA_ERR,"");
	else
	{
		for($i=0; $i<sizeof($retorno)/2; $i++)
		{
			trata_str_out($retorno[$i]);
			$ret[$i] = $retorno[$i];
		}
		$retorno = array(MSG_EDITA_SUC, $ret);
	}

	return $retorno;
}

function altera($tabela, $nome_id, $id, $campos, $valores, $campos_obrigatorios)
{
	global $sql;

	if(!verifica_vazio($campos_obrigatorios))
		return MSG_CADASTRO_ERR_CAMPO;
	
	trata_str_in($id);
	trata_str_in($valores[0]);

	$sets = "set $campos[0] = '" . $valores[0] . "'";

	for($i=1; $i<sizeof($campos); $i++)
	{
		trata_str_in($valores[$i]);
		$sets .= ", $campos[$i] = '" . $valores[$i] . "'"; 
	}

	$sqlquery = "UPDATE " . $tabela . " " . $sets . " WHERE $nome_id = '$id'";

	$retorno = $sql->query($sqlquery);
	
	if(!$retorno)
		$retorno = MSG_ALTERA_ERR;
	else
		$retorno = MSG_ALTERA_SUC;

	return $retorno;
}

function exclui($tabela, $nome_id, $ids)
{
	global $sql;
		
	$query_where = "WHERE $nome_id = '$ids[0]'";
	
	$aux = sizeof($ids);
	for($i=1; $i<$aux; $i++)
		$query_where .= " OR $nome_id = '$ids[$i]'";

	$sqlquery = "DELETE FROM " . $tabela . " " . $query_where;

	$retorno = $sql->query($sqlquery);
	
	if(!$retorno)
		$retorno = MSG_EXCLUI_ERR;
	else
		$retorno = MSG_EXCLUI_SUC;

	return $retorno;
}


/* Verifica se os campos obrigatórios foram preenchidos */
function verifica_vazio($campos)
{
	foreach($campos as $campo)
	{
		global $$campo;
		if(!$$campo)
			return false;
	}
	return true;
}

/* Limpa variaveis globais */
function limpa_vars($campos)
{
	foreach($campos as $campo)
	{
		global $$campo;
		$$campo = "";
	}
}

/* Trata string pra entrada no banco */
function trata_str_in(&$str)
{
	$str = addslashes(limpa_espacos($str));
}

/* Trata string pra mostrar na tela */
function trata_str_out(&$str)
{
	$str = str_replace("'","&#39;", htmlspecialchars(limpa_espacos($str)));
}

/* limpa espacos em branco repetidos */
function limpa_espacos($str)
{
	$str = trim(ereg_replace(" {2,}"," ",$str));
	return $str;
}

/* formata dinheiro */
function formata_dinheiro($din)
{
	$str = "R\$ " . strtr(sprintf("%.2f",$din), ",.", ".,");
	return $str;
}

/* formata data (converte segundos pra h:m) */
function formata_data($s)
{
	$m = (int) ($s/60);

	if($m>59)
		$h = (int) ($m/60) . "h ";

	$m -= $h * 60;	

	$m .= "m";

	$dts = "$h$m";

	return $dts;
}
?>
