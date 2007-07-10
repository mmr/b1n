<?
/* $Id: funcoes.inc.php,v 1.1 2002/07/30 13:28:46 binary Exp $ */

function limpa_grupo(&$dados)
{
    $dados["id"]            = "";
    $dados["grp_nome"]      = "";
    $dados["grp_desc"]      = "";
    $dados["grp_funcoes"]   = array();
    $dados["grp_membros"]   = array();
}

function carrega_grupo($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            grp_id,
            grp_nome,
            grp_desc
        FROM
            grupo
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    if (! is_array($rs))
        return false;

    $dados["id"]            = $rs["grp_id"];
    $dados["grp_desc"]      = $rs["grp_desc"];
    $dados["grp_nome"]      = $rs["grp_nome"];
    $dados["grp_funcoes"]   = array();
    $dados["grp_membros"]  = array();

    $rs = $sql->query("
        SELECT
            fnc_id
        FROM
            grp_fnc
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    if (is_array($rs))
    {
        foreach ($rs as $item)
            array_push($dados["grp_funcoes"], $item["fnc_id"]);
    }

    $rs = $sql->query("
        SELECT
            mem_id
        FROM
            grp_mem
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    if (is_array($rs))
    {
        foreach ($rs as $item)
            array_push($dados["grp_membros"], $item["mem_id"]);
    }

    return true;
}


function insere_grupo($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if (!$rs)
        return false;

    // insere grupo
    $rs = $sql->squery("SELECT nextval('grupo_grp_id_seq')");
    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }
    $dados["id"] = $rs["nextval"];
    
    $rs = $sql->query("
        INSERT INTO grupo
        (
            grp_id,
            grp_nome,
            grp_desc
        )
        VALUES
        (
            '" . in_bd($dados["id"])   . "',
            '" . in_bd($dados["grp_nome"]) . "',
            '" . in_bd($dados["grp_desc"]) . "'
        )");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    // insere grupo_funcao
    if (is_array($dados["grp_funcoes"]))
    {
        foreach ($dados["grp_funcoes"] as $fnc_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO grp_fnc
                (
                    grp_id,
                    fnc_id
                )
                VALUES
                (
                    '".in_bd($dados["id"])."',
                    '".in_bd($fnc_id)."'
                )");

            if (!$rs)
            {
                $sql->query("ROLLBACK TRANSACTION");
                return false;
            }
        }
    }

    // insere grupo_membro
    if (is_array($dados["grp_membros"]))
    {
        foreach ($dados["grp_membros"] as $mem_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO grp_mem
                (
                    grp_id,
                    mem_id
                )
                VALUES
                (
                    '".in_bd($dados["id"])."',
                    '".in_bd($mem_id)."'
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

function altera_grupo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if (!$rs)
        return false;

    // altera grupo
    $rs = $sql->query("
        UPDATE grupo
        SET
            grp_nome = '" . in_bd($dados["grp_nome"]) . "',
            grp_desc = '" . in_bd($dados["grp_desc"]) . "'
        WHERE
            grp_id   = '" . in_bd($dados["id"])   ."'");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    // altera grupo_funcao
    $sql->query("
        DELETE FROM grp_fnc
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    if (is_array($dados["grp_funcoes"]))
    {
        foreach ($dados["grp_funcoes"] as $fnc_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO grp_fnc
                (
                    grp_id,
                    fnc_id
                )
                VALUES
                (
                    '" . in_bd($dados["id"])."', 
                    '" . in_bd($fnc_id)         . "'
                )");

            if (!$rs)
            {
                $sql->query("ROLLBACK TRANSACTION");
                return false;
            }
        }
    }

    // altera grupo_membro
    $sql->query("
        DELETE FROM grp_mem
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    if (!$rs)
    {
        $sql->query("ROLLBACK TRANSACTION");
        return false;
    }

    if (is_array($dados["grp_membros"]))
    {
        foreach ($dados["grp_membros"] as $mem_id)
        {
            $rs = $rs && $sql->query("
                INSERT INTO grp_mem
                (
                    grp_id,
                    mem_id
                )
                VALUES
                (
                    '" . in_bd($dados["id"])."',
                    '" . in_bd($mem_id)         ."'
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

function apaga_grupo($sql, $dados)
{
    $rs = $sql->query("
        DELETE FROM grupo
        WHERE
            grp_id = '".in_bd($dados["id"])."'");

    return $rs; 
}


function valida_grupo($sql, $dados)
{
    $error_msgs = array();

    if ($dados["grp_nome"] == "")
	array_push($error_msgs, "É necessário preencher o Nome do grupo");

    $rs = $sql->squery("
        SELECT
            grp_id
        FROM
            grupo
        WHERE
            grp_nome = '".in_bd($dados["grp_nome"])."'");

    if (is_array($rs) && $rs['grp_id'] != $dados["id"])
        array_push($error_msgs, "Nome deve ser único");

    return $error_msgs;
}

function busca_grupo($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

    //$config["possiveis_campos"]["ID"]               = "grp_id";
    $config["possiveis_campos"]["Nome"]             = "grp_nome";
    $config["possiveis_campos"]["Membro"]           = "mem_nome";
    $config["possiveis_campos"]["Função"]           = "fnc_nome";

    $config["possiveis_ordens"]["Nome"]             = "grp_nome";

    $config["possiveis_quantidades"] = array(10, 15, 20, 25, 30);

    $config["session_hash_name"] = "grupo";
    $config["campo_id"] = "grp_id";
    $config["csv_campos"] = "grp_id,grp_nome";
    $config["tabela"] = "busca_grupo";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
