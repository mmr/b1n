<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:06:54 binary Exp $ */

function limpa_tipo_servico(&$dados)
{
    $dados["id"]       = "";
    $dados["tse_nome"] = "";
    $dados["tse_desc"] = "";
    $dados["tse_tipo"] = "";
}

function carrega_tipo_servico($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tse_id,
            tse_nome,
            tse_desc,
            tse_tipo
        FROM
            tipo_servico
        WHERE
            tse_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["tse_id"];
    $dados["tse_nome"] = $rs["tse_nome"];
    $dados["tse_desc"] = $rs["tse_desc"];
    $dados["tse_tipo"] = $rs["tse_tipo"];

    return true;
}

function insere_tipo_servico($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('tipo_servico_tse_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO tipo_servico
                (
                    tse_id,
                    tse_nome,
                    tse_desc,
                    tse_tipo
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["tse_nome"]) . "',
                    '" . in_bd($dados["tse_desc"]) . "',
                    '" . in_bd($dados["tse_tipo"]) . "'
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

function altera_tipo_servico($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                tipo_servico
            SET
                tse_nome = '" . in_bd($dados["tse_nome"]) . "',
                tse_desc = '" . in_bd($dados["tse_desc"]) . "',
                tse_tipo = '" . in_bd($dados["tse_tipo"]) . "'
            WHERE
                tse_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_tipo_servico($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                tipo_servico
            WHERE
                tse_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_tipo_servico($dados)
{
    $error_msgs = array();

    if ($dados["tse_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do Tipo de Serviço/Produto");

    if ($dados["tse_tipo"] == "")
        array_push($error_msgs, "É necessario escolher o tipo");

    return $error_msgs;
}

function busca_tipo_servico($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "tse_id";
    $config["possiveis_campos"]["Nome"] = "tse_nome";
    $config["possiveis_campos"]["Desc"] = "tse_desc";

    $config["possiveis_ordens"]["Nome"] = "tse_nome";
    $config["possiveis_ordens"]["Nome"] = "tse_tipo";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "tipo_servico";
    $config["campo_id"]                 = "tse_id";
    $config["csv_campos"]               = "tse_id, tse_nome, tse_tipo";
    $config["tabela"]                   = "tipo_servico";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
