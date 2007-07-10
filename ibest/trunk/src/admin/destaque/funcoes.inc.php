<?
/* $Id: funcoes.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function carregar( $sql, &$dados )
{
    $dados[ 'materias' ][ 'mat_id' ] = array( ); 
    $dados[ 'materias' ][ 'mat_titulo' ] = array( ); 

    /* pegando dados desse destaque */
    $rs = $sql->squery( "
        SELECT
            des_nome
        FROM
            destaque
        WHERE
            des_id = '" . $dados[ 'des_id' ] . "'" );

    if( $rs )
    {
        $dados[ 'des_nome' ] = $rs[ 'des_nome' ];

        /* pegando posicoes destacadas */
        $rs = $sql->query( "
            SELECT
                mat_id,
                mat_titulo
            FROM
                mat_des
                NATURAL JOIN materia
            WHERE
                des_id = '" . in_bd( $dados[ 'des_id' ] ) . "'" );

        if (is_array( $rs ) )
        {
            foreach( $rs as $item )
            {
                array_push( $dados[ "materias" ][ "mat_id" ],      $item[ "mat_id" ] );
                array_push( $dados[ "materias" ][ "mat_titulo" ],  $item[ "mat_titulo" ] );
            }
        }

        return true;
    }

    return false;
}

function alterar( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
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

function buscar( $sql, $busca )
{

/* ---------------- Configuracoes de busca ---------------------- */

    $config[ "possiveis_campos" ][ "Nome" ]   = "des_nome";

    $config[ "possiveis_ordens" ][ "Nome" ]   = "des_nome";

    $config[ "possiveis_quantidades" ]    = array( 10, 15, 20, 25, 30 );

    $config[ "session_hash_name" ]        = "destaque";
    $config[ "campo_id" ]                 = "des_id";
    $config[ "csv_campos" ]               = "des_id, des_nome";
    $config[ "tabela" ]                   = "destaque";

/* ---------------------------------------------------------------- */

    return busca_G( $sql, $config, $busca );
}

?>
