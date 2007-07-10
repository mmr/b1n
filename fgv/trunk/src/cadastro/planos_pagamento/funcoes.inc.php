<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:07:39 binary Exp $ */

function limpa_plano_pgto(&$dados)
{
    $dados["id"]       = "";
    $dados["ppg_nome"] = "";
    $dados["ppg_desc"] = "";
    $dados["ppg_plano"] = "";
}

function carrega_plano_pgto($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            ppg_id,
            ppg_nome,
            ppg_desc,
            ppg_plano
        FROM
            plano_pgto
        WHERE
            ppg_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["ppg_id"];
    $dados["ppg_nome"] = $rs["ppg_nome"];
    $dados["ppg_desc"] = $rs["ppg_desc"];
    $dados["ppg_plano"] = $rs["ppg_plano"];

    return true;
}

function insere_plano_pgto($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('plano_pgto_ppg_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO plano_pgto
                (
                    ppg_id,
                    ppg_nome,
                    ppg_desc,
                    ppg_plano
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["ppg_nome"]) . "',
                    '" . in_bd($dados["ppg_desc"]) . "',
                    '" . in_bd($dados["ppg_plano"]) . "'
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

function altera_plano_pgto($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                plano_pgto
            SET
                ppg_nome = '" . in_bd($dados["ppg_nome"]) . "',
                ppg_desc = '" . in_bd($dados["ppg_desc"]) . "',
                ppg_plano = '" . in_bd($dados["ppg_plano"]) . "'
            WHERE
                ppg_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_plano_pgto($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                plano_pgto
            WHERE
                ppg_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_plano_pgto($dados)
{
    $error_msgs = array();

    if ($dados["ppg_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do Plano de Pagamento");

    if (! consis_inteiro($dados["ppg_plano"]))
        array_push($error_msgs, "É necessario preencher o número de parcelas para o Plano de Pagamento");
        

    return $error_msgs;
}

function busca_plano_pgto($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "ppg_id";
    $config["possiveis_campos"]["Nome"] = "ppg_nome";
    $config["possiveis_campos"]["Desc"] = "ppg_desc";
    $config["possiveis_campos"]["Parcelas"] = "ppg_plano";

    $config["possiveis_ordens"]["Nome"] = "ppg_nome";
    $config["possiveis_ordens"]["Parcelas"] = "ppg_plano";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "plano_pgto";
    $config["campo_id"]                 = "ppg_id";
    $config["csv_campos"]               = "ppg_id, ppg_nome, ppg_plano";
    $config["tabela"]                   = "plano_pgto";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
