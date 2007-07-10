<?

function lista_cfg($tabela)
{
	global $sql;

	$sqlquery = "SELECT cfg_id, cfg_int, cfg_money FROM $tabela WHERE cfg_id = 'price_per_unit' OR cfg_id = 'time_unit' OR cfg_id = 'tolerance'";

	$ret = $sql->query($sqlquery);

	if(is_array($ret))
	{
		$price_per_unit = $ret[0][cfg_money];
		$time_unit      = $ret[1][cfg_int]; 
		$tolerance      = $ret[2][cfg_int];

		$time_unit /= (int) 60;
		$tolerance /= (int) 60;

		$retorno = array(MSG_CFG_LST_SUC, $price_per_unit, $time_unit, $tolerance);
	}
	else
		$retorno = MSG_CFG_ERR;

	return $retorno;
}

function altera_cfg($tabela, $campos_obrigatorios, $price_per_unit, $time_unit, $tolerance)
{
	global $sql;

	if(!verifica_vazio($campos_obrigatorios))
		return MSG_CADASTRO_ERR_CAMPO;

	/* PRICE_PER_UNIT */
	$price_per_unit = reconhece_dinheiro($price_per_unit);
	$sqlquery = "UPDATE $tabela SET cfg_money = '$price_per_unit' WHERE cfg_id = 'price_per_unit'";
	$ret = $sql->query($sqlquery);

	if(!$ret)
		return MSG_CFG_PPU_ERR;

	/* TIME_UNIT */
	$time_unit *= (int) 60;
	$sqlquery = "UPDATE config SET cfg_int = '$time_unit' WHERE cfg_id = 'time_unit'";
	$ret = $sql->query($sqlquery);

	if(!$ret)
		return MSG_CFG_TUN_ERR;

	/* TOLERANCE */
	$tolerance *= (int) 60;
	$sqlquery = "UPDATE config SET cfg_int = '$tolerance' WHERE cfg_id = 'tolerance'";
	$ret = $sql->query($sqlquery);

	if(!$ret)
		return MSG_CFG_TOL_ERR;

	return MSG_CFG_SUC;
}
