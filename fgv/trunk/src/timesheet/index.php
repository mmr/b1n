<?
/* $Id: index.php,v 1.15 2002/07/05 20:26:43 binary Exp $ */
?>

<?
include_once( "include/funcoes.php" );

extract_request_var( "acao", $acao );
extract_request_var( "timesheet_area", $timesheet_area );
extract_request_var( "timesheet_atividade", $timesheet_atividade );
extract_request_var( "timesheet_empresa", $timesheet_empresa );
extract_request_var( "timesheet_evento", $timesheet_evento );
extract_request_var( "timesheet_projeto_interno", $timesheet_projeto_interno );
extract_request_var( "timesheet_consultoria", $timesheet_consultoria );
extract_request_var( "timesheet_subatividade", $timesheet_subatividade );
extract_request_var( "timesheet_tempo", $timesheet_tempo );
extract_request_var( "timesheet_comentario", $timesheet_comentario );
extract_request_var( "timesheet_dia", $timesheet_dia );
extract_request_var( "timesheet_mes", $timesheet_mes );
extract_request_var( "timesheet_ano", $timesheet_ano );
extract_request_var( "busca_pagina_num_tsh", $busca_pagina_num_tsh );

/* ------------------------------ Ações ------------------------------ */

if( isset( $acao ) )
{
    switch( $acao )
    {
        case "remover":
            extract_request_var( "timesheets_selecionados_ids", $timesheets_selecionados_ids );

            if( is_array( $timesheets_selecionados_ids ) )
            {
                foreach( $timesheets_selecionados_ids as $timesheet_selecionado_id )
                {
                    $resultado_query = $sql->query( "
                    DELETE FROM
                        timesheet
                    WHERE
                        tsh_id = '" . $timesheet_selecionado_id . "'
                    ");
                }

                unset( $timesheets_selecionados_ids );
                unset( $timesheet_selecionado_id );
            }
            break;
        case "timesheet_inserir":
            $data_atual = getdate();
            if( $timesheet_dia == "" )
                $timesheet_dia = $data_atual[ 'mday' ];
            if( $timesheet_mes == "" )
                $timesheet_mes = $data_atual[ 'mon' ];
            if( $timesheet_ano == "" )
                $timesheet_ano = $data_atual[ 'year' ];

            $timesheet_data = $timesheet_ano . "-" . $timesheet_mes . "-" . $timesheet_dia;

            $timesheet_tempo = str_replace( ",", ".", $timesheet_tempo );

            if( checkdate( $timesheet_mes, $timesheet_dia, $timesheet_ano ) &&
                $timesheet_tempo != "" &&
                is_numeric( $timesheet_tempo ) &&
                $timesheet_tempo <= 24.0 )
            {
                $resultado_query = $sql->query( "
                INSERT INTO
                    timesheet
                    (
                        are_id,
                        tsa_id,
                        cli_id,
                        evt_id,
                        mem_id,
                        cst_id,
                        pin_id,
                        tat_id,
                        tsh_dt,
                        tsh_duracao,
                        tsh_texto
                    )
                    VALUES
                    (
                        '" . $timesheet_area . "',
                        " . ( $timesheet_subatividade != "" ? $timesheet_subatividade : "NULL" ) . ",
                        " . ( $timesheet_empresa != "" ? $timesheet_empresa : "NULL" ) . ",
                        " . ( $timesheet_evento != "" ? $timesheet_evento : "NULL" ) . ",
                        '" . $_SESSION[ 'membro' ][ 'id' ] . "',
                        " . ( $timesheet_consultoria != "" ? $timesheet_consultoria : "NULL" ) . ",
                        " . ( $timesheet_projeto_interno != "" ? $timesheet_projeto_interno : "NULL" ) . ",
                        '" . $timesheet_atividade . "',
                        '" . $timesheet_data . "',
                        '" . $timesheet_tempo . "',
                        '" . addslashes( $timesheet_comentario ) . "'
                    )" );

                $status_inserir_timesheet = "Timesheet inserido.";
                $timesheet_tempo = "";
                $timesheet_comentario = "";
            }
            else if( $timesheet_tempo == "" )
                $status_inserir_timesheet = "Voce não digitou um tempo.";
            else if( !checkdate( $timesheet_mes, $timesheet_dia, $timesheet_ano ) )
                $status_inserir_timesheet = "Data inválida.";
            else if( !is_numeric( $timesheet_tempo ) || $timesheet_tempo >= 24.0 )
                $status_inserir_timesheet = "O tempo deve ser um valor numérico menor que 24.";

            break;
    }
}


/* ------------------------------ Queries ------------------------------ */

$tsh_count = $sql->squery( "
SELECT DISTINCT
    COUNT( * ) AS quantidade
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
    mem_id = '" . $_SESSION[ 'membro' ][ 'id' ] . "'" );

$n_tsh =  $tsh_count[ 'quantidade' ];
$list_data['qt_paginas_tsh'] = ceil( $n_tsh / QT_POR_PAGINA_DEFAULT );
$_SESSION[ 'paginacao' ][ 'timesheet' ] = ( isset( $_SESSION[ 'paginacao' ][ 'timesheet' ] ) && $_SESSION[ 'paginacao' ][ 'timesheet' ] != "" ? $_SESSION[ 'paginacao' ][ 'timesheet' ] : 1 );
if( $busca_pagina_num_tsh != "" )
{
    $list_data["pagina_num_tsh"] = $busca_pagina_num_tsh;
    $_SESSION[ 'paginacao' ][ 'timesheet' ] = $busca_pagina_num_tsh;
}
else
    $list_data["pagina_num_tsh"] = $_SESSION[ 'paginacao' ][ 'timesheet' ];
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
    mem_id = '" . $_SESSION[ 'membro' ][ 'id' ] . "'
ORDER BY
    tsh_timestamp DESC,
    are_nome
LIMIT " . QT_POR_PAGINA_DEFAULT . "
OFFSET  " . ( $list_data["pagina_num_tsh"] - 1 ) * QT_POR_PAGINA_DEFAULT );

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
WHERE
    are_id = '" . $timesheet_area . "'
ORDER BY
    tat_nome" );

$busca_atividade_info = $sql->squery( "
SELECT DISTINCT
    tat_mne,
    tat_list_emp,
    tat_list_subat
FROM
    ts_atividade
WHERE
    tat_id = '" . $timesheet_atividade . "'" );

$timesheet_atividade_nome = $busca_atividade_info[ 'tat_mne' ];
switch( $timesheet_atividade_nome )
{
    /* -------------- Area: Consultoria --------------- */
    case "atendimento":
        $busca_empresas = $sql->query( "
        SELECT DISTINCT
            cli_id,
            cli_nome
        FROM
            consultoria NATURAL JOIN cliente
        WHERE
            cst_status = '" . CST_NOVA_CONSULTORIA . "' OR
            cst_status = '" . CST_CONSULTORIA_NAO_CONFIRMADA . "' OR
            cst_status = '" . CST_REUNIAO_MARCADA . "' OR
            cst_status = '" . CST_PROPOSTA_EM_ANDAMENTO . "' OR
            cst_status = '" . CST_PROPOSTA_CONCLUIDA . "' OR
            cst_status = '" . CST_REUNIAO_NAO_GEROU_PROPOSTA . "' OR
            cst_status = '" . CST_PROPOSTA_ENVIADA . "'
        ORDER BY
            cli_nome" );
        break;
    case "projeto":
        $busca_empresas = $sql->query( "
        SELECT DISTINCT
            cli_id,
            cli_nome
        FROM
            consultoria NATURAL JOIN  cliente
        WHERE
            cst_status = '" . CST_CONTRATO_EM_ANDAMENTO . "' OR
            cst_status = '" . CST_PROJETO_EM_ANDAMENTO . "'
        ORDER BY
            cli_nome" );
        break;
    case "coordenadoria":
        $busca_empresas = $sql->query( "
        SELECT DISTINCT
            cli_id,
            cli_nome
        FROM
            cliente
        ORDER BY
            cli_nome" );
        break;
    case "reuniao":
        break;

    /* -------------- Area: Marketing --------------- */
    case "eventos":
        $busca_eventos = $sql->query( "
        SELECT DISTINCT
            evt_id,
            evt_edicao,
            tev_nome
        FROM
            evento NATURAL JOIN tipo_evento
        ORDER BY
            tev_nome,
            evt_edicao" );
        break;
    case "projetos_internos":
        $busca_projetos_internos = $sql->query( "
        SELECT DISTINCT
            pin_id,
            pin_nome
        FROM
            prj_interno
        ORDER BY
            pin_nome" );
        break;
    case "fup":
        $busca_consultorias = $sql->query( "
        SELECT DISTINCT
            cst_id,
            cst_nome
        FROM
            consultoria
        WHERE
            cst_status = '" . CST_PROPOSTA_EM_ANDAMENTO . "' OR
            cst_status = '" . CST_PROPOSTA_CONCLUIDA . "' OR
            cst_status = '" . CST_REUNIAO_NAO_GEROU_PROPOSTA . "' OR
            cst_status = '" . CST_PROPOSTA_ENVIADA . "' OR
            cst_status = '" . CST_FOLLOW_UP . "'
        ORDER BY
            cst_nome" );
        break;
    case "comunicacao":
    case "tarefa_interna":
        break;
    /* -------------- Area: R.H. --------------- */

    case "treinamento":
        $busca_projetos_internos = $sql->query( "
        SELECT DISTINCT
            pin_id,
            pin_nome
        FROM
            prj_interno
        ORDER BY
            pin_nome" );
        break;
    case "selecao":
        break;

    /* -------------- Area: EJ-Geral --------------- */

    case "reuniao_gestao":
    case "planejamento":
    case "assembleia":
    case "integracao":
    case "outra_atividade":
        break;

    /* -------------- Area: ADM --------------- */

    case "financeiro":
    case "projeto_interno":
        $busca_projetos_internos = $sql->query( "
        SELECT DISTINCT
            pin_id,
            pin_nome
        FROM
            prj_interno
        ORDER BY
            pin_nome" );
        break;

    /* -------------- Area: Diretoria --------------- */

    case "consultoria":
    case "marketing":
    case "adm":
    case "rh":
    case "presidencia":
        break;

}

/* ------------------------------ Sub-página ------------------------------ */
?>
<?

switch( $subpagina )
{
    case "inserir_novo":
        ?>
        <br /><br />
   <center> 
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="9" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Timesheet - Novo</td>
        </tr>

        <?
        if( isset( $status_inserir_timesheet ) )
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="5">
            <?= $status_inserir_timesheet ?>
            </td>
            </tr>
        <?
        }
        ?>

        <!-- -------------- Data --------------- //-->

        <tr>
        <td bgcolor="#ffffff" class="text">
        Data:
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="timesheet" />
        <input type="hidden" name="subpagina" value="inserir_novo" />
        <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>" />
        <input type="hidden" name="timesheet_atividade" value="<?= $timesheet_atividade ?>">
        <input type="hidden" name="timesheet_empresa" value="<?= ( isset( $timesheet_empresa ) ? $timesheet_empresa : "" ) ?>">
        <input type="hidden" name="timesheet_evento" value="<?= ( isset( $timesheet_evento ) ? $timesheet_evento : "" ) ?>">
        <input type="hidden" name="timesheet_projeto_interno" value="<?= ( isset( $timesheet_projeto_interno ) ? $timesheet_projeto_interno : "" ) ?>">
        <input type="hidden" name="timesheet_consultoria" value="<?= ( isset( $timesheet_consultoria ) ? $timesheet_consultoria : "" ) ?>">
        <input type="hidden" name="timesheet_subatividade" value="<?= ( isset( $timesheet_subatividade ) ? $timesheet_subatividade : "" ) ?>">
        <select name="timesheet_dia" onchange="this.form.submit();">
            <?
            $data_atual = getdate();
            $selecionado = ( $timesheet_dia != "" ? $timesheet_dia : $data_atual[ 'mday' ] );
            for( $dia = 1; $dia <= 31; $dia++ )
            {
            ?>
                <option value="<?= $dia ?>" <?= ( $dia == $selecionado ? "selected" : "" ) ?>><?= $dia ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheet_mes" onchange="this.form.submit();">
            <?
            $data_atual = getdate();
            $selecionado = ( $timesheet_mes != "" ? $timesheet_mes : $data_atual[ 'mon' ] );
            for( $mes = 1; $mes <= 12; $mes++ )
            {
            ?>
                <option value="<?= $mes ?>" <?= ( $mes == $selecionado ? "selected" : "" ) ?>><?= $mes ?></option>
            <?
            }
            ?>
        </select> /
        <select name="timesheet_ano" onchange="this.form.submit();">
            <?
            $data_atual = getdate();
            $selecionado = ( $timesheet_ano != "" ? $timesheet_ano : $data_atual[ 'year' ] );
            for( $ano = ANO_MINIMO; $ano <= ANO_MAXIMO; $ano++ )
            {
            ?>
                <option value="<?= $ano ?>" <?= ( $ano == $selecionado ? "selected" : "" ) ?>><?= $ano ?></option>
            <?
            }
            ?>
        </select>
        </form>
        </td>

        <!-- -------------- Area --------------- //-->

        <td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="timesheet" />
        <input type="hidden" name="subpagina" value="inserir_novo" />
        <input type="hidden" name="timesheet_dia" value="<?= ( isset( $timesheet_dia ) ? $timesheet_dia : "" ) ?>">
        <input type="hidden" name="timesheet_mes" value="<?= ( isset( $timesheet_mes ) ? $timesheet_mes : "" ) ?>">
        <input type="hidden" name="timesheet_ano" value="<?= ( isset( $timesheet_ano ) ? $timesheet_ano : "" ) ?>">
        Área: <select name="timesheet_area" onchange="this.form.submit();">
            <option value="">----------</option>
        <?
        foreach( $busca_areas as $tupla )
        {
        ?>
            <option value="<?= $tupla[ 'are_id' ] ?>" <?= ( ( isset( $timesheet_area ) && $timesheet_area == $tupla[ 'are_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'are_nome' ] ?></option>
        <?
        }
        ?>
        </select>
        </form>
        </td>

        <!-- -------------- Atividade --------------- //-->

        <?
        if( isset( $timesheet_area ) && $timesheet_area != "" )
        {
        ?>
            <td bgcolor="#ffffff" class="text">
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="timesheet" />
            <input type="hidden" name="subpagina" value="inserir_novo" />
            <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>" />
            <input type="hidden" name="timesheet_dia" value="<?= ( isset( $timesheet_dia ) ? $timesheet_dia : "" ) ?>">
            <input type="hidden" name="timesheet_mes" value="<?= ( isset( $timesheet_mes ) ? $timesheet_mes : "" ) ?>">
            <input type="hidden" name="timesheet_ano" value="<?= ( isset( $timesheet_ano ) ? $timesheet_ano : "" ) ?>">
            Atividade:
            <?
            if( is_array( $busca_atividades ) )
            {
            ?>
                <select name="timesheet_atividade" onchange="this.form.submit();">
                    <option value="">----------</option>
                <?
                foreach( $busca_atividades as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'tat_id' ] ?>" <?= ( ( isset( $timesheet_atividade ) && $timesheet_atividade == $tupla[ 'tat_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'tat_nome' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else
            {
            ?>
                <select name="timesheet_atividade" disabled>
                    <option value="">----------</option>
                </select>
            <?
            }
            ?>
            </form>
            </td>
        <?
        }
        ?>

        <!-- -------------- Empresa / Evento --------------- //-->

        <?
        if( $busca_atividade_info[ 'tat_list_emp' ] &&
            isset( $timesheet_area ) &&
            $timesheet_area != "" &&
            isset( $timesheet_atividade ) &&
            $timesheet_atividade != "" )
        {
        ?>
            <td bgcolor="#ffffff" class="text">
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="get">
            <input type="hidden" name="suppagina" value="timesheet" />
            <input type="hidden" name="subpagina" value="inserir_novo" />
            <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>">
            <input type="hidden" name="timesheet_atividade" value="<?= $timesheet_atividade ?>">
            <input type="hidden" name="timesheet_subatividade" value="<?= ( isset( $timesheet_subatividade ) ? $timesheet_subatividade : "" ) ?>">
            <input type="hidden" name="timesheet_dia" value="<?= ( isset( $timesheet_dia ) ? $timesheet_dia : "" ) ?>">
            <input type="hidden" name="timesheet_mes" value="<?= ( isset( $timesheet_mes ) ? $timesheet_mes : "" ) ?>">
            <input type="hidden" name="timesheet_ano" value="<?= ( isset( $timesheet_ano ) ? $timesheet_ano : "" ) ?>">
            <?
            if( isset( $busca_empresas ) && is_array( $busca_empresas ) )
            {
            ?>
                Empresa:
                <select name="timesheet_empresa" onchange="this.form.submit();">
                <option value="">----------</option>
                <?
                foreach( $busca_empresas as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'cli_id' ] ?>" <?= ( ( isset( $timesheet_empresa ) && $timesheet_empresa == $tupla[ 'cli_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'cli_nome' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else if( isset( $busca_eventos ) && is_array( $busca_eventos ) )
            {
            ?>
                Evento:
                <select name="timesheet_evento" onchange="this.form.submit();">
                    <option value="">----------</option>
                <?
                foreach( $busca_eventos as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'evt_id' ] ?>" <?= ( ( isset( $timesheet_evento ) && $timesheet_evento == $tupla[ 'evt_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'tev_nome' ] . ": " . $tupla[ 'evt_edicao' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else if( isset( $busca_projetos_internos ) && is_array( $busca_projetos_internos ) )
            {
            ?>
                Projeto interno:
                <select name="timesheet_projeto_interno" onchange="this.form.submit();">
                    <option value="">----------</option>
                <?
                foreach( $busca_projetos_internos as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'pin_id' ] ?>" <?= ( ( isset( $timesheet_projeto_interno ) && $timesheet_projeto_interno == $tupla[ 'pin_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'pin_nome' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else if( isset( $busca_consultorias ) && is_array( $busca_consultorias ) )
            {
            ?>
                Consultoria:
                <select name="timesheet_consultoria" onchange="this.form.submit();">
                    <option value="">----------</option>
                <?
                foreach( $busca_consultorias as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'cst_id' ] ?>" <?= ( ( isset( $timesheet_consultoria ) && $timesheet_consultoria == $tupla[ 'cst_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'cst_nome' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else
            {
            ?>
                Empresa / Evento:
                <select name="timesheet_empresa_evento" disabled>
                    <option value="">----------</option>
                </select>
            <?
            }
            ?>
            </form>
            </td>
        <?
        }
        ?>

        <!-- -------------- Sub-atividade --------------- //-->

        <?
        if( $busca_atividade_info[ 'tat_list_subat' ] )
        {
        ?>
            <td bgcolor="#ffffff" class="text">
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="timesheet" />
            <input type="hidden" name="subpagina" value="inserir_novo" />
            <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>">
            <input type="hidden" name="timesheet_atividade" value="<?= $timesheet_atividade ?>">
            <input type="hidden" name="timesheet_empresa" value="<?= ( isset( $timesheet_empresa ) ? $timesheet_empresa : "" ) ?>">
            <input type="hidden" name="timesheet_evento" value="<?= ( isset( $timesheet_evento ) ? $timesheet_evento : "" ) ?>">
            <input type="hidden" name="timesheet_projeto_interno" value="<?= ( isset( $timesheet_projeto_interno ) ? $timesheet_projeto_interno : "" ) ?>">
            <input type="hidden" name="timesheet_consultoria" value="<?= ( isset( $timesheet_consultoria ) ? $timesheet_consultoria : "" ) ?>">
            <input type="hidden" name="timesheet_dia" value="<?= ( isset( $timesheet_dia ) ? $timesheet_dia : "" ) ?>">
            <input type="hidden" name="timesheet_mes" value="<?= ( isset( $timesheet_mes ) ? $timesheet_mes : "" ) ?>">
            <input type="hidden" name="timesheet_ano" value="<?= ( isset( $timesheet_ano ) ? $timesheet_ano : "" ) ?>">
            Sub-atividade:
            <?
            if( isset( $timesheet_area ) &&
                $timesheet_area != "" &&
                isset( $timesheet_atividade ) &&
                $timesheet_atividade != "" )
            {
                $busca_subatividades = $sql->query( "
                SELECT DISTINCT
                    tsa_id,
                    tsa_nome
                FROM
                    ts_subatividade
                    NATURAL JOIN tat_tsa
                WHERE
                    tat_id = '" . $timesheet_atividade . "'
                ORDER BY
                    tsa_nome" );
            ?>
                <select name="timesheet_subatividade" onchange="this.form.submit();">
                    <option value="">----------</option>
                <?
                foreach( $busca_subatividades as $tupla )
                {
                ?>
                    <option value="<?= $tupla[ 'tsa_id' ] ?>" <?= ( ( isset( $timesheet_subatividade ) && $timesheet_subatividade == $tupla[ 'tsa_id' ] ) ? "selected" : "" ) ?>><?= $tupla[ 'tsa_nome' ] ?></option>
                <?
                }
                ?>
                </select>
            <?
            }
            else
            {
            ?>
                <select name="timesheet_subatividades" disabled>
                    <option value="">----------</option>
                </select>
            <?
            }
            ?>
            </form>
            </td>
        <?
        }
        ?>

        <!-- -------------- Tempo --------------- //-->

        <?
        if( isset( $timesheet_area ) &&
            $timesheet_area != "" &&
            isset( $timesheet_atividade ) &&
            $timesheet_atividade != "" )
        {
        ?>
            </tr>
            <tr>
            <td bgcolor="#FFFFFF" class="text">
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="timesheet" />
            <input type="hidden" name="subpagina" value="inserir_novo" />
            <input type="hidden" name="acao" value="timesheet_inserir" />
            <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>" />
            <input type="hidden" name="timesheet_atividade" value="<?= $timesheet_atividade ?>">
            <input type="hidden" name="timesheet_empresa" value="<?= ( isset( $timesheet_empresa ) ? $timesheet_empresa : "" ) ?>">
            <input type="hidden" name="timesheet_evento" value="<?= ( isset( $timesheet_evento ) ? $timesheet_evento : "" ) ?>">
            <input type="hidden" name="timesheet_projeto_interno" value="<?= ( isset( $timesheet_projeto_interno ) ? $timesheet_projeto_interno : "" ) ?>">
            <input type="hidden" name="timesheet_consultoria" value="<?= ( isset( $timesheet_consultoria ) ? $timesheet_consultoria : "" ) ?>">
            <input type="hidden" name="timesheet_subatividade" value="<?= ( isset( $timesheet_subatividade ) ? $timesheet_subatividade : "" ) ?>">
            <input type="hidden" name="timesheet_dia" value="<?= ( isset( $timesheet_dia ) ? $timesheet_dia : "" ) ?>">
            <input type="hidden" name="timesheet_mes" value="<?= ( isset( $timesheet_mes ) ? $timesheet_mes : "" ) ?>">
            <input type="hidden" name="timesheet_ano" value="<?= ( isset( $timesheet_ano ) ? $timesheet_ano : "" ) ?>">
            Tempo: <input type="text" name="timesheet_tempo" size="4" value="<?= ( isset( $timesheet_tempo ) ? $timesheet_tempo : "" ) ?>">
            </td>
            <td bgcolor="#FFFFFF" class="text" COLSPAN="4">
            Comentarios: <textarea cols="20" rows="10" wrap="hard" name="timesheet_comentario"><?= ( isset( $timesheet_comentario ) ? $timesheet_comentario : "" ) ?></textarea>
            </td>
            </tr>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="9">
            <input type="submit" value="Inserir">
            <input type="button" value="Cancelar" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=timesheet'" />
            </form>
            </td>
        <?
        }
        else
        {
        ?>
            </tr>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="9">
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="button" value="Cancelar" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=timesheet'" />
            </form>
            </td>
        <?
        }
        ?>
        </tr>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="9">&nbsp;</td>
        </tr>        
        </table>
        </table>
        <?
        break;
    default:
    ?>
        <br /><br />
   <center> 
<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
   <tr><td>        
     <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="<?= ( is_array( $busca_timesheets ) ? "8" : "1" ) ?>" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Timesheet</td>
        </tr>
    <?

    /* ------------------------------ Lista de timesheets ------------------------------ */

    if( is_array( $busca_timesheets ) )
    {
    ?>
        <tr>
        <td bgcolor="#ffffff" class="text">&nbsp;
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="timesheet" />
        <input type="hidden" name="acao" value="remover" />
        <input type="hidden" name="timesheet_area" value="<?= $timesheet_area ?>" />
        <input type="hidden" name="timesheet_atividade" value="<?= $timesheet_atividade ?>">
        <input type="hidden" name="timesheet_empresa" value="<?= ( isset( $timesheet_empresa ) ? $timesheet_empresa : "" ) ?>">
        <input type="hidden" name="timesheet_evento" value="<?= ( isset( $timesheet_evento ) ? $timesheet_evento : "" ) ?>">
        <input type="hidden" name="timesheet_projeto_interno" value="<?= ( isset( $timesheet_projeto_interno ) ? $timesheet_projeto_interno : "" ) ?>">
        <input type="hidden" name="timesheet_consultoria" value="<?= ( isset( $timesheet_consultoria ) ? $timesheet_consultoria : "" ) ?>">
        <input type="hidden" name="timesheet_subatividade" value="<?= ( isset( $timesheet_subatividade ) ? $timesheet_subatividade : "" ) ?>">
        <input type="hidden" name="timesheet_tempo" value="<?= ( isset( $timesheet_tempo ) ? $timesheet_tempo : "" ) ?>">
        <input type="hidden" name="timesheet_comentario" value="<?= ( isset( $timesheet_comentario ) ? $timesheet_comentario : "" ) ?>">
        </td>
        <td bgcolor="#ffffff" class="text"><b>Data</b></td>
        <td bgcolor="#ffffff" class="text"><b>Área</b></td>
        <td bgcolor="#ffffff" class="text"><b>Atividade</b></td>
        <td bgcolor="#ffffff" class="text"><b>Empresa / Evento</b></td>
        <td bgcolor="#ffffff" class="text"><b>Sub-atividade</b></td>
        <td bgcolor="#ffffff" class="text"><b>Tempo (H)</b></td>
        <td bgcolor="#ffffff" class="text"><b>Observação</b></td>
        </tr>

        <?
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
            <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" class="caixa" name="timesheets_selecionados_ids[]" value="<?= $tupla[ 'tsh_id' ] ?>" /></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsh_timestamp' ] ) ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'are_nome' ] ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tat_nome' ] ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $empresa_evento ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsa_nome' ] ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsh_duracao' ] ?></td>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= nl2br( $tupla[ 'tsh_texto' ] ) ?></td>
            </tr>
        <?
        }
	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_tsh'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="8" bgcolor="#ffffff">
	        <?
		 
		/* se a pagina atual for maior que 1, mostrar seta pra voltar */
		if( $list_data['pagina_num_tsh'] > 1 )
                {
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=timesheet&busca_pagina_num_tsh=<?= ($list_data["pagina_num_tsh"] - 1) ?>"><font color="#ff8000">&lt;&lt;</font></a>
                    <?
	        }
    
	       for ($i = 1; $i <= $list_data["qt_paginas_tsh"]; $i++)
	       { 
		   if ($i == $list_data["pagina_num_tsh"]) 
	               print ($i);
		   else
   		   {
                       ?>
                       <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=timesheet&busca_pagina_num_tsh=<?= $i ?>"><font color="#ff8000"><?= $i ?></font></a>
                       <? 
		   } 
	       }

               /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
               if( $list_data['qt_paginas_tsh'] > $list_data['pagina_num_tsh'] )
               {
                   ?>
                   <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=timesheet&busca_pagina_num_tsh=<?= ($list_data["pagina_num_tsh"] + 1) ?>"><font color="#ff8000">&gt;&gt;</font></a>
                   <?
               }
               ?>
               </td>
               </tr>
               <?
	    }

	?>
        <tr>
        <td  bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_timesheets ) ? "8" : "1" ) ?>">
            <input type="submit" value="Apagar" />
            <input type="button" value="Inserir" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=timesheet&subpagina=inserir_novo'" />
            <br />
            <br />
            <b><font color='#ff0000' size='1' face='verdana, sans-serif, helvetica'>Atenção: </font></b>
            <font color='#000000' size='1' face='verdana, sans-serif, helvetica'>Nunca apague seus TimeSheets, a não ser que tenha errado.</font>
            <br />
        </form>
        </td>
        </tr>
    <?
    }
    else
    {
    ?>
        <tr>
        <td bgcolor="#ffffff" class="text">Não há nenhum timesheet.</td>
        </tr>
        <tr>
        <td  bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="button" value="Inserir" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=timesheet&subpagina=inserir_novo'" />
        </form>
        </td>
        </tr>
    <?
    }
    ?>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="<?= ( is_array( $busca_timesheets ) ? "8" : "1" ) ?>">&nbsp;</td>
        </tr>    
<?
}
?>
    
         </table>
       </td></tr>
      </table></center><BR><BR> 
