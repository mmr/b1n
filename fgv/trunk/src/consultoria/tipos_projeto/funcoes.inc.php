<?
/* $Id: funcoes.inc.php,v 1.2 2002/03/21 18:29:01 binary Exp $ */

function limpa_tipo_projeto(&$dados)
{
    $dados["id"]       = "";
    $dados["tpj_nome"] = "";
    $dados["tpj_desc"] = "";
}

function carrega_tipo_projeto($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tpj_id,
            tpj_nome,
            tpj_desc
        FROM
            tipo_projeto
        WHERE
            tpj_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["tpj_id"];
    $dados["tpj_nome"] = $rs["tpj_nome"];
    $dados["tpj_desc"] = $rs["tpj_desc"];

    return true;
}

function insere_tipo_projeto($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('tipo_projeto_tpj_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO tipo_projeto
                (
                    tpj_id,
                    tpj_nome,
                    tpj_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["tpj_nome"]) . "',
                    '" . in_bd($dados["tpj_desc"]) . "'
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

function altera_tipo_projeto($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                tipo_projeto
            SET
                tpj_nome = '" . in_bd($dados["tpj_nome"]) . "',
                tpj_desc = '" . in_bd($dados["tpj_desc"]) . "'
            WHERE
                tpj_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_tipo_projeto($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                tipo_projeto
            WHERE
                tpj_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_tipo_projeto($dados)
{
    $error_msgs = array();

    if ($dados["tpj_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do tipo de projeto");

    return $error_msgs;
}

function busca_tipo_projeto($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "tpj_id";
    $config["possiveis_campos"]["Nome"] = "tpj_nome";
    $config["possiveis_campos"]["Desc"] = "tpj_desc";

    $config["possiveis_ordens"]["Nome"] = "tpj_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "tipo_projeto";
    $config["campo_id"]                 = "tpj_id";
    $config["csv_campos"]               = "tpj_id, tpj_nome";
    $config["tabela"]                   = "tipo_projeto";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
