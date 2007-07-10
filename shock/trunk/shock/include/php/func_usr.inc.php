<?

/* Funções específicas pro cadastro/edição de usuário */
function cadastra_usr($valores, $campos_obrigatorios)
{
	global $sql;

	list($usr_id, $usr_senha, $usr_senha2, $usr_nome) = $valores;

	if($usr_senha != $usr_senha2)
		return MSG_CADASTRO_ERR_SENHA;

	if(!verifica_vazio($campos_obrigatorios))
		return MSG_CADASTRO_ERR;

	$sqlquery = "INSERT INTO usuario (usr_id, usr_nome, usr_senha) VALUES ('$usr_id','$usr_nome','$usr_senha')";

	$retorno = $sql->query($sqlquery);
	
	if(!$retorno)
		$retorno = MSG_CADASTRO_ERR;
	else
		$retorno = MSG_CADASTRO_SUC;

	return $retorno;
}

function altera_usr($id, $valores, $campos_obrigatorios)
{
	global $sql; 

	list($usr_id, $usr_senha, $usr_senha2, $usr_nome) = $valores;

	if($usr_senha != $usr_senha2)
		return MSG_CADASTRO_ERR_SENHA;

	if(!verifica_vazio($campos_obrigatorios))
		return MSG_CADASTRO_ERR;

	trata_str_in($usr_id);
	trata_str_in($usr_nome);

	$sets = "SET usr_id = '$usr_id', usr_nome = '$usr_nome'";

	/* Olhar se a senha mudou */
	if($usr_senha != "******")
		$sets .= ", usr_senha = '$usr_senha'";
	
	$sqlquery = "UPDATE usuario $sets WHERE usr_id = '$id'";

	print $sqlquery . "<br>";

	$retorno = $sql->query($sqlquery);
	
	if(!$retorno)
		$retorno = MSG_ALTERA_ERR;
	else
		$retorno = MSG_ALTERA_SUC;

	return $retorno;
}
?>
