<?
include_once( "include/funcoes.php" );


/* ------------------------------ Ações ------------------------------ */

extract_request_var( "acao", $acao );
if( isset( $acao ) )
{
    switch( $acao )
    {
        case "alterar_aluno_reprovado":
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

extract_request_var( "processo_seletivo_id", $processo_seletivo_id );
extract_request_var( "fase_dinamica", $fase_dinamica );

$busca_processos_cadastrados = $sql->query( "
SELECT
    psl_id,
    date_part( 'epoch', psl_dt_selecao ) AS psl_timestamp,
    date_part( 'epoch', psl_dt_inc ) AS psl_dt_inc_timestamp
FROM
    p_seletivo
ORDER BY
    psl_timestamp DESC" );

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
<td class="textwhitemini" colspan="7" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Gerência de Reprovados (Feedback)</td>
</tr>
<tr>
<td bgcolor="#ffffff" class="text">Processo Seletivo:
<form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
<input type="hidden" name="suppagina" value="rh" />
<input type="hidden" name="pagina" value="feedback" />
</td>
<td bgcolor="#ffffff" class="text" colspan="6">
<select name="processo_seletivo_id">
<?
    if( is_array( $busca_processos_cadastrados ) )
    {
        foreach( $busca_processos_cadastrados as $processo_seletivo )
        {
        ?>
            <option value="<?= $processo_seletivo[ 'psl_id' ] ?>" <?= ( $processo_seletivo_id == $processo_seletivo[ 'psl_id' ] ? "selected" : "" ) ?>>
            <?= ( date( "m", $processo_seletivo[ 'psl_timestamp' ] ) > 6 ? "2" : "1" ) . "/" . date( "Y", $processo_seletivo[ 'psl_timestamp' ] ) ?>
            (<?= date( "d/m/Y", $processo_seletivo[ 'psl_dt_inc_timestamp' ] ) ?>)
            </option>
        <?
        }
    }
?>
</select>
</td>
</tr>
<tr>
<td bgcolor="#ffffff" class="text">Fase:</td>
<td bgcolor="#ffffff" class="text" colspan="6">
<select name="fase_dinamica">
    <option value="1" <?= ( $fase_dinamica == 1 ? "selected" : "" ) ?>>Primeira</option>
    <option value="2" <?= ( $fase_dinamica == 2 ? "selected" : "" ) ?>>Segunda</option>
    <option value="3" <?= ( $fase_dinamica == 3 ? "selected" : "" ) ?>>Entrevista</option>
</select>
</td>
</tr>
<tr>
<td bgcolor="#ffffff" class="text" colspan="7">
<input type="submit" value="Consultar">
</form>
</td>
</tr>
<?
if( is_array( $busca_alunos_reprovados ) )
{
?>
    <tr>
    <td bgcolor="#ffffff" class="text">&nbsp;
    <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post" name="form_alunos_reprovados">
    <input type="hidden" name="suppagina" value="rh" />
    <input type="hidden" name="pagina" value="feedback" />
    <input type="hidden" name="acao" value="alterar_aluno_reprovado" />
    <input type="hidden" name="processo_seletivo_id" value="<?= $processo_seletivo_id ?>" />
    <input type="hidden" name="fase_dinamica" value="<?= $fase_dinamica ?>" />
    </td>
    <td bgcolor="#ffffff" class="text"><b>Aluno</b></td>
    <td bgcolor="#ffffff" class="text"><b>Solicitou Feedback?</b></td>
    <td bgcolor="#ffffff" class="text"><b>Data Feedback</b></td>
    <td bgcolor="#ffffff" class="text"><b>Hora</b></td>
    <td bgcolor="#ffffff" class="text"><b>Consultor</b></td>
    <td bgcolor="#ffffff" class="text"><b>Realizado?</b></td>
    </tr>

    <?
    $i = 5;
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
    <td  bgcolor="#ffffff" class="text" colspan="9">
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
    <td bgcolor="#ffffff" class="text" colspan="9">Não há nenhum aluno reprovado nesta fase deste processo seletivo.</td>
    </tr>
<?
}
?>
        <tr>
          <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="9">&nbsp;</td>
        </tr>        
         </table>
       </td></tr>
      </table></center><BR><BR> 
