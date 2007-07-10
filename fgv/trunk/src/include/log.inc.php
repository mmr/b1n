<?
/* $Id: log.inc.php,v 1.5 2002/04/09 21:24:34 binary Exp $ */ 

/* funcoes relacionadas a loggin */

/**
 * Busca uma funcao pelo nome.
 * 
 * Retorna false se nao encontrar, caso contrario retorna o id.
 * 
 */
function search_fnc($sql, $fnc_nome) {
    $rs = $sql->squery("SELECT fnc_id" .
		       "  FROM funcao" .
		       " WHERE fnc_nome = '".in_bd($fnc_nome)."'");
    if (! is_array($rs)) return false;
    return $rs["fnc_id"];
}

/**
 * Insere... o log...
 */
function log_fnc($sql, $fnc_nome, $target_id = "NULL", $comentario = "NULL") {
    $fnc_id = search_fnc($sql, $fnc_nome);

    if (! $fnc_id || ! session_is_registered("membro"))
        return false;

    if( $target_id != "NULL" )
        $target_id = in_bd($target_id);

    if( $comentario != "NULL")
        $comentario = "'" . in_bd($comentario) . "'";

    $sqlquery = "INSERT INTO log (mem_id, fnc_id, fnc_target_id, fnc_comentario) VALUES ('" . in_bd($_SESSION["membro"]["id"]) . "', '" . in_bd($fnc_id) . "', " . $target_id . ", " . $comentario . ")";

    return $sql->query($sqlquery);
}

?>
