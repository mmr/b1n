<?
/* $Id: index.php,v 1.4 2002/07/17 20:33:20 binary Exp $ */

ignore_user_abort( );

/*
 *
 * defines e variaveis de configuracao
 *
 */

$mod_titulo = 'Etiquetas';
$colspan = '2';

extract_request_var( 'acao',          $acao );
extract_request_var( 'tipo_etiqueta', $dados[ 'tipo_etiqueta' ] );
extract_request_var( 'busca_texto',   $dados[ 'busca_texto' ] );
extract_request_var( 'busca_campo',   $dados[ 'busca_campo' ] );

if( $subpagina == 'busca' || isset( $_SESSION[ 'busca' ][ 'etiqueta' ] ) )
{
    if( ! tem_permissao( FUNC_ADM_ETIQUETAS_CRIAR ) )
    {
        include( ACESSO_NEGADO );
        exit;
    }

    if( $subpagina == 'busca' )
    {
        $_SESSION[ 'busca' ][ 'etiqueta' ][ 'tipo_etiqueta' ]   = $dados[ 'tipo_etiqueta' ];
        $_SESSION[ 'busca' ][ 'etiqueta' ][ 'busca_campo' ]     = $dados[ 'busca_campo' ];
        $_SESSION[ 'busca' ][ 'etiqueta' ][ 'busca_texto' ]     = $dados[ 'busca_texto' ];
    }

    $dados[ 'tipo_etiqueta' ]   = $_SESSION[ 'busca' ][ 'etiqueta' ][ 'tipo_etiqueta' ];
    $dados[ 'busca_campo' ]     = $_SESSION[ 'busca' ][ 'etiqueta' ][ 'busca_campo' ];
    $dados[ 'busca_texto' ]     = $_SESSION[ 'busca' ][ 'etiqueta' ][ 'busca_texto' ];
?>
  <center>
  <script language='JavaScript'>
    function verifica( f )
    {
        var checkBox  = false;
        var selectBox = false;

        var checkBoxName  = 'caras_ids[]';
        var selectBoxName = 'campos[]';

        var i = 0;

        /* CheckBox */
        if( f.elements[ checkBoxName ].length )
        {
            for( i=0; i<f.elements[ checkBoxName ].length; i++ )
            {
                if( f.elements[ checkBoxName ][ i ].checked )
                {
                    checkBox = true;
                    break;
                }
            }
        }
        else
        {
            if( f.elements[ checkBoxName ].checked )
            {
                checkBox = true;
            }
        }

        /* SelectBox */
        for( i=0; i<f.elements[ selectBoxName ].options.length; i++ )
        {
            if( f.elements[ selectBoxName ].options[ i ].selected )
            {
                selectBox = true;
                break;
            }
        }

        if( ! checkBox )
            alert( 'Você precisa escolher alguém.' );
        
        if( ! selectBox )
            alert( 'Você precisa escolher um campo para a etiqueta.' );

        return ( checkBox && selectBox );
    }

    function aviso( )
    {
        return confirm( 'Essa operação pode demorar, por favor aguarde.' );
    }
  </script>
<?
    $error_msgs = array( );

    switch( $dados[ 'tipo_etiqueta' ] )
    {
        case 'aluno_gv':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                agv_id,
                agv_nome,
                agv_matricula,
                agv_email,
                agv_telefone
            FROM
                aluno_gv
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                agv_nome,
                agv_matricula";

            $rs = $sql->query( $query ); 

            $indice = 0;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>
            <?
            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Matrícula</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'agv_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'agv_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'agv_matricula' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'agv_email' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'agv_telefone' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='agv_nome' selected>Nome</option>
                      <option value='agv_matricula' selected>Matrícula</option>
                      <option value='agv_endereco'>Endereço</option>
                      <option value='agv_bairro'>Bairro</option>
                      <option value='agv_telefone' selected>Telefone</option>
                      <option value='agv_ramal'>Ramal</option>
                      <option value='agv_cep'>CEP</option>
                      <option value='agv_celular'>Celular</option>
                      <option value='agv_email' selected>Email</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'cliente':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                cli_id,
                cli_nome, 
                cli_email,
                cli_telefone,
                cli_nome_contato
            FROM
                cliente
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                cli_nome,
                cli_email";

            $rs = $sql->query( $query ); 

            $indice = 1;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Contato</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'cli_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'cli_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'cli_nome_contato' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'cli_email' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'cli_telefone' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='cli_nome' selected>Nome</option>
                      <option value='cli_razao'>Razão Social</option>
                      <option value='cli_endereco' selected>Endereço</option>
                      <option value='cli_bairro' selected>Bairro</option>
                      <option value='cli_cidade' selected>Cidade</option>
                      <option value='cli_estado' selected>Estado</option>
                      <option value='cli_cep' selected>CEP</option>
                      <option value='cli_nome_contato'>Nome do Contato</option>
                      <option value='cli_celular_contato'>Celular do Contato</option>
                      <option value='cli_telefone' selected>Telefone</option>
                      <option value='cli_fax'>FAX</option>
                      <option value='cli_ramal'>Ramal</option>
                      <option value='cli_email' selected>Email</option>
                      <option value='cli_homepage' selected>Homepage</option>
                      <option value='cli_faturamento'>Faturamento</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'membro':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                mem_id,
                mem_nome, 
                mem_login,
                mem_email,
                mem_telefone
            FROM
                membro_vivo
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                mem_nome,
                mem_login";

            $rs = $sql->query( $query ); 

            $indice = 2;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return verifica( this ) && aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Login</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'mem_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'mem_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'mem_login' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'mem_email' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'mem_telefone' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='mem_nome' selected>Nome</option>
                      <option value='mem_login'>Login</option>
                      <option value='mem_email' selected>Email</option>
                      <option value='mem_telefone' selected>Telefone</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'professor':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                prf_id,
                prf_nome, 
                prf_email,
                prf_telefone,
                prf_celular
            FROM
                professor
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                prf_nome,
                prf_email";

            $rs = $sql->query( $query ); 

            $indice = 1;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                          <td class='textb' bgcolor='#ffffff'>Celular</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'prf_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'prf_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'prf_telefone' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'prf_celular' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'prf_email' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='prf_nome' selected>Nome</option>
                      <option value='prf_telefone' selected>Telefone</option>
                      <option value='prf_celular' selected>Celular</option>
                      <option value='prf_email' selected>Email</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'fornecedor':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                for_id,
                for_nome, 
                for_email,
                for_telefone,
                for_celular
            FROM
                fornecedor
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                for_nome,
                for_email";

            $rs = $sql->query( $query ); 

            $indice = 1;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                          <td class='textb' bgcolor='#ffffff'>Celular</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'for_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'for_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'for_telefone' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'for_celular' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'for_email' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='for_nome' selected>Nome</option>
                      <option value='for_telefone' selected>Telefone</option>
                      <option value='for_celular' selected>Celular</option>
                      <option value='for_email' selected>Email</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'patrocinador':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                pat_id,
                pat_nome, 
                pat_email,
                pat_telefone,
                pat_celular
            FROM
                patrocinador
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                pat_nome,
                pat_email";

            $rs = $sql->query( $query ); 

            $indice = 1;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                          <td class='textb' bgcolor='#ffffff'>Celular</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'pat_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pat_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pat_telefone' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pat_celular' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pat_email' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='pat_nome' selected>Nome</option>
                      <option value='pat_telefone' selected>Telefone</option>
                      <option value='pat_celular' selected>Celular</option>
                      <option value='pat_email' selected>Email</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;
        case 'palestrante':
            if( $dados[ 'busca_campo' ] == '' )
            {
                array_push( $error_msgs, 'Você precisa escolher um campo para busca' );
                break;
            }

            $query = "
            SELECT
                pal_id,
                pal_nome, 
                pal_email,
                pal_telefone,
                pal_celular
            FROM
                palestrante
            WHERE
                " . $dados[ 'busca_campo' ] . " ILIKE '%" . in_bd( $dados[ 'busca_texto' ] ) . "%'
            ORDER BY
                pal_nome,
                pal_email";

            $rs = $sql->query( $query ); 

            $indice = 1;

            $colspan = 5;
            ?>

    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" OnSubmit='return aviso( );'>
                  <input type="hidden" name="suppagina" value="etiquetas" />
                  <input type="hidden" name="pagina"    value="criar_etiquetas" />
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Resultado da Busca
              </td>
            </tr>

            <?

            if( is_array( $rs ) )                
            {
            ?>
                        <tr>
                          <td class='textb' bgcolor='#ffffff'>&nbsp;</td>
                          <td class='textb' bgcolor='#ffffff'>Nome</td>
                          <td class='textb' bgcolor='#ffffff'>Telefone</td>
                          <td class='textb' bgcolor='#ffffff'>Celular</td>
                          <td class='textb' bgcolor='#ffffff'>Email</td>
                        </tr>
                <?    
                foreach( $rs as $cara )
                {
                ?>
                    <tr>
                      <td class='text' bgcolor='#ffffff'><input type='checkbox' name='caras_ids[]' class='caixa' value='<?= $cara[ 'pal_id' ] ?>' /></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pal_nome' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pal_telefone' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pal_celular' ] ?></td>
                      <td class='text' bgcolor='#ffffff'>&nbsp;<?= $cara[ 'pal_email' ] ?></td>
                    </tr>
                <?
                }
                ?>
                <tr>
                  <td class='textb' bgcolor='#ffffff'>Campos para etiqueta</td>
                  <td class='text' bgcolor='#ffffff' colspan='<?= $colspan - 1 ?>'>
                    <select name='campos[]' multiple>
                      <option value='pal_nome' selected>Nome</option>
                      <option value='pal_telefone' selected>Telefone</option>
                      <option value='pal_celular' selected>Celular</option>
                      <option value='pal_email' selected>Email</option>
                    </select>
                  </td>
                </tr>
                <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#ffffff" align='center'><input type='submit' name='ok' value=' Criar Etiquetas ' /></td></tr>
            <?
            }
            else
            {
            ?>
                <tr>
                  <td class='text' bgcolor='#ffffff'>Nenhum registro foi encontrado para sua busca</td>
                </tr>
            <?
            }
            ?>
                  </form>
                  <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
                </table>
              </td>
            </tr>
          </table>
        </center>
            <?
            break;


    }
    ?>

<br />
<?
}

