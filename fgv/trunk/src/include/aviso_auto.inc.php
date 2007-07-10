<?
//require_once( INCPATH . "/funcoes.php" );

define( "TSK_SISTEMA",         "Task do Sistema" );
define( "STATUS_BAIXADA",      "Baixada"   );
define( "STATUS_ARQUIVADA",    "Arquivada" );
define( "STATUS_EM_ABERTO",    "Em aberto" );

function envia_task_cargo( $sql, $cargo_id, $id_tipo_task, $assunto_task, $mensagem_task, $data_envio = "" )
{
    $query = "
    SELECT
        stt_id
    FROM
        status_task
    WHERE
        stt_nome = '" . STATUS_EM_ABERTO . "'";

    $busca_status_em_aberto = $sql->squery( $query );
    $id_status_task = $busca_status_em_aberto[ 'stt_id' ];

    $query = "
    SELECT DISTINCT
        mem_id
    FROM
        membro_vivo
    WHERE
        cgv_id = '" . $cargo_id . "'";

    $rs_mem = $sql->query( $query );
    
    if( is_array( $rs_mem ) )
    {
	foreach( $rs_mem as $cara_mem )
	{

	    if( $assunto_task != "" && $mensagem_task != "" )
	    {
		$assunto_task = addslashes( $assunto_task );
		$mensagem_task = addslashes( $mensagem_task );

		$query = "
                SELECT DISTINCT
                    nextval( 'task_tsk_id_seq' )";

		$rqn = $sql->squery( $query );

		$query = "
                INSERT INTO
                   task
                (
                    tsk_id,
                    ttk_id,
                    stt_id,
                    mem_id_para,
                    tsk_acao,
                    tsk_assunto,
                    tsk_mensagem
                )
                VALUES
                (
                    '" . $rqn[ 'nextval' ] . "',
                    '" . $id_tipo_task . "',
                    '" . $id_status_task . "',
                    '" . $cara_mem[ 'mem_id' ] . "',
                    '1',
                    '" . $assunto_task . "',
                    '" . $mensagem_task . "'
                )";

		$rq = $sql->query( $query );

		if( $data_envio != "" )
		{
		    $query = "
                    UPDATE
                        task
                    SET
                        tsk_dt = '" . $data_envio . "'
                    WHERE
                        tsk_id = '" . $rqn[ 'nextval' ] . "'";

		    $rq = $sql->query( $query );
		}
	    }
	}
    }
}


function envia_task_novo_cliente( $sql, $cliente, $prt )
{
    $query = "
        SELECT DISTINCT
            ttk_id
        FROM
            tipo_task
        WHERE
            ttk_nome = '" . TSK_SISTEMA . "'";

    $rs_ttk = $sql->squery( $query );

    $query = "
        SELECT DISTINCT
            ava_assunto,
            ava_mensagem
        FROM
            aviso_auto
        WHERE
            ava_mne = 'task_novo_cliente'";
    
    $rs_ava = $sql->squery( $query );
    
    $query = "
        SELECT DISTINCT
            cgv_id
        FROM
            ava_cgv
            NATURAL JOIN aviso_auto
        WHERE
            ava_mne = 'task_novo_cliente'";
    
    $rs_cgv = $sql->query( $query );

    $query = "
        SELECT DISTINCT
            cst_nome,
            cli_nome
        FROM
            cliente
            NATURAL JOIN consultoria
        WHERE
            cst_id = '" . $cliente . "'";
    
    $rs_cst = $sql->squery( $query );
   
    if( is_array( $rs_cgv ) && is_array( $rs_ava ) && is_array( $rs_cst ) )
    {
	$rs_ava[ 'ava_mensagem' ] .= "\nCliente: " . $rs_cst[ 'cli_nome' ] . "\nConsultoria: " . $rs_cst[ 'cst_nome' ] . "\nPrazo para retorno telefônico: " . $prt  . " dia(s) útil(eis)\n";
	
	foreach( $rs_cgv as $cara_cgv )
	{
	    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $rs_ava[ 'ava_assunto' ],  $rs_ava[ 'ava_mensagem' ], $data_envio = "" );
	}
    }
}

function envia_task_carta_agradecimento( $sql, $cst_id )
{
    $query = "
        SELECT DISTINCT
            ttk_id
        FROM
            tipo_task
        WHERE
            ttk_nome = '" . TSK_SISTEMA . "'";

    $rs_ttk = $sql->squery( $query );

    $query = "
        SELECT DISTINCT
            ava_assunto,
            ava_mensagem
        FROM
            aviso_auto
        WHERE
            ava_mne = 'task_carta_agradecimento'";
    
    $rs_ava = $sql->squery( $query );

    $query = "
        SELECT DISTINCT
            cst_nome,
            prf_nome
        FROM
            cst_prf
            NATURAL JOIN professor
            LEFT JOIN consultoria ON ( cst_prf.cst_id = consultoria.cst_id )
        WHERE
            cst_prf.cst_id = '" . $cst_id . "'";
    
    $rs_cst = $sql->query( $query );
    
    $query = "
        SELECT DISTINCT
            cgv_id
        FROM
            ava_cgv
            NATURAL JOIN
            aviso_auto
        WHERE
            ava_mne = 'task_carta_agradecimento'";

    $rs_cgv = $sql->query( $query );
   
    if( is_array( $rs_cgv ) && is_array( $rs_ava ) && is_array( $rs_cst ) )
    {
        $rs_ava[ 'ava_mensagem' ] .= "\nConsultoria: " . $rs_cst[ 0 ][ 'cst_nome' ] . "\nProfessores:";

        foreach( $rs_cst as $prf )
            $rs_ava[ 'ava_mensagem' ] .= "\n" . $prf[ 'prf_nome' ];

	foreach( $rs_cgv as $cara_cgv )
	    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $rs_ava[ 'ava_assunto' ],  $rs_ava[ 'ava_mensagem' ], $data_envio = "" );
    }
}


?>
