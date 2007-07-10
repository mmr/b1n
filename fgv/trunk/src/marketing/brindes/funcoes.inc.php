<?
/* $Id: funcoes.inc.php,v 1.2 2002/03/21 18:29:01 binary Exp $ */

function limpa_brinde(&$dados)
{
    $dados["id"]       = "";
    $dados["bri_nome"] = "";
    $dados["bri_desc"] = "";
}

function carrega_brinde($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            bri_id,
            bri_nome,
            bri_desc
        FROM
            brinde
        WHERE
            bri_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["bri_id"];
    $dados["bri_nome"] = $rs["bri_nome"];
    $dados["bri_desc"] = $rs["bri_desc"];

    return true;
}

function insere_brinde($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('brinde_bri_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO brinde
                (
                    bri_id,
                    bri_nome,
                    bri_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["bri_nome"]) . "',
                    '" . in_bd($dados["bri_desc"]) . "'
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

function altera_brinde($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                brinde
            SET
                bri_nome = '" . in_bd($dados["bri_nome"]) . "',
                bri_desc = '" . in_bd($dados["bri_desc"]) . "'
            WHERE
                bri_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_brinde($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                brinde
            WHERE
                bri_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_brinde($dados)
{
    $error_msgs = array();

    if ($dados["bri_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do brinde");

    return $error_msgs;
}

function busca_brinde($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "bri_id";
    $config["possiveis_campos"]["Nome"] = "bri_nome";
    $config["possiveis_campos"]["Desc"] = "bri_desc";

    $config["possiveis_ordens"]["Nome"] = "bri_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "brinde";
    $config["campo_id"]                 = "bri_id";
    $config["csv_campos"]               = "bri_id, bri_nome";
    $config["tabela"]                   = "brinde";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
