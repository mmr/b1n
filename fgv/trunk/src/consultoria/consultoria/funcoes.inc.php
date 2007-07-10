<?
/* $Id: funcoes.inc.php,v 1.12 2002/07/31 20:55:57 binary Exp $ */

/*
 *
 * DEFINES
 *
 */

define( "CST_NOVA_CONSULTORIA",              "nova consultoria" );
define( "CST_CONSULTORIA_NAO_CONFIRMADA",    "consultoria nao confirmada" );
define( "CST_REUNIAO_MARCADA",               "reuniao marcada" );
define( "CST_PROPOSTA_EM_ANDAMENTO",         "proposta em andamento" );
define( "CST_PROPOSTA_CONCLUIDA",            "proposta concluida" );
define( "CST_REUNIAO_NAO_GEROU_PROPOSTA",    "reuniao nao gerou proposta" );
define( "CST_PROPOSTA_ENVIADA",              "proposta enviada" );
define( "CST_STAND_BY",                      "stand by" );
define( "CST_FOLLOW_UP",                     "follow up" );
define( "CST_CONTRATO_EM_ANDAMENTO",         "contrato em andamento" );
define( "CST_PROJETO_EM_ANDAMENTO",          "projeto em andamento" );
define( "CST_PROJETO_FINALIZADO",            "projeto finalizado" );
define( "CST_FEEDBACK",                      "feedback" );

/*
 *
 * LIMPAR ( limpa possiveis lixos )
 *
 */

function limpa_consultoria( &$dados )
{
    $dados[ "cli_id" ]            = "";
    $dados[ "cst_id" ]            = "";
    $dados[ "cst_dt_contato" ]    = "";
    $dados[ "cst_dt_retorno_u" ]  = "";
    $dados[ "cst_dt_retorno" ]    = "";
    $dados[ "cst_texto" ]         = "";
}

function limpa_atividade( &$dados )
{
    $dados[ "atv_id" ]        = "";
    $dados[ "atv_desc" ]      = "";
    $dados[ "atv_ordem" ]     = "";
    $dados[ "atv_dt_ini" ]    = "";
    $dados[ "atv_dt_fim_u" ]  = "";
    $dados[ "atv_dt_fim" ]    = "";
}

function limpa_etapa( &$dados )
{
    $dados[ "etp_id" ]        = "";
    $dados[ "etp_desc" ]      = "";
    $dados[ "etp_ordem" ]     = "";
    $dados[ "etp_dt_ini" ]    = "";
    $dados[ "etp_dt_fim_u" ]  = "";
    $dados[ "etp_dt_fim" ]    = "";
}

function limpa_cobranca( &$dados )
{
    $dados[ "cob_id" ]          = "";
    $dados[ "cob_dt_venc" ]     = "";
    $dados[ "cob_parcela" ]     = "";
    $dados[ "cob_pago" ]        = "";
    $dados[ "cob_protocolo" ]   = "";
}

/*
 *
 * CARREGAR ( pega os dados do banco pra re-edicao )
 *
 */

function carrega_consultoria( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            cli_id,
            cst_id,
            cst_nome,
            cst_valor,
            cst_status,
            DATE_PART( 'day', cst_dt_contato ) AS cst_dt_contato_d,
            DATE_PART( 'month', cst_dt_contato ) AS cst_dt_contato_m,
            DATE_PART( 'year', cst_dt_contato ) AS cst_dt_contato_a,
            cst_dt_retorno_u,
            DATE_PART( 'day', cst_dt_retorno ) AS cst_dt_retorno_d,
            DATE_PART( 'month', cst_dt_retorno ) AS cst_dt_retorno_m,
            DATE_PART( 'year', cst_dt_retorno ) AS cst_dt_retorno_a,
            cst_texto
        FROM
            consultoria
        WHERE
            cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'" );
    

    if( $rs )
    {
        $dados[ "cli_id" ]            = $rs[ "cli_id" ];
        $dados[ "cst_id" ]            = $rs[ "cst_id" ];
        $dados[ "cst_nome" ]          = $rs[ "cst_nome" ];
        $dados[ "cst_valor" ]         = $rs[ "cst_valor" ];
        $dados[ "cst_status" ]        = $rs[ "cst_status" ];
        $dados[ "cst_dt_contato" ]    = array( "dia" => $rs[ "cst_dt_contato_d" ],
                                               "mes" => $rs[ "cst_dt_contato_m" ],
                                               "ano" => $rs[ "cst_dt_contato_a" ] );
        $dados[ "cst_dt_retorno_u" ]  = $rs[ "cst_dt_retorno_u" ];
        $dados[ "cst_dt_retorno" ]    = array( "dia" => $rs[ "cst_dt_retorno_d" ],
                                               "mes" => $rs[ "cst_dt_retorno_m" ],
                                               "ano" => $rs[ "cst_dt_retorno_a" ] );
        $dados[ "cst_texto" ]         = $rs[ "cst_texto" ];

        return true;
    }

    return false;
}

function carrega_reuniao( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            DATE_PART( 'day',    cst_dt_reuniao ) AS cst_dt_reuniao_d,
            DATE_PART( 'month',  cst_dt_reuniao ) AS cst_dt_reuniao_m,
            DATE_PART( 'year',   cst_dt_reuniao ) AS cst_dt_reuniao_a,
            DATE_PART( 'hour',   cst_dt_reuniao ) AS cst_dt_reuniao_h,
            DATE_PART( 'minute', cst_dt_reuniao ) AS cst_dt_reuniao_i,
            cst_local_reuniao
        FROM
            consultoria
        WHERE
            cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'" );
    
    if( $rs )
    {
        $dados[ "cst_dt_reuniao" ]    = array( "dia" => $rs[ "cst_dt_reuniao_d" ],      
                                               "mes" => $rs[ "cst_dt_reuniao_m" ],
                                               "ano" => $rs[ "cst_dt_reuniao_a" ],
                                               "hor" => $rs[ "cst_dt_reuniao_h" ],
                                               "min" => $rs[ "cst_dt_reuniao_i" ] );
        $dados[ "cst_local_reuniao" ] = $rs[ "cst_local_reuniao" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_REUNIAO_MARCADA . "'" );

        if( $rs )
        {
            $dados[ "arq_id" ]         = $rs[ "arq_id" ];
            $dados[ "arq_texto" ]      = $rs[ "arq_texto" ];
            $dados[ "arq_nome_falso" ] = $rs[ "arq_nome_falso" ];
        }
        
        return true;
    }

    return false;
}

