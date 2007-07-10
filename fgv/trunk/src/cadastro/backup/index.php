<?
ignore_user_abort( );
/* $Id: index.php,v 1.1 2002/07/30 13:06:54 binary Exp $ */

/*
 *
 * defines e variaveis de configuracao
 *
 */

if( ! tem_permissao( FUNC_CAD_BACKUP_RECUPERAR ) && ! tem_permissao( FUNC_CAD_BACKUP_CRIAR ) )
{
    include( ACESSO_NEGADO );
    exit;
}

$mod_titulo = "Backup";

extract_request_var( "acao",        $acao );

function recuperar_backup( $nome_input, &$error_msgs )
{
    if( $_FILES[ 'arq' ][ 'tmp_name' ] != 'none' )
    {
        if( $_FILES[ 'arq' ][ 'name' ] != '' )
        {
            clearstatcache();
            if( is_writable( UPLOAD_DIR ) )
            {
                if( $_FILES[ $nome_input ][ 'tmp_name' ] != 'none' )
                {
                    if( copy( $_FILES[ $nome_input ][ 'tmp_name' ], UPLOAD_DIR . "/" . $_FILES[ $nome_input ][ 'name' ] ) )
                    {
                        /* Copia de Seguranca */
                        $copia_seguranca = array( );
                        $error_level     = 0;
                        srand( ( double ) microtime( ) * 1000000 );
                        $tmp_file        = '/tmp/.fgv_tmp_file_' . rand( ) .  rand( );
                        
                        exec( 'echo  -e "fgv\n" | pg_dump fgv -u > ' . $tmp_file, $copia_seguranca, $error_level );

                        /* Recuperando Backup */
                        $retorno        = array( );
                        $error_level2   = 0;
                        exec( 'psql -U fgv fgv < ' . UPLOAD_DIR . "/" . $_FILES[ $nome_input ][ 'name' ], $retorno, $error_level2 );

                        if( $error_level2 == 0 )
                        {
                            unlink( UPLOAD_DIR . "/" . $_FILES[ $nome_input ][ 'name' ] );
                            unlink( $tmp_file );
                            return true;
                        }
                        else
                        {
                            array_push( $error_msgs, "Não foi possível utilizar o arquivo enviado como backup<br />Recuperando versão anterior" );
                            exec( 'psql -U fgv fgv < ' . $_FILES[ $nome_input ][ 'name' ], $retorno, $error_level2 );
                        }
                    }
                }
                else
                    array_push( $error_msgs, "Arquivo inválido ou muito grande para fazer Upload" );
            }
            else
                array_push( $error_msgs, "Não tem permissão de escrita no diretório de Upload" );
        }
        else
            array_push( $error_msgs, "Arquivo para Upload muito grande ou inválido" );
    }
    else
        array_push( $error_msgs, "Você precisa escolher um arquivo válido para Upload" );

    return false;
}
?>

<center>
<?
if( $acao == "recuperar_backup" )
{
    $subpagina = '';
    if( ! tem_permissao( FUNC_CAD_BACKUP_RECUPERAR ) )
    {
        include( ACESSO_NEGADO );
        exit;
    }

    /* Validacao */
    $error_msgs = array();

    ?>
    <!--
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class='textb' bgcolor="#ffffff">
                <font color='#336699'>Por favor, aguarde enquato o Backup está sendo restaurado...</font><br /><br />
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <br />
    -->
    <?
    if( recuperar_backup( 'arq', $error_msgs ) )
    {
        ?>
       <script language='JavaScript'>
       location = '<?= $_SERVER[ 'SCRIPT_NAME' ] . '?suppagina=' . $suppagina . '&pagina=' . $pagina . '&subpagina=sucesso' ?>';
       </script>
       <?
       exit;
    }
}

if( $subpagina ==  'sucesso' )
{
    ?>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
        <tr>
          <td class='textb' bgcolor="#ffffff">
            <font color='#336699'>Backup Recuperado com Sucesso</font><br /><br />
            Você é fortemente aconselhado a verificar se todas as permissões de acesso e usuários (inclusive o seu) constam no sistema.
          </td>
        </tr>
          </table>
        </td>
      </tr>
    </table>
    <?
}
else
{
    ?>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Criar Novo Backup
              </td>
            </tr>
            <? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                        <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
            <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                        </font></td></tr>
            <? } ?>
            <tr>
              <td class='textb' bgcolor="#ffffff" align='center'>
              <script language='JavaScript'>
                function go_ninja( f )
                {
                    if( confirm( 'Essa operação pode demorar, tem certeza que deseja efetuá-la?' ) )
                    {
                        f.ok.disabled = true;
                        return true;
                    }
                    return false;
                }

              </script>
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return go_ninja( this );'>
                  <input type="hidden" name="suppagina_old" value="<?= $suppagina ?>" />
                  <input type="hidden" name="suppagina" value="backup" />
                  <input type="hidden" name="pagina"    value="<?= $pagina ?>" />
                  <input type="hidden" name="subpagina" value="<?= $subpagina ?>" />
                  <input type="submit" name='ok'        value=" Criar Novo Backup " />
              </td>
            </tr>
            </form>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
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
              <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17" colspan='2'>
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Recuperar Backup
              </td>
            </tr>
            <tr>
              <td class='textb' bgcolor="#ffffff">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data" OnSubmit='return confirm( "Tem certeza que deseja recuperar o Backup?\n\nAtenção: essa operação não pode ser desfeita!\n\nEssa operação pode demorar, por favor aguarde." );'>
                  <input type="hidden" name="suppagina"  value="<?= $suppagina ?>" />
                  <input type="hidden" name="pagina"     value="<?= $pagina ?>" />
                  <input type="hidden" name="subpagina"  value="<?= $subpagina ?>" />
                  <input type="hidden" name="acao"       value="recuperar_backup" />
                  Upload de Arquivo de Backup
               </td>
               <td class='textb' bgcolor="#ffffff">
                  <input type="file" name="arq" size="30" />
               </td>
            </tr>
            <tr>
              <td class='textb' bgcolor="#ffffff" colspan='2' align='center'>
                <input type="submit" value=" Recuperar Backup " />
              </td>
            </tr>
            </form>
            <tr><td class="text" colspan="2" bgcolor="#336699">&nbsp;</td></tr>
          </table>
        </td>
      </tr>
    </table>
    <?
}
?>
</center>
