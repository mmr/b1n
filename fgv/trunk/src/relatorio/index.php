<?
/* $Id: index.php,v 1.34 2002/12/17 19:23:36 binary Exp $ */

include_once( INCPATH . "/funcoes.php" );

extract_request_var( "relatorio", $relatorio );
extract_request_var( "acao", $acao );

switch( $acao )
{
    case "procurar_clientes":
        if( ! tem_permissao( FUNC_REL_CLIENTE ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "clientes_nome", $clientes_nome );
        extract_request_var( "clientes_regiao_id", $clientes_regiao_id );
        extract_request_var( "clientes_ramo_id", $clientes_ramo_id );
        extract_request_var( "clientes_estado", $clientes_estado );
	extract_request_var( "busca_pagina_num_cli", $busca_pagina_num_cli );

	
        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] = 
                ( $clientes_nome != "" ? "AND cli_nome ILIKE '%" . $clientes_nome . "%'" : "" ) .
                ( $clientes_regiao_id != "" ? "AND reg_id = '" . $clientes_regiao_id . "'" : "" ) .
                ( $clientes_ramo_id != "" ? "AND ram_id = '" . $clientes_ramo_id . "'" : "" ) .
                ( $clientes_estado != "" ? "AND cli_estado = '" . $clientes_estado . "'" : "" );
                
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_nome' ] =
                $clientes_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_regiao_id' ] =
                $clientes_regiao_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_ramo_id' ] =
                $clientes_ramo_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] =
                $clientes_estado;

            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] ) )
        {                
            $count_cli = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                cliente
                NATURAL JOIN
                ramo
            WHERE
                cli_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] );

	    $n_cli =  $count_cli[ 'quantidade' ];
	    $list_data['qt_paginas_cli'] = ceil( $n_cli / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ] : 1 );
	    if( $busca_pagina_num_cli != "" )
	    {
		$list_data["pagina_num_cli"] = $busca_pagina_num_cli;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ] = $busca_pagina_num_cli;
	    }
	    else
		$list_data["pagina_num_cli"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'clientes' ];	
	    if( $list_data["pagina_num_cli"] > $list_data['qt_paginas_cli'] )
		$list_data["pagina_num_cli"] = $list_data['qt_paginas_cli'];
	    if(  $list_data["pagina_num_cli"] <= 0 )
		$list_data["pagina_num_cli"] = 1;
	    
	    $busca_clientes = $sql->query( "
            SELECT DISTINCT
                cli_id,
                cli_nome,
                cli_razao,
                ram_nome,
                reg_nome,
                cli_endereco,
                cli_bairro,
                cli_nome_contato,
                cli_ddd,
                cli_ddi,
                cli_telefone
            FROM
                cliente
                NATURAL LEFT OUTER JOIN ramo
                NATURAL LEFT OUTER JOIN regiao
            WHERE
                cli_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] . "
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_cli"] - 1 ) * QT_POR_PAGINA_DEFAULT );

	}
        break;
    case "procurar_consultorias":
        if( ! tem_permissao( FUNC_REL_CONSULTORIA ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_cst", $busca_pagina_num_cst );

        extract_request_var( "busca_status", $busca_status );
        extract_request_var( "busca_tpj_id", $busca_tpj_id );
        extract_request_var( "busca_prf_id", $busca_prf_id );
        extract_request_var( "busca_mem_id", $busca_mem_id );
        extract_request_var( "busca_dia_de",  $busca_dia_de );
        extract_request_var( "busca_mes_de",  $busca_mes_de );
        extract_request_var( "busca_ano_de",  $busca_ano_de );
        extract_request_var( "busca_dia_ate", $busca_dia_ate );
        extract_request_var( "busca_mes_ate", $busca_mes_ate );
        extract_request_var( "busca_ano_ate", $busca_ano_ate );

        if( isset( $forcar_busca ) && $forcar_busca == "true"  )
        {
            $data_valida_de = false;
            $data_valida_ate = false;

            if( checkdate( $busca_mes_de, $busca_dia_de, $busca_ano_de ) )
            {
                $data_valida_de = true;
            }

            if( checkdate( $busca_mes_ate, $busca_dia_ate, $busca_ano_ate ) )
            {
                $data_valida_ate = true;
            }

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] = 
                ( $busca_status != "" ? " AND cst_status = '" . $busca_status . "'" : "" ) .
                ( consis_inteiro( $busca_tpj_id ) ? " AND cst_id IN( SELECT DISTINCT cst_id FROM cst_tpj WHERE tpj_id = '" . $busca_tpj_id . "' )" : "" ) .
                ( consis_inteiro( $busca_prf_id ) ? " AND cst_id IN( SELECT DISTINCT cst_id FROM cst_prf WHERE prf_id = '" . $busca_prf_id . "' )" : "" ) .
                ( consis_inteiro( $busca_mem_id ) ? " AND cst_id IN( SELECT DISTINCT cst_id FROM cst_mem WHERE mem_id = '" . $busca_mem_id . "' )" : "" ) .
                ( $data_valida_de  ? " AND cst_dt_contato >= '" . $busca_ano_de  . "-" . $busca_mes_de  . "-" . $busca_dia_de  . "'" : "" ) .
                ( $data_valida_ate ? " AND cst_dt_contato <= '" . $busca_ano_ate . "-" . $busca_mes_ate . "-" . $busca_dia_ate . "'" : "" );


            print msex_r( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ] );

            if( $data_valida_de )
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] = $busca_dia_de;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_de' ] = $busca_mes_de;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_de' ] = $busca_ano_de;
            }
            else
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_de' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_de' ] = '';
            }

            if( $data_valida_ate )
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_ate' ] = $busca_dia_ate;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_ate' ] = $busca_mes_ate;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_ate' ] = $busca_ano_ate;
            }
            else
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_ate' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_ate' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_ate' ] = '';
            }

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'status' ] = $busca_status;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] = $busca_tpj_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'prf_id' ] = $busca_prf_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mem_id' ] = $busca_mem_id;

            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] ) )
        {                
	    $count_cst = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                ( 
                    consultoria
                    NATURAL JOIN cliente
                ) a
                LEFT JOIN membro_todos b ON( a.cst_prp_coordenador = b.mem_id )
            WHERE
                cst_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] );

	    $n_cst =  $count_cst[ 'quantidade' ];
	    $list_data['qt_paginas_cst'] = ceil( $n_cst / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ] : 1 );
	    if( $busca_pagina_num_cst != "" )
	    {
		$list_data["pagina_num_cst"] = $busca_pagina_num_cst;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ] = $busca_pagina_num_cst;
	    }
	    else
		$list_data["pagina_num_cst"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'consultorias' ];	
	    if( $list_data["pagina_num_cst"] > $list_data['qt_paginas_cst'] )
		$list_data["pagina_num_cst"] = $list_data['qt_paginas_cst'];
	    if(  $list_data["pagina_num_cst"] <= 0 )
		$list_data["pagina_num_cst"] = 1;

	    $busca_consultorias = $sql->query( "
            SELECT DISTINCT
                cst_id,
                cst_prp_coordenador,
                cst_nome,
                cst_status,
                cst_valor,
                cli_nome,
                mem_nome
            FROM
                ( 
                    consultoria
                    NATURAL JOIN cliente
                ) a
                LEFT JOIN membro_todos b ON( a.cst_prp_coordenador = b.mem_id )
            WHERE
                cst_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] . "
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_cst"] - 1 ) * QT_POR_PAGINA_DEFAULT );

	}
        break;
    case "procurar_eventos":
        if( ! tem_permissao( FUNC_REL_EVENTO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_evt", $busca_pagina_num_evt );

        extract_request_var( "busca_campo_tev_id", $busca_campo_tev_id );
        extract_request_var( "busca_campo_prf_id", $busca_campo_prf_id );
        extract_request_var( "busca_campo_pat_id", $busca_campo_pat_id );
        extract_request_var( "busca_campo_dia_de", $busca_campo_dia_de );
        extract_request_var( "busca_campo_mes_de", $busca_campo_mes_de );
        extract_request_var( "busca_campo_ano_de", $busca_campo_ano_de );
        extract_request_var( "busca_campo_dia_ate", $busca_campo_dia_ate );
        extract_request_var( "busca_campo_mes_ate", $busca_campo_mes_ate );
        extract_request_var( "busca_campo_ano_ate", $busca_campo_ano_ate );
        extract_request_var( "busca_campo_aluno_gv", $busca_campo_aluno_gv );
        extract_request_var( "busca_campo_aluno_ngv", $busca_campo_aluno_ngv );

        extract_request_var( "busca_texto_aluno_gv", $busca_texto_aluno_gv );
        extract_request_var( "busca_texto_aluno_ngv", $busca_texto_aluno_ngv );

        if( isset( $forcar_busca ) && $forcar_busca == "true" )
        {
            $data_valida_de = false;
            $data_valida_ate = false;

            if( checkdate( $busca_campo_mes_de, $busca_campo_dia_de, $busca_campo_ano_de ) )
            {
                $data_valida_de = true;
            }

            if( checkdate( $busca_campo_mes_ate, $busca_campo_dia_ate, $busca_campo_ano_ate ) )
            {
                $data_valida_ate = true;
            }

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'query_where' ] = 
                ( consis_inteiro( $busca_campo_tev_id ) ? " AND tev_id = '" . $busca_campo_tev_id . "'" : "" ) .
                ( consis_inteiro( $busca_campo_prf_id ) ? " AND evt_id IN( SELECT DISTINCT evt_id FROM evt_prf WHERE prf_id = '" . $busca_campo_prf_id . "' )" : "" ) .
                ( consis_inteiro( $busca_campo_pat_id ) ? " AND evt_id IN( SELECT DISTINCT evt_id FROM evt_pat WHERE pat_id = '" . $busca_campo_pat_id . "' )" : "" ) .
                ( ( $busca_campo_aluno_gv  != '' && $busca_texto_aluno_gv  != '' ) || ( $busca_texto_aluno_gv = '' && false )  ? " AND evt_id IN( SELECT DISTINCT evt_id FROM inscrito_gv NATURAL JOIN aluno_gv WHERE " . $busca_campo_aluno_gv . " ILIKE '%" . $busca_texto_aluno_gv . "%' )" : "" ) .
                ( ( $busca_campo_aluno_ngv != '' && $busca_texto_aluno_ngv != '' ) || ( $busca_texto_aluno_ngv = '' && false ) ? " AND evt_id IN( SELECT DISTINCT evt_id FROM inscrito_ngv NATURAL JOIN aluno_nao_gv WHERE " . $busca_campo_aluno_ngv . " ILIKE '%" . $busca_texto_aluno_ngv . "%' )" : "" ) .
                ( $data_valida_de  ? " AND evt_dt >= '" . $busca_campo_ano_de  . "-" . $busca_campo_mes_de . "-" . $busca_campo_dia_de . "'" : "" ) .
                ( $data_valida_ate ? " AND evt_dt <= '" . $busca_campo_ano_ate . "-" . $busca_campo_mes_ate . "-" . $busca_campo_dia_ate . "'" : "" );

            /* shub */
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'tev_id' ] = $busca_campo_tev_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'prf_id' ] = $busca_campo_prf_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'pat_id' ] = $busca_campo_pat_id;

            if( $data_valida_de )
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_de' ] = $busca_campo_dia_de;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_de' ] = $busca_campo_mes_de;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_de' ] = $busca_campo_ano_de;
            }
            else
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_de' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_de' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_de' ] = '';
            }

            if( $data_valida_ate )
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_ate' ] = $busca_campo_dia_ate;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_ate' ] = $busca_campo_mes_ate;
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_ate' ] = $busca_campo_ano_ate;
            }
            else
            {
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_ate' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_ate' ] = '';
                $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_ate' ] = '';
            }

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_gv' ]  = $busca_campo_aluno_gv;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_ngv' ] = $busca_campo_aluno_ngv;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_gv' ]  = $busca_texto_aluno_gv;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_ngv' ] = $busca_texto_aluno_ngv;
                
            unset( $forcar_busca );
        }        
        
        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'query_where' ] ) )
        {
            $count_evt = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                evento
                NATURAL JOIN tipo_evento
            WHERE
                tev_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'query_where' ] );

	    $n_evt =  $count_evt[ 'quantidade' ];
	    $list_data['qt_paginas_evt'] = ceil( $n_evt / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ] : 1 );
	    if( $busca_pagina_num_evt != "" )
	    {
		$list_data["pagina_num_evt"] = $busca_pagina_num_evt;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ] = $busca_pagina_num_evt;
	    }
	    else
		$list_data["pagina_num_evt"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos' ];	
	    if( $list_data["pagina_num_evt"] > $list_data['qt_paginas_evt'] )
		$list_data["pagina_num_evt"] = $list_data['qt_paginas_evt'];
	    if(  $list_data["pagina_num_evt"] <= 0 )
		$list_data["pagina_num_evt"] = 1;
	    
	    $busca_eventos = $sql->query( "
            SELECT DISTINCT
                evt_id,
                evt_edicao,
                evt_local,
                date_part( 'epoch', evt_dt ) AS evt_timestamp,
                tev_nome
            FROM
                evento
                NATURAL JOIN tipo_evento
            WHERE
                tev_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'query_where' ] . "
            ORDER BY
                tev_nome,
                evt_edicao
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_evt"] - 1 ) * QT_POR_PAGINA_DEFAULT );

	}
        break;
    case "procurar_membros_exmembros":
        if( ! tem_permissao( FUNC_REL_MEMBRO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "membros_exmembros_nome", $membros_exmembros_nome );
        extract_request_var( "membros_exmembros_semestre_entrada_gv", $membros_exmembros_semestre_entrada_gv );
        extract_request_var( "membros_exmembros_ano_entrada_gv", $membros_exmembros_ano_entrada_gv );
        extract_request_var( "membros_exmembros_semestre_entrada_ej", $membros_exmembros_semestre_entrada_ej );
        extract_request_var( "membros_exmembros_ano_entrada_ej", $membros_exmembros_ano_entrada_ej );
        extract_request_var( "membros_exmembros_mes_nasci", $membros_exmembros_mes_nasci );
        extract_request_var( "membros_exmembros_dia_nasci", $membros_exmembros_dia_nasci );
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_mex", $busca_pagina_num_mex );

	if( isset( $forcar_busca ) && $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'query_where' ] = 
                ( $membros_exmembros_nome != "" ? "AND agv_nome ILIKE '%" . $membros_exmembros_nome . "%' " : "" ) .
                ( $membros_exmembros_ano_entrada_gv != "" ? "AND SUBSTR( agv_matricula, 3, 2 ) = '" . substr( $membros_exmembros_ano_entrada_gv, 2, 2 ) . "' " : "" ) .
                ( $membros_exmembros_semestre_entrada_gv != "" ? "AND SUBSTR( agv_matricula, 5, 1 ) = '" . $membros_exmembros_semestre_entrada_gv . "' " : "" ) .
                ( $membros_exmembros_semestre_entrada_ej != "" ? "AND date_part( 'month', mem_dt_entrada )::int / 7 + 1 = '" . $membros_exmembros_semestre_entrada_ej . "'" : "" ) .
                ( $membros_exmembros_ano_entrada_ej != "" ? "AND date_part( 'year', mem_dt_entrada ) = '" . $membros_exmembros_ano_entrada_ej . "'" : "" ) .
                ( $membros_exmembros_dia_nasci != "" ? "AND date_part( 'day', agv_dt_nasci ) = '" . $membros_exmembros_dia_nasci . "'" : "" ) .
                ( $membros_exmembros_mes_nasci != "" ? "AND date_part( 'month', agv_dt_nasci ) = '" . $membros_exmembros_mes_nasci . "'" : "" );

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_nome' ] =
                $membros_exmembros_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_gv' ] =
                $membros_exmembros_semestre_entrada_gv;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_gv' ] =
                $membros_exmembros_ano_entrada_gv;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_ej' ] =
                $membros_exmembros_semestre_entrada_ej;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_ej' ] =
                $membros_exmembros_ano_entrada_ej;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_mes_nasci' ] =
                $membros_exmembros_mes_nasci;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_dia_nasci' ] =
                $membros_exmembros_dia_nasci;
                
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'query_where' ] ) )
        {
            $count_mex = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                membro
                NATURAL JOIN aluno_gv
            WHERE
                mem_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'query_where' ] );

	    $n_mex =  $count_mex[ 'quantidade' ];
	    $list_data['qt_paginas_mex'] = ceil( $n_mex / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ] : 1 );
	    if( $busca_pagina_num_mex != "" )
	    {
		$list_data["pagina_num_mex"] = $busca_pagina_num_mex;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ] = $busca_pagina_num_mex;
	    }
	    else
		$list_data["pagina_num_mex"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'membros_exmembros' ];	
	    if( $list_data["pagina_num_mex"] > $list_data['qt_paginas_mex'] )
		$list_data["pagina_num_mex"] = $list_data['qt_paginas_mex'];
	    if(  $list_data["pagina_num_mex"] <= 0 )
		$list_data["pagina_num_mex"] = 1;	    

	    $busca_membros_exmembros = $sql->query( "
            SELECT DISTINCT
                mem_id,
                mem_dt_entrada ,
                agv_nome,
                agv_matricula,
                agv_ddd,
                agv_ddi,
                agv_telefone,
                agv_email
            FROM
                membro
                NATURAL JOIN aluno_gv
            WHERE
                mem_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'query_where' ] . "
            ORDER BY
                agv_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_mex"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}        
        break;
    case "procurar_empresas_juniores":
        if( ! tem_permissao( FUNC_REL_EMPRESA_JUNIOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "empresas_juniores_nome", $empresas_juniores_nome );
        extract_request_var( "empresas_juniores_cidade", $empresas_juniores_cidade );
        extract_request_var( "empresas_juniores_estado", $empresas_juniores_estado );
        extract_request_var( "empresas_juniores_rel_estreita", $empresas_juniores_rel_estreita );
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_eju", $busca_pagina_num_eju );

        if( isset( $forcar_busca ) && $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'query_where' ] = 
                " AND eju_nome   ILIKE '%" . $empresas_juniores_nome   . "%'" . 
                " AND eju_cidade ILIKE '%" . $empresas_juniores_cidade . "%'" . 
                " AND eju_estado ILIKE '%" . $empresas_juniores_estado . "%'" .
                ( $empresas_juniores_rel_estreita == 2 ? "" : ( $empresas_juniores_rel_estreita == 0 ? " AND eju_rel_estreita = '0'" : " AND eju_rel_estreita = '1'" ) ); 

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'nome' ] =
                $empresas_juniores_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'cidade' ] =
                $empresas_juniores_cidade;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] =
                $empresas_juniores_estado;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'rel_estreita' ] =
                $empresas_juniores_rel_estreita;
            
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'query_where' ] ) )
        {
            $count_eju = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                empresa_junior
            WHERE
                eju_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'query_where' ] );

	    $n_eju =  $count_eju[ 'quantidade' ];
	    $list_data['qt_paginas_eju'] = ceil( $n_eju / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ] : 1 );
	    if( $busca_pagina_num_eju != "" )
	    {
		$list_data["pagina_num_eju"] = $busca_pagina_num_eju;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ] = $busca_pagina_num_eju;
	    }
	    else
		$list_data["pagina_num_eju"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'empresas_juniores' ];	
	    if( $list_data["pagina_num_eju"] > $list_data['qt_paginas_eju'] )
		$list_data["pagina_num_eju"] = $list_data['qt_paginas_eju'];
	    if(  $list_data["pagina_num_eju"] <= 0 )
		$list_data["pagina_num_eju"] = 1;	    
	    
	    $busca_empresas_juniores = $sql->query( "
            SELECT DISTINCT
                eju_id,
                eju_nome,
                eju_endereco,
                eju_bairro,
                eju_nome_contato,
                eju_ddd,
                eju_ddi,
                eju_telefone
            FROM
                empresa_junior
            WHERE
                eju_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'query_where' ] . "
            ORDER BY
                eju_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_eju"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_fornecedores":
        if( ! tem_permissao( FUNC_REL_FORNECEDOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "fornecedores_nome", $fornecedores_nome );
        extract_request_var( "fornecedores_ramo_id", $fornecedores_ramo_id );
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_for", $busca_pagina_num_for );
	
        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'query_where' ] = 
                ( $fornecedores_nome != "" ? "AND for_nome ILIKE '%" . $fornecedores_nome . "%'" : "" );
                ( $fornecedores_ramo_id != "" ? "AND ram_id = '" . $fornecedores_ramo_id . "'" : "" );

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'nome' ] =
                $fornecedores_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'ramo' ] =
                $fornecedores_ramo_id;
            
            unset( $forcar_busca );
        }
        
        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'query_where' ] ) )
        {
            $count_for = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                fornecedor
            WHERE
                for_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'query_where' ] );

	    $n_for =  $count_for[ 'quantidade' ];
	    $list_data['qt_paginas_for'] = ceil( $n_for / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ] : 1 );
	    if( $busca_pagina_num_for != "" )
	    {
		$list_data["pagina_num_for"] = $busca_pagina_num_for;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ] = $busca_pagina_num_for;
	    }
	    else
		$list_data["pagina_num_for"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'fornecedores' ];	
	    if( $list_data["pagina_num_for"] > $list_data['qt_paginas_for'] )
		$list_data["pagina_num_for"] = $list_data['qt_paginas_for'];
	    if(  $list_data["pagina_num_for"] <= 0 )
		$list_data["pagina_num_for"] = 1;	
	    
	    $busca_fornecedores = $sql->query( "
            SELECT DISTINCT
                for_id,
                for_nome,
                for_servicos,
                for_nome_contato,
                for_ddd,
                for_ddi,
                for_telefone
            FROM
                fornecedor
            WHERE
                for_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'query_where' ] . "
            ORDER BY
                for_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_for"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_patrocinadores":
        if( ! tem_permissao( FUNC_REL_PATROCINADOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "patrocinadores_nome", $patrocinadores_nome );
        extract_request_var( "patrocinadores_setor_id", $patrocinadores_setor_id );
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_pat", $busca_pagina_num_pat );
	
        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'query_where' ] = 
                ( $patrocinadores_nome != "" ? "AND pat_nome ILIKE '%" . $patrocinadores_nome . "%'" : "" ) .
                ( $patrocinadores_setor_id != "" ? "AND set_id = '" . $patrocinadores_setor_id . "'" : "" );

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'nome' ] =
                $patrocinadores_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'setor' ] =
                $patrocinadores_setor_id;
            
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'query_where' ] ) )
        {                
            $count_pat = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                patrocinador
                NATURAL LEFT JOIN setor
                NATURAL LEFT JOIN pat_class
            WHERE
                pat_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'query_where' ] );

	    $n_pat =  $count_pat[ 'quantidade' ];
	    $list_data['qt_paginas_pat'] = ceil( $n_pat / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ] : 1 );
	    if( $busca_pagina_num_pat != "" )
	    {
		$list_data["pagina_num_pat"] = $busca_pagina_num_pat;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ] = $busca_pagina_num_pat;
	    }
	    else
		$list_data["pagina_num_pat"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'patrocinadores' ];	
	    if( $list_data["pagina_num_pat"] > $list_data['qt_paginas_pat'] )
		$list_data["pagina_num_pat"] = $list_data['qt_paginas_pat'];
	    if(  $list_data["pagina_num_pat"] <= 0 )
		$list_data["pagina_num_pat"] = 1;	
	    
	    $busca_patrocinadores = $sql->query( "
            SELECT DISTINCT
                set_nome,
                cla_nome,
                pat_id,
                pat_nome,
                pat_nome_contato,
                pat_ddi,
                pat_ddd,
                pat_telefone
            FROM
                patrocinador
                NATURAL LEFT JOIN setor
                NATURAL LEFT JOIN pat_class
            WHERE
                pat_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'query_where' ] . "
            ORDER BY
                pat_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_pat"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_palestrantes":
        if( ! tem_permissao( FUNC_REL_PALESTRANTE ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        extract_request_var( "palestrantes_nome", $palestrantes_nome );
        extract_request_var( "palestrantes_cargo_id", $palestrantes_cargo_id );
        extract_request_var( "forcar_busca", $forcar_busca );
	extract_request_var( "busca_pagina_num_pal", $busca_pagina_num_pal );
	
        $palestrantes_cargo_id = (int) $palestrantes_cargo_id;
        
        if( isset( $forcar_busca ) && $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'query_where' ] = 
                ( $palestrantes_nome != "" ? "AND pal_nome ILIKE '%" . $palestrantes_nome . "%'" : "" );
                ( $palestrantes_cargo_id != "" ? "AND pal_cargo = '" . $palestrantes_cargo_id . "'" : "" ) .

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'nome' ] =
                $palestrantes_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'cargo_ext' ] =
                $palestrantes_cargo_id;
            
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'query_where' ] ) )
        {                
	    $count_pal = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                palestrante p
                LEFT JOIN cargo_ext c ON( p.pal_cargo = c.cex_id )
            WHERE
                pal_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'query_where' ] );

	    $n_pal =  $count_pal[ 'quantidade' ];
	    $list_data['qt_paginas_pal'] = ceil( $n_pal / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ] : 1 );
	    if( $busca_pagina_num_pal != "" )
	    {
		$list_data["pagina_num_pal"] = $busca_pagina_num_pal;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ] = $busca_pagina_num_pal;
	    }
	    else
		$list_data["pagina_num_pal"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'palestrantes' ];	
	    if( $list_data["pagina_num_pal"] > $list_data['qt_paginas_pal'] )
		$list_data["pagina_num_pal"] = $list_data['qt_paginas_pal'];
	    if(  $list_data["pagina_num_pal"] <= 0 )
		$list_data["pagina_num_pal"] = 1;		    

	    $busca_palestrantes = $sql->query( "
            SELECT DISTINCT
                cex_nome,
                pal_nome,
                pal_cargo,
                pal_nome_contato,
                pal_ddd,
                pal_ddi,
                pal_telefone,
                pal_email
            FROM
                palestrante p
                LEFT OUTER JOIN cargo_ext c ON( p.pal_cargo = c.cex_id )
            WHERE
                pal_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'query_where' ] . "
            ORDER BY
                pal_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_pal"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_professores":
        if( ! tem_permissao( FUNC_REL_PROFESSOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "professores_nome", $professores_nome );
        extract_request_var( "professores_departamento_id", $professores_departamento_id );
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "professores_dia_nasci", $professores_dia_nasci );
        extract_request_var( "professores_mes_nasci", $professores_mes_nasci );
	extract_request_var( "busca_pagina_num_prf", $busca_pagina_num_prf );
	
        $professores_departamento_id = (int) $professores_departamento_id;

        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'query_where' ] = 
                ( $professores_nome != "" ? "AND prf_nome ILIKE '%" . $professores_nome . "%' " : "" ) .
                ( $professores_departamento_id != "" && is_integer( $professores_departamento_id ) ? "AND dpt_id = '" . $professores_departamento_id . "' " : "" ) .
                ( $professores_dia_nasci != "" ? "AND date_part( 'day', prf_dt_nasci ) = '" . $professores_dia_nasci . "' " : "" ) .
                ( $professores_mes_nasci != "" ? "AND date_part( 'month', prf_dt_nasci ) = '" . $professores_mes_nasci . "' " : "" );

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'nome' ] =
                $professores_nome;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'departamento' ] =
                $professores_departamento_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_dia_nasci' ] =
                $professores_dia_nasci;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_mes_nasci' ] =
                $professores_mes_nasci;

            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'query_where' ] ) )
        {                
            $count_prf = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                professor
                NATURAL JOIN
                departamento
            WHERE
                prf_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'query_where' ] );

	    $n_prf =  $count_prf[ 'quantidade' ];
	    $list_data['qt_paginas_prf'] = ceil( $n_prf / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ] : 1 );
	    if( $busca_pagina_num_prf != "" )
	    {
		$list_data["pagina_num_prf"] = $busca_pagina_num_prf;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ] = $busca_pagina_num_prf;
	    }
	    else
		$list_data["pagina_num_prf"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'professores' ];	
	    if( $list_data["pagina_num_prf"] > $list_data['qt_paginas_prf'] )
		$list_data["pagina_num_prf"] = $list_data['qt_paginas_prf'];
	    if(  $list_data["pagina_num_prf"] <= 0 )
		$list_data["pagina_num_prf"] = 1;	
	    
	    $busca_professores = $sql->query( "
            SELECT DISTINCT
                dpt_nome,
                prf_id,
                prf_nome,
                date_part( 'epoch', prf_dt_nasci ) AS prf_nasci_timestamp,
                prf_ddd,
                prf_ddi,
                prf_telefone,
                prf_email
            FROM
                professor
                NATURAL JOIN
                departamento
            WHERE
                prf_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'query_where' ] . "
            ORDER BY
                prf_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_prf"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_alunos_gv":
        if( ! tem_permissao( FUNC_REL_ALUNO_GV ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "alunos_gv_curso_classe", $alunos_gv_curso_classe );
        extract_request_var( "alunos_gv_ano_entrada", $alunos_gv_ano_entrada );
        extract_request_var( "alunos_gv_semestre_entrada", $alunos_gv_semestre_entrada );
	extract_request_var( "busca_pagina_num_agv", $busca_pagina_num_agv );
	
        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'query_where' ] = 
                ( $alunos_gv_curso_classe != "" ? "AND SUBSTR( agv_matricula, 1, 2 ) = '" . $alunos_gv_curso_classe . "' " : "" ) .
                ( $alunos_gv_ano_entrada != "" ? "AND SUBSTR( agv_matricula, 3, 2 ) = '" . substr( $alunos_gv_ano_entrada, 2, 2 ) . "' " : "" ) .
                ( $alunos_gv_semestre_entrada != "" ? "AND SUBSTR( agv_matricula, 5, 1 ) = '" . $alunos_gv_semestre_entrada . "' " : "" );
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] =
                $alunos_gv_curso_classe;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_ano_entrada' ] =
                $alunos_gv_ano_entrada;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_semestre_entrada' ] =
                $alunos_gv_semestre_entrada;

            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'query_where' ] ) )
        {                
            $count_agv = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                aluno_gv
            WHERE
                agv_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'query_where' ] );

	    $n_agv =  $count_agv[ 'quantidade' ];
	    $list_data['qt_paginas_agv'] = ceil( $n_agv / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ] : 1 );
	    if( $busca_pagina_num_agv != "" )
	    {
		$list_data["pagina_num_agv"] = $busca_pagina_num_agv;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ] = $busca_pagina_num_agv;
	    }
	    else
		$list_data["pagina_num_agv"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_gv' ];	
	    if( $list_data["pagina_num_agv"] > $list_data['qt_paginas_agv'] )
		$list_data["pagina_num_agv"] = $list_data['qt_paginas_agv'];
	    if(  $list_data["pagina_num_agv"] <= 0 )
		$list_data["pagina_num_agv"] = 1;	
	    
	    $busca_alunos_gv = $sql->query( "
            SELECT DISTINCT
                SUBSTR( agv_matricula, 1, 2 ) AS agv_curso,
                SUBSTR( agv_matricula, 3, 2 ) AS agv_ano_entrada,
                SUBSTR( agv_matricula, 5, 1 ) AS agv_semestre_entrada,
                agv_nome,
                agv_matricula,
                agv_ddd,
                agv_ddi,
                agv_telefone,
                agv_email
            FROM
                aluno_gv
            WHERE
                agv_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'query_where' ] . "
            ORDER BY
                agv_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_agv"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_alunos_nao_gv":
        if( ! tem_permissao( FUNC_REL_ALUNO_NAO_GV ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "alunos_nao_gv_evento_id", $alunos_nao_gv_evento_id );
	extract_request_var( "busca_pagina_num_ang", $busca_pagina_num_ang );
	
        if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] = 
                ( $alunos_nao_gv_evento_id != "" ? "AND evt_id = '" . $alunos_nao_gv_evento_id . "' " : "" );
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'alunos_nao_gv_evento_id' ] =
                $alunos_nao_gv_evento_id;

            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] ) )
        {                
            if( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] != "" )
                $where = "inscrito_ngv NATURAL JOIN aluno_nao_gv";
            else
                $where = "aluno_nao_gv";

            $count_ang = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                $where
            WHERE
                ang_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] );

	    $n_ang =  $count_ang[ 'quantidade' ];
	    $list_data['qt_paginas_ang'] = ceil( $n_ang / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ] : 1 );
	    if( $busca_pagina_num_ang != "" )
	    {
		$list_data["pagina_num_ang"] = $busca_pagina_num_ang;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ] = $busca_pagina_num_ang;
	    }
	    else
		$list_data["pagina_num_ang"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'alunos_nao_gv' ];	
	    if( $list_data["pagina_num_ang"] > $list_data['qt_paginas_ang'] )
		$list_data["pagina_num_ang"] = $list_data['qt_paginas_ang'];
	    if(  $list_data["pagina_num_ang"] <= 0 )
		$list_data["pagina_num_ang"] = 1;	
	    
            $busca_alunos_nao_gv = $sql->query( "
            SELECT DISTINCT
                ang_nome,
                ang_telefone,
                ang_ddd,
                ang_ddi,
                ang_faculdade,
                ang_curso,
                ang_email
            FROM
                $where
            WHERE
                ang_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] . "
            ORDER BY
                ang_nome
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_ang"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
    case "procurar_timesheets":
        if( ! tem_permissao( FUNC_REL_TIMESHEET ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "timesheets_dia_de", $timesheets_dia_de );
        extract_request_var( "timesheets_mes_de", $timesheets_mes_de );
        extract_request_var( "timesheets_ano_de", $timesheets_ano_de );
        extract_request_var( "timesheets_dia_ate", $timesheets_dia_ate );
        extract_request_var( "timesheets_mes_ate", $timesheets_mes_ate );
        extract_request_var( "timesheets_ano_ate", $timesheets_ano_ate );
	extract_request_var( "timesheets_area_id", $timesheets_area_id );
        extract_request_var( "timesheets_atividade_id", $timesheets_atividade_id );
        extract_request_var( "timesheets_empresa_id", $timesheets_empresa_id );
        extract_request_var( "timesheets_evento_id", $timesheets_evento_id );
        extract_request_var( "timesheets_projeto_interno_id", $timesheets_projeto_interno_id );
        extract_request_var( "timesheets_subatividade_id", $timesheets_subatividade_id );
        extract_request_var( "timesheets_membro_id", $timesheets_membro_id );
	extract_request_var( "busca_pagina_num_tsh", $busca_pagina_num_tsh );

	$data_de = $timesheets_ano_de . "-" .  $timesheets_mes_de . "-" .  $timesheets_dia_de;
	$data_ate = $timesheets_ano_ate . "-" .  $timesheets_mes_ate . "-" .  $timesheets_dia_ate;

        if( isset( $forcar_busca ) &&
	    $forcar_busca == "true" &&
	    checkdate( $timesheets_mes_de, $timesheets_dia_de, $timesheets_ano_de ) &&
	    checkdate( $timesheets_mes_ate, $timesheets_dia_ate, $timesheets_ano_ate ) )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] = 
		"AND tsh_dt BETWEEN '" . $data_de . "' AND '" . $data_ate  . "'" . 
		( $timesheets_area_id != "" ? "AND are_id = '" . $timesheets_area_id . "'" : "" ) .
                ( $timesheets_atividade_id != "" ? "AND tat_id = '" . $timesheets_atividade_id . "'" : "" ) .
                ( $timesheets_empresa_id != "" ? "AND t.cli_id = '" . $timesheets_empresa_id . "'" : "" ) .
                ( $timesheets_evento_id != "" ? "AND evt_id = '" . $timesheets_evento_id . "'" : "" ) .
                ( $timesheets_projeto_interno_id != "" ? "AND pin_id = '" . $timesheets_projeto_interno_id . "'" : "" ) .
                ( $timesheets_subatividade_id != "" ? "AND tsa_id = '" . $timesheets_subatividade_id . "'" : "" ) .
                ( $timesheets_membro_id != "" ? "AND mem_id = '" . $timesheets_membro_id . "'" : "" );

            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_de' ] =
                $timesheets_dia_de;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_de' ] =
                $timesheets_mes_de;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_de' ] =
                $timesheets_ano_de;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_ate' ] =
                $timesheets_dia_ate;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_ate' ] =
                $timesheets_mes_ate;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_ate' ] =
                $timesheets_ano_ate;
	    $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_area_id' ] =
                $timesheets_area_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_atividade_id' ] =
                $timesheets_atividade_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_empresa_id' ] =
                $timesheets_empresa_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_evento_id' ] =
                $timesheets_evento_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_projeto_interno_id' ] =
                $timesheets_projeto_interno_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_subatividade_id' ] =
                $timesheets_subatividade_id;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_membro_id' ] =
                $timesheets_membro_id;
                
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] ) )
        {                
            $count_tsh = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                (
                    timesheet
                    NATURAL JOIN area
                    NATURAL JOIN ts_atividade
                    NATURAL JOIN membro_vivo
                    NATURAL LEFT JOIN evento
                    NATURAL LEFT JOIN tipo_evento
                    NATURAL LEFT JOIN ts_subatividade
                    NATURAL LEFT JOIN cliente
                    NATURAL LEFT JOIN prj_interno
                ) t
                LEFT JOIN consultoria c ON ( t.cst_id = c.cst_id )
            WHERE
                mem_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] );

	    $n_tsh =  $count_tsh[ 'quantidade' ];
	    $list_data['qt_paginas_tsh'] = ceil( $n_tsh / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ] : 1 );
	    if( $busca_pagina_num_tsh != "" )
	    {
		$list_data["pagina_num_tsh"] = $busca_pagina_num_tsh;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ] = $busca_pagina_num_tsh;
	    }
	    else
		$list_data["pagina_num_tsh"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'timesheets' ];	
	    if( $list_data["pagina_num_tsh"] > $list_data['qt_paginas_tsh'] )
		$list_data["pagina_num_tsh"] = $list_data['qt_paginas_tsh'];
	    if(  $list_data["pagina_num_tsh"] <= 0 )
		$list_data["pagina_num_tsh"] = 1;	

	    $busca_timesheets = $sql->query( "
            SELECT DISTINCT
                t.are_id,
                t.tsa_id,
                t.cli_id,
                t.cst_id,
                t.evt_id,
                t.mem_id,
                t.mem_nome,
                t.tat_id,
                t.tsh_id,
                date_part( 'epoch', t.tsh_dt ) AS tsh_timestamp,
                t.tsh_duracao,
                t.tsh_texto,
                t.are_nome,
                t.mem_nome,
                t.tat_nome,
                t.tsa_nome,
                t.cli_nome,
                t.pin_nome,
                t.evt_edicao,
                t.tev_nome,
                c.cst_nome
            FROM
                ( timesheet NATURAL JOIN
                area NATURAL JOIN
                ts_atividade NATURAL JOIN
                membro_vivo NATURAL LEFT JOIN
                evento NATURAL LEFT JOIN
                tipo_evento NATURAL LEFT JOIN
                ts_subatividade NATURAL LEFT JOIN
                cliente NATURAL LEFT JOIN
                prj_interno ) t
                LEFT JOIN
                consultoria c
                ON ( t.cst_id = c.cst_id )
            WHERE
                mem_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] .
            " LIMIT " . QT_POR_PAGINA_DEFAULT . 
            " OFFSET  " . ( $list_data["pagina_num_tsh"] - 1 ) * QT_POR_PAGINA_DEFAULT );

            $busca_timesheets_total_horas = $sql->squery( "
            SELECT DISTINCT
                SUM( t.tsh_duracao ) AS total_horas
            FROM
                ( timesheet NATURAL JOIN
                area NATURAL JOIN
                ts_atividade NATURAL JOIN
                membro_vivo NATURAL LEFT JOIN
                evento NATURAL LEFT JOIN
                tipo_evento NATURAL LEFT JOIN
                ts_subatividade NATURAL LEFT JOIN
                cliente NATURAL LEFT JOIN
                prj_interno ) t
                LEFT JOIN
                consultoria c
                ON ( t.cst_id = c.cst_id )
            WHERE
                mem_id IS NOT NULL 
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] );
	}
        break;
    case "procurar_processos_seletivos":
        if( ! tem_permissao( FUNC_REL_P_SELETIVO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
        extract_request_var( "forcar_busca", $forcar_busca );
        extract_request_var( "processos_seletivos_semestre", $processos_seletivos_semestre );
        extract_request_var( "processos_seletivos_ano", $processos_seletivos_ano );
        extract_request_var( "processos_seletivos_status", $processos_seletivos_status );
	extract_request_var( "busca_pagina_num_psl", $busca_pagina_num_psl );
	
        if( isset( $forcar_busca ) && $forcar_busca == "true" )
        {
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] = 
                ( $processos_seletivos_semestre != "" ? "AND date_part( 'month', psl_dt_selecao )::int / 7 + 1  = '" . $processos_seletivos_semestre . "'" : "" ) .
                ( $processos_seletivos_ano != "" ? "AND date_part( 'year', psl_dt_selecao ) = '" . $processos_seletivos_ano . "'" : "" );
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_semestre' ] =
                $processos_seletivos_semestre;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_ano' ] =
                $processos_seletivos_ano;
            $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] =
                $processos_seletivos_status;
            
            unset( $forcar_busca );
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] ) )
        {
            $count_psl = $sql->squery( "
            SELECT DISTINCT
                COUNT( * ) AS quantidade
            FROM
                p_seletivo
            WHERE
                psl_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] );

	    $n_psl =  $count_psl[ 'quantidade' ];
	    $list_data['qt_paginas_psl'] = ceil( $n_psl / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ] : 1 );
	    if( $busca_pagina_num_psl != "" )
	    {
		$list_data["pagina_num_psl"] = $busca_pagina_num_psl;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ] = $busca_pagina_num_psl;
	    }
	    else
		$list_data["pagina_num_psl"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'processos_seletivos' ];	
	    if( $list_data["pagina_num_psl"] > $list_data['qt_paginas_psl'] )
		$list_data["pagina_num_psl"] = $list_data['qt_paginas_psl'];
	    if(  $list_data["pagina_num_psl"] <= 0 )
		$list_data["pagina_num_psl"] = 1;	
	    
	    $busca_processos_seletivos = $sql->query( "
            SELECT DISTINCT
                psl_id,
                date_part( 'epoch', psl_dt_selecao ) AS psl_timestamp
            FROM
                p_seletivo
            WHERE
                psl_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] . "
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_psl"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	}
        break;
     case "procurar_premio_gestao":
        if( ! tem_permissao( FUNC_REL_PREMIO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
	 extract_request_var( "forcar_busca", $forcar_busca );
	 extract_request_var( "evento_premio_gestao_id", $evento_premio_gestao_id );
         extract_request_var( "busca_pagina_num_epg", $busca_pagina_num_epg );
	 
	 if( isset( $forcar_busca ) &&  $forcar_busca == "true" )
	 {
	     $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'query_where' ] = 
		 ( $evento_premio_gestao_id != "" ? "AND evt_id = '" . $evento_premio_gestao_id . "' " : "" );
	     $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'evento_premio_gestao_id' ] =
		 $evento_premio_gestao_id;
	     
	     unset( $forcar_busca );
	 }
	 
	 if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'query_where' ] ) )
	 {
	    $count_epg = $sql->squery( "
        SELECT DISTINCT
            COUNT( * ) AS quantidade
        FROM
            evento NATURAL JOIN
            tipo_evento
        WHERE
            tev_id IN( SELECT DISTINCT tev_id FROM tipo_evento WHERE tev_mne = 'premio_gestao' )
            " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'query_where' ] );

	    $n_epg =  $count_epg[ 'quantidade' ];
	    $list_data['qt_paginas_epg'] = ceil( $n_epg / QT_POR_PAGINA_DEFAULT );
	    $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ] = ( isset( $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ] ) && $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ] != "" ? $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ] : 1 );
	    if( $busca_pagina_num_epg != "" )
	    {
		$list_data["pagina_num_epg"] = $busca_pagina_num_epg;
		$_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ] = $busca_pagina_num_epg;
	    }
	    else
		$list_data["pagina_num_epg"] = $_SESSION[ 'paginacao' ][ 'relatorios' ][ 'eventos_premio_gestao' ];	
	    if( $list_data["pagina_num_epg"] > $list_data['qt_paginas_epg'] )
		$list_data["pagina_num_epg"] = $list_data['qt_paginas_epg'];
	    if(  $list_data["pagina_num_epg"] <= 0 )
		$list_data["pagina_num_epg"] = 1;	
	    
	     $busca_eventos_pg = $sql->query( "
        SELECT DISTINCT
            tev_nome || ': ' ||evt_edicao AS nome_evento,
            evt_id,
            evt_edicao,
            date_part( 'epoch', evt_dt ) AS evt_timestamp
        FROM
            evento NATURAL JOIN
            tipo_evento
        WHERE
            tev_id IN( SELECT DISTINCT tev_id FROM tipo_evento WHERE tev_mne = 'premio_gestao' )
            " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'query_where' ] ."
        ORDER BY
            nome_evento
            LIMIT " . QT_POR_PAGINA_DEFAULT . "
            OFFSET  " . ( $list_data["pagina_num_epg"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	 }
	 break;
}
?>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="100%">
   <tr><td height="<?= ALTURA_PADRAO ?>" width="130" VALIGN="top" bgcolor="#336699"><br>

  <table border="0" CELLSPACING="0" CELLPADDING="3" bgColor="#000000" WIDTH="100%">
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=alunos_gv&acao=procurar_alunos_gv">Alunos GV</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=alunos_nao_gv&acao=procurar_alunos_nao_gv">Alunos Não GV</a><br />
   </td></tr>
   <tr><td bgcolor="#336699" valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=clientes&acao=procurar_clientes">Clientes</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=consultorias&acao=procurar_consultorias">Consultorias</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=empresas_juniores&acao=procurar_empresas_juniores">Empresas Juniores</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=eventos&acao=procurar_eventos">Eventos</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=fornecedores&acao=procurar_fornecedores">Fornecedores</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=membros_exmembros&acao=procurar_membros_exmembros">Membros e Ex-membros</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=palestrantes&acao=procurar_palestrantes">Palestrantes</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=patrocinadores&acao=procurar_patrocinadores">Patrocinadores</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=premio_gestao&acao=procurar_premio_gestao">Prêmio Gestão</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=processos_seletivos&acao=procurar_processos_seletivos">Processo Seletivo</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=professores&acao=procurar_professores">Professores</a><br />
   </td></tr>
   <tr><td bgcolor="#336699"  valign="top" width="130">
        <a class='lmenu' href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=relatorio&relatorio=timesheets&acao=procurar_timesheets">Timesheet</a><br />
   </td></tr>
  </table></td>
        
       
