<?
// $Id: view.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
?>
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
          <td class='box'>
            <input type='hidden' name='page'    value='<?= $page ?>' />
            <input type='hidden' name='action0' value='' />
            <input type='hidden' name='action1' value='' />
            <input type='hidden' name='fas_id'  value='<?= $reg_data['fas_id'] ?>' />
            &nbsp;&nbsp;<?= $page_title ?> - Visualizar
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br />

<!-- Front -->
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box c' colspan='4'>Front</td>
        </tr>
        <tr>
          <td class='formitem'>Ordem</td>
          <td colspan='3'>
            <?=  b1n_inHtml($reg_data['ordem']) ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Se&ccedil;&atilde;o</td>
          <td colspan='3'><?= (empty($reg_data['sec_id'])?'':b1n_viewSelected($sql, 'sec_id', 'sec_nome', 'secao', $reg_data['sec_id'])) ?></td>
        </tr>
        <tr>
          <td class='formitem'>Pagina&ccedil;&atilde;o</td>
          <td colspan='3'>
            Primeira: <?=  b1n_inHtml($reg_data['pag_ini']) ?><br />
            &Uacute;ltima: <?= b1n_inHtml($reg_data['pag_fin']) ?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Autores</td>
          <td colspan='3'>
<?
//Qtd: $reg_data['qt_autor']

$autor = array();
for($i=1; $i <= $reg_data['qt_autor']; $i++)
{
  $autor[] = $reg_data['aut_prinome'][$i] . ' ' . $reg_data['aut_sobnome'][$i];
}

echo implode('<br />', $autor);
?>
          </td>
        </tr>
        <tr>
          <td class='box' colspan='4'>&nbsp;</td>
        </tr>
        <tr>
          <td class='formitem'>Idiomas</td>
          <td colspan='3'>
            <?= $reg_data['qt_idioma'] ?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td class='formitem'>T&iacute;tulo</td>
          <td class='formitem'>Resumo</td>
          <td class='formitem'>Palavras-Chave</td>
        </tr>
<?
echo '<pre>';
for($i=1; $i <= $reg_data['qt_idioma']; $i++)
{
  // Titulo e resumo
?>
        <tr>
          <td width='10'><?= b1n_viewSelected($sql, 'idi_id', 'idi_nome', 'idioma', $reg_data['front_idi_id'][$i]) ?></td>
          <td><?= wordwrap(b1n_inHtml($reg_data['titulo'][$i]), b1n_DESC_MAX_CHARS, '<br />') ?></td>
          <td>
            <?= wordwrap(b1n_inHtml($reg_data['resumo'][$i]), b1n_DESC_MAX_CHARS*2, '<br />') ?>
          </td>
          <td>
<?
 // Qtd: $reg_data['qt_palchave'][$i]
  if($reg_data['qt_palchave'][$i] > 0)
  {
    echo implode('<br />', $reg_data['palchave'][$i]);
  }
?>
          </td>
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

<!-- Body -->
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box c' colspan='2'>Body</td>
        </tr>
        <tr>
          <td class='formitem'>Idioma</td>
          <td><?= b1n_viewSelected($sql, 'idi_id', 'idi_nome', 'idioma', $reg_data['body_idi_id']) ?></td>
        </tr>
        <tr>
          <td class='formitem'>Artigo em PDF</td>
          <td>
<?
if($reg_data['art_pdf'])
{
?>
            <a href='<?= b1n_UPLOAD_DIR_ARTIGO_PDF . '/' . $reg_data['id'] . '.pdf' ?>' rel='_blank'>Arquivo</a>
<?
}
else
{
  echo "N&atilde;o enviado";
}
?>
          </td>
        </tr>
        <tr>
          <td class='formitem'>Artigo em HTML (ZIP)</td>
          <td>
<?
if($reg_data['art_html'] && !empty($reg_data['art_html_pag']))
{
?>
            <a href='<?= b1n_UPLOAD_DIR_ARTIGO_HTML . '/' . $reg_data['id'] . '/' . $reg_data['art_html_pag'] ?>' rel='_blank'>Arquivo</a>
<?
}
else
{
  echo "N&atilde;o enviado";
}
?>
          </td>
        </tr>
        <tr>
          <td class='box' colspan='2'>&nbsp;</td>
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
            <input type='button' value=' Voltar ' onclick="location='<?= b1n_URL . '?page=' . $page . "&amp;fas_id=" . $reg_data['fas_id'] ?>'" />
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
