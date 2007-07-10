<?
/* $Id: busca.inc.php,v 1.7 2002/07/31 20:20:51 binary Exp $ */ 
define("QT_POR_PAGINA_DEFAULT", 20);

function valida_busca(&$busca, $resp, $session_hash_name)
{
    if( ( ! isset( $busca["pagina_num"] ) ) || ( ! consis_inteiro( $busca["pagina_num"] ) ) || ( $busca["pagina_num"] <= 0 ) )
    {
	$busca["pagina_num"] = 1;
    }
    else
    {
	$pagina_num = $busca["pagina_num"];
    }

    if( ( ! isset( $busca["qt_por_pagina"] ) ) || ( ! consis_inteiro( $busca["qt_por_pagina"] ) ) || ( $busca["qt_por_pagina"] <= 0 ) )
    {
        if( isset( $_SESSION[ "busca" ][ $session_hash_name ][ "qt_por_pagina" ] ) )
        {
	    $busca["qt_por_pagina"] = $_SESSION[ "busca" ][ $session_hash_name ][ "qt_por_pagina" ];
        }
        else
        {
	    $busca["qt_por_pagina"] = QT_POR_PAGINA_DEFAULT;
        }
    }

    if( in_array( $busca["campo"], $resp["possiveis_campos"] ) &&
        in_array( $busca["ordem"], $resp["possiveis_ordens"] ) )                    // recebeu dados validos
    {
        return true;
    }
    else
    {
        if( isset( $_SESSION["busca"][$session_hash_name]["campo"] ) )
        {
            $busca[ "campo" ] = $_SESSION[ "busca" ][ $session_hash_name ][ "campo" ];
        }
        else
        {
            $busca[ "campo" ] = array_shift( $resp[ "possiveis_campos" ] );
        }

        if( isset( $_SESSION["busca"][$session_hash_name]["ordem"] ) )
        {
            $busca[ "ordem" ] = $_SESSION[ "busca" ][ $session_hash_name ][ "ordem" ];
        }
        else
        {
            $busca[ "ordem" ] = array_shift( $resp[ "possiveis_ordens" ] );
        }

        if( isset( $_SESSION["busca"][$session_hash_name]["texto"] ) )
        {
            $busca[ "texto" ] = $_SESSION[ "busca" ][ $session_hash_name ][ "texto" ];
        }

        if( isset( $pagina_num ) )
        {
            $busca["pagina_num"] = $pagina_num;
        }
        /*
        if( isset( $_SESSION["busca"][$session_hash_name]["campo"] ) )
        {
            $busca = $_SESSION["busca"][$session_hash_name];
            if( isset( $pagina_num ) )
            {
                $busca["pagina_num"] = $pagina_num;
            }
            return true;
        }
        */
    }

    return true;
}



/* ATENCAO: O conteudo do hash $config deve ser confiavel */


function busca_G($sql, $config, $busca) {

/* ----------------------- para montagem dos inputs -------------------------*/

    $resp["possiveis_campos"]      = $config["possiveis_campos"];
    $resp["possiveis_ordens"]      = $config["possiveis_ordens"];
    $resp["possiveis_quantidades"] = $config["possiveis_quantidades"];


/* ---------------------- validacao e armazenamento de dados ----------------*/

    if( ! valida_busca( $busca, $resp, $config["session_hash_name"] ) )
    {
        $resp["busca"] = $busca;
	return $resp;
    }

    $resp["busca"] = $busca;
    $_SESSION["busca"][$config["session_hash_name"]] = $busca;

/* ---------------------- contagem de resultados e paginacao ----------------*/
    
    $query_count = ("SELECT COUNT(".$config["campo_id"].")".
		    " FROM (SELECT DISTINCT ".$config["campo_id"].
		    "       FROM ".$config["tabela"].
		    "       WHERE ".$busca["campo"]." ILIKE '%".in_bd($busca["texto"])."%'".
		    "      ) AS FOO");

    $rs_count = $sql->squery($query_count);
    $resp["qt_paginas"] = max(1, ceil($rs_count["count"] / $busca["qt_por_pagina"]));

    if ($busca["pagina_num"] > $resp["qt_paginas"]) 
	$busca["pagina_num"] = $resp["qt_paginas"];

    $resp["pagina_num"] = $busca["pagina_num"];

/* ---------------------- busca no banco de dados ---------------------------*/
    
    $query = ("SELECT DISTINCT ".$config["csv_campos"].
	      " FROM ".$config["tabela"].
	      " WHERE ".$busca["campo"]." ILIKE '%".in_bd($busca["texto"])."%'".
	      " ORDER BY ".$busca["ordem"].
	      " LIMIT ".$busca["qt_por_pagina"]." OFFSET ".(($busca["pagina_num"] - 1) * $busca["qt_por_pagina"]));

    $resp["result"] = $sql->query($query);

/* --------------------- retorno ------------------------------------------- */

    return $resp;    
}
   
?>