<td bgcolor="#FFFFFF" class="text" height="<?= ALTURA_PADRAO ?>" valign="top" WIDTH="650">        
<?
switch( $relatorio )
{
    case "clientes":
        $busca_regioes = $sql->query( "
        SELECT DISTINCT
            reg_id,
            reg_nome
        FROM
            regiao
        ORDER BY
            reg_nome" );

        $busca_ramos = $sql->query( "
        SELECT DISTINCT
            ram_id,
            ram_nome
        FROM
            ramo
        ORDER BY
            ram_nome" );

        $status_selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_status' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_status' ] : "" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Clientes</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="clientes" />
        <input type="hidden" name="acao" value="procurar_clientes" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="clientes_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Região
        <? faz_select( "clientes_regiao_id", $busca_regioes, "reg_id", "reg_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_regiao_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_regiao_id' ] : "" ), "", "true", "Todas as Regiões" ); ?>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Ramo 
        <? faz_select( "clientes_ramo_id", $busca_ramos, "ram_id", "ram_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_ramo_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_ramo_id' ] : "" ), "", "true", "Todos Ramos" ); ?>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Estado
        <select name="clientes_estado">
            <option value="">Todos os Estados</option>
            <option value="AC" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "AC" ? "selected" : "" ?>>Acre</option>
            <option value="AL" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "AL" ? "selected" : "" ?>>Alagoas</option>
            <option value="AM" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "AM" ? "selected" : "" ?>>Amazonas</option>
            <option value="AP" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "AP" ? "selected" : "" ?>>Amapá</option>
            <option value="BA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "BA" ? "selected" : "" ?>>Bahia</option>
            <option value="CE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "CE" ? "selected" : "" ?>>Ceará</option>
            <option value="DF" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "DF" ? "selected" : "" ?>>Distrito Federal</option>
            <option value="ES" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "ES" ? "selected" : "" ?>>Espírito Santo</option>
            <option value="GO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "GO" ? "selected" : "" ?>>Goiás</option>
            <option value="MA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "MA" ? "selected" : "" ?>>Maranhão</option>
            <option value="MG" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "MG" ? "selected" : "" ?>>Minas Gerais</option>
            <option value="MT" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "MT" ? "selected" : "" ?>>Mato Grosso</option>
            <option value="MS" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "MS" ? "selected" : "" ?>>Mato Grosso do Sul</option>
            <option value="PA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "PA" ? "selected" : "" ?>>Pára</option>
            <option value="PB" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "PB" ? "selected" : "" ?>>Paraíba</option>
            <option value="PE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "PE" ? "selected" : "" ?>>Pernambuco</option>
            <option value="PI" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "PI" ? "selected" : "" ?>>Piauí</option>
            <option value="PR" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "PR" ? "selected" : "" ?>>Paraná</option>
            <option value="RJ" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "RJ" ? "selected" : "" ?>>Rio de Janeiro</option>
            <option value="RN" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "RN" ? "selected" : "" ?>>Rio Grande do Norte</option>
            <option value="RO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "RO" ? "selected" : "" ?>>Rondônia</option>
            <option value="RR" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "RR" ? "selected" : "" ?>>Roraima</option>
            <option value="RS" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "RS" ? "selected" : "" ?>>Rio Grande do Sul</option>
            <option value="SC" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "SC" ? "selected" : "" ?>>Santa Catarina</option>
            <option value="SE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "SE" ? "selected" : "" ?>>Sergipe</option>
            <option value="SP" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "SP" ? "selected" : "" ?>>São Paulo</option>
            <option value="TO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'clientes_estado' ] == "TO" ? "selected" : "" ?>>Tocantins</option>
        </select>
        </td>
        <tr>
    <td bgcolor="#ffffff" class="text" colspan='4' align='center'>
    <input type="submit" value="Procurar" />
        
        </td></form></tr>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
    </tr>
    </table>
    </td></tr>
    </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="<?= ( isset( $busca_clientes ) && is_array( $busca_clientes ) ? "9" : "1" ) ?>"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Clientes - Resultados da busca</td>
        </tr>
    
        <?
        if( isset( $busca_clientes ) && is_array( $busca_clientes ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Razão</b></td>
            <td bgcolor="#ffffff" class="text"><b>Ramo</b></td>
            <td bgcolor="#ffffff" class="text"><b>Endereço</b></td>
            <td bgcolor="#ffffff" class="text"><b>Bairro</b></td>
            <td bgcolor="#ffffff" class="text"><b>Região</b></td>
            <td bgcolor="#ffffff" class="text"><b>Contato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>Consultorias</b></td>
            </tr>

            <?
            foreach( $busca_clientes as $tupla )
            {
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cli_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cli_razao' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ram_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cli_endereco' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cli_bairro' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'reg_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cli_nome_contato' ] ?></td>
                <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "cli_ddi" ] ) ? " (+" . $tupla[ "cli_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "cli_ddd" ] ) ? " ("  . $tupla[ "cli_ddd" ] . ")" : "" ) .
                        $tupla[ "cli_telefone" ] )
                    ?>
                </td>
                <td bgcolor='#ffffff' class="text">&nbsp;
                    <?
                        $consultorias = $sql->query( "SELECT cst_nome, cst_status FROM consultoria WHERE cli_id = '" . $tupla[ "cli_id" ] . "'" );

                        if( is_array( $consultorias ) )
                        {
                            foreach( $consultorias as $x )
                            {
                                print "<li>" . $x[ 'cst_nome' ] . " ( " . $x[ 'cst_status' ] . " )";
                            }
                        }

                        unset( $consultorias );
                    ?>
                </td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_cli'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="9" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_cli'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=clientes&acao=procurar_clientes&busca_pagina_num_cli=<?= ($list_data["pagina_num_cli"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_cli"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_cli"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=clientes&acao=procurar_clientes&busca_pagina_num_cli=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_cli'] > $list_data['pagina_num_cli'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=clientes&acao=procurar_clientes&busca_pagina_num_cli=<?= ($list_data["pagina_num_cli"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='9'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum cliente foi encontrado.</td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_clientes ) && is_array( $busca_clientes ) ? "9" : "1" ) ?>">&nbsp;</td>
        </tr>        
        </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "consultorias":
        $busca_membros = $sql->query( "
        SELECT DISTINCT
            mem_id,
            mem_nome
        FROM
            membro_vivo
        ORDER BY
            mem_nome" );
            
        $busca_professores = $sql->query( "
        SELECT DISTINCT
            prf_id,
            prf_nome
        FROM
            professor
        ORDER BY
            prf_nome" );

        $busca_tipos_projeto = $sql->query( "
        SELECT DISTINCT
            tpj_id,
            tpj_nome
        FROM
            tipo_projeto
        ORDER BY
            tpj_nome" );            
            
        $status_selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'status' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'status' ] : "" );
        $tipo_projeto_selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] : "" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="2" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Consultorias</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="consultorias" />
        <input type="hidden" name="acao" value="procurar_consultorias" />
        <input type="hidden" name="forcar_busca" value="true" />
        Status
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="busca_status">
            <option value="">Todos os Status</option>
            <option value="<?= CST_NOVA_CONSULTORIA ?>" <?= ( $status_selecionado == CST_NOVA_CONSULTORIA ? "selected" : "" )  ?>>Nova Consultoria</option>
            <option value="<?= CST_CONSULTORIA_NAO_CONFIRMADA ?>" <?= ( $status_selecionado == CST_CONSULTORIA_NAO_CONFIRMADA ? "selected" : "" )  ?>>Consultoria não Confirmada</option>
            <option value="<?= CST_REUNIAO_MARCADA ?>" <?= ( $status_selecionado == CST_REUNIAO_MARCADA ? "selected" : "" )  ?>>Reunião Marcada</option>
            <option value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" <?= ( $status_selecionado == CST_PROPOSTA_EM_ANDAMENTO ? "selected" : "" )  ?>>Proposta em Andamento</option>
            <option value="<?= CST_PROPOSTA_CONCLUIDA ?>" <?= ( $status_selecionado == CST_PROPOSTA_CONCLUIDA ? "selected" : "" )  ?>>Proposta Concluída</option>
            <option value="<?= CST_REUNIAO_NAO_GEROU_PROPOSTA ?>" <?= ( $status_selecionado == CST_REUNIAO_NAO_GEROU_PROPOSTA ? "selected" : "" )  ?>>Reunião não Gerou Proposta</option>
            <option value="<?= CST_PROPOSTA_ENVIADA ?>" <?= ( $status_selecionado == CST_PROPOSTA_ENVIADA ? "selected" : "" )  ?>>Proposta Enviada</option>
            <option value="<?= CST_STAND_BY ?>" <?= ( $status_selecionado == CST_STAND_BY ? "selected" : "" )  ?>>Stand By</option>
            <option value="<?= CST_FOLLOW_UP ?>" <?= ( $status_selecionado == CST_FOLLOW_UP ? "selected" : "" )  ?>>Follow Up</option>
            <option value="<?= CST_CONTRATO_EM_ANDAMENTO ?>" <?= ( $status_selecionado == CST_CONTRATO_EM_ANDAMENTO ? "selected" : "" )  ?>>Contrato em Andamento</option>
            <option value="<?= CST_PROJETO_EM_ANDAMENTO ?>" <?= ( $status_selecionado == CST_PROJETO_EM_ANDAMENTO ? "selected" : "" )  ?>>Projeto em Andamento</option>
            <option value="<?= CST_PROJETO_FINALIZADO ?>" <?= ( $status_selecionado == CST_PROJETO_FINALIZADO ? "selected" : "" )  ?>>Projeto Finalizado</option>
        </select></td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Tipo Projeto
        </td>
        <td bgcolor="#ffffff" class="text">
         <? faz_select( "busca_tpj_id", $busca_tipos_projeto, "tpj_id", "tpj_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'tpj_id' ] : "" ), "", "true", "Todos Tipos de Projeto" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Professor
        </td>
        <td bgcolor="#ffffff" class="text">
         <? faz_select( "busca_prf_id", $busca_professores, "prf_id", "prf_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'prf_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'prf_id' ] : "" ), "", "true", "Todos os Professores" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Membro
        </td>
        <td bgcolor="#ffffff" class="text">
         <? faz_select( "busca_mem_id", $busca_membros, "mem_id", "mem_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mem_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mem_id' ] : "" ), "", "true", "Todos os Membros" ); ?>        
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Data Contato
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="busca_dia_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_mes_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_de' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_ano_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_de' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        até 
        <select name="busca_dia_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'dia_ate' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_mes_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'mes_ate' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_ano_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'ano_ate' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="text" colspan="2" align='center'>
        <input type="submit" value="Procurar" />
        </td></tr></form>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="2">&nbsp;</td>
        </tr>
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_consultorias ) && is_array( $busca_consultorias ) ? "6" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Consultorias - Resultados da busca</td>
        </tr>    
    
        <?
        if( isset( $busca_consultorias ) && is_array( $busca_consultorias ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Status</b></td>
            <td bgcolor="#ffffff" class="text"><b>Cliente</b></td>
            <td bgcolor="#ffffff" class="text"><b>Coordenador</b></td>
            <td bgcolor="#ffffff" class="text"><b>Membros Envolvidos</b></td>
            <td bgcolor="#ffffff" class="text"><b>Professores</b></td>
            </tr>

            <?
            foreach( $busca_consultorias as $tupla )
            {
                $busca_professores_envolvidos = $sql->query( "
                SELECT DISTINCT
                    prf_nome
                FROM
                    professor
                WHERE
                    prf_id IN ( SELECT DISTINCT prf_id FROM cst_prf WHERE cst_id = '" . $tupla[ 'cst_id' ] . "' )
                ORDER BY
                    prf_nome" );
        
                $busca_membros_envolvidos = $sql->query( "
                SELECT DISTINCT
                    mem_nome
                FROM
                    membro_todos
                WHERE
                    mem_id IN( SELECT DISTINCT mem_id FROM cst_mem WHERE cst_id = '" . $tupla[ 'cst_id' ] . "' )
                ORDER BY
                    mem_nome" );                
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><?= $tupla[ 'cst_nome' ] ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text"><?= $tupla[ 'cst_status' ] ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text"><?= $tupla[ 'cli_nome' ] ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text"><?= $tupla[ 'mem_nome' ] ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_membros_envolvidos ) )
                {
                    foreach( $busca_membros_envolvidos as $membro_envolvido )
                    {
                    ?>
                        <?= $membro_envolvido[ 'mem_nome' ] . ( sizeof( $busca_membros_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_professores_envolvidos ) )
                {
                    foreach( $busca_professores_envolvidos as $professor_envolvido )
                    {
                    ?>
                        <?= $professor_envolvido[ 'prf_nome' ] ?><?= ( sizeof( $busca_professores_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_cst'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="7" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_cst'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=consultorias&acao=procurar_consultorias&busca_pagina_num_cst=<?= ($list_data["pagina_num_cst"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_cst"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_cst"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=consultorias&acao=procurar_consultorias&busca_pagina_num_cst=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_cst'] > $list_data['pagina_num_cst'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=consultorias&acao=procurar_consultorias&busca_pagina_num_cst=<?= ($list_data["pagina_num_cst"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhuma consultoria foi encontrada.</td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_consultorias ) && is_array( $busca_consultorias ) ? "6" : "1" ) ?>">&nbsp;</td>
        </tr>        
        </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "eventos":
        $busca_tipos_evento = $sql->query( "
        SELECT DISTINCT
            tev_id,
            tev_nome
        FROM
            tipo_evento
        ORDER BY
            tev_nome" );

        $busca_professores = $sql->query( "
        SELECT DISTINCT
            prf_id,
            prf_nome
        FROM
            professor
        ORDER BY
            prf_nome" );            

        $busca_patrocinadores = $sql->query( "
        SELECT DISTINCT
            pat_id,
            pat_nome
        FROM
            patrocinador
        ORDER BY
            pat_nome" );          

        $campos_possiveis_aluno_gv = array( "agv_nome"      => "Nome",
                                            "agv_matricula" => "Matrícula",
                                            "agv_telefone"  => "Telefone",
                                            "agv_celular"   => "Celular",
                                            "agv_email"     => "E-mail" );

        $campos_possiveis_aluno_ngv = array( "ang_nome"      => "Nome",
                                             "ang_telefone"  => "Telefone",
                                             "ang_celular"   => "Celular",
                                             "ang_email"     => "E-mail",
                                             "ang_faculdade" => "Faculdade" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="2" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="eventos" />
        <input type="hidden" name="acao" value="procurar_eventos" />
        <input type="hidden" name="forcar_busca" value="true" />
        <!-- shub -->
        Tipo de Evento
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "busca_campo_tev_id", $busca_tipos_evento, "tev_id", "tev_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'tev_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'tev_id' ] : "" ), "", "true", "Todos Tipos de Eventos" ); ?>        
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Professor
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "busca_campo_prf_id", $busca_professores, "prf_id", "prf_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'prf_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'prf_id' ] : "" ), "", "true", "Todos os Professores" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Patrocinador
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "busca_campo_pat_id", $busca_patrocinadores, "pat_id", "pat_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'pat_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'pat_id' ] : "" ), "", "true", "Todos os Patrocinadores" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Aluno GV Inscrito
        </td>
        <td bgcolor="#ffffff" class="text">
        <? gera_select_from_hash_vt($campos_possiveis_aluno_gv, array( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_gv' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_gv' ] : '' ), array( "name" => "busca_campo_aluno_gv" ) ); ?>
        <input type='text' name='busca_texto_aluno_gv' value='<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_gv' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_gv' ] : '' ?>' />
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Aluno não GV Inscrito
        </td>
        <td bgcolor="#ffffff" class="text">
        <? gera_select_from_hash_vt($campos_possiveis_aluno_ngv, array( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_ngv' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'campo_aluno_ngv' ] : '' ), array( "name" => "busca_campo_aluno_ngv" ) ); ?>
        <input type='text' name='busca_texto_aluno_ngv' value='<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_ngv' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'texto_aluno_ngv' ] : '' ?>' />
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">        
        Data
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="busca_campo_dia_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_de' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_campo_mes_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_de' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_campo_ano_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_de' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        até 
        <select name="busca_campo_dia_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'dia_ate' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_campo_mes_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'mes_ate' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="busca_campo_ano_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'ano_ate' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td></tr><tr>
        <td bgcolor="#ffffff" class="text" colspan="2" align='center'>
        <input type="submit" value="Procurar" />
        
    </td></tr></form>
    </table>
    </td></tr>
    </table><br /><br />
    
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_eventos ) && is_array( $busca_eventos ) ? "7" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos - Resultados da Busca</td>
        </tr>

        <?
        if( isset( $busca_eventos ) && is_array( $busca_eventos ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text"><b>Tipo Evento</b></td>
            <td bgcolor="#ffffff" class="text"><b>Edição</b></td>
            <td bgcolor="#ffffff" class="text"><b>Data</b></td>
            <td bgcolor="#ffffff" class="text"><b>Professores</b></td>
            <td bgcolor="#ffffff" class="text"><b>Patrocinadores</b></td>
            <td bgcolor="#ffffff" class="text"><b>Inscritos GV</b></td>
            <td bgcolor="#ffffff" class="text"><b>Inscritos não GV</b></td>
            </tr>

            <?
            foreach( $busca_eventos as $tupla )
            {
                $busca_professores_envolvidos = $sql->query( "
                SELECT DISTINCT
                    prf_nome
                FROM
                    professor
                WHERE
                    prf_id IN ( SELECT DISTINCT prf_id FROM evt_prf WHERE evt_id = '" . $tupla[ 'evt_id' ] . "' )
                ORDER BY
                    prf_nome" );
        
                $busca_patrocinadores_envolvidos = $sql->query( "
                SELECT DISTINCT
                    pat_nome
                FROM
                    patrocinador
                WHERE
                    pat_id IN( SELECT DISTINCT pat_id FROM evt_pat WHERE evt_id = '" . $tupla[ 'evt_id' ] . "' )
                ORDER BY
                    pat_nome" );

                $busca_alunos_gv_envolvidos = $sql->query( "
                SELECT DISTINCT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id IN( SELECT DISTINCT agv_id FROM inscrito_gv WHERE evt_id = '" . $tupla[ 'evt_id' ] . "' )
                ORDER BY
                    agv_nome" );

                $busca_alunos_ngv_envolvidos = $sql->query( "
                SELECT DISTINCT
                    ang_nome
                FROM
                    aluno_nao_gv
                WHERE
                    ang_id IN( SELECT DISTINCT ang_id FROM inscrito_ngv WHERE evt_id = '" . $tupla[ 'evt_id' ] . "' )
                ORDER BY
                    ang_nome" );                    
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tev_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'evt_edicao' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'evt_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_professores_envolvidos ) )
                {
                    foreach( $busca_professores_envolvidos as $professor_envolvido )
                    {
                    ?>
                        <?= $professor_envolvido[ 'prf_nome' ] ?><?= ( sizeof( $busca_professores_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_patrocinadores_envolvidos ) )
                {
                    foreach( $busca_patrocinadores_envolvidos as $patrocinador_envolvido )
                    {
                    ?>
                        <?= $patrocinador_envolvido[ 'pat_nome' ] ?><?= ( sizeof( $busca_patrocinadores_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_alunos_gv_envolvidos ) )
                {
                    foreach( $busca_alunos_gv_envolvidos as $aluno_gv_envolvido )
                    {
                    ?>
                        <?= $aluno_gv_envolvido[ 'agv_nome' ] ?><?= ( sizeof( $busca_alunos_gv_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_alunos_ngv_envolvidos ) )
                {
                    foreach( $busca_alunos_ngv_envolvidos as $aluno_ngv_envolvido )
                    {
                    ?>
                        <?= $aluno_ngv_envolvido[ 'ang_nome' ] ?><?= ( sizeof( $busca_alunos_ngv_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_evt'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="7" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_evt'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=eventos&acao=procurar_eventos&busca_pagina_num_evt=<?= ($list_data["pagina_num_evt"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_evt"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_evt"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=eventos&acao=procurar_eventos&busca_pagina_num_evt=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_evt'] > $list_data['pagina_num_evt'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=eventos&acao=procurar_eventos&busca_pagina_num_evt=<?= ($list_data["pagina_num_evt"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum evento foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="<?= ( isset( $busca_eventos ) && is_array( $busca_eventos ) ? "7" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "premio_gestao":
        $busca_edicao = $sql->query( "
        SELECT DISTINCT
            evt_id,
            evt_edicao
        FROM
            evento NATURAL JOIN
            tipo_evento
        WHERE
            tev_id IN( SELECT DISTINCT tev_id FROM tipo_evento WHERE tev_mne = 'premio_gestao' )        
        ORDER BY
            evt_edicao" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos "Prêmio Gestão"</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="premio_gestao" />
        <input type="hidden" name="acao" value="procurar_premio_gestao" />
        <input type="hidden" name="forcar_busca" value="true" />
        Edição
        </td>
        <td bgcolor="#ffffff" class="textb">
        <? faz_select( "evento_premio_gestao_id", $busca_edicao, "evt_id", "evt_edicao", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'evento_premio_gestao_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'evento_premio_gestao_id' ] : "" ), "", "true", "Todas as Edições" ); ?>
        </td></tr><tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='2'>
        <input type="submit" value="Procurar" />
        
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

   
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
    <tr><td>        
    <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_eventos_pg ) && is_array( $busca_eventos_pg ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos "Prêmio Gestão" - Resultados da Busca</td>
        </tr>
        <?
        if( isset( $busca_eventos_pg ) && is_array( $busca_eventos_pg ) )
        {
        ?>
          <tr>
           <td bgcolor="#ffffff" class="text"><b>Edição</b></td>
           <td bgcolor="#ffffff" class="text"><b>Data</b></td>
           <td bgcolor="#ffffff" class="text"><b>Inscritos</b></td>
            <td bgcolor="#ffffff" class="text"><b>Vencedor</b></td>
            </tr>

            <?
            foreach( $busca_eventos_pg as $tupla )
            {
                $busca_categorias_pg = $sql->query( "
                SELECT DISTINCT
                    cat_id,
                    cat_nome
                FROM
                    inscrito_pg a
                    NATURAL JOIN categoria b
                WHERE
                    evt_id = '" . $tupla[ 'evt_id' ] . "'
                ORDER BY
                    cat_nome" );
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'evt_edicao' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'evt_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_categorias_pg ) )
                {
                    $count = -1;
                    foreach( $busca_categorias_pg as $categoria_pg )
                    {
                        $maior_nota = 0;
                        $count++;
                        ?>
                        <?= "<ul><li>" . $categoria_pg[ 'cat_nome' ] . "</li>" ?>
                        <?
                        $busca_alunos_pg = $sql->query( "
                        SELECT DISTINCT
                            agv_id,
                            agv_nome
                        FROM
                            aluno_gv
                        WHERE
                            agv_id IN( SELECT DISTINCT agv_id FROM inscrito_pg WHERE evt_id = '" . $tupla[ 'evt_id' ] . "' AND cat_id = '" . $categoria_pg[ 'cat_id' ] . "' )
                        ORDER BY
                            agv_nome" );          
    
                        if( is_array( $busca_alunos_pg ) )
                        {
    ?>
<ul> 
 <? 
                            $maior_nota = 0;
                            foreach( $busca_alunos_pg as $aluno_pg )
                            {
                                $busca_notas_aluno_pg = $sql->squery( "
                                SELECT DISTINCT
                                    ipg_nota_1,
                                    ipg_nota_2,
                                    ipg_peso_1,
                                    ipg_peso_2
                                FROM
                                    inscrito_pg
                                WHERE
                                    evt_id = '" . $tupla[ 'evt_id' ] . "' AND cat_id = '" . $categoria_pg[ 'cat_id' ] . "' AND agv_id = '" . $aluno_pg[ 'agv_id' ] . "'" );   

				if(  $busca_notas_aluno_pg[ 'ipg_peso_1' ] == "" )
				     $busca_notas_aluno_pg[ 'ipg_peso_1' ] = 1;
				if(  $busca_notas_aluno_pg[ 'ipg_peso_2' ] == "" )
				     $busca_notas_aluno_pg[ 'ipg_peso_2' ] = 1;
				
                                $nota_aluno_pg =
                                (
                                    $busca_notas_aluno_pg[ 'ipg_peso_1' ] * $busca_notas_aluno_pg[ 'ipg_nota_1' ] +
                                    $busca_notas_aluno_pg[ 'ipg_peso_2' ] * $busca_notas_aluno_pg[ 'ipg_nota_2' ] 
                                )/
                                (
                                    $busca_notas_aluno_pg[ 'ipg_peso_1' ] + $busca_notas_aluno_pg[ 'ipg_peso_2' ]
                                );
                                
                                if( $nota_aluno_pg >= 7.0 && $nota_aluno_pg > $maior_nota )
                                {
                                    $vencedor[ $count ] = $categoria_pg[ 'cat_id' ] . ":" . $aluno_pg[ 'agv_id' ];
                                    $maior_nota = $nota_aluno_pg;
                                }
                                ?>
                                <?= "<li>" . $aluno_pg[ 'agv_nome' ] . ( $nota_aluno_pg >= 7.0 ? " (" . formata_dinheiro( $nota_aluno_pg ) . ")" : "" ) . "</li>" ?>
                                <?
					}
	?>
				</ul>
			      <?
                        }
			    ?>
			    </ul>
			    <?
                    }
                }
                ?>&nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
		$caras[ 'categoria' ]       = "";
		$caras[ 'ganhador' ]        = "";
		$caras[ 'coordenador' ]     = "";
		
		/*
		 * Categorias
		 */
		$busca = $sql->query( "
            SELECT DISTINCT
                cat_id,
                cat_nome
            FROM
                inscrito_pg
                NATURAL JOIN categoria
            WHERE
                evt_id = '" . $tupla[ 'evt_id' ] . "'
            ORDER BY
                cat_nome" );
		
		if( is_array( $busca ) )
		{
		    $media      = "( ( ipg_nota_1 * ipg_peso_1 ) + ( ipg_nota_2 * ipg_peso_2 ) ) / ( ipg_peso_1 + ipg_peso_2 )";

		    $caras[ 'categoria' ] = '<ul>';
		    foreach( $busca as $categoria )
			{
			    $caras[ 'categoria' ] .= "<li>" . $categoria[ 'cat_nome' ] . "</li>";
			    $maior_nota = 0;
			    $query = "
                    SELECT DISTINCT
                        agv_nome,
                        " . $media . " AS nota
                    FROM
                        inscrito_pg
                        NATURAL JOIN aluno_gv
                    WHERE
                        evt_id = '"     . $tupla[ 'evt_id' ]        . "'
                        AND cat_id = '" . $categoria[ 'cat_id' ]    . "'
                        AND " . $media . " >= ( '7.0' )
                        AND " . $media . " IN
                        (
                            SELECT
                                MAX( " . $media . " )
                            FROM
                                inscrito_pg
                            WHERE
                                evt_id = '" . $tupla[ 'evt_id' ] . "'
                                AND cat_id = '" . $categoria[ 'cat_id' ] . "'
                        )
                    ORDER BY
                        agv_nome";
			    
			    $ganhadores = $sql->query( $query );
			    
			    $caras[ 'ganhador' ] .= "<ul><li>" . $categoria[ 'cat_nome' ] . ":</li><ul>";
			    
			    if( is_array( $ganhadores ) )
				foreach( $ganhadores as $ganhador )
				    $caras[ 'ganhador' ] .= "<li>" . $ganhador[ 'agv_nome' ] . " - " . formata_dinheiro( $ganhador[ 'nota' ] ) . "</li>"; 
			    else
				$caras[ 'ganhador' ] .= "<li>Sem ganhador</li>";
			    
			    $caras[ 'ganhador' ] .= "</ul></ul>";
			}
		    $caras[ 'categoria' ] .= '</ul>';
		}
		
		/* 
		 * Coordenador
		 */
		
		$query = "
            SELECT DISTINCT
                mem_nome
            FROM
                evt_mem
                LEFT JOIN membro_vivo USING ( mem_id )
            WHERE
                evt_id = '" . $tupla[ 'evt_id' ] . "'
                AND eme_coordenador = '1'
            ORDER BY
                mem_nome";
		
		$busca = $sql->squery( $query );
		
		if( $busca )
		$caras[ 'coordenador' ] = $busca[ 'mem_nome' ];
        ?>
		<?= ( ( $caras[ 'ganhador' ] != '' ) ? $caras[ 'ganhador' ] : "&nbsp;" ) ?>
                &nbsp;</td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_epg'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="4" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_epg'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=premio_gestao&acao=procurar_premio_gestao&busca_pagina_num_epg=<?= ($list_data["pagina_num_epg"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_epg"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_epg"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=premio_gestao&acao=procurar_premio_gestao&busca_pagina_num_epg=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_epg'] > $list_data['pagina_num_epg'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=premio_gestao&acao=procurar_premio_gestao&busca_pagina_num_epg=<?= ($list_data["pagina_num_epg"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
	  
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum evento "Prêmio Gestão" foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="<?= ( isset( $busca_eventos_pg ) && is_array( $busca_eventos_pg ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "membros_exmembros":
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Membros e Ex-membros</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="membros_exmembros" />
        <input type="hidden" name="acao" value="procurar_membros_exmembros" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="membros_exmembros_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Semestre / Ano Entrada na GV<br>
        <select name="membros_exmembros_semestre_entrada_gv">
            <option value="">---</option>
            <option value="1" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_gv' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_gv' ] == 1 ) ? "selected" : "" ) ?>>1</option>
            <option value="2" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_gv' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_gv' ] == 2 ) ? "selected" : "" ) ?>>2</option>
        </select> /
        <select name="membros_exmembros_ano_entrada_gv">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_gv' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_gv' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_gv' ] : "" );

            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Semestre / Ano Entrada na EJ<br>
        <select name="membros_exmembros_semestre_entrada_ej">
            <option value="">---</option>
            <option value="1" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_ej' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_ej' ] == 1 ) ? "selected" : "" ) ?>>1</option>
            <option value="2" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_ej' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_semestre_entrada_ej' ] == 2 ) ? "selected" : "" ) ?>>2</option>
        </select> /
        <select name="membros_exmembros_ano_entrada_ej">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_ej' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_ej' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_ano_entrada_ej' ] : "" );

            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Aniversário<br />
        <select name="membros_exmembros_dia_nasci">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_dia_nasci' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_dia_nasci' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_dia_nasci' ] : "" );

            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="membros_exmembros_mes_nasci">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_mes_nasci' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_mes_nasci' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'membros_exmembros_mes_nasci' ] : "" );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='4'>
        <input type="submit" value="Procurar" />
        
        </td>
        </tr></form>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />
  
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" colspan="<?= ( isset( $busca_membros_exmembros ) && is_array( $busca_membros_exmembros ) ? "6" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Membros e Ex-membros - Resultados da Busca</td>
        </tr>
        <?
        if( isset( $busca_membros_exmembros ) && is_array( $busca_membros_exmembros ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Matrícula</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            <td bgcolor="#ffffff" class="text"><b>Ano de Entrada</b></td>
            <td bgcolor="#ffffff" class="text"><b>Participa</b></td>
            </tr>

            <?
            foreach( $busca_membros_exmembros as $tupla )
            {
                $busca_consultorias_participa = $sql->query( "
                SELECT DISTINCT
                    cst_nome
                FROM
                    cst_mem c
                    LEFT JOIN consultoria o ON( c.cst_id = o.cst_id )
                WHERE
                    mem_id = '" . $tupla[ 'mem_id' ] . "'
                ORDER BY
                    cst_nome" );
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_matricula' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "agv_ddi" ] ) ? " (+" . $tupla[ "agv_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "agv_ddd" ] ) ? " ("  . $tupla[ "agv_ddd" ] . ")" : "" ) .
                        $tupla[ "agv_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_email' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                   <? 
                    $agv_ano_entrada = substr( $tupla[ 'agv_matricula' ], 2, 2 );
                    if( is_numeric( $agv_ano_entrada ) )
                    {
                        $agv_ano_entrada += ( $agv_ano_entrada > 40 ? 1900 : 2000 );
                    }
                    else
                    {
                        $agv_ano_entrada = "Matrícula não está no formato esperado";
                    }
                    print $agv_ano_entrada;
                    ?>
                </td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_consultorias_participa ) )
                {
                    foreach( $busca_consultorias_participa as $consultoria_participa )
                    {
                    ?>
                        <?= $consultoria_participa[ 'cst_nome' ] . ( sizeof( $busca_consultorias_participa ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_mex'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="6" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_mex'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=membros_exmembros&acao=procurar_membros_exmembros&busca_pagina_num_mex=<?= ($list_data["pagina_num_mex"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_mex"]; $i++)
	    { 
		if( $i == $list_data["pagina_num_mex"] )
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=membros_exmembros&acao=procurar_membros_exmembros&busca_pagina_num_mex=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_mex'] > $list_data['pagina_num_mex'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=membros_exmembros&acao=procurar_membros_exmembros&busca_pagina_num_mex=<?= ($list_data["pagina_num_mex"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='6'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
	    
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum membro ou ex-membro foi encontrado.</td>
            </tr>
        <?
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="<?= ( isset( $busca_membros_exmembros ) && is_array( $busca_membros_exmembros ) ? "6" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "empresas_juniores":
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Empresas Juniores</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="empresas_juniores" />
        <input type="hidden" name="acao" value="procurar_empresas_juniores" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="empresas_juniores_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Cidade <input type="text" name="empresas_juniores_cidade" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'cidade' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'cidade' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Estado
        <select name="empresas_juniores_estado">
            <option value="">Todos os Estados</option>
            <option value="AC" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "AC" ? "selected" : "" ?>>Acre</option>
            <option value="AL" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "AL" ? "selected" : "" ?>>Alagoas</option>
            <option value="AM" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "AM" ? "selected" : "" ?>>Amazonas</option>
            <option value="AP" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "AP" ? "selected" : "" ?>>Amapá</option>
            <option value="BA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "BA" ? "selected" : "" ?>>Bahia</option>
            <option value="CE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "CE" ? "selected" : "" ?>>Ceará</option>
            <option value="DF" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "DF" ? "selected" : "" ?>>Distrito Federal</option>
            <option value="ES" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "ES" ? "selected" : "" ?>>Espírito Santo</option>
            <option value="GO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "GO" ? "selected" : "" ?>>Goiás</option>
            <option value="MA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "MA" ? "selected" : "" ?>>Maranhão</option>
            <option value="MG" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "MG" ? "selected" : "" ?>>Minas Gerais</option>
            <option value="MT" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "MT" ? "selected" : "" ?>>Mato Grosso</option>
            <option value="MS" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "MS" ? "selected" : "" ?>>Mato Grosso do Sul</option>
            <option value="PA" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "PA" ? "selected" : "" ?>>Pára</option>
            <option value="PB" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "PB" ? "selected" : "" ?>>Paraíba</option>
            <option value="PE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "PE" ? "selected" : "" ?>>Pernambuco</option>
            <option value="PI" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "PI" ? "selected" : "" ?>>Piauí</option>
            <option value="PR" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "PR" ? "selected" : "" ?>>Paraná</option>
            <option value="RJ" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "RJ" ? "selected" : "" ?>>Rio de Janeiro</option>
            <option value="RN" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "RN" ? "selected" : "" ?>>Rio Grande do Norte</option>
            <option value="RO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "RO" ? "selected" : "" ?>>Rondônia</option>
            <option value="RR" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "RR" ? "selected" : "" ?>>Roraima</option>
            <option value="RS" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "RS" ? "selected" : "" ?>>Rio Grande do Sul</option>
            <option value="SC" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "SC" ? "selected" : "" ?>>Santa Catarina</option>
            <option value="SE" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "SE" ? "selected" : "" ?>>Sergipe</option>
            <option value="SP" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "SP" ? "selected" : "" ?>>São Paulo</option>
            <option value="TO" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] == "TO" ? "selected" : "" ?>>Tocantins</option>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Relações Estreitas
        <select name="empresas_juniores_rel_estreita">
            <option value="2" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'rel_estreita' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'rel_estreita' ] == "2" ? "selected" : "" ?>>---</option>
            <option value="1" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'rel_estreita' ] == "1" ? "selected" : "" ?>>Sim</option>
            <option value="0" <?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'estado' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'rel_estreita' ] == "0" ? "selected" : "" ?>>Não</option>
        </select>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" colspan='5' align='center'>
        <input type="submit" value="Procurar" />
        </td>
        </tr></form>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />
  
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_empresas_juniores ) && is_array( $busca_empresas_juniores ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Empresas Juniores - Resultados da Busca</td>
        </tr>
        <?
        if( isset( $busca_empresas_juniores ) && is_array( $busca_empresas_juniores ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Endereço</b></td>
            <td bgcolor="#ffffff" class="text"><b>Bairro</b></td>
            <td bgcolor="#ffffff" class="text"><b>Contato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            </tr>

            <?
            foreach( $busca_empresas_juniores as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'eju_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'eju_endereco' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'eju_bairro' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'eju_nome_contato' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "eju_ddi" ] ) ? " (+" . $tupla[ "eju_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "eju_ddd" ] ) ? " ("  . $tupla[ "eju_ddd" ] . ")" : "" ) .
                        $tupla[ "eju_telefone" ] )
                    ?>
                  </td>
                </tr>
            <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_eju'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="5" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_eju'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=empresas_juniores&acao=procurar_empresas_juniores&busca_pagina_num_eju=<?= ($list_data["pagina_num_eju"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_eju"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_eju"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=empresas_juniores&acao=procurar_empresas_juniores&busca_pagina_num_eju=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_eju'] > $list_data['pagina_num_eju'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=empresas_juniores&acao=procurar_empresas_juniores&busca_pagina_num_eju=<?= ($list_data["pagina_num_eju"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhuma Empresa Júnior foi encontrada.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_empresas_juniores ) && is_array( $busca_empresas_juniores ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "fornecedores":
        $busca_ramos = $sql->query( "
        SELECT DISTINCT
            ram_id,
            ram_nome
        FROM
            ramo
        ORDER BY
            ram_nome" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Fornecedores</td>
        </tr>

        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="fornecedores" />
        <input type="hidden" name="acao" value="procurar_fornecedores" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="fornecedores_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Ramo
        <? faz_select( "fornecedores_ramo_id", $busca_ramos, "ram_id", "ram_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'ramo' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'ramo' ] : "" ), "", "true", "Todos os Ramos" ); ?>
        </td>
        </tr>
        <tr>
        <td bgcolor="#FFFFFF" class="text" align='center' colspan='2'>
        <input type="submit" value="Procurar" />
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_fornecedores ) && is_array( $busca_fornecedores ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Fornecedores - Resultados da Busca</td>
        </tr>

        <?
        if( isset( $busca_fornecedores ) && is_array( $busca_fornecedores ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Serviços</b></td>
            <td bgcolor="#ffffff" class="text"><b>Contato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            </tr>

            <?
            foreach( $busca_fornecedores as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_servicos' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_nome_contato' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "for_ddi" ] ) ? " (+" . $tupla[ "for_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "for_ddd" ] ) ? " ("  . $tupla[ "for_ddd" ] . ")" : "" ) .
                        $tupla[ "for_telefone" ] )
                    ?>
                  </td>
                </tr>
            <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_for'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="4" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_for'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=fornecedores&acao=procurar_fornecedores&busca_pagina_num_for=<?= ($list_data["pagina_num_for"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_for"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_for"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=fornecedores&acao=procurar_fornecedores&busca_pagina_num_for=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_for'] > $list_data['pagina_num_for'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=fornecedores&acao=procurar_fornecedores&busca_pagina_num_for=<?= ($list_data["pagina_num_for"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum fornecedor foi encontrado.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_fornecedores ) && is_array( $busca_fornecedores ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "patrocinadores":
        $busca_setores = $sql->query( "
        SELECT DISTINCT
            set_id,
            set_nome
        FROM
            setor
        ORDER BY
            set_nome" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Patrocinadores</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="patrocinadores" />
        <input type="hidden" name="acao" value="procurar_patrocinadores" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="patrocinadores_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Setor
        <? faz_select( "patrocinadores_setor_id", $busca_setores, "set_id", "set_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'setor' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'setor' ] : "" ), "", "true", "Todos os Setores" ); ?>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='2'>
        <input type="submit" value="Procurar" />
        
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_patrocinadores ) && is_array( $busca_patrocinadores ) ? "6" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Patrocinadores - Resultados da Busca</td>
        </tr>      
      
        <?
        if( isset( $busca_patrocinadores ) && is_array( $busca_patrocinadores ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Classificação</b></td>
            <td bgcolor="#ffffff" class="text"><b>Setor</b></td>
            <td bgcolor="#ffffff" class="text"><b>Contato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>Patrocina</b></td>
            </tr>

            <?
            foreach( $busca_patrocinadores as $tupla )
            {
                $busca_eventos_patrocinados = $sql->query( "
                SELECT DISTINCT
                    tev_nome,
                    evt_edicao
                FROM
                    evt_pat
                    NATURAL JOIN
                    evento
                    NATURAL JOIN
                    tipo_evento
                WHERE
                    pat_id = '" . $tupla[ 'pat_id' ] . "'
                ORDER BY
                    tev_nome" );
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'pat_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cla_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'set_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'pat_nome_contato' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "pat_ddi" ] ) ? " (+" . $tupla[ "pat_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "pat_ddd" ] ) ? " ("  . $tupla[ "pat_ddd" ] . ")" : "" ) .
                        $tupla[ "pat_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_eventos_patrocinados ) )
                {
                    foreach( $busca_eventos_patrocinados as $evento_patrocinado )
                    {
                    ?>
                        <?= $evento_patrocinado[ 'evt_edicao' ] ?><?= ( sizeof( $busca_eventos_patrocinados ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                </tr>
            <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_pat'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="6" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_pat'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=patrocinadores&acao=procurar_patrocinadores&busca_pagina_num_pat=<?= ($list_data["pagina_num_pat"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_pat"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_pat"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=patrocinadores&acao=procurar_patrocinadores&busca_pagina_num_pat=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_pat'] > $list_data['pagina_num_pat'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=patrocinadores&acao=procurar_patrocinadores&busca_pagina_num_pat=<?= ($list_data["pagina_num_pat"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum patrocinador foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_patrocinadores ) && is_array( $busca_patrocinadores ) ? "6" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "palestrantes":
        $busca_cargos = $sql->query( "
        SELECT DISTINCT
            cex_id,
            cex_nome
        FROM
            cargo_ext
        ORDER BY
            cex_nome" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Palestrantes</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="palestrantes" />
        <input type="hidden" name="acao" value="procurar_palestrantes" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="palestrantes_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="textb">
        Cargo
        <? faz_select( "palestrantes_cargo_id", $busca_cargos, "cex_id", "cex_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'cargo_ext' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'cargo_ext' ] : "" ), "", "true", "Todos os Cargos" ); ?>
        </td></tr><tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='2'>
        <input type="submit" value="Procurar" />
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_palestrantes ) && is_array( $busca_palestrantes ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Palestrantes - Resultados da Busca</td>
        </tr>

        <?
        if( isset( $busca_palestrantes ) && is_array( $busca_palestrantes ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Cargo</b></td>
            <td bgcolor="#ffffff" class="text"><b>Contato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_palestrantes as $tupla )
            {
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'pal_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'cex_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'pal_nome_contato' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "pal_ddi" ] ) ? " (+" . $tupla[ "pal_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "pal_ddd" ] ) ? " ("  . $tupla[ "pal_ddd" ] . ")" : "" ) .
                        $tupla[ "pal_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'pal_email' ] ?></td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_pal'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="5" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_pal'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=palestrantes&acao=procurar_palestrantes&busca_pagina_num_pal=<?= ($list_data["pagina_num_pal"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_pal"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_pal"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=palestrantes&acao=procurar_palestrantes&busca_pagina_num_pal=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_pal'] > $list_data['pagina_num_pal'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=palestrantes&acao=procurar_palestrantes&busca_pagina_num_pal=<?= ($list_data["pagina_num_pal"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum palestrante foi encontrado.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_palestrantes ) && is_array( $busca_palestrantes ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;        
    case "professores":
        $busca_departamentos = $sql->query( "
        SELECT DISTINCT
            dpt_id,
            dpt_nome
        FROM
            departamento
        ORDER BY
            dpt_nome" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Professores</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="professores" />
        <input type="hidden" name="acao" value="procurar_professores" />
        <input type="hidden" name="forcar_busca" value="true" />
        Nome <input type="text" name="professores_nome" value="<?= isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'nome' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'nome' ] : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="text">
        Departamento
        <? faz_select( "professores_departamento_id", $busca_departamentos, "dpt_id", "dpt_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'departamento' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'departamento' ] : "" ), "", "true", "Todos os Departamentos" ); ?>
        </td>
        <td bgcolor="#ffffff" class="text">
        Aniversário:<br />
        <select name="professores_dia_nasci">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_dia_nasci' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_dia_nasci' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_dia_nasci' ] : "" );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="professores_mes_nasci">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_mes_nasci' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_mes_nasci' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'professores_mes_nasci' ] : "" );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='3'>
        <input type="submit" value="Procurar" />
        
        </td></tr></form>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_professores ) && is_array( $busca_professores ) ? "6" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Professores - Resultados da Busca</td>
        </tr>


        <?
        if( isset( $busca_professores ) && is_array( $busca_professores ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Departamento</b></td>
            <td bgcolor="#ffffff" class="text"><b>Nascimento</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            <td bgcolor="#ffffff" class="text"><b>Participa</b></td>
            </tr>

            <?
            foreach( $busca_professores as $tupla )
            {
                $busca_consultorias_participa = $sql->query( "
                SELECT DISTINCT
                    cst_nome
                FROM
                    cst_prf p
                    JOIN
                    consultoria c ON( p.cst_id = c.cst_id )
                WHERE
                    prf_id = '" . $tupla[ 'prf_id' ] . "'
                ORDER BY
                    cst_nome" );

                $busca_eventos_participa = $sql->query( "
                SELECT DISTINCT
                    tev_nome || ': ' ||evt_edicao AS nome_evento,
                    evt_id,
                    evt_edicao
                FROM
                    evento NATURAL JOIN
                    tipo_evento
                WHERE
                    evt_id IN( SELECT DISTINCT evt_id FROM evt_prf WHERE prf_id = '" . $tupla[ 'prf_id' ] . "' )
                ORDER BY
                    nome_evento" );
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'prf_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'dpt_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'prf_nasci_timestamp' ] ) ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "prf_ddi" ] ) ? " (+" . $tupla[ "prf_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "prf_ddd" ] ) ? " ("  . $tupla[ "prf_ddd" ] . ")" : "" ) .
                        $tupla[ "prf_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'prf_email' ] ?></td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_consultorias_participa ) )
                {
                    foreach( $busca_consultorias_participa as $consultoria_participa )
                    {
                    ?>
                        <?= $consultoria_participa[ 'cst_nome' ] ?><?= ( sizeof( $busca_consultorias_participa ) > 1 ? "<br />" : "" ) ?>
                    <?
                    }
                }
                if( is_array( $busca_eventos_participa ) )
                {
                    ?>
                    <?= ( sizeof( $busca_consultorias_participa ) >= 1 && sizeof( $busca_eventos_participa ) >= 1 ? "<p>" : "" ) ?>
                    <?
                    foreach( $busca_eventos_participa as $evento_participa )
                    {
                    ?>
                        <?= $evento_participa[ 'nome_evento' ] ?><?= ( sizeof( $busca_eventos_participa ) > 1 ? "<br />" : "" ) ?>
                    <?
                    }
                }
                ?>&nbsp;</td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_prf'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="6" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_prf'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=professores&acao=procurar_professores&busca_pagina_num_prf=<?= ($list_data["pagina_num_prf"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_prf"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_prf"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=professores&acao=procurar_professores&busca_pagina_num_prf=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_prf'] > $list_data['pagina_num_prf'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=professores&acao=procurar_professores&busca_pagina_num_prf=<?= ($list_data["pagina_num_prf"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum professor foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_professores ) && is_array( $busca_professores ) ? "6" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;        
    case "alunos_gv":
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos GV</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="alunos_gv" />
        <input type="hidden" name="acao" value="procurar_alunos_gv" />
        <input type="hidden" name="forcar_busca" value="true" />
        Curso / Classe
        <select name="alunos_gv_curso_classe">
            <option value="">---</option>        
            <option value="11" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] == 11 ? "selected" : "" ) ?>>AE/1</option>
            <option value="12" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] == 12 ? "selected" : "" ) ?>>AE/2</option>
            <option value="13" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] == 13 ? "selected" : "" ) ?>>AE/3</option>
            <option value="14" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_curso_classe' ] == 14 ? "selected" : "" ) ?>>AP/-</option>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Ano Entrada
        <select name="alunos_gv_ano_entrada">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_ano_entrada' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_ano_entrada' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_ano_entrada' ] : "" );

            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        Semestre Entrada
        <select name="alunos_gv_semestre_entrada">
            <option value="">---</option>
            <option value="1" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_semestre_entrada' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_semestre_entrada' ] == 1 ? "selected" : "" ) ?>>1</option>
            <option value="2" <?= ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_semestre_entrada' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'alunos_gv_semestre_entrada' ] == 2 ? "selected" : "" ) ?>>2</option>
        </select>        
        </td>
        </tr><tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='3'>
        <input type="submit" value="Procurar" />
        
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_alunos_gv ) && is_array( $busca_alunos_gv ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos GV - Resultados da Busca</td>
        </tr>  
  
        <?
        if( isset( $busca_alunos_gv ) && is_array( $busca_alunos_gv ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Matrícula</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_alunos_gv as $tupla )
            {
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_matricula' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "agv_ddi" ] ) ? " (+" . $tupla[ "agv_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "agv_ddd" ] ) ? " ("  . $tupla[ "agv_ddd" ] . ")" : "" ) .
                        $tupla[ "agv_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_email' ] ?></td>
                </tr>
                <?
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_agv'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="4" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_agv'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_gv&acao=procurar_alunos_gv&busca_pagina_num_agv=<?= ($list_data["pagina_num_agv"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_agv"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_agv"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_gv&acao=procurar_alunos_gv&busca_pagina_num_agv=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_agv'] > $list_data['pagina_num_agv'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_gv&acao=procurar_alunos_gv&busca_pagina_num_agv=<?= ($list_data["pagina_num_agv"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum aluno GV foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_alunos_gv ) && is_array( $busca_alunos_gv ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;        
    case "alunos_nao_gv":
        $busca_eventos = $sql->query( "
        SELECT DISTINCT
            tev_nome || ': ' ||evt_edicao AS nome_evento,
            evt_id,
            evt_edicao
        FROM
            evento NATURAL JOIN
            tipo_evento
        ORDER BY
            nome_evento" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos não GV</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="alunos_nao_gv" />
        <input type="hidden" name="acao" value="procurar_alunos_nao_gv" />
        <input type="hidden" name="forcar_busca" value="true" />
        Evento
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "alunos_nao_gv_evento_id", $busca_eventos, "evt_id", "nome_evento", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'alunos_nao_gv_evento_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'alunos_nao_gv_evento_id' ] : "" ), "", "true", "Todos os Eventos" ); ?>
        </td></tr><tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='2'>
        <input type="submit" value="Procurar" />

        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />
    
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_alunos_nao_gv ) && is_array( $busca_alunos_nao_gv ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos não GV - Resultados da Busca</td>
        </tr>       
       
        <?
        if( isset( $busca_alunos_nao_gv ) && is_array( $busca_alunos_nao_gv ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            <td bgcolor="#ffffff" class="text"><b>Faculdade</b></td>
            <td bgcolor="#ffffff" class="text"><b>Curso</b></td>
            </tr>

            <?
            foreach( $busca_alunos_nao_gv as $tupla )
            {
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ang_nome' ] ?></td>
                  <td bgcolor='#ffffff' class="text">&nbsp;
                    <?= in_html(
                        ( consis_telefone( $tupla[ "ang_ddi" ] ) ? " (+" . $tupla[ "ang_ddi" ] . ")" : "" ) .
                        ( consis_telefone( $tupla[ "ang_ddd" ] ) ? " ("  . $tupla[ "ang_ddd" ] . ")" : "" ) .
                        $tupla[ "ang_telefone" ] )
                    ?>
                  </td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ang_email' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ang_faculdade' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ang_curso' ] ?></td>
                </tr>
                <?
            }

            /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_ang'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="6" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_ang'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_nao_gv&acao=procurar_alunos_nao_gv&busca_pagina_num_ang=<?= ($list_data["pagina_num_ang"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_ang"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_ang"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_nao_gv&acao=procurar_alunos_nao_gv&busca_pagina_num_ang=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_ang'] > $list_data['pagina_num_ang'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=alunos_nao_gv&acao=procurar_alunos_nao_gv&busca_pagina_num_ang=<?= ($list_data["pagina_num_ang"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    	    	    
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum aluno não GV foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_alunos_nao_gv ) && is_array( $busca_alunos_nao_gv ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;                
    case "timesheets":
        $busca_membros = $sql->query( "
        SELECT DISTINCT
            mem_id,
            mem_nome
        FROM
            membro_vivo
        ORDER BY
            mem_nome" );
            
        $busca_eventos = $sql->query( "
        SELECT DISTINCT
            tev_nome || ': ' ||evt_edicao AS nome_evento,
            evt_id,
            evt_edicao
        FROM
            evento NATURAL JOIN
            tipo_evento
        ORDER BY
            nome_evento" );

        $busca_areas = $sql->query( "
        SELECT DISTINCT
            are_nome,
            are_id
        FROM
            area
        ORDER BY
            are_nome" );
        
        $busca_atividades = $sql->query( "
        SELECT DISTINCT
            tat_id,
            tat_nome
        FROM
            ts_atividade
        ORDER BY
            tat_nome" );

        $busca_subatividades = $sql->query( "
        SELECT DISTINCT
            tsa_id,
            tsa_nome
        FROM
            ts_subatividade
        ORDER BY
            tsa_nome" );            

        $busca_empresas = $sql->query( "
        SELECT DISTINCT
            cli_id,
            cli_nome
        FROM
            cliente
        ORDER BY
            cli_nome" );            

        $busca_projetos_internos = $sql->query( "
        SELECT DISTINCT
            pin_id,
            pin_nome
        FROM
            prj_interno
        ORDER BY
            pin_nome" );            
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="2" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Timesheet</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="timesheets" />
        <input type="hidden" name="acao" value="procurar_timesheets" />
        <input type="hidden" name="forcar_busca" value="true" />
        Data
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="timesheets_dia_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_de' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheets_mes_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_de' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheets_ano_de">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_de' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_de' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_de' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        até 
        <select name="timesheets_dia_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_dia_ate' ] : '' );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheets_mes_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_mes_ate' ] : '' );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheets_ano_ate">
            <option value=''>---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_ate' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_ate' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_ano_ate' ] : '' );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Membro
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_membro_id", $busca_membros, "mem_id", "mem_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_membro_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_membro_id' ] : "" ), "", "true", "Todos os membros" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Área
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_area_id", $busca_areas, "are_id", "are_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_area_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_area_id' ] : "" ), "", "true", "Todas as áreas" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Atividade
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_atividade_id", $busca_atividades, "tat_id", "tat_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_atividade_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_atividade_id' ] : "" ), "", "true", "Todas as atividades" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Empresa
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_empresa_id", $busca_empresas, "cli_id", "cli_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_empresa_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_empresa_id' ] : "" ), "", "true", "Todas as empresas" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Evento
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_evento_id", $busca_eventos, "evt_id", "nome_evento", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_evento_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_evento_id' ] : "" ), "", "true", "Todos os eventos" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Projeto Interno
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_projeto_interno_id", $busca_projetos_internos, "pin_id", "pin_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_projeto_interno_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_projeto_interno_id' ] : "" ), "", "true", "Todos os Proj. Internos" ); ?>
        </td></tr>
        <tr>
        <td bgcolor="#ffffff" class="textb">
        Sub-atividade
        </td>
        <td bgcolor="#ffffff" class="text">
        <? faz_select( "timesheets_subatividade_id", $busca_subatividades, "tsa_id", "tsa_nome", ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_subatividade_id' ] ) ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'timesheets_subatividade_id' ] : "" ), "", "true", "Todas as sub-atividades" ); ?>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='7'>
        <input type="submit" value="Procurar" />
        </td></form>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" colspan="<?= ( isset( $busca_timesheets ) && is_array( $busca_timesheets ) ? "7" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Timesheet - Resultado da Busca</td>
        </tr>    
        <?
        if( isset( $busca_timesheets ) && is_array( $busca_timesheets ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text"><b>Data</b></td>
            <td bgcolor="#ffffff" class="text"><b>Membro</b></td>
            <td bgcolor="#ffffff" class="text"><b>Área</b></td>
            <td bgcolor="#ffffff" class="text"><b>Atividade</b></td>
            <td bgcolor="#ffffff" class="text"><b>Empresa / Evento</b></td>
            <td bgcolor="#ffffff" class="text"><b>Sub-atividade</b></td>
            <td bgcolor="#ffffff" class="text"><b>Tempo (H)</b></td>
            </tr>

            <?

	    $total_pagina = 0;

	    foreach( $busca_timesheets as $tupla )
            {
                if( $tupla[ 'cli_nome' ] != "" )
                    $empresa_evento = $tupla[ 'cli_nome' ];
                else if( $tupla[ 'evt_edicao' ] != "" )
                    $empresa_evento = $tupla[ 'tev_nome' ] . ": " . $tupla[ 'evt_edicao' ];
                else if( $tupla[ 'pin_nome' ] != "" )
                    $empresa_evento = $tupla[ 'pin_nome' ];
                else if( $tupla[ 'cst_nome' ] != "" )
                    $empresa_evento = $tupla[ 'cst_nome' ];
                else
                    $empresa_evento = "";
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsh_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'are_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tat_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $empresa_evento ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsa_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsh_duracao' ] ?></td>
                </tr>
                <?
		$total_pagina += $tupla[ 'tsh_duracao' ];
            }
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">Total de horas da página: <?= $total_pagina ?></td>
            </tr>
	    <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">Total de horas da busca: <?= $busca_timesheets_total_horas[ 'total_horas' ] ?></td>
            </tr>
	
	    <?
		 
            /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_tsh'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="7" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_tsh'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=timesheets&acao=procurar_timesheets&busca_pagina_num_tsh=<?= ($list_data["pagina_num_tsh"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_tsh"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_tsh"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=timesheets&acao=procurar_timesheets&busca_pagina_num_tsh=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_tsh'] > $list_data['pagina_num_tsh'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=timesheets&acao=procurar_timesheets&busca_pagina_num_tsh=<?= ($list_data["pagina_num_tsh"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    	    	
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?
		 
	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum timesheet foi encontrado.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="<?= ( isset( $busca_timesheets ) && is_array( $busca_timesheets ) ? "7" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "processos_seletivos":
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Processos Seletivos</td>
        </tr>


        <tr>
        <td bgcolor="#ffffff" class="textb">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="relatorio" />
        <input type="hidden" name="relatorio" value="processos_seletivos" />
        <input type="hidden" name="acao" value="procurar_processos_seletivos" />
        <input type="hidden" name="forcar_busca" value="true" />
        Semestre / Ano
        <select name="processos_seletivos_semestre">
            <option value="">---</option>
            <option value="1" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_semestre' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_semestre' ] == 1 ) ? "selected" : "" ) ?>>1</option>
            <option value="2" <?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_semestre' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_semestre' ] == 2 ) ? "selected" : "" ) ?>>2</option>
        </select> /
        <select name="processos_seletivos_ano">
            <option value="">---</option>
            <?
            $selecionado = ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_ano' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_ano' ] != "" ? $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_ano' ] : "" );

            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="textb">
        <? $a = false; ?>
        Status
        <select name='processos_seletivos_status'>
            <option value=''<?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] == '' ) ? " selected " && $a = true : "" ) ?>>---</option>
            <option value='0'<?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] == 0 && $a == false) ? " selected " : "" ) ?>>Novos</option>
            <option value='1'<?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] == 1 ) ? " selected " : "" ) ?>>Aprovados</option>
            <option value='2'<?= ( ( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] ) && $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] == 2 ) ? " selected " : "" ) ?>>Reprovados</option>
        </select>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" align='center' colspan='2'>
            <input type="submit" value="Procurar" />
        </td></form>
        </tr>
         <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="3">&nbsp;</td>
        </tr> 
        </table>
        </td></tr>
        </table><br /><br />


<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_processos_seletivos ) && is_array( $busca_processos_seletivos ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Processos Seletivos</td>
    </tr>

    <?
        if( isset( $busca_processos_seletivos ) && is_array( $busca_processos_seletivos ) )
        {
            $q = '';
            $x = '';

            if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ] ) )
            {
                $processos_seletivos_status = $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'processos_seletivos_status' ];

                if( $processos_seletivos_status == '' )
                {
                    $x = " Todos";
                }
                elseif( $processos_seletivos_status == 0 )
                {
                    $x = " Novos";
                    $q = "cnd_status = '0' AND ";
                }
                elseif( $processos_seletivos_status == 1 )
                {
                    $x = " Aprovados";
                    $q = "cnd_status = '1' AND ";
                }
                elseif( $processos_seletivos_status == 2 )
                {
                    $x = " Reprovados";
                    $q = "cnd_status = '2' AND ";
                }
            }
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text"><b>Semestre / Ano</b></td>
            <td bgcolor="#ffffff" class="text"><b>Consultores</b></td>
            <td bgcolor="#ffffff" class="text"><b>Empresas Contratadas</b></td>
            <td bgcolor="#ffffff" class="text"><b>Palestras</b></td>
            <td bgcolor="#ffffff" class="text"><b>Inscritos<?= $x ?></b></td>
            </tr>

            <?
            foreach( $busca_processos_seletivos as $tupla )
            {
                $busca_patrocinadores_envolvidos = $sql->query( "
                SELECT DISTINCT
                    for_nome
                FROM
                    fornecedor
                WHERE
                    for_id IN( SELECT DISTINCT for_id FROM abastece WHERE psl_id = '" . $tupla[ 'psl_id' ] . "' )
                ORDER BY
                    for_nome" );
                    
                $busca_consultores_envolvidos = $sql->query( "
                SELECT DISTINCT
                    mem_nome
                FROM
                    membro_todos
                WHERE
                    mem_id IN( SELECT DISTINCT mem_id FROM audita WHERE psl_id = '" . $tupla[ 'psl_id' ] . "' )
                ORDER BY
                    mem_nome" );

                $busca_palestras_envolvidos = $sql->query( "
                SELECT DISTINCT
                    plt_nome
                FROM
                    palestra
                WHERE
                    psl_id = '" . $tupla[ 'psl_id' ] . "'
                ORDER BY
                    plt_nome" );

                $busca_alunos_gv_envolvidos = $sql->query( "
                SELECT DISTINCT
                    agv_nome
                FROM
                    aluno_gv
                    NATURAL JOIN candidato_din
                    NATURAL JOIN dinamica
                WHERE " .  $q . " 
                    agv_id IN( SELECT DISTINCT agv_id FROM candidato_psl WHERE psl_id = '" . $tupla[ 'psl_id' ] . "' )
                ORDER BY
                    agv_nome" );
                ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= ( date( "m", $tupla[ 'psl_timestamp' ] ) > 6 ? "2" : "1" ) . "/" . date( "Y", $tupla[ 'psl_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_consultores_envolvidos ) )
                {
                    foreach( $busca_consultores_envolvidos as $consultor_envolvido )
                    {
                    ?>
                        <?= $consultor_envolvido[ 'mem_nome' ] ?><?= ( sizeof( $busca_consultores_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>
                &nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_patrocinadores_envolvidos ) )
                {
                    foreach( $busca_patrocinadores_envolvidos as $patrocinador_envolvido )
                    {
                    ?>
                        <?= $patrocinador_envolvido[ 'for_nome' ] ?><?= ( sizeof( $busca_patrocinadores_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>
                &nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_palestras_envolvidos ) )
                {
                    foreach( $busca_palestras_envolvidos as $palestra_envolvido )
                    {
                    ?>
                        <?= $palestra_envolvido[ 'plt_nome' ] ?><?= ( sizeof( $busca_palestras_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>
                &nbsp;</td>
                <td bgcolor="#ffffff" class="text">
                <?
                if( is_array( $busca_alunos_gv_envolvidos ) )
                {
                    foreach( $busca_alunos_gv_envolvidos as $aluno_gv_envolvido )
                    {
                    ?>
                        <?= $aluno_gv_envolvido[ 'agv_nome' ] ?><?= ( sizeof( $busca_alunos_gv_envolvidos ) > 1 ? "<br>" : "" ) ?>
                    <?
                    }
                }
                ?>
                &nbsp;</td>
                </tr>
                <?
            }

            /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_psl'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="5" bgcolor="#ffffff">
	        <?
	    
	    /* se a pagina atual for maior que 1, mostrar seta pra voltar */
	    if( $list_data['pagina_num_psl'] > 1 )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=processos_seletivos&acao=procurar_processos_seletivos&busca_pagina_num_psl=<?= ($list_data["pagina_num_psl"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
	    }
    
	    for ($i = 1; $i <= $list_data["qt_paginas_psl"]; $i++)
	    { 
		if ($i == $list_data["pagina_num_psl"]) 
		    print ($i);
		else
		{
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=processos_seletivos&acao=procurar_processos_seletivos&busca_pagina_num_psl=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
		} 
	    }

	    /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
	    if( $list_data['qt_paginas_psl'] > $list_data['pagina_num_psl'] )
	    {
                ?>
                <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio&relatorio=processos_seletivos&acao=procurar_processos_seletivos&busca_pagina_num_psl=<?= ($list_data["pagina_num_psl"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
            }	    
            ?>
            </td>
            </tr>
            <?
	    }	    	    	
            ?>
            <tr>
                <td bgcolor="#ffffff" class="text" align='right' colspan='7'><a href='#' OnClick="window.open('<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=imprimir_relatorio&relatorio=<?= $relatorio ?>&acao=<?= $acao ?>', '', 'toolbar=yes, location=no, status=no, menubar=yes, scrollbars=yes, resizable=yes,width=640, height=480');"><img border='0' src='images/print.gif' /></a></td>
            </tr>
        <?

	}
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Nenhum processo seletivo foi encontrado.</td>
            </tr>
        <?
        }
        ?>

                <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_processos_seletivos ) && is_array( $busca_processos_seletivos ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case 'acesso_negado':
        include( ACESSO_NEGADO );
        break;
    default:
    ?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%" bgcolor="#ffffff" valign="top" height='<?= ALTURA_PADRAO ?>'><br>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td bgcolor="#ffffff" align='center' valign="top" height='<?= ALTURA_PADRAO ?>'><img src="images/trans.gif" width="1" height="20"  />
              <br />
                Relatórios
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <?
        break;
}
?>
</td>
</tr>
</table>
