<?
/* $Id: funcoes.inc.php,v 1.3 2002/03/21 18:29:00 binary Exp $ */

function limpa_status_evento(&$dados)
{
    $dados["id"]       = "";
    $dados["ste_nome"] = "";
    $dados["ste_desc"] = "";
}

function carrega_status_evento($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            ste_id,
            ste_nome,
            ste_desc
        FROM
            status_evento
        WHERE
            ste_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["ste_id"];
    $dados["ste_nome"] = $rs["ste_nome"];
    $dados["ste_desc"] = $rs["ste_desc"];

    return true;
}

function insere_status_evento($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('status_evento_ste_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO status_evento
                    (ste_id,
                     ste_nome,
                     ste_desc)
                VALUES 
                    ('". in_bd($dados["id"])   . "',
                    '" . in_bd($dados["ste_nome"]) . "',
                    '" . in_bd($dados["ste_desc"]) . "')");
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

function altera_status_evento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                status_evento
            SET
                ste_nome = '" . in_bd($dados["ste_nome"]) . "',
                ste_desc = '" . in_bd($dados["ste_desc"]) . "'
            WHERE
                ste_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_status_evento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                status_evento
            WHERE
                ste_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_status_evento($dados)
{
    $error_msgs = array();

    if ($dados["ste_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do status da evento");

    return $error_msgs;
}

function busca_status_evento($sql, $busca) {

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "ste_id";
    $config["possiveis_campos"]["Nome"] = "ste_nome";
    $config["possiveis_campos"]["Desc"] = "ste_desc";

    $config["possiveis_ordens"]["Nome"] = "ste_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "status_evento";
    $config["campo_id"]                 = "ste_id";
    $config["csv_campos"]               = "ste_id, ste_nome";
    $config["tabela"]                   = "status_evento";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
