<?
/* $Id: funcoes.inc.php,v 1.1 2002/08/05 13:30:21 binary Exp $ */

function limpa_professor(&$dados)
{
    $dados["id"]            = "";
    $dados["dpt_id"]        = "";
    $dados["prf_nome"]      = "";
    $dados["prf_ddd"]  = "";
    $dados["prf_ddi"]  = "";
    $dados["prf_telefone"]  = "";
    $dados["prf_ramal"]     = "";
    $dados["prf_fax"]       = "";
    $dados["prf_celular"]   = "";
    $dados["prf_email"]     = "";
    $dados["prf_dt_nasci"]  = "";
    $dados["prf_ajuda_ej"]  = "";
}

function carrega_professor($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            dpt_id,
            prf_id,
            prf_nome,
            prf_ddd,
            prf_ddi,
            prf_telefone,
            prf_ramal,
            prf_fax,
            prf_celular,
            prf_email,
            DATE_PART('day', prf_dt_nasci)   AS prf_dt_nasci_d,
            DATE_PART('month', prf_dt_nasci) AS prf_dt_nasci_m,
            DATE_PART('year', prf_dt_nasci)  AS prf_dt_nasci_a,
            prf_ajuda_ej
        FROM
            professor
        WHERE
            prf_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]            = $rs["prf_id"];
    $dados["dpt_id"]        = $rs["dpt_id"];
    $dados["prf_nome"]      = $rs["prf_nome"];
    $dados["prf_ddd"]  = $rs["prf_ddd"];
    $dados["prf_ddi"]  = $rs["prf_ddi"];
    $dados["prf_telefone"]  = $rs["prf_telefone"];
    $dados["prf_ramal"]     = $rs["prf_ramal"];
    $dados["prf_fax"]       = $rs["prf_fax"];
    $dados["prf_celular"]   = $rs["prf_celular"];
    $dados["prf_email"]     = $rs["prf_email"];
    $dados["prf_dt_nasci"]  = array("dia" => $rs["prf_dt_nasci_d"],
                                    "mes" => $rs["prf_dt_nasci_m"],
                                    "ano" => $rs["prf_dt_nasci_a"]);
    $dados["prf_ajuda_ej"]  = $rs["prf_ajuda_ej"];

    return true;
}

function insere_professor($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('professor_prf_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO professor
                (
                    dpt_id,
                    prf_id,
                    prf_nome,
                    prf_ddd,
                    prf_ddi,
                    prf_telefone,
                    prf_ramal,
                    prf_fax,
                    prf_celular,
                    prf_email,
                    prf_dt_nasci,
                    prf_ajuda_ej
                )
                VALUES 
                (
                    '" . in_bd($dados["dpt_id"])        . "',
                    '" . in_bd($dados["id"])            . "',
                    '" . in_bd($dados["prf_nome"])      . "',
                    '" . in_bd($dados["prf_ddd"])  . "',
                    '" . in_bd($dados["prf_ddi"])  . "',
                    '" . in_bd($dados["prf_telefone"])  . "',
                    '" . in_bd($dados["prf_ramal"])     . "',
                    '" . in_bd($dados["prf_fax"])       . "',
                    '" . in_bd($dados["prf_celular"])   . "',
                    '" . in_bd($dados["prf_email"])     . "',
                    '"  . hash_to_databd($dados["prf_dt_nasci"]) . "',
                    '" . in_bd($dados["prf_ajuda_ej"])  . "'
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

function altera_professor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $query = "
            UPDATE
                professor
            SET
                dpt_id = '"         . in_bd($dados["dpt_id"])       . "',
                prf_nome = '"       . in_bd($dados["prf_nome"])     . "',
                prf_ddd = '"   . in_bd($dados["prf_ddd"]) . "',
                prf_ddi = '"   . in_bd($dados["prf_ddi"]) . "',
                prf_telefone = '"   . in_bd($dados["prf_telefone"]) . "',
                prf_ramal = '"      . in_bd($dados["prf_ramal"])    . "',
                prf_fax = '"        . in_bd($dados["prf_fax"])      . "',
                prf_celular = '"    . in_bd($dados["prf_celular"])  . "',
                prf_email = '"      . in_bd($dados["prf_email"])    . "',
                prf_dt_nasci =  '"  . hash_to_databd($dados["prf_dt_nasci"]) . "',
                prf_ajuda_ej = '"   . in_bd($dados["prf_ajuda_ej"]) . "'
            WHERE
                prf_id = '" . in_bd($dados["id"])   ."'";

        $rs = $sql->query($query);

        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_professor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                professor
            WHERE
                prf_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_professor($dados)
{
    $error_msgs = array();

    if ($dados["prf_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do professor");

    if ($dados["dpt_id"] == "")
        array_push($error_msgs, "É necessario selecionar o departamento do professor");

    if (! consis_data($dados["prf_dt_nasci"]["dia"],
                      $dados["prf_dt_nasci"]["mes"],
                      $dados["prf_dt_nasci"]["ano"]))
        array_push($error_msgs, "É necessário preencher o campo Data de Nascimento com uma data válida");

    if(! consis_email($dados["prf_email"], 0))
        array_push($error_msgs, "Email inválido");

    if(! consis_telefone($dados["prf_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if(! consis_telefone($dados["prf_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if(! consis_telefone($dados["prf_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if(! consis_telefone($dados["prf_fax"], 0))
        array_push($error_msgs, "Fax inválido");
 
    if(! consis_telefone($dados["prf_ramal"], 0))
        array_push($error_msgs, "Ramal inválido");
 
    if(! consis_telefone($dados["prf_celular"], 0))
        array_push($error_msgs, "Celular inválido");

    return $error_msgs;
}

function busca_professor($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "prf_id";
    $config["possiveis_campos"]["Nome"]         = "prf_nome";
    $config["possiveis_campos"]["Telefone" ]    = "prf_telefone";
    $config["possiveis_campos"]["Celular" ]     = "prf_celular";
    $config["possiveis_campos"]["Email" ]       = "prf_email";

    $config["possiveis_ordens"]["Nome"] = "prf_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "professor";
    $config["campo_id"]                 = "prf_id";
    $config["csv_campos"]               = "prf_id, prf_nome";
    $config["tabela"]                   = "professor";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
