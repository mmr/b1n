<?
/* $Id: index.php,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

require_once( $suppagina . "/" . $pagina . "/funcoes.inc.php" );

/* monta uma estrutura com os dados da busca. */

extract_request_var( "busca_campo",         $busca[ "campo" ] );
extract_request_var( "busca_texto",         $busca[ "texto" ] );
extract_request_var( "busca_qt_por_pagina", $busca[ "qt_por_pagina" ] );
extract_request_var( "busca_pagina_num",    $busca[ "pagina_num" ] );
extract_request_var( "busca_ordem",         $busca[ "ordem" ] );

extract_request_var( "mat_id",              $dados[ "mat_id" ] );
extract_request_var( "mat_id_mae",          $dados[ "mat_id_mae" ] );
extract_request_var( "mat_titulo",          $dados[ "mat_titulo" ] );
extract_request_var( "mat_olho",            $dados[ "mat_olho" ] );
extract_request_var( "mat_modo",            $dados[ "mat_modo" ] );
extract_request_var( "mat_texto",           $dados[ "mat_texto" ] );
extract_request_var( "mat_pal_chave",       $dados[ "mat_pal_chave" ] );
extract_request_var( "mat_fonte",           $dados[ "mat_fonte" ] );
extract_request_var( "mat_status",          $dados[ "mat_status" ] );

$dados = trim_r( $dados );

$mod_titulo = "Matéria";
$colspan    = "10";

switch ( $subpagina )
{
case "inserir":
    if( $acao == "go" )
    {
        $error_msgs = validar( $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( inserir( $sql, $dados ) )
            {
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }
        }
    }
    else
    {
        limpar( $dados );
    }
    include( $suppagina . "/" . $pagina . "/inserir.php" );
    break;
case "alterar":
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
    include( $suppagina . "/" . $pagina . "/inserir.php" );
    break;
case "apagar":
    extract_request_var( 'caras_ids', $dados[ 'caras_ids' ] );

    if( is_array( $dados[ 'caras_ids' ] ) )
    {
        if( apagar( $sql, $dados ) )
        {
            include( $suppagina . "/" . $pagina . "/listar.php" );
            break;
        }
    }
    include( $suppagina . "/" . $pagina . "/listar.php" );
    break;
case "destacar":
    extract_request_var( 'destaques',       $dados[ 'destaques' ] );

    extract_request_var( 'mat_des_texto',   $dados[ 'mat_des_texto' ] );
    extract_request_var( 'mat_des_imagem',  $dados[ 'mat_des_imagem' ] );
    extract_request_var( 'mat_des_dt_ent',  $dados[ 'mat_des_dt_ent' ] );

    carregar( $sql, $dados );

    if( $acao == "go" )
    {
        $error_msgs = validar_destaque( $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( destacar( $sql, $dados ) )
            {
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }
        }
    }
    else
    {
        if( ! carregar_destaque( $sql, $dados ) )
        {
            include( $suppagina . "/" . $pagina . "/listar.php" );
            break;
        }
    }

    include( $suppagina . "/" . $pagina . "/destacar.php" );
    break;
case "preview":
    if( ! carregar( $sql, $dados ) )
    {
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }
    include( $suppagina . "/" . $pagina . "/preview.php" );
    break;
default:
    include( $suppagina . "/" . $pagina . "/listar.php" );
}
?>
