<? /* $Id: funcoes.inc.php,v 1.13 2002/07/31 20:55:57 binary Exp $ */ ?>

<?

function limpa_cliente(&$dados)
{
    $dados["cex_id"]                = "";
    $dados["ram_id"]                = "";
    $dados["reg_id"]                = "";
    $dados["id"]                    = "";
    $dados["cli_nome"]              = "";
    $dados["cli_razao"]             = "";
    $dados["cli_faturamento"]       = "";
    $dados["cli_endereco"]          = "";
    $dados["cli_bairro"]            = "";
    $dados["cli_cidade"]            = "";
    $dados["cli_estado"]            = "";
    $dados["cli_cep"]               = "";
    $dados["cli_nome_contato"]      = "";
    $dados["cli_celular_contato"]   = "";
    $dados["cli_ddd"]          = "";
    $dados["cli_ddi"]          = "";
    $dados["cli_telefone"]          = "";
    $dados["cli_ramal"]             = "";
    $dados["cli_email"]             = "";
    $dados["cli_homepage"]          = "";
    $dados["cli_conheceu_ej"]       = "";

    $dados["cli_cob_cnpj"]          = "";
    $dados["cli_cob_resp"]          = "";
    $dados["cli_cob_contato"]       = "";
    $dados["cli_cob_endereco"]      = "";
    $dados["cli_cob_cep"]           = "";
    $dados["cli_cob_ddd"]      = "";
    $dados["cli_cob_ddi"]      = "";
    $dados["cli_cob_telefone"]      = "";
    $dados["cli_cob_fax"]           = "";
}

function carrega_cliente($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            cex_id,
            ram_id,
            reg_id,
            cli_id,
            cli_nome,
            cli_razao,
            cli_faturamento,
            cli_endereco,
            cli_bairro,
            cli_cidade,
            cli_estado,
            cli_cep,
            cli_nome_contato,
            cli_celular_contato,
            cli_ddd,
            cli_ddi,
            cli_telefone,
            cli_fax,
            cli_ramal,
            cli_email,
            cli_homepage,
            cli_conheceu_ej,
            cli_cob_cnpj,
            cli_cob_resp,
            cli_cob_contato,
            cli_cob_endereco,
            cli_cob_cep,
            cli_cob_ddd,
            cli_cob_ddi,
            cli_cob_telefone,
            cli_cob_fax
        FROM
            cliente
        WHERE
            cli_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;


    $dados["cex_id"]                = $rs["cex_id"];
    $dados["ram_id"]                = $rs["ram_id"];
    $dados["reg_id"]                = $rs["reg_id"];
    $dados["id"]                    = $rs["cli_id"];
    $dados["cli_nome"]              = $rs["cli_nome"];
    $dados["cli_razao"]             = $rs["cli_razao"];
    $dados["cli_faturamento"]       = $rs["cli_faturamento"];
    $dados["cli_endereco"]          = $rs["cli_endereco"];
    $dados["cli_bairro"]            = $rs["cli_bairro"];
    $dados["cli_cidade"]            = $rs["cli_cidade"];
    $dados["cli_estado"]            = $rs["cli_estado"];
    $dados["cli_cep"]               = $rs["cli_cep"];
    $dados["cli_nome_contato"]      = $rs["cli_nome_contato"];
    $dados["cli_celular_contato"]   = $rs["cli_celular_contato"];
    $dados["cli_ddd"]          = $rs["cli_ddd"];
    $dados["cli_ddi"]          = $rs["cli_ddi"];
    $dados["cli_telefone"]          = $rs["cli_telefone"];
    $dados["cli_fax"]               = $rs["cli_fax"];
    $dados["cli_ramal"]             = $rs["cli_ramal"];
    $dados["cli_email"]             = $rs["cli_email"];
    $dados["cli_homepage"]          = $rs["cli_homepage"];
    $dados["cli_conheceu_ej"]       = $rs["cli_conheceu_ej"];

    $dados["cli_cob_cnpj"]          = $rs["cli_cob_cnpj"];
    $dados["cli_cob_resp"]          = $rs["cli_cob_resp"];
    $dados["cli_cob_contato"]       = $rs["cli_cob_contato"];
    $dados["cli_cob_endereco"]      = $rs["cli_cob_endereco"];
    $dados["cli_cob_cep"]           = $rs["cli_cob_cep"];
    $dados["cli_cob_ddd"]      = $rs["cli_cob_ddd"];
    $dados["cli_cob_ddi"]      = $rs["cli_cob_ddi"];
    $dados["cli_cob_telefone"]      = $rs["cli_cob_telefone"];
    $dados["cli_cob_fax"]           = $rs["cli_cob_fax"];

    return true;
}

