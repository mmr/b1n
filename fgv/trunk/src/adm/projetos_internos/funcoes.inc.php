<?
/* $Id: funcoes.inc.php,v 1.1 2002/04/16 20:35:16 binary Exp $ */

function limpa_prj_interno(&$dados)
{
    $dados["id"]            = "";
    $dados["pin_nome"]      = "";
    $dados["pin_desc"]      = "";
}

function carrega_prj_interno($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            pin_id,
            pin_nome,
            pin_desc
        FROM
            prj_interno
        WHERE
            pin_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]            = $rs["pin_id"];
    $dados["pin_nome"]      = $rs["pin_nome"];
    $dados["pin_desc"]      = $rs["pin_desc"];

    return true;
}

function insere_prj_interno($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('prj_interno_pin_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO prj_interno
                (
                    pin_id,
                    pin_nome,
                    pin_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["pin_nome"]) . "',
                    '" . in_bd($dados["pin_desc"]) . "'
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

function altera_prj_interno($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                prj_interno
            SET
                pin_nome = '" . in_bd($dados["pin_nome"]) . "',
                pin_desc = '" . in_bd($dados["pin_desc"]) . "'
            WHERE
                pin_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_prj_interno($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                prj_interno
            WHERE
                pin_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_prj_interno($sql, $dados)
{
    $error_msgs = array();

    if ($dados["pin_nome"] == "")
	array_push($error_msgs, "É necessário preencher o nome do projeto interno");
        
    $rs = $sql->squery("
        SELECT
            pin_id
        FROM
            prj_interno
        WHERE
            pin_nome = '".in_bd($dados["pin_nome"])."'");

    if (is_array($rs) && $rs['pin_id'] != $dados["id"])
        array_push($error_msgs, "Já existe um projeto interno com esse nome cadastrado");

    return $error_msgs;
}

function busca_prj_interno($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "pin_id";
    $config["possiveis_campos"]["Nome"] = "pin_nome";
    $config["possiveis_campos"]["Desc"] = "pin_desc";

    $config["possiveis_ordens"]["Nome"] = "pin_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "prj_interno";
    $config["campo_id"]                 = "pin_id";
    $config["csv_campos"]               = "pin_id, pin_nome";
    $config["tabela"]                   = "prj_interno";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
