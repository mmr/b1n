<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:07:39 binary Exp $ */

function limpa_ts_subatividade(&$dados)
{
    $dados["id"]       = "";
    $dados["tsa_nome"] = "";
    $dados["tsa_desc"] = "";
}

function carrega_ts_subatividade($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tsa_id,
            tsa_nome,
            tsa_desc
        FROM
            ts_subatividade
        WHERE
            tsa_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["tsa_id"];
    $dados["tsa_nome"] = $rs["tsa_nome"];
    $dados["tsa_desc"] = $rs["tsa_desc"];

    return true;
}

function insere_ts_subatividade($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('ts_subatividade_tsa_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO ts_subatividade
                (
                    tsa_id,
                    tsa_nome,
                    tsa_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["tsa_nome"]) . "',
                    '" . in_bd($dados["tsa_desc"]) . "'
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

function altera_ts_subatividade($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                ts_subatividade
            SET
                tsa_nome = '" . in_bd($dados["tsa_nome"]) . "',
                tsa_desc = '" . in_bd($dados["tsa_desc"]) . "'
            WHERE
                tsa_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_ts_subatividade($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                ts_subatividade
            WHERE
                tsa_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_ts_subatividade($dados)
{
    $error_msgs = array();

    if ($dados["tsa_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome da SubAtividade (TimeSheet)");

    return $error_msgs;
}

function busca_ts_subatividade($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "tsa_id";
    $config["possiveis_campos"]["Nome"] = "tsa_nome";
    $config["possiveis_campos"]["Desc"] = "tsa_desc";

    $config["possiveis_ordens"]["Nome"] = "tsa_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "ts_subatividade";
    $config["campo_id"]                 = "tsa_id";
    $config["csv_campos"]               = "tsa_id, tsa_nome";
    $config["tabela"]                   = "ts_subatividade";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
