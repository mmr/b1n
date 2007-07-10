<?
/* $Id: funcoes.inc.php,v 1.4 2002/04/30 20:17:03 binary Exp $ */

function limpa_ferramenta(&$dados)
{
    $dados["id"]       = "";
    $dados["are_id"]   = "";
    $dados["frm_nome"] = "";
    $dados["frm_desc"] = "";
}

function carrega_ferramenta($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            are_id,
            frm_id,
            frm_nome,
            frm_desc,
            frm_arq_falso
        FROM
            ferramenta
        WHERE
            frm_id = '" . in_bd($dados["id"]) . "'");
    
    if(! is_array($rs))
        return false;

    $dados["are_id"]   = $rs["are_id"];
    $dados["id"]       = $rs["frm_id"];
    $dados["frm_nome"] = $rs["frm_nome"];
    $dados["frm_desc"] = $rs["frm_desc"];
    $dados["frm_arq_falso"] = $rs["frm_arq_falso"];

    return true;
}

function insere_ferramenta( $sql, &$dados, &$error_msgs )
{
    $error_msgs = array( );

    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $rs = $sql->squery("SELECT nextval('ferramenta_frm_id_seq')");
        if($rs)
        {
            $dados["id"] = $rs["nextval"];
            $rs = $sql->query("
                INSERT
                INTO ferramenta
                (
                    frm_id,
                    are_id,
                    frm_nome,
                    frm_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])       . "',
                    '" . in_bd($dados["are_id"])   . "',
                    '" . in_bd($dados["frm_nome"]) . "',
                    '" . in_bd($dados["frm_desc"]) . "'
                )");

            if($rs)
            {
                if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
                {
                    $dados[ 'frm_arq_real' ] = 'frm_' . $dados[ 'id' ];
                    if( $_FILES[ 'arq' ][ 'name' ] == '' )
                    {
                        array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
                        return false;
                    }
                    else
                    {
                        $error_msgs = faz_upload( $sql, 'frm_id', $dados[ 'id' ], 'arq', $dados[ 'frm_arq_real' ], 'frm_arq_real', 'frm_arq_falso', 'ferramenta' );
                        if( sizeof( $error_msgs ) )
                            return false;
                    }
                }

                return $sql->query("COMMIT TRANSACTION");
            }
        }
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function altera_ferramenta($sql, $dados, &$error_msgs)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        $rs = $sql->query("
            UPDATE
                ferramenta
            SET
                are_id   = '" . in_bd($dados["are_id"])   . "',
                frm_nome = '" . in_bd($dados["frm_nome"]) . "',
                frm_desc = '" . in_bd($dados["frm_desc"]) . "'
            WHERE
                frm_id = '"   . in_bd($dados["id"])   ."'");

        if($rs)
        {
            if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
            {
                $dados[ 'frm_arq_real' ] = 'frm_' . $dados[ 'id' ];
                if( $_FILES[ 'arq' ][ 'name' ] == '' )
                {
                    array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
                    return false;
                }
                else
                {
                    $error_msgs = faz_upload( $sql, 'frm_id', $dados[ 'id' ], 'arq', $dados[ 'frm_arq_real' ], 'frm_arq_real', 'frm_arq_falso', 'ferramenta' );
                    if( sizeof( $error_msgs ) )
                        return false;
                }
            }
            return $sql->query("COMMIT TRANSACTION");
        }
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;
}

function apaga_ferramenta($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if($rs)
    {
        /* apagando arquivo */
        $rs = $sql->squery( "
            SELECT
                frm_arq_real
            FROM
                ferramenta
            WHERE
                frm_id = '" . in_bd( $dados[ 'id' ] ) . "'" );

        if( is_array( $rs ) )
            if( is_writable( UPLOAD_DIR . "/" . $rs[ 'frm_arq_real' ] ) )
                unlink( UPLOAD_DIR . "/" . $rs[ 'frm_arq_real' ] );

        $rs = $sql->query("
            DELETE FROM
                ferramenta
            WHERE
                frm_id = '" . in_bd($dados["id"]) . "'");

        if($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_ferramenta($dados)
{
    $error_msgs = array();

    if($dados["frm_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome da ferramenta");

    if($dados["are_id"] == "")
        array_push($error_msgs, "É necessario escolher uma área para a ferramenta");

    return $error_msgs;
}

function busca_ferramenta($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "frm_id";
    $config["possiveis_campos"]["Nome"] = "frm_nome";
    $config["possiveis_campos"]["Desc"] = "frm_desc";

    $config["possiveis_ordens"]["Nome"] = "frm_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "ferramenta";
    $config["campo_id"]                 = "frm_id";
    $config["csv_campos"]               = "frm_id, frm_nome";
    $config["tabela"]                   = "ferramenta";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
