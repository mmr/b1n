<?
function extract_request_var( $nome, &$destino, $default="" )
{
    $destino = $default;

    $resp = isset( $_REQUEST[ $nome ] );
    if( $resp )
        $destino = $_REQUEST[ $nome ];

    return $resp;
}

function check_chars( $string, $valid_ch )
{
    if( !$valid_ch ) 
        echo "Warning: incorrect parameters at chack_chars<br>";
    for( $i=0; $i<256; $i++ )
        $char_table[ $i ]=0;
    for( $i=0; $i<sizeof( $valid_ch ); $i++ ) 
        $char_table[ ord( $valid_ch[ $i ] ) ]=1;
    for( $i=0; $i<strlen( $string ); $i++ )
    {
        $c = substr( $string,$i,1 );
        if( $char_table[ ord( $c ) ] == 0 )
            return false;
    }
    return true;
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

    if( ( $c_count==1 ) && ( $p_count==1 ) )
        $c_pos < $p_pos ?  $decc = 1 : $decp = 1; 
    elseif( $c_count==1 )
        $decc = 1;
    elseif( $p_count==1 )
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

#--------------------------------------------------------

// checagem de data ( considerando que a data vai ser recebida sempre com select )
function consis_data( $dia, $mes, $ano )
{
    return checkdate( $mes, $dia, $ano );
}

function consis_boleano( $var )
{
    if( is_numeric( $var ) && ( $var == 0 || $var == 1 ) )
        return true;
    else
        return is_bool( $var );
}

function consis_inteiro( $var )
{
    if( is_numeric( $var ) )
        return is_int( $var+0 );

    return false;
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

    if( !ereg( "^[ a-zA-Z0-9_- ]+\@[ a-zA-Z0-9_- ]+( \.[ a-zA-Z0-9_- ]+ ){1,2}$",$str ) ) 
        return false;

    return true;
}

#--------------------------------------------------------

// TODO: colocar key em arquivo seguro
function criptografa( $str )
{
    global $incpath;
    require( "$incpath/sec/.skey.inc.php" );
    $iv = mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
    return mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB,$iv );
}

function descriptografa( $str )
{
    global $incpath;
    require( "$incpath/sec/.skey.inc.php" );
    $iv = mcrypt_create_iv( mcrypt_get_iv_size( MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB ), MCRYPT_RAND );
    $str = mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $key, $str, MCRYPT_MODE_ECB, $iv );
    return str_replace( "\0","",$str );
}


?>
