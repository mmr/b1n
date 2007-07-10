<?

/* Gerando Arquivo Separado por ';' */
define( 'DELIMITADOR',  ';' );

extract_request_var( 'caras_ids',   $dados[ 'caras_ids' ] );
extract_request_var( 'campos',      $dados[ 'campos' ] );

if( $_SESSION[ 'busca' ][ 'etiqueta' ][ 'tipo_etiqueta' ] == '' || 
    ! is_array( $dados[ 'caras_ids' ] ) || 
    ! is_array( $dados[ 'campos' ] ) )
{
    ?>
    <script language='JavaScript'>
        history.go( -1 );
        window.alert( 'Algum dos dados necessários não foi passado, processo de criação de etiquetas abortado' );
    </script>
    <?
    exit;
}

$conteudo = '';
$s_campos = sizeof( $dados[ 'campos' ] );
$dados[ 'tipo_etiqueta' ] = $_SESSION[ 'busca' ][ 'etiqueta' ][ 'tipo_etiqueta' ];

$cabecalho = $dados[ 'campos' ][ 0 ];

for( $i = 1; $i < sizeof( $dados[ 'campos' ] ); $i++ )
    $cabecalho .= DELIMITADOR . $dados[ 'campos' ][ $i ];

$cabecalho .= "\r\n";

switch( $dados[ 'tipo_etiqueta' ] )
{
case 'aluno_gv':
    $query = "SELECT agv_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            aluno_gv
        WHERE
            agv_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR agv_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
case 'cliente':
    $query = "SELECT cli_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            cliente
        WHERE
            cli_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR cli_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
case 'membro':
    $query = "SELECT mem_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            membro_todos
        WHERE
            mem_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR mem_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
            {
                $conteudo .= DELIMITADOR . str_replace( DELIMITADOR, '#', $cara[ $dados[ 'campos' ][ $i ] ] );
            }

            $conteudo .= "\r\n";
        }
    }

    break;
case 'professor':
    $query = "SELECT prf_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            professor
        WHERE
            prf_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR prf_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
case 'fornecedor':
    $query = "SELECT for_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            fornecedor
        WHERE
            for_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR for_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
case 'patrocinador':
    $query = "SELECT pat_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            patrocinador
        WHERE
            pat_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR pat_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
case 'palestrante':
    $query = "SELECT pal_id";

    for( $i = 0; $i < $s_campos; $i++ )
        $query .= ", " . $dados[ 'campos' ][ $i ];

    $query .= "
        FROM
            palestrante
        WHERE
            pal_id IS NULL";

    foreach( $dados[ 'caras_ids' ] as $cara )
        $query .= " OR pal_id = '" . $cara . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $cara )
        {
            $conteudo .= $cara[ $dados[ 'campos' ][ 0 ] ];

            for( $i = 1; $i < $s_campos; $i++ )
                $conteudo .= DELIMITADOR . $cara[ $dados[ 'campos' ][ $i ] ];

            $conteudo .= "\r\n";
        }
    }

    break;
}

$arquivo = "etiqueta-" . $dados[ 'tipo_etiqueta' ] . "-" . date( 'Y-m-d' ) . ".txt";

if( $conteudo != '' )
{
    $conteudo = $cabecalho . $conteudo;
    header( "Content-Type: octet/stream" );
    header( "Content-Disposition: attachment; filename=" . $arquivo );
    print $conteudo;
    exit;
}
?>
<script language='JavaScript'>
    history.go( -1 );
    window.alert( 'Erro inesperado ao tentar criar etiqueta... Processo abortado' );
</script>
