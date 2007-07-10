<? /* $Id: consultar.php,v 1.1 2002/08/05 13:30:21 binary Exp $ */ ?>

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
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["prf_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Departamento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= consulta_select_g($sql, "dpt_id", "dpt_nome", "departamento", $dados["dpt_id"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "prf_ddi" ] ) ? " (+" . $dados[ "prf_ddi" ] . ")" : "" ) .
                    ( consis_telefone( $dados[ "prf_ddd" ] ) ? " ("  . $dados[ "prf_ddd" ] . ")" : "" ) .
                    $dados[ "prf_telefone" ] )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["prf_ramal"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Fax</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["prf_fax"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["prf_celular"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["prf_email"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nascimento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["prf_dt_nasci"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ajuda EJ</td>
              <td bgcolor='#ffffff'><? $dados["prf_ajuda_ej"] == 1 ? print "Sim" : print "Não" ?></td>
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
