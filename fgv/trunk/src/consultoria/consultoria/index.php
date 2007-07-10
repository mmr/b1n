<?
/* $Id: index.php,v 1.8 2002/07/19 20:31:11 binary Exp $ */

require_once( $suppagina . "/" . $pagina . "/funcoes.inc.php" );

/* monta uma estrutura com os dados da busca. */


$mod_titulo = "Consultoria";
$colspan = "8";

extract_request_var( "status",           $status );
extract_request_var( "cst_id",           $dados[ "cst_id" ] );
extract_request_var( "cst_status",       $dados[ "cst_status" ] );

/* Upload mala */
extract_request_var( "arq_texto",        $dados[ "arq_texto" ] );
extract_request_var( "arq_id",           $dados[ "arq_id" ] );
extract_request_var( "arq_nome_falso",   $dados[ "arq_nome_falso" ] );
extract_request_var( "arq_nome_real",    $dados[ "arq_nome_real" ] );

switch( $subpagina )
{
case "inserir":
    extract_request_var( "tipo_inserir", $tipo_inserir );
    switch( $tipo_inserir )
    {
        case "consultoria":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "cli_id",           $dados[ "cli_id" ] );
            extract_request_var( "cst_nome",         $dados[ "cst_nome" ] );
            extract_request_var( "cst_dt_contato",   $dados[ "cst_dt_contato" ] );
            extract_request_var( "cst_dt_retorno",   $dados[ "cst_dt_retorno" ] );
            extract_request_var( "cst_dt_retorno_u", $dados[ "cst_dt_retorno_u" ] );
            extract_request_var( "cst_texto",        $dados[ "cst_texto" ] );

            /* Se for igual a 'GO', ja passou pelo form, deve inserir agora no banco */
            if( $acao == "go" )
            {
                $error_msgs = valida_consultoria( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( insere_consultoria( $sql, $dados ) )
                    {
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_INSERIR, $dados[ "cst_id" ] );
                        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                        {
                            include( ACESSO_NEGADO );
                            break;
                        }
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );

                        include( $suppagina . "/" . $pagina . "/listar.php" );
                        break;
                    }        
                } 
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                limpa_consultoria( $dados );
            }
            include( $suppagina . "/" . $pagina . "/inserir.php" );

            break;
        case "professor":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_PROFESSOR_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "prf_id",   $dados[ "prf_id" ] );

            if( !carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            $error_msgs = valida_professor( $sql, $dados, $status );
            if( !sizeof( $error_msgs ) )
                if( insere_professor( $sql, $dados[ "cst_id" ], $status, $dados[ "prf_id" ] ) )
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_PROFESSOR_INSERIR, $dados[ "prf_id" ] );

            break;
        case "atividade":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ATIVIDADE_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "atv_id",       $dados[ "atv_id" ] );
            extract_request_var( "atv_desc",     $dados[ "atv_desc" ] );
            extract_request_var( "atv_ordem",    $dados[ "atv_ordem" ] );
            extract_request_var( "atv_dt_ini",   $dados[ "atv_dt_ini" ] );
            extract_request_var( "atv_dt_fim_u", $dados[ "atv_dt_fim_u" ] );
            extract_request_var( "atv_dt_fim",   $dados[ "atv_dt_fim" ] );

            /* Se for igual a 'GO', ja passou pelo form, deve inserir agora no banco */
            if( $acao == "go" )
            {
                $error_msgs = valida_atividade( $sql, $dados );
                if( !sizeof( $error_msgs ) )
                {
                    if( insere_atividade( $sql, $dados ) )
                    {
                        $acao = "";
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ATIVIDADE_INSERIR, $dados[ "cst_id" ] );
                        break;
                    }        
                }
                else
                {
                    $status = "";
                }
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                limpa_atividade( $dados );
            }
            include( $suppagina . "/" . $pagina . "/inserir_atividade.php" );

            break;
        case "consultor_reuniao":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "mem_id",   $dados[ "mem_id" ] );

            if( !carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            $dados[ 'cst_status' ] = CST_NOVA_CONSULTORIA;
            $error_msgs = valida_consultor_reuniao( $sql, $dados );
            if( !sizeof( $error_msgs ) )
                if( insere_consultor( $sql, $dados ) )
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_INSERIR, $dados[ "mem_id" ] );

            break;
        case "consultor_projeto":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "mem_id",   $dados[ "mem_id" ] );

            if( !carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            $error_msgs = valida_consultor_reuniao( $sql, $dados );
            $dados[ 'cst_status' ] = CST_PROJETO_EM_ANDAMENTO;
            if( !sizeof( $error_msgs ) )
                if( insere_consultor( $sql, $dados ) )
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_INSERIR, $dados[ "mem_id" ] );

            break;
        case "tipo_projeto":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_TIPO_PROJETO_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "tpj_id",   $dados[ "tpj_id" ] );

            if( !carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            $error_msgs = valida_tipo_projeto( $sql, $dados );
            if( !sizeof( $error_msgs ) )
                if( insere_tipo_projeto( $sql, $dados ) )
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_TIPO_PROJETO_INSERIR, $dados[ "tpj_id" ] );

            break;
        case "etapa":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ETAPA_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "etp_id",       $dados[ "etp_id" ] );
            extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
            extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
            extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
            extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
            extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

            /* Se for igual a 'GO', ja passou pelo form, deve inserir agora no banco */
            if( $acao == "go" )
            {
                $error_msgs = valida_etapa( $sql, $dados );
                if( !sizeof( $error_msgs ) )
                {
                    if( insere_etapa( $sql, $dados ) )
                    {
                        $acao = "";
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ETAPA_INSERIR, $dados[ "cst_id" ] );
                        break;
                    }        
                }
                else
                {
                    $status = "";
                }
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                limpa_etapa( $dados );
            }
            include( $suppagina . "/" . $pagina . "/inserir_etapa.php" );

            break;
        case "cobranca":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_COBRANCA_INSERIR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "ppg_id",          $dados[ "ppg_id" ] );
            extract_request_var( "ppg_plano",       $dados[ "ppg_plano" ] );
            extract_request_var( "cob_dt_venc",     $dados[ "cob_dt_venc" ] );
            extract_request_var( "cob_parcela",     $dados[ "cob_parcela" ] );
            extract_request_var( "cob_nota",        $dados[ "cob_nota" ] );
            extract_request_var( "cob_protocolo",   $dados[ "cob_protocolo" ] );
            extract_request_var( "cob_pago",        $dados[ "cob_pago" ] );

            /* Se for igual a 'GO', ja passou pelo form, deve inserir agora no banco */
            if( $acao == "go" )
            {
                $error_msgs = valida_cobranca( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( insere_cobranca( $sql, $dados ) )
                    {
                        $acao = "";
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_COBRANCA_INSERIR, $dados[ "cst_id" ] );
                        break;
                    }        
                }
                else
                {
                    $status = "";
                }
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                limpa_cobranca( $dados );
            }
            include( $suppagina . "/" . $pagina . "/inserir_cobranca.php" );
            break;
        case "brinde":
            break;
    }
    break;
