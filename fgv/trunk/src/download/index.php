<?
extract_request_var( 'id', $dados[ 'id' ] );
extract_request_var( 'col_id', $dados[ 'col_id' ] );
extract_request_var( 'tabela', $dados[ 'tabela' ] );
extract_request_var( 'arq_col_r', $dados[ 'arq_col_r' ] );
extract_request_var( 'arq_col_f', $dados[ 'arq_col_f' ] );

if( $dados[ 'arq_col_r' ] == '' || 
    $dados[ 'arq_col_f' ] == '' ||
    $dados[ 'tabela' ] == ''    ||
    $dados[ 'col_id' ] == ''    ||
    $dados[ 'id' ] == '' )
{
    ?>
    <script language='JavaScript'>
        history.go( -1 );
        window.alert( 'Algum dos dados necessários não foi passado, processo de download abortado' );
    </script>
    <?
    exit;
}

$query = "
    SELECT
        " . $dados[ 'arq_col_r' ] . ",
        " . $dados[ 'arq_col_f' ] . "
    FROM
        " . $dados[ 'tabela' ] . "
    WHERE
        " . $dados[ 'col_id' ] . " = '" . $dados[ 'id' ] . "'";

$rs = $sql->squery( $query );

if( ! $rs )
{
    ?>
    <script language='JavaScript'>
        history.go( -1 );
        window.alert( 'Não conseguiu pegar dados do Banco de Dados...' );
    </script>
    <?
    exit;
}

$arq_f = $rs[ $dados[ 'arq_col_f' ] ];
$arq_r = UPLOAD_DIR . "/" . $rs[ $dados[ 'arq_col_r' ] ];

if( file_exists( $arq_r ) && is_readable( $arq_r ) )
{
    header( "Content-Type: octet/stream" );
    header( "Content-Disposition: attachment; filename=" . $arq_f );
    readfile( $arq_r );
    exit;
}
?>
<script language='JavaScript'>
    history.go( -1 );
    window.alert( 'Arquivo para Download não encontrado ou você não tem permissão para acessá-lo' );
</script>
