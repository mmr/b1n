<? /* $Id: inserir.php,v 1.10 2002/08/02 15:12:23 binary Exp $ */ ?>

<br />
<br />
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>" />
            <input type="hidden" name="pagina"      value="<?= $pagina ?>" />
            <input type="hidden" name="subpagina"   value="<?= $subpagina ?>" />
            <input type="hidden" name="acao"        value="go" />
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom" />&nbsp;&nbsp;<?= $mod_titulo . " - " . ucfirst($subpagina) ?>
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Aluno</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "agv_id", "agv_nome", "aluno_vivo_nao_membro", $dados["agv_id"], array("name" => "agv_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Login</td>
              <td bgcolor='#ffffff'><input type="text" name="mem_login" value="<?= in_html($dados["mem_login"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Senha</td>
              <td bgcolor='#ffffff'><input type="password" name="mem_senha" value="" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Confirmação da Senha</td>
              <td bgcolor='#ffffff'><input type="password" name="mem_senha2" value="" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Posição Ocupada</td>
              <td bgcolor='#ffffff'>&nbsp;<?= gera_select_g($sql, "cgv_id", "cgv_nome", "cargo_gv", $dados["cgv_id"], array("name" => "cgv_id")) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Apelido</td>
              <td bgcolor='#ffffff'><input type="text" name="mem_apelido" value="<?= in_html($dados["mem_apelido"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Código do Banco</td>
              <td bgcolor='#ffffff'><input type="text" name="mem_cod_banco" value="<?= in_html($dados["mem_cod_banco"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Agência Bancária</td>
              <td bgcolor='#ffffff'><input type="text" name="mem_ag_banco" value="<?= in_html($dados["mem_ag_banco"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Conta Corrente</td>
              <td bgcolor='#ffffff'><input type="text" name="mem_cc_banco" value="<?= in_html($dados["mem_cc_banco"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Entrada</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("mem_dt_entrada", array("dia" => 0, "mes" => "0", "ano" => "0"), 1950, date( "Y" ) ) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Saída</td>
              <td bgcolor='#ffffff'><? gera_select_data("mem_dt_saida", array("dia" => 0, "mes" => "0", "ano" => "0"), 1950, date("Y") ); ?> (Ex-Membro)</td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;<?= ucwords($subpagina) ?>&nbsp;" />
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
