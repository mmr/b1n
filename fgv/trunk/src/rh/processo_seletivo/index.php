<?
include_once( "include/funcoes.php" );


/* ------------------------------ Ações ------------------------------ */

extract_request_var( "acao", $acao );
if( isset( $acao ) )
{
    extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
    switch( $acao )
    {
        case "remover":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_APAGAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }

            extract_request_var( "tipo_remocao", $tipo_remocao );
            switch( $tipo_remocao )
            {
                /* ------------ Remover processo seletivo --------------- */
                case "processo_seletivo":
                    extract_request_var( "remover_processos_seletivos_ids", $remover_processos_seletivos_ids );

                    if( is_array( $remover_processos_seletivos_ids ) )
                    {
                        foreach( $remover_processos_seletivos_ids as $processo_seletivo_id )
                        {
                            $arquivo_gravado_processo = $sql->squery( "
                            SELECT DISTINCT
                                psl_arq_real
                            FROM
                                p_seletivo
                            WHERE
                                psl_id='" . $processo_seletivo_id . "'" );

                            if( $arquivo_gravado_processo[ 'psl_arq_real' ] != "" &&
                                file_exists( $gravar_arquivos_em . $arquivo_gravado_processo[ 'psl_arq_real' ] ) )
                            {
                                unlink( $gravar_arquivos_em . $arquivo_gravado_processo[ 'psl_arq_real' ] );
                            }

                            $resultado_query = $sql->query( "
                            DELETE FROM
                                p_seletivo
                            WHERE
                                psl_id = '" . $processo_seletivo_id . "'
                            ");
                        }

                        unset( $remover_processos_seletivos_ids );
                        unset( $processo_seletivo_id );
                    }
                    break;
                /* ------------ Remover consultor alocado --------------- */
                case "consultor_alocado":
                    extract_request_var( "membros_alocados_ids", $membros_alocados_ids );

                    if( is_array( $membros_alocados_ids ) )
                    {
                        foreach( $membros_alocados_ids as $membro_alocado_id )
                        {
                            $resultado_query = $sql->query( "
                            DELETE FROM
                                audita
                            WHERE
                                audita.psl_id = '" . $processo_seletivo_id . "' AND
                                audita.mem_id = '" . $membro_alocado_id . "'
                            ");

                            $resultado_query = $sql->query( "
                            DELETE FROM
                                acompanha
                            WHERE
                                mem_id = '" . $membro_alocado_id . "'
                            ");
                        }

                        unset( $membros_alocados_ids );
                        unset( $membro_alocado_id );
                    }
                    break;
                /* ------------ Remover empresa contratada --------------- */
                case "empresa_cadastrada":
                    extract_request_var( "empresas_contratadas_ids", $empresas_contratadas_ids );

                    if( is_array( $empresas_contratadas_ids ) )
                    {
                        foreach( $empresas_contratadas_ids as $empresa_cadastrada_id )
                        {
                            $resultado_query = $sql->query( "
                            DELETE FROM
                                abastece
                            WHERE
                                abastece.psl_id = '" . $processo_seletivo_id . "' AND
                                abastece.for_id = '" . $empresa_cadastrada_id . "'
                            ");
                        }

                        unset( $empresas_contratadas_ids );
                        unset( $empresa_cadastrada_id );
                    }
                    break;
                /* ------------ Remover palestra cadastrada --------------- */
                case "palestra_cadastrada":
                    extract_request_var( "palestras_cadastradas_ids", $palestras_cadastradas_ids );

                    if( is_array( $palestras_cadastradas_ids ) )
                    {
                        foreach( $palestras_cadastradas_ids as $palestra_cadastrada_id )
                        {
                            $resultado_query = $sql->query( "
                            DELETE FROM
                                palestra
                            WHERE
                                palestra.psl_id = '" . $processo_seletivo_id . "' AND
                                palestra.plt_id = '" . $palestra_cadastrada_id . "'
                            ");
                        }

                        unset( $palestras_cadastradas_ids );
                        unset( $palestra_cadastrada_id );
                    }
                    break;
                /* ------------ Remover aluno GV inscrito num processo seletivo --------------- */
                case "aluno_gv_inscrito":
                    extract_request_var( "remover_alunos_gv_inscritos_ids", $remover_alunos_gv_inscritos_ids );

                    if( is_array( $remover_alunos_gv_inscritos_ids ) )
                    {
                        foreach( $remover_alunos_gv_inscritos_ids as $aluno_gv_inscrito_id )
                        {
                            $resultado_query = $sql->query( "
                            DELETE FROM
                                candidato_psl
                            WHERE
                                psl_id = '" . $processo_seletivo_id . "' AND
                                agv_id = '" . $aluno_gv_inscrito_id . "'
                            ");

                            $resultado_query = $sql->query( "
                            DELETE FROM
                                candidato_din
                            WHERE
                                agv_id = '" . $aluno_gv_inscrito_id . "'
                            ");
                        }

                        unset( $remover_alunos_gv_inscritos_ids );
                        unset( $aluno_gv_inscrito_id );
                    }
                    break;
                /* ------------ Remover ou finalizar dinamica cadastrada --------------- */
                case "dinamica_cadastrada":
                    extract_request_var( "subacao", $subacao );
                    extract_request_var( "remover_dinamicas_cadastradas_ids", $remover_dinamicas_cadastradas_ids );

                    if( $subacao == "Remover" )
                    {
                        if( is_array( $remover_dinamicas_cadastradas_ids ) )
                        {
                            foreach( $remover_dinamicas_cadastradas_ids as $dinamica_cadastrada_id )
                            {
                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    acompanha
                                WHERE
                                    din_id = '" . $dinamica_cadastrada_id . "'
                                ");

                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    candidato_din
                                WHERE
                                    din_id = '" . $dinamica_cadastrada_id . "'
                                ");

                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    dinamica
                                WHERE
                                    psl_id = '" . $processo_seletivo_id . "' AND
                                    din_id = '" . $dinamica_cadastrada_id . "'
                                ");
                            }

                            unset( $remover_dinamicas_cadastradas_ids );
                            unset( $dincamica_cadastrada_id );
                        }
                    }
                    else if( $subacao == "Encerrar" )
                    {
                        if( is_array( $remover_dinamicas_cadastradas_ids ) )
                        {
                            foreach( $remover_dinamicas_cadastradas_ids as $dinamica_cadastrada_id )
                            {
                                $resultado_query = $sql->query( "
                                UPDATE
                                    candidato_din
                                SET
                                    cnd_status = '2'
                                WHERE
                                    din_id = '" . $dinamica_cadastrada_id . "' AND
                                    cnd_status = '0'" );
                            }

                            unset( $remover_dinamicas_cadastradas_ids );
                            unset( $dincamica_cadastrada_id );
                        }
                    }
                    break;
                /* ------------ Remover ou alterar candidatos de uma dinamica --------------- */
                case "candidato_dinamica":
                    extract_request_var( "subacao", $subacao );

                    if( $subacao == "Remover" )
                    {
                        extract_request_var( "remover_candidatos_dinamica_ids", $remover_candidatos_dinamica_ids );
                        extract_request_var( "dinamica_id", $dinamica_id );

                        if( is_array( $remover_candidatos_dinamica_ids ) )
                        {
                            foreach( $remover_candidatos_dinamica_ids as $candidato_dinamica_id )
                            {
                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    candidato_din
                                WHERE
                                    agv_id = '" . $candidato_dinamica_id . "' AND
                                    din_id = '" . $dinamica_id . "'
                                ");
                            }

                            unset( $remover_candidatos_dinamica_ids );
                            unset( $candidato_dinamica_id );
                        }
                    }
                    else if( $subacao == "Alterar" )
                    {
                        extract_request_var( "remover_candidatos_dinamica_ids", $remover_candidatos_dinamica_ids );
                        extract_request_var( "dinamica_id", $dinamica_id );

                        if( is_array( $remover_candidatos_dinamica_ids ) )
                        {
                            foreach( $remover_candidatos_dinamica_ids as $candidato_dinamica_id )
                            {
                                extract_request_var( "altera_candidato_dinamica_" . $candidato_dinamica_id , $status_candidato_dinamica );
                                extract_request_var( "altera_feedback_candidato_dinamica_" . $candidato_dinamica_id , $feedback_candidato_dinamica );

                                $resultado_query = $sql->query( "
                                UPDATE
                                    candidato_din
                                SET
                                    cnd_status = '" . $status_candidato_dinamica . "',
                                    cnd_fb_solic = '" . $feedback_candidato_dinamica . "'
                                WHERE
                                    din_id = '" . $dinamica_id . "' AND
                                    agv_id = '" . $candidato_dinamica_id . "'" );
                            }

                            unset( $remover_candidatos_dinamica_ids );
                            unset( $dinamica_id );
                            unset( $status_candidato_dinamica );
                        }
                    }
                    break;
                /* ------------ Remover ou alterar membros alocados para uma dinamica --------------- */
                case "membro_dinamica":
                    extract_request_var( "remover_membros_dinamica_ids", $remover_membros_dinamica_ids );
                    extract_request_var( "dinamica_id", $dinamica_id );

                    if( is_array( $remover_membros_dinamica_ids ) )
                    {
                        foreach( $remover_membros_dinamica_ids as $membro_dinamica_id )
                        {
                            $resultado_query = $sql->query( "
                            DELETE FROM
                                acompanha
                            WHERE
                                mem_id = '" . $membro_dinamica_id . "' AND
                                din_id = '" . $dinamica_id . "'
                            ");
                        }

                        unset( $remover_membros_dinamica_ids );
                        unset( $dinamica_id );
                    }
                    break;
                /* ------------ Remover ou alterar candidato de uma entrevista --------------- */
                case "candidato_entrevista":
                    extract_request_var( "subacao", $subacao );

                    if( $subacao == "Remover" )
                    {
                        extract_request_var( "remover_dinamicas_cadastradas_ids", $remover_dinamicas_cadastradas_ids );

                        if( is_array( $remover_dinamicas_cadastradas_ids ) )
                        {
                            foreach( $remover_dinamicas_cadastradas_ids as $dinamica_cadastrada_id )
                            {
                                $dinamica_aluno = explode( "-", $dinamica_cadastrada_id );

                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    acompanha
                                WHERE
                                    din_id = '" . $dinamica_aluno[ 0 ] . "'
                                ");

                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    candidato_din
                                WHERE
                                    din_id = '" . $dinamica_aluno[ 0 ] . "'
                                ");

                                $resultado_query = $sql->query( "
                                DELETE FROM
                                    dinamica
                                WHERE
                                    psl_id = '" . $processo_seletivo_id . "' AND
                                    din_id = '" . $dinamica_aluno[ 0 ] . "'
                                ");
                            }

                            unset( $remover_dinamicas_cadastradas_ids );
                            unset( $dincamica_cadastrada_id );
                        }
                    }
                    else if( $subacao == "Alterar" )
                    {
                        extract_request_var( "remover_dinamicas_cadastradas_ids", $remover_dinamicas_cadastradas_ids );

                        if( is_array( $remover_dinamicas_cadastradas_ids ) )
                        {
                            foreach( $remover_dinamicas_cadastradas_ids as $dinamica_cadastrada_id )
                            {
                                $dinamica_aluno = explode( "-", $dinamica_cadastrada_id );

                                extract_request_var( "altera_candidato_entrevista_" . $dinamica_aluno[ 0 ] . "_" . $dinamica_aluno[ 1 ], $status_candidato_entrevista );
                                extract_request_var( "altera_feedback_entrevista_" . $dinamica_aluno[ 0 ] . "_" . $dinamica_aluno[ 1 ], $feedback_candidato_entrevista );

                                $resultado_query = $sql->query( "
                                UPDATE
                                    candidato_din
                                SET
                                    cnd_status = '" . $status_candidato_entrevista . "',
                                    cnd_fb_solic = '" . $feedback_candidato_entrevista . "'
                                WHERE
                                    din_id = '" . $dinamica_aluno[ 0 ] . "' AND
                                    agv_id = '" . $dinamica_aluno[ 1 ] . "'" );
                            }

                            unset( $remover_dinamicas_cadastradas_ids );
                            unset( $dinamica_cadastrada_id );
                            unset( $dinamica_aluno );
                            unset( $status_candidato_entrevista );
                        }
                    }
                    break;
            }
            unset( $tipo_remocao );
            break;
        /* ------------ Cadastrar um novo processo seletivo --------------- */
        case "cadastrar_processo":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_INSERIR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "selecao_semestre", $selecao_semestre );
            extract_request_var( "selecao_ano", $selecao_ano );

            $data_selecao = $selecao_ano . "-" . ( $selecao_semestre == 1 ? "01" : "07" ) . "-01";

            $processo_cadastrado = $sql->query( "
            SELECT DISTINCT
                psl_id
            FROM
                p_seletivo
            WHERE
                psl_id='" . $processo_seletivo_id . "'" );

            if( !is_array( $processo_cadastrado ) &&
                $selecao_semestre != "" &&
                $selecao_ano != "" )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                    p_seletivo
                    (
                        psl_id,
                        psl_dt_selecao
                    )
                    VALUES
                    (
                        '" . $processo_seletivo_id . "',
                        '" . $data_selecao . "'
                    )" );

                if( $resultado_query )
                {
                    unset( $selecao_semestre );
                    unset( $selecao_ano );
                }
            }
            break;
        /* ------------ Alocar consultores para um processo seletivo --------------- */
        case "alocar_consultor":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "consultor_alocado_id", $consultor_alocado_id );

            $membro_alocado = $sql->squery( "
            SELECT DISTINCT
                audita.mem_id,
                membro_vivo.mem_nome
            FROM
                audita,
                membro_vivo
            WHERE
                audita.psl_id='" . $processo_seletivo_id . "' AND
                audita.mem_id='" . $consultor_alocado_id . "' AND
                audita.mem_id = membro_vivo.mem_id" );

            if( !is_array( $membro_alocado ) &&
                $consultor_alocado_id != "" )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                audita
                (
                    psl_id,
                    mem_id
                )
                VALUES
                (
                    '" . $processo_seletivo_id . "',
                    '" . $consultor_alocado_id . "'
                )" );

                if( $resultado_query )
                    unset( $consultor_alocado_id );
            }
	        break;
         /* ------------ Contratar empresas (fornecedores) para um processo seletivo --------------- */
        case "contratar_empresa":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "empresa_contratada_id", $empresa_contratada_id );

            $empresa_contratada = $sql->squery( "
            SELECT DISTINCT
                abastece.for_id,
                fornecedor.for_nome
            FROM
                abastece,
                fornecedor
            WHERE
                abastece.psl_id='" . $processo_seletivo_id . "' AND
                abastece.for_id='" . $empresa_contratada_id . "' AND
                abastece.for_id = fornecedor.for_id" );

            if( !is_array( $empresa_contratada ) &&
                $empresa_contratada_id != "" )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                abastece
                (
                    psl_id,
                    for_id
                )
                VALUES
                (
                    '" . $processo_seletivo_id . "',
                    '" . $empresa_contratada_id . "'
                )" );

                if( $resultado_query )
                    unset( $empresa_contratada_id );
            }
            break;
        /* ------------ Cadastrar palestras para um processo seletivo --------------- */
        case "cadastrar_palestra":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "palestra_nome",   $palestra_nome );
            extract_request_var( "palestra_dia",    $palestra_dia );
            extract_request_var( "palestra_mes",    $palestra_mes );
            extract_request_var( "palestra_ano",    $palestra_ano );
            extract_request_var( "palestra_hora",   $palestra_hora );
            extract_request_var( "palestra_minuto", $palestra_minuto );
            extract_request_var( "palestra_local",  $palestra_local );

            $palestra_data = $palestra_ano . "-" . $palestra_mes . "-" . $palestra_dia . " " .
                             $palestra_hora . ":" . $palestra_minuto;

            if( checkdate( $palestra_mes, $palestra_dia, $palestra_ano ) &&
                           $palestra_nome != "" )
            {
                $palestra_cadastrada = $sql->query( "
                SELECT DISTINCT
                    plt_id
                FROM
                    palestra
                WHERE
                    psl_id = '" . $processo_seletivo_id . "' AND
                    plt_nome = '" . $palestra_nome . "' AND
                    plt_local = '" . $palestra_local . "' AND
                    plt_dt = '" . $palestra_data . "'" );

                if( !is_array( $palestra_cadastrada ) )
                {
                    $resultado_query = $sql->query( "
                    INSERT INTO
                    palestra
                    (
                        psl_id,
                        plt_nome,
                        plt_local,
                        plt_dt
                    )
                    VALUES
                    (
                        '" . $processo_seletivo_id . "',
                        '" . $palestra_nome . "',
                        '" . $palestra_local . "',
                        '" . $palestra_data . "'
                    )" );

                    if( $resultado_query )
                    {
                        unset( $palestra_nome );
                        unset( $palestra_local );
                    }
                }
            }
            else if( $palestra_nome == "" )
                $status_cadastro_palestra = "Você não digitou um nome.";
            else if( !checkdate( $palestra_mes, $palestra_dia, $palestra_ano ) )
                $status_cadastro_palestra = "A data " . $palestra_dia . "/" . $palestra_mes . "/" . $palestra_ano . " é inválida";
            break;
        /* ------------ Cadastrar cronograma e upload de arquivo --------------- */
        case "gravar_arquivo_processo":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            $processo_seletivo_arquivo = $_FILES['processo_seletivo_arquivo']['name'];

            $arquivo_gravado = $sql->squery( "
            SELECT DISTINCT
                psl_arq_real
            FROM
                p_seletivo
            WHERE
                psl_id='" . $processo_seletivo_id . "'" );

            if( is_writeable( $gravar_arquivos_em ) && $processo_seletivo_arquivo != "" )
            {
                if( $arquivo_gravado[ 'psl_arq_real' ] != "" &&
                    file_exists( $gravar_arquivos_em . $arquivo_gravado[ 'psl_arq_real' ] ) )
                {
                    unlink( $gravar_arquivos_em . $arquivo_gravado[ 'psl_arq_real' ] );
                }
                $resultado_copy = copy( $_FILES['processo_seletivo_arquivo']['tmp_name'], $gravar_arquivos_em . "p_seletivo" . $processo_seletivo_id );

                if( $resultado_copy )
                {
                    $resultado_query = $sql->query( "
                    UPDATE
                        p_seletivo
                    SET
                        psl_arq_real = 'p_seletivo" . $processo_seletivo_id . "',
                        psl_arq_falso = '" . basename( $processo_seletivo_arquivo ) . "'
                    WHERE
                        psl_id = '" . $processo_seletivo_id . "'" );
                }
                else
                    $resultado_query = false;

                if( $resultado_copy && $resultado_query )
                    $status_upload_arquivo = "Arquivo gravado.";
                else
                    $status_upload_arquivo = "Não foi possivel gravar o arquivo.";
            }
            else if( !is_writeable( $gravar_arquivos_em ) )
                $status_upload_arquivo = "Você não tem permissão para gravar no diretório de arquivos.";
            break;
        /* ------------ Procurar alunos GV --------------- */
        case "procurar_inscrito_gv":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "inscrito_matricula",   $inscrito_matricula );
            extract_request_var( "inscrito_nome",    $inscrito_nome );

            $busca_inscrito_gv = $sql->query( "
            SELECT DISTINCT
                agv_id,
                agv_matricula,
                agv_telefone,
                agv_email,
                agv_nome
            FROM
                aluno_gv
            WHERE
                agv_matricula ILIKE '%" . $inscrito_matricula . "%' AND
                agv_nome ILIKE '%" . $inscrito_nome . "%'
            ORDER BY
                agv_nome" );
            break;
        /* ------------ Inscrever alunos GV num processo seletivo --------------- */
        case "inscrever_aluno_gv":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "alunos_gv_inscritos_ids", $alunos_gv_inscritos_ids );

            if( is_array( $alunos_gv_inscritos_ids ) )
            {
                foreach( $alunos_gv_inscritos_ids as $aluno_gv_id )
                {
                    $busca_inscrito = $sql->squery( "
                    SELECT DISTINCT
                        candidato_psl.agv_id,
                        agv_nome
                    FROM
                        candidato_psl,
                        aluno_gv
                    WHERE
                        psl_id = '" . $processo_seletivo_id . "' AND
                        candidato_psl.agv_id = '" . $aluno_gv_id . "' AND
                        candidato_psl.agv_id = aluno_gv.agv_id" );

                    if( !is_array( $busca_inscrito ) )
                    {
                        $resultado_query = $sql->query( "
                        INSERT INTO
                        candidato_psl
                        (
                            psl_id,
                            agv_id
                        )
                        VALUES
                        (
                            '" . $processo_seletivo_id . "',
                            '" . $aluno_gv_id . "'
                        )" );
                    }
                }
            }

            unset( $alunos_gv_inscritos_ids );
            break;
        /* ------------ Cadastrar uma dinamica --------------- */
        case "gravar_dinamica":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "dinamica_fase",     $dinamica_fase );
            extract_request_var( "dinamica_numero",   $dinamica_numero );
            extract_request_var( "dinamica_local",    $dinamica_local );
            extract_request_var( "dinamica_dia",      $dinamica_dia );
            extract_request_var( "dinamica_mes",      $dinamica_mes );
            extract_request_var( "dinamica_ano",      $dinamica_ano );
            extract_request_var( "dinamica_hora",     $dinamica_hora );
            extract_request_var( "dinamica_minuto",   $dinamica_minuto );

            $dinamica_data = $dinamica_ano . "-" . $dinamica_mes . "-" . $dinamica_dia . " " .
                             $dinamica_hora . ":" . $dinamica_minuto;

            if( checkdate( $dinamica_mes, $dinamica_dia, $dinamica_ano ) )
            {
                $dinamica_cadastrada = $sql->query( "
                SELECT DISTINCT
                    din_id
                FROM
                    dinamica
                WHERE
                    psl_id = '" . $processo_seletivo_id . "' AND
                    din_fase = '" . $dinamica_fase . "' AND
                    din_local = '" . $dinamica_local . "' AND
                    din_numero = '" . $dinamica_numero . "' AND
                    din_dt = '" . $dinamica_data . "'" );
            }

            if( checkdate( $dinamica_mes, $dinamica_dia, $dinamica_ano ) &&
                !is_array( $dinamica_cadastrada ) )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                dinamica
                (
                    psl_id,
                    din_fase,
                    din_local,
                    din_dt,
                    din_numero
                )
                VALUES
                (
                    '" . $processo_seletivo_id . "',
                    '" . $dinamica_fase . "',
                    '" . $dinamica_local . "',
                    '" . $dinamica_data . "',
                    '" . $dinamica_numero . "'
                )" );

                if( $resultado_query )
                {
                    unset( $dinamica_fase );
                    unset( $dinamica_local );
                    unset( $dinamica_numero );
                }
            }
            else if( !checkdate( $dinamica_mes, $dinamica_dia, $dinamica_ano ) )
                $status_cadastro_dinamica = "A data " . $dinamica_dia . "/" . $dinamica_mes . "/" . $dinamica_ano . " é inválida";
            break;
        /* ------------ Cadastrar uma entrevista --------------- */
        case "gravar_entrevista":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "candidato_entrevista_id", $candidato_entrevista_id );
            extract_request_var( "entrevista_local",        $entrevista_local );
            extract_request_var( "entrevista_dia",          $entrevista_dia );
            extract_request_var( "entrevista_mes",          $entrevista_mes );
            extract_request_var( "entrevista_ano",          $entrevista_ano );
            extract_request_var( "entrevista_hora",         $entrevista_hora );
            extract_request_var( "entrevista_minuto",       $entrevista_minuto );
            extract_request_var( "entrevista_feedback",       $entrevista_feedback );

            $entrevista_data = $entrevista_ano . "-" . $entrevista_mes . "-" . $entrevista_dia . " " .
                             $entrevista_hora . ":" . $entrevista_minuto;

            if( checkdate( $entrevista_mes, $entrevista_dia, $entrevista_ano ) )
            {
                $entrevista_cadastrada = $sql->query( "
                SELECT DISTINCT
                    dinamica.din_id,
                    candidato_din.agv_id
                FROM
                    dinamica,
                    candidato_din
                WHERE
                    psl_id = '" . $processo_seletivo_id . "' AND
                    din_fase = '3' AND
                    din_local = '" . $entrevista_local . "' AND
                    din_dt = '" . $entrevista_data . "' AND
                    dinamica.din_id = candidato_din.din_id AND
                    candidato_din.agv_id = '" . $candidato_entrevista_id . "'" );
            }

            if( checkdate( $entrevista_mes, $entrevista_dia, $entrevista_ano ) &&
                !is_array( $entrevista_cadastrada ) &&
                $candidato_entrevista_id != "" )
            {
                $dinamica_id = $sql->squery( "
                SELECT DISTINCT
                    nextval( 'dinamica_din_id_seq' )" );

                $aluno = $sql->squery( "
                SELECT DISTINCT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id = '" . $candidato_entrevista_id . "'" );

                $resultado_query = $sql->query( "
                INSERT INTO
                dinamica
                (
                    din_id,
                    psl_id,
                    din_ent_nome,
                    din_local,
                    din_dt,
                    din_fase
                )
                VALUES
                (
                    '" . $dinamica_id[ 'nextval' ] . "',
                    '" . $processo_seletivo_id . "',
                    'Entrevista candidato " . $aluno[ 'agv_nome' ] . "',
                    '" . $entrevista_local . "',
                    '" . $entrevista_data . "',
                    '3'
                )" );

                $resultado_query = $sql->query( "
                INSERT INTO
                candidato_din
                (
                    din_id,
                    agv_id,
                    cnd_fb_solic
                )
                VALUES
                (
                    '" . $dinamica_id[ 'nextval' ] . "',
                    '" . $candidato_entrevista_id . "',
                    '" . $entrevista_feedback . "'
                )" );

                if( $resultado_query )
                {
                    unset( $candidato_entrevista_id );
                    unset( $entrevista_local );
                    unset( $dinamica_id );
                    unset( $aluno );
                }
            }
            else if( !checkdate( $entrevista_mes, $entrevista_dia, $entrevista_ano ) )
                $status_cadastro_entrevista = "A data " . $entrevista_dia . "/" . $entrevista_mes . "/" . $entrevista_ano . " é inválida";
            break;
        /* ------------ Inscrever um aluno GV numa dinâmica --------------- */
        case "inscrever_candidato_dinamica":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "dinamica_id", $dinamica_id );
            extract_request_var( "candidato_dinamica_id", $candidato_dinamica_id );

            $candidato_dinamica = $sql->squery( "
            SELECT DISTINCT
                din_id,
                agv_id
            FROM
                candidato_din
            WHERE
                din_id='" . $dinamica_id . "' AND
                agv_id = '" . $candidato_dinamica_id . "'" );

            if( !is_array( $candidato_dinamica ) &&
                $dinamica_id != "" &&
                $candidato_dinamica_id != "" )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                candidato_din
                (
                    din_id,
                    agv_id
                )
                VALUES
                (
                    '" . $dinamica_id . "',
                    '" . $candidato_dinamica_id . "'
                )" );

                if( $resultado_query )
                {
                    unset( $dinamica_id );
                    unset( $candidato_dinamica_id );
                }
            }
            break;
        /* ------------ Inscrever um membro numa dinâmica --------------- */
        case "inscrever_membro_dinamica":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "dinamica_id", $dinamica_id );
            extract_request_var( "membro_dinamica_id", $membro_dinamica_id );

            $membro_dinamica = $sql->squery( "
            SELECT DISTINCT
                mem_id
            FROM
                acompanha
            WHERE
                din_id='" . $dinamica_id . "' AND
                mem_id = '" . $membro_dinamica_id . "'" );

            if( !is_array( $membro_dinamica ) &&
                $dinamica_id != "" &&
                $membro_dinamica_id != "" )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                acompanha
                (
                    din_id,
                    mem_id
                )
                VALUES
                (
                    '" . $dinamica_id . "',
                    '" . $membro_dinamica_id . "'
                )" );

                if( $resultado_query )
                {
                    unset( $dinamica_id );
                    unset( $membro_dinamica_id );
                }
            }
            break;
        case "alterar_aluno_reprovado":
            if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
            {
                $subpagina = "acesso_negado";
                break;
            }
            extract_request_var( "alunos_reprovados_selecionados_ids", $alunos_reprovados_selecionados_ids );

            if( is_array( $alunos_reprovados_selecionados_ids ) )
            {
                foreach( $alunos_reprovados_selecionados_ids as $aluno_reprovado_selecionado )
                {
                    $aluno_dinamica = explode( "-", $aluno_reprovado_selecionado );

                    extract_request_var( "feedback_solicitado_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_solicitado );
                    extract_request_var( "feedback_dia_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_dia );
                    extract_request_var( "feedback_mes_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_mes );
                    extract_request_var( "feedback_ano_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_ano );
                    extract_request_var( "feedback_hora_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_hora );
                    extract_request_var( "feedback_minuto_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_minuto );
                    extract_request_var( "membro_feedback_id_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $membro_feedback_id );
                    extract_request_var( "feedback_realizado_" . $aluno_dinamica[ 0 ] . "_" . $aluno_dinamica[ 1 ], $feedback_realizado );

                    $data_feedback = $feedback_ano . "-" . $feedback_mes . "-" . $feedback_dia . " " .
                                    $feedback_hora . ":" . $feedback_minuto;

                    $resultado_query = $sql->query( "
                    UPDATE
                        candidato_din
                    SET
                        cnd_fb_solic = '" . $feedback_solicitado . "',
                        " . ( checkdate( $feedback_mes, $feedback_dia, $feedback_ano ) ? "cnd_fb_dt = '" . $data_feedback . "'," : "" ) . "
                        cnd_fb_mem_id = '" . $membro_feedback_id . "',
                        cnd_fb_realizado = '" . $feedback_realizado . "'
                    WHERE
                        din_id = '" . $aluno_dinamica[ 1 ] . "' AND
                        agv_id = '" . $aluno_dinamica[ 0 ] . "'" );
                }

                unset( $alunos_reprovados_selecionados_ids );
                unset( $aluno_reprovado_selecionado );
                unset( $aluno_dinamica );
                unset( $feedback_solicitado );
                unset( $feedback_dia );
                unset( $feedback_mes );
                unset( $feedback_ano );
                unset( $feedback_hora );
                unset( $feedback_minuto );
                unset( $membro_feedback_id );
                unset( $feedback_realizado );
            }
            break;
    }
}


/* ------------------------------ Sub-página ------------------------------ */


extract_request_var( "subpagina", $subpagina );
extract_request_var( "colspan", $colspan );
switch( $subpagina )
{
    /* ------------ Novo PS - Semestre e ano --------------- */
    case "semestre_ano_selecao":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_INSERIR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $processo_seletivo_id = $sql->squery( "
        SELECT DISTINCT
            nextval( 'p_seletivo_psl_id_seq' )" );
        ?>

        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="alocar_consultores" />
        <input type="hidden" name="acao" value="cadastrar_processo" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id[ 'nextval' ] ?>" />

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Novo Processo Seletivo</td>
        </tr>

        <tr>
        <td bgcolor="#ffffff" class="text">
        Semestre / Ano de seleção:
        <? faz_select_sequencia( "selecao_semestre", "semestre" ); ?> /
        <? faz_select_sequencia( "selecao_ano", "ano" ); ?>
        </td>
        </tr>

        <tr>
        <td bgcolor="#ffffff" class="text">
        <input type="submit" value="Inserir" />
        <input type="button" value="Cancelar" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </td>
        </tr>
        </table>
   </td></tr>
      </table>        </form>
        <?
        break;
    /* ------------ PS - Alocar consultores --------------- */
    case "alocar_consultores":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $busca_membros = $sql->query( "
        SELECT DISTINCT
            mem_id,
            mem_nome
        FROM
            membro_vivo
        ORDER BY
            mem_nome" );

        $busca_consultores_alocados = $sql->query( "
        SELECT DISTINCT
            audita.mem_id,
            membro_vivo.mem_nome,
            membro_vivo.mem_telefone,
            membro_vivo.mem_email
        FROM
            audita,
            membro_vivo
        WHERE
            audita.psl_id = '" . $processo_seletivo_id . "' AND
            audita.mem_id = membro_vivo.mem_id
        ORDER BY
            mem_nome" );
        ?>

        <br /><br /><center>
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_consultores_alocados ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alocar Consultores</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_consultores_alocados ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="alocar_consultores" />
        <input type="hidden" name="acao" value="alocar_consultor" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <? faz_select( "consultor_alocado_id", $busca_membros, "mem_id", "mem_nome" ); ?>
        <input type="submit" value="Alocar" />
        </form>
        </td>
        </tr>

        <?
        if( is_array( $busca_consultores_alocados ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="alocar_consultores" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="consultor_alocado" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <? foreach( $busca_consultores_alocados as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="membros_alocados_ids[]" value="<?= $tupla[ 'mem_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_email' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_consultores_alocados ) ? "4" : "1" ) ?>">
            <input type="submit" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhum consultor alocado.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_consultores_alocados ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="empresas_contratadas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br /><input type="submit" value="Empresas >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_consultores_alocados ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>      <?
        break;
    /* ------------ PS - Contratar empresas (fornecedores) --------------- */
    case "empresas_contratadas":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $busca_fornecedores = $sql->query( "
        SELECT DISTINCT
            for_id,
            for_nome
        FROM
            fornecedor
        ORDER BY
            for_nome" );

        $busca_empresas_contratadas = $sql->query( "
        SELECT DISTINCT
            fornecedor.for_id,
            fornecedor.for_nome,
            fornecedor.for_telefone,
            fornecedor.for_email,
            ramo.ram_nome
        FROM
            abastece,
            fornecedor,
            ramo,
            status_contato
        WHERE
            abastece.psl_id = '" . $processo_seletivo_id . "' AND
            abastece.for_id = fornecedor.for_id AND
            fornecedor.ram_id = ramo.ram_id
        ORDER BY
            for_nome" );
        ?>

        <br /><br /><center>
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_empresas_contratadas ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Empresas Contratadas</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_empresas_contratadas ) ? "5" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="empresas_contratadas" />
        <input type="hidden" name="acao" value="contratar_empresa" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <? faz_select( "empresa_contratada_id", $busca_fornecedores, "for_id", "for_nome" ); ?>
        <input type="submit" value="Contratar" />
        </form>
        </td>
        </tr>
        <?
        if( is_array( $busca_empresas_contratadas ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="empresas_contratadas" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="empresa_cadastrada" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Ramo</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_empresas_contratadas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="empresas_contratadas_ids[]" value="<?= $tupla[ 'for_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'ram_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'for_email' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_empresas_contratadas ) ? "5" : "1" ) ?>">
            <input type="submit" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhuma empresa contratada.</td>
            </tr>
            <tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_empresas_contratadas ) ? "5" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="palestras_apresentacao" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br />
        <input type="button" value="<< Consultores" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=alocar_consultores&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="submit" value="Palestras >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_empresas_contratadas ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>
      <?
        break;
    /* ------------ PS - Cadastrar palestras de apresentação --------------- */
    case "palestras_apresentacao":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $busca_palestras_cadastradas = $sql->query( "
        SELECT DISTINCT
            plt_id,
            plt_nome,
            plt_local,
            date_part( 'epoch', plt_dt ) AS plt_timestamp
        FROM
            palestra
        WHERE
            palestra.psl_id = '" . $processo_seletivo_id . "'
        ORDER BY
            plt_timestamp DESC,
            plt_nome" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Palestras de Apresentação</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="palestras_apresentacao" />
        <input type="hidden" name="acao" value="cadastrar_palestra" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        Nome: <input type="text" name="palestra_nome" value="<?= isset( $palestra_nome ) ? $palestra_nome : "" ?>" />
        Data:
        <select name="palestra_dia">
            <?
            $data_atual = getdate();
            $selecionado = ( $palestra_dia != "" ? $palestra_dia : $data_atual[ 'mday' ] );
            print( "-----" );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="palestra_mes">
            <?
            $data_atual = getdate();
            $selecionado = ( $palestra_mes != "" ? $palestra_mes : $data_atual[ 'mon' ] );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="palestra_ano">
            <?
            $data_atual = getdate();
            $selecionado = ( $palestra_ano != "" ? $palestra_ano : $data_atual[ 'year' ] );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        <br /><br />
        Local: <input type="text" name="palestra_local" value="<?= isset( $palestra_local ) ? $palestra_local : "" ?>" />
        Hora:
        <select name="palestra_hora">
            <?
            $data_atual = getdate();
            $selecionado = ( $palestra_hora != "" ? $palestra_hora : $data_atual[ 'hours' ] );
            for( $hora = 0; $hora < 24; $hora++ )
            {
            ?>
                <option value="<?= $hora ?>" <?= ( $hora == $selecionado ? "selected" : "" ) ?>><?= $hora ?></option>
            <?
            }
            ?>
        </select> :
        <select name="palestra_minuto">
            <?
            $data_atual = getdate();
            $selecionado = ( $palestra_minuto != "" ? $palestra_minuto : round( $data_atual[ 'minutes' ] / 10 ) * 10 );
            for( $minuto = "00"; $minuto <= 50; $minuto += 10 )
            {
            ?>
                <option value="<?= $minuto ?>" <?= ( $minuto == $selecionado ? "selected" : "" ) ?>><?= $minuto ?></option>
            <?
            }
            ?>
        </select>
        <input type="submit" value="Cadastrar" />
        </form>
        </td>
        </tr>

        <?
        if( isset( $status_cadastro_palestra ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">
            <?= $status_cadastro_palestra ?>
            </td>
            </tr>
        <?
        }

        if( is_array( $busca_palestras_cadastradas ) )
        {
        ?>

            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="palestras_apresentacao" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="palestra_cadastrada" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Data</b></td>
            <td bgcolor="#ffffff" class="text"><b>Hora</b></td>
            <td bgcolor="#ffffff" class="text"><b>Local</b></td>
            </tr>

            <?
            foreach( $busca_palestras_cadastradas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="palestras_cadastradas_ids[]" value="<?= $tupla[ 'plt_id' ] ?>"></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'plt_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'plt_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'plt_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'plt_local' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">
            <input type="submit" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">Não há nenhuma palestra cadastrada.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="cronograma_processo" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br />
        <input type="button" value="<< Empresas" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=empresas_contratadas&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="submit" value="Cronograma >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_palestras_cadastradas ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>      <?
        break;
    /* ------------ PS - Cadastrar cronograma e upload de arquivo --------------- */
    case "cronograma_processo":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        extract_request_var( "processo_seletivo_id", $processo_seletivo_id );

        $arquivo_gravado = $sql->squery( "
        SELECT DISTINCT
            psl_arq_real,
            psl_arq_falso
        FROM
            p_seletivo
        WHERE
            psl_id='" . $processo_seletivo_id . "'" );
        ?>
        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Cronograma</td>
        </tr>
        <? if( isset( $status_upload_arquivo ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">
            <?= $status_upload_arquivo ?>
            </td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td bgcolor="#ffffff" class="text">
        <?= ( $arquivo_gravado[ 'psl_arq_real' ] != "" ) ? "Arquivo do cronograma: <a href=\"" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $processo_seletivo_id . "&col_id=psl_id&tabela=p_seletivo&arq_col_r=psl_arq_real&arq_col_f=psl_arq_falso\">" . $arquivo_gravado[ 'psl_arq_falso' ] . "</a>" : "Nenhum cronograma foi gravado." ?>
        </tr>

        <tr>
        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="cronograma_processo" />
        <input type="hidden" name="acao" value="gravar_arquivo_processo" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <input type="file" name="processo_seletivo_arquivo" />
        <input type="submit" value="Fazer Upload" />
        </form>
        </td>
        </tr>

        <tr>
        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="inscricoes" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br />
        <input type="button" value="<< Palestras" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=palestras_apresentacao&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="submit" value="Inscrições >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>        <?
        break;
    /* ------------ PS - Inscrever alunos --------------- */
    case "inscricoes":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $busca_inscritos_processo = $sql->query( "
        SELECT DISTINCT
            aluno_gv.agv_id,
            aluno_gv.agv_matricula,
            aluno_gv.agv_nome,
            aluno_gv.agv_telefone,
            aluno_gv.agv_email
        FROM
            candidato_psl,
            aluno_gv
        WHERE
            psl_id = '" . $processo_seletivo_id . "' AND
            candidato_psl.agv_id = aluno_gv.agv_id
        ORDER BY
            agv_nome" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_inscritos_processo ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Inscritos</td>
        </tr>

        <?
        if( is_array( $busca_inscritos_processo ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="inscricoes" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="aluno_gv_inscrito" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Matrícula</b></td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_inscritos_processo as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="remover_alunos_gv_inscritos_ids[]" value="<?= $tupla[ 'agv_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_matricula' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_email' ] ?></td>
                </tr>
            <?
            }
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_inscritos_processo ) ? "5" : "1" ) ?>">
            <input type="submit" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhum aluno inscrito.</td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_inscritos_processo ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR> 
      
      
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Busca</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="inscricoes" />
        <input type="hidden" name="acao" value="procurar_inscrito_gv" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <b>Matrícula:</b><input type="text" name="inscrito_matricula" value="<?= isset( $inscrito_matricula ) ? $inscrito_matricula : "" ?>" />
        </td>
        <td bgcolor="#ffffff" class="text"><b>Nome:</b> <input type="text" name="inscrito_nome" value="<?= isset( $inscrito_nome ) ? $inscrito_nome : "" ?>"/></td>
        <td bgcolor="#ffffff" class="text">
        <input type="submit" value="Procurar" />
        </form>
        </td>
        </tr>
              <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="3">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR>

<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_inscrito_gv ) && is_array( $busca_inscrito_gv ) ? "5" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Resultados</td>
        </tr>
        <tr>
        <?
        if( isset( $busca_inscrito_gv ) && is_array( $busca_inscrito_gv ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="inscricoes" />
            <input type="hidden" name="acao" value="inscrever_aluno_gv" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Matrícula</b></td>
            <td bgcolor="#ffffff" class="text"><b>Aluno</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <? foreach( $busca_inscrito_gv as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="alunos_gv_inscritos_ids[]" value="<?= $tupla[ 'agv_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_matricula' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_email' ] ?></td>
                </tr>
            <?
            }
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="5">
            <input type="submit" value="Inscrever" /><br /><br />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
            if( isset( $busca_inscrito_gv ) && !is_array( $busca_inscrito_gv ) )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">Não foi encontrado nenhum aluno.<br /><br />
                </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( isset( $busca_inscrito_gv ) && is_array( $busca_inscrito_gv ) ? "5" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="dinamicas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br />
        <input type="button" value="<< Cronograma" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=cronograma_processo&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="submit" value="Dinâmicas >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
              <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_inscrito_gv ) && is_array( $busca_inscrito_gv ) ? "5" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR> </center>        <?
        break;
    /* ------------ PS - Cadastrar dinâmicas --------------- */
    case "dinamicas":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        $busca_dinamicas_cadastradas = $sql->query( "
        SELECT DISTINCT
            psl_id,
            din_id,
            din_fase,
            din_local,
            date_part( 'epoch', din_dt ) AS din_timestamp,
            din_numero
        FROM
            dinamica
        WHERE
            psl_id = '" . $processo_seletivo_id . "' AND
            din_fase != 3
        ORDER BY
            din_timestamp DESC,
            din_fase,
            din_numero" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Dinâmicas</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="dinamicas" />
        <input type="hidden" name="acao" value="gravar_dinamica" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        Fase:
        <select name="dinamica_fase">
            <option value="1">Primeira</option>
            <option value="2">Segunda</option>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        Data:
        <select name="dinamica_dia">
            <?
            $data_atual = getdate();
            $selecionado = ( $dinamica_dia != "" ? $dinamica_dia : $data_atual[ 'mday' ] );
            print( "-----" );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="dinamica_mes">
            <?
            $data_atual = getdate();
            $selecionado = ( $dinamica_mes != "" ? $dinamica_mes : $data_atual[ 'mon' ] );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="dinamica_ano">
            <?
            $data_atual = getdate();
            $selecionado = ( $dinamica_ano != "" ? $dinamica_ano : $data_atual[ 'year' ] );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        Local: <input type="text" name="dinamica_local" />
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">
        No. dinâmica: <?= faz_select_sequencia( "dinamica_numero", 1, 1, 99 ); ?>
        </td>
        <td bgcolor="#ffffff" class="text">
        Hora:
        <select name="dinamica_hora">
            <?
            $data_atual = getdate();
            $selecionado = ( $dinamica_hora != "" ? $dinamica_hora : $data_atual[ 'hours' ] );
            for( $hora = 0; $hora < 24; $hora++ )
            {
            ?>
                <option value="<?= $hora ?>" <?= ( $hora == $selecionado ? "selected" : "" ) ?>><?= $hora ?></option>
            <?
            }
            ?>
        </select> :
        <select name="dinamica_minuto">
            <?
            $data_atual = getdate();
            $selecionado = ( $dinamica_minuto != "" ? $dinamica_minuto : round( $data_atual[ 'minutes' ] / 10 ) * 10 );
            for( $minuto = "00"; $minuto <= 50; $minuto += 10 )
            {
            ?>
                <option value="<?= $minuto ?>" <?= ( $minuto == $selecionado ? "selected" : "" ) ?>><?= $minuto ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        <input type="submit" value="Inserir" />
        </form>
        </td>
        </tr>
        </table>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <?
        if( isset( $status_cadastro_dinamica ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_dinamicas_cadastradas ) ? "8" : "1" ) ?>">
            <?= $status_cadastro_dinamica ?>
            </td>
            </tr>
        <?
        }

        if( is_array( $busca_dinamicas_cadastradas ) )
        {
            $fases_texto[ 1 ] = "Primeira";
            $fases_texto[ 2 ] = "Segunda";
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="dinamicas" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="dinamica_cadastrada" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>No</b></td>
            <td bgcolor="#ffffff" class="text"><b>Fase</b></td>
            <td bgcolor="#ffffff" class="text"><b>Local</b></td>
            <td bgcolor="#ffffff" class="text"><b>Data</b></td>
            <td bgcolor="#ffffff" class="text"><b>Hora</b></td>
            <td bgcolor="#ffffff" class="text"><b>Candidatos</b></td>
            <td bgcolor="#ffffff" class="text"><b>Membros</b></td>
            </tr>

            <?
            foreach( $busca_dinamicas_cadastradas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="remover_dinamicas_cadastradas_ids[]" value="<?= $tupla[ 'din_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'din_numero' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $fases_texto[ $tupla[ 'din_fase' ] ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'din_local' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'din_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'din_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <input type="button" value="Candidatos" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=processo_seletivo_candidatos&processo_seletivo_id=<?= $tupla[ 'psl_id' ] ?>&dinamica_id=<?= $tupla[ 'din_id' ] ?>'" />
                </td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <input type="button" value="Membros" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=processo_seletivo_membros&processo_seletivo_id=<?= $tupla[ 'psl_id' ] ?>&dinamica_id=<?= $tupla[ 'din_id' ] ?>'" />
                </td>
                </tr>
            <?
            }
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="8">
            <input type="submit" name="subacao" value="Remover" />
            <input type="submit" name="subacao" value="Encerrar" />
            </form>
            </td>
            </tr>

        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhuma dinâmica cadastrada.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_dinamicas_cadastradas ) ? "8" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="entrevistas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br />
        <input type="button" value="<< Inscrições" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=inscricoes&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="submit" value="Entrevistas >>" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_dinamicas_cadastradas ) ? "8" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR>         <?
        break;
    /* ------------ PS - Inscrever alunos GV em dinâmica --------------- */
    case "processo_seletivo_candidatos":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
        extract_request_var( "dinamica_id", $dinamica_id );

        $fase_dinamica = $sql->squery( "
        SELECT DISTINCT
            din_fase
        FROM
            dinamica
        WHERE
            din_id = '" . $dinamica_id . "'" );

        if( $fase_dinamica[ 'din_fase' ] == 2 )
        {
            $and_para_fase = " AND agv_id IN( SELECT agv_id FROM dinamica NATURAL JOIN candidato_din WHERE psl_id = '" . $processo_seletivo_id . "' AND din_fase = '1' AND cnd_status = '1' )";
        }
        else
        {
            $and_para_fase = "";
        }

        $busca_inscritos_processo = $sql->query( "
        SELECT DISTINCT
            agv_id,
            agv_matricula,
            agv_nome,
            agv_telefone,
            agv_email
        FROM
            candidato_psl
            NATURAL JOIN aluno_gv
        WHERE
            psl_id = '" . $processo_seletivo_id . "'" . $and_para_fase . "
        ORDER BY
            agv_nome" );

        $busca_inscritos_dinamica = $sql->query( "
        SELECT DISTINCT
            agv_id,
            agv_matricula,
            agv_nome,
            agv_telefone,
            agv_email,
            cnd_status,
            cnd_fb_solic
        FROM
            candidato_din
            NATURAL JOIN aluno_gv
        WHERE
            din_id = '" . $dinamica_id . "'
        ORDER BY
            agv_nome" );
        ?>

        <br /><br />
        <center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_inscritos_dinamica ) ? "7" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Dinâmica - Candidatos</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_inscritos_dinamica ) ? "7" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="processo_seletivo_candidatos" />
        <input type="hidden" name="acao" value="inscrever_candidato_dinamica" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
        <? faz_select( "candidato_dinamica_id", $busca_inscritos_processo, "agv_id", "agv_nome" ); ?>
        <input type="submit" value="Candidatar" />
        </form>
        </td>
        </tr>

        <?
        if( is_array( $busca_inscritos_dinamica ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post" name="form_candidatos">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="processo_seletivo_candidatos" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="candidato_dinamica" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Matrícula</b></td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            <td bgcolor="#ffffff" class="text"><b>Status</b></td>
            <td bgcolor="#ffffff" class="text"><b>Feedback?</b></td>
            </tr>

            <?
             $i = 7;
             foreach( $busca_inscritos_dinamica as $tupla )
             {
             ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="remover_candidatos_dinamica_ids[]" value="<?= $tupla[ 'agv_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_matricula' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_email' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <select name="altera_candidato_dinamica_<?= $tupla[ 'agv_id' ] ?>" onchange="document.form_candidatos.elements[<?= $i ?>].checked = true;">
                    <option value="0" <?= ( $tupla[ 'cnd_status' ] == "0" ? "selected" : "" ) ?>>Novo</option>
                    <option value="1" <?= ( $tupla[ 'cnd_status' ] == "1" ? "selected" : "" ) ?>>Aprovado</option>
                    <option value="2" <?= ( $tupla[ 'cnd_status' ] == "2" ? "selected" : "" ) ?>>Reprovado</option>
                </select>
                </td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <select name="altera_feedback_candidato_dinamica_<?= $tupla[ 'agv_id' ] ?>" onchange="document.form_candidatos.elements[<?= $i ?>].checked = true;">
                    <option value="0" <?= ( $tupla[ 'cnd_fb_solic' ] == "0" ? "selected" : "" ) ?>>Não</option>
                    <option value="1" <?= ( $tupla[ 'cnd_fb_solic' ] == "1" ? "selected" : "" ) ?>>Sim</option>
                </select>
                </td>
                </tr>
            <?
                $i+=3;
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">
            <input type="submit" name="subacao" value="Remover" />
            <input type="submit" name="subacao" value="Alterar" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhum candidato para esta dinâmica.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_inscritos_dinamica ) ? "7" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="dinamicas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br /><input type="submit" value="   OK   " />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_inscritos_dinamica ) ? "7" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR>
      <?
        break;
    /* ------------ PS - Inscrever membros em dinâmica --------------- */
    case "processo_seletivo_membros":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
        extract_request_var( "dinamica_id", $dinamica_id );

        $busca_membros_processo = $sql->query( "
        SELECT DISTINCT
            audita.mem_id,
            membro_vivo.mem_nome,
            membro_vivo.mem_telefone,
            membro_vivo.mem_email
        FROM
            audita,
            membro_vivo
        WHERE
            psl_id = '" . $processo_seletivo_id . "' AND
            audita.mem_id = membro_vivo.mem_id
        ORDER BY
            mem_nome" );

        $busca_membros_dinamica = $sql->query( "
        SELECT DISTINCT
            acompanha.mem_id,
            membro_vivo.mem_nome,
            membro_vivo.mem_telefone,
            membro_vivo.mem_email
        FROM
            acompanha,
            membro_vivo
        WHERE
            acompanha.din_id = '" . $dinamica_id . "' AND
            acompanha.mem_id = membro_vivo.mem_id
        ORDER BY
            mem_nome" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_membros_dinamica ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Dinâmica - Membros</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_membros_dinamica ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="processo_seletivo_membros" />
        <input type="hidden" name="acao" value="inscrever_membro_dinamica" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
        <? faz_select( "membro_dinamica_id", $busca_membros_processo, "mem_id", "mem_nome" ); ?>
        <input type="submit" value="Alocar" />
        </form>
        </td>
        </tr>
        <?
        if( is_array( $busca_membros_dinamica ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="processo_seletivo_membros" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="membro_dinamica" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_membros_dinamica as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="remover_membros_dinamica_ids[]" value="<?= $tupla[ 'mem_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_email' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_membros_dinamica ) ? "4" : "1" ) ?>">
            <input type="submit" name="subacao" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhum membro alocado para esta dinâmica.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_membros_dinamica ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="dinamicas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br /><input type="submit" value="   OK   " />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_membros_dinamica ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR>          <?
        break;
    /* ------------ PS - Cadastrar entrevistas --------------- */
    case "entrevistas":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }

        $busca_inscritos_processo = $sql->query( "
        SELECT DISTINCT
            agv_id,
            agv_matricula,
            agv_nome,
            agv_telefone,
            agv_email
        FROM
            candidato_psl
            NATURAL JOIN aluno_gv
        WHERE
            psl_id = '" . $processo_seletivo_id . "'
            AND agv_id IN( SELECT agv_id FROM dinamica NATURAL JOIN candidato_din WHERE psl_id = '" . $processo_seletivo_id . "' AND din_fase = '2' AND cnd_status = '1' )
        ORDER BY
            agv_nome" );

        $busca_membros_processo = $sql->query( "
        SELECT DISTINCT
            audita.mem_id,
            membro_vivo.mem_nome,
            membro_vivo.mem_telefone,
            membro_vivo.mem_email
        FROM
            audita,
            membro_vivo
        WHERE
            psl_id = '" . $processo_seletivo_id . "' AND
            audita.mem_id = membro_vivo.mem_id
        ORDER BY
            mem_nome" );

        $busca_entrevistas_cadastradas = $sql->query( "
        SELECT DISTINCT
            dinamica.psl_id,
            dinamica.din_id,
            dinamica.din_local,
            dinamica.din_ent_nome,
            date_part( 'epoch', din_dt ) AS din_timestamp,
            candidato_din.agv_id,
            candidato_din.cnd_status,
            candidato_din.cnd_fb_solic,
            aluno_gv.agv_nome
        FROM
            dinamica,
            candidato_din,
            aluno_gv
        WHERE
            psl_id = '" . $processo_seletivo_id . "' AND
            din_fase = '3' AND
            dinamica.din_id = candidato_din.din_id AND
            candidato_din.agv_id = aluno_gv.agv_id
        ORDER BY
            din_timestamp DESC,
            agv_nome" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="3" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Entrevistas</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="entrevistas" />
        <input type="hidden" name="acao" value="gravar_entrevista" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        Candidato:
        <? faz_select( "candidato_entrevista_id", $busca_inscritos_processo, "agv_id", "agv_nome" ); ?>
        </td>
        <td bgcolor="#ffffff" class="text">
        Data:
        <select name="entrevista_dia">
            <?
            $data_atual = getdate();
            $selecionado = ( $entrevista_dia != "" ? $entrevista_dia : $data_atual[ 'mday' ] );
            print( "-----" );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="entrevista_mes">
            <?
            $data_atual = getdate();
            $selecionado = ( $entrevista_mes != "" ? $entrevista_mes : $data_atual[ 'mon' ] );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="entrevista_ano">
            <?
            $data_atual = getdate();
            $selecionado = ( $entrevista_ano != "" ? $entrevista_ano : $data_atual[ 'year' ] );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        Feedback:
        <select name="entrevista_feedback" onchange="document.form_candidatos.elements[<?= $i ?>].checked = true;">
            <option value="0" <?= ( isset( $entrevista_feedback ) && $entrevista_feedback == 0 ? "selected" : "" ) ?>>Não</option>
            <option value="1" <?= ( isset( $entrevista_feedback ) && $entrevista_feedback == 1 ? "selected" : "" ) ?>>Sim</option>
        </select>
        </td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">
        Local: <input type="text" name="entrevista_local" /><br /><br />
        </td>
        <td bgcolor="#ffffff" class="text">
        Hora:
        <select name="entrevista_hora">
            <?
            $data_atual = getdate();
            $selecionado = ( $entrevista_hora != "" ? $entrevista_hora : $data_atual[ 'hours' ] );
            for( $hora = 0; $hora < 24; $hora++ )
            {
            ?>
                <option value="<?= $hora ?>" <?= ( $hora == $selecionado ? "selected" : "" ) ?>><?= $hora ?></option>
            <?
            }
            ?>
        </select> :
        <select name="entrevista_minuto">
            <?
            $data_atual = getdate();
            $selecionado = ( $entrevista_minuto != "" ? $entrevista_minuto : round( $data_atual[ 'minutes' ] / 10 ) * 10 );
            for( $minuto = "00"; $minuto <= 50; $minuto += 10 )
            {
            ?>
                <option value="<?= $minuto ?>" <?= ( $minuto == $selecionado ? "selected" : "" ) ?>><?= $minuto ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        <input type="submit" value="Inserir" />
        </form>
        </td>
        </tr>
        </table>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <?
        if( isset( $status_cadastro_entrevista ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="9">
            <?= $status_cadastro_entrevista ?>
            </td>
            </tr>
        <?
        }

        if( is_array( $busca_entrevistas_cadastradas ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post" name="form_entrevistas">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="entrevistas" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="candidato_entrevista" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Candidato</b></td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Local</b></td>
            <td bgcolor="#ffffff" class="text"><b>Data</b></td>
            <td bgcolor="#ffffff" class="text"><b>Hora</b></td>
            <td bgcolor="#ffffff" class="text"><b>Status</b></td>
            <td bgcolor="#ffffff" class="text"><b>Feedback?</b></td>
            <td bgcolor="#ffffff" class="text"><b>Membros</b></td>
            </tr>

            <?
            $i = 6;
            foreach( $busca_entrevistas_cadastradas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="remover_dinamicas_cadastradas_ids[]" value="<?= $tupla[ 'din_id' ] . "-" . $tupla[ 'agv_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'din_ent_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'din_local' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'din_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'din_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <select name="altera_candidato_entrevista_<?= $tupla[ 'din_id' ] ?>_<?= $tupla[ 'agv_id' ] ?>" onchange="document.form_entrevistas.elements[<?= $i ?>].checked = true;">
                    <option value="0" <?= ( $tupla[ 'cnd_status' ] == "0" ? "selected" : "" ) ?>>Novo</option>
                    <option value="1" <?= ( $tupla[ 'cnd_status' ] == "1" ? "selected" : "" ) ?>>Aprovado</option>
                    <option value="2" <?= ( $tupla[ 'cnd_status' ] == "2" ? "selected" : "" ) ?>>Reprovado</option>
                </select>
                </td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <select name="altera_feedback_entrevista_<?= $tupla[ 'din_id' ] ?>_<?= $tupla[ 'agv_id' ] ?>" onchange="document.form_entrevistas.elements[<?= $i ?>].checked = true;">
                    <option value="0" <?= ( $tupla[ 'cnd_fb_solic' ] == "0" ? "selected" : "" ) ?>>Não</option>
                    <option value="1" <?= ( $tupla[ 'cnd_fb_solic' ] == "1" ? "selected" : "" ) ?>>Sim</option>
                </select>
                </td>
                <td bgcolor="#ffffff" class="text">&nbsp;
                <input type="button" value="Membros" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=entrevista_membros&processo_seletivo_id=<?= $tupla[ 'psl_id' ] ?>&dinamica_id=<?= $tupla[ 'din_id' ] ?>'" />
                </td>
                </tr>
            <?
                $i+=4;
            }
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="9">
            <input type="submit" name="subacao" value="Remover" />
            <input type="submit" name="subacao" value="Alterar" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhuma entrevista cadastrada.</td>
            </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_entrevistas_cadastradas ) ? "9" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <br /><br />
        <input type="button" value="<< Dinamicas" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=dinamicas&processo_seletivo_id=<?= $processo_seletivo_id ?>'" />
        <input type="button" value="   OK   " onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_entrevistas_cadastradas ) ? "9" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR>        <?
        break;
    /* ------------ PS - Inscrever membros em entrevista --------------- */
    case "entrevista_membros":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
        extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
        extract_request_var( "dinamica_id", $dinamica_id );

        $busca_membros_processo = $sql->query( "
        SELECT DISTINCT
            mem_id,
            mem_nome,
            mem_telefone,
            mem_email
        FROM
            audita
            NATURAL JOIN membro_vivo
        WHERE
            psl_id = '" . $processo_seletivo_id . "'
        ORDER BY
            mem_nome" );

        $busca_membros_entrevista = $sql->query( "
        SELECT DISTINCT
            mem_id,
            mem_nome,
            mem_telefone,
            mem_email,
            din_fase
        FROM
            acompanha
            NATURAL JOIN membro_vivo
            NATURAL JOIN dinamica
        WHERE
            din_id = '" . $dinamica_id . "'
        ORDER BY
            mem_nome" );
        ?>

        <br /><br />
        <center>
<table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_membros_entrevista ) ? "4" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Entrevista - Membros</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text" COLSPAN="<?= ( is_array( $busca_membros_entrevista ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="entrevista_membros" />
        <input type="hidden" name="acao" value="inscrever_membro_dinamica" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
        <? faz_select( "membro_dinamica_id", $busca_membros_processo, "mem_id", "mem_nome" ); ?>
        <input type="submit" value="Alocar" />
        </form>
        </td>
        </tr>

        <?
        if( is_array( $busca_membros_entrevista ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="subpagina" value="entrevista_membros" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="membro_dinamica" />
            <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
            <input type="hidden" name="dinamica_id" value="<?= $dinamica_id ?>" />
            </td>
            <td bgcolor="#ffffff" class="text"><b>Nome</b></td>
            <td bgcolor="#ffffff" class="text"><b>Telefone</b></td>
            <td bgcolor="#ffffff" class="text"><b>E-mail</b></td>
            </tr>

            <?
            foreach( $busca_membros_entrevista as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" class="caixa" name="remover_membros_dinamica_ids[]" value="<?= $tupla[ 'mem_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_telefone' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_email' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" colspan="4">
            <input type="submit" name="subacao" value="Remover" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
        <tr>
        <td bgcolor="#ffffff" class="text">Não há nenhum membro alocado para esta entrevista.</td>
        </tr>
        <?
        }
        ?>

        <tr>
        <td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_membros_entrevista ) ? "4" : "1" ) ?>">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="rh" />
        <input type="hidden" name="pagina" value="processo_seletivo" />
        <input type="hidden" name="subpagina" value="entrevistas" />
        <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
        <br /><br /><input type="submit" value="   OK   " />
        </form>
        </td>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_membros_entrevista ) ? "4" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table><BR><BR><?
        break;
    case "acesso_negado":
        include( ACESSO_NEGADO );
        break;

    /* ------------ PS - Feedback --------------- */
    case "feedback":
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
extract_request_var( "fase_dinamica", $fase_dinamica );

$busca_alunos_reprovados = $sql->query( "
SELECT
    candidato_din.agv_id,
    candidato_din.din_id,
    candidato_din.cnd_fb_solic,
    candidato_din.cnd_fb_realizado,
    candidato_din.cnd_fb_mem_id,
    date_part( 'epoch', cnd_fb_dt ) AS cnd_timestamp,
    aluno_gv.agv_nome
FROM
    candidato_din,
    aluno_gv
WHERE
    candidato_din.din_id IN
    (
    SELECT
        din_id
    FROM
        dinamica
    WHERE
        psl_id = '" . $processo_seletivo_id . "' AND
        din_fase = '" . $fase_dinamica . "'
    )
    AND
    candidato_din.cnd_status = '2' AND
    candidato_din.agv_id = aluno_gv.agv_id
ORDER BY
    agv_nome" );

$busca_membros_processo = $sql->query( "
SELECT
    audita.mem_id,
    membro_vivo.mem_nome,
    membro_vivo.mem_telefone,
    membro_vivo.mem_email
FROM
    audita,
    membro_vivo
WHERE
    psl_id = '" . $processo_seletivo_id . "' AND
    audita.mem_id = membro_vivo.mem_id
ORDER BY
    mem_nome" );
?>

<br /><br /><center>
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
<tr>
<td class="textwhitemini" colspan="2" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Feedback</td>
</tr>
<tr>
<td bgcolor="#ffffff" class="text">Fase:</td>
<td bgcolor="#ffffff" class="text">
<form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
<input type="hidden" name="suppagina" value="rh" />
<input type="hidden" name="pagina" value="processo_seletivo" />
<input type="hidden" name="subpagina" value="feedback" />
<input type="hidden" name="processo_seletivo_id" value="<?= ( isset( $processo_seletivo_id ) ? $processo_seletivo_id : "" )  ?>" />
<select name="fase_dinamica">
    <option value="1" <?= ( $fase_dinamica == 1 ? "selected" : "" ) ?>>Primeira</option>
    <option value="2" <?= ( $fase_dinamica == 2 ? "selected" : "" ) ?>>Segunda</option>
    <option value="3" <?= ( $fase_dinamica == 3 ? "selected" : "" ) ?>>Entrevista</option>
</select>
</td>
</tr>
<tr>
<td bgcolor="#ffffff" class="text" colspan="2">
<input type="submit" value="Consultar">
</form>
</td>
</tr>
<tr>
<td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="2">&nbsp;</td>
</tr>        
</table>
</td></tr>
</table><BR><BR>



<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
<tr>
<td class="textwhitemini" colspan="<?= ( isset( $busca_alunos_reprovados ) && is_array( $busca_alunos_reprovados ) ? "7" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Feedback - Resultados da Busca</td>
</tr>																				     <?
if( is_array( $busca_alunos_reprovados ) )
{
?>
    <tr>
    <td bgcolor="#ffffff" class="text">&nbsp;
    <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post" name="form_alunos_reprovados">
    <input type="hidden" name="suppagina" value="rh" />
    <input type="hidden" name="pagina" value="processo_seletivo" />
    <input type="hidden" name="subpagina" value="feedback" />
    <input type="hidden" name="acao" value="alterar_aluno_reprovado" />
    <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
    <input type="hidden" name="fase_dinamica" value="<?= $fase_dinamica ?>" />
    <input type="hidden" name="processo_seletivo_id" value="<?= ( isset( $processo_seletivo_id ) ? $processo_seletivo_id : "" )  ?>" />
    </td>
    <td bgcolor="#ffffff" class="text"><b>Aluno</b></td>
    <td bgcolor="#ffffff" class="text"><b>Solicitou Feedback?</b></td>
    <td bgcolor="#ffffff" class="text"><b>Data Feedback</b></td>
    <td bgcolor="#ffffff" class="text"><b>Hora</b></td>
    <td bgcolor="#ffffff" class="text"><b>Consultor</b></td>
    <td bgcolor="#ffffff" class="text"><b>Realizado?</b></td>
    </tr>

    <?
    $i = 7;
    foreach( $busca_alunos_reprovados as $tupla ) { ?>
        <tr>
        <td bgcolor="#ffffff" class="text"><input type="checkbox" name="alunos_reprovados_selecionados_ids[]" value="<?= $tupla[ 'agv_id' ] ?>-<?= $tupla[ 'din_id' ] ?>" /></td>
        <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'agv_nome' ] ?></td>
        <td bgcolor="#ffffff" class="text">
        <select name="feedback_solicitado_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="0" <?= ( $tupla[ 'cnd_fb_solic' ] == 0 ? "selected" : "" ) ?>>Não</option>
            <option value="1" <?= ( $tupla[ 'cnd_fb_solic' ] == 1 ? "selected" : "" ) ?>>Sim</option>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="feedback_dia_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="">--</option>
            <?
            for( $cont = 1; $cont <= 31; $cont++ )
            {
            ?>
                <option value="<?= $cont ?>" <?= ( $tupla[ 'cnd_timestamp' ] != "" && ( date( "j", $tupla[ 'cnd_timestamp' ] ) == $cont ) ? "selected" : "" ) ?>><?= $cont ?></option>
            <?
            }
            ?>
        </select>/
        <select name="feedback_mes_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="">--</option>
            <?
            for( $cont = 1; $cont <= 12; $cont++ )
            {
            ?>
                <option value="<?= $cont ?>" <?= ( $tupla[ 'cnd_timestamp' ] != "" && ( date( "n", $tupla[ 'cnd_timestamp' ] ) == $cont ) ? "selected" : "" ) ?>><?= $cont ?></option>
            <?
            }
            ?>
        </select>/
        <select name="feedback_ano_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="">--</option>
            <?
            for( $cont = $ano_minimo; $cont <= $ano_maximo; $cont++ )
            {
            ?>
                <option value="<?= $cont ?>" <?= ( $tupla[ 'cnd_timestamp' ] != "" && ( date( "Y", $tupla[ 'cnd_timestamp' ] ) == $cont ) ? "selected" : "" ) ?>><?= $cont ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">
        <select name="feedback_hora_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="0">--</option>
            <?
            for( $cont = 0; $cont <= 23; $cont++ )
            {
            ?>
                <option value="<?= $cont ?>" <?= ( $tupla[ 'cnd_timestamp' ] != "" && ( date( "G", $tupla[ 'cnd_timestamp' ] ) == $cont ) ? "selected" : ""  ) ?>><?= $cont ?></option>
            <?
            }
            ?>
        </select>:
        <select name="feedback_minuto_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="0">--</option>
            <?
            for( $cont = "00"; $cont <= 50; $cont+=10 )
            {
            ?>
                <option value="<?= $cont ?>" <?= ( $tupla[ 'cnd_timestamp' ] != "" && ( round( date( "i", $tupla[ 'cnd_timestamp' ] ) / 10 ) * 10 == $cont ) ? "selected" : ""  ) ?>><?= $cont ?></option>
            <?
            }
            ?>
        </select>
        </td>
        <td bgcolor="#ffffff" class="text">&nbsp;<? faz_select( "membro_feedback_id_" . $tupla[ 'agv_id' ] . "_" . $tupla[ 'din_id' ], $busca_membros_processo, "mem_id", "mem_nome", $tupla[ 'cnd_fb_mem_id' ], "onchange=\"document.form_alunos_reprovados.elements[ " . $i . " ].checked = true;\"" ); ?></td>
        <td bgcolor="#ffffff" class="text">
        <select name="feedback_realizado_<?= $tupla[ 'agv_id' ] ?>_<?= $tupla[ 'din_id' ] ?>" onchange="document.form_alunos_reprovados.elements[<?= $i ?>].checked = true;">
            <option value="0" <?= ( $tupla[ 'cnd_fb_realizado' ] == 0 ? "selected" : "" ) ?>>Não</option>
            <option value="1" <?= ( $tupla[ 'cnd_fb_realizado' ] == 1 ? "selected" : "" ) ?>>Sim</option>
        </select>
        </td>
        </tr>
        <?
        $i+=9;
    }
    ?>

    <tr>
    <td  bgcolor="#ffffff" class="text" colspan="7">
    <input type="submit" value="Alterar" />
    </form>
    </td>
    </tr>
<?
}
else
{
?>
    <tr>
    <td bgcolor="#ffffff" class="text">Não há nenhum aluno reprovado nesta fase deste processo seletivo.</td>
    </tr>
<?
}
?>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_alunos_reprovados ) && is_array( $busca_alunos_reprovados ) ? "7" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>
      <form>
      <input type="button" value="Voltar" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo'" />
      </form>
    <?
    break; 
 
    /* ------------ Listagem de processos seletivos --------------- */
    default:
        if( ! tem_permissao( FUNC_RH_PROCESSO_SELETIVO_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }

        $busca_processos_cadastrados = $sql->query( "
        SELECT DISTINCT
            psl_id,
            date_part( 'epoch', psl_dt_selecao ) AS psl_timestamp,
            date_part( 'epoch', psl_dt_inc ) AS psl_dt_inc_timestamp
        FROM
            p_seletivo
        ORDER BY
            psl_timestamp DESC,
            psl_dt_inc_timestamp DESC" );
        ?>

        <br /><br /><center>
        <table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( isset( $busca_processos_cadastrados ) && is_array( $busca_processos_cadastrados ) ? "10" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Processos Seletivos</td>
        </tr>

        <?
        if( is_array( $busca_processos_cadastrados ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="textmini">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="rh" />
            <input type="hidden" name="pagina" value="processo_seletivo" />
            <input type="hidden" name="acao" value="remover" />
            <input type="hidden" name="tipo_remocao" value="processo_seletivo" />
            </td>
            <td bgcolor="#ffffff" class="textmini"><b>Sem/Ano Seleção</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Consultores Alocados</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Empresas Contratadas</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Palestras Apresentação</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Cronograma Proc. Seletivo</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Inscrições</b></td>
            <td bgcolor="#ffffff" class="textmini"><b>Dinâmicas</b></td>
	    <td bgcolor="#ffffff" class="textmini"><b>Entrevistas</b></td>
	    <td bgcolor="#ffffff" class="textmini"><b>Feedback</b></td> 
            </tr>

            <?
            foreach( $busca_processos_cadastrados as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="caixa" name="remover_processos_seletivos_ids[]" value="<?= $tupla[ 'psl_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">
                <?= ( date( "m", $tupla[ 'psl_timestamp' ] ) > 6 ? "2" : "1" ) . "/" . date( "Y", $tupla[ 'psl_timestamp' ] ) ?>
                (<?= date( "d/m/Y", $tupla[ 'psl_dt_inc_timestamp' ] ) ?>)
                </td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=alocar_consultores&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Consultores</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=empresas_contratadas&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Empresas</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=palestras_apresentacao&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Palestras</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=cronograma_processo&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver arquivo</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=inscricoes&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Inscrições</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=dinamicas&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Dinâmicas</a></td>
                <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=entrevistas&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Entrevistas</a></td>
	        <td bgcolor="#ffffff" class="text"><a href="<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=rh&pagina=processo_seletivo&subpagina=feedback&processo_seletivo_id=" . $tupla[ 'psl_id' ] ?>">Ver Feedback</a></td>
	    </tr>
            <?
            }
            ?>

            <tr>
            <td  bgcolor="#ffffff" class="text" colspan="10">
            <input type="submit" value="Remover" />
            <input type="button" value="Novo" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=semestre_ano_selecao'" />
            </form>
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text">Não há nenhum processo seletivo.</td>
            </tr>
            <tr>
            <td bgcolor="#ffffff" class="text">
            <form>
            <input type="button" value="Novo" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=rh&pagina=processo_seletivo&subpagina=semestre_ano_selecao'" />
            </td>
            </tr></form>
        <?
        }
        ?>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( isset( $busca_processos_cadastrados ) && is_array( $busca_processos_cadastrados ) ? "10" : "1" ) ?>">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR>       <?
}
?>
