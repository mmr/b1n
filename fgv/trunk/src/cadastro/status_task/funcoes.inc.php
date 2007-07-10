<?
/* $Id: funcoes.inc.php,v 1.3 2002/03/21 18:29:00 binary Exp $ */

function limpa_status_task(&$dados)
{
    $dados["id"]       = "";
    $dados["stt_nome"] = "";
    $dados["stt_desc"] = "";
}

function carrega_status_task($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            stt_id,
            stt_nome,
            stt_desc
        FROM
            status_task
        WHERE
            stt_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["stt_id"];
    $dados["stt_nome"] = $rs["stt_nome"];
    $dados["stt_desc"] = $rs["stt_desc"];

    return true;
}

function insere_status_task($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('status_task_stt_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO status_task
                    (stt_id,
                     stt_nome,
                     stt_desc)
                VALUES 
                    ('". in_bd($dados["id"])   . "',
                    '" . in_bd($dados["stt_nome"]) . "',
                    '" . in_bd($dados["stt_desc"]) . "')");
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

function altera_status_task($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                status_task
            SET
                stt_nome = '" . in_bd($dados["stt_nome"]) . "',
                stt_desc = '" . in_bd($dados["stt_desc"]) . "'
            WHERE
                stt_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_status_task($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                status_task
            WHERE
                stt_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_status_task($dados)
{
    $error_msgs = array();

    if ($dados["stt_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do status da task");

    return $error_msgs;
}

function busca_status_task($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "stt_id";
    $config["possiveis_campos"]["Nome"] = "stt_nome";
    $config["possiveis_campos"]["Desc"] = "stt_desc";

    $config["possiveis_ordens"]["Nome"] = "stt_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "status_task";
    $config["campo_id"]                 = "stt_id";
    $config["csv_campos"]               = "stt_id, stt_nome";
    $config["tabela"]                   = "status_task";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
