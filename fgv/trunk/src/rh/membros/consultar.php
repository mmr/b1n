<? /* $Id: consultar.php,v 1.10 2002/07/30 20:22:27 binary Exp $ */ ?>

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
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>" />
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
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "agv_id", "agv_nome", "aluno_gv", $dados["agv_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Login</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["mem_login"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Posição Ocupada</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "cgv_id", "cgv_nome", "cargo_gv", $dados["cgv_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Apelido</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["mem_apelido"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Código do Banco</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["mem_cod_banco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Agência Bancária</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["mem_ag_banco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Conta Corrente</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["mem_cc_banco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Entrada</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["mem_dt_entrada"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Data de Saída</td>
              <td bgcolor='#ffffff'><? $dados["mem_dt_saida"]["dia"] != "" ? print implode("/", $dados["mem_dt_saida"]) : print "----"; ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' colspan='2' class='textb'>
                <a target='_blank' href='<?= $_SERVER[ 'SCRIPT_NAME' ] . "?suppagina=grade_horario&mem_id=" . $dados[ 'id' ] ?>'>Consultar Grade de Horários</a>
              </td>
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