case "alterar":
    extract_request_var( "tipo_alterar", $tipo_alterar );
    switch( $tipo_alterar )
    {
        case "valor_projeto":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "cst_valor",   $dados[ "cst_valor" ] );
            extract_request_var( "ppg_id",      $dados[ "ppg_id" ] );

            $error_msgs = valida_valor_projeto( $sql, $dados, $subpagina );
            if( !sizeof( $error_msgs ) )
            {
                if( altera_valor_projeto( $sql, $dados ) )
                {
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );

                    /* etapas */
                    extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
                    extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
                    extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
                    extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
                    extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

                    /* membro consultor */
                    extract_request_var( "mem_id",       $dados[ "mem_id" ] );

                    /* professor orientador */
                    extract_request_var( "prf_id",       $dados[ "prf_id" ] );

                    /* cobranca */
                    extract_request_var( "cst_valor",     $dados[ "cst_valor" ] );
                    extract_request_var( "ppg_id",        $dados[ "ppg_id" ] );
                    extract_request_var( "ppg_plano",     $dados[ "ppg_plano" ] );
                    extract_request_var( "cob_parcela",   $dados[ "cob_parcela" ] );
                    extract_request_var( "cob_valor",     $dados[ "cob_valor" ] );
                    extract_request_var( "cob_dt_venc",   $dados[ "cob_dt_venc" ] );
                    extract_request_var( "cob_nota",      $dados[ "cob_nota" ] );
                    extract_request_var( "cob_protocolo", $dados[ "cob_protocolo" ] );
                    extract_request_var( "cob_pago",      $dados[ "cob_pago" ] );

                    /* misc */
                    extract_request_var( "cst_dt_prj_ini",   $dados[ "cst_dt_prj_ini" ] );
                    extract_request_var( "bri_id",           $dados[ "bri_id" ] );

                    carrega_projeto( $sql, $dados );
                    break;
                } 
            }
            break;
        case "consultoria":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "cst_nome",         $dados[ "cst_nome" ] );
            extract_request_var( "cst_dt_contato",   $dados[ "cst_dt_contato" ] );
            extract_request_var( "cst_dt_retorno",   $dados[ "cst_dt_retorno" ] );
            extract_request_var( "cst_dt_retorno_u", $dados[ "cst_dt_retorno_u" ] );
            extract_request_var( "cst_texto",        $dados[ "cst_texto" ] );

            if( $acao == "go" )
            {
                $error_msgs = valida_consultoria( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( altera_consultoria( $sql, $dados ) )
                    {
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                        {
                            include( ACESSO_NEGADO );
                            break;
                        }
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );

                        include( $suppagina . "/" . $pagina . "/listar.php" );
                        break;
                    }        
                } 
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );

            break;
        case "atividade":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ATIVIDADE_ALTERAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "atv_id",       $dados[ "atv_id" ] );
            extract_request_var( "atv_desc",     $dados[ "atv_desc" ] );
            extract_request_var( "atv_ordem",    $dados[ "atv_ordem" ] );
            extract_request_var( "atv_dt_ini",   $dados[ "atv_dt_ini" ] );
            extract_request_var( "atv_dt_fim_u", $dados[ "atv_dt_fim_u" ] );
            extract_request_var( "atv_dt_fim",   $dados[ "atv_dt_fim" ] );

            if( $acao == "go" )
            {
                $error_msgs = valida_atividade( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( altera_atividade( $sql, $dados ) )
                    {
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ATIVIDADE_ALTERAR, $dados[ "cst_id" ] );
                        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                        {
                            include( ACESSO_NEGADO );
                            break;
                        }
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ATIVIDADE_LISTAR );

                        /* default */
                        extract_request_var( 'mem_id',               $dados[ 'mem_id ' ] );  /* coordenador */
                        extract_request_var( 'cst_dt_prp_entrega',   $dados[ 'cst_dt_prp_entrega' ] );

                        /* atividade */
                        extract_request_var( "atv_ordem",    $dados[ "atv_ordem" ] );
                        extract_request_var( "atv_desc",     $dados[ "atv_desc" ] );
                        extract_request_var( "atv_dt_fim_u", $dados[ "atv_dt_fim_u" ] );
                        extract_request_var( "atv_dt_ini",   $dados[ "atv_dt_ini" ] );
                        extract_request_var( "atv_dt_fim",   $dados[ "atv_dt_fim" ] );

                        /* tipos de projeto */
                        extract_request_var( "tpj_id",       $dados[ "tpj_id" ] );

                        /* professor */
                        extract_request_var( "prf_id",       $dados[ "prf_id" ] );

                        carrega_proposta( $sql, $dados );

                        include( $suppagina . "/" . $pagina . "/prop_and.php" );
                        break;
                    }        
                } 
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                if( !carrega_atividade( $sql, $dados ) )
                {
                    carrega_proposta( $sql, $dados );
                    include( $suppagina . "/" . $pagina . "/prop_and.php" );
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar_atividade.php" );

            break;
        case "etapa":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ETAPA_ALTERAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "etp_id",       $dados[ "etp_id" ] );
            extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
            extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
            extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
            extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
            extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

            if( $acao == "go" )
            {
                $error_msgs = valida_etapa( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( altera_etapa( $sql, $dados ) )
                    {
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ETAPA_ALTERAR, $dados[ "cst_id" ] );
                        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                        {
                            include( ACESSO_NEGADO );
                            break;
                        }
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ETAPA_LISTAR );

                        /* etapas */
                        extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
                        extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
                        extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
                        extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
                        extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

                        /* membro consultor */
                        extract_request_var( "mem_id",       $dados[ "mem_id" ] );

                        /* professor orientador */
                        extract_request_var( "prf_id",       $dados[ "prf_id" ] );

                        /* cobranca */
                        extract_request_var( "cst_valor",     $dados[ "cst_valor" ] );
                        extract_request_var( "ppg_id",        $dados[ "ppg_id" ] );
                        extract_request_var( "ppg_plano",     $dados[ "ppg_plano" ] );
                        extract_request_var( "cob_parcela",   $dados[ "cob_parcela" ] );
                        extract_request_var( "cob_valor",     $dados[ "cob_valor" ] );
                        extract_request_var( "cob_dt_venc",   $dados[ "cob_dt_venc" ] );
                        extract_request_var( "cob_nota",      $dados[ "cob_nota" ] );
                        extract_request_var( "cob_protocolo", $dados[ "cob_protocolo" ] );
                        extract_request_var( "cob_pago",      $dados[ "cob_pago" ] );

                        /* misc */
                        extract_request_var( "cst_dt_prj_ini",   $dados[ "cst_dt_prj_ini" ] );
                        extract_request_var( "bri_id",           $dados[ "bri_id" ] );

                        carrega_projeto( $sql, $dados );

                        include( $suppagina . "/" . $pagina . "/projeto.php" );
                        break;
                    }        
                } 
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                if( !carrega_etapa( $sql, $dados ) )
                {
                    carrega_proposta( $sql, $dados );
                    include( $suppagina . "/" . $pagina . "/projeto.php" );
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar_etapa.php" );

            break;
    case "cobranca":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_COBRANCA_ALTERAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "cob_id",          $dados[ "cob_id" ] );
            extract_request_var( "cob_dt_venc",     $dados[ "cob_dt_venc" ] );
            extract_request_var( "cob_parcela",     $dados[ "cob_parcela" ] );
            extract_request_var( "cob_nota",        $dados[ "cob_nota" ] );
            extract_request_var( "cob_protocolo",   $dados[ "cob_protocolo" ] );
            extract_request_var( "cob_pago",        $dados[ "cob_pago" ] );


            if( $acao == "go" )
            {
                $error_msgs = valida_cobranca( $sql, $dados, $subpagina );
                if( !sizeof( $error_msgs ) )
                {
                    if( altera_cobranca( $sql, $dados ) )
                    {
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_COBRANCA_ALTERAR, $dados[ "cob_id" ] );
                        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) ) 
                        {
                            include( ACESSO_NEGADO );
                            break;
                        }
                        log_fnc( $sql, FUNC_CST_CONSULTORIA_ETAPA_LISTAR );

                        /* etapas */
                        extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
                        extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
                        extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
                        extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
                        extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

                        /* membro consultor */
                        extract_request_var( "mem_id",       $dados[ "mem_id" ] );

                        /* professor orientador */
                        extract_request_var( "prf_id",       $dados[ "prf_id" ] );

                        /* cobranca */
                        extract_request_var( "cst_valor",     $dados[ "cst_valor" ] );
                        extract_request_var( "ppg_id",        $dados[ "ppg_id" ] );
                        extract_request_var( "ppg_plano",     $dados[ "ppg_plano" ] );
                        extract_request_var( "cob_parcela",   $dados[ "cob_parcela" ] );
                        extract_request_var( "cob_valor",     $dados[ "cob_valor" ] );
                        extract_request_var( "cob_dt_venc",   $dados[ "cob_dt_venc" ] );
                        extract_request_var( "cob_nota",      $dados[ "cob_nota" ] );
                        extract_request_var( "cob_protocolo", $dados[ "cob_protocolo" ] );
                        extract_request_var( "cob_pago",      $dados[ "cob_pago" ] );

                        /* misc */
                        extract_request_var( "cst_dt_prj_ini",   $dados[ "cst_dt_prj_ini" ] );
                        extract_request_var( "bri_id",           $dados[ "bri_id" ] );

                        carrega_projeto( $sql, $dados );

                        include( $suppagina . "/" . $pagina . "/projeto.php" );
                        break;
                    }        
                } 
            }
            /* Se for diferente de 'GO' deve limpar as variaveis mostrar o formulario */
            else
            {
                if( ! carrega_cobranca( $sql, $dados ) )
                {
                    carrega_projeto( $sql, $dados );
                    include( $suppagina . "/" . $pagina . "/projeto.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar_cobranca.php" );

            break;
    }
    break;
case "apagar":
    extract_request_var( "tipo_apagar", $tipo_apagar );
    switch( $tipo_apagar )
    {
        case "consultoria":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }
            if( $acao == "go" )
            {
                if( apaga_consultoria( $sql, $dados ) )
                {
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_APAGAR, $dados[ "cst_id" ] );
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            if( ! carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }   
            include( $suppagina . "/" . $pagina . "/apagar.php" );
            break;
        case "professor":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_PROFESSOR_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            extract_request_var( "professores", $dados[ "professores" ] );

            if( apaga_professor( $sql, $dados ) )
                log_fnc( $sql, FUNC_CST_CONSULTORIA_PROFESSOR_APAGAR, $dados[ "cst_id" ] );

            break;
        case "atividade":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ATIVIDADE_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "atv_id",       $dados[ "atv_id" ] );
            extract_request_var( "atv_desc",     $dados[ "atv_desc" ] );
            extract_request_var( "atv_ordem",    $dados[ "atv_ordem" ] );
            extract_request_var( "atv_dt_ini",   $dados[ "atv_dt_ini" ] );
            extract_request_var( "atv_dt_fim_u", $dados[ "atv_dt_fim_u" ] );
            extract_request_var( "atv_dt_fim",   $dados[ "atv_dt_fim" ] );

            if( ! carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            if( $acao == "go" )
            {
                if( apaga_atividade( $sql, $dados ) )
                {
                    $acao = "";
                    $status = CST_PROPOSTA_EM_ANDAMENTO;
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_ATIVIDADE_APAGAR, $dados[ "cst_id" ] );
                    break;
                }
            }
            carrega_atividade( $sql, $dados );

            include( $suppagina . "/" . $pagina . "/apagar_atividade.php" );
            break;
        case "tipo_projeto":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_TIPO_PROJETO_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            extract_request_var( "tipos_projeto", $dados[ "tipos_projeto" ] );

            if( apaga_tipo_projeto( $sql, $dados ) )
                log_fnc( $sql, FUNC_CST_CONSULTORIA_TIPO_PROJETO_APAGAR, $dados[ "cst_id" ] );

            break;
        case "consultor_reuniao":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            extract_request_var( "consultor_reuniao", $dados[ "consultor_reuniao" ] );

            $dados[ 'cst_status' ] = CST_NOVA_CONSULTORIA;
            if( apaga_consultor( $sql, $dados, "consultor_reuniao" ) )
                log_fnc( $sql, FUNC_CST_CONSULTORIA_CONSULTOR_REUNIAO_APAGAR, $dados[ "cst_id" ] );

            break;
        case "consultor_projeto":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            extract_request_var( "consultor_projeto", $dados[ "consultor_projeto" ] );

            if( apaga_consultor( $sql, $dados, "consultor_projeto" ) )
                log_fnc( $sql, FUNC_CST_CONSULTORIA_CONSULTOR_PROJETO_APAGAR, $dados[ "cst_id" ] );

            break;
        case "etapa":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_ETAPA_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "etp_id",       $dados[ "etp_id" ] );
            extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
            extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
            extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
            extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
            extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

            if( ! carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            if( $acao == "go" )
            {
                if( apaga_etapa( $sql, $dados ) )
                {
                    $acao = "";
                    $status = CST_PROJETO_EM_ANDAMENTO;
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_ETAPA_APAGAR, $dados[ "cst_id" ] );
                    break;
                }
            }
            carrega_etapa( $sql, $dados );

            include( $suppagina . "/" . $pagina . "/apagar_etapa.php" );
            break;
        case "cobranca":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_COBRANCA_APAGAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }

            /* Dados */
            extract_request_var( "cob_id",      $dados[ "cob_id" ] );
            extract_request_var( "cob_dt_venc", $dados[ "cob_desc" ] );
            extract_request_var( "cob_nota",    $dados[ "cob_nota" ] );
            extract_request_var( "cob_pago",    $dados[ "cob_pago" ] );
            extract_request_var( "cob_protolo", $dados[ "cob_protocolo" ] );

            if( ! carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }

            if( $acao == "go" )
            {
                if( apaga_cobranca( $sql, $dados ) )
                {
                    $acao = "";
                    $status = CST_PROJETO_EM_ANDAMENTO;
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_COBRANCA_APAGAR, $dados[ "cst_id" ] );
                    break;
                }
            }
            carrega_cobranca( $sql, $dados );

            include( $suppagina . "/" . $pagina . "/apagar_cobranca.php" );
            break;
        case "brinde":
            break;
    }
    break;
