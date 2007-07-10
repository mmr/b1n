<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:07:39 binary Exp $ */

function limpa_ramo(&$dados)
{
    $dados["id"]       = "";
    $dados["ram_nome"] = "";
    $dados["ram_desc"] = "";
}

function carrega_ramo($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            ram_id,
            ram_nome,
            ram_desc
        FROM
            ramo
        WHERE
            ram_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["ram_id"];
    $dados["ram_nome"] = $rs["ram_nome"];
    $dados["ram_desc"] = $rs["ram_desc"];

    return true;
}

function insere_ramo($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('ramo_ram_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO ramo
                (
                    ram_id,
                    ram_nome,
                    ram_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["ram_nome"]) . "',
                    '" . in_bd($dados["ram_desc"]) . "'
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

function altera_ramo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                ramo
            SET
                ram_nome = '" . in_bd($dados["ram_nome"]) . "',
                ram_desc = '" . in_bd($dados["ram_desc"]) . "'
            WHERE
                ram_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_ramo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                ramo
            WHERE
                ram_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_ramo($dados)
{
    $error_msgs = array();

    if ($dados["ram_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do ramo");

    return $error_msgs;
}

function busca_ramo($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "ram_id";
    $config["possiveis_campos"]["Nome"] = "ram_nome";
    $config["possiveis_campos"]["Desc"] = "ram_desc";

    $config["possiveis_ordens"]["Nome"] = "ram_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "ramo";
    $config["campo_id"]                 = "ram_id";
    $config["csv_campos"]               = "ram_id, ram_nome";
    $config["tabela"]                   = "ramo";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
