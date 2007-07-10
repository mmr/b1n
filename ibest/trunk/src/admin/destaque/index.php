<?
/* $Id: index.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

require_once( $suppagina . "/" . $pagina . "/funcoes.inc.php" );

/* monta uma estrutura com os dados da busca. */

extract_request_var( "busca_campo",         $busca[ "campo" ] );
extract_request_var( "busca_texto",         $busca[ "texto" ] );
extract_request_var( "busca_qt_por_pagina", $busca[ "qt_por_pagina" ] );
extract_request_var( "busca_pagina_num",    $busca[ "pagina_num" ] );
extract_request_var( "busca_ordem",         $busca[ "ordem" ] );

extract_request_var( 'des_id',              $dados[ 'des_id' ] );
extract_request_var( 'materias',            $dados[ 'materias' ] );

$dados = trim_r( $dados );

$mod_titulo = "Destaque";
$colspan    = "3";

switch ( $subpagina )
{
case "visualizar":
    if( $acao == "go" )
    {
        $error_msgs = validar( $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( alterar( $sql, $dados ) )
            {
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }
        }
    }
    else
    {
        if( ! carregar( $sql, $dados ) )
        {
            include( $suppagina . "/" . $pagina . "/listar.php" );
            break;
        }
    }
    include( $suppagina . "/" . $pagina . "/visualizar.php" );
    break;
default:
    include( $suppagina . "/" . $pagina . "/listar.php" );
}
?>
