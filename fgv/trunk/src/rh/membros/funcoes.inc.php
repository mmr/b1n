<?
/* $Id: funcoes.inc.php,v 1.11 2002/07/30 20:22:27 binary Exp $ */

function limpa_membro(&$dados)
{
    $dados["agv_id"]            = "";
    $dados["cgv_id"]            = "";
    $dados["id"]                = "";
    $dados["mem_login"]         = "";
    $dados["mem_senha"]         = "";
    $dados["mem_dt_entrada"]    = "";
    $dados["mem_dt_saida"]      = "";
    $dados["mem_apelido"]       = "";
    $dados["mem_cod_banco"]     = "";
    $dados["mem_ag_banco"]      = "";
    $dados["mem_cc_banco"]      = "";
}

function carrega_membro($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            agv_id,
            cgv_id,
            mem_id,
            mem_login,
            mem_senha,
            DATE_PART('day', mem_dt_entrada) AS mem_dt_entrada_d,
            DATE_PART('month', mem_dt_entrada) AS mem_dt_entrada_m,
            DATE_PART('year', mem_dt_entrada) AS mem_dt_entrada_a,
            DATE_PART('day', mem_dt_saida) AS mem_dt_saida_d,
            DATE_PART('month', mem_dt_saida) AS mem_dt_saida_m,
            DATE_PART('year', mem_dt_saida) AS mem_dt_saida_a,
            mem_apelido,
            mem_cod_banco,
            mem_ag_banco,
            mem_cc_banco
        FROM
            membro
        WHERE
            mem_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["agv_id"]        = $rs["agv_id"];
    $dados["cgv_id"]        = $rs["cgv_id"];
    $dados["id"]            = $rs["mem_id"];
    $dados["mem_login"]     = $rs["mem_login"];
    $dados["mem_senha"]     = descriptografa($rs["mem_senha"]);
    $dados["mem_dt_entrada"]= array("dia" => $rs["mem_dt_entrada_d"],
                                    "mes" => $rs["mem_dt_entrada_m"],
                                    "ano" => $rs["mem_dt_entrada_a"]);
    $dados["mem_dt_saida"]  = array("dia" => $rs["mem_dt_saida_d"],
                                    "mes" => $rs["mem_dt_saida_m"],
                                    "ano" => $rs["mem_dt_saida_a"]);
    $dados["mem_apelido"]   = $rs["mem_apelido"]; 
    $dados["mem_cod_banco"] = $rs["mem_cod_banco"];
    $dados["mem_ag_banco"]  = $rs["mem_ag_banco"];
    $dados["mem_cc_banco"]  = $rs["mem_cc_banco"];

    return true;
}

