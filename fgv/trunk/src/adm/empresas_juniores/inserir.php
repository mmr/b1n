<? /* $Id: inserir.php,v 1.6 2002/07/12 16:06:15 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina    ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>" />
            <input type="hidden" name="acao"        value="go" />
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br />" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_nome" value="<?= in_html($dados["eju_nome"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Razão Social</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_razao" value="<?= in_html($dados["eju_razao"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Endereço</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_endereco" value="<?= in_html($dados["eju_endereco"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Bairro</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_bairro" value="<?= in_html($dados["eju_bairro"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cidade</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_cidade" value="<?= in_html($dados["eju_cidade"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Estado</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_estado($dados["eju_estado"], array("name" => "eju_estado")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Região</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "reg_id", "reg_nome", "regiao", $dados["reg_id"], array("name" => "reg_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">CEP</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_cep" value="<?= in_html($dados["eju_cep"]) ?>" size="9" /> (99999-999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>
                <input type="text" name="eju_ddi" value="<?= in_html($dados["eju_ddi"]) ?>" size="2">
                <input type="text" name="eju_ddd" value="<?= in_html($dados["eju_ddd"]) ?>" size="3">
                <input type="text" name="eju_telefone" value="<?= in_html($dados["eju_telefone"]) ?>" size="9"> ([ DDI 99 ] [ DDD 999 ] 9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Ramal</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_ramal" value="<?= in_html($dados["eju_ramal"]) ?>" size="6" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Fax</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_fax" value="<?= in_html($dados["eju_fax"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Email</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_email" value="<?= in_html($dados["eju_email"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">HomePage</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_homepage" value="<?= in_html($dados["eju_homepage"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Faculdade</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_faculdade" value="<?= in_html($dados["eju_faculdade"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Relações Estreitas</td>
              <td bgcolor='#ffffff'><input type="checkbox" value="1" name="eju_rel_estreita"<? if($dados["eju_rel_estreita"] == 1) print " checked"; ?> /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome do Contato</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_nome_contato" value="<?= in_html($dados["eju_nome_contato"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Celular do Contato</td>
              <td bgcolor='#ffffff'><input type="text" name="eju_celular_contato" value="<?= in_html($dados["eju_celular_contato"]) ?>" size="9" /> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cargo do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["cex_id"], array("name" => "cex_id")) ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;<?= ucfirst($subpagina) ?>&nbsp;" />
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'" />
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
  <br />
  <br />
</center>
