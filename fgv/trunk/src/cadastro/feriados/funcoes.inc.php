<?
/* $Id: funcoes.inc.php,v 1.2 2002/07/31 20:55:56 binary Exp $ */

function limpa_feriado(&$dados)
{
    $dados["id"]            = "";
    $dados["frd_nome"]      = "";
    $dados["frd_desc"]      = "";
    $dados["frd_dt_data"]   = "";
}

function carrega_feriado($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            frd_id,
            frd_nome,
            frd_desc,
            DATE_PART('day', frd_dt_data) AS frd_dt_data_d,
            DATE_PART('month', frd_dt_data) AS frd_dt_data_m,
            DATE_PART('year', frd_dt_data) AS frd_dt_data_a
        FROM
            feriado
        WHERE
            frd_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]            = $rs["frd_id"];
    $dados["frd_nome"]      = $rs["frd_nome"];
    $dados["frd_desc"]      = $rs["frd_desc"];
    $dados["frd_dt_data"]   = array("dia" => $rs["frd_dt_data_d"],
                                    "mes" => $rs["frd_dt_data_m"],
                                    "ano" => $rs["frd_dt_data_a"]);

    return true;
}

function insere_feriado($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('feriado_frd_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO feriado
                (
                    frd_id,
                    frd_nome,
                    frd_desc,
                    frd_dt_data
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["frd_nome"]) . "',
                    '" . in_bd($dados["frd_desc"]) . "',
                    '" . in_bd(hash_to_databd($dados["frd_dt_data"])) . "'
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

function altera_feriado($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                feriado
            SET
                frd_nome = '" . in_bd($dados["frd_nome"]) . "',
                frd_desc = '" . in_bd($dados["frd_desc"]) . "',
                frd_dt_data = '" . in_bd(hash_to_databd($dados["frd_dt_data"])) . "'
            WHERE
                frd_id = '"   . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_feriado($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                feriado
            WHERE
                frd_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_feriado($sql, $dados)
{
    $error_msgs = array();

    if ($dados["frd_nome"] == "")
	array_push($error_msgs, "É necessário preencher o nome do feriado");
        
    if (! consis_data($dados["frd_dt_data"]["dia"], 
                      $dados["frd_dt_data"]["mes"], 
                      $dados["frd_dt_data"]["ano"])) 
	array_push($error_msgs, "É necessário preencher o campo Data com uma data válida");

    $rs = $sql->squery("
        SELECT
            frd_id
        FROM
            feriado
        WHERE
            frd_nome = '".in_bd($dados["frd_nome"])."'");

    if (is_array($rs) && $rs['frd_id'] != $dados["id"])
        array_push($error_msgs, "Já existe um feriado com esse nome cadastrado");

    return $error_msgs;
}

function busca_feriado($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "frd_id";
    $config["possiveis_campos"]["Nome"] = "frd_nome";
    $config["possiveis_campos"]["Desc"] = "frd_desc";

    $config["possiveis_ordens"]["Nome"] = "frd_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "feriado";
    $config["campo_id"]                 = "frd_id";
    $config["csv_campos"]               = "frd_id, frd_nome, DATE_PART( 'epoch', frd_dt_data ) AS frd_dt_data";
    $config["tabela"]                   = "feriado";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