function insere_cliente($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('cliente_cli_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            
            $query = " 
                INSERT INTO cliente
                (
                    cex_id,
                    ram_id,
                    reg_id,
                    cli_id,
                    cli_nome,
                    cli_razao,
                    cli_faturamento,
                    cli_endereco,
                    cli_bairro,
                    cli_cidade,
                    cli_estado,
                    cli_cep,
                    cli_nome_contato,
                    cli_celular_contato,
                    cli_ddd,
                    cli_ddi,
                    cli_telefone,
                    cli_fax,
                    cli_ramal,
                    cli_email,
                    cli_homepage,
                    cli_conheceu_ej,
                    cli_cob_cnpj,
                    cli_cob_resp,
                    cli_cob_contato,
                    cli_cob_endereco,
                    cli_cob_cep,
                    cli_cob_ddd,
                    cli_cob_ddi,
                    cli_cob_telefone,
                    cli_cob_fax
                )
                VALUES 
                (
                    '" . in_bd($dados["cex_id"])                . "',
                    '" . in_bd($dados["ram_id"])                . "',
                    '" . in_bd($dados["reg_id"])                . "',
                    '" . in_bd($dados["id"])                    . "',
                    '" . in_bd($dados["cli_nome"])              . "',
                    '" . in_bd($dados["cli_razao"])             . "',
                    '" . in_bd( reconhece_dinheiro( $dados[ "cli_faturamento" ] ) )       . "',
                    '" . in_bd($dados["cli_endereco"])          . "',
                    '" . in_bd($dados["cli_bairro"])            . "',
                    '" . in_bd($dados["cli_cidade"])            . "',
                    '" . in_bd($dados["cli_estado"])            . "',
                    '" . in_bd($dados["cli_cep"])               . "',
                    '" . in_bd($dados["cli_nome_contato"])      . "',
                    '" . in_bd($dados["cli_celular_contato"])   . "',
                    '" . in_bd($dados["cli_ddd"])          . "',
                    '" . in_bd($dados["cli_ddi"])          . "',
                    '" . in_bd($dados["cli_telefone"])          . "',
                    '" . in_bd($dados["cli_fax"])               . "',
                    '" . in_bd($dados["cli_ramal"])             . "',
                    '" . in_bd($dados["cli_email"])             . "',
                    '" . in_bd($dados["cli_homepage"])          . "',
                    '" . in_bd($dados["cli_conheceu_ej"])       . "',
                    '" . in_bd($dados["cli_cob_cnpj"])          . "',
                    '" . in_bd($dados["cli_cob_resp"])          . "',
                    '" . in_bd($dados["cli_cob_contato"])       . "',
                    '" . in_bd($dados["cli_cob_endereco"])      . "',
                    '" . in_bd($dados["cli_cob_cep"])           . "',
                    '" . in_bd($dados["cli_cob_ddd"])      . "',
                    '" . in_bd($dados["cli_cob_ddi"])      . "',
                    '" . in_bd($dados["cli_cob_telefone"])      . "',
                    '" . in_bd($dados["cli_cob_fax"])           . "'
                )";

            $rs = $sql->query($query);

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

function altera_cliente($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
            $query = "
            UPDATE cliente
            SET
                cex_id              = '" . in_bd($dados["cex_id"])              . "',
                ram_id              = '" . in_bd($dados["ram_id"])              . "',
                reg_id              = '" . in_bd($dados["reg_id"])              . "',
                cli_nome            = '" . in_bd($dados["cli_nome"])            . "',
                cli_razao           = '" . in_bd($dados["cli_razao"])           . "',
                cli_faturamento     = '" . in_bd( reconhece_dinheiro( $dados[ "cli_faturamento" ] ) )   . "',
                cli_endereco        = '" . in_bd($dados["cli_endereco"])        . "',
                cli_bairro          = '" . in_bd($dados["cli_bairro"])          . "',
                cli_cidade          = '" . in_bd($dados["cli_cidade"])          . "',
                cli_estado          = '" . in_bd($dados["cli_estado"])          . "',
                cli_cep             = '" . in_bd($dados["cli_cep"])             . "',
                cli_nome_contato    = '" . in_bd($dados["cli_nome_contato"])    . "',
                cli_celular_contato = '" . in_bd($dados["cli_celular_contato"]) . "',
                cli_ddd        = '" . in_bd($dados["cli_ddd"])        . "',
                cli_ddi        = '" . in_bd($dados["cli_ddi"])        . "',
                cli_telefone        = '" . in_bd($dados["cli_telefone"])        . "',
                cli_fax             = '" . in_bd($dados["cli_fax"])             . "',
                cli_ramal           = '" . in_bd($dados["cli_ramal"])           . "',
                cli_email           = '" . in_bd($dados["cli_email"])           . "',
                cli_homepage        = '" . in_bd($dados["cli_homepage"])        . "',
                cli_conheceu_ej     = '" . in_bd($dados["cli_conheceu_ej"])     . "',
                cli_cob_cnpj        = '" . in_bd($dados["cli_cob_cnpj"])        . "',
                cli_cob_resp        = '" . in_bd($dados["cli_cob_resp"])        . "',
                cli_cob_contato     = '" . in_bd($dados["cli_cob_contato"])     . "',
                cli_cob_endereco    = '" . in_bd($dados["cli_cob_endereco"])    . "',
                cli_cob_cep         = '" . in_bd($dados["cli_cob_cep"])         . "',
                cli_cob_ddd    = '" . in_bd($dados["cli_cob_ddd"])    . "',
                cli_cob_ddi    = '" . in_bd($dados["cli_cob_ddi"])    . "',
                cli_cob_telefone    = '" . in_bd($dados["cli_cob_telefone"])    . "',
                cli_cob_fax         = '" . in_bd($dados["cli_cob_fax"])         . "'
            WHERE
                cli_id = '"              . in_bd($dados["id"])                  ."'";

        $rs = $sql->query($query);;
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_cliente($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                cliente
            WHERE
                cli_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_cliente($dados)
{
    $error_msgs = array();

    if( $dados["cli_nome"] == "" )
        array_push($error_msgs, "É necessario preencher o nome do cliente");

    if( $dados["cli_nome_contato"] == "" )
        array_push($error_msgs, "É necessario preencher o nome do contato com o cliente");

    if( ! consis_inteiro( $dados["reg_id"] ) )
        array_push($error_msgs, "É necessario selecionar uma região para o cliente");

    if( ! consis_inteiro( $dados["ram_id"] ) )
        array_push($error_msgs, "É necessario selecionar uma ramo para o cliente");

    if( ! consis_inteiro( $dados["cex_id"] ) )
        array_push($error_msgs, "É necessario selecionar um cargo para o contato");

    if( ! consis_dinheiro( reconhece_dinheiro( $dados[ 'cli_faturamento' ] ) ) )
        array_push( $error_msgs, "Valor de faturamento inválido" );

    if( reconhece_dinheiro( strlen( $dados[ 'cli_faturamento' ] ) ) > 32 )
        array_push( $error_msgs, "Valor de faturamento muito grande" );

    if( ! consis_email($dados["cli_email"], 0))
        array_push($error_msgs, "Email inválido");
 
    if( ! consis_telefone($dados["cli_cep"], 0))
        array_push($error_msgs, "CEP inválido");

    if( ! consis_telefone($dados["cli_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if( ! consis_telefone($dados["cli_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if( ! consis_telefone($dados["cli_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if( ! consis_telefone($dados["cli_fax"], 0))
        array_push($error_msgs, "Fax inválido");

    if( ! consis_telefone($dados["cli_ramal"], 0))
        array_push($error_msgs, "Ramal inválido");

    /* Cobranca */
    if( ! consis_telefone($dados["cli_cob_cep"], 0))
        array_push($error_msgs, "CEP da cobrança inválido");

    if( ! consis_telefone($dados["cli_cob_ddi"], 0))
        array_push($error_msgs, "DDI da cobrança inválido");

    if( ! consis_telefone($dados["cli_cob_ddd"], 0))
        array_push($error_msgs, "DDD da cobrança inválido");

    if( ! consis_telefone($dados["cli_cob_telefone"], 0))
        array_push($error_msgs, "Telefone da cobrança inválido");

    if( ! consis_telefone($dados["cli_cob_fax"], 0))
        array_push($error_msgs, "Fax da cobrança inválido");

    return $error_msgs;
}

function busca_cliente($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "cli_id";
    $config["possiveis_campos"]["Nome"]         = "cli_nome";
    $config["possiveis_campos"]["Contato"]      = "cli_nome_contato";
    $config["possiveis_campos"]["Telefone"]     = "cli_telefone";
    $config["possiveis_campos"]["Fax"]          = "cli_fax";
    $config["possiveis_campos"]["Celular"]      = "cli_celular_contato";
    $config["possiveis_campos"]["Email"]        = "cli_email";
    $config["possiveis_campos"]["HomePage"]     = "cli_homepage";

    $config["possiveis_ordens"]["Nome"]         = "cli_nome";
    $config["possiveis_ordens"]["Contato"]      = "cli_nome_contato";

    $config["possiveis_quantidades"]            = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]                = "cliente";
    $config["campo_id"]                         = "cli_id";
    $config["csv_campos"]                       = "cli_id, cli_nome, cli_nome_contato";
    $config["tabela"]                           = "cliente";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}
?>
