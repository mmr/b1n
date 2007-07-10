<? /* $Id: funcoes.inc.php,v 1.6 2002/07/31 20:55:56 binary Exp $ */ ?>

<?

function limpa_fornecedor(&$dados)
{
    $dados["id"]      = "";
    $dados["ram_id"]  = "";
    $dados["cex_id"]  = "";
    $dados["for_nome"]      = "";
    $dados["for_nome_contato"] = "";
    $dados["for_ddd"]  = "";
    $dados["for_ddi"]  = "";
    $dados["for_telefone"]  = "";
    $dados["for_ramal"]     = "";
    $dados["for_fax"]       = "";
    $dados["for_email"]     = "";
    $dados["for_celular"]   = "";
    $dados["for_homepage"]  = "";
    $dados["for_texto"]     = "";
}

function carrega_fornecedor($sql, &$dados)
{
    /*
    FKS:
    ram_id => ramo de atividade
    cex_id => cargo do contato
    */
    
    $rs = $sql->squery("
        SELECT
            ram_id,
            cex_id,
            for_id,
            for_nome,
            for_nome_contato,
            for_ddd,
            for_ddi,
            for_telefone,
            for_ramal,
            for_fax,
            for_email,
            for_celular,
            for_homepage,
            for_texto
        FROM
            ramo
            NATURAL JOIN status_contato
            NATURAL JOIN cargo_ext
            NATURAL JOIN fornecedor
        WHERE
            for_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]      = $rs["for_id"];
    $dados["ram_id"]  = $rs["ram_id"];
    $dados["cex_id"]  = $rs["cex_id"];

    $dados["for_nome"]      = $rs["for_nome"];
    $dados["for_nome_contato"] = $rs["for_nome_contato"];
    $dados["for_ddd"]  = $rs["for_ddd"];
    $dados["for_ddi"]  = $rs["for_ddi"];
    $dados["for_telefone"]  = $rs["for_telefone"];
    $dados["for_ramal"]     = $rs["for_ramal"];
    $dados["for_fax"]       = $rs["for_fax"];
    $dados["for_email"]     = $rs["for_email"];
    $dados["for_celular"]   = $rs["for_celular"];
    $dados["for_homepage"]  = $rs["for_homepage"];
    $dados["for_texto"]     = $rs["for_texto"];

    return true;
}

function insere_fornecedor($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('fornecedor_for_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            
            $rs = $sql->query("
                INSERT INTO fornecedor
                (
                    ram_id,
                    cex_id,
                    for_id,
                    for_nome,
                    for_nome_contato,
                    for_ddd,
                    for_ddi,
                    for_telefone,
                    for_ramal,
                    for_fax,
                    for_email,
                    for_celular,
                    for_homepage,
                    for_texto
                )
                VALUES 
                (
                    '" . in_bd($dados["ram_id"])   . "',
                    '" . in_bd($dados["cex_id"])   . "',
                    '" . in_bd($dados["id"])       . "',
                    '" . in_bd($dados["for_nome"])      . "',
                    '" . in_bd($dados["for_nome_contato"]) . "',
                    '" . in_bd($dados["for_ddd"]) . "',
                    '" . in_bd($dados["for_ddi"]) . "',
                    '" . in_bd($dados["for_telefone"]) . "',
                    '" . in_bd($dados["for_ramal"])     . "',
                    '" . in_bd($dados["for_fax"])       . "',
                    '" . in_bd($dados["for_email"])     . "',
                    '" . in_bd($dados["for_celular"])   . "',
                    '" . in_bd($dados["for_homepage"])  . "',
                    '" . in_bd($dados["for_texto"])     . "'
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

function altera_fornecedor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
            $query = "
            UPDATE
                fornecedor
            SET
                ram_id = '"     . in_bd($dados["ram_id"])   . "',
                cex_id = '"     . in_bd($dados["cex_id"])   . "',
                for_id = '"     . in_bd($dados["id"])       . "',
                for_nome = '"   . in_bd($dados["for_nome"]) . "',
                for_nome_contato = '" . in_bd($dados["for_nome_contato"]) . "',
                for_ddi = '"   . in_bd($dados["for_ddi"]) . "',
                for_ddd = '"   . in_bd($dados["for_ddd"]) . "',
                for_telefone = '"   . in_bd($dados["for_telefone"]) . "',
                for_ramal = '"      . in_bd($dados["for_ramal"])    . "',
                for_fax = '"        . in_bd($dados["for_fax"])      . "',
                for_email = '"      . in_bd($dados["for_email"])    . "',
                for_celular = '"    . in_bd($dados["for_celular"])  . "',
                for_homepage = '"   . in_bd($dados["for_homepage"]) . "',
                for_texto   = '"    . in_bd($dados["for_texto"]) . "'
            WHERE
                for_id = '" . in_bd($dados["id"])   ."'";

        $rs = $sql->query($query);;
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_fornecedor($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                fornecedor
            WHERE
                for_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_fornecedor($dados)
{
    $error_msgs = array();

    if ($dados["for_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do fornecedor");

    if ($dados["for_nome_contato"] == "")
        array_push($error_msgs, "É necessario preencher o nome do contato com o fornecedor");

    if ($dados["cex_id"] == "")
        array_push($error_msgs, "É necessario selecionar um cargo para o contato");

    if ($dados["ram_id"] == "")
        array_push($error_msgs, "É necessario selecionar um ramo de atividade para o fornecedor");

    if(! consis_email($dados["for_email"], 0))
        array_push($error_msgs, "Email inválido");

    if(! consis_telefone($dados["for_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if(! consis_telefone($dados["for_ramal"], 0))
        array_push($error_msgs, "Ramal inválido");
 
    if(! consis_telefone($dados["for_celular"], 0))
        array_push($error_msgs, "Celular inválido");

    return $error_msgs;
}

function busca_fornecedor($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "for_id";
    $config["possiveis_campos"]["Nome"]         = "for_nome";
    $config["possiveis_campos"]["Contato"]      = "for_nome_contato";
    $config["possiveis_campos"]["Telefone"]     = "for_telefone";
    $config["possiveis_campos"]["Fax"]          = "for_fax";
    $config["possiveis_campos"]["Celular"]      = "for_celular";
    $config["possiveis_campos"]["Email"]        = "for_email";
    $config["possiveis_campos"]["HomePage"]     = "for_homepage";
    $config["possiveis_campos"]["Cometários"]   = "for_texto";

    $config["possiveis_ordens"]["Nome"]     = "for_nome";
    $config["possiveis_ordens"]["Contato"]  = "for_nome_contato";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "fornecedor";
    $config["campo_id"]                 = "for_id";
    $config["csv_campos"]               = "for_id, for_nome, for_nome_contato";
    $config["tabela"]                   = "fornecedor";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