function insere_membro($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('membro_mem_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT INTO membro
                (
                    agv_id,
                    cgv_id,
                    mem_id,
                    mem_login,
                    mem_senha,
                    mem_dt_entrada,
                    mem_dt_saida,
                    mem_apelido,
                    mem_cod_banco,
                    mem_ag_banco,
                    mem_cc_banco
                )
                VALUES 
                (
                    '" . in_bd($dados["agv_id"])            . "',
                    '" . in_bd($dados["cgv_id"])            . "',
                    '" . in_bd($dados["id"])                . "',
                    '" . in_bd($dados["mem_login"])         . "',
                    '" . in_bd(criptografa($dados["mem_senha"])) . "',
                    '" . in_bd(hash_to_databd($dados["mem_dt_entrada"]))    . "',
                    " . hash_to_databd2($dados["mem_dt_saida"]) . ",
                    '" . in_bd($dados["mem_apelido"])       . "',
                    '" . in_bd($dados["mem_cod_banco"])     . "',
                    '" . in_bd($dados["mem_ag_banco"])      . "',
                    '" . in_bd($dados["mem_cc_banco"])      . "'
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

function altera_membro($sql, $dados)
{
    $muda_agv_id = "";
    $muda_senha = "";     

    if($dados["agv_id"] != "")
        $muda_agv_id = "agv_id = '" . in_bd($dados["agv_id"]) . "',";

    if($dados["mem_senha"] != "")
        $muda_senha = "mem_senha = '" . in_bd(criptografa($dados["mem_senha"])) . "',";

    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $query = "
            UPDATE
                membro
            SET
                " . $muda_agv_id . "
                " . $muda_senha  . "
                cgv_id          = '" . in_bd($dados["cgv_id"])          . "',
                mem_login       = '" . in_bd($dados["mem_login"])       . "',
                mem_dt_entrada  = '" . in_bd(hash_to_databd($dados["mem_dt_entrada"])) . "',
                mem_dt_saida    =  " . hash_to_databd2($dados["mem_dt_saida"]) . ",
                mem_apelido     = '" . in_bd($dados["mem_apelido"])     . "',
                mem_cod_banco   = '" . in_bd($dados["mem_cod_banco"])   . "',
                mem_ag_banco    = '" . in_bd($dados["mem_ag_banco"])    . "',
                mem_cc_banco    = '" . in_bd($dados["mem_cc_banco"])    . "'
            WHERE
                mem_id = '" . in_bd($dados["id"])   ."'";

        $rs = $sql->query($query);

        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }

    $sql->query("ROLLBACK TRANSACTION");
    return false;
}

function apaga_membro($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("
            SELECT
                COUNT(tsk_id) 
            FROM
                task
            WHERE
                mem_id_de = '" . $dados["id"] . "'
                OR mem_id_para = '" . $dados["id"] . "'");

        if($rs["count"] > 0)
        {
            $sql->query("ROLLBACK TRANSACTION");
            
            printf("Você não pode remover um usuário que possui tasks recebidas/enviadas.<br/> Delete as tasks antes.");
            return false;
        }

        $rs = $sql->query("
            DELETE FROM
                membro
            WHERE
                mem_id = '" . in_bd($dados["id"]) . "'");
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }

    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_membro($sql, $dados, $subpagina)
{
    $error_msgs = array();

    if($subpagina != "alterar")
    {
        if($dados["agv_id"] == "")
            array_push($error_msgs, "É necessario escolher o aluno que corresponde ao membro");

        if($dados["mem_senha"] == "")
            array_push($error_msgs, "É necessário o preenchimento da senha");
    }

    if( ! consis_inteiro( $dados[ 'cgv_id' ] ) )
        array_push( $error_msgs, "É necessário o preenchimento do cargo do membro" );

    if( $dados[ 'mem_login' ] == '' )
        array_push( $error_msgs, "É necessário o preenchimento do login do membro" );

    if(! consis_data($dados["mem_dt_entrada"]["dia"],
                      $dados["mem_dt_entrada"]["mes"],
                      $dados["mem_dt_entrada"]["ano"]))
        array_push($error_msgs, "Data de Entrada inválida");


    if($dados["mem_senha"] != $dados["mem_senha2"])
        array_push($error_msgs, "Confirmação e Senha tem de ser iguais");

    if(! consis_telefone($dados["mem_ag_banco"], 0))
        array_push($error_msgs, "Agência de banco inválida");

    if(! consis_telefone($dados["mem_cod_banco"], 0))
        array_push($error_msgs, "Código de banco inválido");

    if(! consis_telefone($dados["mem_cc_banco"], 0))
        array_push($error_msgs, "Número de conta corrente inválido");

    $rs = $sql->squery("
        SELECT
            mem_id
        FROM
            membro
        WHERE
            mem_login = '" . in_bd($dados["mem_login"]) . "'");

    if (is_array($rs) && $rs['mem_id'] != $dados["id"])
        array_push($error_msgs, "Já existe um membro com esse login cadastrado");

    return $error_msgs;
}

function busca_membro($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]     = "agv_id";
    $config["possiveis_campos"]["Login"]    = "mem_login";
    $config["possiveis_campos"]["Nome"]     = "mem_nome";
    $config["possiveis_campos"]["Telefone"] = "mem_telefone";
    $config["possiveis_campos"]["Email"]    = "mem_email";

    $config["possiveis_ordens"]["Login"]    = "mem_login";
    $config["possiveis_ordens"]["Nome"]     = "mem_nome";
    $config["possiveis_ordens"]["Email"]    = "mem_email";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "membro";
    $config["campo_id"]                 = "mem_id";
    $config["csv_campos"]               = "mem_id, mem_login, mem_nome, mem_email";
    $config["tabela"]                   = "membro_todos";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
