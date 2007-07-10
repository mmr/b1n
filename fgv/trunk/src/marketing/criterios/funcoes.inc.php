<?
/* $Id: funcoes.inc.php,v 1.2 2002/04/12 17:28:56 binary Exp $ */

function limpa_criterio(&$dados)
{
    $dados["id"]       = "";
    $dados["cri_nome"] = "";
    $dados["cri_peso"] = "";
    $dados["cri_desc"] = "";
}

function carrega_criterio($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cri_id,
            cri_nome,
            cri_peso,
            cri_desc
        FROM
            criterio
        WHERE
            cri_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["cri_id"];
    $dados["cri_nome"] = $rs["cri_nome"];
    $dados["cri_peso"] = $rs["cri_peso"];
    $dados["cri_desc"] = $rs["cri_desc"];

    return true;
}

function insere_criterio($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('criterio_cri_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO criterio
                (
                    cri_id,
                    cri_nome,
                    cri_peso,
                    cri_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["cri_nome"]) . "',
                    '" . in_bd($dados["cri_peso"]) . "',
                    '" . in_bd($dados["cri_desc"]) . "'
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

function altera_criterio($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                criterio
            SET
                cri_nome = '" . in_bd($dados["cri_nome"]) . "',
                cri_peso = '" . in_bd($dados["cri_peso"]) . "',
                cri_desc = '" . in_bd($dados["cri_desc"]) . "'
            WHERE
                cri_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_criterio($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                criterio
            WHERE
                cri_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_criterio($dados)
{
    $error_msgs = array();

    if ($dados["cri_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do critério");

    return $error_msgs;
}

function busca_criterio($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cri_id";
    $config["possiveis_campos"]["Nome"] = "cri_nome";
    $config["possiveis_campos"]["Desc"] = "cri_desc";
    $config["possiveis_campos"]["Peso"] = "cri_peso";

    $config["possiveis_ordens"]["Nome"] = "cri_nome";
    $config["possiveis_ordens"]["Peso"] = "cri_peso";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "criterio";
    $config["campo_id"]                 = "cri_id";
    $config["csv_campos"]               = "cri_id, cri_nome, cri_peso";
    $config["tabela"]                   = "criterio";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
