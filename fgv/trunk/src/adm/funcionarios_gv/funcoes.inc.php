<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:41:47 binary Exp $ */

function limpa_funcionario_gv(&$dados)
{
    $dados["id"]       = "";
    $dados["fgv_nome"] = "";
    $dados["fgv_funcao"] = "";
}

function carrega_funcionario_gv($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            fgv_id,
            fgv_nome,
            fgv_funcao
        FROM
            funcionario_gv
        WHERE
            fgv_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["fgv_id"];
    $dados["fgv_nome"] = $rs["fgv_nome"];
    $dados["fgv_funcao"] = $rs["fgv_funcao"];

    return true;
}

function insere_funcionario_gv($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('funcionario_gv_fgv_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO funcionario_gv
                (
                    fgv_id,
                    fgv_nome,
                    fgv_funcao
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["fgv_nome"]) . "',
                    '" . in_bd($dados["fgv_funcao"]) . "'
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

function altera_funcionario_gv($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                funcionario_gv
            SET
                fgv_nome = '" . in_bd($dados["fgv_nome"]) . "',
                fgv_funcao = '" . in_bd($dados["fgv_funcao"]) . "'
            WHERE
                fgv_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_funcionario_gv($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                funcionario_gv
            WHERE
                fgv_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_funcionario_gv($dados)
{
    $error_msgs = array();

    if ($dados["fgv_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do Funcionário");

    return $error_msgs;
}

function busca_funcionario_gv($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "fgv_id";
    $config["possiveis_campos"]["Nome"] = "fgv_nome";
    $config["possiveis_campos"]["Desc"] = "fgv_funcao";

    $config["possiveis_ordens"]["Nome"] = "fgv_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "funcionario_gv";
    $config["campo_id"]                 = "fgv_id";
    $config["csv_campos"]               = "fgv_id, fgv_nome";
    $config["tabela"]                   = "funcionario_gv";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
