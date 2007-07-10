<? /* $Id: funcoes.inc.php,v 1.6 2002/07/31 20:55:56 binary Exp $ */ ?>

<?

function limpa_empresa_junior(&$dados)
{
    $dados["id"]                    = "";
    $dados["reg_id"]                = "";
    $dados["cex_id"]                = "";
    $dados["eju_nome"]              = "";
    $dados["eju_razao"]             = "";
    $dados["eju_endereco"]          = "";
    $dados["eju_bairro"]            = "";
    $dados["eju_cidade"]            = "";
    $dados["eju_estado"]            = "";
    $dados["eju_cep"]               = "";
    $dados["eju_nome_contato"]      = "";
    $dados["eju_celular_contato"]   = "";
    $dados["eju_ddd"]               = "";
    $dados["eju_ddi"]               = "";
    $dados["eju_telefone"]          = "";
    $dados["eju_ramal"]             = "";
    $dados["eju_fax"]               = "";
    $dados["eju_email"]             = "";
    $dados["eju_homepage"]          = "";
    $dados["eju_faculdade"]         = "";
    $dados["eju_rel_estreita"]      = "";
}

function carrega_empresa_junior($sql, &$dados)
{
    /*
    FKS:
    ram_id => ramo de atividade
    stc_id => status do contato
    cex_id => cargo do contato
    */
    
    $rs = $sql->squery("
        SELECT
            cex_id,
            reg_id,
            eju_id,
            eju_nome,
            eju_razao,
            eju_endereco,
            eju_bairro,
            eju_cidade,
            eju_estado,
            eju_cep,
            eju_nome_contato,
            eju_celular_contato,
            eju_ddd,
            eju_ddi,
            eju_telefone,
            eju_ramal,
            eju_fax,
            eju_email,
            eju_homepage,
            eju_faculdade,
            eju_rel_estreita
        FROM
            empresa_junior
        WHERE
            eju_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["cex_id"]                    = $rs["cex_id"];
    $dados["reg_id"]                    = $rs["reg_id"];
    $dados["id"]                        = $rs["eju_id"];
    $dados["eju_nome"]                  = $rs["eju_nome"];
    $dados["eju_razao"]                 = $rs["eju_razao"];
    $dados["eju_endereco"]              = $rs["eju_endereco"];
    $dados["eju_bairro"]                = $rs["eju_bairro"];
    $dados["eju_cidade"]                = $rs["eju_cidade"];
    $dados["eju_estado"]                = $rs["eju_estado"];
    $dados["eju_cep"]                   = $rs["eju_cep"];
    $dados["eju_nome_contato"]          = $rs["eju_nome_contato"];
    $dados["eju_celular_contato"]       = $rs["eju_celular_contato"];
    $dados["eju_ddd"]                   = $rs["eju_ddd"];
    $dados["eju_ddi"]                   = $rs["eju_ddi"];
    $dados["eju_telefone"]              = $rs["eju_telefone"];
    $dados["eju_ramal"]                 = $rs["eju_ramal"];
    $dados["eju_fax"]                   = $rs["eju_fax"];
    $dados["eju_email"]                 = $rs["eju_email"];
    $dados["eju_homepage"]              = $rs["eju_homepage"];
    $dados["eju_faculdade"]             = $rs["eju_faculdade"];
    $dados["eju_rel_estreita"]          = $rs["eju_rel_estreita"];

    return true;
}

function insere_empresa_junior($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('empresa_junior_eju_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            
            $rs = $sql->query("
                INSERT
                INTO empresa_junior
                (
                    cex_id,
                    reg_id,
                    eju_id,
                    eju_nome,
                    eju_razao,
                    eju_endereco,
                    eju_bairro,
                    eju_cidade,
                    eju_estado,
                    eju_cep,
                    eju_nome_contato,
                    eju_celular_contato,
                    eju_ddd,
                    eju_ddi,
                    eju_telefone,
                    eju_ramal,
                    eju_fax,
                    eju_email,
                    eju_homepage,
                    eju_faculdade,
                    eju_rel_estreita
                )
                VALUES 
                (
                    '" . in_bd($dados["cex_id"])                . "',
                    '" . in_bd($dados["reg_id"])                . "',
                    '" . in_bd($dados["id"])                    . "',
                    '" . in_bd($dados["eju_nome"])              . "',
                    '" . in_bd($dados["eju_razao"])             . "',
                    '" . in_bd($dados["eju_endereco"])          . "',
                    '" . in_bd($dados["eju_bairro"])            . "',
                    '" . in_bd($dados["eju_cidade"])            . "',
                    '" . in_bd($dados["eju_estado"])            . "',
                    '" . in_bd($dados["eju_cep"])               . "',
                    '" . in_bd($dados["eju_nome_contato"])      . "',
                    '" . in_bd($dados["eju_celular_contato"])   . "',
                    '" . in_bd($dados["eju_ddd"])          . "',
                    '" . in_bd($dados["eju_ddi"])          . "',
                    '" . in_bd($dados["eju_telefone"])          . "',
                    '" . in_bd($dados["eju_ramal"])             . "',
                    '" . in_bd($dados["eju_fax"])               . "',
                    '" . in_bd($dados["eju_email"])             . "',
                    '" . in_bd($dados["eju_homepage"])          . "',
                    '" . in_bd($dados["eju_faculdade"])         . "',
                    '" . in_bd($dados["eju_rel_estreita"])      . "'
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

function altera_empresa_junior($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
            $query = "
            UPDATE
                empresa_junior
            SET
                cex_id = '"             . in_bd($dados["cex_id"])               . "',
                reg_id = '"             . in_bd($dados["reg_id"])               . "',
                eju_id = '"             . in_bd($dados["id"])                   . "',
                eju_nome = '"           . in_bd($dados["eju_nome"])             . "',
                eju_razao = '"          . in_bd($dados["eju_razao"])            . "',
                eju_endereco = '"       . in_bd($dados["eju_endereco"])         . "',
                eju_bairro = '"         . in_bd($dados["eju_bairro"])           . "',
                eju_cidade = '"         . in_bd($dados["eju_cidade"])           . "',
                eju_estado = '"         . in_bd($dados["eju_estado"])           . "',
                eju_cep = '"            . in_bd($dados["eju_cep"])              . "',
                eju_nome_contato = '"   . in_bd($dados["eju_nome_contato"])     . "',
                eju_celular_contato = '". in_bd($dados["eju_celular_contato"])  . "',
                eju_ddd = '"       . in_bd($dados["eju_ddd"])         . "',
                eju_ddi = '"       . in_bd($dados["eju_ddi"])         . "',
                eju_telefone = '"       . in_bd($dados["eju_telefone"])         . "',
                eju_ramal = '"          . in_bd($dados["eju_ramal"])            . "',
                eju_fax = '"            . in_bd($dados["eju_fax"])              . "',
                eju_email = '"          . in_bd($dados["eju_email"])            . "',
                eju_homepage = '"       . in_bd($dados["eju_homepage"])         . "',
                eju_faculdade = '"      . in_bd($dados["eju_faculdade"])        . "',
                eju_rel_estreita = '"   . in_bd($dados["eju_rel_estreita"])     . "'
            WHERE
                eju_id = '"             . in_bd($dados["id"])                   ."'";

        $rs = $sql->query($query);;
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_empresa_junior($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                empresa_junior
            WHERE
                eju_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_empresa_junior($dados)
{
    $error_msgs = array();

    if ($dados["eju_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome da Empresa Júnior");

    if ($dados["eju_nome_contato"] == "")
        array_push($error_msgs, "É necessario preencher o nome do contato com o Empresa Júnior");

    if ($dados["cex_id"] == "")
        array_push($error_msgs, "É necessario selecionar um cargo para o contato");

    if(! consis_email($dados["eju_email"], 0))
        array_push($error_msgs, "Email inválido");

    if(! consis_inteiro($dados["reg_id"]) )
        array_push($error_msgs, "Você precisa escolher uma região");

    if(! consis_telefone($dados["eju_cep"], 0))
        array_push($error_msgs, "CEP inválido");

    if(! consis_telefone($dados["eju_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if(! consis_telefone($dados["eju_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if(! consis_telefone($dados["eju_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if(! consis_telefone($dados["eju_ramal"], 0))
        array_push($error_msgs, "Ramal inválido");
 
    if(! consis_telefone($dados["eju_celular_contato"], 0))
        array_push($error_msgs, "Celular inválido");


    return $error_msgs;
}

function busca_empresa_junior($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "eju_id";
    $config["possiveis_campos"]["Nome"]         = "eju_nome";
    $config["possiveis_campos"]["Contato"]      = "eju_nome_contato";
    $config["possiveis_campos"]["Telefone"]     = "eju_telefone";
    $config["possiveis_campos"]["Fax"]          = "eju_fax";
    $config["possiveis_campos"]["Celular"]      = "eju_celular";
    $config["possiveis_campos"]["Email"]        = "eju_email";
    $config["possiveis_campos"]["HomePage"]     = "eju_homepage";
    $config["possiveis_campos"]["Cometários"]   = "eju_texto";

    $config["possiveis_ordens"]["Nome"]         = "eju_nome";
    $config["possiveis_ordens"]["Contato"]      = "eju_nome_contato";

    $config["possiveis_quantidades"]            = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]                = "empresa_junior";
    $config["campo_id"]                         = "eju_id";
    $config["csv_campos"]                       = "eju_id, eju_nome, eju_nome_contato";
    $config["tabela"]                           = "empresa_junior";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
