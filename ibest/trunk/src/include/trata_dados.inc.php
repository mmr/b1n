<?
/* $Id: trata_dados.inc.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

function extract_request_var( $nome, &$destino, $default="" )
{
    if( $destino == "" )
        $destino = $default;

    $resp = isset( $_REQUEST[ $nome ] );
    if ( $resp )
        $destino = $_REQUEST[ $nome ];

    return $resp;
}

function check_chars( $string, $valid_ch )
{
    if ( !$valid_ch ) 
        echo "Warning: incorrect parameters at chack_chars<br>";
    for ( $i=0; $i<256; $i++ )
        $char_table[ $i ]=0;
    for ( $i=0; $i<sizeof( $valid_ch ); $i++ ) 
        $char_table[ ord( $valid_ch[ $i ] ) ]=1;
    for ( $i=0; $i<strlen( $string ); $i++ )
    {
        $c = substr( $string,$i,1 );
        if ( $char_table[ ord( $c ) ] == 0 )
            return false;
    }
    return true;
}


function limpa_chars( $string, $limpa_ch )
{
    for ( $i=0; $i<sizeof( $limpa_ch ); $i++ )
	$string = str_replace( $limpa_ch[ $i ],"",$string );
    return $string;
}

// recebe hash com dia, mes e ano
// retorna data no formato do banco de dados ( aaaa-mm-dd ) 
//function hash_to_databd( $hash_data ){
//    return( $hash_data[ "ano" ]."-".$hash_data[ "mes" ]."-".$hash_data[ "dia" ] );
//}

function hash_to_databd( $hash_data )
{
    return( $hash_data[ "ano" ]."-".$hash_data[ "mes" ]."-".$hash_data[ "dia" ] );
}

function hash_to_databd2( $hash_data )
{
    if( $hash_data[ "ano" ] == 0 || $hash_data[ "mes" ] == 0 && $hash_data[ "dia" ] == 0 )
        return "NULL";
    else
        return( "'".$hash_data[ "ano" ]."-".$hash_data[ "mes" ]."-".$hash_data[ "dia" ]."'" );
}

function databd_to_hash( $databd )
{
    if ( ! $databd )
        return array( );

    $hash[ "dia" ] = fmta_databd( "d", $databd );
    $hash[ "mes" ] = fmta_databd( "m", $databd );
    $hash[ "ano" ] = fmta_databd( "Y", $databd );

    return $hash  ;
}

function fmta_databd($formato,$data){
    $data = databd_to_ts($data);
    return date($formato,$data);
}

function ts_to_databd($ts) {
    return date("Y-m-d",$ts);
}

function databd_to_ts($data) {
    $data = split("-",$data);
    return mktime(0,0,0,$data[1],$data[2],$data[0]);
}



#--------------------------------------------------------

// para inserir valores no bd
function in_bd( $var )
{
    if( is_null( $var ) )
        return "";
    return addslashes( trim( $var ) );
}

// para inserir valores na pagina
function in_html( $var )
{
    return nl2br( htmlspecialchars( $var, ENT_QUOTES ) );
}


#--------------------------------------------------------

function reconhece_dinheiro( $x )
{
    $decc = $decp = 0;
    $c_count = substr_count( $x,',' );
    $p_count = substr_count( $x,'.' );
    $c_pos   = strlen( strrchr( $x,',' ) );
    $p_pos   = strlen( strrchr( $x,'.' ) );

    // primeiro verifica se nao tem bobeira no meio.
    if( !ereg( "^[ 0-9\.\, ]*$",$x ) )
        return null;

    //mais de um separador decimal
    if( ( $c_count>1 ) && ( $p_count>1 ) )
        return null;

    if ( ( $c_count==1 ) && ( $p_count==1 ) )
        $c_pos < $p_pos ?  $decc = 1 : $decp = 1; 
    elseif ( $c_count==1 )
        $decc = 1;
    elseif ( $p_count==1 )
        $decp = 1;

    if( $decp )
        $x = str_replace( ',','',$x );
    elseif( $decc )
    {
        $x = str_replace( '.','',$x );
        $x = str_replace( ',','.',$x );
    }
    else
    {
        $x = str_replace( '.','',$x );
        $x = str_replace( ',','',$x );
    }

    return ( float )$x;
}



function formata_dinheiro( $din, $simbolo=0 )
{
    $simbolo ? $simbolo = "R\$ " : $simbolo = "";

    $str = $simbolo . strtr( sprintf( "%.2f",$din ), ",.", ".," );
    return $str;
}



#--------------------------------------------------------

// checagem de data ( considerando que a data vai ser recebida sempre com select )
function consis_data( $dia, $mes, $ano )
{
    return checkdate( $mes, $dia, $ano );
}

function consis_boleano( $var )
{
    if ( is_numeric( $var ) && ( $var == 0 || $var == 1 ) )
        return true;
    else
        return is_bool( $var );
}

function consis_inteiro( $var )
{
    if ( is_numeric( $var ) )
        return is_int( $var+0 );

    return false;
}

function consis_natural( $var )
{
    if ( consis_inteiro( $var ) and $var >= 0 )
        return true;
    return false;
}

function consis_dinheiro( $var )
{
    return ( is_numeric( $var ) and $var >= 0.0 );
}

// numero entre 0.0 e 100
function consis_porcentagem( $var )
{
    return ( is_numeric( $var ) and $var >= 0.0 and $var <= 100.0 );
}

function consis_telefone( $var, $obrigatorio = 1 )
{
    if( !$obrigatorio && !$var )
        return true;
    elseif( !$var )
        return false;

    $vc = array( "0","1","2","3","4","5","6","7","8","9","-" );
    return check_chars( $var,$vc );
}

function consis_email( $str, $obrigatorio = 1 )
{
    if( !$obrigatorio && !$str )
        return true;
    elseif( !$str )
        return false;

    if ( !ereg( "^[ a-zA-Z0-9_- ]+\@[ a-zA-Z0-9_- ]+( \.[ a-zA-Z0-9_- ]+ ){1,2}$",$str ) ) 
        return false;

    return true;
}

/* funcao para aplicar trim recusivamente */
function trim_r( $list, $max_depth = 0, $depth = 0 )
{
    if ( !is_array( $list ) ) return trim( $list );

    if ( ( $max_depth == 0 ) || ( $depth < $max_depth ) )
    {
	foreach ( $list as $key => $member )
	    $list[ $key ] = trim_r( $member, $max_depth, ( $depth+1 ) );
    }
    return $list;
}

