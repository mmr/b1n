<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:34:34 binary Exp $ */

function limpa_setor(&$dados)
{
    $dados["id"]       = "";
    $dados["set_nome"] = "";
    $dados["set_desc"] = "";
}

function carrega_setor($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            set_id,
            set_nome,
            set_desc
        FROM
            setor
        WHERE
            set_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["set_id"];
    $dados["set_nome"] = $rs["set_nome"];
    $dados["set_desc"] = $rs["set_desc"];

    return true;
}

function insere_setor($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('setor_set_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO setor
                (
                    set_id,
                    set_nome,
                    set_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["set_nome"]) . "',
                    '" . in_bd($dados["set_desc"]) . "'
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

function altera_setor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                setor
            SET
                set_nome = '" . in_bd($dados["set_nome"]) . "',
                set_desc = '" . in_bd($dados["set_desc"]) . "'
            WHERE
                set_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_setor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                setor
            WHERE
                set_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_setor($dados)
{
    $error_msgs = array();

    if ($dados["set_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do setor");

    return $error_msgs;
}

function busca_setor($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "set_id";
    $config["possiveis_campos"]["Nome"] = "set_nome";
    $config["possiveis_campos"]["Desc"] = "set_desc";

    $config["possiveis_ordens"]["Nome"] = "set_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "setor";
    $config["campo_id"]                 = "set_id";
    $config["csv_campos"]               = "set_id, set_nome";
    $config["tabela"]                   = "setor";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
