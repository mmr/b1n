<?
// $Id: add.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
$colspan = 2;
?>
<script type='text/javascript'><!--
function b1n_mudou(f)
{
  f.action0.value = '';
  f.submit();
}
// --></script>
<script type="text/javascript"><!-- // load htmlarea
_editor_url = "htmlarea/"; // URL to htmlarea files
var win_ie_ver = parseFloat(navigator.appVersion.split("MSIE")[1]);
if (navigator.userAgent.indexOf('Mac')        >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Windows CE') >= 0) { win_ie_ver = 0; }
if (navigator.userAgent.indexOf('Opera')      >= 0) { win_ie_ver = 0; }
if (win_ie_ver >= 5.5) {
 document.write('<scr' + 'ipt src="' +_editor_url+ 'editor.js"');
 document.write(' language="Javascript1.2"></scr' + 'ipt>');  
} else { document.write('<scr'+'ipt>function editor_generate() { return false; }</scr'+'ipt>'); }
// --></script> 
<form method='post' action='<?= b1n_URL ?>' enctype='multipart/form-data'>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
            <input type='hidden' name='page'    value='<?= $page ?>' />
            <input type='hidden' name='action0' value='<?= $action1 ?>' />
            <input type='hidden' name='action1' value='<?= $action1 ?>' />
            <input type='hidden' name='fas_id'  value='<?= $reg_data['fas_id'] ?>' />
            &nbsp;&nbsp;<?= $page_title ?> - Adicionar
          </td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>'>
            <i>Itens com o '<b>*</b>' s&atilde;o obrigat&oacute;rios</i>
          </td>
        </tr>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />
<?
// select da secao
$select_sec = b1n_buildSelectCommon($sql, 'sec_id', 'sec_id', 'sec_nome', 'secao', $reg_data['sec_id'], array(), 'sec_ativo = 1 AND sec_real_id IS NULL');
?>
<!-- Front -->
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box c' colspan='4'>Front</td>
        </tr>
        <tr>
          <td class='formitem'>*Ordem</td>
          <td colspan='3'>
            <input name='ordem' value='<?=  b1n_inHtml($reg_data['ordem']) ?>' maxlength='2' size='2' />
          </td>
        </tr>
        <tr>
          <td class='formitem'>Se&ccedil;&atilde;o</td>
          <td colspan='3'><?= $select_sec ?></td>
        </tr>
        <tr>
          <td class='formitem'>Pagina&ccedil;&atilde;o</td>
          <td colspan='3'>
            Primeira <input name='pag_ini' value='<?=  b1n_inHtml($reg_data['pag_ini']) ?>' maxlength='4' size='4' />
            &Uacute;ltima <input name='pag_fin' value='<?= b1n_inHtml($reg_data['pag_fin']) ?>' maxlength='4' size='4' />
          </td>
        </tr>
        <tr>
          <td class='formitem'>Autores</td>
          <td colspan='3'>
            Quantidade: <select name='qt_autor' onchange='b1n_mudou(this.form)'>
<?
for($i=1; $i<= 25; $i++)
{
?>
              <option value='<?= $i ?>'<?= ($reg_data['qt_autor'] == $i)?' selected="selected"':''?>><?= $i ?></option>
<?
}
?>
            </select>
            <br />
<?
for($i=1; $i <= $reg_data['qt_autor']; $i++)
{
?>
            Nome <input type='text' name='aut_prinome[<?= $i ?>]' value='<?= b1n_inHtml($reg_data['aut_prinome'][$i]) ?>' maxlength='128' />
            Sobrenome <input type='text' name='aut_sobnome[<?= $i ?>]' value='<?= b1n_inHtml($reg_data['aut_sobnome'][$i]) ?>' maxlength='255' />
            <br />
<?
}
?>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='box'>&nbsp;</td>
        </tr>
        <tr>
          <td class='formitem'>*Idiomas</td>
          <td colspan='3'>
            Quantidade: <select name='qt_idioma' onchange='b1n_mudou(this.form)'>
              <option value=''>---</option>
<?
for($i=1; $i <= 5; $i++)
{
?>
              <option value='<?= $i ?>'<?= ($reg_data['qt_idioma'] == $i)?' selected="selected"':''?>><?= $i ?></option>
<?
}
?>
            </select>
          </td>
        </tr>
<?
$select_idi = b1n_buildSelectCommon($sql, '{NOME}', 'idi_id', 'idi_nome', 'idioma', '', array(), 'idi_ativo = 1');

if(b1n_checkNumeric($reg_data['qt_idioma']) && $reg_data['qt_idioma'] >= 1 && $reg_data['qt_idioma'] <= 5)
{
?>
        <tr>
          <td>&nbsp;</td>
          <td class='formitem'>T&iacute;tulo</td>
          <td class='formitem'>Resumo</td>
          <td class='formitem'>Palavras-Chave</td>
        </tr>
<?
  for($i=1; $i <= $reg_data['qt_idioma']; $i++)
  {
    $front_select_idi = str_replace('{NOME}', 'front_idi_id['.$i.']', $select_idi);
    $front_select_idi = str_replace("value='".$reg_data['front_idi_id'][$i]."'>", "value='".$reg_data['front_idi_id'][$i]."' selected='selected'>", $front_select_idi);
    // Titulo e resumo
?>
        <tr>
          <td>*<?= $front_select_idi ?></td>
          <td><input type='text' name='titulo[<?= $i ?>]' value='<?= b1n_inHtml($reg_data['titulo'][$i]) ?>' maxlength='255' /></td>
          <td>
            <textarea name='resumo[<?= $i ?>]' rows='7' cols='39' /><?= b1n_inHtml($reg_data['resumo'][$i]) ?></textarea>
            <script>//editor_generate('resumo[<?= $i ?>]')</script>
            </td>
          <td>
            Quantidade: <select name='qt_palchave[<?= $i ?>]' onchange='b1n_mudou(this.form)'>
<?
    // Pal Chave
    if(!b1n_checkNumeric($reg_data['qt_palchave'][$i]) || $reg_data['qt_palchave'][$i] < 1 || $reg_data['qt_palchave'][$i] > 10)
    {
      $reg_data['qt_palchave'][$i] = 4;
    }

    for($j=1; $j <= 10; $j++)
    {
?>
              <option value='<?= $j ?>'<?= ($reg_data['qt_palchave'][$i] == $j)?' selected="selected"':''?>><?= $j ?></option>
<?
    }
?>
            </select>
            <br />
<?
    for($j=1; $j <= $reg_data['qt_palchave'][$i]; $j++)
    {
?>
            <input type='text' name='palchave[<?= $i ?>][<?= $j ?>]' value='<?= b1n_inHtml($reg_data['palchave'][$i][$j]) ?>' /><br />
<?
    }
?>
          </td>
        </tr>
<?    
  }
}
else
{
?>
        <tr>
          <td class='c' colspan='4'>Escolha a quantidade de Idiomas</td>
        </tr>
<?  
}
?>
        <tr>
          <td class='box' colspan='4'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />

<?
// select do idioma
$body_select_idi = str_replace('{NOME}', 'body_idi_id', $select_idi);
$body_select_idi = str_replace("value='".$reg_data['body_idi_id']."'>", "value='".$reg_data['body_idi_id']."' selected='selected'>", $body_select_idi);

// checagem para autores
if(!b1n_checkNumeric($reg_data['qt_autor']) || $reg_data['qt_autor'] < 1 || $reg_data['qt_autor'] > 25)
{
  $reg_data['qt_autor'] = 4;
}
?>
<!-- Body -->
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box c' colspan='2'>Body</td>
        </tr>
        <tr>
          <td class='c' colspan='<?= $colspan ?>'>
            <i>Por favor, somente escolha os arquivos pra Upload quando tiver preenchido tudo</i>
          </td>
        </tr>
        <tr>
          <td class='formitem'>*Idioma</td>
          <td><?= $body_select_idi ?></td>
        </tr>
        <tr>
          <td class='formitem'>Artigo em PDF</td>
          <td><input type='file' name='arquivo_pdf' /></td>
        </tr>
        <tr>
          <td class='formitem'>Artigo em HTML (ZIP)</td>
          <td><input type='file' name='arquivo_html' /> <b>Apenas arquivos .ZIP s&atilde;o suportados</b></td>
        </tr>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />

<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='submit' value=' Adicionar ' />
            <input type='button' value=' Cancelar ' onclick="location='<?= b1n_URL . '?page=' . $page . "&amp;fas_id=" . $reg_data['fas_id'] ?>'" />
          </td>
        </tr>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>&nbsp;</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
