<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:06:54 binary Exp $ */

function limpa_ts_atividade(&$dados)
{
    $dados["id"]                = "";
    $dados["tat_nome"]          = "";
    $dados["tat_desc"]          = "";
    $dados["ts_subatividades"]  = array();
}

function carrega_ts_atividade($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            tat_id,
            tat_nome,
            tat_desc
        FROM
            ts_atividade
        WHERE
            tat_id = '".in_bd($dados["id"])."'");

    if (! is_array($rs))
        return false;

    $dados["id"]                = $rs["tat_id"];
    $dados["tat_desc"]          = $rs["tat_desc"];
    $dados["tat_nome"]          = $rs["tat_nome"];
    $dados["ts_subatividades"]  = array();

    $rs = $sql->query("
        SELECT
            tsa_id
        FROM
            tat_tsa
        WHERE
            tat_id = '".in_bd($dados["id"])."'");

    if (is_array($rs))
    {
        foreach ($rs as $item)
            array_push($dados["ts_subatividades"], $item["tsa_id"]);
    }

    return true;
}


function insere_ts_atividade($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if (!$rs)
        return false;

    // insere ts_atividade
    $rs = $sql->squery("SELECT nextval('ts_atividade_tat_id_seq')");
    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }
    $dados["id"] = $rs["nextval"];
    
    $rs = $sql->query("
        INSERT INTO ts_atividade
        (
            tat_id,
            tat_nome,
            tat_desc
        )
        VALUES
        (
            '" . in_bd($dados["id"])   . "',
            '" . in_bd($dados["tat_nome"]) . "',
            '" . in_bd($dados["tat_desc"]) . "'
        )");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    // insere ts_subatividades
    if (is_array($dados["ts_subatividades"]))
    {
        foreach ($dados["ts_subatividades"] as $tsa_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO tat_mem
                (
                    tat_id,
                    tsa_id
                )
                VALUES
                (
                    '".in_bd($dados["id"])."',
                    '".in_bd($tsa_id)."'
                )");

            if (!$rs)
            {
                $sql->query("ROLLBACK TRANSACTION");
                return false;
            }
        }
    }

    $sql->query("COMMIT TRANSACTION");
    return true;
}

function altera_ts_atividade($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if (!$rs)
        return false;

    // altera ts_atividade
    $rs = $sql->query("
        UPDATE ts_atividade
        SET
            tat_nome = '" . in_bd($dados["tat_nome"]) . "',
            tat_desc = '" . in_bd($dados["tat_desc"]) . "'
        WHERE
            tat_id   = '" . in_bd($dados["id"])   ."'");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    // altera ts_subatividades
    $sql->query("
        DELETE FROM tat_tsa
        WHERE
            tat_id = '".in_bd($dados["id"])."'");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    if (is_array($dados["ts_subatividades"]))
    {
        foreach ($dados["ts_subatividades"] as $tsa_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO tat_tsa
                (
                    tat_id,
                    tsa_id
                )
                VALUES
                (
                    '" . in_bd($dados["id"])."',
                    '" . in_bd($tsa_id)         ."'
                )");

            if (!$rs)
            {
                $sql->query("ROLLBACK TRANSACTION");
                return false;
            }
        }
    }

    $sql->query("COMMIT TRANSACTION");
    return true;

}

function apaga_ts_atividade($sql, $dados)
{
    $rs = $sql->query("
        DELETE FROM ts_atividade
        WHERE
            tat_id = '".in_bd($dados["id"])."'");

    return $rs; 
}


function valida_ts_atividade($sql, $dados)
{
    $error_msgs = array();

    if ($dados["tat_nome"] == "")
	array_push($error_msgs, "É necessário preencher o Nome do Atividade");

    $rs = $sql->squery("
        SELECT
            tat_id
        FROM
            ts_atividade
        WHERE
            tat_nome = '".in_bd($dados["tat_nome"])."'");

    if (is_array($rs) && $rs['tat_id'] != $dados["id"])
        array_push($error_msgs, "Nome deve ser único");

    return $error_msgs;
}

function busca_ts_atividade($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

    //$config["possiveis_campos"]["ID"]     = "tat_id";
    $config["possiveis_campos"]["Atividade"]       = "tat_nome";
    $config["possiveis_campos"]["Sub-Atividade"]   = "tsa_nome";
    $config["possiveis_campos"]["Área"]            = "are_nome";

    $config["possiveis_ordens"]["Atividade"]    = "tat_nome";
    $config["possiveis_ordens"]["Área"]         = "are_nome";

    $config["possiveis_quantidades"] = array(10, 15, 20, 25, 30);

    $config["session_hash_name"] = "ts_atividade";
    $config["campo_id"] = "tat_id";
    $config["csv_campos"] = "tat_id, tat_nome, are_nome";
    $config["tabela"] = "busca_ts_atividade";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
