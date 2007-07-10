<?
/* $Id: rel_print.php,v 1.8 2002/12/17 19:23:37 binary Exp $ */

extract_request_var( "relatorio", $relatorio );
extract_request_var( "acao", $acao );

$titulo = "- Relatório";
?>
<html>
<head>
  <title>Banco de Dados - Empresa Junior FGV - Relatório</title>
  <link rel="stylesheet" type="text/css" href="images/fgv.css" />
</head>

<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?

switch( $acao )
{
    case "procurar_clientes":
        if( ! tem_permissao( FUNC_REL_CLIENTE ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] ) )
        {                
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
                cli_id IS NOT NULL " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'clientes' ][ 'query_where' ] );
	}
        break;
    case "procurar_consultorias":
        if( ! tem_permissao( FUNC_REL_CONSULTORIA ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] ) )
        {                
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
                cst_id IS NOT NULL " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'consultorias' ][ 'query_where' ] );

	}
        break;
    case "procurar_eventos":
        if( ! tem_permissao( FUNC_REL_EVENTO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'eventos' ][ 'query_where' ] ) )
        {
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
                evt_edicao" );

	}
        break;
    case "procurar_membros_exmembros":
        if( ! tem_permissao( FUNC_REL_MEMBRO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'membros_exmembros' ][ 'query_where' ] ) )
        {
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
                agv_nome" );
	}        
        break;
    case "procurar_empresas_juniores":
        if( ! tem_permissao( FUNC_REL_EMPRESA_JUNIOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'empresas_juniores' ][ 'query_where' ] ) )
        {
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
                eju_nome" );
	}
        break;
    case "procurar_fornecedores":
        if( ! tem_permissao( FUNC_REL_FORNECEDOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'fornecedores' ][ 'query_where' ] ) )
        {
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
                for_nome" );
	}
        break;
    case "procurar_patrocinadores":
        if( ! tem_permissao( FUNC_REL_PATROCINADOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'patrocinadores' ][ 'query_where' ] ) )
        {                
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
                pat_nome" );
	}
        break;
    case "procurar_palestrantes":
        if( ! tem_permissao( FUNC_REL_PALESTRANTE ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'palestrantes' ][ 'query_where' ] ) )
        {                
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
                pal_nome" );
	}
        break;
    case "procurar_professores":
        if( ! tem_permissao( FUNC_REL_PROFESSOR ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'professores' ][ 'query_where' ] ) )
        {                
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
                prf_nome" );
	}
        break;
    case "procurar_alunos_gv":
        if( ! tem_permissao( FUNC_REL_ALUNO_GV ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_gv' ][ 'query_where' ] ) )
        {                
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
                agv_nome" );
	}
        break;
    case "procurar_alunos_nao_gv":
        if( ! tem_permissao( FUNC_REL_ALUNO_NAO_GV ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] ) )
        {                
            if( $_SESSION[ 'busca' ][ 'relatorio' ][ 'alunos_nao_gv' ][ 'query_where' ] != "" )
                $where = "inscrito_ngv NATURAL JOIN aluno_nao_gv";
            else
                $where = "aluno_nao_gv";
	    
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
                ang_nome" );
	}
        break;
    case "procurar_timesheets":
        if( ! tem_permissao( FUNC_REL_TIMESHEET ) )
        {
            $relatorio = "acesso_negado";
            break;
        }

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] ) )
        {                
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
                mem_id IS NOT NULL " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'timesheets' ][ 'query_where' ] );

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

        if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] ) )
        {
	    $busca_processos_seletivos = $sql->query( "
            SELECT DISTINCT
                psl_id,
                date_part( 'epoch', psl_dt_selecao ) AS psl_timestamp
            FROM
                p_seletivo
            WHERE
                psl_id IS NOT NULL
                " . $_SESSION[ 'busca' ][ 'relatorio' ][ 'processos_seletivos' ][ 'query_where' ] );
	}
        break;
     case "procurar_premio_gestao":
        if( ! tem_permissao( FUNC_REL_PREMIO ) )
        {
            $relatorio = "acesso_negado";
            break;
        }
	
	if( isset( $_SESSION[ 'busca' ][ 'relatorio' ][ 'premio_gestao' ][ 'query_where' ] ) )
	{
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
                 nome_evento" );
	}
	break;
}
?>

<center><img src='images/tecnologia.gif' /></center>
       
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

        ?>

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="9"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Clientes <?= $titulo ?></td>
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
	}
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="9">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="6" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Consultorias <?= $titulo ?></td>
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
        }
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="6">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="7" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="7">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
    <tr><td>        
    <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Eventos "Prêmio Gestão" <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="4">&nbsp;</td>
        </tr>
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "membros_exmembros":
        ?>

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" colspan="6" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Membros e Ex-membros <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="6">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "empresas_juniores":
        ?>

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Empresas Juniores <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="5">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Fornecedores <?= $titulo ?></td>
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
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="4">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="6" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Patrocinadores <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="6">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Palestrantes <?= $titulo ?></td>
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
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="5">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="6" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Professores <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="6">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;        
    case "alunos_gv":
        ?>

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="4" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos GV <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="4">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Alunos não GV <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="5">&nbsp;</td>
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

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" colspan="7" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Timesheet <?= $titulo ?></td>
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
        }
        ?>

        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="7">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case "processos_seletivos":
        ?>

<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="600">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Processos Seletivos <?= $titulo ?></td>
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
        }
        ?>

        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="5">&nbsp;</td>
        </tr>
         </table>
       </td></tr>
      </table></center><BR><BR> 
        <?
        break;
    case 'acesso_negado':
        include( ACESSO_NEGADO );
        break;
}
?>
