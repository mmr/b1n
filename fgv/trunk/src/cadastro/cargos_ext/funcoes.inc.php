<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:28:46 binary Exp $ */

function limpa_cargo(&$dados)
{
    $dados["id"]       = "";
    $dados["cex_nome"] = "";
    $dados["cex_desc"] = "";
}

function carrega_cargo($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cex_id,
            cex_nome,
            cex_desc
        FROM
            cargo_ext
        WHERE
            cex_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["cex_id"];
    $dados["cex_nome"] = $rs["cex_nome"];
    $dados["cex_desc"] = $rs["cex_desc"];

    return true;
}

function insere_cargo($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('cargo_cex_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO cargo_ext
                (
                    cex_id,
                    cex_nome,
                    cex_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["cex_nome"]) . "',
                    '" . in_bd($dados["cex_desc"]) . "'
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

function altera_cargo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                cargo_ext
            SET
                cex_nome = '" . in_bd($dados["cex_nome"]) . "',
                cex_desc = '" . in_bd($dados["cex_desc"]) . "'
            WHERE
                cex_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_cargo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                cargo_ext
            WHERE
                cex_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_cargo($dados)
{
    $error_msgs = array();

    if ($dados["cex_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do cargo_ext");

    return $error_msgs;
}

function busca_cargo($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cex_id";
    $config["possiveis_campos"]["Nome"] = "cex_nome";
    $config["possiveis_campos"]["Desc"] = "cex_desc";

    $config["possiveis_ordens"]["Nome"] = "cex_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "cargo_ext";
    $config["campo_id"]                 = "cex_id";
    $config["csv_campos"]               = "cex_id, cex_nome";
    $config["tabela"]                   = "cargo_ext";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
