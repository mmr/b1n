<?
/* $Id: aviso_auto.php,v 1.9 2002/07/17 17:24:35 binary Exp $ */
?>

<html>
<head>
<title>Avisos AutomaGicos</title>
<link rel="stylesheet" type="text/css" href="images/fgv.css">
</head>
<BODY BGCOLOR="#FFFFFF" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<?
/* Acho que o ideal seria rodar esse arquivo as 00:01 */

if ( get_magic_quotes_gpc() || get_magic_quotes_runtime() )
    die( "Para o correto funcionamento desta aplicaçao e necessario desligar magic_quote_gpc e magic_quote_runtime do PHP" );

define( "INCPATH",          "../include" );
define( "EMAIL_DE",         "tiobinary@inferno.net" );

require_once( INCPATH . "/debug.inc.php" );             /* biblioteca para debug.           */
require_once( INCPATH . "/sql_link.inc.php" );          /* biblioteca para uso do BD.       */
require_once( INCPATH . "/trata_dados.inc.php" );       /* funcoes de tratamento de dados   */
require_once( INCPATH . "/funcoes.php" );
require_once( INCPATH . "/aviso_auto.inc.php" );        /* funcoes de avisos automáticos    */


$sql = new sqlLink( "fgv",  INCPATH . "/sql_conf.inc.php" );

$rs = $sql->query( "BEGIN TRANSACTION" );

