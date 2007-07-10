<?
/* $Id: funcoes.inc.php,v 1.10 2002/07/31 20:55:57 binary Exp $ */

function limpa_patrocinador(&$dados)
{
    $dados["id"]      = "";
    $dados["cla_id"]  = "";
    $dados["set_id"]  = "";
    $dados["cex_id"]  = "";

    $dados["pat_nome"]      = "";
    $dados["pat_nome_contato"] = "";
    $dados["pat_ddd"]  = "";
    $dados["pat_ddi"]  = "";
    $dados["pat_telefone"]  = "";
    $dados["pat_ramal"]     = "";
    $dados["pat_fax"]       = "";
    $dados["pat_email"]     = "";
    $dados["pat_celular"]   = "";
    $dados["pat_apoiador"]  = "";
    $dados["pat_texto"]     = "";
}

function carrega_patrocinador($sql, &$dados)
{
    /*
    FKS:
    cla_id => classificacao
    set_id => setor
    cex_id => cargo do contato
    */
    
    $query = " 
        SELECT
            cla_id,
            set_id,
            cex_id,
            pat_id,
            pat_nome,
            pat_nome_contato,
            pat_ddd,
            pat_ddi,
            pat_telefone,
            pat_ramal,
            pat_fax,
            pat_email,
            pat_celular,
            pat_apoiador,
            pat_texto
        FROM
            patrocinador
            NATURAL LEFT OUTER JOIN pat_class
            NATURAL LEFT OUTER JOIN setor
            NATURAL LEFT OUTER JOIN status_contato
            NATURAL LEFT OUTER JOIN cargo_ext
        WHERE
            pat_id = '" . in_bd($dados["id"]) . "'";

    //pq( $query,0 );

    $rs = $sql->squery( $query );

    if (! is_array($rs))
        return false;

    $dados["id"]      = $rs["pat_id"];
    $dados["cla_id"]  = $rs["cla_id"];
    $dados["set_id"]  = $rs["set_id"];
    $dados["cex_id"]  = $rs["cex_id"];

    $dados["pat_nome"]      = $rs["pat_nome"];
    $dados["pat_nome_contato"] = $rs["pat_nome_contato"];
    $dados["pat_ddd"]  = $rs["pat_ddd"];
    $dados["pat_ddi"]  = $rs["pat_ddi"];
    $dados["pat_telefone"]  = $rs["pat_telefone"];
    $dados["pat_ramal"]     = $rs["pat_ramal"];
    $dados["pat_fax"]       = $rs["pat_fax"];
    $dados["pat_email"]     = $rs["pat_email"];
    $dados["pat_celular"]   = $rs["pat_celular"];
    $dados["pat_apoiador"]  = $rs["pat_apoiador"];
    $dados["pat_texto"]     = $rs["pat_texto"];

    return true;
}

function insere_patrocinador($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('patrocinador_pat_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            
            $rs = $sql->query("
                INSERT
                INTO patrocinador
                (
                    cla_id,
                    set_id,
                    cex_id,
                    pat_id,
                    pat_nome,
                    pat_nome_contato,
                    pat_ddd,
                    pat_ddi,
                    pat_telefone,
                    pat_ramal,
                    pat_fax,
                    pat_email,
                    pat_celular,
                    pat_apoiador,
                    pat_texto
                )
                VALUES 
                (
                    '" . in_bd($dados["cla_id"])   . "',
                    '" . in_bd($dados["set_id"])   . "',
                    '" . in_bd($dados["cex_id"])   . "',
                    '" . in_bd($dados["id"])       . "',
                    '" . in_bd($dados["pat_nome"])      . "',
                    '" . in_bd($dados["pat_nome_contato"]) . "',
                    '" . in_bd($dados["pat_ddd"]) . "',
                    '" . in_bd($dados["pat_ddi"]) . "',
                    '" . in_bd($dados["pat_telefone"]) . "',
                    '" . in_bd($dados["pat_ramal"])     . "',
                    '" . in_bd($dados["pat_fax"])       . "',
                    '" . in_bd($dados["pat_email"])     . "',
                    '" . in_bd($dados["pat_celular"])   . "',
                    '" . in_bd($dados["pat_apoiador"])  . "',
                    '" . in_bd($dados["pat_texto"])     . "'
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

function altera_patrocinador($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
            $query = "
            UPDATE
                patrocinador
            SET
                cla_id = '"     . in_bd($dados["cla_id"])   . "',
                set_id = '"     . in_bd($dados["set_id"])   . "',
                cex_id = '"     . in_bd($dados["cex_id"])   . "',
                pat_id = '"     . in_bd($dados["id"])       . "',
                pat_nome = '"   . in_bd($dados["pat_nome"]) . "',
                pat_nome_contato = '" . in_bd($dados["pat_nome_contato"]) . "',
                pat_ddd = '"   . in_bd($dados["pat_ddd"]) . "',
                pat_ddi = '"   . in_bd($dados["pat_ddi"]) . "',
                pat_telefone = '"   . in_bd($dados["pat_telefone"]) . "',
                pat_ramal = '"      . in_bd($dados["pat_ramal"])    . "',
                pat_fax = '"        . in_bd($dados["pat_fax"])      . "',
                pat_email = '"      . in_bd($dados["pat_email"])    . "',
                pat_celular = '"    . in_bd($dados["pat_celular"])  . "',
                pat_apoiador = '"   . in_bd($dados["pat_apoiador"]) . "',
                pat_texto   = '"    . in_bd($dados["pat_texto"]) . "'
            WHERE
                pat_id = '" . in_bd($dados["id"])   ."'";

        $rs = $sql->query($query);;
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_patrocinador($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                patrocinador
            WHERE
                pat_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_patrocinador($dados)
{
    $error_msgs = array();

    if ($dados["pat_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do patrocinador");

    if ($dados["pat_nome_contato"] == "")
        array_push($error_msgs, "É necessario preencher o nome do contato com o patrocinador");
        
    if(! consis_telefone($dados["pat_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if(! consis_telefone($dados["pat_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if(! consis_telefone($dados["pat_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if ($dados["cex_id"] == "")
        array_push($error_msgs, "É necessario selecionar um cargo para o contato");

    if ($dados["cla_id"] == "")
        array_push($error_msgs, "É necessario selecionar uma classificação para o patrocinador");

    if ($dados["set_id"] == "")
        array_push($error_msgs, "É necessario selecionar um setor de atuação para o patrocinador");

    return $error_msgs;
}

function busca_patrocinador($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "pat_id";
    $config["possiveis_campos"]["Nome"]         = "pat_nome";
    $config["possiveis_campos"]["Contato"]      = "pat_nome_contato";
    $config["possiveis_campos"]["Telefone"]     = "pat_telefone";
    $config["possiveis_campos"]["Fax"]          = "pat_fax";
    $config["possiveis_campos"]["Celular"]      = "pat_celular";
    $config["possiveis_campos"]["Email"]        = "pat_email";
    $config["possiveis_campos"]["Cometarios"]   = "pat_texto";

    $config["possiveis_ordens"]["Nome"]     = "pat_nome";
    $config["possiveis_ordens"]["Contato"]  = "pat_nome_contato";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "patrocinador";
    $config["campo_id"]                 = "pat_id";
    $config["csv_campos"]               = "pat_id, pat_nome, pat_nome_contato";
    $config["tabela"]                   = "patrocinador";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
