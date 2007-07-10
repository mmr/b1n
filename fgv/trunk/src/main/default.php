<? /* $Id: default.php,v 1.25 2002/05/07 16:03:17 binary Exp $ */ ?>

<?
include_once( INCPATH . "/funcoes.php" );


/* ------------------------------ Ações ------------------------------ */

extract_request_var( "acao", $acao );
if( isset( $acao ) )
{
    extract_request_var( "task", $task );

    switch( $acao )
    {
        case "Alterar":
            if( is_array( $task ) )
            {
                foreach( $task as $task_id )
                {
                    extract_request_var( "status" . $task_id, $status_id );

                    $resultado_query = $sql->query( "
                    UPDATE
                        task
                    SET
                        stt_id = " . $status_id . "
                    WHERE
                        tsk_id = '" . $task_id . "'" );

		    $query = "
                        SELECT
                            tsk_gemea
                        FROM
                            task
                        WHERE
                            tsk_id = '" . $task_id . "'";

		    $task_gemea = $sql->squery( $query );

		    if( is_array( $task_gemea ) )
		    {
		        $query = "
		        UPDATE
                            task
                        SET
                            stt_id = " . $status_id . "
                        WHERE
                            tsk_id = '" . $task_gemea[ 'tsk_gemea' ] . "'";

		        $rq = $sql->query( $query );
		    }
		}

                unset( $task );
                unset( $task_id );
                unset( $status_id );
            }
	        break;
        case "Remover":
            if( is_array( $task ) )
            {
                foreach( $task as $task_id )
                {
                    $busca_baixada = $sql->squery( "
                    SELECT
                        stt_id
                    FROM
                        status_task
                    WHERE
                        stt_nome = '" . $status_baixada . "'" );
		    
		    $query = "
                        SELECT
                            tsk_gemea
                        FROM
                            task
                        WHERE
                            tsk_id = '" . $task_id . "'";

		    $task_gemea = $sql->squery( $query );

		    if( is_array( $task_gemea ) && is_array( $busca_baixada ) )
		    {
			$status_baixada_id =  $busca_baixada[ 'stt_id' ];
			
			$query = "
		        UPDATE
                            task
                        SET
                            stt_id = " . $status_baixada_id . "
                        WHERE
                            tsk_id = '" . $task_gemea[ 'tsk_gemea' ] . "'";

		        $rq = $sql->query( $query );
		    }

                    $sql->query( "
                    DELETE FROM
                        task
                    WHERE
                        tsk_id = " . $task_id );
		    
		}

                unset( $task );
                unset( $task_id );
            }
	        break;
        case "Arquivar":
            if( is_array( $task ) )
            {
                foreach( $task as $task_id )
                {
                    $busca_arquivada = $sql->squery( "
                    SELECT
                        stt_id
                    FROM
                        status_task
                    WHERE
                        stt_nome = '" . STATUS_ARQUIVADA . "'" );

                    $busca_baixada = $sql->squery( "
                    SELECT
                        stt_id
                    FROM
                        status_task
                    WHERE
                        stt_nome = '" . $status_baixada . "'" );

                    $sql->query( "
                    UPDATE
                        task
                    SET
                        stt_id = '" . $busca_arquivada[ 'stt_id' ] . "'
                    WHERE
                        tsk_id = '" . $task_id . "' AND
                        stt_id = '" . $busca_baixada[ 'stt_id' ] . "'" );
                }

                unset( $task );
                unset( $task_id );
            }
            break;
        case "nova_task":
            extract_request_var( "id_membro_para", $id_membro_para );
            extract_request_var( "id_tipo_task", $id_tipo_task );
            extract_request_var( "assunto_task", $assunto_task );
            extract_request_var( "mensagem_task", $mensagem_task );

            $busca_status_em_aberto = $sql->squery( "
            SELECT
                stt_id
            FROM
                status_task
            WHERE
                stt_nome = '" . $status_em_aberto . "'" );

            $id_status_task = $busca_status_em_aberto[ 'stt_id' ];
            $id_membro_de = $_SESSION[ 'membro' ][ 'id' ];

	    if( $id_membro_para != "" )
	    {
		$enviar_membros[ 0 ][ 'mem_id' ] = $id_membro_para;
	    }
	    else
	    {
		$enviar_membros = $sql->query( "
                    SELECT DISTINCT
                        mem_id
                    FROM
                        membro_vivo" );
	    }
	    
            if( $assunto_task != "" && $mensagem_task != "" && is_array( $enviar_membros ) )
            {
                $assunto_task = addslashes( $assunto_task );
                $mensagem_task = addslashes( $mensagem_task );

		foreach( $enviar_membros as $enviar_membro )
		{
		    $rs = $sql->squery("SELECT nextval('task_tsk_id_seq')");

		    if( is_array( $rs ) )
		    {
			$resultado_query = $sql->query( "
                INSERT INTO
                    task
                    (
                        tsk_id,
                        ttk_id,
                        stt_id,
                        mem_id_de,
                        mem_id_para,
                        tsk_acao,
                        tsk_assunto,
                        tsk_mensagem
                    )
                    VALUES
                    (
                        '" . $rs[ 'nextval' ] . "',
                        '" . $id_tipo_task . "',
                        '" . $id_status_task . "',
                        '" . $id_membro_de . "',
                        '" . $enviar_membro[ 'mem_id' ] . "',
                        '0',
                        '" . $assunto_task . "',
                        '" . $mensagem_task . "'
                    )" );

			$resultado_query = $sql->query( "
                INSERT INTO
                    task
                    (
                        tsk_gemea,
                        ttk_id,
                        stt_id,
                        mem_id_de,
                        mem_id_para,
                        tsk_acao,
                        tsk_assunto,
                        tsk_mensagem
                    )
                    VALUES
                    (
                        '" . $rs[ 'nextval' ] . "',
                        '" . $id_tipo_task . "',
                        '" . $id_status_task . "',
                        '" . $id_membro_de . "',
                        '" . $enviar_membro[ 'mem_id' ] . "',
                        '1',
                        '" . $assunto_task . "',
                        '" . $mensagem_task . "'
                    )" );
		    }
		
		    $status_envio_mensagem = "Task enviada.";
		}
		
                unset( $id_membro_para );
                unset( $id_tipo_task );
                unset( $assunto_task );
                unset( $mensagem_task );
            }
            else if( $assunto_task == "" )
                $status_envio_mensagem = "Você não digitou um assunto.";
            else if( $mensagem_task == "" )
                $status_envio_mensagem = "Você não digitou uma mesagem.";
            break;
    }
}

/* ------------------------------ Querys ------------------------------ */

$busca_status_tasks = $sql->query( "
SELECT
    stt_id,
    stt_nome
FROM
    status_task
WHERE
    stt_nome != '" . STATUS_ARQUIVADA . "'
ORDER BY
    stt_nome" );

$busca_status_em_aberto = $sql->query( "
SELECT
    stt_id,
    stt_nome
FROM
    status_task
WHERE
    stt_nome = '" . STATUS_EM_ABERTO . "'" );

$busca_status_baixada = $sql->query( "
SELECT
    stt_id,
    stt_nome
FROM
    status_task
WHERE
    stt_nome = '" . STATUS_BAIXADA . "'" );

$busca_status_arquivada = $sql->query( "
SELECT
    stt_id,
    stt_nome
FROM
    status_task
WHERE
    stt_nome = '" . STATUS_ARQUIVADA . "'" );


/* ------------------------------ Sub-página ------------------------------ */

extract_request_var( "subpagina", $subpagina );
$subpagina = urldecode( $subpagina );

if( !is_array( $busca_status_em_aberto ) ||
    !is_array( $busca_status_baixada )   ||
    !is_array( $busca_status_arquivada ) )
    {
        $subpagina = "cadastro_status";
    }

switch( $subpagina )
{
    case "cadastro_status":
        ?>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="9" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Cadastre Status para as Tasks</td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">Para que a Task List possa ser utilizada são necessários pelo menos três status de task cadastrados: "Em aberto", "Baixada" e "Arquivada".</td>
        </tr>
        </table>
        <?
        break;
    case "Nova Task":
        $busca_membros = $sql->query( "
        SELECT
            mem_id,
            mem_nome
        FROM
            membro_vivo
        ORDER BY
            mem_nome" );

        $busca_tipos_tasks = $sql->query( "
        SELECT
            ttk_id,
            ttk_nome
        FROM
            tipo_task
        WHERE
            ttk_nome != 'Task do Sistema'
        ORDER BY
            ttk_nome" );
        ?>

        <br /><br />
        <center>
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="9" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Nova Task</td>
        </tr>
        <? if( isset( $status_envio_mensagem ) )
        {
            ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;<?= $status_envio_mensagem ?></td>
            </tr>
            <?
        }
        ?>
	<tr>
	<td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="acao" value="nova_task">
        <input type="hidden" name="subpagina" value="Nova Task">
        Para: <? faz_select( "id_membro_para", $busca_membros, "mem_id", "mem_nome", ( isset( $id_membro_para ) ? $id_membro_para : "" ), "", "true", "Todos os Membros" ); ?>
        Tipo: <? faz_select( "id_tipo_task", $busca_tipos_tasks, "ttk_id", "ttk_nome", ( isset( $id_tipo_task ) ? $id_tipo_task : "" ) ); ?>
        </td bgcolor="#ffffff" class="text">
        </tr>
	<tr>
	<td bgcolor="#ffffff" class="text">Assunto: <input type="text" name="assunto_task" value="<?= ( isset( $assunto_task ) ? $assunto_task : "" ) ?>" maxlength="50"></td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">Texto: <textarea cols="55" rows="13" wrap="hard" name="mensagem_task"><?= ( isset( $mensagem_task ) ? $mensagem_task : "" ) ?></textarea></td>
        </tr>
	<tr>
	<td bgcolor="#ffffff" class="text">
        <input type="button" value="<< Voltar" onclick="location='<?= $_SERVER['SCRIPT_NAME'] ?>?subpagina=home'">
        <input type="submit" value="Enviar Task">
        </form>
        </td>
        </tr>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="9">&nbsp;</td>
        </tr>        
        </table>
        </td></tr>
        </table></center><BR><BR>
        <?
        break;
    case "Ver Task":
        extract_request_var( "task_id", $task_id );
        extract_request_var( "origem", $origem );
        extract_request_var( "sys", $sys );

	$task_id = urldecode( $task_id );
        $origem = urldecode( $origem );

        if( $sys == "yeah" )
            $busca_task = $sql->squery( "
            SELECT
                task.tsk_id,
                task.ttk_id,
                task.stt_id,
                'Sistema' AS mem_de,
                para.mem_nome AS mem_para,
                status_task.stt_nome AS tsk_status,
                date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
                tsk_assunto,
                tsk_mensagem,
                ttk_nome AS tsk_tipo
            FROM
                task,
                membro_vivo para,
                status_task,
                tipo_task
            WHERE
                mem_id_para = para.mem_id  AND
                task.stt_id = status_task.stt_id AND
                task.ttk_id = tipo_task.ttk_id AND
                task.tsk_id = '" . $task_id . "'" );
        else
            $busca_task = $sql->squery( "
            SELECT
                task.tsk_id,
                task.ttk_id,
                task.stt_id,
                de.mem_nome AS mem_de,
                para.mem_nome AS mem_para,
                status_task.stt_nome AS tsk_status,
                date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
                tsk_assunto,
                tsk_mensagem,
                ttk_nome AS tsk_tipo
            FROM
                task,
                membro_vivo de,
                membro_vivo para,
                status_task,
                tipo_task
            WHERE
                mem_id_de = de.mem_id AND
                mem_id_para = para.mem_id  AND
                task.stt_id = status_task.stt_id AND
                task.ttk_id = tipo_task.ttk_id AND
                task.tsk_id = '" . $task_id . "'" );

        ?>
        <br /><br />
        <center>
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="2" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Detalhes da Task</td>
        </tr>
	<tr>
	<td bgcolor="#ffffff" class="text">Assunto:</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= $busca_task[ 'tsk_assunto' ] ?></td>
        </tr>
        <tr>
	<td bgcolor="#ffffff" class="text">De:</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= $busca_task[ 'mem_de' ] ?></td>
        </tr>
        <tr>
	<td bgcolor="#ffffff" class="text">Para:</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= $busca_task[ 'mem_para' ] ?></td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">Data</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $busca_task[ 'tsk_timestamp' ] ) ?></td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">Hora</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $busca_task[ 'tsk_timestamp' ] ) ?></td>
        </tr>
        <tr>
	<td bgcolor="#ffffff" class="text">Status:</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= $busca_task[ 'tsk_status' ] ?></td>
        </tr>
        <tr>
	<td bgcolor="#ffffff" class="text">Tipo:</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= $busca_task[ 'tsk_tipo' ] ?></td>
        </tr>
        <tr>
        <td bgcolor="#ffffff" class="text">Mensagem</td>
	<td bgcolor="#ffffff" class="text">&nbsp;<?= nl2br( $busca_task[ 'tsk_mensagem' ] ) ?></td>
        </tr>
        </table>
        </td></tr>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="2">&nbsp;</td>
        </tr>       
        </table></center>

        <br /><br />
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="button" value="<< Voltar" onclick="location='<?= $_SERVER['SCRIPT_NAME'] ?>?subpagina=<?= $origem ?>'">
        </form>
        <?
        break;
    case "Arquivo de Tasks":
	extract_request_var( "busca_pagina_num_rec_arq", $busca_pagina_num_rec_arq );
	extract_request_var( "busca_pagina_num_env_arq", $busca_pagina_num_env_arq );

	$busca_tasks_recebidas_arquivadas_count_1 = $sql->squery( "
        SELECT
            COUNT( * ) AS quantidade
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '1' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome = '" . STATUS_ARQUIVADA . "'" );

	$busca_tasks_recebidas_arquivadas_count_2 = $sql->squery( "	
            SELECT
                COUNT( * ) AS quantidade
            FROM
                task,
                membro_vivo para,
                status_task
            WHERE
                tsk_acao = '1' AND
                mem_id_de = NULL AND
                mem_id_para = para.mem_id AND
                mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
                task.stt_id = status_task.stt_id AND
                status_task.stt_nome = '" . STATUS_ARQUIVADA . "'" );	

	$n_tasks_recebidas_arquivadas =  $busca_tasks_recebidas_arquivadas_count_1[ 'quantidade' ] + $busca_tasks_recebidas_arquivadas_count_2[ 'quantidade' ];
	$list_data['qt_paginas_rec_arq'] = ceil( $n_tasks_recebidas_arquivadas / QT_POR_PAGINA_DEFAULT );
	$_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ] = ( isset( $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ] ) && $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ] != "" ? $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ] : 1 );
	if( $busca_pagina_num_rec_arq != "" )
	{
	    $list_data["pagina_num_rec_arq"] = $busca_pagina_num_rec_arq;
	    $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ] = $busca_pagina_num_rec_arq;
	}
	else
	    $list_data["pagina_num_rec_arq"] = $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_recebidas' ];	
	if( $list_data["pagina_num_rec_arq"] > $list_data['qt_paginas_rec_arq'] )
	    $list_data["pagina_num_rec_arq"] = $list_data['qt_paginas_rec_arq'];
	if(  $list_data["pagina_num_rec_arq"] <= 0 )
	    $list_data["pagina_num_rec_arq"] = 1;
	
        $busca_tasks_enviadas_arquivadas_count = $sql->squery( "
        SELECT
            COUNT( * ) AS quantidade
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '0' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_de = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome = '" . STATUS_ARQUIVADA . "'" );

	$n_tasks_enviadas_arquivadas =  $busca_tasks_enviadas_arquivadas_count[ 'quantidade' ];
	$list_data['qt_paginas_env_arq'] = ceil( $n_tasks_enviadas_arquivadas / QT_POR_PAGINA_DEFAULT );

	$_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ] = ( isset( $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ] ) && $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ] != "" ? $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ] : 1 );
	if( $busca_pagina_num_env_arq != "" )
	{
	    $list_data["pagina_num_env_arq"] = $busca_pagina_num_env_arq;
	    $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ] = $busca_pagina_num_env_arq;
	}
	else
	    $list_data["pagina_num_env_arq"] = $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'arquivo_enviadas' ];		
	if( $list_data["pagina_num_env_arq"] > $list_data['qt_paginas_env_arq'] || $list_data["pagina_num_env_arq"] <= 0 )
	    $list_data["pagina_num_env_arq"] = $list_data['qt_paginas_env_arq'];
	if(  $list_data["pagina_num_env_arq"] <= 0 )
	    $list_data["pagina_num_env_arq"] = 1;
	
	$busca_tasks_recebidas_arquivadas = $sql->query( "
        SELECT
            task.tsk_id,
            ttk_id,
            task.stt_id,
            de.mem_nome AS mem_de,
            para.mem_nome AS mem_para,
            status_task.stt_nome AS tsk_status,
            date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
            tsk_assunto,
            tsk_mensagem
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '1' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome = '" . STATUS_ARQUIVADA . "'
        UNION
        (
            SELECT
                task.tsk_id,
                ttk_id,
                task.stt_id,
                'Sistema' AS mem_de,
                para.mem_nome AS mem_para,
                status_task.stt_nome AS tsk_status,
                date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
                tsk_assunto,
                tsk_mensagem
            FROM
                task,
                membro_vivo para,
                status_task
            WHERE
                tsk_acao = '1' AND
                mem_id_de = NULL AND
                mem_id_para = para.mem_id AND
                mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
                task.stt_id = status_task.stt_id AND
                status_task.stt_nome = '" . STATUS_ARQUIVADA . "'
        )
        ORDER BY
            tsk_timestamp DESC
        LIMIT " . QT_POR_PAGINA_DEFAULT . "
        OFFSET  " . ( $list_data["pagina_num_rec_arq"] - 1 ) * QT_POR_PAGINA_DEFAULT );
	
        $busca_tasks_enviadas_arquivadas = $sql->query( "
        SELECT
            task.tsk_id,
            ttk_id,
            task.stt_id,
            de.mem_nome AS mem_de,
            para.mem_nome AS mem_para,
            status_task.stt_nome AS tsk_status,
            date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
            tsk_assunto,
            tsk_mensagem
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '0' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_de = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome = '" . STATUS_ARQUIVADA . "'
        ORDER BY
            tsk_timestamp DESC
        LIMIT " . QT_POR_PAGINA_DEFAULT . "
        OFFSET  " . ( $list_data["pagina_num_env_arq"] - 1 ) * QT_POR_PAGINA_DEFAULT );
        ?>

        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="subpagina" value="Arquivo de Tasks">
	<input type="hidden" name="busca_pagina_num_env_arq" value="<?= $busca_pagina_num_env_arq ?>">
	<input type="hidden" name="busca_pagina_num_rec_arq" value="<?= $busca_pagina_num_rec_arq ?>">
	     
        <br /><br />
        <center>
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="7" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Arquivo de Tasks - Recebidas</td>
        </tr>
        <?
        if( is_array( $busca_tasks_recebidas_arquivadas ) )
        {
        ?>
	    <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;</td>
            <td bgcolor="#ffffff" class="text">No</td>
            <td bgcolor="#ffffff" class="text">Data</td>
            <td bgcolor="#ffffff" class="text">Hora</td>
	    <td bgcolor="#ffffff" class="text">De</td>
            <td bgcolor="#ffffff" class="text">Assunto</td>
	    <td bgcolor="#ffffff" class="text">Mensagem</td>
            </tr>
            <?
            $numero_mensagem = 1 + ( $list_data["pagina_num_rec_arq"] - 1 ) * QT_POR_PAGINA_DEFAULT;
            foreach( $busca_tasks_recebidas_arquivadas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="botao" name="task[]" value="<?= $tupla[ 'tsk_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $numero_mensagem ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_de' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsk_assunto' ] ?></td>
                <td bgcolor="#ffffff" class="text"><?= "<a href=\"" . $_SERVER[ 'SCRIPT_NAME' ] . "?subpagina=" . urlencode( "Ver Task" ) . "&task_id=" . urlencode( $tupla[ 'tsk_id' ] ) . "&origem=" . urlencode( "Arquivo de Tasks" ) . ( ( $tupla[ 'mem_de' ] == "Sistema" ) ? "&sys=yeah" : "" ) . "\">" .

                ( strlen( $tupla[ 'tsk_mensagem' ] ) < 20 ? $tupla[ 'tsk_mensagem' ] : substr( $tupla[ 'tsk_mensagem' ], 0, 20 ) . "..." ) . "</a>" ?></td>
                </tr>
            <?
            $numero_mensagem++;
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_rec_arq'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="7" bgcolor="#ffffff">
	        <?
		 
		/* se a pagina atual for maior que 1, mostrar seta pra voltar */
		if( $list_data['pagina_num_rec_arq'] > 1 )
                {
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_rec_arq=<?= ($list_data["pagina_num_rec_arq"] - 1) ?>&busca_pagina_num_env_arq=<?= $list_data["pagina_num_env_arq"] ?>"><font color="#ff8000">&lt;&lt;</font></a>
                    <?
	        }
    
	       for ($i = 1; $i <= $list_data["qt_paginas_rec_arq"]; $i++)
	       { 
		   if ($i == $list_data["pagina_num_rec_arq"]) 
	               print ($i);
		   else
   		   {
                       ?>
                       <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_rec_arq=<?= $i ?>&busca_pagina_num_env_arq=<?= $list_data["pagina_num_env_arq"] ?>"><font color="#ff8000"><?= $i ?></font></a>
                       <? 
		   } 
	       }

               /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
               if( $list_data['qt_paginas_rec_arq'] > $list_data['pagina_num_rec_arq'] )
               {
                   ?>
                   <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_rec_arq=<?= ($list_data["pagina_num_rec_arq"] + 1) ?>&busca_pagina_num_env_arq=<?= $list_data["pagina_num_env_arq"] ?>"><font color="#ff8000">&gt;&gt;</font></a>
                   <?
               }
               ?>
               </td>
               </tr>
               <?
	    }

            ?>
	    <tr>
	    <td bgcolor="#ffffff" class="text" colspan="7">
	    <input type="submit" name="acao" value="Remover" />
	    </td>
            </tr>
	    <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">Não há nenhuma task recebida no arquivo.</td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="7">&nbsp;</td>
        </tr>         
        </table>
        </td></tr>
        </table>
        </form>

        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="subpagina" value="Arquivo de Tasks">
	<input type="hidden" name="busca_pagina_num_env_arq" value="<?= $busca_pagina_num_env_arq ?>">
	<input type="hidden" name="busca_pagina_num_rec_arq" value="<?= $busca_pagina_num_rec_arq ?>">
	<br /><br />
        
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="7" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Arquivo de Tasks - Enviadas</td>
        </tr>
        <?
        if( is_array( $busca_tasks_enviadas_arquivadas ) )
        {
        ?>
	    <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;</td>
            <td bgcolor="#ffffff" class="text">No</td>
            <td bgcolor="#ffffff" class="text">Data</td>
            <td bgcolor="#ffffff" class="text">Hora</td>
            <td bgcolor="#ffffff" class="text">Para</td>
            <td bgcolor="#ffffff" class="text">Assunto</td>
	    <td bgcolor="#ffffff" class="text">Mensagem</td>
            </tr>
            <?
            $numero_mensagem = 1 + ( $list_data["pagina_num_env_arq"] - 1 ) * QT_POR_PAGINA_DEFAULT;
            foreach( $busca_tasks_enviadas_arquivadas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="botao" name="task[]" value="<?= $tupla[ 'tsk_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $numero_mensagem ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_para' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsk_assunto' ] ?></td>
                <td bgcolor="#ffffff" class="text"><?= "<a href=\"" . $_SERVER[ 'SCRIPT_NAME' ] . "?subpagina=" . urlencode( "Ver Task" ) . "&task_id=" . urlencode( $tupla[ 'tsk_id' ] ) . "&origem=" . urlencode( "Arquivo de Tasks" ) . "\">" .
                ( strlen( $tupla[ 'tsk_mensagem' ] ) < 20 ? $tupla[ 'tsk_mensagem' ] : substr( $tupla[ 'tsk_mensagem' ], 0, 20 ) . "..." ) . "</a>" ?></td>
                </tr>
            <?
            $numero_mensagem++;
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_env_arq'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="7" bgcolor="#ffffff">
	        <?
		 
		/* se a pagina atual for maior que 1, mostrar seta pra voltar */
		if( $list_data['pagina_num_env_arq'] > 1 )
                {
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_env_arq=<?= ($list_data["pagina_num_env_arq"] - 1) ?>&busca_pagina_num_rec_arq=<?= $list_data["pagina_num_rec_arq"] ?>"><font color="#ff8000">&lt;&lt;</font></a>
                    <?
	        }
    
	       for ($i = 1; $i <= $list_data["qt_paginas_env_arq"]; $i++)
	       { 
		   if ($i == $list_data["pagina_num_env_arq"]) 
	               print ($i);
		   else
   		   {
                       ?>
                       <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_env_arq=<?= $i ?>&busca_pagina_num_rec_arq=<?= $list_data["pagina_num_rec_arq"] ?>"><font color="#ff8000"><?= $i ?></font></a>
                       <? 
		   } 
	       }

               /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
               if( $list_data['qt_paginas_env_arq'] > $list_data['pagina_num_env_arq'] )
               {
                   ?>
                   <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?subpagina=<?= urlencode( "Arquivo de Tasks" ) ?>&busca_pagina_num_env_arq=<?= ($list_data["pagina_num_env_arq"] + 1) ?>&busca_pagina_num_rec_arq=<?= $list_data["pagina_num_rec_arq"] ?>"><font color="#ff8000">&gt;&gt;</font></a>
                   <?
               }
               ?>
               </td>
               </tr>
               <?
	    }
	    
	    ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">
            <input type="submit" name="acao" value="Remover" />
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="7">Não há nenhuma task enviada no arquivo.</td>
            </tr>
        <?
        }
        ?>
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" COLSPAN="7">&nbsp;</td>
        </tr>         
        </table>
        </td></tr>
        </table></center>
        </form>

        <br /><br />
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="button" value="<< Voltar" onclick="location='<?= $_SERVER['SCRIPT_NAME'] ?>?subpagina=home'">
        </form>
        <?
        break;
    default:
	extract_request_var( "busca_pagina_num_rec", $busca_pagina_num_rec );
	extract_request_var( "busca_pagina_num_env", $busca_pagina_num_env );

	$busca_tasks_recebidas_count_1 = $sql->squery( "
        SELECT
            COUNT( * ) AS quantidade
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '1' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome != '" . STATUS_ARQUIVADA . "'" );

        $busca_tasks_recebidas_count_2 = $sql->squery( "
	    SELECT
                count( * ) AS quantidade
            FROM
                task,
                membro_vivo para,
                status_task
            WHERE
                tsk_acao = '1' AND
                mem_id_de = NULL AND
                mem_id_para = para.mem_id AND
                mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
                task.stt_id = status_task.stt_id AND
                status_task.stt_nome != '" . STATUS_ARQUIVADA . "'" );
	
	$n_tasks_recebidas =  $busca_tasks_recebidas_count_1[ 'quantidade' ] + $busca_tasks_recebidas_count_2[ 'quantidade' ];
	$list_data['qt_paginas_rec'] = ceil( $n_tasks_recebidas / QT_POR_PAGINA_DEFAULT );
	$_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ] = ( isset( $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ] ) && $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ] != "" ? $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ] : 1 );
	if( $busca_pagina_num_rec != "" )
	{
	    $list_data["pagina_num_rec"] = $busca_pagina_num_rec;
	    $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ] = $busca_pagina_num_rec;
	}
	else
	    $list_data["pagina_num_rec"] = $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'recebidas' ];	
	if( $list_data["pagina_num_rec"] > $list_data['qt_paginas_rec'] )
	    $list_data["pagina_num_rec"] = $list_data['qt_paginas_rec'];
	if(  $list_data["pagina_num_rec"] <= 0 )
	    $list_data["pagina_num_rec"] = 1;
	
        $busca_tasks_enviadas_count = $sql->squery( "
        SELECT
            COUNT( * ) AS quantidade
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '0' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_de = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome != '" . STATUS_ARQUIVADA . "'" );

	$n_tasks_enviadas =  $busca_tasks_enviadas_count[ 'quantidade' ];
	$list_data['qt_paginas_env'] = ceil( $n_tasks_enviadas / QT_POR_PAGINA_DEFAULT );
	$_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ] = ( isset( $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ] ) && $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ] != "" ? $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ] : 1 );
	if( $busca_pagina_num_env != "" )
	{
	    $list_data["pagina_num_env"] = $busca_pagina_num_env;
	    $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ] = $busca_pagina_num_env;
	}
	else
	    $list_data["pagina_num_env"] = $_SESSION[ 'paginacao' ][ 'tasklist' ][ 'enviadas' ];	
	if( $list_data["pagina_num_env"] > $list_data['qt_paginas_env'] )
	    $list_data["pagina_num_env"] = $list_data['qt_paginas_env'];
	if(  $list_data["pagina_num_env"] <= 0 )
	    $list_data["pagina_num_env"] = 1;	
	
	$busca_tasks_recebidas = $sql->query( "
        SELECT
            task.tsk_id,
            ttk_id,
            task.stt_id,
            de.mem_nome AS mem_de,
            para.mem_nome AS mem_para,
            status_task.stt_nome AS tsk_status,
            date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
            tsk_assunto,
            tsk_mensagem
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '1' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome != '" . STATUS_ARQUIVADA . "'
        UNION
        (
            SELECT
                task.tsk_id,
                ttk_id,
                task.stt_id,
                'Sistema' AS mem_de,
                para.mem_nome AS mem_para,
                status_task.stt_nome AS tsk_status,
                date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
                tsk_assunto,
                tsk_mensagem
            FROM
                task,
                membro_vivo para,
                status_task
            WHERE
                tsk_acao = '1' AND
                mem_id_de = NULL AND
                mem_id_para = para.mem_id AND
                mem_id_para = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
                task.stt_id = status_task.stt_id AND
                status_task.stt_nome != '" . STATUS_ARQUIVADA . "'
        )
        ORDER BY
            tsk_timestamp DESC
        LIMIT " . QT_POR_PAGINA_DEFAULT . "
        OFFSET  " . ( $list_data["pagina_num_rec"] - 1 ) * QT_POR_PAGINA_DEFAULT );

        $busca_tasks_enviadas = $sql->query( "
        SELECT
            task.tsk_id,
            ttk_id,
            task.stt_id,
            de.mem_nome AS mem_de,
            para.mem_nome AS mem_para,
            status_task.stt_nome AS tsk_status,
            date_part( 'epoch', tsk_dt ) AS tsk_timestamp,
            tsk_assunto,
            tsk_mensagem
        FROM
            task,
            membro_vivo de,
            membro_vivo para,
            status_task
        WHERE
            tsk_acao = '0' AND
            mem_id_de = de.mem_id AND
            mem_id_para = para.mem_id AND
            mem_id_de = " . $_SESSION[ 'membro' ][ 'id' ] . " AND
            task.stt_id = status_task.stt_id AND
            status_task.stt_nome != '" . STATUS_ARQUIVADA . "'
        LIMIT " . QT_POR_PAGINA_DEFAULT . "
        OFFSET  " . ( $list_data["pagina_num_env"] - 1 ) * QT_POR_PAGINA_DEFAULT );


	?>
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" name="form_tasks_recebidas" method="post">
	<input type="hidden" name="busca_pagina_num_env" value="<?= $busca_pagina_num_env ?>">
	<input type="hidden" name="busca_pagina_num_rec" value="<?= $busca_pagina_num_rec ?>">
	     
        <br /><br />
        <center>
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
       <tr><td>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="8" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Tasklist - Recebidas</td>
        </tr>
        <?
        if( is_array( $busca_tasks_recebidas ) )
        {
        ?>
	    <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;</td>
            <td bgcolor="#ffffff" class="text">No</td>
            <td bgcolor="#ffffff" class="text">Data</td>
            <td bgcolor="#ffffff" class="text">Hora</td>
            <td bgcolor="#ffffff" class="text">Assunto</td>
            <td bgcolor="#ffffff" class="text">De:</td>
	    <td bgcolor="#ffffff" class="text">Mensagem</td>
            <td bgcolor="#ffffff" class="text">Status</td>
            </tr>
            <?
            $numero_mensagem = 1 + ( $list_data["pagina_num_rec"] - 1 ) * QT_POR_PAGINA_DEFAULT;
            $i = 2;
            foreach( $busca_tasks_recebidas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="caixa" name="task[]" value="<?= $tupla[ 'tsk_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $numero_mensagem ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:m", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsk_assunto' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_de' ] ?></td>
                <td bgcolor="#ffffff" class="text"><?= "<a href=\"" . $_SERVER[ 'SCRIPT_NAME' ] . "?subpagina=" . urlencode( "Ver Task" ) . "&task_id=" . urlencode( $tupla[ 'tsk_id' ] ) . "&origem=home" . ( ( $tupla[ 'mem_de' ] == "Sistema" ) ? "&sys=yeah" : "" ) . "\">" .
                ( strlen( $tupla[ 'tsk_mensagem' ] ) < 20 ? $tupla[ 'tsk_mensagem' ] : substr( $tupla[ 'tsk_mensagem' ], 0, 20 ) . "..." ) . "</a>" ?>
                </td>
	            <td bgcolor="#ffffff" class="text">&nbsp;<? faz_select( "status" . $tupla[ 'tsk_id' ], $busca_status_tasks, "stt_id", "stt_nome", $tupla[ 'stt_id' ], "onchange=\"document.form_tasks_recebidas.elements[ " . $i . " ].checked = true;\"" ) ?></td>
                </tr>
            <?
            $numero_mensagem++;
            $i+=2;
            }
            
	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_rec'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="8" bgcolor="#ffffff">
	        <?
		 
		/* se a pagina atual for maior que 1, mostrar seta pra voltar */
		if( $list_data['pagina_num_rec'] > 1 )
                {
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_rec=<?= ($list_data["pagina_num_rec"] - 1) ?>&busca_pagina_num_env=<?= $list_data["pagina_num_env"] ?>"><font color="#ff8000">&lt;&lt;</font></a>
                    <?
	        }
    
	       for ($i = 1; $i <= $list_data["qt_paginas_rec"]; $i++)
	       { 
		   if ($i == $list_data["pagina_num_rec"]) 
	               print ($i);
		   else
   		   {
                       ?>
                       <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_rec=<?= $i ?>&busca_pagina_num_env=<?= $list_data["pagina_num_env"] ?>"><font color="#ff8000"><?= $i ?></font></a>
                       <? 
		   } 
	       }

               /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
               if( $list_data['qt_paginas_rec'] > $list_data['pagina_num_rec'] )
               {
                   ?>
                   <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_rec=<?= ($list_data["pagina_num_rec"] + 1) ?>&busca_pagina_num_env=<?= $list_data["pagina_num_env"] ?>"><font color="#ff8000">&gt;&gt;</font></a>
                   <?
               }
               ?>
               </td>
               </tr>
               <?
	    }

	    ?>
	    <tr>
            <td bgcolor="#ffffff" class="text" colspan="8">
            <input type="submit" name="acao" value="Alterar" />
            <input type="submit" name="acao" value="Arquivar" />
            <input type="submit" name="acao" value="Remover" />
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="8">Não há nenhuma task recebida.</td>
            </tr>
        <?
        }
	?>
         <tr>
         <td class="textwhitemini" COLSPAN="8" bgColor="#336699" HEIGHT="17">&nbsp;</td>
         </tr>
        </form>
        </table>
        </td></tr>
        </table>
	      
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" name="form_tasks_enviadas" method="post">
	<input type="hidden" name="busca_pagina_num_env" value="<?= $busca_pagina_num_env ?>">
	<input type="hidden" name="busca_pagina_num_rec" value="<?= $busca_pagina_num_rec ?>">
	      
        <br /><br />
        <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
        <tr><td>
        <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="8" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Tasklist - Enviadas</td>
        </tr>
        <?
        if( is_array( $busca_tasks_enviadas ) )
        {
        ?>
	    <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;</td>
            <td bgcolor="#ffffff" class="text">No</td>
            <td bgcolor="#ffffff" class="text">Data</td>
            <td bgcolor="#ffffff" class="text">Hora</td>
            <td bgcolor="#ffffff" class="text">Assunto</td>
	    <td bgcolor="#ffffff" class="text">Para:</td>
	    <td bgcolor="#ffffff" class="text">Mensagem</td>
            <td bgcolor="#ffffff" class="text">Status</td>
            </tr>
            <?
            $numero_mensagem = 1 + ( $list_data["pagina_num_env"] - 1 ) * QT_POR_PAGINA_DEFAULT;
            $i = 0;
            foreach( $busca_tasks_enviadas as $tupla )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text"><input type="checkbox" class="caixa" name="task[]" value="<?= $tupla[ 'tsk_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $numero_mensagem ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "d/m/Y", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= date( "H:i", $tupla[ 'tsk_timestamp' ] ) ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsk_assunto' ] ?></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'mem_para' ] ?></td>
                <td bgcolor="#ffffff" class="text"><?= "<a href=\"" . $_SERVER[ 'SCRIPT_NAME' ] . "?subpagina=" . urlencode( "Ver Task" ) . "&task_id=" . urlencode( $tupla[ 'tsk_id' ] ) . "&origem=home" . "\">" .
                ( strlen( $tupla[ 'tsk_mensagem' ] ) < 20 ? $tupla[ 'tsk_mensagem' ] : substr( $tupla[ 'tsk_mensagem' ], 0, 20 ) . "..." ) . "</a>" ?></td>
	        <td bgcolor="#ffffff" class="text">&nbsp;<?= $tupla[ 'tsk_status' ] ?></td>
                </tr>
            <?
            $numero_mensagem++;
            $i+=2;
            }

	    /* se a quantidade total de paginas for maior que 1 tem de mostrar a navegacao */
	    if( $list_data['qt_paginas_env'] > 1 )
	    {
                ?>
                <tr>
                <td class="text" colspan="8" bgcolor="#ffffff">
	        <?
		 
		/* se a pagina atual for maior que 1, mostrar seta pra voltar */
		if( $list_data['pagina_num_env'] > 1 )
                {
                    ?>
                    <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_env=<?= ($list_data["pagina_num_env"] - 1) ?>&busca_pagina_num_rec=<?= $list_data["pagina_num_rec"] ?>"><font color="#ff8000">&lt;&lt;</font></a>
                    <?
	        }
    
	       for ($i = 1; $i <= $list_data["qt_paginas_env"]; $i++)
	       { 
		   if ($i == $list_data["pagina_num_env"]) 
	               print ($i);
		   else
   		   {
                       ?>
                       <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_env=<?= $i ?>&busca_pagina_num_rec=<?= $list_data["pagina_num_rec"] ?>"><font color="#ff8000"><?= $i ?></font></a>
                       <? 
		   } 
	       }

               /* Se a quantidade de paginas for maior que a pagina atual, mostrar a seta pra ir pra proxima */
               if( $list_data['qt_paginas_env'] > $list_data['pagina_num_env'] )
               {
                   ?>
                   <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?busca_pagina_num_env=<?= ($list_data["pagina_num_env"] + 1) ?>&busca_pagina_num_rec=<?= $list_data["pagina_num_rec"] ?>"><font color="#ff8000">&gt;&gt;</font></a>
                   <?
               }
               ?>
               </td>
               </tr>
               <?
	    }
	    
	    ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="8">
            <input type="submit" name="acao" value="Arquivar" />
            <input type="submit" name="acao" value="Remover" />
            </td>
            </tr>
        <?
        }
        else
        {
        ?>
            <tr>
            <td bgcolor="#ffffff" class="text" colspan="8">Não há nenhuma task enviada.</td>
            </tr>
        <?
        }
        ?>
         <tr>
         <td class="textwhitemini" COLSPAN="8" bgColor="#336699" HEIGHT="17">&nbsp;</td>
         </tr>
         </table>
         </td></tr>
         </table>
         </form>

         <br /><br />
         <table border="0," CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="700">
         <tr><td>
         <table border="0" CELLSPACING="1" CELLPADDING="5" WIDTH="100%" class="text">
         <tr>
         <td class="textwhitemini" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Tasklist - Novas</td>
         </tr>
         <tr>
         <td BGCOLOR="#FFFFFF" class="text">
         <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
         <input name="subpagina" type="submit" value="Nova Task">
         <input name="subpagina" type="submit" value="Arquivo de Tasks">
         </td></tr></form>
         <tr>
         <td class="textwhitemini" bgColor="#336699" HEIGHT="17">&nbsp;</td>
         </tr>
         </table>
         </td></tr>
         </table></center><BR><BR>
        
<?
}
?>