if( $rs )
{
    $query = "
        SELECT DISTINCT
            ava_id,
            DATE_PART( 'day', ava_dt) AS ava_dia,
            DATE_PART( 'month', ava_dt) AS ava_mes,
            DATE_PART( 'year', ava_dt ) AS ava_ano,
            ava_mne,
            ava_assunto,
            ava_mensagem
        FROM
            aviso_auto";

    $rs = $sql->query( $query );

    $query = "
        SELECT DISTINCT
            ttk_id
        FROM
            tipo_task
        WHERE
            ttk_nome = '" . TSK_SISTEMA . "'";

    $rs_ttk = $sql->squery( $query );
   
    if( is_array( $rs ) && is_array( $rs_ttk ) )
    {
        foreach( $rs as $cara ) 
        {
            switch( $cara[ 'ava_mne' ] )
            {

	    /* ------------------------- Avisos Automáticos -- Prêmio Gestão ------------------------- */
	      
	    case "email_antes_entrega_artigo":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            agv_id,
                            agv_email,
                            DATE_PART( 'epoch', evt_dt_ent_art ) AS evt_dt_ent_art_tms
                        FROM
                            inscrito_pg
                            NATURAL JOIN
                            aluno_gv
                            NATURAL JOIN
                            evento
                        WHERE
                            ipg_resumo = 1 AND
                            DATE_PART ( 'day', evt_dt_ent_art ) = DATE_PART( 'day', CURRENT_DATE + 7 ) AND
                            DATE_PART ( 'month', evt_dt_ent_art ) = DATE_PART( 'month', CURRENT_DATE + 7 ) AND
                            DATE_PART ( 'year', evt_dt_ent_art ) = DATE_PART( 'year', CURRENT_DATE + 7 )";

                    $rs_agv = $sql->query( $query );
    
                    if( is_array( $rs_agv ) )
                    {
                        foreach( $rs_agv as $cara_agv )
			{
			    if( consis_email( $cara_agv[ 'agv_email' ] ) )
			    {
				$mensagem = $cara[ 'ava_mensagem' ] . "\n\nData de entrega do artigo: " . date( "d/m/Y", $cara_agv[ 'evt_dt_ent_art_tms' ] ) . "\n";
				mail( $cara_agv[ 'agv_email' ], $cara[ 'ava_assunto' ], $mensagem, "From: " . EMAIL_DE . "\n\r" );
			    }
			}
                        
                        $query = "
                            UPDATE
                                aviso_auto
                            SET
                                ava_dt = CURRENT_TIMESTAMP
                            WHERE
                                ava_mne = 'email_antes_entrega_artigo'";
                                
                        $rq = $sql->query( $query );
                    }
                }
		break;
	    case "email_entrega_artigo":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            agv_id,
                            agv_email,
                            DATE_PART( 'epoch', evt_dt_ent_art ) AS evt_dt_ent_art_tms
                        FROM
                            inscrito_pg
                            NATURAL JOIN
                            aluno_gv
                            NATURAL JOIN
                            evento
                        WHERE
                            ipg_resumo = 1 AND
                            DATE_PART ( 'day', evt_dt_ent_art ) = DATE_PART( 'day', CURRENT_DATE ) AND
                            DATE_PART ( 'month', evt_dt_ent_art ) = DATE_PART( 'month', CURRENT_DATE ) AND
                            DATE_PART ( 'year', evt_dt_ent_art ) = DATE_PART( 'year', CURRENT_DATE )";

                    $rs_agv = $sql->query( $query );
    
                    if( is_array( $rs_agv ) )
                    {
                        foreach( $rs_agv as $cara_agv )
			{
			    if( consis_email( $cara_agv[ 'agv_email' ] ) )
			    {
				$mensagem = $cara[ 'ava_mensagem' ] . "\n\nData de entrega do artigo: " . date( "d/m/Y", $cara_agv[ 'evt_dt_ent_art_tms' ] ) . "\n";
				mail( $cara_agv[ 'agv_email' ], $cara[ 'ava_assunto' ], $mensagem, "From: " . EMAIL_DE . "\n\r" );
			    }
			}
                        
                        $query = "
                            UPDATE
                                aviso_auto
                            SET
                                ava_dt = CURRENT_TIMESTAMP
                            WHERE
                                ava_mne = 'email_entrega_artigo'";
                                
                        $rq = $sql->query( $query );
                    }
                }
		break;

            /* ------------------------- Avisos Automáticos -- Evento Superação ------------------------- */

	    case "email_membros_equipes":
		$data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            eqp_agv.agv_id,
                            agv_email,
                            DATE_PART( 'epoch', evt_dt ) AS evt_dt_tms
                        FROM
                            (
                                equipe
                                NATURAL JOIN
                                evento
                            )
                            JOIN
                            eqp_agv USING( eqp_id )
                            JOIN
                            aluno_gv ON( eqp_agv.agv_id = aluno_gv.agv_id )
                        WHERE
                            DATE_PART ( 'day', evt_dt ) = DATE_PART( 'day', CURRENT_DATE + 1 ) AND
                            DATE_PART ( 'month', evt_dt ) = DATE_PART( 'month', CURRENT_DATE + 1 ) AND
                            DATE_PART ( 'year', evt_dt ) = DATE_PART( 'year', CURRENT_DATE + 1 ) AND
                            tev_id = ( SELECT DISTINCT tev_id FROM tipo_evento WHERE tev_mne = 'super_acao' )";

                    $rs_agv = $sql->query( $query );
    
                    if( is_array( $rs_agv ) )
                    {
                        foreach( $rs_agv as $cara_agv )
			{
			    if( consis_email( $cara_agv[ 'agv_email' ] ) )
			    {
				$mensagem = $cara[ 'ava_mensagem' ] . "\n\nData do Evento Superação: " . date( "d/m/Y", $cara_agv[ 'evt_dt_tms' ] ) . "\n";
				mail( $cara_agv[ 'agv_email' ], $cara[ 'ava_assunto' ], $mensagem, "From: " . EMAIL_DE . "\n\r" );
			    }
			}
                        
                        $query = "
                            UPDATE
                                aviso_auto
                            SET
                                ava_dt = CURRENT_TIMESTAMP
                            WHERE
                                ava_mne = 'email_membros_equipes'";
                                
                        $rq = $sql->query( $query );
                    }
                }
		break;
		
            /* ------------------------- Avisos Automáticos -- Cadastro de Clientes ------------------------- */
		
            case "task_novo_cliente":
		/* Função envia_task_novo_cliente( $sql, $cliente, $prt ) */
		break;
            case "task_retorno_telefonico_vencido":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            cli_nome,
                            DATE_PART( 'epoch', cst_dt_retorno ) AS cst_dt_retorno_tms
                        FROM
                            consultoria
                            NATURAL JOIN
                            cliente
                        WHERE
                            DATE_PART ( 'day', cst_dt_retorno ) = DATE_PART( 'day', CURRENT_DATE ) AND
                            DATE_PART ( 'month', cst_dt_retorno ) = DATE_PART( 'month', CURRENT_DATE ) AND
                            DATE_PART ( 'year', cst_dt_retorno ) = DATE_PART( 'year', CURRENT_DATE )";

                    $rs_cli = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_retorno_telefonico_vencido'";

		    $rs_cgv = $sql->query( $query );		    

		    if( is_array( $rs_cgv ) && is_array( $rs_cli ) )
		    {
			foreach( $rs_cli as $cara_cli )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nCliente: " . $cara_cli[ 'cli_nome' ] . "\nConsultoria: " . $cara_cli[ 'cst_nome' ] . "\nPrazo para retorno telefônico: " .  date( "d/m/Y", $cara_cli[ 'cst_dt_retorno_tms' ] );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_retorno_telefonico_vencido'";
                                
		    $rq = $sql->query( $query );
		}
		break;
	    case "task_reuniao_marcada":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            cst_dt_reuniao,
                            cst_dt_prp_reuniao,
                            DATE_PART( 'epoch', cst_dt_reuniao ) AS cst_dt_reuniao_tms,
                            DATE_PART( 'epoch', cst_dt_prp_reuniao ) AS cst_dt_prp_reuniao_tms
                        FROM
                            consultoria
                        WHERE
                            (
                                DATE_PART ( 'day', cst_dt_reuniao ) = DATE_PART( 'day', CURRENT_DATE + 3 ) AND
                                DATE_PART ( 'month', cst_dt_reuniao ) = DATE_PART( 'month', CURRENT_DATE + 3 ) AND
                                DATE_PART ( 'year', cst_dt_reuniao ) = DATE_PART( 'year', CURRENT_DATE + 3 )
                            )
                            OR
                            (
                                DATE_PART ( 'day', cst_dt_prp_reuniao ) = DATE_PART( 'day', CURRENT_DATE + 3 ) AND
                                DATE_PART ( 'month', cst_dt_prp_reuniao ) = DATE_PART( 'month', CURRENT_DATE + 3 ) AND
                                DATE_PART ( 'year', cst_dt_prp_reuniao ) = DATE_PART( 'year', CURRENT_DATE + 3 )
                            )";

                    $rs_cst = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_reuniao_marcada'";

		    $rs_cgv = $sql->query( $query );		    

		    if( is_array( $rs_cgv ) && is_array( $rs_cst ) )
		    {
			foreach( $rs_cst as $cara_cst )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nConsultoria: " . $cara_cst[ 'cst_nome' ] . ( $cara_cst[ 'cst_dt_reuniao' ] != "" ? "\nData da reunião: " . date( "d/m/Y - H:i", $cara_cst[ 'cst_dt_reuniao_tms' ] ) : "" ) . ( $cara_cst[ 'cst_dt_prp_reuniao' ] != "" ? "\nData reunião de entrega de proposta: " . date( "d/m/Y - H:i", $cara_cst[ 'cst_dt_prp_reuniao_tms' ] ) : "" );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_reuniao_marcada'";
                                
		    $rq = $sql->query( $query );
                }
		break;
	    case "task_entrega_proposta":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            cli_nome,
                            DATE_PART( 'epoch', cst_dt_prp_entrega ) AS cst_dt_prp_entrega_tms
                        FROM
                            consultoria
                            NATURAL JOIN
                            cliente
                        WHERE
                            DATE_PART ( 'day', cst_dt_prp_entrega ) = DATE_PART( 'day', CURRENT_DATE + ROUND( DATE_PART( 'day', cst_dt_prp_entrega - cst_dt_prp_envio ) * 0.3 ) ) AND
                            DATE_PART ( 'month', cst_dt_prp_entrega ) = DATE_PART( 'month', CURRENT_DATE + ROUND( DATE_PART( 'day', cst_dt_prp_entrega - cst_dt_prp_envio ) * 0.3 ) ) AND
                            DATE_PART ( 'year', cst_dt_prp_entrega ) = DATE_PART( 'year', CURRENT_DATE + ROUND( DATE_PART( 'day', cst_dt_prp_entrega - cst_dt_prp_envio ) * 0.3 ) )";
		    
                    $rs_cst = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_entrega_proposta'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_cst ) )
		    {
			foreach( $rs_cst as $cara_cst )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nConsultoria: " . $cara_cst[ 'cst_nome' ] . "\nCliente: " . $cara_cst[ 'cli_nome' ] . "\nData final de entrega de proposta: " . date( "d/m/Y", $cara_cst[ 'cst_dt_prp_entrega_tms' ] );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_entrega_proposta'";
                                
		    $rq = $sql->query( $query );
		} 
		break;

            /* ------------------------- Avisos Automáticos -- Diversos ------------------------- */

	    case "task_feriado":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_feriado'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_feriado'";
                                
		    $rq = $sql->query( $query );
		}
                break;
	    case "task_carta_agradecimento":
		/* Função envia_task_carta_agradecimento( $sql, $cst_id ) */
		break;
	    case "task_parcela_vencer":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            cob_valor,
                            DATE_PART( 'epoch', cob_dt_venc ) AS cob_dt_venc_tms
                        FROM
                            cobranca
                            NATURAL JOIN
                            consultoria
                        WHERE
                            DATE_PART ( 'day', cob_dt_venc ) = DATE_PART( 'day', CURRENT_DATE + 7 ) AND
                            DATE_PART ( 'month', cob_dt_venc ) = DATE_PART( 'month', CURRENT_DATE + 7 ) AND
                            DATE_PART ( 'year', cob_dt_venc ) = DATE_PART( 'year', CURRENT_DATE + 7 )";

                    $rs_cob = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_parcela_vencer'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_cob ) )
		    {
			foreach( $rs_cob as $cara_cob )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nConsultoria: " . $cara_cob[ 'cst_nome' ] . "\nValor: " . formata_dinheiro( $cara_cob[ 'cob_valor' ], 1 ) . "\nData do vencimento: " . date( "d/m/Y", $cara_cob[ 'cob_dt_venc_tms' ] );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ], $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_parcela_vencer'";
                                
		    $rq = $sql->query( $query );
                }
		break;
	    case "task_parcela_vencida":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            cob_valor,
                            cob_parcela,
                            DATE_PART( 'epoch', cob_dt_venc ) AS cob_dt_venc_tms
                        FROM
                            cobranca
                            NATURAL JOIN
                            consultoria
                        WHERE
                            DATE_PART ( 'day', cob_dt_venc ) = DATE_PART( 'day', CURRENT_DATE - 1 ) AND
                            DATE_PART ( 'month', cob_dt_venc ) = DATE_PART( 'month', CURRENT_DATE - 1 ) AND
                            DATE_PART ( 'year', cob_dt_venc ) = DATE_PART( 'year', CURRENT_DATE - 1 )";

                    $rs_cob = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_parcela_vencida'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_cob ) )
		    {
			foreach( $rs_cob as $cara_cob )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nConsultoria: " . $cara_cob[ 'cst_nome' ] . "\nValor: " . formata_dinheiro( $cara_cob[ 'cob_valor' ], 1 ) . "\nData do vencimento: " . date( "d/m/Y", $cara_cob[ 'cob_dt_venc_tms' ] );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ], $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_parcela_vencida'";
                                
		    $rq = $sql->query( $query );
                }
		break;
	    case "task_enviar_brinde_professor":
		$data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            cst_nome,
                            prf_nome,
                            DATE_PART( 'epoch', cst_dt_prj_fim ) AS cst_dt_prj_fim_tms
                        FROM
                            cst_prf
                            JOIN
                            professor USING( prf_id )
                            JOIN
                            consultoria USING( cst_id )
                        WHERE
                            DATE_PART ( 'day', cst_dt_prj_fim ) = DATE_PART( 'day', CURRENT_DATE ) AND
                            DATE_PART ( 'month', cst_dt_prj_fim ) = DATE_PART( 'month', CURRENT_DATE ) AND
                            DATE_PART ( 'year', cst_dt_prj_fim ) = DATE_PART( 'year', CURRENT_DATE )
                        ORDER BY
                            cst_nome";

                    $rs_prf = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_enviar_brinde_professor'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_prf ) )
		    {
			foreach( $rs_prf as $cara_prf )
			{
			    $cara[ 'ava_mensagem' ] .= "\n\nConsultoria: " . $cara_prf[ 'cst_nome' ] . "\nProfessor: " . $cara_prf[ 'prf_nome' ] . "\nData de fim do projeto: " . date( "d/m/Y", $cara_prf[ 'cst_dt_prj_fim_tms' ] );
                        }

			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ], $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_enviar_brinde_professor'";
                                
		    $rq = $sql->query( $query );
                }
		break;
            case "task_atualizar_posicao_membro_janeiro":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_janeiro'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_janeiro'";
                                
		    $rq = $sql->query( $query );
		}
                break;
            case "task_atualizar_posicao_membro_marco":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_marco'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-03-25" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_marco'";
                                
		    $rq = $sql->query( $query );
		}
                break;
            case "task_atualizar_posicao_membro_setembro":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_setembro'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-09-23" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_atualizar_posicao_membro_setembro'";
                                
		    $rq = $sql->query( $query );
		}
                break;
            case "task_atualizar_grade_horario_marco":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_atualizar_grade_horario_marco'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ],  $data_atual[ 'year' ] . "-03-01" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_atualizar_grade_horario_marco'";
                                
		    $rq = $sql->query( $query );
		}
                break;
            case "task_atualizar_grade_horario_agosto":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_atualizar_grade_horario_agosto'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) )
		    {
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ], $data_atual[ 'year' ] . "-08-04" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_atualizar_grade_horario_agosto'";
                                
		    $rq = $sql->query( $query );
		}
                break;
            case "task_aniversario_membro":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            mem_nome,
                            DATE_PART ( 'day', mem_dt_nasci ) AS mem_dia_nasci,
                            DATE_PART ( 'month', mem_dt_nasci ) AS mem_mes_nasci
                        FROM
                            membro_vivo
                        WHERE
                            DATE_PART ( 'day', mem_dt_nasci ) = DATE_PART( 'day', CURRENT_DATE + 7 ) AND
                            DATE_PART ( 'month', mem_dt_nasci ) = DATE_PART( 'month', CURRENT_DATE + 7 )
                        ORDER BY
                            mem_nome";

                    $rs_anv = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_aniversario_membro'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_anv ) )
		    {
			foreach( $rs_anv as $cara_anv )
                        {
			    $cara[ 'ava_mensagem' ] .= "\n" . $cara_anv[ 'mem_nome' ] . " (" . $cara_anv[ 'mem_dia_nasci' ] . "/" . $cara_anv[ 'mem_mes_nasci' ] . ")";
			}
			
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ] );
			}
			
		    }
                        
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_aniversario_membro'";
		    
                    $rq = $sql->query( $query );
		}
                break;
	    case "task_empresa_junior":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_ano' ] < $data_atual[ 'year' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            eju_nome
                        FROM
                            empresa_junior
                        WHERE
                            eju_rel_estreita = '1'
                        ORDER BY
                            eju_nome";

                    $rs_eju = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_empresa_junior'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_eju ) )
		    {
			foreach( $rs_eju as $cara_eju )
			{
			    $cara[ 'ava_mensagem' ] .= "\n" . $cara_eju[ 'eju_nome' ];
                        }
			
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ], $data_atual[ 'year' ] . "-01-01" );
			}
			
		    }

		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_empresa_junior'";
                                
                    $rq = $sql->query( $query );
                }
                break;
            case "task_aniversario_professor":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            prf_nome,
                            DATE_PART ( 'day', prf_dt_nasci ) AS prf_dia_nasci,
                            DATE_PART ( 'month', prf_dt_nasci ) AS prf_mes_nasci
                        FROM
                            professor
                        WHERE
                            DATE_PART ( 'day', prf_dt_nasci ) = DATE_PART( 'day', CURRENT_DATE ) AND
                            DATE_PART ( 'month', prf_dt_nasci ) = DATE_PART( 'month', CURRENT_DATE )
                        ORDER BY
                            prf_nome";

                    $rs_anv = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_aniversario_professor'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_anv ) )
		    {
			foreach( $rs_anv as $cara_anv )
			{
			    $cara[ 'ava_mensagem' ] .= "\n" . $cara_anv[ 'prf_nome' ] . " (" . $cara_anv[ 'prf_dia_nasci' ] . "/" . $cara_anv[ 'prf_mes_nasci' ] . ")";
                        }
			
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ] );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_aniversario_professor'";
		    
		    $rq = $sql->query( $query );
                }
                break;
	    case "task_feedback_membro":
                $data_atual = getdate();
                if( $cara[ 'ava_dia' ] != ""
                    && $cara[ 'ava_mes' ] != ""
                    && $cara[ 'ava_ano' ] != ""
                    && $cara[ 'ava_dia' ] != $data_atual[ 'mday' ] )
                {
                    $query = "
                        SELECT DISTINCT
                            agv_nome
                        FROM
                            candidato_din
                            NATURAL JOIN
                            aluno_gv
                        WHERE
                            DATE_PART ( 'day', cnd_fb_dt ) = DATE_PART( 'day', CURRENT_DATE ) AND
                            DATE_PART ( 'month', cnd_fb_dt ) = DATE_PART( 'month', CURRENT_DATE ) AND
                            DATE_PART ( 'year', cnd_fb_dt ) = DATE_PART( 'year', CURRENT_DATE ) AND
                            cnd_fb_solic = '1'
                        ORDER BY
                            agv_nome";

                    $rs_agv = $sql->query( $query );

		    $query = "
                        SELECT DISTINCT
                            cgv_id
                        FROM
                            ava_cgv
                            NATURAL JOIN
                            aviso_auto
                        WHERE
                            ava_mne = 'task_feedback_membro'";

		    $rs_cgv = $sql->query( $query );

		    if( is_array( $rs_cgv ) && is_array( $rs_agv ) )
		    {
			foreach( $rs_agv as $cara_agv )
			{
			    $cara[ 'ava_mensagem' ] .= "\n" . $cara_agv[ 'agv_nome' ];
                        }
			
			foreach( $rs_cgv as $cara_cgv )
			{
			    envia_task_cargo( $sql, $cara_cgv[ 'cgv_id' ], $rs_ttk[ 'ttk_id' ],  $cara[ 'ava_assunto' ],  $cara[ 'ava_mensagem' ] );
			}
			
		    }
		    
		    $query = "
                        UPDATE
                            aviso_auto
                        SET
                            ava_dt = CURRENT_TIMESTAMP
                        WHERE
                            ava_mne = 'task_feedback_membro'";
		    
		    $rq = $sql->query( $query );
                }
                break;
	    }
        }
    }
}

$rs = $sql->query( "COMMIT TRANSACTION" );
?>
