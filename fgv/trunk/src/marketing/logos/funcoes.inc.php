<?
/* $Id: funcoes.inc.php,v 1.3 2002/04/16 12:55:31 binary Exp $ */

function limpa_logo(&$dados)
{
    $dados["id"]       = "";
    $dados["lgo_nome"] = "";
    $dados["lgo_desc"] = "";
    $dados["lgo_nome_falso"] = "";
    $dados["lgo_nome_real"] = "";
}

function carrega_logo($sql, &$dados)
{
    $rs = $sql->squery("
        SELECT
            lgo_id,
            lgo_nome,
            lgo_desc,
            lgo_nome_falso
        FROM
            logo
        WHERE
            lgo_id = '" . in_bd($dados["id"]) . "'");
    
    if (! is_array($rs))
        return false;

    $dados["id"]       = $rs["lgo_id"];
    $dados["lgo_nome"] = $rs["lgo_nome"];
    $dados["lgo_desc"] = $rs["lgo_desc"];
    $dados["lgo_nome_falso"] = $rs["lgo_nome_falso"];

    return true;
}

function insere_logo($sql, &$dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->squery("SELECT nextval('logo_lgo_id_seq')");
        if ($rs)
        {
            $dados["id"] = $rs["nextval"];

            $rs = $sql->query("
                INSERT INTO logo
                (
                    lgo_id,
                    lgo_nome,
                    lgo_desc
                )
                VALUES 
                (
                    '" . in_bd($dados["id"])   . "',
                    '" . in_bd($dados["lgo_nome"]) . "',
                    '" . in_bd($dados["lgo_desc"]) . "'
                )");

            if( isset( $_FILES[ 'lgo_arq' ][ 'tmp_name' ] ) && $_FILES[ 'lgo_arq' ][ 'tmp_name' ] != 'none' )
            {
                $dados[ 'lgo_nome_real' ] = 'lgo_' . $dados[ 'id'];
                $error_msgs = faz_upload( $sql, "lgo_id", $dados[ 'id' ], "lgo_arq", $dados[ 'lgo_nome_real' ], "lgo_nome_real", "lgo_nome_falso", "logo" );
                if( sizeof( $error_msgs ) )
                {
                    foreach( $error_msgs as $error )
                        print "<br /><font color='#ff0000'>" . $error . "</font>";
                    $sql->query( 'ROLLBACK TRANSACTION' ); 
                    return false;
                }
            }

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

function altera_logo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if ($rs)
    {
        $rs = $sql->query("
            UPDATE logo
            SET
                lgo_nome = '" . in_bd($dados["lgo_nome"]) . "',
                lgo_desc = '" . in_bd($dados["lgo_desc"]) . "'
            WHERE
                lgo_id = '"   . in_bd($dados["id"])   ."'");

        if( isset( $_FILES[ 'lgo_arq' ][ 'tmp_name' ] ) && $_FILES[ 'lgo_arq' ][ 'tmp_name' ] != 'none' )
        {
            $dados[ 'lgo_nome_real' ] = 'lgo_' . $dados[ 'id' ];
            $error_msgs = faz_upload( $sql, "lgo_id", $dados[ 'id' ], "lgo_arq", $dados[ 'lgo_nome_real' ], "lgo_nome_real", "lgo_nome_falso", "logo" );
            if( sizeof( $error_msgs ) )
            {
                foreach( $error_msgs as $error )
                    print "<br /><font color='#ff0000'>" . $error . "</font>";
                $sql->query( 'ROLLBACK TRANSACTION' ); 
                return false;
            }
        }

        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
   
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}

function apaga_logo($sql, $dados)
{
    $rs = $sql->query("BEGIN TRANSACTION");
    if( $rs )
    {
        $rs = $sql->squery( "
            SELECT
                lgo_nome_real
            FROM
                logo
            WHERE
                lgo_id = '" . in_bd( $dados[ 'id' ] ) . "'" );

        if( is_array( $rs ) )
            if( is_writable( UPLOAD_DIR . "/" . $rs[ 'lgo_nome_real' ] ) )
                unlink( UPLOAD_DIR . "/" . $rs[ 'lgo_nome_real' ] ); 
    
        $rs = $sql->query( "
            DELETE FROM logo
            WHERE
                lgo_id = '" . in_bd( $dados["id"] ) . "'" );
        
        if ($rs)
            return $sql->query("COMMIT TRANSACTION");
    }
      
    $sql->query("ROLLBACK TRANSACTION");
    return false;    
}


function valida_logo($dados)
{
    $error_msgs = array();

    if ($dados["lgo_nome"] == "")
        array_push($error_msgs, "É necessario preencher o nome do logo");

    return $error_msgs;
}

function busca_logo($sql, $busca)
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config["possiveis_campos"]["ID"]   = "lgo_id";
    $config["possiveis_campos"]["Nome"] = "lgo_nome";
    $config["possiveis_campos"]["Desc"] = "lgo_desc";

    $config["possiveis_ordens"]["Nome"] = "lgo_nome";

    $config["possiveis_quantidades"]    = array(10, 15, 20, 25, 30);

    $config["session_hash_name"]        = "logo";
    $config["campo_id"]                 = "lgo_id";
    $config["csv_campos"]               = "lgo_id, lgo_nome";
    $config["tabela"]                   = "logo";

/* ---------------------------------------------------------------- */

    return busca_G($sql, $config, $busca);
}

?>
