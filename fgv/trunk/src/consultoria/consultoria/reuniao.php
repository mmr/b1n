<? /* $Id: reuniao.php,v 1.4 2002/07/16 20:40:17 binary Exp $ */ ?>
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
    <input type="hidden" name="status"      value="<?= CST_REUNIAO_MARCADA ?>" />
    <input type="hidden" name="cst_status"  value="<?= $dados["cst_status"] ?>" />
    <input type="hidden" name="cst_id"      value="<?= $dados["cst_id"] ?>" />
   <input type="hidden" name="acao"        value="go" />
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - <?= (($dados["cst_status"] == CST_NOVA_CONSULTORIA) ? "Marcar" : "Reagendar / Nova") . " Reunião" ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Data de Reunião</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("cst_dt_reuniao", $dados["cst_dt_reuniao"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Horário</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_hora("cst_dt_reuniao", $dados["cst_dt_reuniao"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Local</td>
              <td bgcolor='#ffffff'><input type="text" name="cst_local_reuniao" value="<?= in_html($dados["cst_local_reuniao"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">Upload de Arquivo</td>
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

</form>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <br />
  <br />

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
    <input type="hidden" name="status"      value="<?= CST_REUNIAO_MARCADA ?>" />

        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
    <script language="javascript">
    function mudar(obj)
    {
        if(obj.value.search("serir") >= 0) /* Inserir */
        {
            obj.form.tipo_inserir.value  = "consultor_reuniao";
            obj.form.subpagina.value      = "inserir";
        }
        else
        {
            obj.form.tipo_apagar.value    = "consultor_reuniao";
            obj.form.subpagina.value      = "apagar";
        }

        obj.form.ok.disabled = true;
        obj.form.submit();
    }

    function binLaden(obj)
    {
        obj.disabled = true;
        document.f.submit( );
    }
    </script>


                  <tr>
                    <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                      Consultores Alocados
                    </td>  
                  </tr>   
                  <tr>
                    <td bgcolor='#ffffff' class="textb">Excluir</td>
                    <td bgcolor='#ffffff' class="textb">Consultor</td>
                  </tr>
                  <? mostra_consultores($sql, $dados["cst_id"], $dados["cst_status"], "consultor_reuniao") ?>
                  <tr>
                    <td bgcolor='#ffffff'>&nbsp;</td>
                    <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "mem_id", "mem_nome", "membro_vivo", $dados["mem_id"], array("name" => "mem_id")) ?></td>
                  </tr>
                  <tr>
                    <td bgcolor='#ffffff' colspan="3">
                      <input type='button' name="ok" value='Inserir' OnClick="mudar(this);" />
                      <input type='button' name="ok" value='Apagar'  OnClick="mudar(this);" />
                    </td>
                  </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="button" value="&nbsp;Marcar Reunião&nbsp;" OnClick="binLaden(this);"  />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "&subpagina=alterar&tipo_alterar=consultoria&cst_id=" . $dados["cst_id"] ?>'" />
              </td>
            </tr>

            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>
</form>
  <br />
  <br />
</center>
