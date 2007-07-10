<? /* $Id: prop_and.php,v 1.2 2002/05/03 23:30:57 binary Exp $ */ ?>
<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>

<form name='f' method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="acao"        value="go" />

        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Gerar Proposta
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Prazo Final Entrega Proposta</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("cst_dt_prp_entrega", $dados["cst_dt_prp_entrega"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Coordernador</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "mem_id", "mem_nome", "membro_vivo", $dados["cst_prp_coordenador"], array("name" => "mem_id")) ?></td>
            </tr>
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Upload de Arquivo - Relatório de Primeira Reunião</td>
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
<!-- Fim - main -->
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>
</form>

<br />

<!-- Atividade x Prazo -->
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <tr>
            <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Atividades x Prazos</td>
          </tr>
          <? mostra_atividades($sql, $dados["cst_id"], $suppagina, $pagina, $subpagina) ?>
          <tr>
            <td bgcolor='#ffffff' colspan="7">
              <input type='button' name="ok" value='Inserir' OnClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=inserir&tipo_inserir=atividade&cst_id=" . $dados["cst_id"] ?>';" />
            </td>
          </tr>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>

<!-- Fim  atividade -->

  <br />

<!-- Tipo Projeto -->

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
            <tr>
              <td>
                <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">

<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="" />
    <input type="hidden" name="tipo_inserir" value="" />
    <input type="hidden" name="tipo_apagar" value="" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />

    <script language="javascript">
    function mudar(obj, cara)
    {
        if(obj.value.search("nserir") >= 0) /* Inserir */
        {
            obj.form.tipo_inserir.value = cara;
            obj.form.subpagina.value    = "inserir";
        }
        else
        {
            obj.form.tipo_apagar.value  = cara;
            obj.form.subpagina.value    = "apagar";
        }

        obj.form.ok.disabled = true;
        obj.form.submit();
    }
    </script>

      <tr>
        <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Tipos de Projeto</td>
      </tr>

      <tr>
        <td bgcolor='#ffffff' class="textb">Excluir</td>
        <td bgcolor='#ffffff' class="textb">Tipo de Projeto</td>
      </tr>
      <? mostra_tipos_projeto($sql, $dados["cst_id"]) ?>
      <tr>
        <td bgcolor='#ffffff'>&nbsp;</td>
        <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "tpj_id", "tpj_nome", "tipo_projeto", $dados["tpj_id"], array("name" => "tpj_id")) ?></td>
      </tr>
      <tr>
        <td bgcolor='#ffffff' colspan="3">
          <input type='button' name="ok" value='Inserir' OnClick="mudar(this, 'tipo_projeto');">
          <input type='button' name="ok" value='Apagar'  OnClick="mudar(this, 'tipo_projeto');"/>
        </td>
        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
    </table>
  </td>
</tr>
</table>
</form>
<!-- Fim -->
    
<br />

<!-- Professores -->

 <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
   <tr>
     <td>
       <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">

<form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>" enctype="multipart/form-data">
    <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
    <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
    <input type="hidden" name="subpagina"   value="" />
    <input type="hidden" name="tipo_inserir" value="" />
    <input type="hidden" name="tipo_apagar" value="" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
    <input type="hidden" name="status"      value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />
    <input type="hidden" name="cst_status"  value="<?= CST_PROPOSTA_EM_ANDAMENTO ?>" />

      <tr>
        <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Professores</td>
      </tr>

      <tr>
        <td bgcolor='#ffffff' class="textb">Excluir</td>
        <td bgcolor='#ffffff' class="textb">Professor</td>
      </tr>
      <? mostra_professores($sql, $dados["cst_id"], CST_PROPOSTA_EM_ANDAMENTO ) ?>
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

    <tr>
      <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
      <script language='JavaScript'>
        function binLaden( obj )
        {
            obj.disabled = true;
            document.f.submit( );
        }
      </script>

        <input type="button" value="&nbsp;Gerar Proposta&nbsp;" OnClick='binLaden( this );' />
        <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=consultoria&cst_id=" . $dados["cst_id"] ?>'" />
      </td>
    </tr>

        <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
    </table>
  </td>
</tr>
</table>
</form>

<!-- Fim -->

  <br />
  <br />
</center>
