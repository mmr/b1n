<?
/* $Id: busca.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */ 
define("QT_POR_PAGINA_DEFAULT", 10);

function valida_busca(&$busca, $resp, $session_hash_name) {
    if ((!isset($busca["pagina_num"])) || (!consis_inteiro($busca["pagina_num"])) || ($busca["pagina_num"] <= 0))
	$busca["pagina_num"] = 1;
    else
	$pagina_num = $busca["pagina_num"];

    if ((!isset($busca["qt_por_pagina"])) || (!consis_inteiro($busca["qt_por_pagina"])) || ($busca["qt_por_pagina"] <= 0))
	$busca["qt_por_pagina"] = QT_POR_PAGINA_DEFAULT;


    if (in_array($busca["campo"], $resp["possiveis_campos"]) &&
	in_array($busca["ordem"], $resp["possiveis_ordens"]))                    // recebeu dados validos
	{
	    return true;
	}
    
    if (isset($_SESSION["busca"][$session_hash_name])) {            // pegar dados na sessao
	$busca = $_SESSION["busca"][$session_hash_name];
	if (isset($pagina_num)) $busca["pagina_num"] = $pagina_num;
	return true;
    }
    
    return false;
}



/* ATENCAO: O conteudo do hash $config deve ser confiavel */


function busca_G($sql, $config, &$busca) {

/* ----------------------- para montagem dos inputs -------------------------*/

    $resp["possiveis_campos"]      = $config["possiveis_campos"];
    $resp["possiveis_ordens"]      = $config["possiveis_ordens"];
    $resp["possiveis_quantidades"] = $config["possiveis_quantidades"];

/* ---------------------- validacao e armazenamento de dados ----------------*/

    if (!valida_busca($busca, $resp, $config["session_hash_name"]))
	return $resp;
    $_SESSION["busca"][$config["session_hash_name"]] = $busca;

/* ---------------------- contagem de resultados e paginacao ----------------*/
    
    $query_count = ("SELECT COUNT(".$config["campo_id"].")".
		    " FROM (SELECT DISTINCT ".$config["campo_id"].
		    "       FROM ".$config["tabela"].
		    "       WHERE ".$busca["campo"]." ~* '.*".in_bd($busca["texto"]).".*'".
		    "      ) AS FOO");

    $rs_count = $sql->squery($query_count);
    $resp["qt_paginas"] = max(1, ceil($rs_count["count"] / $busca["qt_por_pagina"]));

    if ($busca["pagina_num"] > $resp["qt_paginas"]) 
	$busca["pagina_num"] = $resp["qt_paginas"];

    $resp["pagina_num"] = $busca["pagina_num"];

/* ---------------------- busca no banco de dados ---------------------------*/
    
    $query = ("SELECT DISTINCT ".$config["csv_campos"].
	      " FROM ".$config["tabela"].
	      " WHERE ".$busca["campo"]." ~* '.*".in_bd($busca["texto"]).".*'".
	      " ORDER BY ".$busca["ordem"].
	      " LIMIT ".$busca["qt_por_pagina"]." OFFSET ".(($busca["pagina_num"] - 1) * $busca["qt_por_pagina"]));

    $resp["result"] = $sql->query($query);

/* --------------------- retorno ------------------------------------------- */

    return $resp;    
}
   
?>
