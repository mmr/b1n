<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:34:34 binary Exp $ */

function limpa_regiao(&$dados)
{
    $dados["id"]       = "";
    $dados["reg_nome"] = "";
    $dados["reg_desc"] = "";
}

function carrega_regiao($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            reg_id,
            reg_nome,
            reg_desc
        FROM
            regiao
        WHERE
            reg_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["reg_id"];
    $dados["reg_nome"] = $rs["reg_nome"];
    $dados["reg_desc"] = $rs["reg_desc"];

    return true;
}

function insere_regiao($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('regiao_reg_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO regiao
                (
                    reg_id,
                    reg_nome,
                    reg_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["reg_nome"]) . "',
                    '" . in_bd($dados["reg_desc"]) . "'
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

function altera_regiao($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                regiao
            SET
                reg_nome = '" . in_bd($dados["reg_nome"]) . "',
                reg_desc = '" . in_bd($dados["reg_desc"]) . "'
            WHERE
                reg_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_regiao($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                regiao
            WHERE
                reg_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function valida_regiao($sql, $dados)
{
    $error_msgs = array();

    if ($dados["reg_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do regiao");

    $rs = $sql->squery("
        SELECT
            reg_id
        FROM
            regiao
        WHERE
            reg_nome = '" . in_bd($dados["reg_nome"]) . "'");

    if (is_array($rs) && $rs['reg_id'] != $dados["id"])
        array_push($error_msgs, "Já existe uma região com esse nome cadastrada");

    return $error_msgs;
}

function busca_regiao($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "reg_id";
    $config["possiveis_campos"]["Nome"] = "reg_nome";
    $config["possiveis_campos"]["Desc"] = "reg_desc";

    $config["possiveis_ordens"]["Nome"] = "reg_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "regiao";
    $config["campo_id"]                 = "reg_id";
    $config["csv_campos"]               = "reg_id, reg_nome";
    $config["tabela"]                   = "regiao";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