function carrega_prop_con( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            DATE_PART( 'day',    cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_d,
            DATE_PART( 'month',  cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_m,
            DATE_PART( 'year',   cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_a,
            DATE_PART( 'hour',   cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_h,
            DATE_PART( 'minute', cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_i,
            cst_local_prp_reuniao
        FROM
            consultoria
        WHERE
            cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'" );

    if( $rs )
    {
        $dados[ "cst_dt_prp_reuniao" ]    = array( "dia" => $rs[ "cst_dt_prp_reuniao_d" ],      
                                                   "mes" => $rs[ "cst_dt_prp_reuniao_m" ],
                                                   "ano" => $rs[ "cst_dt_prp_reuniao_a" ],
                                                   "hor" => $rs[ "cst_dt_prp_reuniao_h" ],
                                                   "min" => $rs[ "cst_dt_prp_reuniao_i" ] );

        $dados[ "cst_local_prp_reuniao" ]     = $rs[ "cst_local_prp_reuniao" ];

        $query = "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_PROPOSTA_CONCLUIDA . "'";

        $rs = $sql->squery( $query );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }

    return false;
}

function carrega_nao_gerou( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            com_texto
        FROM
            comentario
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . CST_REUNIAO_NAO_GEROU_PROPOSTA . "'" );
    
    if( $rs )
    {
        $dados[ "com_texto" ]         = $rs[ "com_texto" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_REUNIAO_NAO_GEROU_PROPOSTA  . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }

    return false;
}

function carrega_proposta( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            DATE_PART( 'day', cst_dt_prp_entrega )    AS cst_dt_prp_entrega_d,
            DATE_PART( 'month', cst_dt_prp_entrega )  AS cst_dt_prp_entrega_m,
            DATE_PART( 'year', cst_dt_prp_entrega )   AS cst_dt_prp_entrega_a,
            cst_prp_coordenador
        FROM
            consultoria
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'" );

    if( $rs )
    {
        $dados[ "cst_dt_prp_entrega" ]    = array( "dia" => $rs[ "cst_dt_prp_entrega_d" ],
                                                   "mes" => $rs[ "cst_dt_prp_entrega_m" ],
                                                   "ano" => $rs[ "cst_dt_prp_entrega_a" ] );
        $dados[ "cst_prp_coordenador" ]   = $rs[ "cst_prp_coordenador" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_PROPOSTA_EM_ANDAMENTO . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }

    return false;
}

function carrega_feedback( $sql, &$dados )
{
    /* Dados Especificos */
    $rs = $sql->squery( "
        SELECT
            cst_status 
        FROM
            consultoria 
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'" );

    if( $rs )
    {
        $dados[ 'cst_status' ] = $rs[ 'cst_status' ];

        switch( $dados[ 'cst_status' ] )
        {
            case CST_STAND_BY:
                $dados[ 'cst_feedback' ] = "stand by";
                break;
            case CST_FOLLOW_UP:
                $dados[ 'cst_feedback' ] = "negativo";
                break;
            default:
                $dados[ 'cst_feedback' ] = "positivo";
                $dados[ 'cst_status' ] = CST_CONTRATO_EM_ANDAMENTO;
                break; 
        }


        /* Arquivo de Upload */
        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . $dados[ 'cst_status' ] . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        /* Observacoes / Comentarios */
        $rs = $sql->squery( "
            SELECT
                com_texto
            FROM
                comentario
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . $dados[ 'cst_status' ] . "'" );

        if( $rs )
            $dados[ "com_texto" ] = $rs[ "com_texto" ];

        return true;
    }

    return false;
}

function carrega_nao_confirmada( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            com_texto
        FROM
            comentario
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . CST_CONSULTORIA_NAO_CONFIRMADA  . "'" );
    
    if( $rs )
    {
        $dados[ "com_texto" ] = $rs[ "com_texto" ];
        return true;
    }

    return false;
}

function carrega_atividade( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            atv_id,
            atv_desc,
            atv_ordem,
            DATE_PART( 'day', atv_dt_ini )    AS atv_dt_ini_d,
            DATE_PART( 'month', atv_dt_ini )  AS atv_dt_ini_m,
            DATE_PART( 'year', atv_dt_ini )   AS atv_dt_ini_a,
            atv_dt_fim_u,
            DATE_PART( 'day', atv_dt_fim )    AS atv_dt_fim_d,
            DATE_PART( 'month', atv_dt_fim )  AS atv_dt_fim_m,
            DATE_PART( 'year', atv_dt_fim )   AS atv_dt_fim_a
        FROM
            cst_atividade 
        WHERE
            atv_id = '" . $dados[ "atv_id" ] . "'" );

    if( $rs )
    {
        $dados[ "atv_id" ]        = $rs[ "atv_id" ];
        $dados[ "atv_desc" ]      = $rs[ "atv_desc" ];
        $dados[ "atv_ordem" ]     = $rs[ "atv_ordem" ];
        $dados[ "atv_dt_ini" ]    = array( "dia" => $rs[ "atv_dt_ini_d" ],
                                           "mes" => $rs[ "atv_dt_ini_m" ],
                                           "ano" => $rs[ "atv_dt_ini_a" ] );
        $dados[ "atv_dt_fim_u" ]  = $rs[ "atv_dt_fim_u" ];
        $dados[ "atv_dt_fim" ]    = array( "dia" => $rs[ "atv_dt_fim_d" ],
                                           "mes" => $rs[ "atv_dt_fim_m" ],
                                           "ano" => $rs[ "atv_dt_fim_a" ] );
        return true;
    }

    return false;
}

function carrega_prop_env( $sql, &$dados ) /* Proposta Enviada */
{
    $rs = $sql->squery( "
        SELECT
            DATE_PART( 'day',    cst_dt_prp_envio ) AS cst_dt_prp_envio_d,
            DATE_PART( 'month',  cst_dt_prp_envio ) AS cst_dt_prp_envio_m,
            DATE_PART( 'year',   cst_dt_prp_envio ) AS cst_dt_prp_envio_a,
            cst_dt_prp_retorno_u
        FROM
            consultoria
        WHERE
            cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'" );
    
    if( $rs )
    {
        $dados[ "cst_dt_prp_envio" ] = array( "dia" => $rs[ "cst_dt_prp_envio_d" ],      
                                              "mes" => $rs[ "cst_dt_prp_envio_m" ],
                                              "ano" => $rs[ "cst_dt_prp_envio_a" ] );
        $dados[ "cst_dt_prp_retorno_u" ] = $rs[ "cst_dt_prp_retorno_u" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_PROPOSTA_ENVIADA . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }

    return false;
}

function carrega_contrato( $sql, &$dados )
{
    $query = "
        SELECT
            com_texto
        FROM
            comentario
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . CST_CONTRATO_EM_ANDAMENTO . "'";

    $rs = $sql->squery( $query );

    if( $rs )
    {
        $dados[ "com_texto" ] = $rs[ "com_texto" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_CONTRATO_EM_ANDAMENTO . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }

    return false;
}

function carrega_projeto( $sql, &$dados )
{
    /* misc */
    extract_request_var( "cst_dt_prj_ini",   $dados[ "cst_dt_prj_ini" ] );

    $query = "
        SELECT
            bri_id,
            DATE_PART( 'day', cst_dt_prj_ini )    AS cst_dt_prj_ini_d,
            DATE_PART( 'month', cst_dt_prj_ini )  AS cst_dt_prj_ini_m,
            DATE_PART( 'year', cst_dt_prj_ini )   AS cst_dt_prj_ini_a,
            ppg_id
        FROM
            consultoria
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'";

    $rs = $sql->squery( $query );

    if( $rs )
    {
        $dados[ "bri_id" ]         = $rs[ "bri_id" ];
        $dados[ "cst_dt_prj_ini" ] = array( "dia" => $rs[ "cst_dt_prj_ini_d" ],
                                            "mes" => $rs[ "cst_dt_prj_ini_m" ],
                                            "ano" => $rs[ "cst_dt_prj_ini_a" ] );

        if( ! isset( $_REQUEST[ 'ppg_id' ] ) || ! consis_inteiro( $_REQUEST[ 'ppg_id' ] ) )
            $dados[ "ppg_id" ] = $rs[ "ppg_id" ];

        $rs = $sql->squery( "
            SELECT
                arq_id,
                arq_texto,
                arq_nome_falso
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ "cst_id" ] . "'
                AND cst_status = '" . CST_PROJETO_EM_ANDAMENTO . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

        return true;
    }
}

function carrega_prj_fim( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            com_texto
        FROM
            comentario
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . CST_PROJETO_FINALIZADO . "'" );
    
    if( ! is_array( $rs ) )
        return false;

    $dados[ "com_texto" ]         = $rs[ "com_texto" ];

    $rs = $sql->squery( "
        SELECT
            arq_id,
            arq_texto,
            arq_nome_falso
        FROM
            cst_arq
            NATURAL JOIN arquivo
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . CST_PROJETO_FINALIZADO . "'" );

        if( isset( $rs[ 'arq_id' ] ) && consis_inteiro( $rs[ 'arq_id' ] ) )
        {
            $dados[ "arq_id" ]         = $rs[ 'arq_id' ];
            $dados[ "arq_texto" ]      = $rs[ 'arq_texto' ];
            $dados[ "arq_nome_falso" ] = $rs[ 'arq_nome_falso' ];
        }

    return true;
}

function carrega_etapa( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            etp_id,
            etp_desc,
            etp_ordem,
            DATE_PART( 'day', etp_dt_ini )    AS etp_dt_ini_d,
            DATE_PART( 'month', etp_dt_ini )  AS etp_dt_ini_m,
            DATE_PART( 'year', etp_dt_ini )   AS etp_dt_ini_a,
            etp_dt_fim_u,
            DATE_PART( 'day', etp_dt_fim )    AS etp_dt_fim_d,
            DATE_PART( 'month', etp_dt_fim )  AS etp_dt_fim_m,
            DATE_PART( 'year', etp_dt_fim )   AS etp_dt_fim_a
        FROM
            cst_etapa 
        WHERE
            etp_id = '" . $dados[ "etp_id" ] . "'" );

    if( ! is_array( $rs ) )
        return false;

    $dados[ "etp_id" ]        = $rs[ "etp_id" ];
    $dados[ "etp_desc" ]      = $rs[ "etp_desc" ];
    $dados[ "etp_ordem" ]     = $rs[ "etp_ordem" ];
    $dados[ "etp_dt_ini" ]    = array( "dia" => $rs[ "etp_dt_ini_d" ],
                                    "mes" => $rs[ "etp_dt_ini_m" ],
                                    "ano" => $rs[ "etp_dt_ini_a" ] );
    $dados[ "etp_dt_fim_u" ]  = $rs[ "etp_dt_fim_u" ];
    $dados[ "etp_dt_fim" ]    = array( "dia" => $rs[ "etp_dt_fim_d" ],
                                    "mes" => $rs[ "etp_dt_fim_m" ],
                                    "ano" => $rs[ "etp_dt_fim_a" ] );
    return true;
}

function carrega_cobranca( $sql, &$dados )
{
    $rs = $sql->squery( "
        SELECT
            ppg_id,
            cob_id,
            DATE_PART( 'day', cob_dt_venc )     AS cob_dt_venc_d,
            DATE_PART( 'month', cob_dt_venc )   AS cob_dt_venc_m,
            DATE_PART( 'year', cob_dt_venc )    AS cob_dt_venc_a,
            cob_parcela,
            cob_nota,
            cob_pago,
            cob_protocolo
        FROM
            cobranca 
            NATURAL JOIN consultoria
        WHERE
            cob_id = '" . $dados[ "cob_id" ] . "'
            AND cst_id = '" . $dados[ 'cst_id' ] . "'" ); 

    if( $rs )
    {
        $dados[ "cob_id" ]      = $rs[ "cob_id" ];

        $dados[ "cob_dt_venc" ] = array( "dia" => $rs[ "cob_dt_venc_d" ],
                                         "mes" => $rs[ "cob_dt_venc_m" ],
                                         "ano" => $rs[ "cob_dt_venc_a" ] );
        $dados[ 'cob_parcela' ]     = $rs[ 'cob_parcela' ];
        $dados[ 'cob_nota' ]        = $rs[ 'cob_nota' ];
        $dados[ 'cob_pago' ]        = $rs[ 'cob_pago' ];
        $dados[ 'cob_protocolo' ]   = $rs[ 'cob_protocolo' ];
        $dados[ 'ppg_id' ]          = $rs[ 'ppg_id' ];

        return true;
    }

    return false;
}

/*
 *
 * INSERIR
 *
 */

function insere_consultoria( $sql, &$dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $rs = $sql->squery( "SELECT nextval( 'consultoria_cst_id_seq' )" );
        if( $rs )
        {
            $dados[ "cst_id" ] = $rs[ "nextval" ];
            $query = "
                INSERT
                INTO consultoria
                (
                    cli_id,
                    cst_id,
                    cst_nome,
                    cst_dt_contato,
                    cst_dt_retorno_u,
                    cst_dt_retorno,
                    cst_texto
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "cli_id" ] )   . "',
                    '" . in_bd( $dados[ "cst_id" ] )   . "',
                    '" . in_bd( $dados[ "cst_nome" ] ) . "',
                    '" . in_bd( hash_to_databd( $dados[ "cst_dt_contato" ] ) ) . "',
                    '" . in_bd( $dados[ "cst_dt_retorno_u" ] ) . "',
                    '" . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "cst_dt_contato" ], $dados[ "cst_dt_retorno_u" ] ) ) ) . "',
                    '" . in_bd( $dados[ "cst_texto" ] ) . "'
                )";

            $rs = $sql->query( $query );
            if( $rs )
            {
                include( INCPATH . "/aviso_auto.inc.php" );
                envia_task_novo_cliente( $sql, $dados[ 'cst_id' ], $dados[ 'cst_dt_retorno_u' ] );
                $sql->query( "COMMIT TRANSACTION" );
                return true;
            }
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_consultor( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    
    if( $rs )
    {
        $query = "
            INSERT INTO cst_mem
            (
                cst_id,
                cst_status,
                mem_id
            )
            VALUES 
            (
                '" . in_bd( $dados[ "cst_id" ] )   . "',
                '" . in_bd( $dados[ "cst_status" ] )   . "',
                '" . in_bd( $dados[ "mem_id" ] ) . "'
            )";

        $rs = $sql->query( $query );
        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_professor( $sql, $cst_id, $cst_status, $prf_id )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    
    if( $rs )
    {
        $query = "
            INSERT INTO cst_prf
            (
                cst_id,
                cst_status,
                prf_id
            )
            VALUES 
            (
                '" . in_bd( $cst_id )     . "',
                '" . in_bd( $cst_status ) . "',
                '" . in_bd( $prf_id )     . "'
            )";

        $rs = $sql->query( $query );
        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_tipo_projeto( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    
    if( $rs )
    {
        $query = "
            INSERT INTO cst_tpj
            (
                cst_id,
                tpj_id
            )
            VALUES 
            (
                '" . in_bd( $dados[ "cst_id" ] )   . "',
                '" . in_bd( $dados[ "tpj_id" ] )   . "'
            )";

        $rs = $sql->query( $query );
        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_atividade( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $rs = $sql->squery( "SELECT nextval( 'cst_atividade_atv_id_seq' )" );

        if( $rs )
        {
            $dados[ "atv_id" ] = $rs[ 'nextval' ];

            $query = "
            INSERT INTO cst_atividade
            (
                cst_id,
                atv_id,
                atv_ordem,
                atv_desc,
                atv_dt_ini,
                atv_dt_fim_u,
                atv_dt_fim
            )
            VALUES 
            (
                '" . in_bd( $dados[ "cst_id" ] )        . "',
                '" . in_bd( $dados[ "atv_id" ] )        . "',
                '" . in_bd( $dados[ "atv_ordem" ] )     . "',
                '" . in_bd( $dados[ "atv_desc" ] )      . "',
                '" . in_bd( hash_to_databd( $dados[ "atv_dt_ini" ] ) )    . "',
                '" . in_bd( $dados[ "atv_dt_fim_u" ] )  . "',
                '" . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "atv_dt_ini" ], $dados[ "atv_dt_fim_u" ] ) ) ) . "'
            )";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_etapa( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $rs = $sql->squery( "SELECT nextval( 'cst_etapa_etp_id_seq' )" );

        if( $rs )
        {
            $dados[ "etp_id" ] = $rs[ 'nextval' ];

            $query = "
            INSERT INTO cst_etapa
            (
                cst_id,
                etp_id,
                etp_ordem,
                etp_desc,
                etp_dt_ini,
                etp_dt_fim_u,
                etp_dt_fim
            )
            VALUES 
            (
                '" . in_bd( $dados[ "cst_id" ] )        . "',
                '" . in_bd( $dados[ "etp_id" ] )        . "',
                '" . in_bd( $dados[ "etp_ordem" ] )     . "',
                '" . in_bd( $dados[ "etp_desc" ] )      . "',
                '" . in_bd( hash_to_databd( $dados[ "etp_dt_ini" ] ) )    . "',
                '" . in_bd( $dados[ "etp_dt_fim_u" ] )  . "',
                '" . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "etp_dt_ini" ], $dados[ "etp_dt_fim_u" ] ) ) ) . "'
            )";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

function insere_cobranca( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $rs = $sql->squery( "SELECT nextval( 'cobranca_cob_id_seq' )" );

        if( $rs )
        {
            $dados[ "cob_id" ] = $rs[ 'nextval' ];

            $query = "
            INSERT INTO cobranca
            (
                cst_id,
                cob_id,
                cob_dt_venc,
                cob_parcela,
                cob_nota,
                cob_pago,
                cob_protocolo
            )
            VALUES 
            (
                '" . in_bd( $dados[ "cst_id" ] )        . "',
                '" . in_bd( $dados[ "cob_id" ] )        . "',
                '" . in_bd( hash_to_databd( $dados[ 'cob_dt_venc' ] ) ) . "',
                '" . in_bd( $dados[ "cob_parcela" ] )   . "',
                '" . in_bd( $dados[ "cob_nota" ] )      . "',
                '" . in_bd( $dados[ "cob_pago" ] )      . "',
                '" . in_bd( $dados[ "cob_protocolo" ] ) . "'
            )";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false; 
}

/*
 *
 * ALTERAR
 *
 */

function altera_valor_projeto( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "
            DELETE FROM cobranca
            WHERE
               cst_id = '" . in_bd( $dados[ 'cst_id' ] ) . "'";

        $rs = $sql->query( $query );
        
        if( $rs )
        {
            $query = "
                UPDATE consultoria
                SET
                    cst_valor = '"  .   in_bd( reconhece_dinheiro( $dados[ "cst_valor" ] ) )    . "',
                    ppg_id    = '"  .   in_bd( $dados[ 'ppg_id' ] ) . "'
                WHERE
                    cst_id = '"     .   in_bd( $dados[ "cst_id" ] ) . "'";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function altera_consultoria( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
    /*
        $cst_dt_retorno = calcula_dia_util( $sql, $dados[ "cst_dt_contato" ], $dados[ "cst_dt_retorno_u" ] );
        if( $dados[ "cst_dt_retorno" ] != $cst_dt_retorno )
            $cst_dt_retorno = $dados[ "cst_dt_retorno" ];
    */

        $query = "
            UPDATE consultoria
            SET
                cst_nome = '" .         in_bd( $dados[ "cst_nome" ] )           . "',
                cst_dt_contato = '" .   in_bd( hash_to_databd( $dados[ "cst_dt_contato" ] ) ) . "',
                cst_dt_retorno_u = '" . in_bd( $dados[ "cst_dt_retorno_u" ] )   . "',
                cst_dt_retorno = '" .   in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "cst_dt_contato" ], $dados[ "cst_dt_retorno_u" ] ) ) ) . "',
                cst_texto = '" .        in_bd( $dados[ "cst_texto" ] )          . "'
            WHERE
                cst_id = '"   .         in_bd( $dados[ "cst_id" ] )             . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function altera_atividade( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE cst_atividade
            SET
                atv_ordem = '"      . in_bd( $dados[ "atv_ordem" ] ) . "',
                atv_desc  = '"      . in_bd( $dados[ "atv_desc" ] ) . "',
                atv_dt_ini = '"     . in_bd( hash_to_databd( $dados[ "atv_dt_ini" ] ) ) . "',
                atv_dt_fim_u = '"   . in_bd( $dados[ "atv_dt_fim_u" ] ) . "',
                atv_dt_fim  = '"    . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "atv_dt_ini" ], $dados[ "atv_dt_fim_u" ] ) ) ) . "'
            WHERE
                atv_id = '" . in_bd( $dados[ "atv_id" ] )   . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function altera_cobranca( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE cobranca
            SET
                cob_dt_venc = '"    . in_bd( hash_to_databd( $dados[ 'cob_dt_venc' ] ) ) . "',
                cob_parcela = '"    . in_bd( $dados[ 'cob_parcela' ] )  . "',
                cob_nota = '"       . in_bd( $dados[ 'cob_nota' ] )     . "',
                cob_pago = '"       . in_bd( $dados[ 'cob_pago' ] )     . "',
                cob_protocolo = '"  . in_bd( $dados[ 'cob_protocolo' ] ) . "'
            WHERE
                cob_id = '" . in_bd( $dados[ "cob_id" ] )   . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function altera_etapa( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE cst_etapa
            SET
                etp_ordem = '"      . in_bd( $dados[ "etp_ordem" ] ) . "',
                etp_desc  = '"      . in_bd( $dados[ "etp_desc" ] ) . "',
                etp_dt_ini = '"     . in_bd( hash_to_databd( $dados[ "etp_dt_ini" ] ) ) . "',
                etp_dt_fim_u = '"   . in_bd( $dados[ "etp_dt_fim_u" ] ) . "',
                etp_dt_fim  = '"    . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "etp_dt_ini" ], $dados[ "etp_dt_fim_u" ] ) ) ) . "'
            WHERE
                etp_id = '" . in_bd( $dados[ "etp_id" ] )   . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}


/*
 *
 * APAGAR
 *
 */

function apaga_consultoria( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        /* apagando todos arquivos referenciados por essa consultoria (upload) */
        $q_del_arq = "DELETE FROM arquivo WHERE arq_id = ''";

        $rs = $sql->query( "
            SELECT
                arq_id,
                arq_nome_real
            FROM
                cst_arq
                NATURAL JOIN arquivo
            WHERE
                cst_id = '" . $dados[ 'cst_id' ] . "'" );

        if( is_array( $rs ) )
        {
            foreach( $rs as $arq )
            {
                $q_del_arq .= " OR arq_id = '" . $arq[ 'arq_id' ] . "'";
                if( is_writable( UPLOAD_DIR . "/" . $arq[ 'arq_nome_real' ] ) )
                    unlink( UPLOAD_DIR . "/" . $arq[ 'arq_nome_real' ] );
            }
        }

        $rs = $sql->query( $q_del_arq );

        if( $rs )
        {
            $rs = $sql->query( "
                DELETE FROM
                    consultoria
                WHERE
                    cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'" );
            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function apaga_consultor( $sql, $dados, $nome_hash )
{
    if( !is_array( $dados[ $nome_hash ] ) )
        return false;

    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cst_mem WHERE mem_id = '' ";

        if( !is_array( $dados[ $nome_hash ] ) )
            return false;

        foreach( $dados[ $nome_hash ] as $mem_id )
            $query .= " OR mem_id = '" . $mem_id . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
      
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function apaga_professor( $sql, $dados )
{
    if( !is_array( $dados[ "professores" ] ) )
        return false;

    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cst_prf WHERE prf_id = '' ";

        if( !is_array( $dados[ "professores" ] ) )
            return false;

        foreach( $dados[ "professores" ] as $prf_id )
            $query .= " OR prf_id = '" . $prf_id . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function apaga_tipo_projeto( $sql, $dados )
{
    if( !is_array( $dados[ "tipos_projeto" ] ) )
        return false;

    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cst_tpj WHERE tpj_id = '' ";

        if( !is_array( $dados[ "tipos_projeto" ] ) )
            return false;

        foreach( $dados[ "tipos_projeto" ] as $tpj_id )
            $query .= " OR tpj_id = '" . $tpj_id . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function apaga_atividade( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cst_atividade WHERE atv_id = '" . $dados[ "atv_id" ] . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function apaga_etapa( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cst_etapa WHERE etp_id = '" . $dados[ "etp_id" ] . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function apaga_cobranca( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );
    if( $rs )
    {
        $query = "DELETE FROM cobranca WHERE cob_id = '" . $dados[ "cob_id" ] . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

/*
 *
 * VALIDAR
 *
 */

function valida_consultoria( $sql, $dados, $subpagina )
{
    $error_msgs = array( );

    if( $dados[ "cst_nome" ] == "" )
        array_push( $error_msgs, "É necessário preencher o nome da consultoria" );

    if( ! consis_data( $dados[ "cst_dt_contato" ][ "dia" ],
                      $dados[ "cst_dt_contato" ][ "mes" ],
                      $dados[ "cst_dt_contato" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Contato inválida" );


    if( $subpagina == "alterar" )
        if( ! consis_data( $dados[ "cst_dt_retorno" ][ "dia" ],
                          $dados[ "cst_dt_retorno" ][ "mes" ],
                          $dados[ "cst_dt_retorno" ][ "ano" ] ) )
            array_push( $error_msgs, "Data de Retorno inválida" );

    if( $subpagina == "inserir" )
        if( $dados[ "cli_id" ] == "" )
            array_push( $error_msgs, "É necessário escolher um cliente" );

    if( ! consis_inteiro( $dados[ "cst_dt_retorno_u" ] ) )
        array_push( $error_msgs, "É necessário preencher o campo de data de retorno ( dias úteis )" );

    return $error_msgs;
}

function valida_reuniao( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_data( $dados[ "cst_dt_reuniao" ][ "dia" ],
                       $dados[ "cst_dt_reuniao" ][ "mes" ],
                       $dados[ "cst_dt_reuniao" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Reunião inválida" );

    if( ! ( consis_inteiro( $dados[ "cst_dt_reuniao" ][ "hor" ] )
            && $dados[ "cst_dt_reuniao" ][ "hor" ] >= 0 
            &&  $dados[ "cst_dt_reuniao" ][ "hor" ] <= 23 ) ||
        ! ( consis_inteiro( $dados[ "cst_dt_reuniao" ][ "min" ] )
            && $dados[ "cst_dt_reuniao" ][ "hor" ] >= 0 
            &&  $dados[ "cst_dt_reuniao" ][ "hor" ] <= 59 ) )
        array_push( $error_msgs, "Hora inválida" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_REUNIAO_MARCADA, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_consultor_reuniao( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "mem_id" ] == "" )
        array_push( $error_msgs, "É necessário escolher um consultor" );

    $query = "
        SELECT
            COUNT( mem_id )
        FROM
            cst_mem
        WHERE
            cst_id = '" . $dados[ 'cst_id' ] . "' 
            AND mem_id = '" . $dados[ 'mem_id' ] . "'
            AND cst_status = '" . $dados[ 'cst_status' ] . "'";

    $rs = $sql->squery( $query );

    if( $rs[ "count" ] > 0 )
        array_push( $error_msgs, "Já existe uma ocorrência desse consultor para essa consultoria" );

    return $error_msgs;
}

function valida_nao_gerou( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "com_texto" ] == "" )
        array_push( $error_msgs, "É necessário preencher o comentário" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_REUNIAO_NAO_GEROU_PROPOSTA, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_nao_confirmada( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "com_texto" ] == "" )
        array_push( $error_msgs, "É necessário preencher o( s ) motivo( s )" );

    return $error_msgs;
}

function valida_professor( $sql, $dados, $status )
{
    $error_msgs = array( );

    if( $dados[ "prf_id" ] == "" )
        array_push( $error_msgs, "É necessário escolher um professor" );

    $query = "
        SELECT
            COUNT( prf_id )
        FROM
            cst_prf
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND cst_status = '" . $status . "'
            AND prf_id = '" . $dados[ "prf_id" ] . "'";

    $rs = $sql->squery( $query );

    if( $rs[ "count" ] > 0 )
        array_push( $error_msgs, "Já existe uma ocorrência desse professor para essa consultoria" );

    return $error_msgs;
}

function valida_tipo_projeto( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "tpj_id" ] == "" )
        array_push( $error_msgs, "É necessário escolher um tipo de projeto" );

    $query = "
        SELECT
            COUNT( tpj_id )
        FROM
            cst_tpj
        WHERE
            cst_id = '" . $dados[ "cst_id" ] . "'
            AND tpj_id = '" . $dados[ "tpj_id" ] ."'";

    $rs = $sql->squery( $query );

    if( $rs[ "count" ] > 0 )
        array_push( $error_msgs, "Já existe uma ocorrência desse tipo de projeto para essa consultoria" );

    return $error_msgs;
}

function valida_atividade( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_inteiro( $dados[ "atv_ordem" ] ) )
        array_push( $error_msgs, "Ordem precisa ser um número inteiro" );

    if( $dados[ "atv_desc" ] == "" )
        array_push( $error_msgs, "O preenchimento do nome da atividade é obrigatório" );

    if( ! consis_data( $dados[ "atv_dt_ini" ][ "dia" ],
                        $dados[ "atv_dt_ini" ][ "mes" ],
                        $dados[ "atv_dt_ini" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Início inválida" );

    return $error_msgs;
}

function valida_proposta( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "mem_id" ] == "" )
        array_push( $error_msgs, "É necessário selecionar um coordenador" );

    if( ! consis_data( $dados[ "cst_dt_prp_entrega" ][ "dia" ],
                       $dados[ "cst_dt_prp_entrega" ][ "mes" ],
                       $dados[ "cst_dt_prp_entrega" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Entrega inválida" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_PROPOSTA_EM_ANDAMENTO, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_prop_con( $sql, $dados )
{
    $error_msgs = array();

    if( ! consis_data(  $dados[ "cst_dt_prp_reuniao" ][ "dia" ],
                        $dados[ "cst_dt_prp_reuniao" ][ "mes" ],
                        $dados[ "cst_dt_prp_reuniao" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Reunião inválida" );

    if( ! ( consis_inteiro( $dados[ "cst_dt_prp_reuniao" ][ "hor" ] )
            && $dados[ "cst_dt_prp_reuniao" ][ "hor" ] >= 0 
            &&  $dados[ "cst_dt_prp_reuniao" ][ "hor" ] <= 23 ) ||
        ! ( consis_inteiro( $dados[ "cst_dt_prp_reuniao" ][ "min" ] )
            && $dados[ "cst_dt_prp_reuniao" ][ "hor" ] >= 0 
            &&  $dados[ "cst_dt_prp_reuniao" ][ "hor" ] <= 59 ) )
        array_push( $error_msgs, "Hora inválida" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
    {
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_PROPOSTA_CONCLUIDA, $dados[ "arq_texto" ] );
    }

    return $error_msgs;
}

function valida_prop_env( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_data( $dados[ "cst_dt_prp_envio" ][ "dia" ],
                      $dados[ "cst_dt_prp_envio" ][ "mes" ],
                      $dados[ "cst_dt_prp_envio" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Envio da Proposta inválida" );

    if( ! consis_inteiro( $dados[ "cst_dt_prp_retorno_u" ] ) )
        array_push( $error_msgs, "É necessário o preenchimento do prazo para retorno dado ao cliente ( em dias úteis )" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_PROPOSTA_ENVIADA, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_feedback( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "cst_feedback" ] == "" )
        array_push( $error_msgs, "É necessário escolher um FeedBack" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_FEEDBACK, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_contrato( $sql, $dados )
{
    $error_msgs = array( );

/*
    if( $dados[ "com_texto" ] == "" )
        array_push( $error_msgs, "É necessário preencher o comentário" );
*/

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_CONTRATO_EM_ANDAMENTO, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_prj_fim( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_data( $dados[ "cst_dt_prj_fim" ][ "dia" ],
                       $dados[ "cst_dt_prj_fim" ][ "mes" ],
                       $dados[ "cst_dt_prj_fim" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Término de Projeto inválida" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_PROJETO_FINALIZADO, $dados[ "arq_texto" ] );

    return $error_msgs;
}

function valida_etapa( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_inteiro( $dados[ "etp_ordem" ] ) )
        array_push( $error_msgs, "Ordem precisa ser um número inteiro" );

    if( ! consis_inteiro( $dados[ "etp_dt_fim_u" ] ) )
        array_push( $error_msgs, "Data final ( dias úteis ) precisa ser um número inteiro" );

    if( $dados[ "etp_desc" ] == "" )
        array_push( $error_msgs, "O preenchimento do nome da etapa é obrigatório" );

    if( ! consis_data( $dados[ "etp_dt_ini" ][ "dia" ],
                      $dados[ "etp_dt_ini" ][ "mes" ],
                      $dados[ "etp_dt_ini" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Início inválida" );

    return $error_msgs;
}

function valida_cobranca( $sql, $dados, $subpagina )
{
    $error_msgs = array( );

    if( ! consis_data( $dados[ 'cob_dt_venc' ][ 'dia' ],
                       $dados[ 'cob_dt_venc' ][ 'mes' ],
                       $dados[ 'cob_dt_venc' ][ 'ano' ] ) )
        array_push( $error_msgs, "Data de Vencimento inválida" );

    if( ! consis_inteiro( $dados[ 'cob_parcela' ] ) )
        array_push( $error_msgs, "Número de parcela inválido" );

    if( ! consis_inteiro( $dados[ 'cob_nota' ] ) )
        array_push( $error_msgs, "Número de nota fiscal inválido" );

    if( $subpagina != "alterar" )
    {
        $query = "
            SELECT
                COUNT( cob_id )
            FROM
                cobranca
            WHERE
                cst_id = '" . $dados[ 'cst_id' ] . "'";

        $cob_rs = $sql->squery( $query );

        $erro_inesperado = 0;
        if( $cob_rs )
        {
            $query = "
                SELECT
                    ppg_plano
                FROM
                    plano_pgto
                WHERE ppg_id = '" . $dados[ 'ppg_id' ] . "'";

            $ppg_rs = $sql->squery( $query );

            if( $ppg_rs )
            {
                if( $cob_rs[ 'count' ] >= $ppg_rs[ 'ppg_plano' ] )
                   array_push( $error_msgs, "Esse plano de pagamento não suporta mais cobranças." ); 
            }
            else
                $erro_inesperado = 1;
        }
        else
            $erro_inesperado = 1;

        if( $erro_inesperado )
            array_push( $error_msgs, "Erro inesperado ao tentar conferir consistência de cobrança com plano de pagamento" );
    }


    return $error_msgs;
}

function valida_valor_projeto( $sql, $dados )
{
    $error_msgs = array( );

    if( $dados[ "cst_valor" ] == "" || ! consis_dinheiro( reconhece_dinheiro( $dados[ "cst_valor" ] ) ) )
        array_push( $error_msgs, "Valor de projeto inválido" );

    if( reconhece_dinheiro( strlen( $dados[ 'cst_valor' ] ) ) > 8 )
        array_push( $error_msgs, "Valor de projeto muito grande" );

    if( ! consis_inteiro( $dados[ 'ppg_id' ] ) )
        array_push( $error_msgs, "É necessário escolher um plano de pagamento" );

    return $error_msgs;
}

function valida_projeto( $sql, $dados )
{
    $error_msgs = array( );

    if( ! consis_data( $dados[ "cst_dt_prj_ini" ][ "dia" ],
                       $dados[ "cst_dt_prj_ini" ][ "mes" ],
                       $dados[ "cst_dt_prj_ini" ][ "ano" ] ) )
        array_push( $error_msgs, "Data de Início inválida" );

    if( $dados[ "bri_id" ] != "" && ! consis_inteiro( $dados[ "bri_id" ] ) )
        array_push( $error_msgs, "O Brinde escolhido não é válido" );

    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
        if( $_FILES[ 'arq' ][ 'name' ] == '' )
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
        else
            if( ! sizeof( $error_msgs ) )
                $error_msgs = faz_upload_cst( $sql, $dados[ "cst_id" ], CST_PROJETO_EM_ANDAMENTO, $dados[ "arq_texto" ] );

    return $error_msgs;
}

/*
 *
 * MOSTRAR
 *
 */

function mostra_consultores( $sql, $cst_id, $cst_status, $nome_hash )
{
    $query = "
        SELECT DISTINCT
            cst.mem_id,
            mem.mem_nome
        FROM
            cst_mem cst
            LEFT JOIN membro_vivo mem ON ( cst.mem_id = mem.mem_id )
        WHERE
            cst_id = '" . $cst_id . "'
            AND cst_status = '" . $cst_status . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $item )
        {
            print "<tr>";
            print "<td bgcolor='#ffffff'><input type='checkbox' name='" . $nome_hash . "[ ]' value='" . $item[ "mem_id" ] . "' /></td>";
            print "<td bgcolor='#ffffff'>" . $item[ "mem_nome" ] . "</td>";
            print "</tr>";
        }
    }
}

function mostra_atividades( $sql, $cst_id, $suppagina, $pagina )
{
    $query = "
        SELECT
            DISTINCT atv_id,
            atv_ordem,
            atv_desc,
            DATE_PART( 'day', atv_dt_ini )    AS atv_dt_ini_d,
            DATE_PART( 'month', atv_dt_ini )  AS atv_dt_ini_m,
            DATE_PART( 'year', atv_dt_ini )   AS atv_dt_ini_a,
            atv_dt_fim_u,
            DATE_PART( 'day', atv_dt_fim )    AS atv_dt_fim_d,
            DATE_PART( 'month', atv_dt_fim )  AS atv_dt_fim_m,
            DATE_PART( 'year', atv_dt_fim )   AS atv_dt_fim_a
        FROM
            cst_atividade
        WHERE
            cst_id = '" . $cst_id . "'
        ORDER BY
            atv_ordem";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        print "<tr>";
        print "<td bgcolor='#ffffff' class='textb'>Ordem</td>";
        print "<td bgcolor='#ffffff' class='textb'>Atividade</td>";
        print "<td bgcolor='#ffffff' class='textb'>Prazo ( d.u. )</td>";
        print "<td bgcolor='#ffffff' class='textb'>Data Início</td>";
        print "<td bgcolor='#ffffff' class='textb'>Data Fim</td>";
        print "<td bgcolor='#ffffff' class='textb' colspan='2'>Funções</td>";
        print "</tr>";

        foreach( $rs as $item )
        {
            if( strlen( $item[ "atv_desc" ] ) > 20 )
                $desc = substr( $item[ "atv_desc" ], 0, 20 ) . "...";
            else
                $desc = $item[ 'atv_desc' ];

            print "<tr>";
            print "<td bgcolor='#ffffff' class='text'>" . $item[ "atv_ordem" ] . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . $desc . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . $item[ "atv_dt_fim_u" ] . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%02d/%02d/%d", $item[ "atv_dt_ini_d" ], $item[ "atv_dt_ini_m" ], $item[ "atv_dt_ini_a" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%02d/%02d/%d", $item[ "atv_dt_fim_d" ], $item[ "atv_dt_fim_m" ], $item[ "atv_dt_fim_a" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=atividade&cst_id=" . $cst_id . "&atv_id=" . $item[ "atv_id" ] . "'>Editar</a></td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=apagar&tipo_apagar=atividade&cst_id=" . $cst_id . "&atv_id=" . $item[ "atv_id" ] . "'>Excluir</a></td>";
            print "</tr>";
        }
    }
}

function mostra_tipos_projeto( $sql, $cst_id )
{
    $query = "
        SELECT
            DISTINCT tpj_id,
            tpj_nome
        FROM
            cst_tpj
            NATURAL JOIN tipo_projeto
        WHERE
            cst_id = '" . $cst_id . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $item )
        {
            print "<tr>";
            print "<td bgcolor='#ffffff'><input type='checkbox' name='tipos_projeto[ ]' value='" . $item[ "tpj_id" ] . "' /></td>";
            print "<td bgcolor='#ffffff'>" . $item[ "tpj_nome" ] . "</td>";
            print "</tr>";
        }
    }
}

function mostra_professores( $sql, $cst_id, $cst_status )
{
    $query = "
        SELECT
            DISTINCT prf_id,
            prf_nome
        FROM
            cst_prf
            NATURAL JOIN professor
        WHERE
            cst_id = '" . $cst_id . "'
            AND cst_status = '" . $cst_status . "'"; 

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        foreach( $rs as $item )
        {
            print "<tr>";
            print "<td bgcolor='#ffffff'><input type='checkbox' name='professores[ ]' value='" . $item[ "prf_id" ] . "' /></td>";
            print "<td bgcolor='#ffffff'>" . $item[ "prf_nome" ] . "</td>";
            print "</tr>";
        }
    }
}

function mostra_etapas( $sql, $cst_id, $suppagina, $pagina )
{
    $query = "
        SELECT
            DISTINCT etp_id,
            etp_ordem,
            etp_desc,
            DATE_PART( 'day', etp_dt_ini )    AS etp_dt_ini_d,
            DATE_PART( 'month', etp_dt_ini )  AS etp_dt_ini_m,
            DATE_PART( 'year', etp_dt_ini )   AS etp_dt_ini_a,
            etp_dt_fim_u,
            DATE_PART( 'day', etp_dt_fim )    AS etp_dt_fim_d,
            DATE_PART( 'month', etp_dt_fim )  AS etp_dt_fim_m,
            DATE_PART( 'year', etp_dt_fim )   AS etp_dt_fim_a
        FROM
            cst_etapa
        WHERE
            cst_id = '" . $cst_id . "'
        ORDER BY
            etp_ordem";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        print "<tr>";
        print "<td bgcolor='#ffffff' class='textb'>Ordem</td>";
        print "<td bgcolor='#ffffff' class='textb'>Etapa</td>";
        print "<td bgcolor='#ffffff' class='textb'>Prazo ( d.u. )</td>";
        print "<td bgcolor='#ffffff' class='textb'>Data Início</td>";
        print "<td bgcolor='#ffffff' class='textb'>Data Fim</td>";
        print "<td bgcolor='#ffffff' class='textb' colspan='2'>Funções</td>";
        print "</tr>";

        foreach( $rs as $item )
        {
            if( strlen( $item[ "etp_desc" ] ) > 20 )
                $desc = substr( $item[ "etp_desc" ], 0, 20 ) . "...";
            else
                $desc = $item[ 'etp_desc' ];

            print "<tr>";
            print "<td bgcolor='#ffffff' class='text'>" . $item[ "etp_ordem" ] . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . $desc . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . $item[ "etp_dt_fim_u" ] . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%02d/%02d/%d", $item[ "etp_dt_ini_d" ], $item[ "etp_dt_ini_m" ], $item[ "etp_dt_ini_a" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%02d/%02d/%d", $item[ "etp_dt_fim_d" ], $item[ "etp_dt_fim_m" ], $item[ "etp_dt_fim_a" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=etapa&cst_id=" . $cst_id . "&etp_id=" . $item[ "etp_id" ] . "'>Editar</a></td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=apagar&tipo_apagar=etapa&cst_id=" . $cst_id . "&etp_id=" . $item[ "etp_id" ] . "'>Excluir</a></td>";
            print "</tr>";
        }
    }
}

function mostra_cobrancas( $sql, $cst_id, $ppg_id, $suppagina, $pagina )
{
    $query = "
        SELECT
            DISTINCT cob_id,
            cob_parcela,
            DATE_PART( 'day', cob_dt_venc )    AS cob_dt_venc_d,
            DATE_PART( 'month', cob_dt_venc )  AS cob_dt_venc_m,
            DATE_PART( 'year', cob_dt_venc )   AS cob_dt_venc_a,
            cob_nota,
            cob_pago,
            cob_protocolo
        FROM
            cobranca
        WHERE
            cst_id = '" . $cst_id . "'
        ORDER BY
            cob_parcela";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        print "<tr>";
        print "<td bgcolor='#ffffff' class='textb'>Parcela</td>";
        print "<td bgcolor='#ffffff' class='textb'>Vencimento</td>";
        print "<td bgcolor='#ffffff' class='textb'>Nota Fiscal</td>";
        print "<td bgcolor='#ffffff' class='textb'>Pago</td>";
        print "<td bgcolor='#ffffff' class='textb'>Protocolo</td>";
        print "<td bgcolor='#ffffff' class='textb' colspan='2'>Funções</td>";
        print "</tr>";

        foreach( $rs as $item )
        {
            print "<tr>";
            print "<td bgcolor='#ffffff' class='text'>" . $item[ "cob_parcela" ] . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%02d/%02d/%d", $item[ "cob_dt_venc_d" ], $item[ "cob_dt_venc_m" ], $item[ "cob_dt_venc_a" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . sprintf( "%05d", $item[ "cob_nota" ] ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . ( ( $item[ "cob_pago" ] == "1" ) ? "Sim" : "Não" ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'>" . ( ( $item[ "cob_protocolo" ] == "1" ) ? "Sim" : "Não" ) . "</td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=cobranca&cst_id=" . $cst_id . "&cob_id=" . $item[ "cob_id" ] . "'>Editar</a></td>";
            print "<td bgcolor='#ffffff' class='text'><a href='" . $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=apagar&tipo_apagar=cobranca&cst_id=" . $cst_id . "&cob_id=" . $item[ "cob_id" ] . "'>Excluir</a></td>";
            print "</tr>";
        }
    }

    $rs = $sql->squery( "
        SELECT
            COUNT( cob_id )
        FROM
            cobranca
        WHERE
            cst_id = '" . $cst_id . "'" );

    if( $rs )
        return $rs[ 'count' ];
    else
        return 1;
}

function mostra_botoes_da_consultoria( $cst_id, $status, $suppagina="consultoria", $pagina="consultoria", $subpagina="alterar" )
{
    print "
    <tr><td bgcolor='#336699' align='center' colspan='2'>
    <script language='JavaScript'>
    /*
    function verifica_cst_status( atual, velho )
    {
        alert( atual + '\\n\\n' + velho )
    }
    */
    </script>";

    $coisas = array( 'suppagina'    => $suppagina,
                     'pagina'       => $pagina,
                     'subpagina'    => $subpagina,
                     'cst_id'       => $cst_id,
                     'status'       => $status );

    function mostra_link( $c, $a, $s )
    {
        print "<br /><a class='lmenu' href='" .  $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $c[ 'suppagina' ] . "&pagina=" . $c[ 'pagina' ] . "&subpagina=" . $c[ 'subpagina' ] . "&cst_id=" . $c[ 'cst_id' ] . "&status=" . urlencode( $s ) . "'>" . $a . "</a><br />";
    }

    switch( $status )
    {
        case CST_PROJETO_FINALIZADO:
        case CST_PROJETO_EM_ANDAMENTO:
            mostra_link( $coisas, 'Projeto Finalizado', CST_PROJETO_FINALIZADO );
            mostra_link( $coisas, 'Projeto', CST_PROJETO_EM_ANDAMENTO );
        case CST_CONTRATO_EM_ANDAMENTO:
            mostra_link( $coisas, 'Contrato', CST_CONTRATO_EM_ANDAMENTO );
        case CST_STAND_BY:
        case CST_FOLLOW_UP:
        case CST_PROPOSTA_ENVIADA:
            mostra_link( $coisas, 'FeedBack do Cliente', CST_FEEDBACK );
        case CST_PROPOSTA_CONCLUIDA:
            mostra_link( $coisas, 'Proposta Enviada', CST_PROPOSTA_ENVIADA );
        case CST_PROPOSTA_EM_ANDAMENTO:
            mostra_link( $coisas, 'Proposta Concluída', CST_PROPOSTA_CONCLUIDA );
        case CST_REUNIAO_MARCADA:
            mostra_link( $coisas, 'Proposta', CST_PROPOSTA_EM_ANDAMENTO );
            mostra_link( $coisas, 'Reunião Não Gerou Proposta', CST_REUNIAO_NAO_GEROU_PROPOSTA );
            mostra_link( $coisas, 'Agendar Reunião', CST_REUNIAO_MARCADA );
            break;
        case CST_NOVA_CONSULTORIA:
            mostra_link( $coisas, 'Agendar Reunião', CST_REUNIAO_MARCADA );
            mostra_link( $coisas, 'Consultoria Não Confirmada no Atendimento Telefônico', CST_CONSULTORIA_NAO_CONFIRMADA );
            break;
        case CST_REUNIAO_NAO_GEROU_PROPOSTA:
        case CST_CONSULTORIA_NAO_CONFIRMADA:
            mostra_link( $coisas, 'Agendar Reunião', CST_REUNIAO_MARCADA );
            break;
    }
    print "<br /></td></tr><tr><td bgcolor='#ffffff' colspan='2'>&nbsp;</td></tr>";
}

/*
 *
 * MARCAR ( marca mudancas de status e inclui/exclui o que for preciso )
 *
 */

function marcar_reuniao( $sql, $dados, $status )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        /* Formato: aaaa-mm-dd hh:mm */
        $dados[ "cst_dt_reuniao" ]  = 
                $dados[ "cst_dt_reuniao" ][ "ano" ] . "-" . $dados[ "cst_dt_reuniao" ][ "mes" ] . "-" . $dados[ "cst_dt_reuniao" ][ "dia" ] . " " . 
                $dados[ "cst_dt_reuniao" ][ "hor" ] . ":" . $dados[ "cst_dt_reuniao" ][ "min" ];

        $query = "
            UPDATE consultoria
            SET
                cst_status     = '" .   in_bd( $status )      . "',
                cst_dt_reuniao = '" .   in_bd( $dados[ "cst_dt_reuniao" ] ) . "',
                cst_local_reuniao = '" . in_bd( $dados[ "cst_local_reuniao" ] ) . "'
            WHERE
                cst_id = '"   .         in_bd( $dados[ "cst_id" ] )         . "'";

        $rs = $sql->query( $query );

        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }
            
        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }

        return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_nao_gerou( $sql, $dados ) /* reuniao nao gerou proposta */
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            DELETE FROM comentario
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'
                AND cst_status = '" . in_bd( $dados[ "cst_status" ] ) . "'";
    
        $rs = $sql->query( $query );

        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }

        $rs = $sql->squery( "SELECT nextval( 'comentario_com_id_seq' )" );
        if( $rs )
        {
            $dados[ "com_id" ] = $rs[ "nextval" ];

            $query = "
                INSERT INTO comentario
                (
                    cst_id,
                    cst_status,
                    com_id,
                    com_texto
                )
                VALUES
                (
                    '" . in_bd( $dados[ "cst_id" ] )        . "',
                    '" . in_bd( $dados[ "cst_status" ] )    . "',
                    '" . in_bd( $dados[ "com_id" ] )        . "',
                    '" . in_bd( $dados[ "com_texto" ] )     . "'
                )";

            $rs = $sql->query( $query );

            if( !$rs )
            {
                $sql->query( "ROLLBACK TRANSACTION" );
                return false;
            }

            /* Atualizando Status */
            $query = "
                UPDATE consultoria
                SET
                    cst_status     = '" .   in_bd( CST_REUNIAO_NAO_GEROU_PROPOSTA )   . "'
                WHERE
                    cst_id = '"         .         in_bd( $dados[ "cst_id" ] )           . "'";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_nao_confirmada( $sql, $dados ) /* consultoria nao confirmada */
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            DELETE FROM comentario
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'
                AND cst_status = '" . in_bd( CST_CONSULTORIA_NAO_CONFIRMADA ) . "'";
    
        $rs = $sql->query( $query );

        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }

        $rs = $sql->squery( "SELECT nextval( 'comentario_com_id_seq' )" );
        if( $rs )
        {
            $dados[ "com_id" ] = $rs[ "nextval" ];

            $query = "
                INSERT INTO comentario
                (
                    cst_id,
                    cst_status,
                    com_id,
                    com_texto
                )
                VALUES
                (
                    '" . in_bd( $dados[ "cst_id" ] )        . "',
                    '" . in_bd( $dados[ "cst_status" ] )    . "',
                    '" . in_bd( $dados[ "com_id" ] )        . "',
                    '" . in_bd( $dados[ "com_texto" ] )     . "'
                )";

            $rs = $sql->query( $query );

            if( !$rs )
            {
                $sql->query( "ROLLBACK TRANSACTION" );
                return false;
            }

            $query = "
                UPDATE consultoria
                SET
                    cst_status     = '" .   in_bd( CST_CONSULTORIA_NAO_CONFIRMADA )   . "'
                WHERE
                    cst_id = '"         .         in_bd( $dados[ "cst_id" ] )           . "'";

            $rs = $sql->query( $query );

            if( !$rs )
            {
                $sql->query( "ROLLBACK TRANSACTION" );
                return false;
            }
        }

        return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_proposta( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE consultoria
            SET
                cst_status          = '" . in_bd( CST_PROPOSTA_EM_ANDAMENTO )      . "',
                cst_prp_coordenador = '" . in_bd( $dados[ 'mem_id' ] ) . "',
                cst_dt_prp_entrega  = '" . in_bd( hash_to_databd( $dados[ 'cst_dt_prp_entrega' ] ) ) . "'
            WHERE
                cst_id = '"   .         in_bd( $dados[ "cst_id" ] )         . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_prop_con( $sql, $dados )
{
    /* Formato: aaaa-mm-dd hh:mm */
    $dados[ "cst_dt_prp_reuniao" ]  = 
            $dados[ "cst_dt_prp_reuniao" ][ "ano" ] . "-" . $dados[ "cst_dt_prp_reuniao" ][ "mes" ] . "-" . $dados[ "cst_dt_prp_reuniao" ][ "dia" ] . " " . 
            $dados[ "cst_dt_prp_reuniao" ][ "hor" ] . ":" . $dados[ "cst_dt_prp_reuniao" ][ "min" ];

    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE consultoria
            SET
                cst_status     = '" .   in_bd( CST_PROPOSTA_CONCLUIDA )      . "',
                cst_dt_prp_reuniao = '" .   in_bd( $dados[ "cst_dt_prp_reuniao" ] ) . "',
                cst_local_prp_reuniao = '" . in_bd( $dados[ "cst_local_prp_reuniao" ] ) . "'
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] )         . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function marcar_prop_env( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            UPDATE consultoria
            SET
                cst_status     = '" .   in_bd( CST_PROPOSTA_ENVIADA )      . "',
                cst_dt_prp_envio = '" . in_bd( hash_to_databd( $dados[ "cst_dt_prp_envio" ] ) ) . "',
                cst_dt_prp_retorno_u = '" . in_bd( $dados[ "cst_dt_prp_retorno_u" ] ) . "',
                cst_dt_prp_retorno = '" . in_bd( hash_to_databd( calcula_dia_util( $sql, $dados[ "cst_dt_prp_envio" ], $dados[ "cst_dt_retorno_u" ] ) ) ) . "'
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] )         . "'";
    
        $rs = $sql->query( $query );

        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }

        
        include( INCPATH . "/aviso_auto.inc.php" );
        envia_task_carta_agradecimento( $sql, $dados[ 'cst_id' ] );

        return $sql->query( "COMMIT TRANSACTION" );
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_feedback( $sql, $dados ) /* feedback do cliente */
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        switch( $dados[ "cst_feedback" ] )
        {
            case "positivo":
                $dados[ 'cst_status' ] = CST_CONTRATO_EM_ANDAMENTO;
                break;
            case "negativo":
                $dados[ 'cst_status' ] = CST_FOLLOW_UP;
                break;
            case "stand by":
            default:
                $dados[ 'cst_status' ] = CST_STAND_BY;
                break;
        }

        /* deletando comentarios pra esse [ cst_id + cst_status ] soh eh permitido um por [ cst_id + cst_status ] ( a combinacao ) */
        $query = "
            DELETE FROM comentario
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'
                AND cst_status = '" . in_bd( $dados[ "cst_status" ] ) . "'";
    
        $rs = $sql->query( $query );

        if( !$rs )
        {
            $sql->query( "ROLLBACK TRANSACTION" );
            return false;
        }

        /* incluindo o comentario novo */
        $rs = $sql->squery( "SELECT nextval( 'comentario_com_id_seq' )" );
        if( $rs )
        {
            $dados[ "com_id" ] = $rs[ "nextval" ];

            $query = "
                INSERT INTO comentario
                (
                    cst_id,
                    cst_status,
                    com_id,
                    com_texto
                )
                VALUES
                (
                    '" . in_bd( $dados[ "cst_id" ] )        . "',
                    '" . in_bd( $dados[ "cst_status" ] )    . "',
                    '" . in_bd( $dados[ "com_id" ] )        . "',
                    '" . in_bd( $dados[ "com_texto" ] )     . "'
                )";

            $rs = $sql->query( $query );

            if( !$rs )
            {
                $sql->query( "ROLLBACK TRANSACTION" );
                return false;
            }

            /* Atualizando Status */
            $query = "
                UPDATE consultoria
                SET
                    cst_status     = '" .   in_bd( $dados[ "cst_status" ] )   . "'
                WHERE
                    cst_id = '"         .   in_bd( $dados[ "cst_id" ] ) . "'";

            $rs = $sql->query( $query );

            if( $rs )
                return $sql->query( "COMMIT TRANSACTION" );
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function marcar_contrato( $sql, $dados ) /* contrato em andamento */
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            DELETE FROM comentario
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'
                AND cst_status = '" . in_bd( $dados[ "cst_status" ] ) . "'";
    
        $rs = $sql->query( $query );

        if( $rs )
        {

            $rs = $sql->squery( "SELECT nextval( 'comentario_com_id_seq' )" );
            if( $rs )
            {
                $dados[ "com_id" ] = $rs[ "nextval" ];

                $query = "
                    INSERT INTO comentario
                    (
                        cst_id,
                        cst_status,
                        com_id,
                        com_texto
                    )
                    VALUES
                    (
                        '" . in_bd( $dados[ "cst_id" ] )        . "',
                        '" . in_bd( $dados[ "cst_status" ] )    . "',
                        '" . in_bd( $dados[ "com_id" ] )        . "',
                        '" . in_bd( $dados[ "com_texto" ] )     . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    /* Atualizando Status */
                    $query = "
                        UPDATE consultoria
                        SET
                            cst_status = '"     .   in_bd( CST_PROJETO_EM_ANDAMENTO )   . "'
                        WHERE
                            cst_id = '"         .   in_bd( $dados[ "cst_id" ] )           . "'";

                    $rs = $sql->query( $query );

                    if( $rs )
                        return $sql->query( "COMMIT TRANSACTION" );
                }
            }
        }
    }

    $sql->query( "ROLLBACK TRANSACTION" );
    return false;
}

function marcar_prj_fim( $sql, $dados ) /* termino de projeto */
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    if( $rs )
    {
        $query = "
            DELETE FROM comentario
            WHERE
                cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'
                AND cst_status = '" . in_bd( $dados[ "cst_status" ] ) . "'";
    
        $rs = $sql->query( $query );

        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'comentario_com_id_seq' )" );
            if( $rs )
            {
                $dados[ "com_id" ] = $rs[ "nextval" ];

                $query = "
                    INSERT INTO comentario
                    (
                        cst_id,
                        cst_status,
                        com_id,
                        com_texto
                    )
                    VALUES
                    (
                        '" . in_bd( $dados[ "cst_id" ] )        . "',
                        '" . in_bd( $dados[ "cst_status" ] )    . "',
                        '" . in_bd( $dados[ "com_id" ] )        . "',
                        '" . in_bd( $dados[ "com_texto" ] )     . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    /* Atualizando Status */
                    $query = "
                        UPDATE consultoria
                        SET
                            cst_status = '" .   in_bd( CST_PROJETO_FINALIZADO )   . "'
                        WHERE
                            cst_id = '" . in_bd( $dados[ "cst_id" ] ) . "'";

                    $rs = $sql->query( $query );
                    
                    if( $rs )
                        return $sql->query( "COMMIT TRANSACTION" );
                }
            }
        }
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}

function marcar_projeto( $sql, $dados )
{
    $rs = $sql->query( "BEGIN TRANSACTION" );

    /* Atualizando Dados na tabela de Consultoria */
    if( $rs )
    {
        if( $dados[ "bri_id" ] != "" )
            $brinde = "'" . in_bd( $dados[ "bri_id" ] ) . "'";
        else
            $brinde = "NULL";

        $query = "
            UPDATE consultoria
            SET
                bri_id = " . $brinde . ",
                cst_dt_prj_ini = '" . in_bd( hash_to_databd( $dados[ 'cst_dt_prj_ini' ] ) ) . "',
                cst_status = '" . in_bd( CST_PROJETO_EM_ANDAMENTO ) . "'
            WHERE
                cst_id = '"   .  in_bd( $dados[ "cst_id" ] )    . "'";

        $rs = $sql->query( $query );

        if( $rs )
            return $sql->query( "COMMIT TRANSACTION" );
    }
   
    $sql->query( "ROLLBACK TRANSACTION" );
    return false;    
}


/*
 *
 * MISC ( coisas que nao se encaixaram nas outras secoes )
 *
 */

function faz_upload_cst( $sql, $cst_id, $cst_status, $arq_texto )
{
    $error_msgs = array();

    /* Apagando referencias anteriores a esse cst_id com esse cst_status ( soh um arquivo por [ cst_id + cst_staus ] ) */
    $query = "
        SELECT
            arq_id,
            arq_nome_real
        FROM
            cst_arq
            NATURAL JOIN arquivo
        WHERE
            cst_id = '" . in_bd( $cst_id ) . "'
            AND cst_status = '" . in_bd( $cst_status ) . "'";

    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
        $query = "DELETE FROM arquivo WHERE arq_id = ''";

        foreach( $rs as $arq )
        {
            $query .= " OR arq_id = '" . $arq[ "arq_id" ] . "'";
            
            /* Apagando do sistema de arquivos */
            if( is_writable( UPLOAD_DIR . "/" . $arq[ "arq_nome_real" ] ) )
                unlink( UPLOAD_DIR . "/" . $arq[ "arq_nome_real" ] );
        }

        $sql->query( $query );

        $query = "
            DELETE FROM cst_arq
            WHERE
                cst_id = '" . in_bd( $cst_id ) . "'
                AND cst_status = '" . in_bd( $cst_status ) . "'";

        $sql->query( $query );
    }

/* ------------------------------------- */

    $arq_nome_real = "cst_" . $cst_id . "_" . str_replace( " ", "_", $cst_status );

    /* grava arquivo no sistema de arquivos */
    clearstatcache();
    if( ! is_writable( UPLOAD_DIR ) )
        array_push( $error_msgs, "Não tem permissão de escrita no diretório de Upload" );

/*
    if( $_FILES[ 'arq' ][ 'tmp_name' ] == 'none' )
        array_push( $error_msgs, "Arquivo inválidou ou muito grande para fazer Upload" );
*/

    if( ! copy( $_FILES[ 'arq' ][ 'tmp_name' ], UPLOAD_DIR . "/" . $arq_nome_real ) )
        array_push( $error_msgs, "Erro inesperado! Não foi possível completar upload..." );

    if( sizeof( $error_msgs ) )
        return $error_msgs;


    /* Gravar referencias do arquivo no banco de dados */
    $rs = $sql->squery( "SELECT nextval( 'arquivo_arq_id_seq' )" );

    if( $rs )
    {
        $arq_id = $rs[ "nextval" ];

        $query = "
            INSERT INTO arquivo
            (
                arq_id,
                arq_texto,
                arq_nome_real,
                arq_nome_falso
            )
            VALUES
            (
                '" . in_bd( $arq_id )            . "',
                '" . in_bd( $arq_texto )         . "',
                '" . in_bd( $arq_nome_real )     . "',
                '" . in_bd( $_FILES[ 'arq' ][ 'name' ] )    . "'
            )";

        $rs = $sql->query( $query );

        if( $rs )
        {
            $query = "
                INSERT INTO cst_arq
                (
                    cst_id,
                    cst_status,
                    arq_id
                ) 
                VALUES
                (
                    '" . in_bd( $cst_id ) . "',
                    '" . in_bd( $cst_status ) . "',
                    '" . in_bd( $arq_id ) . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
                return $error_msgs;
        }
    }
    
    $sql->query( "ROLLBACK TRANSACTION" );

    return $error_msgs;
}

?>
