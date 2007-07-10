<?
/* $Id: funcoes.inc.php,v 1.1 2002/08/05 13:30:21 binary Exp $ */

function limpa_departamento(&$dados)
{
    $dados["id"]       = "";
    $dados["dpt_nome"] = "";
    $dados["dpt_desc"] = "";
    $dados["dpt_andar"] = "";
    $dados["dpt_ramal"] = "";
}

function carrega_departamento($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            dpt_id,
            dpt_nome,
            dpt_desc,
            dpt_andar,
            dpt_ramal
        FROM
            departamento
        WHERE
            dpt_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["dpt_id"];
    $dados["dpt_nome"] = $rs["dpt_nome"];
    $dados["dpt_desc"] = $rs["dpt_desc"];
    $dados["dpt_andar"] = $rs["dpt_andar"];
    $dados["dpt_ramal"] = $rs["dpt_ramal"];

    return true;
}

function insere_departamento($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('departamento_dpt_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO departamento
                (
                    dpt_id,
                    dpt_nome,
                    dpt_desc,
                    dpt_andar,
                    dpt_ramal
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["dpt_nome"]) . "',
                    '" . in_bd($dados["dpt_desc"]) . "',
                    '" . in_bd($dados["dpt_andar"]) . "',
                    '" . in_bd($dados["dpt_ramal"]) . "'
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

function altera_departamento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                departamento
            SET
                dpt_nome = '"  . in_bd($dados["dpt_nome"])  . "',
                dpt_desc = '"  . in_bd($dados["dpt_desc"])  . "',
                dpt_andar = '" . in_bd($dados["dpt_andar"]) . "',
                dpt_ramal = '" . in_bd($dados["dpt_ramal"]) . "'
            WHERE
                dpt_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_departamento($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                departamento
            WHERE
                dpt_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_departamento($dados)
{
    $error_msgs = array();

    if ($dados["dpt_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do departamento");

    return $error_msgs;
}

function busca_departamento($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "dpt_id";
    $config["possiveis_campos"]["Nome"] = "dpt_nome";
    $config["possiveis_campos"]["Desc"] = "dpt_desc";

    $config["possiveis_ordens"]["Nome"] = "dpt_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "departamento";
    $config["campo_id"]                 = "dpt_id";
    $config["csv_campos"]               = "dpt_id, dpt_nome";
    $config["tabela"]                   = "departamento";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
