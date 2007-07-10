<?
/* $Id: funcoes.inc.php,v 1.7 2002/07/31 20:55:56 binary Exp $ */

function limpa_aluno_gv(&$dados)
{
    $dados["id"]                = "";
    $dados["agv_vivo"]          = "";
    $dados["agv_matricula"]     = "";
    $dados["agv_nome"]          = "";
    $dados["agv_endereco"]      = "";
    $dados["agv_bairro"]        = "";
    $dados["agv_ddi"]           = "";
    $dados["agv_ddd"]           = "";
    $dados["agv_telefone"]      = "";
    $dados["agv_ramal"]         = "";
    $dados["agv_cep"]           = "";
    $dados["agv_celular"]       = "";
    $dados["agv_rg"]            = "";
    $dados["agv_cpf"]           = "";
    $dados["agv_email"]         = "";
    $dados["agv_dt_nasci"]      = "";
    $dados["agv_dt_saida"]      = "";
}

function carrega_aluno_gv($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            agv_id,
            agv_vivo,
            agv_matricula,
            agv_nome,
            agv_rg,
            agv_cpf,
            agv_endereco,
            agv_bairro,
            agv_ddd,
            agv_ddi,
            agv_telefone,
            agv_ramal,
            agv_cep,
            agv_celular,
            agv_email,
            DATE_PART('day', agv_dt_nasci) AS agv_dt_nasci_d,
            DATE_PART('month', agv_dt_nasci) AS agv_dt_nasci_m,
            DATE_PART('year', agv_dt_nasci) AS agv_dt_nasci_a,
            DATE_PART('day', agv_dt_saida) AS agv_dt_saida_d,
            DATE_PART('month', agv_dt_saida) AS agv_dt_saida_m,
            DATE_PART('year', agv_dt_saida) AS agv_dt_saida_a
        FROM
            aluno_gv
        WHERE
            agv_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]            = $rs["agv_id"];
    $dados["agv_vivo"]      = $rs["agv_vivo"];
    $dados["agv_matricula"] = $rs["agv_matricula"];
    $dados["agv_nome"]      = $rs["agv_nome"];
    $dados["agv_rg"]        = $rs["agv_rg"];
    $dados["agv_cpf"]       = $rs["agv_cpf"];
    $dados["agv_endereco"]  = $rs["agv_endereco"];
    $dados["agv_bairro"]    = $rs["agv_bairro"];
    $dados["agv_ddd"]       = $rs["agv_ddd"];
    $dados["agv_ddi"]       = $rs["agv_ddi"];
    $dados["agv_telefone"]  = $rs["agv_telefone"];
    $dados["agv_ramal"]     = $rs["agv_ramal"];
    $dados["agv_cep"]       = $rs["agv_cep"];
    $dados["agv_celular"]   = $rs["agv_celular"];
    $dados["agv_email"]     = $rs["agv_email"];
    $dados["agv_dt_nasci"]  = array("dia" => $rs["agv_dt_nasci_d"],
                                    "mes" => $rs["agv_dt_nasci_m"],
                                    "ano" => $rs["agv_dt_nasci_a"]);
    $dados["agv_dt_saida"]  = array("dia" => $rs["agv_dt_saida_d"],
                                    "mes" => $rs["agv_dt_saida_m"],
                                    "ano" => $rs["agv_dt_saida_a"]);

    // pegando dados da matricula
    if( strlen( $dados[ 'agv_matricula' ] ) > 0 )
    {
        switch( substr( $dados[ 'agv_matricula' ], 0, 2 ) )
        {
        case "11":
            $dados[ 'agv_curso' ]  = "AE";
            $dados[ 'agv_classe' ] = "1";
            break;
        case "12":
            $dados[ 'agv_curso' ]  = "AE";
            $dados[ 'agv_classe' ] = "2";
            break;
        case "13":
            $dados[ 'agv_curso' ]  = "AE";
            $dados[ 'agv_classe' ] = "3";
            break;
        case "14":
            $dados[ 'agv_curso' ]  = "AP";
            $dados[ 'agv_classe' ] = "-";
            break;
        default:
            $dados[ 'agv_curso' ]  = "...";
            $dados[ 'agv_classe' ] = "...";
            break;
        }

        $dados[ 'agv_ano_entrada' ] = substr( $dados[ 'agv_matricula' ], 2, 2 );
        $dados[ 'agv_ano_entrada' ] += ( $dados[ 'agv_ano_entrada' ] > 40 ? 1900 : 2000 );

        $dados[ 'agv_semestre_entrada' ] = substr( $dados[ 'agv_matricula' ], 4, 1 );

        $dados[ 'agv_semestre_atual' ] = ( date( "Y" ) - $dados[ 'agv_ano_entrada' ] ) * 2;
        $dados[ 'agv_semestre_atual' ]++;

        if( $dados[ 'agv_semestre_entrada' ] == 2 )
            $dados[ 'agv_semestre_atual' ]--;

        if( date( "m" ) > 6 )
            $dados[ 'agv_semestre_atual' ]++;
    }

    return true;
}