if( ! tem_permissao( FUNC_ADM_ETIQUETAS_CRIAR ) )
{
    include( ACESSO_NEGADO );
    exit;
}
?>
<script language="javascript">
    function deleteOption( object, index )
    {
        object.options[ index ] = null;
    }

    function addOption( object, text, value )
    {
        var optionName = new Option( text, value, true, true )
        object.options[ object.length ] = optionName;
    }

    function muda( o ) 
    {
        s = o.form.busca_campo;
        s.disabled = false;

        /* Deletando todas opcoes */
        for (var i=s.options.length-1; i>=0; i--)
            deleteOption( s, i );

        /* Adicionando opcoes certas */
        switch( o.value )
        {
            case 'aluno_gv':
                addOption( s, 'Nome', 'agv_nome' );
                addOption( s, 'Matrícula', 'agv_matricula' );
                addOption( s, 'Telefone', 'agv_telefone' );
                addOption( s, 'Celular', 'agv_celular' );
                addOption( s, 'Email', 'agv_email' );
                break;
            case 'cliente':
                addOption( s, 'Nome', 'cli_nome' );
                addOption( s, 'Contato', 'cli_nome_contato' );
                addOption( s, 'Telefone', 'cli_telefone' );
                addOption( s, 'Email', 'cli_email' );
                addOption( s, 'HomePage', 'cli_homepage' );
                break;
            case 'membro':
                addOption( s, 'Nome', 'mem_nome' );
                addOption( s, 'Login', 'mem_login' );
                addOption( s, 'Apelido', 'mem_apelido' );
                addOption( s, 'Telefone', 'mem_telefone' );
                addOption( s, 'Celular', 'mem_celular' );
                addOption( s, 'Email', 'mem_email' );
                break;
            case 'professor':
                addOption( s, 'Nome', 'prf_nome' );
                addOption( s, 'Telefone', 'prf_telefone' );
                addOption( s, 'Celular', 'prf_celular' );
                addOption( s, 'Email', 'prf_email' );
                break;
            case 'fornecedor':
                addOption( s, 'Nome', 'for_nome' );
                addOption( s, 'Telefone', 'for_telefone' );
                addOption( s, 'Celular', 'for_celular' );
                addOption( s, 'Email', 'for_email' );
                addOption( s, 'HomePage', 'for_homepage' );
                break;
            case 'patrocinador':
                addOption( s, 'Nome', 'pat_nome' );
                addOption( s, 'Telefone', 'pat_telefone' );
                addOption( s, 'Celular', 'pat_celular' );
                addOption( s, 'Email', 'pat_email' );
                break;
            case 'palestrante':
                addOption( s, 'Nome', 'pal_nome' );
                addOption( s, 'Telefone', 'pal_telefone' );
                addOption( s, 'Celular', 'pal_celular' );
                addOption( s, 'Email', 'pal_email' );
                break;
        }

        s.options[ 0 ].selected = true;
    }
