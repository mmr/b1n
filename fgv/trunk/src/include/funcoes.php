<?
/* $Id: funcoes.php,v 1.13 2002/05/03 20:31:28 ivanneto Exp $ */

/* Algumas constantes */
$status_em_aberto = "Em aberto";                    /* Status "Em aberto" da task */
$status_arquivada = "Arquivada";                    /* Status "Arquivada" da task */
$status_baixada = "Baixada";                        /* Status "Baixada" da task */
$ano_minimo = 1950;                                 /* Menor ano presente nas selects de anos */
$ano_maximo = 2030;                                 /* Maior ano presente nas selects de anos */
$gravar_arquivos_em = "arquivo/";                   /* Diretorio onde serao gravados os arquivos enviados por upload */
define( "STATUS_BAIXADA",                "Baixada"   );
define( "STATUS_ARQUIVADA",              "Arquivada" );
define( "STATUS_EM_ABERTO",              "Em aberto" );

/* Defines para os status (cst_status) */
define("CST_NOVA_CONSULTORIA",              "nova consultoria");
define("CST_CONSULTORIA_NAO_CONFIRMADA",    "consultoria nao confirmada");
define("CST_REUNIAO_MARCADA",               "reuniao marcada");
define("CST_PROPOSTA_EM_ANDAMENTO",         "proposta em andamento");
define("CST_PROPOSTA_CONCLUIDA",            "proposta concluida");
define("CST_REUNIAO_NAO_GEROU_PROPOSTA",    "reuniao nao gerou proposta");
define("CST_PROPOSTA_ENVIADA",              "proposta enviada");
define("CST_STAND_BY",                      "stand by");
define("CST_FOLLOW_UP",                     "follow up");
define("CST_CONTRATO_EM_ANDAMENTO",         "contrato em andamento");
define("CST_PROJETO_EM_ANDAMENTO",          "projeto em andamento");
define("CST_PROJETO_FINALIZADO",            "projeto finalizado");

/* Defines para datas */
define("ANO_MINIMO", 1950 );           /* Menor ano presente nas selects de anos */
define("ANO_MAXIMO", 2030 );           /* Maior ano presente nas selects de anos */


function faz_select( $nome_select, $lista, $option_values, $text_values, $selecionado = "", $argumento="", $nullvalue = "false", $nullvalue_text = "" )
{
    print( "<select name=\"". $nome_select . "\" " . $argumento . ">\n" );
    if( $nullvalue == "true" )
        print( "<option value=\"\">" . ( $nullvalue_text != "" ? $nullvalue_text : "-----------" ) . "</option>" );
    if( is_array( $lista ) )
        foreach( $lista as $opcao )
            print( "<option value=\"" . $opcao[ $option_values ]  . "\"" .  ( $opcao[ $option_values ] == $selecionado ? " selected" : "" ) . ">" . $opcao[ $text_values ] . "</option>\n" );
    print( "</select>\n" );
}

function faz_select_sequencia( $nome_select, $selecionado = "", $de = 0, $ate = 0, $argumento="" )
{
    $data_atual = getdate();
    switch( $selecionado )
    {
        case "semestre":
            $de = 1;
            $ate = 2;
            $incremento = 1;
            $selecionado = ( $data_atual[ 'mon' ] <= 6 ? 1 : 2 );
            break;
        case "ano":
            $de = $GLOBALS[ 'ano_minimo' ];
            $ate = $GLOBALS[ 'ano_maximo' ];
            $incremento = 1;
            $selecionado = $data_atual[ 'year' ];
            break;
        case "mes":
            $de = 1;
            $ate = 12;
            $incremento = 1;
            $selecionado = $data_atual[ 'mon' ];
            break;
        case "dia":
            $de = 1;
            $ate = 31;
            $incremento = 1;
            $selecionado = $data_atual[ 'mday' ];
            break;
        case "hora":
            $de = 0;
            $ate = 23;
            $incremento = 1;
            $selecionado = $data_atual[ 'hours' ];
            break;
        case "minuto":
            $de = "00";
            $ate = 50;
            $incremento = 10;
            $selecionado = round( $data_atual[ 'minutes' ] / 10 ) * 10;
            break;
        default:
            $incremento = 1;
            break;
    }

    for( $i = $de, $j = 0; $i <= $ate; $i += $incremento, $j++ )
        $sequencia[ $j ][ 'item_sequencia' ] = $i;

    faz_select( $nome_select, $sequencia, "item_sequencia", "item_sequencia", $selecionado, $argumento );
}
?>