case "consultar":
    extract_request_var( "tipo_consultar", $tipo_consultar );
    switch( $tipo_consultar )
    {
        case "consultoria":
            if( ! tem_permissao( FUNC_CST_CONSULTORIA_CONSULTAR ) )
            {
                include( ACESSO_NEGADO );
                break;
            }
            if( !carrega_consultoria( $sql, $dados ) )
            {
                if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                {
                    include( ACESSO_NEGADO );
                    break;
                }
                log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                include( $suppagina . "/" . $pagina . "/listar.php" );
                break;
            }
            log_fnc( $sql, FUNC_CST_CONSULTORIA_CONSULTAR, $dados[ "cst_id" ] );
            include( $suppagina . "/" . $pagina . "/consultar.php" );
            break;
    }
    break;
default:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
    include( $suppagina . "/" . $pagina . "/listar.php" );
}

/* Status */
switch( $status )
{
case CST_CONSULTORIA_NAO_CONFIRMADA:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "com_texto",    $dados[ "com_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_nao_confirmada( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_nao_confirmada( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_nao_confirmada( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/nao_confirmada.php" ); /* consultoria nao confirmada */
    break;
case CST_REUNIAO_MARCADA:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "mem_id",           $dados[ "mem_id" ] );
    extract_request_var( "cst_dt_reuniao",   $dados[ "cst_dt_reuniao" ] );
    extract_request_var( "cst_local_reuniao",   $dados[ "cst_local_reuniao" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_reuniao( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_reuniao( $sql, $dados, $status ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_reuniao( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/reuniao.php" ); /* marcar reuniao */
    break;
case CST_PROPOSTA_EM_ANDAMENTO:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    /* default */
    extract_request_var( "arq_texto",            $dados[ "arq_texto" ] );
    extract_request_var( "mem_id",               $dados[ "mem_id" ] );  /* coordenador */
    extract_request_var( "cst_dt_prp_entrega",   $dados[ "cst_dt_prp_entrega" ] );

    /* atividade */
    extract_request_var( "atv_ordem",    $dados[ "atv_ordem" ] );
    extract_request_var( "atv_desc",     $dados[ "atv_desc" ] );
    extract_request_var( "atv_dt_fim_u", $dados[ "atv_dt_fim_u" ] );
    extract_request_var( "atv_dt_ini",   $dados[ "atv_dt_ini" ] );
    extract_request_var( "atv_dt_fim",   $dados[ "atv_dt_fim" ] );

    /* tipos de projeto */
    extract_request_var( "tpj_id",       $dados[ "tpj_id" ] );

    /* professor */
    extract_request_var( "prf_id",       $dados[ "prf_id" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_proposta( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_proposta( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_proposta( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/prop_and.php" ); /* proposta em andamento */
    break;
case CST_PROPOSTA_CONCLUIDA:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "cst_dt_prp_reuniao",   $dados[ "cst_dt_prp_reuniao" ] );
    extract_request_var( "cst_local_prp_reuniao",   $dados[ "cst_local_prp_reuniao" ] );
    extract_request_var( "arq_texto",        $dados[ "arq_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_prop_con( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_prop_con( $sql, $dados, $status ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        }
    }
    carrega_prop_con( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/prop_con.php" );
    break;
case CST_REUNIAO_NAO_GEROU_PROPOSTA:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "com_texto",        $dados[ "com_texto" ] );
    extract_request_var( "arq_texto",        $dados[ "arq_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_nao_gerou( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_nao_gerou( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_nao_gerou( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/nao_gerou.php" ); /* reuniao nao gerou proposta */
    break;
case CST_PROPOSTA_ENVIADA:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "cst_dt_prp_envio",     $dados[ "cst_dt_prp_envio" ] );
    extract_request_var( "cst_dt_prp_retorno_u", $dados[ "cst_dt_prp_retorno_u" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_prop_env( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_prop_env( $sql, $dados, $status ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_prop_env( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/prop_env.php" ); /* proposta enviada */
    break;
case CST_FEEDBACK:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "cst_feedback",     $dados[ "cst_feedback" ] );
    extract_request_var( "com_texto",        $dados[ "com_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_feedback( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_feedback( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_feedback( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/feedback.php" ); /* feedback do cliente */
    break;
case CST_CONTRATO_EM_ANDAMENTO:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    extract_request_var( "com_texto",        $dados[ "com_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_contrato( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_contrato( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_consultoria( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_contrato( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/contrato.php" );
    break;
case CST_PROJETO_EM_ANDAMENTO:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    /* etapas */
    extract_request_var( "etp_ordem",    $dados[ "etp_ordem" ] );
    extract_request_var( "etp_desc",     $dados[ "etp_desc" ] );
    extract_request_var( "etp_dt_fim_u", $dados[ "etp_dt_fim_u" ] );
    extract_request_var( "etp_dt_ini",   $dados[ "etp_dt_ini" ] );
    extract_request_var( "etp_dt_fim",   $dados[ "etp_dt_fim" ] );

    /* membro consultor */
    extract_request_var( "mem_id",       $dados[ "mem_id" ] );

    /* professor orientador */
    extract_request_var( "prf_id",       $dados[ "prf_id" ] );

    /* cobranca */
    extract_request_var( "cst_valor",     $dados[ "cst_valor" ] );
    extract_request_var( "ppg_id",        $dados[ "ppg_id" ] );
    extract_request_var( "ppg_plano",     $dados[ "ppg_plano" ] );
    extract_request_var( "cob_parcela",   $dados[ "cob_parcela" ] );
    extract_request_var( "cob_valor",     $dados[ "cob_valor" ] );
    extract_request_var( "cob_dt_venc",   $dados[ "cob_dt_venc" ] );
    extract_request_var( "cob_nota",      $dados[ "cob_nota" ] );
    extract_request_var( "cob_protocolo", $dados[ "cob_protocolo" ] );
    extract_request_var( "cob_pago",      $dados[ "cob_pago" ] );

    /* misc */
    extract_request_var( "cst_dt_prj_ini",   $dados[ "cst_dt_prj_ini" ] );
    extract_request_var( "bri_id",           $dados[ "bri_id" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_projeto( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_projeto( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                if( ! carrega_projeto( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_projeto( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/projeto.php" ); /* contrato em andamento */
    break;
case CST_PROJETO_FINALIZADO:
    if( ! tem_permissao( FUNC_CST_CONSULTORIA_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    if( ! carrega_consultoria( $sql, $dados ) )
    {
        if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
        include( $suppagina . "/" . $pagina . "/listar.php" );
        break;
    }

    /* misc */
    extract_request_var( "cst_dt_prj_fim",   $dados[ "cst_dt_prj_fim" ] );
    extract_request_var( "com_texto",        $dados[ "com_texto" ] );

    if( $acao == "go" )
    {
        $error_msgs = valida_prj_fim( $sql, $dados );
        if( ! sizeof( $error_msgs ) )
        {
            if( marcar_prj_fim( $sql, $dados ) )
            {
                log_fnc( $sql, FUNC_CST_CONSULTORIA_ALTERAR, $dados[ "cst_id" ] );
                
                if( ! carrega_prj_fim( $sql, $dados ) )
                {
                    if( ! tem_permissao( FUNC_CST_CONSULTORIA_LISTAR ) )
                    {
                        include( ACESSO_NEGADO );
                        break;
                    }
                    log_fnc( $sql, FUNC_CST_CONSULTORIA_LISTAR );
                    include( $suppagina . "/" . $pagina . "/listar.php" );
                    break;
                }
            }
            include( $suppagina . "/" . $pagina . "/alterar.php" );
            break;
        } 
    }
    carrega_prj_fim( $sql, $dados );

    include( $suppagina . "/" . $pagina . "/prj_fim.php" );
    break;
}

?>