</script>
  <center>
    <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630" height=0>
      <tr>
        <td>
          <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="3" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Buscar
              </td>
            </tr>
            <? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
                        <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
            <?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
                        </font></td></tr>
            <? } ?>
            <tr>
              <td class='textb' bgcolor='#ffffff'>Tipo de Etiqueta</td>
              <td class='text' bgcolor='#ffffff'>
              <script language='JavaScript'>
              </script>
                <form method="post" name='f' action="<?= $_SERVER["SCRIPT_NAME"] ?>">
                  <input type="hidden" name="suppagina" value="<?= $suppagina ?>" />
                  <input type="hidden" name="pagina"    value="<?= $pagina ?>" />
                  <input type="hidden" name="subpagina" value="busca" />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='aluno_gv' OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'aluno_gv' ) ? ' checked' : '' ) ?> />Aluno GV<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='cliente'  OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'cliente' )  ? ' checked' : '' ) ?> />Cliente<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='membro'   OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'membro' )   ? ' checked' : '' ) ?> />Membro<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='professor'   OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'professor' )   ? ' checked' : '' ) ?> />Professor<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='fornecedor'   OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'fornecedor' )   ? ' checked' : '' ) ?> />Fornecedor<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='patrocinador'   OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'patrocinador' )   ? ' checked' : '' ) ?> />Patrocinador<br />
                  <input type='radio' name='tipo_etiqueta' class='caixa' value='palestrante'   OnClick='muda( this );'<?= ( ( $dados[ 'tipo_etiqueta' ] == 'palestrante' )   ? ' checked' : '' ) ?> />Palestrante<br />
              </td>
            </tr>
            <tr>
              <td class='textb' bgcolor='#ffffff'>Campo para Busca</td>
              <td class='text'  bgcolor='#ffffff'><select name='busca_campo'<?= ( ( $dados[ 'busca_campo' ] == '' ) ? ' disabled' : '' ) ?>></select></td>
            </tr>
            <tr>
              <td class='textb' bgcolor='#ffffff'>Texto para Busca</td>
              <td class='text' bgcolor='#ffffff'><input type='text' name='busca_texto' value='<?= in_html( $dados[ 'busca_texto' ] ) ?>' /></td>
            </tr>
            <tr>
              <td class='text' colspan='<?= $colspan ?>' bgcolor='#ffffff' align='center'>
                <input type="submit" name='ok' value=" Buscar " />
              </td>
            </tr>
            <? if( isset( $indice ) ){ ?>
              <script language='JavaScript'>
                muda( document.f.tipo_etiqueta[ <?= $indice ?> ] );
              </script>
            <? } ?>
            </form>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </table>
        </td>
      </tr>
    </table>
  </center>
  <br />
