<?
/* $Id: projeto.php,v 1.4 2002/07/16 20:40:17 binary Exp $ */

$js='';
if( consis_inteiro( $dados[ 'bri_id' ] ) )
    $js = 'document.f_prj.bri_id.disabled = false;';
?>
<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data" name="f_prj">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= $dados["cst_status"] ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="acao"        value="go" />

        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - <?= ( ($dados["cst_status"] == CST_PROJETO_FINALIZADO) ?  "Modificando Projeto" : "Projeto em Andamento" ) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data Início do Projeto</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("cst_dt_prj_ini", $dados["cst_dt_prj_ini"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb" colspan="2">
                <input type="checkbox" name="bri_chk" OnChange='( ( this.checked == true ) ? ( this.form.bri_id.disabled = false ) : (this.form.bri_id.disabled = true ) )'<? if($dados["bri_id"] != "") print " checked"; ?>>
                Brinde Enviado para Professores Orientadores
                &nbsp;&nbsp;
                <?= gera_select_g($sql, "bri_id", "bri_nome", "brinde", $dados["bri_id"], array("name" => "bri_id", "disabled" => "")) ?>
                <script language='javascript'>
                    <?= $js ?>
                </script>
              </td>
            </tr>
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Upload de Arquivo<?= (($dados["arq_nome_real"] != "") ? " <a href='" . $dados["arq_nome_real"] . "'>" . $dados["arq_nome_falso"] . "</a>" : "") ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Descrição do Arquivo</td>
              <td bgcolor='#ffffff'><input type="text" name="arq_texto" value="<?= in_html($dados["arq_texto"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Arquivo para Upload</td>
              <td bgcolor='#ffffff' class="textb">
                <input type="file" name="arq" />
                <?
                if( consis_inteiro( $dados[ "arq_id" ] ) && $dados[ "arq_nome_falso" ] != "" )
                {
                ?>
                    <br />
                    Aquivo Atual: 
                    <a href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=download&id=" . $dados[ 'arq_id' ] . "&tabela=arquivo&col_id=arq_id&arq_col_r=arq_nome_real&arq_col_f=arq_nome_falso" ?>'><?= $dados[ 'arq_nome_falso' ] ?></a>
                    <br />
                <?
                }
                ?>
              </td>
            </tr>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</form>
</table>

<!-- Fim Main -->

<br />

<script language="javascript">
function mudar(obj, cara)
{
    if(obj.value.search("serir") >= 0) /* Inserir */
    {
        obj.form.tipo_inserir.value  = cara;
        obj.form.subpagina.value      = "inserir";
    }
    else
    {
        obj.form.tipo_apagar.value    = cara;
        obj.form.subpagina.value      = "apagar";
    }

    obj.form.ok.disabled = true;
    obj.form.submit();
}
</script>

<!-- Consultores -->

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>

<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="" />
    <input type="hidden" name="tipo_inserir" value="" />
    <input type="hidden" name="tipo_apagar" value="" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />

        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
      <tr>
        <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
          Consultores Alocados
        </td>  
      </tr>   
      <tr>
        <td bgcolor='#ffffff' class="textb">Excluir</td>
        <td bgcolor='#ffffff' class="textb">Consultor</td>
      </tr>
      <? mostra_consultores($sql, $dados["cst_id"], CST_PROJETO_EM_ANDAMENTO, "consultor_projeto") ?>
      <tr>
        <td bgcolor='#ffffff'>&nbsp;</td>
        <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "mem_id", "mem_nome", "membro_vivo", $dados["mem_id"], array("name" => "mem_id")) ?></td>
      </tr>
      <tr>
        <td bgcolor='#ffffff' colspan="3">
          <input type='button' name="ok" value='Inserir' OnClick="mudar(this, 'consultor_projeto');">
          <input type='button' name="ok" value='Apagar'  OnClick="mudar(this, 'consultor_projeto');"/>
        </td>
      </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</table>
</form>
<!-- Fim Consultores -->

<br />

<!-- Professores Orientadores -->

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>

<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="" />
    <input type="hidden" name="tipo_inserir" value="" />
    <input type="hidden" name="tipo_apagar" value="" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />

        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
      <tr>
        <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
          Professores Orientadores
        </td>  
      </tr>   
      <tr>
        <td bgcolor='#ffffff' class="textb">Excluir</td>
        <td bgcolor='#ffffff' class="textb">Professor</td>
      </tr>
      <? mostra_professores($sql, $dados["cst_id"], CST_PROJETO_EM_ANDAMENTO) ?>
      <tr>
        <td bgcolor='#ffffff'>&nbsp;</td>
        <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "prf_id", "prf_nome", "professor", $dados["prf_id"], array("name" => "prf_id")) ?></td>
      </tr>
      <tr>
        <td bgcolor='#ffffff' colspan="3">
          <input type='button' name="ok" value='Inserir' OnClick="mudar(this, 'professor');">
          <input type='button' name="ok" value='Apagar'  OnClick="mudar(this, 'professor');"/>
        </td>
      </tr>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</table>
</form>
<!-- Fim Professores -->

<br />

