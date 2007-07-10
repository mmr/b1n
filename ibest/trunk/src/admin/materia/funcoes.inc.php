<?
/* $Id: funcoes.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function limpar( &$dados )
{
    $dados[ 'mat_id_mae' ]      = '';
    $dados[ 'mat_titulo' ]      = '';
    $dados[ 'mat_olho' ]        = '';
    $dados[ 'mat_modo' ]        = '';
    $dados[ 'mat_texto' ]       = '';
    $dados[ 'mat_pal_chave' ]   = '';
    $dados[ 'mat_fonte' ]       = '';
    $dados[ 'mat_status' ]      = '';
}

function carregar( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            mat_id,
            mat_id_mae,
            mat_titulo,
            mat_modo,
            mat_olho,
            mat_texto,
            mat_pal_chave,
            mat_fonte,
            mat_status
        FROM
            materia
        WHERE
            mat_id = '" . in_bd( $dados[ 'mat_id' ] ) . "'" );
    
    if( is_array( $rs ) )
    {
        $dados[ 'mat_id' ]          = $rs[ 'mat_id' ];
        $dados[ 'mat_id_mae' ]      = $rs[ 'mat_id_mae' ];
        $dados[ 'mat_titulo' ]      = $rs[ 'mat_titulo' ];
        $dados[ 'mat_olho' ]        = $rs[ 'mat_olho' ];
        $dados[ 'mat_modo' ]        = $rs[ 'mat_modo' ];
        $dados[ 'mat_texto' ]       = $rs[ 'mat_texto' ];
        $dados[ 'mat_pal_chave' ]   = $rs[ 'mat_pal_chave' ];
        $dados[ 'mat_fonte' ]       = $rs[ 'mat_fonte' ];
        $dados[ 'mat_status' ]      = $rs[ 'mat_status' ];
        return true;
    }

    return false;
}

function carregar_destaque( $sql, &$dados )
{
    $dados[ 'destaques' ] = array( ); 

    /* pegando posicoes destacadas */
    $rs = $sql->query("
        SELECT
            des_id
        FROM
            mat_des
        WHERE
            mat_id = '" . in_bd( $dados[ "mat_id" ] ) . "'" );

    if (is_array($rs))
    {
        foreach ($rs as $item)
            array_push($dados["destaques"], $item["des_id"]);

        $rs = $sql->squery("
            SELECT
                mat_des_texto,
                mat_des_imagem,
                mat_des_dt_ent,
                mat_des_arq_f
            FROM
                materia
            WHERE
                mat_id = '" . in_bd( $dados[ 'mat_id' ] ) . "'" );

        if( $rs )
        {
            $dados[ 'mat_des_texto' ]   = $rs[ 'mat_des_texto' ];
            $dados[ 'mat_des_imagem' ]  = $rs[ 'mat_des_imagem' ];
            $dados[ 'mat_des_dt_ent' ]  = databd_to_hash( $rs[ 'mat_des_dt_ent' ] );
            $dados[ 'mat_des_arq_f' ]   = $rs[ 'mat_des_arq_f' ];
        }
    }

    return true;
}

function inserir( $sql, &$dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $rs = $sql->squery( "SELECT nextval( 'materia_mat_id_seq' )" );
        if( $rs )
        {
            $dados[ "mat_id" ] = $rs[ "nextval" ];

            if( $dados[ 'mat_modo' ] == 1 )
                $dados[ 'mat_texto' ] = htmlspecialchars( $dados[ 'mat_texto' ] );

            $rs = $sql->query( "
                INSERT INTO materia
                (
                    mat_id,
                    mat_titulo,
                    mat_olho,
                    mat_modo,
                    mat_texto,
                    mat_pal_chave,
                    mat_fonte,
                    mat_status
                )
                VALUES 
                (
                    '" . in_bd( $dados[ 'mat_id' ] )        . "',
                    '" . in_bd( $dados[ 'mat_titulo' ] )    . "',
                    '" . in_bd( $dados[ 'mat_olho' ] )      . "',
                    '" . in_bd( $dados[ 'mat_modo' ] )      . "',
                    '" . in_bd( $dados[ 'mat_texto' ] )     . "',
                    '" . in_bd( $dados[ 'mat_pal_chave' ] ) . "',
                    '" . in_bd( $dados[ 'mat_fonte' ] )     . "',
                    '" . in_bd( $dados[ 'mat_status' ] )    . "'
                )" );

            if( $rs )
            {
                if( consis_inteiro( $dados[ 'mat_id_mae' ] ) )
                {
                    $rs = $sql->query( "
                        UPDATE materia
                        SET
                            mat_id_mae = '"     . in_bd( $dados[ 'mat_id_mae' ] )       . "'
                        WHERE
                            mat_id = '"       . in_bd( $dados[ 'mat_id' ] )           . "'" );

                    if( !$rs )
                        return false;
                }
                $sql->query( "COMMIT TRANSACTION" );
                return true;
            }
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function alterar( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {

        if( $dados[ 'mat_modo' ] == 1 )
            $dados[ 'mat_texto' ] = htmlspecialchars( $dados[ 'mat_texto' ] );


        $query = "
            UPDATE materia
            SET
                mat_titulo = '"     . in_bd( $dados[ 'mat_titulo' ] )       . "',
                mat_olho = '"       . in_bd( $dados[ 'mat_olho' ] )         . "',
                mat_texto = '"      . in_bd( $dados[ 'mat_texto' ] )        . "',
                mat_modo  = '"      . in_bd( $dados[ 'mat_modo' ] )         . "',
                mat_pal_chave = '"  . in_bd( $dados[ 'mat_pal_chave' ] )    . "',
                mat_fonte = '"      . in_bd( $dados[ 'mat_fonte' ] )        . "',
                mat_status = '"      . in_bd( $dados[ 'mat_status' ] )      . "'
            WHERE
                mat_id = '"       . in_bd( $dados[ 'mat_id' ] )             . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function apagar( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM materia WHERE mat_id = ''";

        foreach( $dados[ 'caras_ids' ] as $mat_id )
            $query .= " OR mat_id = '" . in_bd( $mat_id ) . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
      
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function validar( $dados )
{
    $error_msgs = array( );

    if( $dados[ "mat_titulo" ] == "" )
        array_push( $error_msgs, "É necessário preencher o título da matéria" );

    return $error_msgs;
}

function validar_destaque( $dados )
{
    $error_msgs = array( );

    if( ! sizeof( $dados[ 'destaques' ] ) )
        array_push( $error_msgs, "É necessário escolher ao menos uma posição de destaque" );

/*
    if( $dados[ "mat_des_texto" ] == "" )
        array_push( $error_msgs, "É necessário preencher o campo de destaque" );
*/

    if( ! consis_data( $dados[ 'mat_des_dt_ent' ][ 'dia' ],
                       $dados[ 'mat_des_dt_ent' ][ 'mes' ],
                       $dados[ 'mat_des_dt_ent' ][ 'ano' ] ) )
        array_push( $error_msgs, "Data de entrada de destaque inválida" );

    return $error_msgs;
}

function destacar( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "
            UPDATE materia
            SET
                mat_des_texto = '"  . in_bd( $dados[ 'mat_des_texto' ] )    . "',
                mat_des_imagem = '" . in_bd( $dados[ 'mat_des_imagem' ] )   . "',
                mat_des_dt_ent = '" . in_bd( hash_to_databd( $dados[ 'mat_des_dt_ent' ] ) ) . "'
            WHERE
                mat_id = '"         . in_bd( $dados[ 'mat_id' ] )             . "'";

        $rs = $sql->query( $query );

        if( $rs )
        {
            $rs = $sql->query( "
                DELETE FROM mat_des
                WHERE
                    mat_id = '" . $dados[ 'mat_id' ] . "'" );
            if( $rs )
            {
                if (is_array($dados["destaques"]))
                {
                    foreach ($dados["destaques"] as $des_id)
                    {
                        $rs = $rs && $sql->query("
                            INSERT INTO mat_des
                            (
                                mat_id,
                                des_id
                            )
                            VALUES
                            (
                                '".in_bd($dados["mat_id"])."',
                                '".in_bd($des_id)."'
                            )");

                        if (!$rs)
                        {
                            $sql->query("ROLLBACK TRANSACTION");
                            return false;
                        }
                    }
                }
            }

            return $sql->query( "COMMIT TRANSACTION" );
        }
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}


function buscar( $sql, $busca )
{

/* ---------------- Configuracoes de busca ---------------------- */

//    $config[ "possiveis_campos" ][ "ID" ]   = "stc_id";
    $config[ "possiveis_campos" ][ "Título" ]   = "mat_titulo";
    $config[ "possiveis_campos" ][ "Olho" ]     = "mat_olho";
    $config[ "possiveis_campos" ][ "Texto" ]    = "mat_texto";
    $config[ "possiveis_campos" ][ "Palavra Chave" ]    = "mat_pal_chave";
    $config[ "possiveis_campos" ][ "Fonte" ]    = "mat_fonte";
    $config[ "possiveis_campos" ][ "Status" ]   = "mat_status";

    $config[ "possiveis_ordens" ][ "Título" ]   = "mat_titulo";
    $config[ "possiveis_ordens" ][ "Olho" ]     = "mat_olho";
    $config[ "possiveis_ordens" ][ "Fonte" ]    = "mat_fonte";
    $config[ "possiveis_ordens" ][ "Status" ]   = "mat_status";

    $config[ "possiveis_quantidades" ]    = array( 10, 15, 20, 25, 30 );

    $config[ "session_hash_name" ]        = "materia";
    $config[ "campo_id" ]                 = "mat_id";
    $config[ "csv_campos" ]               = "mat_id, mat_titulo, mat_olho, mat_fonte, mat_status";
    $config[ "tabela" ]                   = "materia";

/* ---------------------------------------------------------------- */

    return busca_G( $sql, $config, $busca );
}

?>