/* aprica um funcao booleana em todos os elementos do array e agrupa os resultados com and */
function boolean_and_walk( $array, $function ) 
{
    $resp = true;
    foreach ( $array as $item )
	$resp = $resp && call_user_func( $function, $item );

    return $resp;
}

/* aprica um funcao booleana em todos os elementos do array e agrupa os resultados com or */
function boolean_or_walk( $array, $function )
{
    $resp = false;
    foreach ( $array as $key )
	$resp = $resp || call_user_func( $function, $item );

    return $resp;
}



#--------------------------------------------------------

function criptografa( $str )
{
    /*
    require( INCPATH . "/sec/.skey.inc.php" );
    $iv = mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
    return mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB,$iv );
    */
    return $str;
}

function descriptografa( $str )
{
    /*
    require( INCPATH . "/sec/.skey.inc.php" );
    $iv = mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
    $str = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv );
    return str_replace( "\0","",$str );
    */
    return $str;
}

function calcula_dia_util( $sql, $ini, $fim, $nao_contar_data_ini=1 )
{
    $dias = 0;
    $ini_timestamp = mktime( 0,0,0,$ini[ 'mes' ], $ini[ 'dia' ], $ini[ 'ano' ] );

    /* O dia do $ini nao eh contado como dia util, esse eh o padrao */
    if( $nao_contar_data_ini )
    {
        $aux = strtotime( "+1 day", $ini_timestamp );
        $ini_timestamp = mktime( 0, 0, 0, date( "m", $aux ), date( "d", $aux ), date( "y", $aux ) );
    }

    $ini = date( "Y-m-d", $ini_timestamp );

    $dia_da_semana = date( "w", $ini_timestamp );

    /* 0 = Domingo */
    for( $i=1, $j=$dia_da_semana; $i<=$fim; $i++, $j++ )
    {
        /* 0 == domingo, 6 == sabado */
        if( $j == 0 || $j == 6 )
        {       
            $j = 1;
            $dias++;
        }
        $dias++;
    }

    /* Calculando data parcial ( contando finais de semana ) */
    $dt_parcial = date( "Y-m-d", strtotime( "+" . $dias . " days", $ini_timestamp ) );

    /*
    Contando feriados
    Pego a quantidade de feriados entre a data inicial e data parcial ( contando finais de semana ) E que NAO seja sabado OU domingo
    */

    $query = "
            SELECT
                COUNT( frd_id )
            FROM
                feriado
            WHERE
                frd_dt_data BETWEEN '" . $ini . "' AND '" . $dt_parcial . "'
                AND DATE_PART( 'dow', frd_dt_data ) NOT BETWEEN 5 AND 6";

    $rs = $sql->squery( $query );

    $dias += $rs[ 'count' ];

    $dt_final_timestamp = strtotime( "+" . $dias . " days", $ini_timestamp );

    $dt_final[ 'dia' ] = date( "d", $dt_final_timestamp );
    $dt_final[ 'mes' ] = date( "m", $dt_final_timestamp );
    $dt_final[ 'ano' ] = date( "Y", $dt_final_timestamp );

    return $dt_final;
}

?>
