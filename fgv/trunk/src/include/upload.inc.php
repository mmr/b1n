<?
/* $Id: upload.inc.php,v 1.6 2002/05/07 14:00:28 binary Exp $ */
/* Funcoes pra Upload */

define( "UPLOAD_DIR", "arquivo" );

function faz_upload( $sql, $col_id, $id, $nome_input, $nome_real, $col_nome_real, $col_nome_falso, $tabela )
{
    /* Verificar se tenho as coisas no $_FILES */
    $error_msgs = array();

    clearstatcache();
    if( ! is_writable( UPLOAD_DIR ) )
        array_push( $error_msgs, "Não tem permissão de escrita no diretório de Upload" );

    if( $_FILES[ $nome_input ][ 'tmp_name' ] == 'none' )
        array_push( $error_msgs, "Arquivo inválido ou muito grande para fazer Upload" );

    if( sizeof( $error_msgs ) )
        return $error_msgs;   

    /* Grava no Sistema de Arquivos */
    if( ! copy( $_FILES[ $nome_input ][ 'tmp_name' ], UPLOAD_DIR . "/" . $nome_real ) )
        array_push( $error_msgs, "Erro inesperado! Não foi possível completar upload..." );

    if( sizeof( $error_msgs ) )
        return $error_msgs;

    $query = "
        UPDATE " . $tabela . "
        SET
            " . $col_nome_real  . " = '" . in_bd( $nome_real )  . "',
            " . $col_nome_falso . " = '" . in_bd( $_FILES[ $nome_input ][ 'name' ]  ) . "'
        WHERE " . $col_id . " = '" . in_bd( $id ) . "'";

    $rs = $sql->query($query);

    if( ! $rs )
        array_push( $error_msgs, "Erro inesperado! Não conseguiu gravar no banco de dados..." );

    return $error_msgs;
}

?>
