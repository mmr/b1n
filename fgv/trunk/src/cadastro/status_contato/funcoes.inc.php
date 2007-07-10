<? /* $Id: funcoes.inc.php,v 1.4 2002/03/21 18:35:22 binary Exp $ */ ?>

<?

function limpa_status_contato(&$dados)
{
    $dados["id"]       = "";
    $dados["stc_nome"] = "";
    $dados["stc_desc"] = "";
}

function carrega_status_contato($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            stc_id,
            stc_nome,
            stc_desc
        FROM
            status_contato
        WHERE
            stc_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["stc_id"];
    $dados["stc_nome"] = $rs["stc_nome"];
    $dados["stc_desc"] = $rs["stc_desc"];

    return true;
}

function insere_status_contato($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('status_contato_stc_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO status_contato
                    (stc_id,
                     stc_nome,
                     stc_desc)
                VALUES 
                    ('". in_bd($dados["id"])   . "',
                    '" . in_bd($dados["stc_nome"]) . "',
                    '" . in_bd($dados["stc_desc"]) . "')");
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

function altera_status_contato($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                status_contato
            SET
                stc_nome = '" . in_bd($dados["stc_nome"]) . "',
                stc_desc = '" . in_bd($dados["stc_desc"]) . "'
            WHERE
                stc_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_status_contato($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                status_contato
            WHERE
                stc_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_status_contato($dados)
{
    $error_msgs = array();

    if ($dados["stc_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do status da contato");

    return $error_msgs;
}

function busca_status_contato($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "stc_id";
    $config["possiveis_campos"]["Nome"] = "stc_nome";
    $config["possiveis_campos"]["Desc"] = "stc_desc";

    $config["possiveis_ordens"]["Nome"] = "stc_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "status_contato";
    $config["campo_id"]                 = "stc_id";
    $config["csv_campos"]               = "stc_id, stc_nome";
    $config["tabela"]                   = "status_contato";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
