<?
/* $Id: funcoes.inc.php,v 1.2 2002/03/21 18:29:01 binary Exp $ */

function limpa_categoria(&$dados)
{
    $dados["id"]       = "";
    $dados["cat_nome"] = "";
    $dados["cat_desc"] = "";
}

function carrega_categoria($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cat_id,
            cat_nome,
            cat_desc
        FROM
            categoria
        WHERE
            cat_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["cat_id"];
    $dados["cat_nome"] = $rs["cat_nome"];
    $dados["cat_desc"] = $rs["cat_desc"];

    return true;
}

function insere_categoria($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('categoria_cat_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO categoria
                (
                    cat_id,
                    cat_nome,
                    cat_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["cat_nome"]) . "',
                    '" . in_bd($dados["cat_desc"]) . "'
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

function altera_categoria($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                categoria
            SET
                cat_nome = '" . in_bd($dados["cat_nome"]) . "',
                cat_desc = '" . in_bd($dados["cat_desc"]) . "'
            WHERE
                cat_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_categoria($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                categoria
            WHERE
                cat_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_categoria($dados)
{
    $error_msgs = array();

    if ($dados["cat_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome da categoria");

    return $error_msgs;
}

function busca_categoria($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cat_id";
    $config["possiveis_campos"]["Nome"] = "cat_nome";
    $config["possiveis_campos"]["Desc"] = "cat_desc";

    $config["possiveis_ordens"]["Nome"] = "cat_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "categoria";
    $config["campo_id"]                 = "cat_id";
    $config["csv_campos"]               = "cat_id, cat_nome";
    $config["tabela"]                   = "categoria";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