<!-- Etapas -->
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <tr>
            <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Etapas do Projeto</td>
          </tr>
          <? mostra_etapas($sql, $dados["cst_id"], $suppagina, $pagina, $subpagina) ?>
          <tr>
            <td bgcolor='#ffffff' colspan="7">
              <input type='button' name="ok" value='Inserir' OnClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir&tipo_inserir=etapa&cst_id=" . $dados["cst_id"] ?>';">
            </td>
          </tr>
        <tr><td class="text" colspan="7" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</table>
<!-- Fim Etapas -->

<br />

<!-- Dados de Cobranca do Projeto -->
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <tr>
            <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
            <script language='JavaScript'>
            function muda_valor( obj )
            {
                obj.form.subpagina.value    = 'alterar';
                obj.form.tipo_alterar.value = 'valor_projeto';

                // se for a primeira vez que passa o ppg_id nao ta setado, portanto "x" == "x", entao vai dar submit sem o aviso
                if( "x<?= $dados[ "ppg_id" ] ?>" == "x" )
                    obj.form.submit( );

                // se chegou aqui eh porque o ppg_id ta setado, ele ja tem plano de pagamento, se ele mudar as cobrancas serao excluidas
                // tem de confirmar antes
                if( confirm( 'Mudando o plano de pagamento você perderá todas cobranças já cadastradas\n\nEstá certo disso?\n' ) )
                    obj.form.submit( );
                else
                {
                    obj.form.cst_valor.value = '<?= formata_dinheiro( $dados[ "cst_valor" ] ) ?>';

                    for( i=0; i < obj.options.length; i++ )
                    {
                        if( obj.options[ i ].value = '<?= $dados[ "ppg_id" ] ?>' )
                            obj.options[ i ].selected = true;
                    }
                }
            }

            function muda_plano( obj )
            {
                // se for a primeira vez, dar submit direto ja que nao vai ter ppg_id nenhum
                if( "x<?= $dados[ "ppg_id" ] ?>" == "x" )
                    obj.form.submit( );
            }
            </script>

<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROJETO_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="ppg_id"      value="<?= $dados["ppg_id"] ?>" />
    <input type="hidden" name="ppg_plano"   value="<?= $dados["ppg_plano"] ?>" />
    <input type="hidden" name="tipo_alterar" value="" />
                    Dados de Cobrança do Projeto</td>
                  </tr>
                  <tr>
                    <td bgcolor='#ffffff' class="textb">Valor do Projeto</td>
                    <td bgcolor='#ffffff' class='textb' colspan='8'>R$<input type="text" name="cst_valor" value="<?= in_html( formata_dinheiro( $dados[ "cst_valor" ] ) ) ?>" size="10"></td>
                  </tr>
                  <tr>
                    <td bgcolor='#ffffff' class="textb">Plano de Pagamento</td>
                    <td bgcolor='#ffffff' class='text' colspan='8'><?= gera_select_g( $sql, "ppg_id", "ppg_nome", "plano_pgto", $dados[ "ppg_id" ], array( "name" => "ppg_id", "OnChange" => "if( this.options[ this.selectedIndex ].value != '' ) muda_plano( this );"  ), 1 ) ?></td>
                  </tr>
                <?
                if( consis_inteiro( $dados[ 'ppg_id' ] ) )
                {
                    if( $dados[ 'ppg_plano' ] == '' )
                    {
                        $rs = $sql->squery( "
                            SELECT
                                ppg_plano
                            FROM
                                plano_pgto
                            WHERE
                                ppg_id = '" . $dados[ 'ppg_id' ] . "'" );
                        
                        if( $rs )
                            $dados[ 'ppg_plano' ] = $rs[ 'ppg_plano' ];
                    }

                    if( $dados[ 'ppg_plano' ] != '' )
                    {
                    ?>
                        <tr>
                          <td bgcolor='#ffffff' class='textb'>N&ordm; Parcelas: </td>
                          <td bgcolor='#ffffff' class='text' colspan='8'><?= $dados[ 'ppg_plano' ] ?></td>
                        </tr>
                        <?
                        $cob_total = mostra_cobrancas( $sql,  $dados[ "cst_id" ], $dados[ "ppg_id" ], $suppagina, $pagina, $subpagina );

                        if( $cob_total < $dados[ 'ppg_plano' ] )
                        {
                        ?>
                            <tr>
                              <td bgcolor='#ffffff' colspan="8">
                                <input type='button' name="ok" value=' Inserir Nova Cobrança ' OnClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir&tipo_inserir=cobranca&ppg_id=" . $dados["ppg_id"] . "&ppg_plano=" . $dados["ppg_plano"] . "&cst_id=" . $dados["cst_id"] ?>';">
                              </td>
                            </tr>
                        <?
                        }
                        ?>
                      <tr>
                        <td colspan="8" bgcolor='#ffffff'>
                          <input type="button" name="ok" value="&nbsp;Alterar Valor do Projeto / Plano de Pagamento&nbsp;" OnClick='muda_valor( this );' />
                        </td>
                      </tr>
                    <?
                    }
                }
                ?>

            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
              <script language='JavaScript'>
            function binLaden( obj )
            {
                obj.disabled = true;    
                document.f_prj.submit( );
            }
              </script>
                <input type="button" value="&nbsp;OK&nbsp;" OnClick='binLaden( this );' />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=consultoria&cst_id=" . $dados["cst_id"] ?>'" />
              </td>
            </tr>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
</form>
  </table>
<!-- Fim -->
  <br />
  <br />
</center>
