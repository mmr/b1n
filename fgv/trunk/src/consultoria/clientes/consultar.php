<? /* $Id: consultar.php,v 1.7 2002/07/12 18:08:36 binary Exp $ */ ?>

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
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Razão Social</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_razao"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Faturamento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html( formata_dinheiro( $dados[ "cli_faturamento" ], 1) ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Ramo de Atividade</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "ram_id", "ram_nome", "ramo", $dados["ram_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Endereço</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_endereco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Bairro</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_bairro"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cidade</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_razao"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Estado</td>
              <td bgcolor='#ffffff'>&nbsp;<?= $dados["cli_estado"] ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Região</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "reg_id", "reg_nome", "regiao", $dados["reg_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">CEP</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_cep"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "cli_ddi" ] ) ? " (+" . $dados[ "cli_ddi" ] . ")" : "" ) .
                    ( consis_telefone( $dados[ "cli_ddd" ] ) ? " ("  . $dados[ "cli_ddd" ] . ")" : "" ) .
                    $dados[ "cli_telefone" ] )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Ramal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_ramal"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Fax</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_fax"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Email</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_email"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">HomePage</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_homepage"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Nome do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_nome_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Celular do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_celular_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Cargo do Contato</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cex_id", "cex_nome", "cargo_ext", $dados["cex_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Como conheceu a EJ</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_conheceu_ej"]) ?></td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <br />

  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo ?> - Dados de Cobrança
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">CNPJ / CPF</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_cob_cnpj"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Responsável Legal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["cli_cob_resp"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Contato</td>
              <td bgcolor='#ffffff'><?= in_html($dados["cli_cob_contato"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Endereço</td>
              <td bgcolor='#ffffff'><?= in_html( $dados["cli_cob_endereco"] == '' ?  $dados["cli_endereco"] : $dados["cli_cob_endereco"] ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">CEP</td>
              <td bgcolor='#ffffff'><?= in_html( consis_telefone( $dados["cli_cob_cep"]) ? $dados["cli_cep"] : $dados["cli_cob_cep"] ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "cli_cob_ddi" ] ) ? " (+" . $dados[ "cli_cob_ddi" ] . ")" :
                        ( consis_telefone( $dados[ "cli_ddi" ] ) ? " (+" . $dados[ "cli_ddi" ]     . ")" : "" ) ) .
                    ( consis_telefone( $dados[ "cli_cob_ddd" ] ) ? " ("  . $dados[ "cli_cob_ddd" ] . ")" :
                        ( consis_telefone( $dados[ "cli_ddd" ] ) ? " ("  . $dados[ "cli_ddd" ]     . ")" : "" ) ) .
                    ( consis_telefone( $dados[ "cli_cob_telefone" ] ) ? $dados[ "cli_cob_telefone" ] : $dados[ "cli_telefone" ] ) )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class="textb">Fax</td>
              <td bgcolor='#ffffff'><?= ( ( $dados["cli_cob_fax"]) == '' ? in_html( $dados["cli_fax"] ) : in_html( $dados["cli_cob_fax"] ) ) ?></td>
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
