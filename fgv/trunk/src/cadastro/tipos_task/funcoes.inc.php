<?
/* $Id: funcoes.inc.php,v 1.2 2002/03/21 18:29:01 binary Exp $ */

function limpa_tipo_task(&$dados)
{
    $dados["id"]       = "";
    $dados["ttk_nome"] = "";
    $dados["ttk_desc"] = "";
}

function carrega_tipo_task($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            ttk_id,
            ttk_nome,
            ttk_desc
        FROM
            tipo_task
        WHERE
            ttk_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["ttk_id"];
    $dados["ttk_nome"] = $rs["ttk_nome"];
    $dados["ttk_desc"] = $rs["ttk_desc"];

    return true;
}

function insere_tipo_task($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('tipo_task_ttk_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO tipo_task
                    (ttk_id,
                     ttk_nome,
                     ttk_desc)
                VALUES 
                    ('". in_bd($dados["id"])   . "',
                    '" . in_bd($dados["ttk_nome"]) . "',
                    '" . in_bd($dados["ttk_desc"]) . "')");
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

function altera_tipo_task($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                tipo_task
            SET
                ttk_nome = '" . in_bd($dados["ttk_nome"]) . "',
                ttk_desc = '" . in_bd($dados["ttk_desc"]) . "'
            WHERE
                ttk_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_tipo_task($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                tipo_task
            WHERE
                ttk_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_tipo_task($dados)
{
    $error_msgs = array();

    if ($dados["ttk_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do tipo da task");

    return $error_msgs;
}

function busca_tipo_task($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "ttk_id";
    $config["possiveis_campos"]["Nome"] = "ttk_nome";
    $config["possiveis_campos"]["Desc"] = "ttk_desc";

    $config["possiveis_ordens"]["Nome"] = "ttk_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "tipo_task";
    $config["campo_id"]                 = "ttk_id";
    $config["csv_campos"]               = "ttk_id, ttk_nome";
    $config["tabela"]                   = "tipo_task";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