function insere_aluno_gv($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('aluno_gv_agv_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT INTO aluno_gv
                (
                    agv_id,
                    agv_matricula,
                    agv_nome,
                    agv_rg,
                    agv_cpf,
                    agv_endereco,
                    agv_bairro,
                    agv_ddd,
                    agv_ddi,
                    agv_telefone,
                    agv_ramal,
                    agv_cep,
                    agv_celular,
                    agv_email,
                    agv_dt_nasci,
                    agv_dt_saida
                )
                VALUES 
                (
                    '". in_bd($dados["id"])             . "',
                    '" . in_bd($dados["agv_matricula"]) . "',
                    '" . in_bd($dados["agv_nome"])      . "',
                    '" . in_bd($dados["agv_rg"])        . "',
                    '" . in_bd($dados["agv_cpf"])       . "',
                    '" . in_bd($dados["agv_endereco"])  . "',
                    '" . in_bd($dados["agv_bairro"])    . "',
                    '" . in_bd($dados["agv_ddd"])       . "',
                    '" . in_bd($dados["agv_ddi"])       . "',
                    '" . in_bd($dados["agv_telefone"])  . "',
                    '" . in_bd($dados["agv_ramal"])     . "',
                    '" . in_bd($dados["agv_cep"])       . "',
                    '" . in_bd($dados["agv_celular"])   . "',
                    '" . in_bd($dados["agv_email"])     . "',
                    '" . in_bd(hash_to_databd($dados["agv_dt_nasci"])) . "',
                    "  . hash_to_databd2($dados["agv_dt_saida"]) . ")");
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

function altera_aluno_gv($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE
                aluno_gv
            SET
                agv_vivo        = '" . in_bd($dados["agv_vivo"])        . "',
                agv_matricula   = '" . in_bd($dados["agv_matricula"])   . "',
                agv_nome        = '" . in_bd($dados["agv_nome"])        . "',
                agv_rg          = '" . in_bd($dados["agv_rg"])          . "',
                agv_cpf         = '" . in_bd($dados["agv_cpf"])         . "',
                agv_endereco    = '" . in_bd($dados["agv_endereco"])    . "',
                agv_bairro      = '" . in_bd($dados["agv_bairro"])      . "',
                agv_ddd         = '" . in_bd($dados["agv_ddd"])         . "',
                agv_ddi         = '" . in_bd($dados["agv_ddi"])         . "',
                agv_telefone    = '" . in_bd($dados["agv_telefone"])    . "',
                agv_ramal       = '" . in_bd($dados["agv_ramal"])       . "',
                agv_cep         = '" . in_bd($dados["agv_cep"])         . "',
                agv_celular     = '" . in_bd($dados["agv_celular"])     . "',
                agv_email       = '" . in_bd($dados["agv_email"])       . "',
                agv_dt_nasci    = '" . in_bd(hash_to_databd($dados["agv_dt_nasci"])) . "',
                agv_dt_saida    =  " . hash_to_databd2($dados["agv_dt_saida"]) . "
            WHERE
                agv_id = '" . in_bd($dados["id"])   ."'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_aluno_gv($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            DELETE FROM
                aluno_gv
            WHERE
                agv_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_aluno_gv($dados)
{
    $error_msgs = array();

    if ($dados["agv_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do aluno");

    if( $dados[ 'agv_rg' ] == '' )
        array_push( $error_msgs, "É necessário o preenchimento do RG" );

    if(! consis_telefone($dados["agv_cpf"], 0))
        array_push($error_msgs, "CPF inválido");

    if( $dados["agv_matricula"] == "")
        array_push($error_msgs, "É necessario preencher o número de matrícula do aluno");

    if( ! consis_data($dados["agv_dt_nasci"]["dia"],
                      $dados["agv_dt_nasci"]["mes"],
                      $dados["agv_dt_nasci"]["ano"]))
        array_push($error_msgs, "Data de Nascimento inválida");

    if(! consis_email($dados["agv_email"], 0))
        array_push($error_msgs, "Email inválido");

    if(! consis_telefone($dados["agv_cep"], 0))
        array_push($error_msgs, "CEP inválido");

    if(! consis_telefone($dados["agv_ddi"], 0))
        array_push($error_msgs, "DDI inválido");

    if(! consis_telefone($dados["agv_ddd"], 0))
        array_push($error_msgs, "DDD inválido");

    if(! consis_telefone($dados["agv_telefone"], 0))
        array_push($error_msgs, "Telefone inválido");

    if(! consis_telefone($dados["agv_ramal"], 0))
        array_push($error_msgs, "Ramal inválido");
 
    if(! consis_telefone($dados["agv_celular"], 0))
        array_push($error_msgs, "Celular inválido");

    return $error_msgs;
}

function busca_aluno_gv($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "agv_id";
    $config["possiveis_campos"]["Nome"]     = "agv_nome";
    $config["possiveis_campos"]["Matricula"] = "agv_matricula";
    $config["possiveis_campos"]["Endereco"] = "agv_endereco";
    $config["possiveis_campos"]["Bairro"]   = "agv_bairro";
    $config["possiveis_campos"]["Telefone"] = "agv_telefone";
    $config["possiveis_campos"]["CEP"]      = "agv_cep";
    $config["possiveis_campos"]["Celular"]  = "agv_celular";
    $config["possiveis_campos"]["Email"]    = "agv_email";

    $config["possiveis_ordens"]["Nome"]     = "agv_nome";
    $config["possiveis_ordens"]["Matricula"] = "agv_matricula";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "aluno_gv";
    $config["campo_id"]                 = "agv_id";
    $config["csv_campos"]               = "agv_id, agv_nome, agv_matricula";
    $config["tabela"]                   = "aluno_gv";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
