<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:07:39 binary Exp $ */

function limpa_area(&$dados)
{
    $dados["id"]       = "";
    $dados["are_nome"] = "";
    $dados["are_desc"] = "";
}

function carrega_area($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            are_id,
            are_nome,
            are_desc
        FROM
            area
        WHERE
            are_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["are_id"];
    $dados["are_nome"] = $rs["are_nome"];
    $dados["are_desc"] = $rs["are_desc"];

    return true;
}

function insere_area($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('area_are_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO area
                (
                    are_id,
                    are_nome,
                    are_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["are_nome"]) . "',
                    '" . in_bd($dados["are_desc"]) . "'
                )");
            if ($rs)
            {
                $sql->query("COMMIT TRANSACTION");
                return true;
            }
        }
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function altera_area($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                area
            SET
                are_nome = '" . in_bd($dados["are_nome"]) . "',
                are_desc = '" . in_bd($dados["are_desc"]) . "'
            WHERE
                are_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_area($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                area
            WHERE
                are_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_area($dados)
{
    $error_msgs = array();

    if ($dados["are_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do área");

    return $error_msgs;
}

function busca_area($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "are_id";
    $config["possiveis_campos"]["Nome"] = "are_nome";
    $config["possiveis_campos"]["Desc"] = "are_desc";

    $config["possiveis_ordens"]["Nome"] = "are_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "area";
    $config["campo_id"]                 = "are_id";
    $config["csv_campos"]               = "are_id, are_nome";
    $config["tabela"]                   = "area";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
