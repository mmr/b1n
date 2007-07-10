<?
/* $Id: funcoes.inc.php,v 1.7 2002/07/31 20:55:57 binary Exp $ */

function limpa_palestrante(&$dados)
{
    $dados["id"]      = "";
    $dados["cex_id"]  = "";
    $dados["pal_cargo"]     = "";

    $dados["pal_nome"]      = "";
    $dados["pal_nome_contato"] = "";
    $dados["pal_ddd"]  = "";
    $dados["pal_ddi"]  = "";
    $dados["pal_telefone"]  = "";
    $dados["pal_ramal"]     = "";
    $dados["pal_fax"]       = "";
    $dados["pal_email"]     = "";
    $dados["pal_celular"]   = "";
    $dados["pal_curriculo"]     = "";
}

function carrega_palestrante($sql, &$dados)
{
    /*
    FKS:
    cex_id => cargo do contato
    */
    
    $rs = $sql->squery("
        SELECT
            p.cex_id,
            pal_cargo,
            pal_id,
            pal_nome,
            pal_nome_contato,
            pal_ddd,
            pal_ddi,
            pal_telefone,
            pal_ramal,
            pal_fax,
            pal_email,
            pal_celular,
            pal_curriculo
        FROM
            status_contato s,
            cargo_ext c1,
            cargo_ext c2,
            palestrante p
        WHERE
            p.cex_id = c1.cex_id
            AND p.pal_cargo = c2.cex_id
            AND pal_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]      = $rs["pal_id"];
    $dados["cex_id"]  = $rs["cex_id"];
    $dados["pal_cargo"]     = $rs["pal_cargo"];

    $dados["pal_nome"]      = $rs["pal_nome"];
    $dados["pal_nome_contato"] = $rs["pal_nome_contato"];
    $dados["pal_ddd"]  = $rs["pal_ddd"];
    $dados["pal_ddi"]  = $rs["pal_ddi"];
    $dados["pal_telefone"]  = $rs["pal_telefone"];
    $dados["pal_ramal"]     = $rs["pal_ramal"];
    $dados["pal_fax"]       = $rs["pal_fax"];
    $dados["pal_email"]     = $rs["pal_email"];
    $dados["pal_celular"]   = $rs["pal_celular"];
    $dados["pal_curriculo"]     = $rs["pal_curriculo"];

    return true;
}

function insere_palestrante($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('palestrante_pal_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            
            $rs = $sql->query("
                INSERT
                INTO palestrante
                (
                    cex_id,
                    pal_id,
                    pal_cargo,
                    pal_nome,
                    pal_nome_contato,
                    pal_ddd,
                    pal_ddi,
                    pal_telefone,
                    pal_ramal,
                    pal_fax,
                    pal_email,
                    pal_celular,
                    pal_curriculo
                )
                VALUES 
                (
                    '" . in_bd($dados["cex_id"])    . "',
                    '" . in_bd($dados["id"])        . "',
                    '" . in_bd($dados["pal_cargo"]) . "',
                    '" . in_bd($dados["pal_nome"])  . "',
                    '" . in_bd($dados["pal_nome_contato"]) . "',
                    '" . in_bd($dados["pal_ddd"])  . "',
                    '" . in_bd($dados["pal_ddi"])  . "',
                    '" . in_bd($dados["pal_telefone"])  . "',
                    '" . in_bd($dados["pal_ramal"])     . "',
                    '" . in_bd($dados["pal_fax"])       . "',
                    '" . in_bd($dados["pal_email"])     . "',
                    '" . in_bd($dados["pal_celular"])   . "',
                    '" . in_bd($dados["pal_curriculo"]) . "'
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

function altera_palestrante($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
            $query = "
            UPDATE
                palestrante
            SET
                cex_id = '"     . in_bd($dados["cex_id"])   . "',
                pal_id = '"     . in_bd($dados["id"])       . "',
                pal_cargo = '"  . in_bd($dados["pal_cargo"]). "',
                pal_nome = '"   . in_bd($dados["pal_nome"]) . "',
                pal_nome_contato = '" . in_bd($dados["pal_nome_contato"]) . "',
                pal_ddd = '"   . in_bd($dados["pal_ddd"]) . "',
                pal_ddi = '"   . in_bd($dados["pal_ddi"]) . "',
                pal_telefone = '"   . in_bd($dados["pal_telefone"]) . "',
                pal_ramal = '"      . in_bd($dados["pal_ramal"])    . "',
                pal_fax = '"        . in_bd($dados["pal_fax"])      . "',
                pal_email = '"      . in_bd($dados["pal_email"])    . "',
                pal_celular = '"    . in_bd($dados["pal_celular"])  . "',
                pal_curriculo   = '"    . in_bd($dados["pal_curriculo"]) . "'
            WHERE
                pal_id = '" . in_bd($dados["id"])   ."'";

        $rs = $sql->query($query);;
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_palestrante($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                palestrante
            WHERE
                pal_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_palestrante($dados)
{
    $error_msgs = array();

    if ($dados["pal_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do palestrante");

    if ($dados["pal_nome_contato"] == "")
        array_push($error_msgs, "É necessario preencher o nome do contato com o palestrante");

    if(! consis_telefone($dados["pal_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if(! consis_telefone($dados["pal_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if(! consis_telefone($dados["pal_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if ($dados["cex_id"] == "")
        array_push($error_msgs, "É necessario selecionar um cargo para o contato");

    if ($dados["pal_cargo"] == "")
        array_push($error_msgs, "É necessario selecionar um cargo para o palestrante");

    return $error_msgs;
}

function busca_palestrante($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

    $config["possiveis_campos"]["Nome"]         = "pal_nome";
    $config["possiveis_campos"]["Contato"]      = "pal_nome_contato";
    $config["possiveis_campos"]["Telefone"]     = "pal_telefone";
    $config["possiveis_campos"]["Fax"]          = "pal_fax";
    $config["possiveis_campos"]["Celular"]      = "pal_celular";
    $config["possiveis_campos"]["Email"]        = "pal_email";
    $config["possiveis_campos"]["Curriculo"]    = "pal_curriculo";

    $config["possiveis_ordens"]["Nome"]     = "pal_nome";
    $config["possiveis_ordens"]["Contato"]  = "pal_nome_contato";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "palestrante";
    $config["campo_id"]                 = "pal_id";
    $config["csv_campos"]               = "pal_id, pal_nome, pal_nome_contato";
    $config["tabela"]                   = "palestrante";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
