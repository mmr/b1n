<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:28:46 binary Exp $ */

function limpa_cargo(&$dados)
{
    $dados["id"]       = "";
    $dados["cgv_nome"] = "";
    $dados["cgv_desc"] = "";
}

function carrega_cargo($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cgv_id,
            cgv_nome,
            cgv_desc
        FROM
            cargo_gv
        WHERE
            cgv_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["cgv_id"];
    $dados["cgv_nome"] = $rs["cgv_nome"];
    $dados["cgv_desc"] = $rs["cgv_desc"];

    return true;
}

function insere_cargo($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('cargo_gv_cgv_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO cargo_gv
                (
                    cgv_id,
                    cgv_nome,
                    cgv_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["cgv_nome"]) . "',
                    '" . in_bd($dados["cgv_desc"]) . "'
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
                cargo_gv
            SET
                cgv_nome = '" . in_bd($dados["cgv_nome"]) . "',
                cgv_desc = '" . in_bd($dados["cgv_desc"]) . "'
            WHERE
                cgv_id = '"   . in_bd($dados["id"])   ."'");
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
                cargo_gv
            WHERE
                cgv_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_cargo($dados)
{
    $error_msgs = array();

    if ($dados["cgv_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do cargo_gv");

    return $error_msgs;
}

function busca_cargo($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cgv_id";
    $config["possiveis_campos"]["Nome"] = "cgv_nome";
    $config["possiveis_campos"]["Desc"] = "cgv_desc";

    $config["possiveis_ordens"]["Nome"] = "cgv_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "cargo_gv";
    $config["campo_id"]                 = "cgv_id";
    $config["csv_campos"]               = "cgv_id, cgv_nome";
    $config["tabela"]                   = "cargo_gv";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
