<? /* $Id: alterar.php,v 1.6 2002/07/30 20:22:33 binary Exp $ */ ?>

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
            <input type="hidden" name="subpagina"   value="alterar">
            <input type="hidden" name="id"          value="<?= in_html($dados["id"]) ?>">
            <input type="hidden" name="acao"        value="go">
            <tr>
              <td class="textwhitemini" colspan="<?= $colspan ?>" bgcolor="#336699" height="17">
                <img src="images/icone.gif" width="23" height="17" align="absbottom">&nbsp;&nbsp;Alunos GV - Alterar
              </td>
            </tr>
 
<? if (isset($error_msgs) && is_array($error_msgs) && sizeof($error_msgs)) { ?>
            <tr><td bgcolor='#ffffff' colspan="<?= $colspan ?>"  align="center"><font color="#ff0000">
<?    foreach ($error_msgs as $msg) print in_html($msg)."<br>" ?>
            </font></td></tr>
<? } ?>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Matrícula</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_matricula" value="<?= in_html($dados["agv_matricula"]) ?>" size="30"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nome</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_nome" value="<?= in_html($dados["agv_nome"]) ?>" size="30"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>RG</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_rg" value="<?= in_html($dados["agv_rg"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>CPF</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_cpf" value="<?= in_html($dados["agv_cpf"]) ?>" size="30" /></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Curso</td>
              <td bgcolor='#ffffff'><?= in_html($dados["agv_curso"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Classe</td>
              <td bgcolor='#ffffff'><?= in_html($dados["agv_classe"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ano de entrada na GV</td>
              <td bgcolor='#ffffff'><?= in_html($dados["agv_ano_entrada"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Semestre de entrada na GV</td>
              <td bgcolor='#ffffff'><?= in_html($dados["agv_semestre_entrada"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Semestre Atual</td>
              <td bgcolor='#ffffff'><?= in_html($dados["agv_semestre_atual"]) ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Endereço</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_endereco" value="<?= in_html($dados["agv_endereco"]) ?>" size="30"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Bairro</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_bairro" value="<?= in_html($dados["agv_bairro"]) ?>" size="30"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Telefone</td>
              <td bgcolor='#ffffff'>
                <input type="text" name="agv_ddi" value="<?= in_html($dados["agv_ddi"]) ?>" size="2">
                <input type="text" name="agv_ddd" value="<?= in_html($dados["agv_ddd"]) ?>" size="3">
                <input type="text" name="agv_telefone" value="<?= in_html($dados["agv_telefone"]) ?>" size="9"> ([ DDI 99 ] [ DDD 999 ] 9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Ramal</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_ramal" value="<?= in_html($dados["agv_ramal"]) ?>" size="6"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>CEP</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_cep" value="<?= in_html($dados["agv_cep"]) ?>" size="9"> (99999-999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Celular</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_celular" value="<?= in_html($dados["agv_celular"]) ?>" size="8"> (9999-9999)</td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Email</td>
              <td bgcolor='#ffffff'><input type="text" name="agv_email" value="<?= in_html($dados["agv_email"]) ?>" size="30"></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Nascimento</td>
              <td bgcolor='#ffffff'>&nbsp;<? gera_select_data("agv_dt_nasci", $dados["agv_dt_nasci"], 1950, date("Y", time()) - 2); ?></td>
            </tr>
            <tr>
              <td bgcolor='#ffffff' class='textb'>Saída</td>
              <td bgcolor='#ffffff'><? gera_select_data("agv_dt_saida", $dados["agv_dt_saida"], 1950, date("Y", time()),1); ?> (Ex-Aluno)</td>
            </tr>
            <tr>
              <td colspan="<?= $colspan ?>" bgcolor='#ffffff'>
                <input type="submit" name="ok" value="&nbsp;Altera&nbsp;">
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
