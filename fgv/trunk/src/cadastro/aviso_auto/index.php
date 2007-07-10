<?
require_once( INCPATH . "/debug.inc.php" );             /* biblioteca para debug.           */
require_once( INCPATH . "/sql_link.inc.php" );          /* biblioteca para uso do BD.       */
require_once( INCPATH . "/trata_dados.inc.php" );       /* funcoes de tratamento de dados   */
require_once( INCPATH . "/funcoes.php" );

extract_request_var( "subpagina", $subpagina );
extract_request_var( "acao", $acao );

switch( $acao )
{
    case "update_ava":
        if( ! tem_permissao( FUNC_CAD_AVISO_AUTO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }

	extract_request_var( "ava_mne", $ava_mne );
	extract_request_var( "ava_assunto", $ava_assunto );
	extract_request_var( "ava_mensagem", $ava_mensagem );


	if( $ava_assunto  == "" )
	    $status_ava = "Você precisa digitar um assunto.";
	else if( $ava_mensagem == "" )
	    $status_ava = "Você precisa digitar uma mensagem.";
	else
	    $rq = $sql->query( "
                UPDATE
                    aviso_auto
                SET
                    ava_assunto = '" . addslashes( $ava_assunto ) . "',
                    ava_mensagem = '" . addslashes( $ava_mensagem ) . "'
                WHERE
                    ava_mne = '" . $ava_mne . "'" );

	if( isset( $rq ) && $rq )
	    $status_ava = "Aviso Automático atualizado.";
	
	if( $status_ava != "" )
	{
	    $subpagina = "editar_ava";
	    $aviso_auto_mne = $ava_mne;
	}
	break;
    case "remover_ava_cgv":
        if( ! tem_permissao( FUNC_CAD_AVISO_AUTO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
	    extract_request_var( "ava_cgv_selecionado", $ava_cgv_selecionado );
	    
	    if( is_array( $ava_cgv_selecionado ) )
	    {
		foreach( $ava_cgv_selecionado as $ava_cgv_removendo )
		    {
			$ava_cgv_removendo_array = explode( "-", $ava_cgv_removendo );

			$rq = $sql->query( "
                            DELETE FROM
                                ava_cgv
                            WHERE
                                ava_id = '" . $ava_cgv_removendo_array[ 0 ] . "' AND
                                cgv_id = '" . $ava_cgv_removendo_array[ 1 ] . "'
                            ");
		    }
	    }
	    break;
    case "inserir_ava_cgv":
        if( ! tem_permissao( FUNC_CAD_AVISO_AUTO_ALTERAR ) )
        {
            $subpagina = "acesso_negado";
            break;
        }
	extract_request_var( "aviso_auto_ava_id", $aviso_auto_ava_id );
	extract_request_var( "cargo_cgv_id", $cargo_cgv_id );

	$busca_ava_cgv = $sql->squery( "
        SELECT DISTINCT
            ava_id,
            cgv_id
        FROM
            ava_cgv
         WHERE
            ava_id = '" . $aviso_auto_ava_id . "' AND
            cgv_id = '" . $cargo_cgv_id . "'" );

	if( !is_array( $busca_ava_cgv ) &&
	    $aviso_auto_ava_id != "" &&
	    $cargo_cgv_id != "" )
            {
                $rq = $sql->query( "
                INSERT INTO
                ava_cgv
                (
                    ava_id,
                    cgv_id
                )
                VALUES
                (
                    '" . $aviso_auto_ava_id . "',
                    '" . $cargo_cgv_id . "'
                )" );
            }
	break;
}

switch( $subpagina )
{
     case "editar_ava":
        if( ! tem_permissao( FUNC_CAD_AVISO_AUTO_ALTERAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }

	extract_request_var( "aviso_auto_mne", $aviso_auto_mne );
     
	$busca_ava = $sql->squery( "
        SELECT DISTINCT
            ava_id,
            ava_assunto,
            ava_mensagem,
            ava_mne,
            ava_tipo
        FROM
            aviso_auto
        WHERE
            ava_mne = '" . $aviso_auto_mne . "'" );
	?>
	<center>
	<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="630">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="9" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17" colspan="2"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Avisos Automáticos - Definir mensagem</td>
	</tr>	    
	<?
	if( isset( $status_ava ) && $status_ava != "" )
	{
            ?>
	    <tr>
	    <td bgcolor="#ffffff" class="text" colspan="2"><?= $status_ava ?></td>
            </tr>
            <?
	}
	?> 
	<tr>
	<td bgcolor="#ffffff" class="text">
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />   
        <input type="hidden" name="pagina" value="aviso_auto" />
	<input type="hidden" name="subpagina" value="update_ava" />
	<input type="hidden" name="acao" value="update_ava" />     
	<input type="hidden" name="ava_mne" value="<?= $busca_ava[ 'ava_mne' ] ?>" />
	<input type="hidden" name="aviso_auto_mne" value="<?= $aviso_auto_mne ?>" />      
	Assunto:
	</td>
        <td bgcolor="#ffffff" class="text">
	<input type="text" name="ava_assunto" value="<?= isset( $ava_assunto ) ? $ava_assunto : $busca_ava[ 'ava_assunto' ] ?>" size="30" />
	</td>
	</tr>
	<tr>
	<td bgcolor="#ffffff" class="text">
	Mensagem:
	</td>
        <td bgcolor="#ffffff" class="text">
	<textarea cols="55" rows="13" wrap="hard" name="ava_mensagem"><?= isset( $ava_mensagem ) ? $ava_mensagem : $busca_ava[ 'ava_mensagem' ] ?></textarea>
	</td>
        </tr>
        <tr>
	<td bgcolor="#ffffff" class="text" colspan="2">
	<input type="submit" value="Alterar" />
	</td>
	</form>
	</tr>
	<tr><td class="text" bgColor="#336699" colspan="2">&nbsp;</td></tr>     
	</table>
        </td></tr>
	</table><br /><br />

        <?
	if( isset( $busca_ava ) && is_array( $busca_ava ) && isset( $busca_ava[ 'ava_tipo' ] ) && $busca_ava[ 'ava_tipo' ] == 'task' )
        {
	?>       
        <table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="630">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="9" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" COLSPAN="5" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Enviar Para</td>
	</tr>
        <?
	$busca_ava_cgv = $sql->query( "
        SELECT DISTINCT
            ava_id,
            cgv_id,
            cgv_nome
        FROM
            ava_cgv
            NATURAL JOIN cargo_gv
        WHERE
            ava_id = '" . $busca_ava[ 'ava_id' ] . "'" );
	
        if( is_array( $busca_ava_cgv ) )
	{
	    ?>
            <tr>
            <td bgcolor="#ffffff" class="text">&nbsp;
            <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
            <input type="hidden" name="suppagina" value="cadastro" />
            <input type="hidden" name="pagina" value="aviso_auto" />
            <input type="hidden" name="subpagina" value="editar_ava" />
	    <input type="hidden" name="acao" value="remover_ava_cgv" />
	    <input type="hidden" name="aviso_auto_mne" value="<?= $aviso_auto_mne ?>" />  
            </td>
            <td bgcolor="#ffffff" class="text"><b>Cargo</b></td>
            </tr>
            <?
            foreach( $busca_ava_cgv as $ava_cgv )
            {
            ?>
                <tr>
                <td bgcolor="#ffffff" class="text">&nbsp;<input type="checkbox" name="ava_cgv_selecionado[]" value="<?= $ava_cgv[ 'ava_id' ] ?>-<?= $ava_cgv[ 'cgv_id' ] ?>" /></td>
                <td bgcolor="#ffffff" class="text">&nbsp;<?= $ava_cgv[ 'cgv_nome' ] ?></td>
                </tr>
            <?
            }
            ?>

            <tr>
            <td bgcolor="#ffffff" class="text" COLSPAN="2">
            <input type="submit" value="Remover" />
            </td>
            </form>
	    </tr>
	    <? 
	}
	else
	{
            ?> 
            <tr>
	    <td bgcolor="#ffffff" class="text">Nenhum cargo receberá esse aviso automático.</td>
            </tr>
            <? 
	}

	$busca_cargo = $sql->query( "
        SELECT DISTINCT
            cgv_id,
            cgv_nome
        FROM
            cargo_gv
        ORDER BY
            cgv_nome" );	
	?>

        <tr>
	<td bgcolor="#ffffff" class="text" colspan="<?= ( is_array( $busca_ava_cgv ) ? "2" : "1" ) ?>">
	Cargo:
	<form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="cadastro" />
        <input type="hidden" name="pagina" value="aviso_auto" />
        <input type="hidden" name="subpagina" value="editar_ava" />
	<input type="hidden" name="acao" value="inserir_ava_cgv" />
	<input type="hidden" name="aviso_auto_ava_id" value="<?= $busca_ava[ 'ava_id' ] ?>" />
        <input type="hidden" name="aviso_auto_mne" value="<?= $aviso_auto_mne ?>" /> 
	<? faz_select( "cargo_cgv_id", $busca_cargo, "cgv_id", "cgv_nome" ); ?>
	<input type="submit" value="Adicionar" />     
	</td>
	</form>
	</tr>	    
	<tr><td class="text" bgColor="#336699" colspan="<?= ( is_array( $busca_ava_cgv ) ? "2" : "1" ) ?>">&nbsp;</td></tr>	    
        </table>
        </td></tr>
	</table>
        <?
	}												       
	?>													       
	</center>     
	<br />

	<form>
        <input type="button" value="<<< Voltar" onclick="location='<?= $_SERVER[ 'SCRIPT_NAME' ] ?>?suppagina=<?= $suppagina ?>&pagina=aviso_auto'" />     
	</form>      
	      
	<?
	break;
    case "acesso_negado":
        include( ACESSO_NEGADO );
        break;
    default:
        if( ! tem_permissao( FUNC_CAD_AVISO_AUTO_LISTAR ) )
        {
            include( ACESSO_NEGADO );
            break;
        }
	$busca_ava = $sql->query( "
            SELECT DISTINCT
                ava_mne,
                ava_assunto
            FROM
                aviso_auto
            ORDER BY
                ava_assunto" );
	?>
	<center>
	<table border="0" CELLSPACING="0" CELLPADDING="0" bgColor="#000000" WIDTH="630">
        <tr><td>        
        <table border="0" CELLSPACING="1" CELLPADDING="9" WIDTH="100%" class="text">
        <tr>
        <td class="textwhitemini" bgColor="#336699" HEIGHT="17"><img SRC="images/icone.gif" WIDTH="23" HEIGHT="17" ALIGN="absbottom">&nbsp;&nbsp;Avisos Automáticos</td>
	</tr>
	<tr>
	<td bgcolor="#ffffff" class="text">
     
        <form action="<?= $_SERVER[ 'SCRIPT_NAME' ] ?>" method="post">
        <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />   
        <input type="hidden" name="pagina" value="aviso_auto" />
	<input type="hidden" name="subpagina" value="editar_ava" />     
	Aviso Automático:
	<? faz_select( "aviso_auto_mne", $busca_ava, "ava_mne", "ava_assunto" ); ?>
	<input type="submit" value="Editar">
	</td>
	</form>
	</tr>
	<tr><td class="text" bgColor="#336699">&nbsp;</td></tr>
	</table>
        </td></tr>
	</table>
	</center>     
	<?
	break;
}
?>
