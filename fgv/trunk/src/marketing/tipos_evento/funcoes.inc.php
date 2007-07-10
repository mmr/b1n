<?
/* $Id: funcoes.inc.php,v 1.1 2002/03/21 19:46:09 binary Exp $ */

function limpa_tipo_evento(&$dados)
{
    $dados["id"]       = "";
    $dados["tev_nome"] = "";
    $dados["tev_desc"] = "";
}

function carrega_tipo_evento($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tev_id,
            tev_nome,
            tev_desc
        FROM
            tipo_evento
        WHERE
            tev_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["tev_id"];
    $dados["tev_nome"] = $rs["tev_nome"];
    $dados["tev_desc"] = $rs["tev_desc"];

    return true;
}

function insere_tipo_evento($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('tipo_evento_tev_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO tipo_evento
                (
                    tev_id,
                    tev_nome,
                    tev_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["tev_nome"]) . "',
                    '" . in_bd($dados["tev_desc"]) . "'
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

function altera_tipo_evento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                tipo_evento
            SET
                tev_nome = '" . in_bd($dados["tev_nome"]) . "',
                tev_desc = '" . in_bd($dados["tev_desc"]) . "'
            WHERE
                tev_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_tipo_evento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                tipo_evento
            WHERE
                tev_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_tipo_evento($dados)
{
    $error_msgs = array();

    if ($dados["tev_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do tipo de evento");

    return $error_msgs;
}

function busca_tipo_evento($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "tev_id";
    $config["possiveis_campos"]["Nome"] = "tev_nome";
    $config["possiveis_campos"]["Desc"] = "tev_desc";

    $config["possiveis_ordens"]["Nome"] = "tev_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "tipo_evento";
    $config["campo_id"]                 = "tev_id";
    $config["csv_campos"]               = "tev_id, tev_nome";
    $config["tabela"]                   = "tipo_evento";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
