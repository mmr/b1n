<? /* $Id: consultar.php,v 1.6 2002/07/30 20:22:33 binary Exp $ */ ?>

<? $colspan = 4; ?>

<br>
<br>
<center>
  <table border="0" cellspacing="0" cellpadding="0" bgcolor="#000000" width="630">
    <tr>
      <td>
        <table border="0" cellspacing="1" cellpadding="5" width="100%" class="text">
          <form method="post" action="<?= $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden" name="suppagina"   value="<?= $suppagina ?>">
            <input type="hidden" name="pagina"      value="<?= $pagina ?>">
            <input type="hidden" name="subpagina"   value="consultar">
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>">
            <input type="hidden" name="acao"        value="go">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom">&nbsp;&nbsp;Alunos GV - Consultar
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Matrícula</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_matricula"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_nome"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>RG</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_rg"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>CPF</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_cpf"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Endereço</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_endereco"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Bairro</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_bairro"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>&nbsp;
                <?= in_html(
                    ( consis_telefone( $dados[ "agv_ddi" ] ) ? " (+" . $dados[ "agv_ddi" ] . ")" : "" ) .
                    ( consis_telefone( $dados[ "agv_ddd" ] ) ? " ("  . $dados[ "agv_ddd" ] . ")" : "" ) .
                    $dados[ "agv_telefone" ] )
                ?>
              </td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_ramal"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>CEP</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_cep"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_celular"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'>&nbsp;<?= in_html($dados["agv_email"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nascimento</td>
              <td bgcolor='#ffffff'>&nbsp;<?= implode("/", $dados["agv_dt_nasci"]); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Saída</td>
              <td bgcolor='#ffffff'><? $dados["agv_dt_saida"]["dia"] != "" ? print implode("/", $dados["agv_dt_saida"]) : print "----"; ?></td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="button" value="Cancelar" onClick="location='<?= $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina ?>'">
              </td>
            </tr>
            <tr><td class="text" colspan="<?= $colspan ?>" bgcolor="#336699">&nbsp;</td></tr>
          </form>
        </table>
      </td>
    </tr>
  </table>
  <br>
  <br>
</center>
