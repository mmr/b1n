<?
/* $Id: index.php,v 1.51 2002/12/17 19:23:36 binary Exp $ */

/*
 *
 * defines e variaveis de configuracao
 *
 */

define( "MAX_TEXTO", 20 );

$mod_titulo = "Eventos";
$colspan = "6";

extract_request_var( "acao",        $acao );
extract_request_var( "tipo",        $tipo );

extract_request_var( "evt_id",      $dados[ "evt_id" ] );
extract_request_var( "evt_edicao",  $dados[ "evt_edicao" ] );
extract_request_var( "tev_nome",    $dados[ "tev_nome" ] );
extract_request_var( "tev_mne",     $dados[ "tev_mne" ] );

if( consis_inteiro( $dados[ 'evt_id' ] ) && ( $dados[ 'evt_edicao' ] == ""  || $dados[ 'tev_nome' ] == "" ) )
{
    $rs = $sql->squery( "
        SELECT
            evt_edicao,
            tev_nome,
            tev_mne
        FROM
            evento
            NATURAL JOIN tipo_evento
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'" );

    if( $rs )
    {
        $dados[ 'evt_edicao' ] = $rs[ 'evt_edicao' ];
        $dados[ 'tev_nome' ]   = $rs[ 'tev_nome' ];
        $dados[ 'tev_mne' ]    = $rs[ 'tev_mne' ];
    }

    unset( $rs );
}

/*
 *
 * ACAO
 *  - Inserts / Deletes no BD
 *
 */

switch( $acao )
{





/*
 *
 * INSERIR
 *
 */




case "inserir":
    switch( $tipo )
    {
    case "integrante":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Evento SuperAcao */
        /* superacao */
        /* Vars */
        extract_request_var( 'eqp_id',  $dados[ 'eqp_id' ] );
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );
        extract_request_var( 'i_lider', $dados[ 'i_lider' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'agv_id' ] ) )
            array_push( $error_msgs, "É necessário selecionar um aluno" );

        $query = "
            SELECT
                COUNT( agv_id )
            FROM
                eqp_agv
            WHERE
                eqp_id = '"     . in_bd( $dados[ 'eqp_id' ] )   . "'
                AND agv_id = '" . in_bd( $dados[ 'agv_id' ] )   . "'";

        $rs = $sql->squery( $query );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse aluno já foi alocado. Escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_integrante";
            break;
        } 

        $rs = $sql->query( "BEGIN TRANSACTION" );

        if( $rs )
        {
            /* Lider novo */
            if( $dados[ 'i_lider' ] == 1 )
            {
                $query = "
                    UPDATE equipe
                    SET
                        agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'
                    WHERE
                        evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'";

                $rs = $sql->query( $query );
                if( ! $rs )
                {
                    array_push( $error_msgs, "Não foi possível mudar líder" );
                    break;
                }
            }

            $query = "
                INSERT INTO eqp_agv
                (
                    agv_id,
                    eqp_id
                ) 
                VALUES
                (
                    '" . in_bd( $dados[ 'agv_id' ] ) . "',
                    '" . in_bd( $dados[ 'eqp_id' ] ) . "'
                )";
        
            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                $dados[ 'agv_id' ] = '';
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_integrante";
        break;
    case "banca_julgadora":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "prf_id",          $dados[ 'prf_id' ] );
        extract_request_var( "cat_id",          $dados[ 'cat_id' ] );
        extract_request_var( "stc_id",          $dados[ 'stc_id' ] );
        extract_request_var( "epr_texto",       $dados[ 'epr_texto' ] );
        extract_request_var( "epr_entregue",    $dados[ 'epr_entregue' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "prf_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um professor da lista" );

        if( ! consis_inteiro( $dados[ "cat_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher uma categoria" );

        if( ! consis_inteiro( $dados[ "stc_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um Status de Contato" );

        $query = "
            SELECT
                COUNT( prf_id )
            FROM
                evt_prf
            WHERE
                prf_id = '" . $dados[ 'prf_id' ] . "'
                AND evt_id = '" . $dados[ 'evt_id' ] . "'";

        $rs = $sql->squery( $query );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse professor já consta na banca julgadora desse Evento, escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_banca_julgadora";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                INSERT INTO evt_prf
                (
                    evt_id,
                    prf_id,
                    cat_id,
                    stc_id,
                    epr_texto,
                    epr_entregue
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "evt_id" ] )    . "',
                    '" . in_bd( $dados[ "prf_id" ] )    . "',
                    '" . in_bd( $dados[ "cat_id" ] )    . "',
                    '" . in_bd( $dados[ "stc_id" ] )    . "',
                    '" . in_bd( $dados[ "epr_texto" ] ) . "',
                    '" . in_bd( $dados[ "epr_entregue" ] ) . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_banca_julgadora";
        break;
    case "equipe_alocada":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'mem_id', $dados[ 'mem_id' ] );
        extract_request_var( 'eme_coordenador', $dados[ 'eme_coordenador' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'mem_id' ] ) )
            array_push( $error_msgs, "É necessário selecionar um membro" );

        $rs = $sql->squery( "
            SELECT
                COUNT( mem_id )
            FROM
                evt_mem
            WHERE
                evt_id = '" . $dados[ 'evt_id' ] . "'
                AND mem_id = '" . $dados[ 'mem_id' ] . "'" );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse membro já foi alocado pra esse membro. Escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "po_equipe_alocada";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );

        if( $rs )
        {
            if( $dados[ 'eme_coordenador' ] == 1 )
            {
                $query = "
                    UPDATE evt_mem
                    SET
                        eme_coordenador = '0'
                    WHERE
                        evt_id = '" . $dados[ 'evt_id' ] . "'";

                $sql->query( $query );
            }

            $query = "
                INSERT INTO evt_mem
                (
                    evt_id,
                    mem_id,
                    eme_coordenador
                ) 
                VALUES
                (
                    '" . in_bd( $dados[ 'evt_id' ] ) . "',
                    '" . in_bd( $dados[ 'mem_id' ] ) . "',
                    '" . in_bd( $dados[ 'eme_coordenador' ] ) . "'
                )";
        
            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "po_equipe_alocada";
        break;
    case "inscrito_superacao":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );

        /* Campos Form */
        extract_request_var( "eqp_nome",      $dados[ 'eqp_nome' ] );
        extract_request_var( "eqp_colocacao", $dados[ 'eqp_colocacao' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'agv_id' ] ) )
            array_push( $error_msgs, "É necessário escolher um líder" );

        if( $dados[ 'eqp_nome' ] == '' )
            array_push( $error_msgs, "É necessário preencher um nome para a equipe" );

        $rs = $sql->squery( "
            SELECT
                evt_dt_fim
            FROM
                evento
            WHERE
                evt_id = '" . $dados[ 'evt_id' ] . "'" );

        $dados[ 'evt_dt_fim' ] = $rs[ 'evt_dt_fim' ];

        $colocacao = "NULL";
        if( ! is_null( $dados[ 'evt_dt_fim' ] ) && $dados[ 'evt_dt_fim' ] <= time() )
            if( ! consis_inteiro( $dados[ 'eqp_colocacao' ] ) )
                array_push( $error_msgs, "Colocação inválida" );
            else
                $colocacao = "'" . in_bd( $dados[ 'eqp_colocacao' ] ) . "'";

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_inscrito_superacao";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                SELECT
                    COUNT( agv_id )
                FROM
                    equipe 
                WHERE
                    agv_id   = '" . $dados[ 'agv_id' ] . "'";

            $rs = $sql->squery( $query );

            if( $rs[ 'count' ] > 0 )
            {
                array_push( $error_msgs, "Esse aluno já foi alocado. Escolha outro." );
                $subpagina = "inserir_inscrito_superacao";
                break;
            }

            /* Inserindo no Banco */
            $rs = $sql->squery( "SELECT nextval( 'equipe_eqp_id_seq' )" );

            $dados[ "eqp_id" ] = $rs[ "nextval" ];

            $query = "
                INSERT INTO equipe
                (
                    evt_id,
                    agv_id,
                    eqp_id,
                    eqp_nome,
                    eqp_colocacao
                )
                VALUES 
                (
                    '" . in_bd( $dados[ 'evt_id' ] )    . "',
                    '" . in_bd( $dados[ 'agv_id' ] )    . "',
                    '" . in_bd( $dados[ 'eqp_id' ] )    . "',
                    '" . in_bd( $dados[ "eqp_nome" ] )  . "',
                    "  . $colocacao                     . "
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $query = "
                    INSERT INTO eqp_agv
                    (
                        agv_id,
                        eqp_id
                    )
                    VALUES
                    (
                        '" . in_bd( $dados[ 'agv_id' ] ) . "',
                        '" . in_bd( $dados[ 'eqp_id' ] ) . "'
                    )";
                
                $rs = $sql->query( $query );
        
                if( $rs )
                {
                    $sql->query( "COMMIT TRANSACTION" );
                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_inscrito_superacao";
        break;
    case "inscrito_pg":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );

        /* Campos Form */
        extract_request_var( "cat_id",      $dados[ 'cat_id' ] );
        extract_request_var( "prf_id_1",    $dados[ 'prf_id_1' ] );
        extract_request_var( "prf_id_2",    $dados[ 'prf_id_2' ] );
        extract_request_var( "ipg_resumo",  $dados[ 'ipg_resumo' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'agv_id' ] ) )
            array_push( $error_msgs, "É necessário escolher um aluno" );

        if( ! consis_inteiro( $dados[ 'cat_id' ] ) )
            array_push( $error_msgs, "É necessário escolher uma categoria" );

        if( consis_inteiro( $dados[ 'prf_id_1' ] ) && $dados[ 'prf_id_1' ] == $dados[ 'prf_id_2' ] )
            array_push( $error_msgs, "Você não pode escolher o mesmo professor" );
        
        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_inscrito_pg";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            /* Verificando se ja nao tem esse aluno inscrito */
            $rs = $sql->squery( "
                SELECT
                    COUNT( agv_id )
                FROM
                    inscrito_pg
                WHERE
                    evt_id = '" . $dados[ 'evt_id' ] . "'
                    AND agv_id = '" . $dados[ "agv_id" ] . "'" );

            if( $rs[ 'count' ] > 0 )
            {
                array_push( $error_msgs, "Esse aluno já está inscrito nesse evento. Escolha outro." );
                $subpagina = "inserir_inscrito_pg";
                break;
            }

            /* Inserindo no Banco */
            $query = "
                INSERT INTO inscrito_pg
                (
                    agv_id,
                    evt_id,
                    cat_id,
                    prf_id_1,
                    prf_id_2,
                    ipg_resumo
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "agv_id" ] )        . "',
                    '" . in_bd( $dados[ "evt_id" ] )        . "',
                    '" . in_bd( $dados[ "cat_id" ] )        . "',
                    '" . in_bd( $dados[ "prf_id_1" ] )      . "',
                    '" . in_bd( $dados[ "prf_id_2" ] )      . "',
                    '" . in_bd( $dados[ "ipg_resumo" ] )    . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_inscrito_pg";
        break;
    case "inscrito":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );

        /* Campos Form */
        extract_request_var( "i_aluno_gv",  $dados[ 'i_aluno_gv' ] );
        extract_request_var( "i_nome",      $dados[ 'i_nome' ] );
        extract_request_var( "i_endereco",  $dados[ 'i_endereco' ] );
        extract_request_var( "i_bairro",    $dados[ 'i_bairro' ] );
        extract_request_var( "i_ddd",  $dados[ 'i_ddd' ] );
        extract_request_var( "i_ddi",  $dados[ 'i_ddi' ] );
        extract_request_var( "i_telefone",  $dados[ 'i_telefone' ] );
        extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
        extract_request_var( "i_dt_nasci",  $dados[ 'i_dt_nasci' ] );
        extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
        extract_request_var( "i_email",     $dados[ 'i_email' ] );
        extract_request_var( "i_convidado", $dados[ 'i_convidado' ] );
        extract_request_var( "tcv_id",      $dados[ 'tcv_id' ] );
        extract_request_var( "i_curso",     $dados[ 'i_curso' ] );
        extract_request_var( "i_faculdade", $dados[ 'i_faculdade' ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ 'i_nome' ] == "" )
            array_push($error_msgs, "É necessário preencher o nome do aluno" );

        if( ! consis_data( $dados[ 'i_dt_nasci' ][ 'dia' ],
                            $dados[ 'i_dt_nasci' ][ 'mes' ],
                            $dados[ 'i_dt_nasci' ][ 'ano' ] ) )
            array_push( $error_msgs, "Data de Nascimento inválida" );

        if( ! consis_email( $dados[ "i_email" ], 0 ) )
            array_push( $error_msgs, "Email inválido" );

        if( ! consis_telefone( $dados[ "i_cep" ], 0 ) )
            array_push( $error_msgs, "CEP inválido" );

        if( ! consis_telefone( $dados[ "i_ddi" ], 0 ) )
            array_push( $error_msgs, "DDI inválido" );

        if( ! consis_telefone( $dados[ "i_ddd" ], 0 ) )
            array_push( $error_msgs, "DDD inválido" );

        if( ! consis_telefone( $dados[ "i_telefone" ], 0 ) )
            array_push( $error_msgs, "Telefone inválido" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_inscrito";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if( consis_inteiro( $dados[ "agv_id" ] ) )
            {
                /* Verificando se ja nao tem esse aluno inscrito */
                $rs = $sql->squery( "
                    SELECT
                        COUNT( igv_id )
                    FROM
                        inscrito_gv
                    WHERE
                        agv_id = '" . $dados[ "agv_id" ] . "'" );

                if( $rs[ 'count' ] > 0 )
                {
                    array_push( $error_msgs, "Esse aluno da GV já está inscrito nesse evento. Escolha outro." );
                    $subpagina = "inserir_inscrito";
                    break;
                }


                /* Inserindo no Banco / Atualizando */
                $rs = $sql->squery( "SELECT nextval( 'inscrito_gv_igv_id_seq' )" );

                $dados[ "igv_id" ] = $rs[ "nextval" ];

                $query = "
                    INSERT INTO inscrito_gv
                    (
                        agv_id,
                        evt_id,
                        igv_id,
                        tcv_id,
                        igv_convidado
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "agv_id" ] )        . "',
                        '" . in_bd( $dados[ "evt_id" ] )        . "',
                        '" . in_bd( $dados[ "igv_id" ] )        . "',
                        "  . ( ( consis_inteiro( $dados[ "tcv_id" ] ) ) ? "'" . in_bd( $dados[ "tcv_id" ] ) . "'" : "NULL" ) . ",
                        '" . in_bd( $dados[ "i_convidado" ] )   . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    /* Atualizando dados do aluno da gv (o cara pode ter alterado) */
                    $query = "
                        UPDATE aluno_gv
                        SET
                            agv_nome = '"       . in_bd( $dados[ 'i_nome' ] )       . "',
                            agv_endereco = '"   . in_bd( $dados[ 'i_endereco' ] )   . "',
                            agv_bairro = '"     . in_bd( $dados[ 'i_bairro' ] )     . "',
                            agv_ddd = '"        . in_bd( $dados[ 'i_ddd' ] )        . "',
                            agv_ddi = '"        . in_bd( $dados[ 'i_ddi' ] )        . "',
                            agv_telefone = '"   . in_bd( $dados[ 'i_telefone' ] )   . "',
                            agv_cep = '"        . in_bd( $dados[ 'i_cep' ] )        . "',
                            agv_dt_nasci = '"   . in_bd( hash_to_databd( $dados[ 'i_dt_nasci' ] ) ) . "',
                            agv_email = '"      . in_bd( $dados[ 'i_email' ] )      . "'
                        WHERE
                            agv_id = '"         . in_bd( $dados[ 'agv_id' ] )       . "'";
                    
                    $rs = $sql->query( $query );

                    if( $rs )
                    {
                        $sql->query( "COMMIT TRANSACTION" );
                        break;
                    }
                }
            }
            else
            {
                /* Inserindo no Banco / Atualizando */
                $rs = $sql->squery( "SELECT nextval( 'aluno_nao_gv_ang_id_seq' )" );

                if( $rs )
                {
                    $dados[ 'ang_id' ] = $rs[ 'nextval' ];

                    /* Atualizando dados do aluno da gv (o cara pode ter alterado) */
                    $query = "
                        INSERT INTO aluno_nao_gv
                        (
                            ang_id,
                            ang_nome,
                            ang_curso,
                            ang_faculdade,
                            ang_endereco,
                            ang_bairro,
                            ang_ddd,
                            ang_ddi,
                            ang_telefone,
                            ang_cep,
                            ang_dt_nasci,
                            ang_email
                        )
                        VALUES
                        (
                            '"  . in_bd( $dados[ 'ang_id' ] )       . "',
                            '"  . in_bd( $dados[ 'i_nome' ] )       . "',
                            '"  . in_bd( $dados[ 'i_curso' ] )      . "',
                            '"  . in_bd( $dados[ 'i_faculdade' ] )  . "',
                            '"  . in_bd( $dados[ 'i_endereco' ] )   . "',
                            '"  . in_bd( $dados[ 'i_bairro' ] )     . "',
                            '"  . in_bd( $dados[ 'i_ddd' ] )   . "',
                            '"  . in_bd( $dados[ 'i_ddi' ] )   . "',
                            '"  . in_bd( $dados[ 'i_telefone' ] )   . "',
                            '"  . in_bd( $dados[ 'i_cep' ] )        . "',
                            '"  . in_bd( hash_to_databd( $dados[ 'i_dt_nasci' ] ) ) . "',
                            '"  . in_bd( $dados[ 'i_email' ] )      . "'
                        )";

                    $rs = $sql->query( $query );

                    if( $rs )
                    {
                        $rs = $sql->squery( "SELECT nextval( 'inscrito_ngv_ing_id_seq' )" );

                        $dados[ "ing_id" ] = $rs[ "nextval" ];

                        $query = "
                            INSERT INTO inscrito_ngv
                            (
                                ang_id,
                                evt_id,
                                ing_id,
                                tcv_id,
                                ing_convidado
                            )
                            VALUES 
                            (
                                '" . in_bd( $dados[ "ang_id" ] )        . "',
                                '" . in_bd( $dados[ "evt_id" ] )        . "',
                                '" . in_bd( $dados[ "ing_id" ] )        . "',
                                "  . ( ( consis_inteiro( $dados[ "tcv_id" ] ) ) ? "'" . in_bd( $dados[ "tcv_id" ] ) . "'" : "NULL" ) . ",
                                '" . in_bd( $dados[ "i_convidado" ] ) . "'
                            )";

                        $rs = $sql->query( $query );

                        if( $rs )
                        {
                            $sql->query( "COMMIT TRANSACTION" );
                            break;
                        }
                    }
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_inscrito";
        break;
    case "custo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "cto_nome",        $dados[ "cto_nome" ] ); 
        extract_request_var( "cto_t_movimento", $dados[ "cto_t_movimento" ] ); 
        extract_request_var( "cto_valor",       $dados[ "cto_valor" ] ); 

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "cto_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher a Descrição de receitas e despesas" );

        if( ! consis_boleano( $dados[ 'cto_t_movimento' ] ) )
            array_push( $error_msgs, "Valor inválido para tipo de movimento" );

        if( $dados[ "cto_valor" ] == "" || ! consis_dinheiro( reconhece_dinheiro( $dados[ "cto_valor" ] ) ) )
            array_push( $error_msgs, "Você precisa preencher um valor válido" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_custo";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'evt_custo_cto_id_seq' )" );
            if( $rs )
            {
                $dados[ "cto_id" ] = $rs[ "nextval" ];

                $query = "
                    INSERT INTO evt_custo
                    (
                        evt_id,
                        cto_id,
                        cto_nome,
                        cto_t_movimento,
                        cto_valor
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "evt_id" ] )                            . "',
                        '" . in_bd( $dados[ "cto_id" ] )                            . "',
                        '" . in_bd( $dados[ "cto_nome" ] )                          . "',
                        '" . in_bd( $dados[ "cto_t_movimento" ] )                   . "',
                        '" . in_bd( reconhece_dinheiro( $dados[ 'cto_valor' ] ) )   . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    $sql->query( "COMMIT TRANSACTION" );
                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_custo";
        break;
    case "evt_arquivo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "ear_nome",    $dados[ "ear_nome" ] );
        extract_request_var( "ear_desc",    $dados[ "ear_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "ear_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do Upload" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_evt_arquivo";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'evt_arquivo_ear_id_seq' )" );
            if( $rs )
            {
                $dados[ "ear_id" ] = $rs[ "nextval" ];
                $query = "
                    INSERT INTO evt_arquivo
                    (
                        evt_id,
                        ear_id,
                        ear_nome,
                        ear_desc
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "evt_id" ] )    . "',
                        '" . in_bd( $dados[ "ear_id" ] )    . "',
                        '" . in_bd( $dados[ "ear_nome" ] )  . "',
                        '" . in_bd( $dados[ "ear_desc" ] )  . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    $dados[ 'ear_arq_real' ] = "evt_ear_" . $dados[ 'ear_id' ];

                    $error_msgs = faz_upload( $sql, "ear_id", $dados[ 'ear_id' ], "ear_arq", $dados[ 'ear_arq_real' ], "ear_arq_real", "ear_arq_falso", "evt_arquivo" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'ear_arq_real' ] ) && is_writable( $dados[ 'ear_arq_real' ] ) )
                            unlink( $dados[ 'ear_arq_real' ] );

                        /* Cancelar a Insercao e Update do banco */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_evt_arquivo";
                        break;
                    }

                    $sql->query( "COMMIT TRANSACTION" );

                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_evt_arquivo";
        break;
    case "item_final":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "ifi_nome",    $dados[ "ifi_nome" ] );
        extract_request_var( "ifi_desc",    $dados[ "ifi_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "ifi_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do item de finalização" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_item_final";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'item_final_ifi_id_seq' )" );
            if( $rs )
            {
                $dados[ "ifi_id" ] = $rs[ "nextval" ];
                $query = "
                    INSERT INTO item_final
                    (
                        evt_id,
                        ifi_id,
                        ifi_nome,
                        ifi_desc
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "evt_id" ] )    . "',
                        '" . in_bd( $dados[ "ifi_id" ] )    . "',
                        '" . in_bd( $dados[ "ifi_nome" ] )  . "',
                        '" . in_bd( $dados[ "ifi_desc" ] )  . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    $dados[ 'ifi_arq_real' ] = "evt_ifi_" . $dados[ 'ifi_id' ];

                    $error_msgs = faz_upload( $sql, "ifi_id", $dados[ 'ifi_id' ], "ifi_arq", $dados[ 'ifi_arq_real' ], "ifi_arq_real", "ifi_arq_falso", "item_final" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'ifi_arq_real' ] ) && is_writable( $dados[ 'ifi_arq_real' ] ) )
                            unlink( $dados[ 'ifi_arq_real' ] );

                        /* Cancelar a Insercao e Update do banco */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_item_final";
                        break;
                    }

                    $sql->query( "COMMIT TRANSACTION" );

                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_item_final";
        break;
    case "material_grafico":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "mgf_nome",    $dados[ "mgf_nome" ] );
        extract_request_var( "mgf_desc",    $dados[ "mgf_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "mgf_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do material gráfico" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_material_grafico";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'material_grafico_mgf_id_seq' )" );
            if( $rs )
            {
                $dados[ "mgf_id" ] = $rs[ "nextval" ];
                $query = "
                    INSERT INTO material_grafico
                    (
                        evt_id,
                        mgf_id,
                        mgf_nome,
                        mgf_desc
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "evt_id" ] )    . "',
                        '" . in_bd( $dados[ "mgf_id" ] )    . "',
                        '" . in_bd( $dados[ "mgf_nome" ] )  . "',
                        '" . in_bd( $dados[ "mgf_desc" ] )  . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    $dados[ 'mgf_arq_real' ] = "evt_mgf_" . $dados[ 'mgf_id' ];

                    $error_msgs = faz_upload( $sql, "mgf_id", $dados[ 'mgf_id' ], "mgf_arq", $dados[ 'mgf_arq_real' ], "mgf_arq_real", "mgf_arq_falso", "material_grafico" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'mgf_arq_real' ] ) && is_writable( $dados[ 'mgf_arq_real' ] ) )
                            unlink( $dados[ 'mgf_arq_real' ] );

                        /* Cancelar a Insercao e Update do banco */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_material_grafico";
                        break;
                    }

                    $sql->query( "COMMIT TRANSACTION" );

                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_material_grafico";
        break;
    case "patrocinador":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "pat_id",      $dados[ "pat_id" ] );

        extract_request_var( "epa_texto",   $dados[ "epa_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "pat_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um patrocinador da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );
    
        if( ! consis_inteiro( $dados[ "stc_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um status para o contato" );

        $query = "
            SELECT
                COUNT(pat_id)
            FROM
                evt_pat
            WHERE
                pat_id = '" . $dados[ 'pat_id' ] . "'
                AND evt_id = '" . $dados[ 'evt_id' ] . "'";

        $rs = $sql->squery( $query );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse patrocinador já consta para esse Evento, escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_patrocinador";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                INSERT INTO evt_pat
                (
                    evt_id,
                    pat_id,
                    mem_id,
                    stc_id,
                    epa_texto
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "evt_id" ] )    . "',
                    '" . in_bd( $dados[ "pat_id" ] )    . "',
                    '" . in_bd( $dados[ "mem_id" ] )    . "',
                    '" . in_bd( $dados[ "stc_id" ] )    . "',
                    '" . in_bd( $dados[ "epa_texto" ] ) . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_patrocinador";
        break;
    case "fornecedor":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "for_id",      $dados[ "for_id" ] );

        extract_request_var( "efo_texto",   $dados[ "efo_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "for_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um fornecedor da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );

        if( ! consis_inteiro( $dados[ "stc_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um status para o contato" );

        $query = "
            SELECT
                COUNT(for_id)
            FROM
                evt_for
            WHERE
                for_id = '" . $dados[ 'for_id' ] . "'
                AND evt_id = '" . $dados[ 'evt_id' ] . "'";

        $rs = $sql->squery( $query );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse fornecedor já consta para esse Evento, escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_fornecedor";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                INSERT INTO evt_for
                (
                    evt_id,
                    for_id,
                    mem_id,
                    stc_id,
                    efo_texto
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "evt_id" ] )    . "',
                    '" . in_bd( $dados[ "for_id" ] )    . "',
                    '" . in_bd( $dados[ "mem_id" ] )    . "',
                    '" . in_bd( $dados[ "stc_id" ] )    . "',
                    '" . in_bd( $dados[ "efo_texto" ] ) . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_fornecedor";
        break;
    case "palestrante":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "pal_id",      $dados[ "pal_id" ] );

        extract_request_var( "epl_texto",   $dados[ "epl_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "pal_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um Palestrante da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );

        if( ! consis_inteiro( $dados[ "stc_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um status para o contato" );

        $query = "
            SELECT
                COUNT(pal_id)
            FROM
                evt_pal
            WHERE
                pal_id = '" . $dados[ 'pal_id' ] . "'
                AND evt_id = '" . $dados[ 'evt_id' ] . "'";

        $rs = $sql->squery( $query );

        if( $rs[ 'count' ] > 0 )
            array_push( $error_msgs, "Esse palestrante já consta para esse Evento, escolha outro." );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_palestrante";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                INSERT INTO evt_pal
                (
                    evt_id,
                    pal_id,
                    mem_id,
                    stc_id,
                    epl_texto
                )
                VALUES 
                (
                    '" . in_bd( $dados[ "evt_id" ] )    . "',
                    '" . in_bd( $dados[ "pal_id" ] )    . "',
                    '" . in_bd( $dados[ "mem_id" ] )    . "',
                    '" . in_bd( $dados[ "stc_id" ] )    . "',
                    '" . in_bd( $dados[ "epl_texto" ] ) . "'
                )";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_palestrante";
        break;
    case "tarefa_cronograma":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "eta_desc",    $dados[ "eta_desc" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "ste_id",      $dados[ "ste_id" ] );
        extract_request_var( "eta_dt_ini",  $dados[ "eta_dt_ini" ] );
        extract_request_var( "eta_dt_fim",  $dados[ "eta_dt_fim" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "eta_desc" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o campo de Tarefa" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um responsável para a tarefa" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um responsável para a tarefa" );

        if( ! consis_data( $dados[ "eta_dt_ini" ][ "dia" ], $dados[ "eta_dt_ini" ][ "mes" ], $dados[ "eta_dt_ini" ][ "ano" ] ) )
            array_push( $error_msgs, "Data de Início inválida" ); 

        if( ! consis_data( $dados[ "eta_dt_fim" ][ "dia" ], $dados[ "eta_dt_fim" ][ "mes" ], $dados[ "eta_dt_fim" ][ "ano" ] ) )
            array_push( $error_msgs, "Data de Fim inválida" ); 

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_tarefa_cronograma";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'evt_tarefa_eta_id_seq' )" );
            if( $rs )
            {
                $dados[ "eta_id" ] = $rs[ "nextval" ];

                $query = "
                    INSERT INTO evt_tarefa
                    (
                        mem_id,
                        ste_id,
                        evt_id,
                        eta_id,
                        eta_desc,
                        eta_dt_ini,
                        eta_dt_fim
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "mem_id" ] )        . "',
                        '" . in_bd( $dados[ "ste_id" ] )        . "',
                        '" . in_bd( $dados[ "evt_id" ] )        . "',
                        '" . in_bd( $dados[ "eta_id" ] )        . "',
                        '" . in_bd( $dados[ "eta_desc" ] )      . "',
                        '" . in_bd( hash_to_databd( $dados[ "eta_dt_ini" ] ) )  . "',
                        '" . in_bd( hash_to_databd( $dados[ "eta_dt_fim" ] ) )  . "'
                    )";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    $sql->query( "COMMIT TRANSACTION" );
                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_tarefa_cronograma";
        break;
    case "evento":
        if( ! tem_permissao( FUNC_MKT_EVENTO_INSERIR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "tev_id",      $dados[ "tev_id" ] );
        extract_request_var( "evt_dt",      $dados[ "evt_dt" ] );

        $error_msgs = array();

        /* Validacao dos Dados */
        if( ! consis_inteiro( $dados[ "tev_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um Tipo de Evento" );

        if( $dados[ "evt_edicao" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o campo de Edição" );
            /*
        else
        {
            $rs = $sql->squery( "SELECT COUNT(evt_id) WHERE evt_edicao = '" . $dados[ "evt_edicao" ] );

            if( $rs['count'] > 0 )
                array_push( $error_msgs, "Já existe um evento com edição com esse Nome" );
        }
            */

        if(! consis_data($dados["evt_dt"]["dia"],
                          $dados["evt_dt"]["mes"],
                          $dados["evt_dt"]["ano"]))
            array_push( $error_msgs, "Data inválida" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "tipo_evento_selecao";
            break;
        }

        /* Inserindo no Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $rs = $sql->squery( "SELECT nextval( 'evento_evt_id_seq' )" );
            if( $rs )
            {
                $dados[ "evt_id" ] = $rs[ "nextval" ];
                $query = "
                    INSERT INTO evento
                    (
                        tev_id,
                        evt_id,
                        evt_edicao,
                        evt_dt
                    )
                    VALUES 
                    (
                        '" . in_bd( $dados[ "tev_id" ] )        . "',
                        '" . in_bd( $dados[ "evt_id" ] )        . "',
                        '" . in_bd( $dados[ "evt_edicao" ] )    . "',
                        '" . in_bd( hash_to_databd( $dados[ "evt_dt" ] ) )  . "'
                    )";


                $rs = $sql->query( $query );

                if( $rs )
                {
                    $sql->query( "COMMIT TRANSACTION" );
                    break;
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "tipo_evento_selecao";
        break;
    }
    break;





/*
 *
 * ALTERAR
 *
 */




case "alterar":
    switch( $tipo )
    {
    case "inscrito_superacao":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( 'eqp_id',      $dados[ 'eqp_id' ] );
        extract_request_var( 'eqp_nome',    $dados[ 'eqp_nome' ] );

        /* Validação */
        $error_msgs = array();

        if( $dados[ 'eqp_nome' ] == '' )
            array_push( $error_msgs, 'É necessário preencher o nome da equipe' );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "parte_publica";
            $alterar = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE equipe
                SET
                    eqp_nome = '"   . in_bd( $dados[ 'eqp_nome' ] )     . "'
                WHERE
                    eqp_id = '"     . in_bd( $dados[ 'eqp_id' ] )       . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "parte_publica";
        break;
    case "evento":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PP_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( 'evt_dt',      $dados[ 'evt_dt' ] );
        extract_request_var( 'evt_dt_fim',  $dados[ 'evt_dt_fim' ] );
        extract_request_var( 'evt_local',   $dados[ 'evt_local' ] ); 
        extract_request_var( 'evt_tema',    $dados[ 'evt_tema' ] ); 

        /* Validacao */
        $error_msgs = array();

        if( ! consis_data( $dados[ 'evt_dt' ][ 'dia' ],
                            $dados[ 'evt_dt' ][ 'mes' ],
                            $dados[ 'evt_dt' ][ 'ano' ] ) )
            array_push( $error_msgs, "Data de Evento inválida" );

        if( ! consis_data( $dados[ 'evt_dt_fim' ][ 'dia' ],
                            $dados[ 'evt_dt_fim' ][ 'mes' ],
                            $dados[ 'evt_dt_fim' ][ 'ano' ] ) )
            array_push( $error_msgs, "Data de Fim de Evento inválida" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "parte_publica";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evento
                SET
                    evt_edicao = '" . in_bd( $dados[ 'evt_edicao' ] )   . "',
                    evt_dt = '"     . in_bd( hash_to_databd( $dados[ 'evt_dt' ] ) )     . "',
                    evt_dt_fim = '" . in_bd( hash_to_databd( $dados[ 'evt_dt_fim' ] ) ) . "',
                    evt_local = '"  . in_bd( $dados[ 'evt_local' ] )    . "',
                    evt_tema  = '"  . in_bd( $dados[ 'evt_tema' ] )     . "'
                WHERE
                    evt_id = '"     . in_bd( $dados[ 'evt_id' ] )       . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "parte_publica";
        break;
    case "nota_pg":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( 'agv_id',          $dados[ 'agv_id' ] );
        extract_request_var( 'cri_id_1',        $dados[ 'cri_id_1' ] );
        extract_request_var( 'cri_id_2',        $dados[ 'cri_id_2' ] );
        extract_request_var( 'ipg_peso_1',      $dados[ 'ipg_peso_1' ] );
        extract_request_var( 'ipg_peso_2',      $dados[ 'ipg_peso_2' ] );
        extract_request_var( 'ipg_nota_1',      $dados[ 'ipg_nota_1' ] );
        extract_request_var( 'ipg_nota_2',      $dados[ 'ipg_nota_2' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'ipg_peso_1' ] ) || $dados[ 'ipg_peso_1' ] == 0 )
            array_push( $error_msgs, 'Peso 1 inválido' );

        if( ! consis_inteiro( $dados[ 'ipg_peso_2' ] ) || $dados[ 'ipg_peso_2' ] == 0 )
            array_push( $error_msgs, 'Peso 2 inválido' );

        if( ! consis_inteiro( $dados[ 'cri_id_1' ] ) ||
            ! consis_inteiro( $dados[ 'cri_id_2' ] ) )
            array_push( $error_msgs, 'É necessário escolher os critérios' );

        if( $dados[ 'ipg_nota_1' ] == '' || 
            $dados[ 'ipg_nota_2' ] == '' ||
            ! is_float( reconhece_dinheiro( $dados[ 'ipg_nota_1' ] ) ) ||
            ! is_float( reconhece_dinheiro( $dados[ 'ipg_nota_2' ] ) ) )
            array_push( $error_msgs, 'É necessário preencher todas as notas' );

        if( sizeof( $error_msgs ) )
            break;

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE inscrito_pg 
                SET
                    cri_id_1    = '" . in_bd( $dados[ 'cri_id_1' ] )    . "',
                    cri_id_2    = '" . in_bd( $dados[ 'cri_id_2' ] )    . "',
                    ipg_peso_1  = '" . in_bd( $dados[ 'ipg_peso_1' ] )  . "',
                    ipg_peso_2  = '" . in_bd( $dados[ 'ipg_peso_2' ] )  . "',
                    ipg_nota_1  = '" . in_bd( $dados[ 'ipg_nota_1' ] )  . "',
                    ipg_nota_2  = '" . in_bd( $dados[ 'ipg_nota_2' ] )  . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ 'evt_id' ] )      . "'
                    AND agv_id  = '" . in_bd( $dados[ 'agv_id' ] )      . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        break;
    case "banca_julgadora":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "prf_id",          $dados[ 'prf_id' ] );
        extract_request_var( "prf_id_old",      $dados[ 'prf_id_old' ] );
        extract_request_var( "cat_id",          $dados[ 'cat_id' ] );
        extract_request_var( "stc_id",          $dados[ 'stc_id' ] );
        extract_request_var( "epr_texto",       $dados[ 'epr_texto' ] );
        extract_request_var( "epr_entregue",    $dados[ 'epr_entregue' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "prf_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um professor da lista" );

        if( ! consis_inteiro( $dados[ "cat_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um categoria" );

        if( ! consis_inteiro( $dados[ "stc_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um Status de Contato" );

        if( $dados[ 'prf_id' ] != $dados[ 'prf_id_old' ] )
        {
            $query = "
                SELECT
                    COUNT( prf_id )
                FROM
                    evt_prf
                WHERE
                    prf_id = '" . $dados[ 'prf_id' ] . "'
                    AND evt_id = '" . $dados[ 'evt_id' ] . "'";

            $rs = $sql->squery( $query );

            if( $rs[ 'count' ] > 0 )
                array_push( $error_msgs, "Esse professor já consta na banca julgadora desse Evento, escolha outro." );
        }

        if( sizeof( $error_msgs ) )
        {
            $subpagina  = "inserir_banca_julgadora";
            $alterar    = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_prf
                SET
                    prf_id      = '" . in_bd( $dados[ 'prf_id' ] )      . "',
                    cat_id      = '" . in_bd( $dados[ 'cat_id' ] )      . "',
                    stc_id      = '" . in_bd( $dados[ 'stc_id' ] )      . "',
                    epr_texto   = '" . in_bd( $dados[ 'epr_texto' ] )   . "',
                    epr_entregue = '" . in_bd( $dados[ 'epr_entregue' ] ) . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ "evt_id" ] )      . "'
                    AND prf_id  = '" . in_bd( $dados[ "prf_id_old" ] )      . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_banca_julgadora";
        $alterar = "yeah";
        break;
    case "inscrito_pg":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );
        extract_request_var( 'agv_id_old',  $dados[ 'agv_id_old' ] );

        /* Campos Form */
        extract_request_var( "cat_id",      $dados[ 'cat_id' ] );
        extract_request_var( "prf_id_1",    $dados[ 'prf_id_1' ] );
        extract_request_var( "prf_id_2",    $dados[ 'prf_id_2' ] );
        extract_request_var( "ipg_resumo",  $dados[ 'ipg_resumo' ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ 'agv_id' ] ) )
            array_push( $error_msgs, "É necessário escolher um aluno" );

        if( ! consis_inteiro( $dados[ 'cat_id' ] ) )
            array_push( $error_msgs, "É necessário escolher uma categoria" );

        if( consis_inteiro( $dados[ 'prf_id_1' ] ) && $dados[ 'prf_id_1' ] == $dados[ 'prf_id_2' ] )
            array_push( $error_msgs, "Você não pode escolher o mesmo professor" );
        
        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_inscrito_pg";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if( $dados[ "agv_id" ] != $dados[ "agv_id_old" ] )
            {
                /* Verificando se ja nao tem esse aluno inscrito */
                $rs = $sql->squery( "
                    SELECT
                        COUNT( agv_id )
                    FROM
                        inscrito_pg
                    WHERE
                        agv_id = '" . $dados[ "agv_id" ] . "'" );

                if( $rs[ 'count' ] > 0 )
                {
                    array_push( $error_msgs, "Esse aluno já está inscrito nesse evento. Escolha outro." );
                    $subpagina = "inserir_inscrito_pg";
                    break;
                }
            }

            /* Inserindo no Banco */
            $query = "
                UPDATE inscrito_pg
                SET
                    agv_id      = '" . in_bd( $dados[ 'agv_id' ] )      . "',
                    evt_id      = '" . in_bd( $dados[ 'evt_id' ] )      . "',
                    cat_id      = '" . in_bd( $dados[ 'cat_id' ] )      . "',
                    prf_id_1    = '" . in_bd( $dados[ 'prf_id_1' ] )    . "',
                    prf_id_2    = '" . in_bd( $dados[ 'prf_id_2' ] )    . "',
                    ipg_resumo  = '" . in_bd( $dados[ 'ipg_resumo' ] )  . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ 'evt_id' ] )      . "'
                    AND agv_id  = '" . in_bd( $dados[ 'agv_id_old' ] )  . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_inscrito_pg";
        break;
    case "inscrito":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );
        extract_request_var( 'ang_id',  $dados[ 'ang_id' ] );

        extract_request_var( 'igv_id',  $dados[ 'igv_id' ] );
        extract_request_var( 'ing_id',  $dados[ 'ing_id' ] );

        /* Campos Form */
        extract_request_var( "i_aluno_gv",  $dados[ 'i_aluno_gv' ] );
        extract_request_var( "i_nome",      $dados[ 'i_nome' ] );
        extract_request_var( "i_endereco",  $dados[ 'i_endereco' ] );
        extract_request_var( "i_bairro",    $dados[ 'i_bairro' ] );
        extract_request_var( "i_ddd",  $dados[ 'i_ddd' ] );
        extract_request_var( "i_ddi",  $dados[ 'i_ddi' ] );
        extract_request_var( "i_telefone",  $dados[ 'i_telefone' ] );
        extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
        extract_request_var( "i_dt_nasci",  $dados[ 'i_dt_nasci' ] );
        extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
        extract_request_var( "i_email",     $dados[ 'i_email' ] );
        extract_request_var( "i_convidado", $dados[ 'i_convidado' ] );
        extract_request_var( "tcv_id",      $dados[ 'tcv_id' ] );
        extract_request_var( "i_curso",     $dados[ 'i_curso' ] );
        extract_request_var( "i_faculdade", $dados[ 'i_faculdade' ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ 'i_nome' ] == "" )
            array_push($error_msgs, "É necessário preencher o nome do aluno" );

        if( ! consis_data( $dados[ 'i_dt_nasci' ][ 'dia' ],
                            $dados[ 'i_dt_nasci' ][ 'mes' ],
                            $dados[ 'i_dt_nasci' ][ 'ano' ] ) )
            array_push( $error_msgs, "Data de Nascimento inválida" );

        if( ! consis_email( $dados[ "i_email" ], 0 ) )
            array_push( $error_msgs, "Email inválido" );

        if( ! consis_telefone( $dados[ "i_cep" ], 0 ) )
            array_push( $error_msgs, "CEP inválido" );

        if( ! consis_telefone( $dados[ "i_ddi" ], 0 ) )
            array_push( $error_msgs, "DDI inválido" );

        if( ! consis_telefone( $dados[ "i_ddd" ], 0 ) )
            array_push( $error_msgs, "DDD inválido" );

        if( ! consis_telefone( $dados[ "i_telefone" ], 0 ) )
            array_push( $error_msgs, "Telefone inválido" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_inscrito";
            $alterar = "yeah";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            if( consis_inteiro( $dados[ "agv_id" ] ) )
            {
                /* Atualizando tabela inscrito_gv */
                $query = "
                    UPDATE inscrito_gv
                    SET
                        tcv_id = " . ( ( consis_inteiro( $dados[ "tcv_id" ] ) ) ? "'" . in_bd( $dados[ "tcv_id" ] ) . "'" : "NULL" ) . ",
                        igv_convidado = '" . in_bd( $dados[ 'i_convidado' ] ) . "'
                    WHERE
                        igv_id = '" . in_bd( $dados[ 'igv_id' ] ) . "'";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    /* Atualizando dados do aluno da gv (o cara pode ter alterado) */
                    $query = "
                        UPDATE aluno_gv
                        SET
                            agv_nome = '"       . in_bd( $dados[ 'i_nome' ] )       . "',
                            agv_endereco = '"   . in_bd( $dados[ 'i_endereco' ] )   . "',
                            agv_bairro = '"     . in_bd( $dados[ 'i_bairro' ] )     . "',
                            agv_ddd = '"   . in_bd( $dados[ 'i_ddd' ] )    . "',
                            agv_ddi = '"   . in_bd( $dados[ 'i_ddi' ] )    . "',
                            agv_telefone = '"   . in_bd( $dados[ 'i_telefone' ] )    . "',
                            agv_cep = '"        . in_bd( $dados[ 'i_cep' ] )        . "',
                            agv_dt_nasci = '"   . in_bd( hash_to_databd( $dados[ 'i_dt_nasci' ] ) ) . "',
                            agv_email = '"      . in_bd( $dados[ 'i_email' ] )      . "'
                        WHERE
                            agv_id = '"         . in_bd( $dados[ 'agv_id' ] )       . "'";

                    $rs = $sql->query( $query );

                    if( $rs )
                    {
                        $sql->query( "COMMIT TRANSACTION" );
                        break;
                    }
                }
            }
            else
            {
                /* Atualizando tabela inscrito_ngv */
                $query = "
                    UPDATE inscrito_ngv
                    SET

                        tcv_id = " . ( ( consis_inteiro( $dados[ "tcv_id" ] ) ) ? "'" . in_bd( $dados[ "tcv_id" ] ) . "'" : "NULL" ) . ",
                        ing_convidado = '" . in_bd( $dados[ 'i_convidado' ] ) . "'
                    WHERE
                        ing_id = '" . in_bd( $dados[ 'ing_id' ] ) . "'";

                $rs = $sql->query( $query );

                if( $rs )
                {
                    /* Atualizando tabela aluno_nao_gv */
                    $query = "
                        UPDATE aluno_nao_gv
                        SET
                            ang_nome = '"       . in_bd( $dados[ 'i_nome' ] )       . "',
                            ang_curso = '"      . in_bd( $dados[ 'i_curso' ] )      . "',
                            ang_faculdade = '"  . in_bd( $dados[ 'i_faculdade' ] )  . "',
                            ang_endereco = '"   . in_bd( $dados[ 'i_endereco' ] )   . "',
                            ang_bairro = '"     . in_bd( $dados[ 'i_bairro' ] )     . "',
                            ang_ddd = '"   . in_bd( $dados[ 'i_ddd' ] )    . "',
                            ang_ddi = '"   . in_bd( $dados[ 'i_ddi' ] )    . "',
                            ang_telefone = '"   . in_bd( $dados[ 'i_telefone' ] )    . "',
                            ang_cep = '"        . in_bd( $dados[ 'i_cep' ] )        . "',
                            ang_dt_nasci = '"   . in_bd( hash_to_databd( $dados[ 'i_dt_nasci' ] ) ) . "',
                            ang_email = '"      . in_bd( $dados[ 'i_email' ] )      . "'
                        WHERE
                            ang_id = '"         . in_bd( $dados[ 'ang_id' ] )       . "'";

                    $rs = $sql->query( $query );

                    if( $rs )
                    {
                        $sql->query( "COMMIT TRANSACTION" );
                        break;
                    }
                }
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_inscrito";
        break;
    case "custo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "cto_id",          $dados[ 'cto_id' ] );
        extract_request_var( "cto_nome",        $dados[ 'cto_nome' ] );
        extract_request_var( "cto_t_movimento", $dados[ 'cto_t_movimento' ] );
        extract_request_var( "cto_valor",       $dados[ 'cto_valor' ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "cto_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher a Descrição da receita ou despesa" );

        if( ! consis_boleano( $dados[ 'cto_t_movimento' ] ) )
            array_push( $error_msgs, "Valor inválido para tipo de movimento" );

        if( $dados[ "cto_valor" ] == "" || ! consis_dinheiro( reconhece_dinheiro( $dados[ "cto_valor" ] ) ) )
            array_push( $error_msgs, "Você precisa preencher um valor válido" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_custo";
            $alterar   = "yeah";
            break;
        }

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_custo
                SET
                    cto_nome = '"           . in_bd( $dados[ "cto_nome" ] )             . "',
                    cto_t_movimento = '"    . in_bd( $dados[ "cto_t_movimento" ] )            . "',
                    cto_valor   = '"        . in_bd( reconhece_dinheiro( $dados[ "cto_valor" ] ) ) . "'
                WHERE
                    cto_id      = '"        . in_bd( $dados[ "cto_id" ] )  . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_custo";
        $alterar = "yeah";
        break;
    case "evt_arquivo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "ear_id",      $dados[ "ear_id" ] );
        extract_request_var( "ear_nome",    $dados[ "ear_nome" ] );
        extract_request_var( "ear_desc",    $dados[ "ear_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "ear_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do item de finalização" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_evt_arquivo";
            break;
        }

        /* Atualizando o Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_arquivo
                SET
                    ear_nome = '" . $dados[ 'ear_nome' ] . "',
                    ear_desc = '" . $dados[ 'ear_desc' ] . "'
                WHERE
                    ear_id = '" . $dados[ 'ear_id' ] . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                if( $_FILES[ 'ear_arq' ][ 'name' ] != '' )
                {
                    $dados[ 'ear_arq_real' ] = "evt_ear_" . $dados[ 'ear_id' ];

                    $error_msgs = faz_upload( $sql, "ear_id", $dados[ 'ear_id' ], "ear_arq", $dados[ 'ear_arq_real' ], "ear_arq_real", "ear_arq_falso", "evt_arquivo" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'ear_arq_real' ] ) && is_writable( $dados[ 'ear_arq_real' ] ) )
                            unlink( $dados[ 'ear_arq_real' ] );

                        /* Cancelar Queries */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_evt_arquivo";
                        $alterar = "yeah";
                        break;
                    }
                }
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_evt_arquivo";
        break;
    case "item_final":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "ifi_id",      $dados[ "ifi_id" ] );
        extract_request_var( "ifi_nome",    $dados[ "ifi_nome" ] );
        extract_request_var( "ifi_desc",    $dados[ "ifi_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "ifi_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do item de finalização" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_item_final";
            break;
        }

        /* Atualizando o Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE item_final
                SET
                    ifi_nome = '" . $dados[ 'ifi_nome' ] . "',
                    ifi_desc = '" . $dados[ 'ifi_desc' ] . "'
                WHERE
                    ifi_id = '" . $dados[ 'ifi_id' ] . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                if( $_FILES[ 'ifi_arq' ][ 'name' ] != '' )
                {
                    $dados[ 'ifi_arq_real' ] = "evt_ifi_" . $dados[ 'ifi_id' ];

                    $error_msgs = faz_upload( $sql, "ifi_id", $dados[ 'ifi_id' ], "ifi_arq", $dados[ 'ifi_arq_real' ], "ifi_arq_real", "ifi_arq_falso", "item_final" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'ifi_arq_real' ] ) && is_writable( $dados[ 'ifi_arq_real' ] ) )
                            unlink( $dados[ 'ifi_arq_real' ] );

                        /* Cancelar Queries */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_item_final";
                        $alterar = "yeah";
                        break;
                    }
                }
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_item_final";
        break;
    case "material_grafico":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "mgf_id",      $dados[ "mgf_id" ] );
        extract_request_var( "mgf_nome",    $dados[ "mgf_nome" ] );
        extract_request_var( "mgf_desc",    $dados[ "mgf_desc" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "mgf_nome" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o nome do material gráfico" );

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_material_grafico";
            break;
        }

        /* Atualizando o Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE material_grafico
                SET
                    mgf_nome = '" . $dados[ 'mgf_nome' ] . "',
                    mgf_desc = '" . $dados[ 'mgf_desc' ] . "'
                WHERE
                    mgf_id = '" . $dados[ 'mgf_id' ] . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                if( $_FILES[ 'mgf_arq' ][ 'name' ] != '' )
                {
                    $dados[ 'mgf_arq_real' ] = "evt_mgf_" . $dados[ 'mgf_id' ];

                    $error_msgs = faz_upload( $sql, "mgf_id", $dados[ 'mgf_id' ], "mgf_arq", $dados[ 'mgf_arq_real' ], "mgf_arq_real", "mgf_arq_falso", "material_grafico" );

                    if( sizeof( $error_msgs ) )
                    {
                        /* Deu Erro, apagar arquivo do filesystem se existir e for gravavel */
                        if( file_exists( $dados[ 'mgf_arq_real' ] ) && is_writable( $dados[ 'mgf_arq_real' ] ) )
                            unlink( $dados[ 'mgf_arq_real' ] );

                        /* Cancelar Queries */
                        $sql->query( "ROLLBACK TRANSACTION" );

                        /* Voltar para o cadastro e mostrar erros na tela ($error_msgs) */
                        $subpagina = "inserir_material_grafico";
                        $alterar = "yeah";
                        break;
                    }
                }
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_material_grafico";
        break;
    case "patrocinador":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "pat_id",      $dados[ "pat_id" ] );
        extract_request_var( "pat_id_old",  $dados[ "pat_id_old" ] );

        extract_request_var( "epa_texto",   $dados[ "epa_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "pat_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um patrocinador da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );

        if( $dados[ "pat_id" ] != $dados[ "pat_id_old" ] )
        {
            $query = "
                SELECT
                    COUNT(pat_id)
                FROM
                    evt_pat
                WHERE
                    pat_id = '" . $dados[ 'pat_id' ] . "'
                    AND evt_id = '" . $dados[ 'evt_id' ] . "'";

            $rs = $sql->squery( $query );

            if( $rs[ 'count' ] > 0 )
                array_push( $error_msgs, "Esse patrocinador já consta para esse Evento, escolha outro." );
        }

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_patrocinador";
            $alterar   = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_pat
                SET
                    pat_id      = '" . in_bd( $dados[ "pat_id" ] )      . "',
                    mem_id      = '" . in_bd( $dados[ "mem_id" ] )      . "',
                    stc_id      = '" . in_bd( $dados[ "stc_id" ] )      . "',
                    epa_texto   = '" . in_bd( $dados[ "epa_texto" ] )   . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ "evt_id" ] )      . "'
                    AND pat_id  = '" . in_bd( $dados[ "pat_id_old" ] )      . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_patrocinador";
        $alterar = "yeah";
        break;
    case "fornecedor":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "for_id",      $dados[ "for_id" ] );
        extract_request_var( "for_id_old",  $dados[ "for_id_old" ] );

        extract_request_var( "efo_texto",   $dados[ "efo_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "for_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um fornecedor da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );

        if( $dados[ "for_id" ] != $dados[ "for_id_old" ] )
        {
            $query = "
                SELECT
                    COUNT(for_id)
                FROM
                    evt_for
                WHERE
                    for_id = '" . $dados[ 'for_id' ] . "'
                    AND evt_id = '" . $dados[ 'evt_id' ] . "'";

            $rs = $sql->squery( $query );

            if( $rs[ 'count' ] > 0 )
                array_push( $error_msgs, "Esse fornecedor já consta para esse Evento, escolha outro." );
        }

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_fornecedor";
            $alterar   = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_for
                SET
                    for_id      = '" . in_bd( $dados[ "for_id" ] )      . "',
                    mem_id      = '" . in_bd( $dados[ "mem_id" ] )      . "',
                    stc_id      = '" . in_bd( $dados[ "stc_id" ] )      . "',
                    efo_texto   = '" . in_bd( $dados[ "efo_texto" ] )   . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ "evt_id" ] )      . "'
                    AND for_id  = '" . in_bd( $dados[ "for_id_old" ] )      . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_fornecedor";
        $alterar = "yeah";
        break;
    case "palestrante":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "pal_id",      $dados[ "pal_id" ] );
        extract_request_var( "pal_id_old",  $dados[ "pal_id_old" ] );

        extract_request_var( "epl_texto",   $dados[ "epl_texto" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "stc_id",      $dados[ "stc_id" ] );

        /* Validacao */
        $error_msgs = array();

        if( ! consis_inteiro( $dados[ "pal_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um Palestrante da lista" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) ) 
            array_push( $error_msgs, "Você precisa escolher um responsável" );

        if( $dados[ "pal_id" ] != $dados[ "pal_id_old" ] )
        {
            $query = "
                SELECT
                    COUNT(pal_id)
                FROM
                    evt_pal
                WHERE
                    pal_id = '" . $dados[ 'pal_id' ] . "'
                    AND evt_id = '" . $dados[ 'evt_id' ] . "'";

            $rs = $sql->squery( $query );

            if( $rs[ 'count' ] > 0 )
                array_push( $error_msgs, "Esse palestrante já consta para esse Evento, escolha outro." );
        }

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_palestrante";
            $alterar   = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_pal
                SET
                    pal_id      = '" . in_bd( $dados[ "pal_id" ] )      . "',
                    mem_id      = '" . in_bd( $dados[ "mem_id" ] )      . "',
                    stc_id      = '" . in_bd( $dados[ "stc_id" ] )      . "',
                    epl_texto   = '" . in_bd( $dados[ "epl_texto" ] )   . "'
                WHERE
                    evt_id      = '" . in_bd( $dados[ "evt_id" ] )      . "'
                    AND pal_id  = '" . in_bd( $dados[ "pal_id_old" ] )      . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_palestrante";
        $alterar = "yeah";
        break;
    case "tarefa_cronograma_dt_ent_art":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "evt_dt_ent_art", $dados[ "evt_dt_ent_art" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ 'evt_dt_ent_art' ][ 'dia' ] == 0 ||
            $dados[ 'evt_dt_ent_art' ][ 'mes' ] == 0 || 
            $dados[ 'evt_dt_ent_art' ][ 'ano' ] == 0 )
            $nova_data = 'NULL';
        else
            if( ! consis_data( $dados[ 'evt_dt_ent_art' ][ 'dia' ],
                               $dados[ 'evt_dt_ent_art' ][ 'mes' ],
                               $dados[ 'evt_dt_ent_art' ][ 'ano' ] ) )
                array_push( $error_msgs, 'Data de Entrega de Artigo inválida' ); 
            else
                $nova_data = "'" . in_bd( hash_to_databd( $dados[ 'evt_dt_ent_art' ] ) ) . "'";

        if( sizeof( $error_msgs ) )
            break;

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evento
                SET
                    evt_dt_ent_art  = " . $nova_data . "
                WHERE
                    evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'";
    
            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $alterar = "yeah";
        break;
    case "tarefa_cronograma":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        /* Vars */
        extract_request_var( "eta_id",      $dados[ "eta_id" ] );
        extract_request_var( "eta_desc",    $dados[ "eta_desc" ] );
        extract_request_var( "mem_id",      $dados[ "mem_id" ] );
        extract_request_var( "ste_id",      $dados[ "ste_id" ] );
        extract_request_var( "eta_dt_ini",  $dados[ "eta_dt_ini" ] );
        extract_request_var( "eta_dt_fim",  $dados[ "eta_dt_fim" ] );

        /* Validacao */
        $error_msgs = array();

        if( $dados[ "eta_desc" ] == "" )
            array_push( $error_msgs, "Você precisa preencher o campo de Tarefa" );

        if( ! consis_inteiro( $dados[ "mem_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um responsável para a tarefa" );

        if( ! consis_inteiro( $dados[ "ste_id" ] ) )
            array_push( $error_msgs, "Você precisa escolher um status para a tarefa" );

        if( ! consis_data( $dados[ "eta_dt_ini" ][ "dia" ], $dados[ "eta_dt_ini" ][ "mes" ], $dados[ "eta_dt_ini" ][ "ano" ] ) )
            array_push( $error_msgs, "Data de Início inválida" ); 

        if( ! consis_data( $dados[ "eta_dt_fim" ][ "dia" ], $dados[ "eta_dt_fim" ][ "mes" ], $dados[ "eta_dt_fim" ][ "ano" ] ) )
            array_push( $error_msgs, "Data de Fim inválida" ); 

        if( sizeof( $error_msgs ) )
        {
            $subpagina = "inserir_tarefa_cronograma";
            $alterar   = "yeah";
            break;
        }

        /* Atualizando Banco */
        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "
                UPDATE evt_tarefa
                SET
                    mem_id      = '" . in_bd( $dados[ "mem_id" ] )    . "',
                    evt_id      = '" . in_bd( $dados[ "evt_id" ] )    . "',
                    ste_id      = '" . in_bd( $dados[ "ste_id" ] )    . "',
                    eta_desc    = '" . in_bd( $dados[ "eta_desc" ] )  . "',
                    eta_dt_ini  = '" . in_bd( hash_to_databd( $dados[ "eta_dt_ini" ] ) ) . "',
                    eta_dt_fim  = '" . in_bd( hash_to_databd( $dados[ "eta_dt_fim" ] ) ) . "'
                WHERE
                    eta_id      = '" . in_bd( $dados[ "eta_id" ] )    . "'";

            $rs = $sql->query( $query );

            if( $rs )
            {
                $sql->query( "COMMIT TRANSACTION" );
                break;
            }
        }

        $sql->query( "ROLLBACK TRANSACTION" );
        array_push( $error_msgs, "Aconteceu um erro inesperado" );
        $subpagina = "inserir_tarefa_cronograma";
        $alterar = "yeah";
        break;
    }
    break;





/*
 *
 * APAGAR
 *
 */





case "apagar":
    switch( $tipo )
    {
    case "integrante":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( 'eqp_id',      $dados[ 'eqp_id' ] );
        extract_request_var( 'caras_ids',   $dados[ 'caras_ids' ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM eqp_agv WHERE eqp_id = '" . in_bd( $dados[ 'eqp_id' ] ) . "' AND ( agv_id = ''";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR agv_id = '" . in_bd( $id ) . "'";

            $query .= ") ";

            $rs = $sql->query( $query );

            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $rs );
        unset( $id );
        break;
    case "inscrito_superacao":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids", $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM equipe WHERE eqp_id = ''";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR eqp_id = '" . in_bd( $id ) . "'";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $rs );
        unset( $id );
        break;
    case "equipe_alocada":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",   $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_mem WHERE evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' AND ( mem_id = '' ";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR mem_id = '" . in_bd( $id ) . "'";
            
            $query .= " )";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        unset( $query );
        break;
    case "inscrito_pg":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",   $dados[ "caras_ids" ] );

        if( ! is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            /* Apagando inscritos PG */
            if( is_array( $dados[ 'caras_ids' ] ) )
            {
                $query = "DELETE FROM inscrito_pg WHERE evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' AND ( agv_id = '' ";

                foreach( $dados[ 'caras_ids' ] as $id )
                    $query .= "OR agv_id = '" . in_bd( $id ) . "'";

                $query .= " )";

                $rs = $sql->query( $query );
            
                if( $rs )
                    $sql->query( "COMMIT TRANSACTION" );
                else
                    $sql->query( "ROLLBACK TRANSACTION" );
            }
        }

        unset( $rs );
        unset( $id );
        break;
    case "inscrito":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_agv_ids",   $dados[ "caras_agv_ids" ] );
        extract_request_var( "caras_ang_ids",   $dados[ "caras_ang_ids" ] );

        if( ! is_array( $dados[ "caras_agv_ids" ] ) && ! is_array( $dados[ "caras_ang_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            /* Apagando inscritos GV */
            if( is_array( $dados[ 'caras_agv_ids' ] ) )
            {
                $query = "DELETE FROM inscrito_gv WHERE igv_id = ''";

                foreach( $dados[ 'caras_agv_ids' ] as $id )
                    $query .= "OR igv_id = '" . in_bd( $id ) . "'";

                $rs = $sql->query( $query );
            
                if( ! $rs )
                {
                    $sql->query( "ROLLBACK TRANSACTION" );
                    break;
                }
            }

            if( is_array( $dados[ 'caras_ang_ids' ] ) )
            {
                $query = "DELETE FROM aluno_nao_gv WHERE ang_id = ''";

                foreach( $dados[ 'caras_ang_ids' ] as $id )
                    $query .= "OR ang_id = '" . in_bd( $id ) . "'";

                $rs = $sql->query( $query );
            }

            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $rs );
        unset( $id );
        break;
    case "custo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_custo WHERE cto_id = ''";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR cto_id = '" . in_bd( $id ) . "'";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $rs );
        unset( $id );
        break;
    case "evt_arquivo":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            foreach( $dados[ 'caras_ids' ] as $cara )
            {
                $rs = $sql->squery( "
                    SELECT
                        ear_arq_real
                    FROM
                        evt_arquivo
                    WHERE
                        ear_id = '" . in_bd( $cara ) . "'" );

                if( $rs )
                {
                    $arq_real = UPLOAD_DIR . "/" . $rs[ 'ear_arq_real' ];
                    
                    if( file_exists( $arq_real ) && is_writable( $arq_real ) )
                        unlink( $arq_real );
                }
               
                $rs = $sql->query( "DELETE FROM evt_arquivo WHERE ear_id = '" . in_bd( $cara ) . "'" );
            }
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        unset( $rs );
        unset( $arq_real );
        break;
    case "item_final":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            foreach( $dados[ 'caras_ids' ] as $cara )
            {
                $rs = $sql->squery( "
                    SELECT
                        ifi_arq_real
                    FROM
                        item_final
                    WHERE
                        ifi_id = '" . in_bd( $cara ) . "'" );

                if( $rs )
                {
                    $arq_real = UPLOAD_DIR . "/" . $rs[ 'ifi_arq_real' ];
                    
                    if( file_exists( $arq_real ) && is_writable( $arq_real ) )
                        unlink( $arq_real );
                }
               
                $rs = $sql->query( "DELETE FROM item_final WHERE ifi_id = '" . in_bd( $cara ) . "'" );
            }
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        unset( $rs );
        unset( $arq_real );
        break;
    case "material_grafico":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            foreach( $dados[ 'caras_ids' ] as $cara )
            {
                $rs = $sql->squery( "
                    SELECT
                        mgf_arq_real
                    FROM
                        material_grafico
                    WHERE
                        mgf_id = '" . in_bd( $cara ) . "'" );

                if( $rs )
                {
                    $arq_real = UPLOAD_DIR . "/" . $rs[ 'mgf_arq_real' ];
                    
                    if( file_exists( $arq_real ) && is_writable( $arq_real ) )
                        unlink( $arq_real );
                }
               
                $rs = $sql->query( "DELETE FROM material_grafico WHERE mgf_id = '" . in_bd( $cara ) . "'" );
            }
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        unset( $arq_real );
        break;
    case "patrocinador":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_pat WHERE evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' AND ( pat_id = '' ";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR pat_id = '" . in_bd( $id ) . "'";
            
            $query .= " )";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        break;
    case "fornecedor":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_for WHERE evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' AND ( for_id = '' ";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR for_id = '" . in_bd( $id ) . "'";
            
            $query .= " )";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        break;
    case "palestrante":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_pal WHERE evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' AND ( pal_id = '' ";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR pal_id = '" . in_bd( $id ) . "'";
            
            $query .= " )";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        break;
    case "tarefa_cronograma":
        if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            $query = "DELETE FROM evt_tarefa WHERE eta_id = ''";

            foreach( $dados[ "caras_ids" ] as $id )
                $query .= "OR eta_id = '" . in_bd( $id ) . "'";

            $rs = $sql->query( $query );
            
            if( $rs )
                $sql->query( "COMMIT TRANSACTION" );
            else
                $sql->query( "ROLLBACK TRANSACTION" );
        }

        unset( $id );
        break;
    case "evento":
        if( ! tem_permissao( FUNC_MKT_EVENTO_APAGAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
        extract_request_var( "caras_ids",     $dados[ "caras_ids" ] );

        if( !is_array( $dados[ "caras_ids" ] ) )
            break;

        $rs = $sql->query( "BEGIN TRANSACTION" );
        if( $rs )
        {
            /* Primeiro apagar todos uploads dos Eventos selecionados */

            $q_del_evt = "DELETE FROM evento WHERE evt_id = ''";

            $q_sel_mgf = "
                SELECT
                    mgf_arq_real as arq
                FROM
                    material_grafico
                WHERE
                    evt_id = ''";

            $q_sel_ifi = "
                SELECT
                    ifi_arq_real as arq
                FROM
                    item_final
                WHERE
                    evt_id = ''";

            $q_sel_ear = "
                SELECT
                    ear_arq_real as arq
                FROM
                    evt_arquivo
                WHERE
                    evt_id = ''";

            $q_ids = "";
            foreach( $dados[ "caras_ids" ] as $id )
                $q_ids .= "OR evt_id = '" . in_bd( $id ) . "'";

            $q_del_evt .= $q_ids;
            $q_sel_mgf .= $q_ids;
            $q_sel_ifi .= $q_ids;
            $q_sel_ear .= $q_ids;

            $rs_sel_mgf = $sql->query( $q_sel_mgf );
            $rs_sel_ifi = $sql->query( $q_sel_ifi );
            $rs_sel_ear = $sql->query( $q_sel_ear );
            $rs_del_evt = $sql->query( $q_del_evt );

            if( $rs_del_evt && $rs_sel_mgf && $rs_sel_ifi && $rs_sel_ear )
            {
                if( is_array( $rs_sel_mgf ) )
                {
                    foreach( $rs_sel_mgf as $arq )
                    {
                        $arq[ 'arq' ] = UPLOAD_DIR . "/" . $arq[ 'arq' ];
                        if( file_exists( $arq[ 'arq' ] ) && is_writeable( $arq[ 'arq' ] ) )
                            unlink( $arq[ 'arq' ] );
                    }
                }

                if( is_array( $rs_sel_ifi ) )
                {
                    foreach( $rs_sel_ifi as $arq )
                    {
                        $arq[ 'arq' ] = UPLOAD_DIR . "/" . $arq[ 'arq' ];
                        if( file_exists( $arq[ 'arq' ] ) && is_writeable( $arq[ 'arq' ] ) )
                            unlink( $arq[ 'arq' ] );
                    }
                }

                if( is_array( $rs_sel_ear ) )
                {
                    foreach( $rs_sel_ear as $arq )
                    {
                        $arq[ 'arq' ] = UPLOAD_DIR . "/" . $arq[ 'arq' ];
                        if( file_exists( $arq[ 'arq' ] ) && is_writeable( $arq[ 'arq' ] ) )
                            unlink( $arq[ 'arq' ] );
                    }
                }

                unset( $q_ids );
                unset( $q_del_evt );
                unset( $q_sel_mgf );
                unset( $q_sel_ifi );
                unset( $q_sel_ifi );
                unset( $rs_del_evt );
                unset( $rs_sel_mgf );
                unset( $rs_sel_ifi );
                unset( $rs_sel_ifi );
                unset( $arq );

                $sql->query( "COMMIT TRANSACTION" );
            }
            else
            {
                $sql->query( "ROLLBACK TRANSACTION" );
            }
        }

        unset( $id );
        break;
    }
    break;
} /* Fim da Acao */
unset( $acao );






/* Tabela comum a todos */
/* Tabela de localizacao (tipo_de_evento - edicao_do_evento -<< link para voltar par ao menu) */
?>
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
<?

/* Tipo de Evendo - Edicao */

if( $dados[ 'tev_nome' ] != "" && $dados[ 'evt_edicao' ] != "" )
{
?>
        <tr>
          <td class="textwhitemini" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="center" />&nbsp;&nbsp;<b><?= $mod_titulo . " - " . $dados[ 'tev_nome' ] . " - " . $dados[ 'evt_edicao' ] ?></b>
    <?
    if( $subpagina != "menu" )
    {
    ?>
        <br />
        <center>
                <a class='lmenu' href='<?= $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=menu&tipo=evento&evt_id=" . $dados[ 'evt_id' ] ."&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'>Voltar para o Menu</a>
        </center>
    <?
    }
    ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <br />

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
<?
}












/*
 *
 * SUBPAGINA:
 *  - HTML
 *  - Selects no BD
 *
 */











switch( $subpagina )
{





/*
 *
 * PARTE ORGANIZACIONAL (po_)
 *
 */





case "po_banca_julgadora":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            prf_id,
            prf_nome,
            cat_nome,
            stc_nome,
            epr_texto,
            epr_entregue
        FROM
            evt_prf
            NATURAL LEFT OUTER JOIN professor
            NATURAL LEFT OUTER JOIN categoria
            NATURAL LEFT OUTER JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
        ORDER BY
            cat_nome,
            prf_nome";

    $rs = $sql->query( $query );
    $colspan = 7;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Banca Julgadora
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="banca_julgadora" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Professor</td>
          <td bgcolor='#ffffff' class='textb'>Categoria</td>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff' class='textb'>Comentário</td>
          <td bgcolor='#ffffff' class='textb'>Entregue</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'prf_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'prf_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'cat_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'stc_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'epr_texto' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( ( $cara[ 'epr_entregue' ] == 0 ) ? "Não" : "Sim" ) ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_banca_julgadora&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&prf_id=" . $cara[ 'prf_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
                <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
                
              </td>
            </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há professores cadastrados na banca julgadora desse evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_banca_julgadora" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $colspan );
    unset( $tot_despesa);
    unset( $tot_receita);
    break;
case "po_equipe_alocada":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( 'mem_id', $dados[ 'mem_id' ] );
    extract_request_var( 'eme_coordenador', $dados[ 'eme_coordenador' ] );

    $query = "
        SELECT
            mem.mem_id,
            mem.mem_nome
        FROM
            evt_mem cst
            LEFT JOIN membro_vivo mem ON ( cst.mem_id = mem.mem_id )
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            mem_nome";

    $rs = $sql->query( $query );

    if( $dados[ 'tev_mne' ] != 'premio_gestao' )
        $colspan = 5;
    else
        $colspan = 6;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Equipe Alocada
      </td>
    </tr>
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="" />
              <input type="hidden" name="tipo"        value="equipe_alocada" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Nome</td>
          <?
          if( $dados[ 'tev_mne' ] == 'premio_gestao' )
          {
          ?>
              <td bgcolor='#ffffff' class='textb'>Coordenador</td>
          <?
          }
          ?> 
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
//shub
        <script language="javascript">
        function mudar(obj)
        {
            if(obj.value.search("serir") >= 0) /* Inserir */
                obj.form.acao.value = "inserir";
            else
                obj.form.acao.value = "apagar";

            obj.form.ok.disabled = true;
            obj.form.submit();
        }
        </script>

    <?
    if( $dados[ 'tev_mne' ] != 'premio_gestao' )
    {
        if( is_array( $rs ) )
        {
            $i = 1;
            foreach( $rs as $cara )
            {
            ?>
                <tr>
                  <td bgcolor='#ffffff' class='text'>
                    <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'mem_id' ] ?>'>
                  </td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
                  <td bgcolor='#ffffff' class='text'><a target='_blank' href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=grade_horario&mem_id=" . $cara[ 'mem_id' ] ?>'>Grade de Horário</a></td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <td bgcolor='#ffffff'>&nbsp;</td>
            <td bgcolor='#ffffff'>&nbsp;</td>
            <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "mem_id", "mem_nome", "membro_vivo", $dados["mem_id"], array("name" => "mem_id")) ?></td>
            <td bgcolor='#ffffff'>&nbsp;</td>
        </tr>
    <?
    }
    else
    {
        if( is_array( $rs ) )
        {
            $i = 1;
            foreach( $rs as $cara )
            {
            ?>
                <tr>
                  <td bgcolor='#ffffff' class='text'>
                    <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'mem_id' ] ?>'>
                  </td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
                  <td bgcolor='#ffffff' class='text'><?= ( ( $cara[ 'eme_coordenador' ] == 1 ) ? "Sim" : "Não" ) ?></td>
                  <td bgcolor='#ffffff' class='text'><a target='_blank' href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=grade_horario&mem_id=" . $cara[ 'mem_id' ] ?>'>Grade de Horário</a></td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
            <td bgcolor='#ffffff'>&nbsp;</td>
            <td bgcolor='#ffffff'>&nbsp;</td>
            <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "mem_id", "mem_nome", "membro_vivo", $dados["mem_id"], array("name" => "mem_id")) ?></td>
            <td bgcolor='#ffffff' class='textb'><input type='checkbox' class="caixa" name='eme_coordenador' value='1' /> Coordenador</td>
            <td bgcolor='#ffffff'>&nbsp;</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td bgcolor='#ffffff' colspan="<?= $colspan ?>" align='center'>
        <input type='button' name="ok" value='Inserir' OnClick="mudar(this);">
        <input type='button' name="ok" value='Apagar'  OnClick="mudar(this);"/>
      </td>
    </tr>
    </form>
    <tr>
      <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_cronograma":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $colspan = 8;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Cronograma
      </td>
    </tr>

    <?if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
    <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                </font></td></tr>
    <?
    }

    /* Se for um evento de premio_gestao tem mostrar um form pra cadastro da data de entrega do artigo (seja la o que for isso ) */
    if( $dados[ 'tev_mne' ] == "premio_gestao" )
    {
        $query = "
            SELECT
                DATE_PART( 'epoch', evt_dt_ent_art ) AS evt_dt_ent_art
            FROM
                evento
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'";

        $rs = $sql->squery( $query );

        if( consis_inteiro( $rs[ 'evt_dt_ent_art' ] ) )
            $dados[ 'evt_dt_ent_art' ]  = array( 'dia' => date( 'd', $rs[ 'evt_dt_ent_art' ] ),
                                                 'mes' => date( 'm', $rs[ 'evt_dt_ent_art' ] ) ,
                                                 'ano' => date( 'Y', $rs[ 'evt_dt_ent_art' ] ) );
        else
            $dados[ 'evt_dt_ent_art' ]  = array( 'dia' => 0,
                                                 'mes' => 0,
                                                 'ano' => 0 );
    ?>
                <tr>
                  <td bgcolor='#ffffff' class='textb'>
                    <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                      <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
                      <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
                      <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
                      <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
                      <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
                      <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
                      <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
                      <input type="hidden" name="acao"        value="alterar" />
                      <input type="hidden" name="tipo"        value="tarefa_cronograma_dt_ent_art" />
                      Data de Entrega do Artigo
                  </td>
                  <td bgcolor='#ffffff'>&nbsp;<? gera_select_data( 'evt_dt_ent_art', $dados[ 'evt_dt_ent_art' ], '', '', 1 ); ?></td>
                </tr>

                <tr>
                  <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
                    <input type="submit" name="ok" value="&nbsp;Alterar&nbsp;" />
                    </form>
                  </td>
                </tr>  
                  </td>
                </tr>
              </table>
            </td>
          </tr>
       </table>

        <br />

      <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
        <tr>
          <td>
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Cronograma - Tarefas
              </td>
            </tr>
    <?
    }

    $query = "
        SELECT
            mem_nome,
            eta_id,
            eta_desc,
            DATE_PART( 'epoch', eta_dt_ini ) AS eta_dt_ini,
            DATE_PART( 'epoch', eta_dt_fim ) AS eta_dt_fim,
            ste_nome
        FROM
            evt_tarefa
            NATURAL LEFT OUTER JOIN membro_vivo
            NATURAL LEFT OUTER JOIN status_evento
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            eta_dt_inc DESC";
    
    $rs = $sql->query( $query );

    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="tarefa_cronograma" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Tarefa</td>
          <td bgcolor='#ffffff' class='textb'>Data Início</td>
          <td bgcolor='#ffffff' class='textb'>Data Fim</td>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $i = 1;
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'eta_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'eta_desc' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= date( "d", $cara[ 'eta_dt_ini' ] ) . "/" . date( "m", $cara[ 'eta_dt_ini' ] ) . "/" .  date( "Y", $cara[ 'eta_dt_ini' ] ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= date( "d", $cara[ 'eta_dt_fim' ] ) . "/" . date( "m", $cara[ 'eta_dt_fim' ] ) . "/" .  date( "Y", $cara[ 'eta_dt_fim' ] ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'ste_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_tarefa_cronograma&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&eta_id=" . $cara[ 'eta_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há tarefas cadastradas para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_tarefa_cronograma" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_custo":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            cto_id,
            cto_nome,
            cto_t_movimento,
            cto_valor
        FROM
            evt_custo
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            cto_dt_inc ASC";
    
    $rs = $sql->query( $query );
    $colspan = 6;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Receitas e Despesas
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="custo" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Descrição</td>
          <td bgcolor='#ffffff' class='textb'>Tipo de Movimento</td>
          <td bgcolor='#ffffff' class='textb'>Valor</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $i = 1;
        $tot_despesa = 0;
        $tot_receita = 0;
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'cto_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'cto_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( ( $cara[ 'cto_t_movimento' ] == 0 ) ? "Receita" : "Despesa" ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= formata_dinheiro( $cara[ 'cto_valor' ], 1 ) ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_custo&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&cto_id=" . $cara[ 'cto_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
            if( $cara[ 'cto_t_movimento' ] == 0 )
                $tot_receita += $cara[ 'cto_valor' ];
            else
                $tot_despesa += $cara[ 'cto_valor' ];
        }
        ?>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
                <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
                
              </td>
            </tr></form>  
            </table>
          </td>
        </tr>
      </table>
      <br />
      <br />
    </center>

    <center>
      <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
        <tr>
          <td>
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Receitas e Despesas - Totais
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Receita</td>
              <td bgcolor='#ffffff' class="textb">Despesa</td>
              <td bgcolor='#ffffff' class="textb">( Receita - Despesa )</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">&nbsp;<?= formata_dinheiro( $tot_receita, 1 ) ?></td>
              <td bgcolor='#ffffff' class="textb">&nbsp;<?= formata_dinheiro( $tot_despesa, 1 ) ?></td>
              <td bgcolor='#ffffff' class="textb">&nbsp;<?= formata_dinheiro( $tot_receita - $tot_despesa, 1 ) ?></td>
            </tr>
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há receitas e despesas cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_custo" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $colspan );
    unset( $tot_despesa);
    unset( $tot_receita);
    break;
    
case "po_palestrante":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            pal_id,
            pal_nome,
            stc_nome,
            mem_nome,
            epl_texto
        FROM
            evt_pal
            NATURAL LEFT JOIN palestrante
            NATURAL LEFT JOIN membro_vivo
            NATURAL LEFT JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            epl_dt_inc DESC";

    $rs = $sql->query( $query );
    $colspan = 7;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Palestrantes
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="palestrante" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Palestrante</td>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $i = 1;
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'pal_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'pal_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'stc_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'epl_texto' ] ) < 20 ? $cara[ 'epl_texto' ] : substr( $cara[ 'epl_texto' ], 0, 20 ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_palestrante&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&pal_id=" . $cara[ 'pal_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há palestrantes cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_palestrante" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_fornecedor":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            for_id,
            for_nome,
            stc_nome,
            mem_nome,
            efo_texto
        FROM
            evt_for
            NATURAL LEFT JOIN fornecedor
            NATURAL LEFT JOIN membro_vivo
            NATURAL LEFT JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            efo_dt_inc DESC";
    
    $rs = $sql->query( $query );
    $colspan = 7;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Fornecedores
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="fornecedor" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Fornecedor</td>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $i = 1;
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'for_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'for_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'stc_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'efo_texto' ] ) < MAX_TEXTO ? $cara[ 'efo_texto' ] : substr( $cara[ 'efo_texto' ], 0, MAX_TEXTO ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_fornecedor&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&for_id=" . $cara[ 'for_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há fornecedores cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_fornecedor" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_patrocinador":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            pat_id,
            pat_nome,
            stc_nome,
            mem_nome,
            epa_texto
        FROM
            evt_pat
            NATURAL LEFT JOIN patrocinador
            NATURAL LEFT JOIN membro_vivo
            NATURAL LEFT JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            epa_dt_inc DESC";
    
    $rs = $sql->query( $query );
    $colspan = 7;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Patrocinadores
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="patrocinador" />
          </td>
          <td bgcolor='#ffffff' class='textb'>N&ordm;</td>
          <td bgcolor='#ffffff' class='textb'>Patrocinador</td>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $i = 1;
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'pat_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $i++ ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'pat_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'stc_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'epa_texto' ] ) < MAX_TEXTO ? $cara[ 'epa_texto' ] : substr( $cara[ 'epa_texto' ], 0, MAX_TEXTO ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_patrocinador&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&pat_id=" . $cara[ 'pat_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr>  </form>
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há patrocinadores cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_patrocinador" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_material_grafico":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            mgf_id,
            mgf_nome,
            mgf_desc,
            mgf_arq_real,
            mgf_arq_falso
        FROM
            material_grafico
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            mgf_dt_inc DESC";
    
    $rs = $sql->query( $query );
    $colspan = 5;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp; Material Gráfico
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="material_grafico" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'mgf_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'mgf_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'mgf_desc' ] ) < MAX_TEXTO ? $cara[ 'mgf_desc' ] : substr( $cara[ 'mgf_desc' ], 0, MAX_TEXTO ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'><a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $cara[ 'mgf_id' ] . "&tabela=material_grafico&col_id=mgf_id&arq_col_r=mgf_arq_real&arq_col_f=mgf_arq_falso" ?>'><?= $cara[ 'mgf_arq_falso' ] ?></a></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_material_grafico&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&mgf_id=" . $cara[ 'mgf_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há materiais gráficos cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_material_grafico" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_inscrito_pg":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            agv_id,
            agv_nome,
            cat_nome,
            p1.prf_nome as prf_nome_1,
            p2.prf_nome as prf_nome_2,
            ipg_resumo,
            (
                DATE_PART( 'day',   evt_dt_ent_art ) || '/' ||
                DATE_PART( 'month', evt_dt_ent_art ) || '/' ||
                DATE_PART( 'year',  evt_dt_ent_art )
            ) AS evt_dt_ent_art
        FROM
            (
                inscrito_pg
                NATURAL LEFT OUTER JOIN aluno_gv
                NATURAL LEFT OUTER JOIN categoria
                NATURAL LEFT OUTER JOIN evento
            ),
            professor p1,
            professor p2
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
            AND p1.prf_id = prf_id_1
            AND p2.prf_id = prf_id_2
        ORDER BY
            agv_nome,
            cat_nome";

/*
    $query = "
        SELECT
            agv_id,
            agv_nome,
            cat_nome,
            p1.prf_nome as prf_nome_1,
            p2.prf_nome as prf_nome_2,
            ipg_resumo,
            (
                DATE_PART( 'day',   evt_dt_ent_art ) || '/' ||
                DATE_PART( 'month', evt_dt_ent_art ) || '/' ||
                DATE_PART( 'year',  evt_dt_ent_art )
            ) AS evt_dt_ent_art
        FROM
            (
                inscrito_pg
                NATURAL LEFT OUTER JOIN aluno_gv
                NATURAL LEFT OUTER JOIN categoria
                NATURAL LEFT OUTER JOIN evento
            )
            LEFT JOIN professor ON ( prf_id = prf_id_1 )
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
        ORDER BY
            agv_nome,
            cat_nome";

    */

    $rs = $sql->query( $query );
    $colspan = 8;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="inscrito_pg" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Aluno GV</td>
          <td bgcolor='#ffffff' class='textb'>Categoria</td>
          <td bgcolor='#ffffff' class='textb'>Prof Orientador</td>
          <td bgcolor='#ffffff' class='textb'>Resumo Parcial</td>
          <td bgcolor='#ffffff' class='textb'>Dt Ent Artigo</td>
          <td bgcolor='#ffffff' class='textb' colspan='2'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'agv_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'cat_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
            <?  
            if( $cara[ 'prf_nome_1' ] != '' )
            {
                print $cara[ 'prf_nome_1' ]; 
                if( $cara[ 'prf_nome_2' ] != '' )
                    print ", " . $cara[ 'prf_nome_2' ];
            }
            elseif( $cara[ 'prf_nome_2' ] != '' )
                $prof = $cara[ 'prf_nome_2' ];
            ?>
              </td>
              <td bgcolor='#ffffff' class='text'><?= ( ( $cara[ 'ipg_resumo' ] == 1 ) ? "Sim" : "Não" ) ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'evt_dt_ent_art' ] ?></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_inscrito_pg&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&agv_id=" . $cara[ 'agv_id' ] ?>'>Alterar</a>
              </td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_nota_pg&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&agv_id=" . $cara[ 'agv_id' ] ?>'>Notas</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há inscritos cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_inscrito_pg" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>

    <?
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_inscrito":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            igv_id          AS id,
            igv_convidado   AS convidado,
            agv_id          AS a_id,
            agv_nome        AS nome,
            agv_email       AS email,
            1               AS aluno_gv
        FROM
        ( 
            inscrito_gv
            NATURAL LEFT OUTER JOIN aluno_gv
        )
        WHERE
            evt_id   = '" . $dados[ 'evt_id' ] . "'
        UNION
        (
            SELECT
                ing_id          AS id,
                ing_convidado   AS convidado,
                ang_id          AS a_id,
                ang_nome        AS nome,  
                ang_email       AS email,
                0               AS aluno_gv
            FROM
            (
                inscrito_ngv
                NATURAL LEFT OUTER JOIN aluno_nao_gv
            )
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
        )
        ORDER BY
            nome,
            email";

    $rs = $sql->query( $query );
    $colspan = 6;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos/Convidados
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="inscrito" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Aluno GV</td>
          <td bgcolor='#ffffff' class='textb'>Nome</td>
          <td bgcolor='#ffffff' class='textb'>Email</td>
          <td bgcolor='#ffffff' class='textb'>Convidado</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        $tot_convidado = 0;
        $tot_inscrito  = 0;
        $tot_aluno_gv  = 0;
        $tot_aluno_nao_gv = 0;
        foreach( $rs as $cara )
        {
            if( $cara[ 'aluno_gv' ] == 1 )
            {
            ?>
                <tr>
                  <td bgcolor='#ffffff' class='text'>
                    <input type='checkbox' class="caixa" name='caras_agv_ids[]' value='<?= $cara[ 'id' ] ?>'>
                  </td>
                  <td bgcolor='#ffffff' class='text'>Sim</td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'nome' ] ?></td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'email' ] ?></td>
                  <td bgcolor='#ffffff' class='text'>
                <?
                if( $cara[ 'convidado' ] == 1 )
                {
                    $tot_convidado++;
                    print "Sim";
                }
                else
                {
                    $tot_inscrito++;
                    print "Não";
                }
                $tot_aluno_gv++;
                ?>
                  </td>
                  <td bgcolor='#ffffff' class='text'>
                    <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_inscrito&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&igv_id=" . $cara[ 'id' ] . "&agv_id=" . $cara[ 'a_id' ] ?>'>Alterar</a>
                  </td>
                </tr>
            <?
            }
            elseif( $cara[ 'aluno_gv' ] == 0 )
            {
            ?>
                <tr>
                  <td bgcolor='#ffffff' class='text'>
                    <input type='checkbox' clas="caixa" name='caras_ang_ids[]' value='<?= $cara[ 'a_id' ] ?>'>
                  </td>
                  <td bgcolor='#ffffff' class='text'>Não</td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'nome' ] ?></td>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'email' ] ?></td>
                  <td bgcolor='#ffffff' class='text'>
                <?
                if( $cara[ 'convidado' ] == 1 )
                {
                    $tot_convidado++;
                    print "Sim";
                }
                else
                {
                    $tot_inscrito++;
                    print "Não";
                }
                $tot_aluno_nao_gv++;
                ?>
                  </td>
                  <td bgcolor='#ffffff' class='text'>
                    <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_inscrito&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&ing_id=" . $cara[ 'id' ] . "&ang_id=" . $cara[ 'a_id' ] ?>'>Alterar</a>
                  </td>
                </tr>
            <?
            }
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
            </table>
          </td>
        </tr>
      </table>
      <br />
      <br />
    </center>

    <center>
      <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
        <tr>
          <td>
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos/Convidados - Totais
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Alunos GV</td>
              <td bgcolor='#ffffff' class="textb">Alunos Não GV</td>
              <td bgcolor='#ffffff' class="textb">( Alunos GV + Alunos Não GV )</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= $tot_aluno_gv  ?></td>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= $tot_aluno_nao_gv ?></td>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= ( $tot_aluno_gv + $tot_aluno_nao_gv ) ?></td>
            </tr>

            <tr>
              <td bgcolor='#ffffff' class='textb' colspan='3'>&nbsp;</td>
            </tr>

            <tr>
              <td bgcolor='#ffffff' class="textb">Inscritos</td>
              <td bgcolor='#ffffff' class="textb">Convidados</td>
              <td bgcolor='#ffffff' class="textb">( Inscritos + Convidados )</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= $tot_inscrito  ?></td>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= $tot_convidado ?></td>
              <td bgcolor='#ffffff' class="text">&nbsp;<?= ( $tot_inscrito + $tot_convidado ) ?></td>
            </tr>
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há inscritos/convidados cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_inscrito" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>



    <?
    unset( $i );
    unset( $rs );
    unset( $colspan );
    unset( $tot_despesa);
    unset( $tot_receita);
    break;
case "po_inscrito_superacao":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            eqp_id,
            eqp_nome,
            eqp_colocacao,
            agv_id,
            agv_nome,
            DATE_PART( 'epoch', evt_dt_fim ) AS evt_dt_fim
        FROM
            equipe
            NATURAL LEFT JOIN aluno_gv
            NATURAL LEFT JOIN evento
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
        ORDER BY
            eqp_nome,
            agv_nome";

    $rs = $sql->query( $query );
    $colspan = 6;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos - Equipes
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="inscrito_superacao" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Equipe</td>
          <td bgcolor='#ffffff' class='textb'>Líder</td>
          <td bgcolor='#ffffff' class='textb'>Integrantes</td>
          <?
          if( ! is_null( $rs[ 0 ][ 'evt_dt_fim' ] ) && $rs[ 0 ][ 'evt_dt_fim' ] <= time() )
          {
          ?>
              <td bgcolor='#ffffff' class='textb'>Colocação</td>
          <?
          }
          ?>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'eqp_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'eqp_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&evt_id=" . $dados[ 'evt_id' ] . "&subpagina=inserir_integrante&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&eqp_id=" . $cara[ 'eqp_id' ] ?>'>Integrantes</a></td>
              <?
              if( ! is_null( $cara[ 'evt_dt_fim' ] ) && $cara[ 'evt_dt_fim' ] <= time() )
              {
              ?>
                  <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'eqp_colocacao' ] ?></td>
              <?
              }
              ?>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_inscrito_superacao&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&eqp_id=" . $cara[ 'eqp_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr>  </form>
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há equipes cadastradas para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_inscrito_superacao" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>

    <?
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_item_final":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            ifi_id,
            ifi_nome,
            ifi_desc,
            ifi_arq_real,
            ifi_arq_falso
        FROM
            item_final
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            ifi_dt_inc DESC";
    
    $rs = $sql->query( $query );
    $colspan = 5;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Finalização
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="item_final" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'ifi_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'ifi_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'ifi_desc' ] ) < MAX_TEXTO ? $cara[ 'ifi_desc' ] : substr( $cara[ 'ifi_desc' ], 0, MAX_TEXTO ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'><a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $cara[ 'ifi_id' ] . "&tabela=item_final&col_id=ifi_id&arq_col_r=ifi_arq_real&arq_col_f=ifi_arq_falso" ?>'><?= $cara[ 'ifi_arq_falso' ] ?></a></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_item_final&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&ifi_id=" . $cara[ 'ifi_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há itens de finalização cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_item_final" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;
case "po_evt_arquivo":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $query = "
        SELECT
            ear_id,
            ear_nome,
            ear_desc,
            ear_arq_real,
            ear_arq_falso
        FROM
            evt_arquivo
        WHERE
            evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'
        ORDER BY
            ear_dt_inc DESC";
    
    $rs = $sql->query( $query );
    $colspan = 5;
?>
    <tr>
      <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Uploads Gerais
      </td>
    </tr>
<?
    if( is_array( $rs ) )
    {
?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>
            <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="acao"        value="apagar" />
              <input type="hidden" name="tipo"        value="evt_arquivo" />
          </td>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff' class='textb'>Funções</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='text'>
                <input type='checkbox' class="caixa" name='caras_ids[]' value='<?= $cara[ 'ear_id' ] ?>'>
              </td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'ear_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'><?= ( strlen( $cara[ 'ear_desc' ] ) < MAX_TEXTO ? $cara[ 'ear_desc' ] : substr( $cara[ 'ear_desc' ], 0, MAX_TEXTO ) . "..." ) ?></td>
              <td bgcolor='#ffffff' class='text'><a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $cara[ 'ear_id' ] . "&tabela=evt_arquivo&col_id=ear_id&arq_col_r=ear_arq_real&arq_col_f=ear_arq_falso" ?>'><?= $cara[ 'ear_arq_falso' ] ?></a></td>
              <td bgcolor='#ffffff' class='text'>
                <a href='<?=  $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir_evt_arquivo&alterar=yeah&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) . "&ear_id=" . $cara[ 'ear_id' ] ?>'>Alterar</a>
              </td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
            <input type="submit" name="ok" value="&nbsp;Apagar&nbsp;" />
            
          </td>
        </tr></form>  
    <? 
    }
    else
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>Não há uploads cadastrados para esse Evento</td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
          <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
          <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
          <input type="hidden" name="subpagina"   value="inserir_evt_arquivo" />
          <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
          <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
          <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
          <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
          <input type="submit" value=" Inserir Novo " />
        
      </td>
    </tr></form>
    <tr>
    <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    unset( $i );
    unset( $rs );
    unset( $cara );
    unset( $colspan );
    break;











/*
 *
 * FORMULARIOS
 *
 * INSERCAO / ALTERACAO
 * 
 */











case "inserir_integrante":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    /* superacao */
    /* Vars */
    extract_request_var( 'eqp_id',  $dados[ 'eqp_id' ] );
    extract_request_var( 'agv_id',  $dados[ 'agv_id' ] );
    extract_request_var( 'i_lider', $dados[ 'i_lider' ] );

    extract_request_var( 'aluno_busca_campo', $dados[ 'aluno_busca_campo' ] );
    extract_request_var( 'aluno_busca_texto', $dados[ 'aluno_busca_texto' ] );

    $colspan = '5';
    
    $js = "";
    if( $dados[ 'aluno_busca_campo' ] )
        $js = "document.f_ins.ok_aluno_busca.disabled = false;";

    $query = "
        SELECT
            eqp_nome,
            agv_id
        FROM
            equipe
            NATURAL JOIN aluno_gv
        WHERE
            eqp_id = '" . in_bd( $dados[ 'eqp_id' ] ) . "'";

    $rs = $sql->squery( $query );

    if( $rs )
        $lider = $rs[ 'agv_id' ];
    else
        $lider = 'NULL';

    $dados[ 'eqp_nome' ] = $rs[ 'eqp_nome' ]; 

    $query = "
        SELECT
            agv_id,
            agv_nome,
            agv_ddd,
            agv_ddi,
            agv_telefone,
            agv_email
        FROM
            eqp_agv
            NATURAL JOIN aluno_gv
        WHERE
            eqp_id = '" . in_bd( $dados[ 'eqp_id' ] ) . "'";

    $rs = $sql->query( $query );
    ?>
  <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" name='f_ins'>
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value='inserir_integrante' />
    <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
    <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
    <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
    <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
    <input type="hidden" name="eqp_id"      value="<?= $dados[ "eqp_id" ] ?>" />
    <input type="hidden" name="agv_id"      value="<?= $dados[ "agv_id" ] ?>" />
    <input type='hidden' name='acao'        value=''>
    <input type='hidden' name='tipo'        value='integrante' />
    <tr>
      <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
        <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Integrantes para Equipe <?= $dados[ 'eqp_nome' ] ?> 
        <script language="javascript">
        function mudar( obj )
        {
            if( obj.value.search( "serir" ) >= 0 )
                obj.form.acao.value = 'inserir';
            else
                obj.form.acao.value = 'apagar';

            obj.form.subpagina.value = 'inserir_integrante';
            obj.form.ok.disabled = true;
            obj.form.submit( );
        }

        function aluno_busca( obj )
        {
            obj.form.acao.value = ''
            obj.form.subpagina.value = 'aluno_busca';
            obj.form.submit( );
        }
        </script>
      </td>
    </tr>

    <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
    <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                </font></td></tr>
    <? }
    if( is_array( $rs ) )
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Excluir</td>
          <td bgcolor='#ffffff' class='textb'>Nome</td>
          <td bgcolor='#ffffff' class='textb'>Email</td>
          <td bgcolor='#ffffff' class='textb'>Telefone</td>
          <td bgcolor='#ffffff' class='textb'>Líder</td>
        </tr>
        <?
        foreach( $rs as $cara )
        {
            if( $cara[ 'agv_id' ] != $lider )
            {
                $chk = "<input type='checkbox' class='caixa' name='caras_ids[]' value='" . $cara[ 'agv_id' ] . "' />";
                $lid = "Não";
            }
            else
            {
                $chk = "&nbsp;";
                $lid = "Sim";
            }
            ?>
            <tr>
              <td bgcolor="#ffffff" class="text"><?= $chk ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_nome' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_email' ] ?></td>
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_telefone' ] ?></td>
              bosta
              <td bgcolor='#ffffff' class='text'>&nbsp;<?= $lid ?></td>
            </tr>
        <?
        }
    }
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Aluno
            <input type="hidden" name="pagina_retorno"  value='inserir_integrante' />
        <?
        if( consis_inteiro( $dados[ 'agv_id' ] ) )
        {
            $rs = $sql->squery( "
                SELECT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'" );

            if( $rs )
            {
            ?>
                <br /> Atual: <?= ucwords( $rs[ 'agv_nome' ] ) ?> <br />
            <?
                unset( $rs ); 
            }
        }
        ?>
        </td>
        <td bgcolor='#ffffff' class='textb' colspan='<?= $colspan - 2 ?>'>
            <input type='radio' name='aluno_busca_campo' value='agv_matricula'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_matricula' ) print " checked"; ?> OnClick='this.form.ok_aluno_busca.disabled = ! this.checked;' /> Matrícula<br />
            <input type='radio' name='aluno_busca_campo' value='agv_nome'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_nome' ) print " checked"; ?> OnClick='this.form.ok_aluno_busca.disabled = ! this.checked;' /> Nome <br />
            <input type='text' name='aluno_busca_texto' value='<? if( $dados[ 'aluno_busca_texto' ] ) print $dados[ 'aluno_busca_texto' ]; ?>' size='30' /><br />
            <input type='submit' name='ok_aluno_busca' value=' Procurar ' OnClick='aluno_busca( this );' disabled>
            <script language='javascript'>
                <?= $js ?>
            </script>
        </td>
        <td bgcolor='#ffffff' class='textb'><input type='checkbox' name='i_lider' value='1' /> Líder</td>
    </tr>
    <tr>
      <td bgcolor='#ffffff' colspan="<?= $colspan ?>" align='center'>
        <input type='button' name="ok" value=' Inserir ' OnClick="mudar( this );">
        <input type='button' name="ok" value=' Apagar '  OnClick="mudar( this );"/>
      </td>
    </tr>
    </form>
    <tr>
      <td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td>
    </tr>
<?
    break;
case "inserir_nota_pg":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( 'agv_id',      $dados[ 'agv_id' ] );
    extract_request_var( 'cri_id_1',    $dados[ 'cri_id_1' ] );
    extract_request_var( 'cri_id_2',    $dados[ 'cri_id_2' ] );

    extract_request_var( 'ipg_peso_1',    $dados[ 'ipg_peso_1' ] );
    extract_request_var( 'ipg_peso_2',    $dados[ 'ipg_peso_2' ] );
    extract_request_var( 'ipg_nota_1',    $dados[ 'ipg_nota_1' ] );
    extract_request_var( 'ipg_nota_2',    $dados[ 'ipg_nota_2' ] );

    if( consis_inteiro( $dados[ 'agv_id' ] ) )
    {
        $query = "
            SELECT
                p1.prf_nome AS prf_nome_1,
                p2.prf_nome AS prf_nome_2,
                cri_id_1,
                cri_id_2,
                ipg_peso_1,
                ipg_peso_2,
                ipg_nota_1,
                ipg_nota_2
            FROM
                inscrito_pg,
                professor p1,
                professor p2
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'
                AND prf_id_1 = p1.prf_id
                AND prf_id_2 = p2.prf_id";

        $rs = $sql->squery( $query );

        if( is_array( $rs ) )
        {
            $dados[ 'prf_nome_1' ]  = $rs[ 'prf_nome_1' ];
            $dados[ 'prf_nome_2' ]  = $rs[ 'prf_nome_2' ];
            
            if( ! consis_inteiro( $dados[ 'cri_id_1' ] ) )
            {
                $dados[ 'cri_id_1' ]    = $rs[ 'cri_id_1' ];
                $dados[ 'ipg_peso_1' ]  = $rs[ 'ipg_peso_1' ];
            }

            if( ! consis_inteiro( $dados[ 'cri_id_2' ] ) )
            {
                $dados[ 'cri_id_2' ]    = $rs[ 'cri_id_2' ];
                $dados[ 'ipg_peso_2' ]  = $rs[ 'ipg_peso_2' ];
            }

            $dados[ 'ipg_nota_1' ]  = $rs[ 'ipg_nota_1' ];
            $dados[ 'ipg_nota_2' ]  = $rs[ 'ipg_nota_2' ];

            if( ! consis_inteiro( $dados[ 'ipg_peso_1' ] ) || $dados[ 'ipg_peso_1' ] == 0 )
            {
                $rs = $sql->squery( "
                    SELECT
                        cri_peso
                    FROM
                        criterio
                    WHERE
                        cri_id = '" . in_bd( $dados[ 'cri_id_1' ] ) . "'" );

                $dados[ 'ipg_peso_1' ] = $rs[ 'cri_peso' ];
            }

            if( ! consis_inteiro( $dados[ 'ipg_peso_2' ] ) || $dados[ 'ipg_peso_2' ] == 0 )
            {
                $rs = $sql->squery( "
                    SELECT
                        cri_peso
                    FROM
                        criterio
                    WHERE
                        cri_id = '" . in_bd( $dados[ 'cri_id_2' ] ) . "'" );

                $dados[ 'ipg_peso_2' ] = $rs[ 'cri_peso' ];
            }
        }
    }

    $colspan = '5'
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="agv_id"      value="<?= $dados[ "agv_id" ] ?>" />
        <input type="hidden" name="cri_id_1"    value="<?= $dados[ 'ipg_peso_1' ] ?>" />
        <input type="hidden" name="cri_id_2"    value="<?= $dados[ 'ipg_peso_2' ] ?>" />
        <input type="hidden" name="ipg_peso_1"  value="<?= $dados[ 'ipg_peso_1' ] ?>" />
        <input type="hidden" name="ipg_peso_2"  value="<?= $dados[ 'ipg_peso_2' ] ?>" />
        <input type="hidden" name="ipg_nota_1"  value="<?= $dados[ 'ipg_nota_1' ] ?>" />
        <input type="hidden" name="ipg_nota_2"  value="<?= $dados[ 'ipg_nota_2' ] ?>" />
        <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
        <input type='hidden' name='acao'        value='alterar' />
        <input type='hidden' name='tipo'        value='nota_pg' />
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Notas
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Professor</td>
          <td bgcolor='#ffffff' class='textb'>Critério</td>

        <?
        if( $dados[ 'cri_id_1' ] && $dados[ 'cri_id_2' ] )
        {
        ?>
          <td bgcolor='#ffffff' class='textb'>Peso</td>
          <td bgcolor='#ffffff' class='textb'>Nota</td>
        <?
        }
        ?>
        </tr>
        <tr>
        <script language='JavaScript'>
        function muda_criterio( c )
        {
           c.form.subpagina.value = 'inserir_nota_pg'; 
           //c.form.ipg_peso_1.value = '0';
           //c.form.ipg_peso_2.value = '0';
           c.form.acao.value = '';
           /*
           b='';
           for( i=0; i < c.form.elements.length; i++ )
             b+='\n' + c.form.elements[ i ].name + ' => ' + c.form.elements[ i ].value;
            */

           //alert( b );
           //TODO
           c.form.submit();
        }
        </script>
          <td bgcolor='#ffffff' class='textb'>&nbsp;<?= $dados[ 'prf_nome_1' ] ?></td>
          <td bgcolor='#ffffff' class='text'>&nbsp;<?= gera_select_g( $sql, "cri_id", "cri_nome", "criterio", $dados[ "cri_id_1" ], array( "name" => "cri_id_1", "OnChange" => "muda_criterio( this );" ) ) ?></td>
          <?
        if( $dados[ 'cri_id_1' ] )
        {
        ?>
          <td bgcolor='#ffffff' class='text'><input type='text' name='ipg_peso_1' value='<?= in_html( $dados[ 'ipg_peso_1' ] ) ?>' size='3' /></td>
          <td bgcolor='#ffffff' class='text'><input type='text' name='ipg_nota_1' value='<?= in_html( $dados[ 'ipg_nota_1' ] ) ?>' size='3' /></td>
        <?
        }
        ?>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>&nbsp;<?= $dados[ 'prf_nome_2' ] ?></td>
          <td bgcolor='#ffffff' class='text'>&nbsp;<?= gera_select_g( $sql, "cri_id", "cri_nome", "criterio", $dados[ "cri_id_2" ], array( "name" => "cri_id_2", "OnChange" => "muda_criterio( this );" ) ) ?></td>
        <?
        if( $dados[ 'cri_id_2' ] )
        {
        ?>
          <td bgcolor='#ffffff' class='text'><input type='text' name='ipg_peso_2' value='<?= in_html( $dados[ 'ipg_peso_2' ] ) ?>' size='3' /></td>
          <td bgcolor='#ffffff' class='text'><input type='text' name='ipg_nota_2' value='<?= in_html( $dados[ 'ipg_nota_2' ] ) ?>' size='3' /></td>
        <?
        }
        ?>
        </tr>

        <?
        if( consis_inteiro( $dados[ 'cri_id_1' ] ) && consis_inteiro( $dados[ 'cri_id_2' ] ) )
        {
        ?>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;Alterar&nbsp;" />
                <input type='button' value='Cancelar / Voltar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito_pg&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . $dados[ "evt_edicao" ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
              </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type='button' value='Cancelar / Voltar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito_pg&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . $dados[ "evt_edicao" ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
              </td>
            </tr>
        <?
        }
        ?>

        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>

        <?
        if( is_numeric( $dados[ 'ipg_nota_1' ] ) && is_numeric( $dados[ 'ipg_nota_2' ] ) )
        {
        ?>
            </table>
          </td>
        </tr>
      </table>

      <br />

      <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
        <tr>
          <td>
            <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" bgcolor="#336699" height="17" colspan="<?= $colspan ?>">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Totais
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb" align='center'>
                Média: <?= formata_dinheiro( ( ( $dados[ 'ipg_nota_1' ] * $dados[ 'ipg_peso_1' ] ) + ( $dados[ 'ipg_nota_2' ] * $dados[ 'ipg_peso_2' ] ) ) / ( $dados[ 'ipg_peso_1' ] + $dados[ 'ipg_peso_2' ] ) ) ?>
              </td>
            </tr>
        <?
        }
        ?>
    <?
    break;
case "inserir_inscrito_superacao":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    /* Aluno GV */
    extract_request_var( "eqp_id",      $dados[ 'eqp_id' ] );
    extract_request_var( "agv_id",      $dados[ 'agv_id' ] );
    extract_request_var( "eqp_nome",    $dados[ 'eqp_nome' ] );
    extract_request_var( "eqp_colocacao",    $dados[ 'eqp_colocacao' ] );

    extract_request_var( "alterar",     $alterar );

    $rs = $sql->squery( "
        SELECT
            DATE_PART( 'epoch', evt_dt_fim ) AS evt_dt_fim
        FROM
            evento
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'" );

    $dados[ 'evt_dt_fim' ] = $rs[ 'evt_dt_fim' ];

    $js = "";
    if( $alterar == "yeah" && consis_inteiro( $dados[ 'eqp_id' ] ) )
    {
        $query = "
            SELECT
                agv_nome,
                eqp_nome,
                eqp_colocacao
            FROM
                equipe
                NATURAL LEFT OUTER JOIN aluno_gv
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND eqp_id = '" . in_bd( $dados[ 'eqp_id' ] ) . "'";

        $rs = $sql->squery( $query );

        if( $rs )
        {
            $dados[ 'agv_nome' ] = $rs[ 'agv_nome' ];
            $dados[ 'eqp_nome' ] = $rs[ 'eqp_nome' ];
            $dados[ 'eqp_colocacao' ] = $rs[ 'eqp_colocacao' ];
        }
    }    
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" name='f_ins'>
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="agv_id"      value="<?=  $dados[ "agv_id" ] ?>" />
        <input type="hidden" name="eqp_id"      value="<?=  $dados[ "eqp_id" ] ?>" />
        <input type="hidden" name="eqp_colocacao"    value="<?= $dados[ "eqp_colocacao" ] ?>" />
        <input type="hidden" name="alterar"     value="<?= $alterar ?>" />
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos - Equipes
          </td>
        </tr>
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
    <?
    /* Se nao for alterar pode fazer a busca por aluno da GV pra preencher os campos */
    if( $alterar != "yeah" )
    {
        extract_request_var( 'aluno_busca_campo', $dados[ 'aluno_busca_campo' ] );
        extract_request_var( 'aluno_busca_texto', $dados[ 'aluno_busca_texto' ] );
        
        if( $dados[ 'aluno_busca_campo' ] )
            $js = "document.f_ins.ok.disabled = false;";
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Líder
        <input type="hidden" name="subpagina" value='aluno_busca' />
        <input type="hidden" name="pagina_retorno"  value='inserir_inscrito_superacao' />
        <?
        if( consis_inteiro( $dados[ 'agv_id' ] ) )
        {
            $rs = $sql->squery( "
                SELECT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'" );

            if( $rs )
            {
            ?>
                <br /> Atual: <?= ucwords( $rs[ 'agv_nome' ] ) ?> <br />
            <?
                unset( $rs ); 
            }
        }
        ?>
            </td>
            <td bgcolor='#ffffff' class='textb'>
            <input type='radio' name='aluno_busca_campo' value='agv_matricula'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_matricula' ) print " checked"; ?> OnClick='this.form.ok.disabled = ! this.checked;' /> Matrícula<br />
            <input type='radio' name='aluno_busca_campo' value='agv_nome'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_nome' ) print " checked"; ?> OnClick='this.form.ok.disabled = ! this.checked;' /> Nome <br />
            <input type='text' name='aluno_busca_texto' value='<? if( $dados[ 'aluno_busca_texto' ] ) print $dados[ 'aluno_busca_texto' ]; ?>' size='30' /><br />
            <input type='submit' name='ok' value=' Procurar ' disabled>
            <script language='javascript'>
            <?= $js ?>
            </script>
            </form>
            <form name='f_ins' method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="agv_id"      value="<?= $dados[ "agv_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="subpagina"   value='po_inscrito_superacao' />
              <input type='hidden' name='acao'        value='inserir' />
              <input type='hidden' name='tipo'        value='inscrito_superacao' />
          </td>
        </tr>
    <?
    }
    else
    {
        ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Líder
                <input type='hidden' name='acao'  value='alterar' />
                <input type='hidden' name='agv_id'      value='<?= $dados[ 'agv_id' ] ?>' />
                <input type="hidden" name="subpagina"   value='po_inscrito_superacao' />
                <input type="hidden" name="tipo"        value="inscrito_superacao" />
              </td>
              <td bgcolor='#ffffff'>&nbsp;<?= $rs[ 'agv_nome' ] ?></td>
            </tr>
        <?
    }
    ?>

        <tr>
          <td bgcolor='#ffffff' class='textb'>Equipe</td>
          <td bgcolor='#ffffff' class='text'><input type='text' name='eqp_nome' value='<?= $dados[ 'eqp_nome' ] ?>' size='30' /></td>
        </tr>
    <?
    if( ! is_null( $dados[ 'evt_dt_fim' ] ) && $dados[ 'evt_dt_fim' ] <= time() )
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Colocação</td>
          <td bgcolor='#ffffff' class='text'><input type='text' name='eqp_colocacao' value='<?= $dados[ 'eqp_colocacao' ] ?>' size='3' /></td>
        </tr>
    <?
    }
    ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito_superacao&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
    <?
    break;
case "inserir_inscrito_pg":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    /* Aluno GV */
    extract_request_var( "agv_id",      $dados[ 'agv_id' ] );
    extract_request_var( "agv_id_old",  $dados[ 'agv_id_old' ] );

    /* Campos Form */
    extract_request_var( "cat_id",      $dados[ 'cat_id' ] );
    extract_request_var( "prf_id_1",    $dados[ 'prf_id_1' ] );
    extract_request_var( "prf_id_2",    $dados[ 'prf_id_2' ] );
    extract_request_var( "ipg_resumo",  $dados[ 'ipg_resumo' ] );

    extract_request_var( "alterar",     $alterar );

    $js = "";
    if( $alterar == "yeah" && consis_inteiro( $dados[ 'agv_id' ] ) )
    {
        $query = "
            SELECT
                agv_nome,
                cat_id,
                prf_id_1,
                prf_id_2,
                ipg_resumo
            FROM
                inscrito_pg
                NATURAL JOIN aluno_gv
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND agv_id = '" . ( consis_inteiro( $dados[ 'agv_id_old' ] ) ? in_bd( $dados[ 'agv_id_old' ] ) : in_bd( $dados[ "agv_id" ] ) ) . "'";

        $rs = $sql->squery( $query );

        if( $rs )
        {
            if( ! consis_inteiro( $dados[ 'cat_id' ] ) )
                $dados[ 'cat_id' ]      = $rs[ 'cat_id' ];

            $dados[ 'prf_id_1' ]    = $rs[ 'prf_id_1' ];
            $dados[ 'prf_id_2' ]    = $rs[ 'prf_id_2' ];
            $dados[ 'ipg_resumo' ]  = $rs[ 'ipg_resumo' ];
        }
    }    
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" name='f_ins'>
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="agv_id_old"  value="<?= ( ( $dados[ "agv_id_old" ] != "" ) ? $dados[ "agv_id_old" ] : $dados[ "agv_id" ] ) ?>" />
        <input type="hidden" name="agv_id"      value="<?=  $dados[ "agv_id" ] ?>" />
        <input type="hidden" name="cat_id"      value="<?= $dados[ "cat_id" ] ?>" />
        <input type="hidden" name="prf_id_1"    value="<?= $dados[ "prf_id_1" ] ?>" />
        <input type="hidden" name="prf_id_2"    value="<?= $dados[ "prf_id_2" ] ?>" />
        <input type="hidden" name="alterar"     value="<?= $alterar ?>" />
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos
          </td>
        </tr>
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
    <?
    /* Se nao for alterar pode fazer a busca por aluno da GV pra preencher os campos */
    if( $alterar != "yeah" )
    {
        extract_request_var( 'aluno_busca_campo', $dados[ 'aluno_busca_campo' ] );
        extract_request_var( 'aluno_busca_texto', $dados[ 'aluno_busca_texto' ] );
        
        if( $dados[ 'aluno_busca_campo' ] )
            $js = "document.f_ins.ok.disabled = false;";
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Aluno
        <input type="hidden" name="subpagina"   value='aluno_busca' />
        <input type="hidden" name="pagina_retorno"  value='inserir_inscrito_pg' />
        <?
        if( consis_inteiro( $dados[ 'agv_id' ] ) )
        {
            $rs = $sql->squery( "
                SELECT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'" );

            if( $rs )
            {
            ?>
                <br /> Atual: <?= ucwords( $rs[ 'agv_nome' ] ) ?> <br />
            <?
                unset( $rs ); 
            }
        }
        ?>
            </td>
            <td bgcolor='#ffffff' class='textb'>
            <input type='radio' name='aluno_busca_campo' value='agv_matricula'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_matricula' ) print " checked"; ?> OnClick='this.form.ok.disabled = ! this.checked;' /> Matrícula<br />
            <input type='radio' name='aluno_busca_campo' value='agv_nome'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_nome' ) print " checked"; ?> OnClick='this.form.ok.disabled = ! this.checked;' /> Nome <br />
            <input type='text' name='aluno_busca_texto' value='<? if( $dados[ 'aluno_busca_texto' ] ) print $dados[ 'aluno_busca_texto' ]; ?>' size='30' /><br />
            <input type='submit' name='ok' value=' Procurar ' disabled>
            <script language='javascript'>
            <?= $js ?>
            </script>
            </form>
            <form name='f_ins' method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="agv_id"      value="<?= $dados[ "agv_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type="hidden" name="subpagina"   value='po_inscrito_pg' />
              <input type='hidden' name='acao'        value='inserir' />
              <input type='hidden' name='tipo'        value='inscrito_pg' />
          </td>
        </tr>
    <?
    }
    else
    {
        if( consis_inteiro( $dados[ 'agv_id' ] ) )
        {
            $rs = $sql->squery( "
                SELECT
                    agv_nome
                FROM
                    aluno_gv
                WHERE
                    agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'" );
            
            if( $rs )
            {
            ?>
                <tr>
                  <td bgcolor='#ffffff' class='textb'>Aluno</td>
                  <td bgcolor='#ffffff'>&nbsp;<?= $rs[ 'agv_nome' ] ?></td>
                </tr>
            <?
            }
        }
    ?>
        <input type='hidden' name='agv_id'      value='<?= $dados[ 'agv_id' ] ?>' />
        <input type="hidden" name="subpagina"   value='po_inscrito_pg' />
        <input type="hidden" name="tipo"        value="inscrito_pg" />
        <input type='hidden' name='acao'        value='alterar' />
    <?
    }
    ?>
        <script language='JavaScript'>
        function muda_categoria( c )
        {
           c.form.subpagina.value = 'inserir_inscrito_pg'; 
           c.form.acao.value = '';
           c.form.submit();
        }
        </script>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Categorias</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "cat_id", "cat_nome", "categoria", $dados[ "cat_id" ], array( "name" => "cat_id", "OnChange" => "muda_categoria( this );" ) ) ?></td>
        </tr>
    <?
    if( consis_inteiro( $dados[ 'cat_id' ] ) )
    {
        $rs = $sql->query( "
            SELECT
                prf_id,
                prf_nome
            FROM
                evt_prf
                NATURAL JOIN professor
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND cat_id = '" . in_bd( $dados[ 'cat_id' ] ) . "'" );

        function gera_select_prf( $rs, $selecionado, $param="" )
        {
            print "<select " . $param . ">";
            print "<option value=''>---</option>";
            
            if( is_array( $rs ) )
                foreach( $rs as $p )
                    print "<option value='" . $p[ 'prf_id' ] . "'" . ( ( $p[ 'prf_id' ] == $selecionado ) ? " selected" : "" ) . ">" . $p[ 'prf_nome' ] . "</option>";
        }
        ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Professor 1</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_prf( $rs, $dados[ "prf_id_1" ], "name = 'prf_id_1' OnChange='if( this.value && this.value == this.form.prf_id_2.value ) alert( \"Professor 1 e Professor 2 tem de ser diferentes\");'" ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Professor 2</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_prf( $rs, $dados[ "prf_id_2" ], "name = 'prf_id_2' OnChange='if( this.value && this.value == this.form.prf_id_1.value ) alert( \"Professor 1 e Professor 2 tem de ser diferentes\");'" ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb' colspan='2'><input type='checkbox' class="caixa" name='ipg_resumo' value='1'<? if( $dados[ 'ipg_resumo' ] == 1 ) print ' checked'; ?> /> Resumo Parcial</td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito_pg&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
    <?
    }
    else
    {
    ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito_pg&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
    <?
    }
    ?>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_inscrito":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    /* Aluno GV */
    extract_request_var( "agv_id",          $dados[ 'agv_id' ] );

    /* Aluno Nao GV */
    extract_request_var( "ang_id",      $dados[ 'ang_id' ] );

    /* Campos Form */
    extract_request_var( "i_aluno_gv",  $dados[ 'i_aluno_gv' ] );
    extract_request_var( "i_nome",      $dados[ 'i_nome' ] );
    extract_request_var( "i_endereco",  $dados[ 'i_endereco' ] );
    extract_request_var( "i_bairro",    $dados[ 'i_bairro' ] );
    extract_request_var( "i_ddd",  $dados[ 'i_ddd' ] );
    extract_request_var( "i_ddi",  $dados[ 'i_ddi' ] );
    extract_request_var( "i_telefone",  $dados[ 'i_telefone' ] );
    extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
    extract_request_var( "i_dt_nasci",  $dados[ 'i_dt_nasci' ] );
    extract_request_var( "i_cep",       $dados[ 'i_cep' ] );
    extract_request_var( "i_email",     $dados[ 'i_email' ] );
    extract_request_var( "i_convidado", $dados[ 'i_convidado' ] );
    extract_request_var( "tcv_id",      $dados[ 'tcv_id' ] );
    extract_request_var( "i_curso",     $dados[ 'i_curso' ] );
    extract_request_var( "i_faculdade", $dados[ 'i_faculdade' ] );

    extract_request_var( "alterar",     $alterar );

    $js  = "";
    $js2 = "";
    if( $alterar == "yeah" || $dados[ 'i_aluno_gv' ] == 1 )
    {
        extract_request_var( "aluno_busca_campo", $dados[ 'aluno_busca_campo' ] );
        extract_request_var( "aluno_busca_texto", $dados[ 'aluno_busca_texto' ] );

        if( consis_inteiro( $dados[ 'agv_id' ] ) )
        {
            $js = "document.f_ins.i_aluno_gv.checked = true; document.f_ins.ok.disabled = false;";
            $dados[ 'i_aluno_gv' ] == 1;

            $query = "
                SELECT
                    agv_nome,
                    agv_matricula,
                    SUBSTR( agv_matricula, 1, 2 ) AS agv_curso,
                    agv_endereco,
                    agv_bairro,
                    agv_ddd,
                    agv_ddi,
                    agv_telefone,
                    agv_cep,
                    DATE_PART( 'epoch', agv_dt_nasci ) AS agv_dt_nasci,
                    agv_cep,
                    agv_email,
                    tcv_id,
                    igv_convidado
                FROM
                    aluno_gv
                    NATURAL LEFT OUTER JOIN inscrito_gv
                WHERE
                    agv_id = '" . in_bd( $dados[ 'agv_id' ] ) . "'
                    AND ( evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "' OR evt_id = NULL )";

            $rs = $sql->squery( $query );

            if( $rs )
            {
                $dados[ 'i_nome' ]      = $rs[ 'agv_nome' ];
                $dados[ 'i_matricula' ] = $rs[ 'agv_matricula' ];
                $dados[ 'i_curso' ]     = $rs[ 'agv_curso' ];
                $dados[ 'i_endereco' ]  = $rs[ 'agv_endereco' ];
                $dados[ 'i_bairro' ]    = $rs[ 'agv_bairro' ];
                $dados[ 'i_ddd' ]  = $rs[ 'agv_ddd' ];
                $dados[ 'i_ddi' ]  = $rs[ 'agv_ddi' ];
                $dados[ 'i_telefone' ]  = $rs[ 'agv_telefone' ];
                $dados[ 'i_cep' ]       = $rs[ 'agv_cep' ];
                $dados[ 'i_dt_nasci' ]  = array( "dia" => date('d', $rs[ 'agv_dt_nasci' ] ),
                                                 "mes" => date('m', $rs[ 'agv_dt_nasci' ] ),
                                                 "ano" => date('Y', $rs[ 'agv_dt_nasci' ] ) );
                $dados[ 'i_cep' ]       = $rs[ 'agv_cep' ];
                $dados[ 'i_email' ]     = $rs[ 'agv_email' ];

                $dados[ 'i_convidado' ] = $rs[ 'igv_convidado' ];
                $dados[ 'tcv_id' ]      = $rs[ 'tcv_id' ];
            }
        }    
        elseif( consis_inteiro( $dados[ 'ang_id' ] ) )
        {
            $query = "
                SELECT
                    ang_nome,
                    ang_curso,
                    ang_faculdade,
                    ang_endereco,
                    ang_bairro,
                    ang_ddd,
                    ang_ddi,
                    ang_telefone,
                    ang_cep,
                    DATE_PART( 'epoch', ang_dt_nasci ) AS ang_dt_nasci,
                    ang_cep,
                    ang_email,
                    ing_convidado,
                    tcv_id
                FROM
                    aluno_nao_gv
                    NATURAL LEFT OUTER JOIN inscrito_ngv
                WHERE
                    ang_id = '" . in_bd( $dados[ 'ang_id' ] ) . "'
                    AND evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'";

            $rs = $sql->squery( $query );

            if( is_array( $rs ) )
            {
                $dados[ 'i_nome' ]      = $rs[ 'ang_nome' ];
                $dados[ 'i_curso' ]     = $rs[ 'ang_curso' ];
                $dados[ 'i_faculdade' ] = $rs[ 'ang_faculdade' ];
                $dados[ 'i_endereco' ]  = $rs[ 'ang_endereco' ];
                $dados[ 'i_bairro' ]    = $rs[ 'ang_bairro' ];
                $dados[ 'i_ddd' ]  = $rs[ 'ang_ddd' ];
                $dados[ 'i_ddi' ]  = $rs[ 'ang_ddi' ];
                $dados[ 'i_telefone' ]  = $rs[ 'ang_telefone' ];
                $dados[ 'i_cep' ]       = $rs[ 'ang_cep' ];
                $dados[ 'i_dt_nasci' ]  = array( "dia" => date('d', $rs[ 'ang_dt_nasci' ] ),
                                                 "mes" => date('m', $rs[ 'ang_dt_nasci' ] ),
                                                 "ano" => date('Y', $rs[ 'ang_dt_nasci' ] ) );
                $dados[ 'i_cep' ]       = $rs[ 'ang_cep' ];
                $dados[ 'i_email' ]     = $rs[ 'ang_email' ];

                $dados[ 'i_convidado' ] = $rs[ 'ing_convidado' ];
                $dados[ 'tcv_id' ]      = $rs[ 'tcv_id' ];
            }
        }    
    }

    if( $dados[ 'i_convidado' ] )
        $js2 = "document.f_ins.tcv_id.disabled = false";

    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" name='f_ins'>
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Inscritos/Convidados
          </td>
        </tr>
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
    <?
    /* Se nao for alterar pode fazer a busca por aluno da GV pra preencher os campos */
    if( $alterar != "yeah" )
    {
        extract_request_var( 'aluno_busca_campo', $dados[ 'aluno_busca_campo' ] );
        extract_request_var( 'aluno_busca_texto', $dados[ 'aluno_busca_texto' ] );
    ?>
        <input type="hidden" name="subpagina"   value='aluno_busca' />
        <input type="hidden" name="pagina_retorno"  value='inserir_inscrito' />

        <tr>
          <td bgcolor='#ffffff' class='textb'><input type="checkbox" class="caixa" value='1' name="i_aluno_gv"<? if( $dados[ 'i_aluno_gv' ] == 1 ) print " checked"; ?> OnChange='var a = ! this.checked; this.form.aluno_busca_campo[ 0 ].disabled = a; this.form.aluno_busca_campo[1].disabled = a; this.form.aluno_busca_texto.disabled = a; this.form.ok.disabled = a;' /> Aluno GV</td>
          <td bgcolor='#ffffff' class='textb'>
            Busca:<br />
            <input type='radio' name='aluno_busca_campo' value='agv_matricula'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_matricula' ) print " checked"; ?> disabled OnClick='this.form.ok.disabled = ! this.checked;' /> Matrícula<br />
            <input type='radio' name='aluno_busca_campo' value='agv_nome'<? if( $dados[ 'aluno_busca_campo' ] == 'agv_nome' ) print " checked"; ?> disabled  OnClick='this.form.ok.disabled = ! this.checked;' /> Nome <br />
            <input type='text' name='aluno_busca_texto' value='<? if( $dados[ 'aluno_busca_texto' ] ) print $dados[ 'aluno_busca_texto' ]; ?>' size='30' disabled /><br />
            <input type='submit' name='ok' value=' Procurar ' disabled>
            <script language='JavaScript'>
            <?= $js ?>
            document.f_ins.aluno_busca_campo.disabled = ! document.f_ins.i_aluno_gv.checked; 
            document.f_ins.aluno_busca_texto.disabled = ! document.f_ins.i_aluno_gv.checked; 

            if( document.f_ins.aluno_busca_campo[ 0 ].checked == true || document.f_ins.aluno_busca_campo[1].checked == true )
                document.f_ins.ok.disabled = false;
            </script>
            </form>
            <form name='f_ins' method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
              <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
              <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
              <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
              <input type="hidden" name="agv_id"      value="<?= $dados[ "agv_id" ] ?>" />
              <input type="hidden" name="ang_id"      value="<?= $dados[ "ang_id" ] ?>" />
              <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
              <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
              <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
              <input type='hidden' name='tipo'        value='inscrito' />
              <input type="hidden" name="subpagina"   value='po_inscrito' />
              <input type='hidden' name='acao'        value='inserir' />
          </td>
        </tr>
    <?
    }
    else
    {
        extract_request_var( 'agv_id', $dados[ 'agv_id' ] );
        extract_request_var( 'igv_id', $dados[ 'igv_id' ] );
        extract_request_var( 'ang_id', $dados[ 'ang_id' ] );
        extract_request_var( 'ing_id', $dados[ 'ing_id' ] );
    ?>
        <input type='hidden' name='agv_id'      value='<?= $dados[ 'agv_id' ] ?>' />
        <input type='hidden' name='igv_id'      value='<?= $dados[ 'igv_id' ] ?>' />
        <input type='hidden' name='ang_id'      value='<?= $dados[ 'ang_id' ] ?>' />
        <input type='hidden' name='ing_id'      value='<?= $dados[ 'ing_id' ] ?>' />
        <input type="hidden" name="subpagina"   value='po_inscrito' />
        <input type="hidden" name="tipo"        value="inscrito" />
        <input type='hidden' name='acao'        value='alterar' />
        <tr>
          <td bgcolor='#ffffff' class='textb'>Aluno GV</td>
          <td bgcolor='#ffffff'><?= ( consis_inteiro( $dados[ 'agv_id' ] ) ? "Sim" : "Não" ) ?></td>
        </tr>
    <?
    }
    ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Nome</td>
          <td bgcolor='#ffffff'><input type="text" name="i_nome" value="<?= in_html( $dados[ "i_nome" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Endereço</td>
          <td bgcolor='#ffffff'><input type="text" name="i_endereco" value="<?= in_html( $dados[ "i_endereco" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Bairro</td>
          <td bgcolor='#ffffff'><input type="text" name="i_bairro" value="<?= in_html( $dados[ "i_bairro" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Telefone</td>
          <td bgcolor='#ffffff'>
            <input type="text" name="i_ddi" value="<?= in_html($dados["i_ddi"]) ?>" size="2">
            <input type="text" name="i_ddd" value="<?= in_html($dados["i_ddd"]) ?>" size="3">
            <input type="text" name="i_telefone" value="<?= in_html($dados["i_telefone"]) ?>" size="9"> ([ DDI 99 ] [ DDD 999 ] 9999-9999)</td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>CEP</td>
          <td bgcolor='#ffffff'><input type="text" name="i_cep" value="<?= in_html( $dados[ "i_cep" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Faculdade</td>
          <td bgcolor='#ffffff'><input type="text" name="i_faculdade" value="<?= in_html( $dados[ "i_faculdade" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Data de Nascimento</td>
          <td bgcolor='#ffffff'>&nbsp;<? gera_select_data( "i_dt_nasci", $dados[ "i_dt_nasci" ], 1950, date( "Y" ) - 2 ); ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Email</td>
          <td bgcolor='#ffffff'><input type="text" name="i_email" value="<?= in_html( $dados[ "i_email" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Curso</td>
          <td bgcolor='#ffffff'><input type="text" name="i_curso" value="<?= in_html( $dados[ "i_curso" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'><input type='checkbox' class="caixa" name='i_convidado' value='1' OnChange='this.form.tcv_id.disabled = ! this.checked'<? if( $dados[ 'i_convidado' ] == 1 ) print ' checked'; ?> /> Convidado</td>
          <td bgcolor='#ffffff' class='textb'>Tipo de Convidado: <?= gera_select_g( $sql, "tcv_id", "tcv_nome", "tipo_convidado", $dados[ "tcv_id" ], array( "name" => "tcv_id", "disabled" => "" ) ) ?></td>
          <script language='JavaScript'>
          <?= $js2 ?> 
          </script>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_inscrito&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_banca_julgadora":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "prf_id",          $dados[ 'prf_id' ] );
    extract_request_var( "prf_id_old",      $dados[ 'prf_id_old' ] );

    extract_request_var( "cat_id",          $dados[ 'cat_id' ] );
    extract_request_var( "stc_id",          $dados[ 'stc_id' ] );
    extract_request_var( "epr_texto",       $dados[ 'epr_texto' ] );
    extract_request_var( "epr_entregue",    $dados[ 'epr_entregue' ] );

    extract_request_var( "alterar",         $alterar );


    if( $alterar == "yeah" && $dados[ 'prf_id' ] != "" && $dados[ 'evt_id' ] != "" )
    {

        $query = "
            SELECT
                cat_id,
                stc_id,
                stc_nome,
                epr_texto,
                epr_entregue
            FROM
                evt_prf
                NATURAL LEFT OUTER JOIN status_contato
            WHERE
                prf_id = '" . ( consis_inteiro( $dados[ 'prf_id_old' ] ) ? in_bd( $dados[ 'prf_id_old' ] ) : in_bd( $dados[ "prf_id" ] ) ) . "'
                AND evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'"; 

        $rs = $sql->squery( $query );

        if( is_array( $rs ) )
        {
            $dados[ 'cat_id' ]          = $rs[ 'cat_id' ];
            $dados[ 'stc_id' ]          = $rs[ 'stc_id' ];
            $dados[ 'epr_texto' ]       = $rs[ 'epr_texto' ];
            $dados[ 'epr_entregue' ]    = $rs[ 'epr_entregue' ];
            $dados[ 'stc_nome' ]        = $rs[ 'stc_nome' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="prf_id_old"  value="<?= ( ( $dados[ "prf_id_old" ] != "" ) ? $dados[ "prf_id_old" ] : $dados[ "prf_id" ] ) ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_banca_julgadora" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='banca_julgadora'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Banca Julgadora
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Professor</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "prf_id", "prf_nome", "professor", $dados[ "prf_id" ], array( "name" => "prf_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Categoria</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "cat_id", "cat_nome", "categoria", $dados[ "cat_id" ], array( "name" => "cat_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Status Contato</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "stc_id", "stc_nome", "status_contato", $dados[ "stc_id" ], array( "name" => "stc_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff'><input type="text" name="epr_texto" value="<?= in_html( $dados[ "epr_texto" ] ) ?>" size="30" /></td>
        </tr>
        <?
        if( isset( $dados[ 'stc_nome' ] ) && $dados[ 'stc_nome' ] == "Confirmado" )
        {
        ?> 
            <tr>
              <td bgcolor='#ffffff' class='textb'>Entregue</td>
              <td bgcolor='#ffffff'><input type="checkbox" class="caixa" value='1' name='epr_entregue'<? if( $dados[ 'epr_entregue' ] == 1 ) print " checked"; ?> /></td>
            </tr>
        <?
        }
        ?>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_banca_julgadora&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_custo":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "cto_id",          $dados[ 'cto_id' ] );

    extract_request_var( "cto_nome",        $dados[ 'cto_nome' ] );
    extract_request_var( "cto_t_movimento", $dados[ 'cto_t_movimento' ] );
    extract_request_var( "cto_valor",       $dados[ 'cto_valor' ] );

    extract_request_var( "alterar",         $alterar );

    if( $alterar == "yeah" && $dados[ 'cto_id' ] != "" )
    {
        $query = "
            SELECT
                cto_nome,
                cto_t_movimento,
                cto_valor
            FROM
                evt_custo
            WHERE
                cto_id = '" . in_bd( $dados[ "cto_id" ] ) . "'";

        $rs = $sql->squery( $query );

        if( is_array( $rs ) )
        {
            $dados[ 'cto_nome' ]    = $rs[ 'cto_nome' ];
            $dados[ 'cto_t_movimento' ] = $rs[ 'cto_t_movimento' ];
            $dados[ 'cto_valor' ]    = $rs[ 'cto_valor' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="cto_id"      value="<?= $dados[ "cto_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_custo" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='custo'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Receitas e Despesas
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Descrição</td>
          <td bgcolor='#ffffff'><input type="text" name="cto_nome" value="<?= in_html( $dados[ "cto_nome" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Tipo Movimento</td>
          <td bgcolor='#ffffff'>
            <input type='radio' name='cto_t_movimento' value='0'<? if( $dados[ 'cto_t_movimento' ] == 0 ) print " checked"; ?> /> Receita 
            <input type='radio' name='cto_t_movimento' value='1'<? if( $dados[ 'cto_t_movimento' ] == 1 ) print " checked"; ?> /> Despesa 
          </td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Valor</td>
          <td bgcolor='#ffffff'><input type="text" name="cto_valor" value="<?= in_html( formata_dinheiro( $dados[ "cto_valor" ] ) ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_custo&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . $dados[ "evt_edicao" ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_evt_arquivo":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "ear_id",      $dados[ 'ear_id' ] );

    extract_request_var( "ear_nome",    $dados[ 'ear_nome' ] );
    extract_request_var( "ear_desc",    $dados[ 'ear_desc' ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'ear_id' ] ) )
    {
        $query = "
            SELECT
                ear_nome,
                ear_desc,
                ear_arq_real,
                ear_arq_falso
            FROM
                evt_arquivo
            WHERE
                ear_id = '" . in_bd( $dados[ "ear_id" ] ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'ear_nome' ]        = $rs[ 'ear_nome' ];
            $dados[ 'ear_desc' ]        = $rs[ 'ear_desc' ];
            $dados[ 'ear_arq_real' ]    = $rs[ 'ear_arq_real' ];
            $dados[ 'ear_arq_falso' ]   = $rs[ 'ear_arq_falso' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="ear_id"      value="<?= $dados[ "ear_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_evt_arquivo" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='evt_arquivo'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Uploads Gerais
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff'><input type="text" name="ear_nome" value="<?= in_html( $dados[ "ear_nome" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Cometários</td>
          <td bgcolor='#ffffff'><input type="text" name="ear_desc" value="<?= in_html( $dados[ "ear_desc" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff'>
        <?
        if( $alterar == "yeah" && $dados[ "ear_arq_real" ] != "" && $dados[ "ear_arq_falso" ] != "" )
                print "Arquivo Atual:  <a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'ear_id' ] . "&tabela=evt_arquivo&col_id=ear_id&arq_col_r=ear_arq_real&arq_col_f=ear_arq_falso '>" . $dados[ 'ear_arq_falso' ] . "</a><br />";
        ?>
            <input type="file" name="ear_arq" size="30" />
          </td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_evt_arquivo&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . $dados[ "evt_edicao" ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_item_final":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "ifi_id",      $dados[ 'ifi_id' ] );

    extract_request_var( "ifi_nome",    $dados[ 'ifi_nome' ] );
    extract_request_var( "ifi_desc",    $dados[ 'ifi_desc' ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'ifi_id' ] ) )
    {
        $query = "
            SELECT
                ifi_nome,
                ifi_desc,
                ifi_arq_real,
                ifi_arq_falso
            FROM
                item_final
            WHERE
                ifi_id = '" . in_bd( $dados[ "ifi_id" ] ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'ifi_nome' ]        = $rs[ 'ifi_nome' ];
            $dados[ 'ifi_desc' ]        = $rs[ 'ifi_desc' ];
            $dados[ 'ifi_arq_real' ]    = $rs[ 'ifi_arq_real' ];
            $dados[ 'ifi_arq_falso' ]   = $rs[ 'ifi_arq_falso' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="ifi_id"      value="<?= $dados[ "ifi_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_item_final" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='item_final'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Finalização
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff'><input type="text" name="ifi_nome" value="<?= in_html( $dados[ "ifi_nome" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Cometários</td>
          <td bgcolor='#ffffff'><input type="text" name="ifi_desc" value="<?= in_html( $dados[ "ifi_desc" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff'>
        <?
        if( $alterar == "yeah" && $dados[ "ifi_arq_real" ] != "" && $dados[ "ifi_arq_falso" ] != "" )
                print "Arquivo Atual:  <a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'ifi_id' ] . "&tabela=item_final&col_id=ifi_id&arq_col_r=ifi_arq_real&arq_col_f=ifi_arq_falso '>" . $dados[ 'ifi_arq_falso' ] . "</a><br />";
        ?>
            <input type="file" name="ifi_arq" size="30" />
          </td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_item_final&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . $dados[ "evt_edicao" ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_material_grafico":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "mgf_id",      $dados[ 'mgf_id' ] );

    extract_request_var( "mgf_nome",    $dados[ 'mgf_nome' ] );
    extract_request_var( "mgf_desc",    $dados[ 'mgf_desc' ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'mgf_id' ] ) )
    {
        $query = "
            SELECT
                mgf_nome,
                mgf_desc,
                mgf_arq_real,
                mgf_arq_falso
            FROM
                material_grafico
            WHERE
                mgf_id = '" . in_bd( $dados[ "mgf_id" ] ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'mgf_nome' ]        = $rs[ 'mgf_nome' ];
            $dados[ 'mgf_desc' ]        = $rs[ 'mgf_desc' ];
            $dados[ 'mgf_arq_real' ]    = $rs[ 'mgf_arq_real' ];
            $dados[ 'mgf_arq_falso' ]   = $rs[ 'mgf_arq_falso' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="mgf_id"      value="<?= $dados[ "mgf_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_material_grafico" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='material_grafico'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Material Gráfico
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Item</td>
          <td bgcolor='#ffffff'><input type="text" name="mgf_nome" value="<?= in_html( $dados[ "mgf_nome" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Cometários</td>
          <td bgcolor='#ffffff'><input type="text" name="mgf_desc" value="<?= in_html( $dados[ "mgf_desc" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Arquivo</td>
          <td bgcolor='#ffffff'>
        <?
        if( $alterar == "yeah" && $dados[ "mgf_arq_real" ] != "" && $dados[ "mgf_arq_falso" ] != "" )
                print "Arquivo Atual:  <a href='" . $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'mgf_id' ] . "&tabela=material_grafico&col_id=mgf_id&arq_col_r=mgf_arq_real&arq_col_f=mgf_arq_falso '>" . $dados[ 'mgf_arq_falso' ] . "</a><br />";
        ?>
            <input type="file" name="mgf_arq" size="30" />
          </td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_material_grafico&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_patrocinador":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "pat_id",      $dados[ 'pat_id' ] );
    extract_request_var( "pat_id_old",  $dados[ 'pat_id_old' ] );

    extract_request_var( "epa_texto",   $dados[ 'epa_texto' ] );
    extract_request_var( "mem_id",      $dados[ 'mem_id' ] );
    extract_request_var( "stc_id",      $dados[ "stc_id" ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'pat_id' ] ) && consis_inteiro( $dados[ 'evt_id' ] ) )
    {
        $query = "
            SELECT
                mem_id,
                stc_id,
                epa_texto
            FROM
                evt_pat
                NATURAL LEFT OUTER JOIN patrocinador
            WHERE
                pat_id = '" . ( consis_inteiro( $dados[ 'pat_id_old' ] ) ? in_bd( $dados[ 'pat_id_old' ] ) : in_bd( $dados[ "pat_id" ] ) ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'mem_id' ]      = $rs[ 'mem_id' ];
            $dados[ 'stc_id' ]      = $rs[ 'stc_id' ];
            $dados[ 'epa_texto' ]   = $rs[ 'epa_texto' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="pat_id_old"  value="<?= ( ( $dados[ "pat_id_old" ] != "" ) ? $dados[ "pat_id_old" ] : $dados[ "pat_id" ] ) ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_patrocinador" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='patrocinador'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Patrocinadores
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Patrocinador</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "pat_id", "pat_nome", "patrocinador", $dados[ "pat_id" ], array( "name" => "pat_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Status Contato</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "stc_id", "stc_nome", "status_contato", $dados[ "stc_id" ], array( "name" => "stc_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff'><input type="text" name="epa_texto" value="<?= in_html( $dados[ "epa_texto" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "mem_id", "mem_nome", "membro_vivo", $dados[ "mem_id" ], array( "name" => "mem_id" ) ) ?></td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_patrocinador&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_fornecedor":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "for_id",      $dados[ 'for_id' ] );
    extract_request_var( "for_id_old",  $dados[ 'for_id_old' ] );

    extract_request_var( "efo_texto",   $dados[ 'efo_texto' ] );
    extract_request_var( "mem_id",      $dados[ 'mem_id' ] );
    extract_request_var( "stc_id",      $dados[ "stc_id" ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'for_id' ] ) && consis_inteiro( $dados[ 'evt_id' ] ) )
    {
        $query = "
            SELECT
                mem_id,
                stc_id,
                efo_texto
            FROM
                evt_for
                NATURAL LEFT OUTER JOIN fornecedor
            WHERE
                for_id = '" . ( consis_inteiro( $dados[ 'for_id_old' ] ) ? in_bd( $dados[ 'for_id_old' ] ) : in_bd( $dados[ "for_id" ] ) ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'mem_id' ]      = $rs[ 'mem_id' ];
            $dados[ 'stc_id' ]      = $rs[ 'stc_id' ];
            $dados[ 'efo_texto' ]   = $rs[ 'efo_texto' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="for_id_old"  value="<?= ( ( $dados[ "for_id_old" ] != "" ) ? $dados[ "for_id_old" ] : $dados[ "for_id" ] ) ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_fornecedor" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='fornecedor'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Fornecedores
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Fornecedor</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "for_id", "for_nome", "fornecedor", $dados[ "for_id" ], array( "name" => "for_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Status Contato</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "stc_id", "stc_nome", "status_contato", $dados[ "stc_id" ], array( "name" => "stc_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff'><input type="text" name="efo_texto" value="<?= in_html( $dados[ "efo_texto" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "mem_id", "mem_nome", "membro_vivo", $dados[ "mem_id" ], array( "name" => "mem_id" ) ) ?></td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_fornecedor&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_palestrante":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "pal_id",      $dados[ 'pal_id' ] );
    extract_request_var( "pal_id_old",  $dados[ 'pal_id_old' ] );

    extract_request_var( "epl_texto",   $dados[ 'epl_texto' ] );
    extract_request_var( "mem_id",      $dados[ 'mem_id' ] );
    extract_request_var( "stc_id",      $dados[ 'stc_id' ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && consis_inteiro( $dados[ 'pal_id' ] ) && consis_inteiro( $dados[ 'evt_id' ] ) )
    {
        $query = "
            SELECT
                mem_id,
                stc_id,
                epl_texto
            FROM
                evt_pal
                NATURAL LEFT OUTER JOIN palestrante
            WHERE
                pal_id = '" . ( consis_inteiro( $dados[ 'pal_id_old' ] ) ? in_bd( $dados[ 'pal_id_old' ] ) : in_bd( $dados[ "pal_id" ] ) ) . "'
                AND evt_id = '" . in_bd( $dados[ "evt_id" ] ) . "'"; 
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'mem_id' ]      = $rs[ 'mem_id' ];
            $dados[ 'stc_id' ]      = $rs[ 'stc_id' ];
            $dados[ 'epl_texto' ]   = $rs[ 'epl_texto' ];
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="pal_id_old"  value="<?= ( ( $dados[ "pal_id_old" ] != "" ) ? $dados[ "pal_id_old" ] : $dados[ "pal_id" ] ) ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_palestrante" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='palestrante'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Palestrantes
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Palestrante</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "pal_id", "pal_nome", "palestrante", $dados[ "pal_id" ], array( "name" => "pal_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Status Contato</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "stc_id", "stc_nome", "status_contato", $dados[ "stc_id" ], array( "name" => "stc_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Comentários</td>
          <td bgcolor='#ffffff'><input type="text" name="epl_texto" value="<?= in_html( $dados[ "epl_texto" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "mem_id", "mem_nome", "membro_vivo", $dados[ "mem_id" ], array( "name" => "mem_id" ) ) ?></td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_palestrante&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;
case "inserir_tarefa_cronograma":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    extract_request_var( "eta_id",      $dados[ 'eta_id' ] );

    extract_request_var( "mem_id",      $dados[ 'mem_id' ] );
    extract_request_var( "ste_id",      $dados[ 'ste_id' ] );
    extract_request_var( "eta_desc",    $dados[ 'eta_desc' ] );
    extract_request_var( "eta_dt_ini",  $dados[ 'eta_dt_ini' ] );
    extract_request_var( "eta_dt_fim",  $dados[ 'eta_dt_fim' ] );

    extract_request_var( "alterar",     $alterar );

    if( $alterar == "yeah" && $dados[ 'eta_id' ] != "" )
    {
        $query = "
            SELECT
                mem_id,
                ste_id,
                eta_desc,
                DATE_PART( 'epoch', eta_dt_ini ) AS eta_dt_ini,
                DATE_PART( 'epoch', eta_dt_fim ) AS eta_dt_fim
            FROM
                evt_tarefa
            WHERE
                eta_id = '" . in_bd( $dados[ "eta_id" ] ) . "'";
    
        $rs = $sql->squery( $query );
        
        if( is_array( $rs ) )
        {
            $dados[ 'mem_id' ]      = $rs[ 'mem_id' ];
            $dados[ 'ste_id' ]      = $rs[ 'ste_id' ];
            $dados[ 'eta_desc' ]    = $rs[ 'eta_desc' ];
            $dados[ 'eta_dt_ini' ]  = array("dia" => date( 'd', $rs[ 'eta_dt_ini' ] ),
                                            "mes" => date( 'm', $rs[ 'eta_dt_ini' ] ) ,
                                            "ano" => date( 'Y', $rs[ 'eta_dt_ini' ] ) );
            $dados[ 'eta_dt_fim' ]  = array("dia" => date( 'd', $rs[ 'eta_dt_fim' ] ),
                                            "mes" => date( 'm', $rs[ 'eta_dt_fim' ] ),
                                            "ano" => date( 'Y', $rs[ 'eta_dt_fim' ] ) );
        }
    }
    ?>
      <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
        <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
        <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
        <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
        <input type="hidden" name="eta_id"      value="<?= $dados[ "eta_id" ] ?>" />
        <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
        <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
        <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
        <input type="hidden" name="subpagina"   value="po_cronograma" />
        <input type='hidden' name='acao'        value='<?= ( ( $alterar == "yeah" ) ? "alterar" : "inserir" ) ?>'>
        <input type='hidden' name='tipo'        value='tarefa_cronograma'>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Cronograma
          </td>
        </tr>
 
        <? if(isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                    </font></td></tr>
        <? } ?>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Tarefa</td>
          <td bgcolor='#ffffff'><input type="text" name="eta_desc" value="<?= in_html( $dados[ "eta_desc" ] ) ?>" size="30" /></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Responsável</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "mem_id", "mem_nome", "membro_vivo", $dados[ "mem_id" ], array( "name" => "mem_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Status</td>
          <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "ste_id", "ste_nome", "status_evento", $dados[ "ste_id" ], array( "name" => "ste_id" ) ) ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Data de Início</td>
          <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("eta_dt_ini", $dados["eta_dt_ini"]); ?></td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='textb'>Data de Fim</td>
          <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("eta_dt_fim", $dados["eta_dt_fim"]); ?></td>
        </tr>
        <tr>
          <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
            <input type="submit" name="ok" value="&nbsp;<?= ( ( $alterar == "yeah" ) ? "Alterar" : "Inserir" ) ?>&nbsp;" />
            <input type='button' value='Cancelar' onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=po_cronograma&evt_id=" . $dados[ "evt_id" ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
          </td>
        </tr>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
      </form>
<?
    break;











/*
 *
 * MENU
 *
 */











case "menu":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PP_ALTERAR ) && ! tem_permissao( FUNC_MKT_EVENTO_PO_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    /* Vars */
    switch( $dados[ 'tev_mne' ] )
    {
    case "premio_gestao":
        $menu = array( "po_cronograma"          => "Cronograma",
                       "po_equipe_alocada"      => "Equipe Alocada",
                       "po_custo"               => "Receitas e Despesas",
                       "po_fornecedor"          => "Fornecedores",
                       "po_patrocinador"        => "Patrocinadores",
                       "po_material_grafico"    => "Material Gráfico",
                       "po_banca_julgadora"     => "Banca Julgadora",
                       "po_inscrito_pg"         => "Inscritos",
                       "po_item_final"          => "Finalização",
                       "po_evt_arquivo"         => "Uploads Gerais" );
        ?>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Pública
          </td>
        </tr>
        <tr>
          <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=parte_publica&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'>Parte Pública</a>
          </td>
        </tr>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Organizacional
          </td>
        </tr>
        <?
        foreach( $menu as $subpagina_menu => $item_menu )
        {
        ?>
            <tr>
              <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=" . $subpagina_menu . "&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'><?= $item_menu ?></a>
              </td>
            </tr>
        <?
        }
        break;
    case "super_acao":
        $menu = array( "po_cronograma"          => "Cronograma",
                       "po_equipe_alocada"      => "Equipe Alocada",
                       "po_custo"               => "Receitas e Despesas",
                       "po_fornecedor"          => "Fornecedores",
                       "po_patrocinador"        => "Patrocinadores",
                       "po_material_grafico"    => "Material Gráfico",
                       "po_inscrito_superacao"  => "Inscritos",
                       "po_item_final"          => "Finalização",
                       "po_evt_arquivo"         => "Uploads Gerais" );
        ?>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Pública
          </td>
        </tr>
        <tr>
          <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=parte_publica&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'>Parte Pública</a>
          </td>
        </tr>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Organizacional
          </td>
        </tr>
        <?
        foreach( $menu as $subpagina_menu => $item_menu )
        {
        ?>
            <tr>
              <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=" . $subpagina_menu . "&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'><?= $item_menu ?></a>
              </td>
            </tr>
        <?
        }
        break;
    default:
        $menu = array( "po_cronograma"          => "Cronograma",
                       "po_equipe_alocada"      => "Equipe Alocada",
                       "po_custo"               => "Receitas e Despesas",
                       "po_palestrante"         => "Palestrantes",
                       "po_fornecedor"          => "Fornecedores",
                       "po_patrocinador"        => "Patrocinadores",
                       "po_material_grafico"    => "Material Gráfico",
                       "po_inscrito"            => "Inscritos / Convidados",
                       "po_item_final"          => "Finalização",
                       "po_evt_arquivo"         => "Uploads Gerais" );
        ?>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Pública
          </td>
        </tr>
        <tr>
          <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=parte_publica&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'>Parte Pública</a>
          </td>
        </tr>
        <tr>
          <td class='textwhitemini' bgcolor='#336699' height='17'>
            <img src='images/icone.gif' width='23' height='17' align='absbottom' />&nbsp;&nbsp;Parte Organizacional
          </td>
        </tr>
        <?
        foreach( $menu as $subpagina_menu => $item_menu )
        {
        ?>
            <tr>
              <td class='text' bgcolor='#ffffff' align='center'>
                <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=" . $subpagina_menu . "&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . $dados[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'><?= $item_menu ?></a>
              </td>
            </tr>
        <?
        }
    }
    unset( $menu );
    unset( $subpagin_menu );
    unset( $item_menu );
?>
            </tr>
            <tr><td class="text" bgcolor="#336699">&nbsp;</td></tr>
          </form>


<?
    break;





/*
 *
 * PARTE PUBLICA
 *
 */





case "parte_publica":
    if( ! tem_permissao( FUNC_MKT_EVENTO_PP_ALTERAR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }
    $caras[ 'patrocinador' ]    = "";
    $caras[ 'fornecedor' ]      = "";
    $caras[ 'membro' ]          = "";

    /*
     * Dados do Evento 
     */
    $query = "
        SELECT
            evt_tema,
            evt_local,
            DATE_PART( 'epoch', evt_dt_fim ) AS evt_dt_fim,
            DATE_PART( 'epoch', evt_dt ) AS evt_dt
        FROM
            evento
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'";
    
    $rs = $sql->squery( $query );

    if( $rs )
    {
        $dados[ 'evt_tema' ]    = $rs[ 'evt_tema' ];
        $dados[ 'evt_local' ]   = $rs[ 'evt_local' ];
        $dados[ 'evt_dt' ]      = array( 'dia' => date( 'd', $rs[ 'evt_dt' ] ),
                                         'mes' => date( 'm', $rs[ 'evt_dt' ] ) ,
                                         'ano' => date( 'Y', $rs[ 'evt_dt' ] ) );
        $dados[ 'evt_dt_fim' ]  = array( 'dia' => date( 'd', $rs[ 'evt_dt_fim' ] ),
                                         'mes' => date( 'm', $rs[ 'evt_dt_fim' ] ) ,
                                         'ano' => date( 'Y', $rs[ 'evt_dt_fim' ] ) );
    }
    unset( $rs );

    /*
     * Patrocinadores do Evento 
     */
    $busca = $sql->query( "
        SELECT DISTINCT
            pat_nome
        FROM
            evt_pat
            NATURAL JOIN patrocinador
            NATURAL JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
            AND stc_nome = 'Confirmado'
        ORDER BY
            pat_nome" );

    if( is_array( $busca ) )
    {
        $caras[ 'patrocinador' ] = $busca[ 0 ][ 'pat_nome' ];
        array_shift( $busca );

        foreach( $busca as $cara )
            $caras[ 'patrocinador' ] .=  ", " . $cara[ 'pat_nome' ];
    }

    /*
     * Fornecedores do Evento 
     */
    $busca = $sql->query( "
        SELECT DISTINCT
            for_nome
        FROM
            evt_for
            NATURAL JOIN fornecedor
            NATURAL JOIN status_contato
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
            AND stc_nome = 'Confirmado'
        ORDER BY
            for_nome" );

    if( is_array( $busca ) )
    {
        $caras[ 'fornecedor' ] = $busca[ 0 ][ 'for_nome' ];
        array_shift( $busca );

        foreach( $busca as $cara )
            $caras[ 'fornecedor' ] .=  ", " . $cara[ 'for_nome' ];
    }

    /*
     * Equipe Alocada (membros)
     */
    $query = "
        SELECT DISTINCT
            mem.mem_nome
        FROM
            evt_mem cst
            LEFT JOIN membro_vivo mem ON ( cst.mem_id = mem.mem_id )
        WHERE
            evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
        ORDER BY
            mem_nome";
    
    $busca = $sql->query( $query );

    if( is_array( $busca ) )
        foreach( $busca as $cara )
            $caras[ 'membro' ] .=  $cara[ 'mem_nome' ] . "<br />";

    unset( $query );
    unset( $cara  );

    $colspan = '2';
    ?>
        <tr>
          <td class="textwhitemini" colspan="<?= $colspan ?>" bgColor="#336699" HEIGHT="17">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
            <!-- 
            <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
            //-->
            <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
            <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
            <input type="hidden" name="subpagina"   value="menu" />
            <input type="hidden" name="acao"        value="alterar" />
            <input type="hidden" name="tipo"        value="evento" />
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Parte Pública
          </td>
        </tr>
        <? if( isset( $error_msgs ) && is_array( $error_msgs ) && sizeof( $error_msgs ) ) { ?>
                    <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
        <?    foreach( $error_msgs as $msg ) print in_html( $msg )."<br />" ?>
                    </font></td></tr>
        <?  } ?>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Tipo de Evento</td>
          <td class='text' bgcolor='#ffffff'>&nbsp;<?= $dados[ 'tev_nome' ] ?></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Edição</td>
          <td class='text' bgcolor='#ffffff'><input type='text' name='evt_edicao' value='<?= in_html( $dados[ 'evt_edicao' ] ) ?>' size='30' /></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Data do Evento</td>
          <td class='text'bgcolor='#ffffff'><? gera_select_data( "evt_dt", $dados[ 'evt_dt' ] ) ?></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Data de Fim Evento</td>
          <td class='text'bgcolor='#ffffff'><? gera_select_data( "evt_dt_fim", $dados[ 'evt_dt_fim' ] ) ?></td>
        </tr>
    <?
    switch( $dados[ 'tev_mne' ] )
    {
    case "super_acao":
        break;
    case "premio_gestao":
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
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
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
                        evt_id = '"     . in_bd( $dados[ 'evt_id' ] )       . "'
                        AND cat_id = '" . in_bd( $categoria[ 'cat_id' ] )   . "'
                        AND " . $media . " >= ( '7.0' )
                        AND " . $media . " IN
                        (
                            SELECT
                                MAX( " . $media . " )
                            FROM
                                inscrito_pg
                            WHERE
                                evt_id = '" . in_bd( $dados[ 'evt_id' ] )       . "'
                                AND cat_id = '" . in_bd( $categoria[ 'cat_id' ] ) . "'
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
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND eme_coordenador = '1'
            ORDER BY
                mem_nome";
    
        $busca = $sql->squery( $query );

        if( $busca )
            $caras[ 'coordenador' ] = $busca[ 'mem_nome' ];
        ?>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Categorias</td>
          <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'categoria' ] != '' ) ? $caras[ 'categoria' ] : "&nbsp;" ) ?></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Ganhadores</td>
          <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'ganhador' ] != '' ) ? $caras[ 'ganhador' ] : "&nbsp;" ) ?></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Coordenador</td>
          <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'coordenador' ] != '' ) ? $caras[ 'coordenador' ] : "&nbsp;" ) ?></td>
        </tr>
        <?
        break;
    default:
        $caras[ 'palestrante' ]     = "";
        $caras[ 'apoiador' ]        = "";
        
        /*
         * Palestrantes do Evento 
         */
        $busca = $sql->query( "
            SELECT DISTINCT
                pal_nome
            FROM
                evt_pal
                NATURAL JOIN palestrante
                NATURAL JOIN status_contato
            WHERE
                evt_id = '" . in_bd( $dados[ 'evt_id' ] ) . "'
                AND stc_nome = 'Confirmado'
            ORDER BY
                pal_nome" );

        if( is_array( $busca ) )
        {
            $caras[ 'palestrante' ] = $busca[ 0 ][ 'pal_nome' ];
            array_shift( $busca );

            foreach( $busca as $cara )
                $caras[ 'palestrante' ] .=  ", " . $cara[ 'pal_nome' ];
        }
    ?>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Tema do Evento</td>
          <td class='text' bgcolor='#ffffff'><input type='text' name='evt_tema' value='<?= in_html( $dados[ 'evt_tema' ] ) ?>' size='30' /></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Local do Evento</td>
          <td class='text' bgcolor='#ffffff'><input type='text' name='evt_local' value='<?= in_html( $dados[ 'evt_local' ] ) ?>' size='30' /></td>
        </tr>
        <tr>
          <td class='textb' bgcolor='#ffffff'>Palestrantes</td>
          <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'palestrante' ] ) ? $caras[ 'palestrante' ] : '&nbsp;' ) ?></td>
        </tr>
        <!--
        <tr>
          <td class='textb' bgcolor='#ffffff'>Apoiadores</td>
          <td class='text' bgcolor='#ffffff'><?= in_html( $caras[ 'apoiador' ] ) ?>&nbsp;</td>
        </tr>
        //-->
    <?
        break;
    }   
    ?>
    <tr>
      <td class='textb' bgcolor='#ffffff'>Patrocinadores</td>
      <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'patrocinador' ] ) ? $caras[ 'patrocinador' ] : '&nbsp;' ) ?></td>
    </tr>
    <tr>
      <td class='textb' bgcolor='#ffffff'>Fornecedores</td>
      <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'fornecedor' ] ) ? $caras[ 'fornecedor' ] : '&nbsp;' ) ?></td>
    </tr>
    <tr>
      <td class='textb' bgcolor='#ffffff'>Equipe Alocada</td>
      <td class='text' bgcolor='#ffffff'><?= ( ( $caras[ 'membro' ] ) ? $caras[ 'membro' ] : '&nbsp;' ) ?></td>
    </tr>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff' class='text' align='center'>
        <input type="submit" name="ok" value="&nbsp;Alterar&nbsp;" />
      </td>
    </tr>  
    </form>
    <tr>
      <td class="text" colspan="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
    </tr>
    <?
    break;





/*
 *
 * MISC
 *
 */





case "aluno_busca":
    extract_request_var( "aluno_busca_campo",    $dados[ "aluno_busca_campo" ] );
    extract_request_var( "aluno_busca_texto",    $dados[ "aluno_busca_texto" ] );
    extract_request_var( "pagina_retorno",       $dados[ "pagina_retorno" ] );

    /* premio gestao */
    extract_request_var( "cat_id",              $dados[ 'cat_id' ] );
    extract_request_var( "prf_id_1",            $dados[ 'prf_id_1' ] );
    extract_request_var( "prf_id_2",            $dados[ 'prf_id_2' ] );

    /* superacao */
    extract_request_var( "agv_id_old",          $dados[ 'agv_id_old' ] );
    extract_request_var( "eqp_colocacao",       $dados[ 'eqp_colocacao' ] );
    extract_request_var( "eqp_id",              $dados[ 'eqp_id' ] );

    if( $dados[ 'aluno_busca_campo' ] == "" )
    {
        ?>
        <script language='javascript'>
        history.go( -1 );
        window.alert( 'Você deve escolher um campo pra busca' ); 
        </script>
        <?
        exit;
    }

    $query = "
        SELECT
            agv_id,
            agv_matricula,
            agv_nome,
            agv_email
        FROM
            aluno_gv
        WHERE
            " . $dados[ "aluno_busca_campo" ] . " ILIKE '%" . in_bd( $dados[ "aluno_busca_texto" ] ) . "%'";

    $rs = $sql->query( $query );
?>

<tr>
  <td class="textwhitemini" colspan="4" bgcolor="#336699" height="17">
  <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="evt_id"      value="<?= $dados[ "evt_id" ] ?>" />
    <input type="hidden" name="evt_edicao"  value="<?= $dados[ "evt_edicao" ] ?>" />
    <input type="hidden" name="tev_nome"    value="<?= $dados[ "tev_nome" ] ?>" />
    <input type="hidden" name="tev_mne"     value="<?= $dados[ "tev_mne" ] ?>" />
    <input type="hidden" name="subpagina"   value="<?= $dados[ "pagina_retorno"] ?>" />
    <input type="hidden" name="i_aluno_gv"  value="1" />
    <input type="hidden" name="cat_id"      value="<?= $dados[ "cat_id" ] ?>" />
    <input type="hidden" name="prf_id_1"    value="<?= $dados[ "prf_id_1" ] ?>" />
    <input type="hidden" name="prf_id_2"    value="<?= $dados[ "prf_id_2" ] ?>" />
    <input type="hidden" name="agv_id_old"  value="<?= $dados[ "agv_id_old" ] ?>" />
    <input type="hidden" name="eqp_id"      value="<?= $dados[ "eqp_id" ] ?>" />
    <input type="hidden" name="eqp_colocacao"  value="<?= $dados[ "eqp_colocacao" ] ?>" />
    <input type="hidden" name="aluno_busca_campo" value="<?= $dados[ "aluno_busca_campo" ] ?>" />
    <input type="hidden" name="aluno_busca_texto" value="<?= $dados[ "aluno_busca_texto" ] ?>" />
    <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;Busca de Aluno GV para Inscrição
  </td>
</tr>
<?
    if( ! is_array( $rs ) )
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'>
          Nenhum aluno foi encontrado para sua busca
          </td>
        </tr>
        <tr>
          <td bgcolor='#ffffff' class='text'>
            <input type="button" value="Voltar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=" . $dados[ 'pagina_retorno' ] . "&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
            </form>
          </td>
        </tr>
    <?
        break;
    }
?>

<tr>
  <td bgcolor='#ffffff' class='textb' colspan='5'>Escolha o Aluno da Lista</td>
</tr>

<tr>
  <td bgcolor='#ffffff' class='textb'>&nbsp;</td>
  <td bgcolor='#ffffff' class='textb'>Matrícula</td>
  <td bgcolor='#ffffff' class='textb'>Nome</td>
  <td bgcolor='#ffffff' class='textb'>Email</td>
</tr>

<?
    foreach( $rs as $cara )
    {
    ?>
        <tr>
          <td bgcolor='#ffffff' class='text'><input type='radio' name='agv_id' value='<?= $cara[ 'agv_id' ] ?>' /></td>
          <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_matricula' ] ?></td>
          <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_nome' ] ?></td>
          <td bgcolor='#ffffff' class='text'>&nbsp;<?= $cara[ 'agv_email' ] ?></td>
        </tr>
    <?
    }
    ?>
    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
        <input type="submit" name="ok" value="&nbsp;OK&nbsp;" />
        <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=" . $dados[ 'pagina_retorno' ] . "&evt_id=" . $dados[ 'evt_id' ] . "&evt_edicao=" . urlencode( $dados[ "evt_edicao" ] ) . "&tev_nome=" . urlencode( $dados[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $dados[ 'tev_mne' ] ) ?>'" />
        </form>
      </td>
    </tr>
<?
    break;
case "tipo_evento_selecao":
    if( ! tem_permissao( FUNC_MKT_EVENTO_INSERIR ) )
    {
        include( ACESSO_NEGADO );
        break;
    }

    /* Vars */
    extract_request_var( "tev_id",      $dados[ "tev_id" ] );
    extract_request_var( "evt_dt",      $dados[ "evt_dt" ] );
?>
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="menu" />
            <input type="hidden" name="acao"        value="inserir" />
            <input type="hidden" name="tipo"        value="evento" />
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Tipo Evento Seleção
              </td>
            </tr>
 
<? if( isset( $error_msgs ) && is_array( $error_msgs ) && sizeof( $error_msgs ) ) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach( $error_msgs as $msg ) print in_html( $msg )."<br />" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Tipo de Evento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g( $sql, "tev_id", "tev_nome", "tipo_evento", $dados[ "tev_id" ], array( "name" => "tev_id" ) ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Edição</td>
              <td bgcolor='#ffffff'><input type="text" name="evt_edicao" value="<?= in_html( $dados[ "evt_edicao" ] ) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data do Evento</td>
              <td bgcolor='#ffffff'><? gera_select_data( "evt_dt", ( ( is_array( $dados[ "evt_dt" ] ) ) ? $dados[ "evt_dt" ] : array( "dia" => 0, "mes" => "0", "ano" => "0" ) ) ) ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;Inserir&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
<?
    unset( $error_msgs );
    break;
case "acesso_negado":
    include( ACESSO_NEGADO );
    break;
default:
    /* Consulta */

    /* Vars que vem do POST */
    extract_request_var( "busca_campo_evento",          $busca_campo_evento );
    extract_request_var( "busca_campo_tipo_evento",     $busca_campo_tipo_evento );
    extract_request_var( "busca_campo_patrocinador",    $busca_campo_patrocinador );
    extract_request_var( "busca_campo_fornecedor",      $busca_campo_fornecedor  );

    extract_request_var( "busca_texto_evento",          $busca_texto_evento );
    extract_request_var( "busca_texto_tipo_evento",     $busca_texto_tipo_evento );
    extract_request_var( "busca_texto_patrocinador",    $busca_texto_patrocinador );
    extract_request_var( "busca_texto_fornecedor",      $busca_texto_fornecedor  );

    extract_request_var( "busca_pagina_num",            $busca_pagina_num );
    extract_request_var( "busca_qt_por_pagina",         $busca_qt_por_pagina );

    $busca_qt_por_pagina = QT_POR_PAGINA_DEFAULT;

    if( ! consis_inteiro( $busca_pagina_num )  || $busca_pagina_num == 0 )
        $busca_pagina_num = 1;

    /* Campos possiveis pra busca */
    $possiveis_campos[ 'evento' ]       = array( "Edição"           => "evt_edicao" );

    $possiveis_campos[ 'tipo_evento' ]  = array( "Nome"             => "tev_nome",
                                                 "Descrição"        => "tev_desc" );

    $possiveis_campos[ 'patrocinador' ] = array( "Nome"             => "pat_nome",
                                                 "Nome do Contato"  => "pat_nome_contato",
                                                 "Telefone"         => "pat_telefone",
                                                 "Ramal"            => "pat_ramal",
                                                 "Fax"              => "pat_fax",
                                                 "Email"            => "pat_email",
                                                 "Celular"          => "pat_celular" );

    $possiveis_campos[ 'fornecedor' ]   = array( "Nome"             => "for_nome",
                                                 "Nome do Contato"  => "for_nome_contato",
                                                 "Telefone"         => "for_telefone",
                                                 "Ramal"            => "for_ramal",
                                                 "Fax"              => "for_fax",
                                                 "Email"            => "for_email",
                                                 "Celular"          => "for_celular" );
?>
    <tr>
    <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17">
    <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Busca
    </td>
    </tr>
    <tr>
    <td class='textb' bgcolor="#ffffff">
    <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
    <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina" value="<?= $pagina ?>" />
    <input type="hidden" name="busca_agora" value="yeah" />
    Evento
    </td>
    <td class='text' bgcolor="#ffffff">
    <? gera_select_from_hash_tv( $possiveis_campos[ 'evento' ], array( $busca_campo_evento ), array( "name" => "busca_campo_evento" ) ); ?>
    </td>
    <td class='text' bgcolor="#ffffff"><input type='text' name='busca_texto_evento' value="<?= in_html( $busca_texto_evento ) ?>" /></td>
    </tr>

    <tr>
    <td class='textb' bgcolor="#ffffff">Tipo de Evento</td>
    </td>
    <td class='text' bgcolor="#ffffff">
    <? gera_select_from_hash_tv( $possiveis_campos[ 'tipo_evento' ], array( $busca_campo_tipo_evento ), array( "name" => "busca_campo_tipo_evento" ) ); ?>
    </td>
    <td class='text' bgcolor="#ffffff"><input type='text' name='busca_texto_tipo_evento' value="<?= in_html( $busca_texto_tipo_evento ) ?>" /></td>
    </tr>

    <tr>
    <td class='textb' bgcolor="#ffffff">Patrocinador</td>
    <td class='text' bgcolor="#ffffff">
    <? gera_select_from_hash_tv( $possiveis_campos[ 'patrocinador' ], array( $busca_campo_patrocinador ), array( "name" => "busca_campo_patrocinador" ) ); ?>
    </td>
    <td class='text' bgcolor="#ffffff"><input type='text' name='busca_texto_patrocinador' value="<?= in_html( $busca_texto_patrocinador ) ?>" /></td>
    </td>
    </tr>

    <tr>
    <td class='textb' bgcolor="#ffffff">Fornecedor</td>
    <td class='text' bgcolor="#ffffff">
    <? gera_select_from_hash_tv( $possiveis_campos[ 'fornecedor' ], array( $busca_campo_fornecedor ), array( "name" => "busca_campo_fornecedor" ) ); ?>
    </td>
    <td class='text' bgcolor="#ffffff"><input type='text' name='busca_texto_fornecedor' value="<?= in_html( $busca_texto_fornecedor ) ?>" /></td>

    <tr>
    <td class='textb' bgcolor="#ffffff" colspan='3' align='center'>
    <input type="submit" value='&nbsp;Buscar&nbsp;' />
    </form>
    </td>
    </tr>

    <tr>
    <td class='textb' bgcolor="#ffffff" colspan='3' align='center'>
    <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
    <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina" value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina" value="tipo_evento_selecao" />
    <input type="submit" name="ok" value="&nbsp;Inserir Novo&nbsp;" />
    </form>
    </td>
    </tr>

    <tr><td class="text" COLSPAN="4" bgColor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
</center>

<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
<?
    extract_request_var( "busca_agora", $busca_agora );
    /* Ja passou pelo form de busca, buscar agora */
    if( $busca_agora == "yeah" || isset( $_SESSION[ "busca" ][ "evento" ] ) )
    {
        $where = "";

        if( $busca_agora != "yeah" && isset( $_SESSION[ "busca" ][ "evento" ] ) && $_SESSION[ "busca" ][ "evento" ] != "" )
            $where = $_SESSION[ "busca" ][ "evento" ];
        else
        {
            if( $busca_texto_evento != "" && $busca_campo_evento != "" )
                $where .= " AND " . $busca_campo_evento . " ILIKE '%" . in_bd( $busca_texto_evento ) . "%'";

            if( $busca_texto_tipo_evento != "" && $busca_campo_tipo_evento != "" )
                $where .= " AND " . $busca_campo_tipo_evento . " ILIKE '%" . in_bd( $busca_texto_tipo_evento ) . "%'";

            if( $busca_texto_patrocinador != "" && $busca_campo_patrocinador != "" )
                $where .= " AND " . $busca_campo_patrocinador . " ILIKE '%" . in_bd( $busca_texto_patrocinador ) . "%'";

            if( $busca_texto_fornecedor != "" && $busca_campo_fornecedor != "" )
                $where .= " AND " . $busca_campo_fornecedor . " ILIKE '%" . in_bd( $busca_texto_fornecedor ) . "%'";

            $_SESSION[ "busca" ][ "evento" ] = $where;
        }

        $query = "
        SELECT
            COUNT( DISTINCT evt_id )
        FROM
            (
                evento
                NATURAL JOIN tipo_evento
            )
            NATURAL LEFT OUTER JOIN
            (
                (
                    evt_pat
                    NATURAL JOIN patrocinador
                )
                FULL OUTER JOIN
                (
                    evt_for
                    NATURAL JOIN fornecedor
                )
                USING ( evt_id )
            )
        WHERE
            evt_id IS NOT NULL" . $where;

        $rs = $sql->squery( $query );

        $dados[ 'qt_paginas' ] = ( $rs ? ceil( $rs[ 'count' ] / $busca_qt_por_pagina ) : 1 );

        if( $busca_pagina_num > $dados[ 'qt_paginas' ] ) 
            $busca_pagina_num = $dados[ 'qt_paginas' ];

        $query = "
        SELECT DISTINCT
            evt_id,
            tev_nome,
            tev_mne,
            evt_edicao
        FROM
            (
                evento
                NATURAL JOIN tipo_evento
            )
            NATURAL LEFT OUTER JOIN
            (
                (
                    evt_pat
                    NATURAL JOIN patrocinador
                )
                FULL OUTER JOIN
                (
                    evt_for
                    NATURAL JOIN fornecedor
                )
                USING ( evt_id )
            )
        WHERE
            evt_id IS NOT NULL" . $where . "
        ORDER BY 
            tev_nome,
            evt_edicao
        LIMIT
            " . $busca_qt_por_pagina . "
        OFFSET
            " . ( abs( ( $busca_pagina_num - 1 ) * $busca_qt_por_pagina ) );

        $rs = $sql->query( $query );
?>
        <tr>
          <td class="textwhitemini" COLSPAN="<?= $colspan ?>" bgColor="#336699" HEIGHT="17">
            <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?>
          </td>
        </tr>
<?
        if( is_array( $rs ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="textb">Excluir
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina" value="<?= $pagina ?>" />
            <input type="hidden" name="acao" value="apagar" />
            <input type="hidden" name="tipo" value="evento" />
            <td bgcolor="#ffffff" class="textb">Tipo Evento</td>
            <td bgcolor="#ffffff" class="textb">Edição</td>
            <td bgcolor="#ffffff" class="textb">Patrocinadores</td>
            <td bgcolor="#ffffff" class="textb">Fornecedores</td>
            <td bgcolor="#ffffff" class="textb">Funções</td>
            </tr>

            <?
            foreach( $rs as $cara )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="caixa" name="caras_ids[]" value="<?= $cara[ 'evt_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'tev_nome' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $cara[ 'evt_edicao' ] ?></td>


                <td bgcolor="#ffffff" class="text">
                &nbsp;
                <?
                $busca_patrocinador = $sql->query( "
                    SELECT DISTINCT
                        pat_nome
                    FROM
                        evt_pat
                        NATURAL JOIN patrocinador
                    WHERE
                        evt_id = '" . in_bd( $cara[ 'evt_id' ] ) . "'
                    ORDER BY
                        pat_nome" );

                if( is_array( $busca_patrocinador ) )
                {
                    print $busca_patrocinador[ 0 ][ 'pat_nome' ];
                    array_shift( $busca_patrocinador );

                    foreach( $busca_patrocinador as $patrocinador )
                        print ", " . $patrocinador[ 'pat_nome' ];
                }

                unset( $busca_patrocinador );
                unset( $patrocinador );
                ?>
                </td>

                <td bgcolor="#ffffff" class="text">
                &nbsp;
                <?
                $busca_fornecedor = $sql->query( "
                    SELECT DISTINCT
                        for_nome
                    FROM
                        evt_for
                        NATURAL JOIN fornecedor
                    WHERE
                        evt_id = '" . in_bd( $cara[ 'evt_id' ] ) . "'
                    ORDER BY
                        for_nome" );

                if( is_array( $busca_fornecedor ) )
                {
                    print $busca_fornecedor[ 0 ][ 'for_nome' ];
                    array_shift( $busca_fornecedor );

                    foreach( $busca_fornecedor as $fornecedor )
                        print ", " . $fornecedor[ 'for_nome' ];
                }

                unset( $busca_fornecedor );
                unset( $fornecedor );
            ?>
                </td>

                <td  bgcolor="#ffffff" class="text">
                <a href='<?= $_SERVER[ "SCRIPT_NAME" ] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=menu&tipo=evento&evt_id=" . $cara[ 'evt_id' ] . "&evt_edicao=" . $cara[ 'evt_edicao' ] . "&tev_nome=" . urlencode( $cara[ 'tev_nome' ] ) . "&tev_mne=" . urlencode( $cara[ 'tev_mne' ] ) ?>'>Alterar</a>
                </td>
            <?
            }
            ?>
            </td>
            </tr>
            <?
            /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
            if( $dados[ 'qt_paginas' ] > 1 )
            {
            ?>
                <tr>
                    <td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff">
            <?
                /* se a pagina atual for maior que 1, mostrar seta pra voltar */
                if( $busca_pagina_num > 1 )
                {
                ?>
                    <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ( $busca_pagina_num - 1 ) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                <?
                }

                for( $i = 1; $i <= $dados[ 'qt_paginas' ]; $i++ )
                { 
                    if( $i == $busca_pagina_num )
                        print $i;
                    else
                    {
                    ?>
                        <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                    <? 
                    } 
                }

                /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
                if( $dados[ 'qt_paginas' ] > $busca_pagina_num )
                {
                ?>
                    <a href="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=<?= $pagina ?>&busca_pagina_num=<?= ( $busca_pagina_num + 1 ) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                <?
                }
            ?>
                </td>
                </tr>
            <?
            }
            ?>
            <tr>
            <td  bgcolor="#ffffff" class="text" align='center' colspan="<?= $colspan ?>">
            <input type="submit" value=" Apagar " />
            
            </td>
            </tr></form>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor='#ffffff' class="textb" align="center">Não há registros para esta busca.</td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td class="text" COLSPAN="<?= $colspan ?>" bgColor="#336699">&nbsp;</td>
        </tr>
    <?
    }

    /* Unsets */
    unset( $rs );

    unset( $busca_campo_tipo_evento );
    unset( $busca_campo_patrocinador );
    unset( $busca_campo_fornecedor );

    unset( $busca_texto_tipo_evento );
    unset( $busca_texto_patrocinador );
    unset( $busca_texto_fornecedor );

    unset( $possiveis_campos );
}

?>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
</center>
