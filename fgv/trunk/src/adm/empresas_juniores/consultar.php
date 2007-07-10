<? /* $Id: consultar.php,v 1.6 2002/07/12 16:06:15 binary Exp $ */ ?>

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
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Razão Social</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_razao"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Endereço</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_endereco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Bairro</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_bairro"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cidade</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_razao"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Estado</td>
              <td bgcolor='#ffffff'>&nbsp;<?= $dados["eju_estado"] ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Região</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "reg_id", "reg_nome", "regiao", $dados["reg_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">CEP</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_cep"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "eju_ddi" ] ) ? " (+" . $dados[ "eju_ddi" ] . ")" : "" ) .
                    ( consis_telefone( $dados[ "eju_ddd" ] ) ? " ("  . $dados[ "eju_ddd" ] . ")" : "" ) .
                    $dados[ "eju_telefone" ] )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Ramal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_ramal"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Fax</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_fax"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Email</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_email"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">HomePage</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_homepage"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Faculdade</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_faculdade"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Relações Estreitas</td>
              <td bgcolor='#ffffff'><? $dados["eju_rel_estreita"] == 1 ? print "Sim" : print "Não" ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_nome_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Celular do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["eju_celular_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cargo do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["cex_id"]) ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
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
