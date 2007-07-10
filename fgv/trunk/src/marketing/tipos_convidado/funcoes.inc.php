<?
/* $Id: funcoes.inc.php,v 1.2 2002/03/21 18:29:02 binary Exp $ */

function limpa_tipo_convidado(&$dados)
{
    $dados["id"]       = "";
    $dados["tcv_nome"] = "";
    $dados["tcv_desc"] = "";
}

function carrega_tipo_convidado($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tcv_id,
            tcv_nome,
            tcv_desc
        FROM
            tipo_convidado
        WHERE
            tcv_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["tcv_id"];
    $dados["tcv_nome"] = $rs["tcv_nome"];
    $dados["tcv_desc"] = $rs["tcv_desc"];

    return true;
}

function insere_tipo_convidado($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('tipo_convidado_tcv_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO tipo_convidado
                (
                    tcv_id,
                    tcv_nome,
                    tcv_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["tcv_nome"]) . "',
                    '" . in_bd($dados["tcv_desc"]) . "'
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

function altera_tipo_convidado($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                tipo_convidado
            SET
                tcv_nome = '" . in_bd($dados["tcv_nome"]) . "',
                tcv_desc = '" . in_bd($dados["tcv_desc"]) . "'
            WHERE
                tcv_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_tipo_convidado($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                tipo_convidado
            WHERE
                tcv_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_tipo_convidado($dados)
{
    $error_msgs = array();

    if ($dados["tcv_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do tipo de convidado");

    return $error_msgs;
}

function busca_tipo_convidado($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "tcv_id";
    $config["possiveis_campos"]["Nome"] = "tcv_nome";
    $config["possiveis_campos"]["Desc"] = "tcv_desc";

    $config["possiveis_ordens"]["Nome"] = "tcv_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "tipo_convidado";
    $config["campo_id"]                 = "tcv_id";
    $config["csv_campos"]               = "tcv_id, tcv_nome";
    $config["tabela"]                   = "tipo_convidado";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
