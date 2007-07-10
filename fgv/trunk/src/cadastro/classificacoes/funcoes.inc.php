<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:34:33 binary Exp $ */

function limpa_classificacao(&$dados)
{
    $dados["id"]       = "";
    $dados["cla_nome"] = "";
    $dados["cla_desc"] = "";
}

function carrega_classificacao($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cla_id,
            cla_nome,
            cla_desc
        FROM
            pat_class
        WHERE
            cla_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["cla_id"];
    $dados["cla_nome"] = $rs["cla_nome"];
    $dados["cla_desc"] = $rs["cla_desc"];

    return true;
}

function insere_classificacao($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('pat_class_cla_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO pat_class
                (
                    cla_id,
                    cla_nome,
                    cla_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["cla_nome"]) . "',
                    '" . in_bd($dados["cla_desc"]) . "'
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

function altera_classificacao($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                pat_class
            SET
                cla_nome = '" . in_bd($dados["cla_nome"]) . "',
                cla_desc = '" . in_bd($dados["cla_desc"]) . "'
            WHERE
                cla_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_classificacao($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                pat_class
            WHERE
                cla_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_classificacao($dados)
{
    $error_msgs = array();

    if ($dados["cla_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do classificação");

    return $error_msgs;
}

function busca_classificacao($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cla_id";
    $config["possiveis_campos"]["Nome"] = "cla_nome";
    $config["possiveis_campos"]["Desc"] = "cla_desc";

    $config["possiveis_ordens"]["Nome"] = "cla_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "pat_class";
    $config["campo_id"]                 = "cla_id";
    $config["csv_campos"]               = "cla_id, cla_nome";
    $config["tabela"]                   = "pat_class";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
