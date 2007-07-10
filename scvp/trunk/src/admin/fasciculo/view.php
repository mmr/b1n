<?
// $Id: view.php,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $
$colspan = 4;
?>
<form method='post' action='<?= b1n_URL ?>'>
<table class='extbox'>
  <tr>
    <td>
      <table class='intbox'>
        <tr>
          <td class='box' colspan='<?= $colspan ?>'>
            &nbsp;&nbsp;<?= $page_title ?> - Visualizar
          </td>
        </tr>
        <tr>
          <td class='formitem'>Volume</td>
          <td class='formitem'>N&uacute;mero</td>
          <td class='formitem'>Num Seq</td>
          <td class='formitem'>Capa</td>
        </tr>
        <tr>
          <td>&nbsp;<?= $reg_data['fas_vol_num'] ?></td>
          <td>&nbsp;<?= $reg_data['fas_num'] ?></td>
          <td>&nbsp;<?= $reg_data['fas_seq_num'] ?></td>
          <td>&nbsp;<?= ($reg_data['fas_capa']?'<img src="'.b1n_UPLOAD_DIR_CAPA.'/'.$reg_data['id'].'.'.$reg_data['fas_capa_tipo'].'" alt="Capa do Fasciculo">':'') ?></td>
        </tr>
        <tr>
          <td colspan='<?= $colspan ?>' class='c'>
            <input type='button' value=' Voltar ' onclick="location='<?= b1n_URL . '?page=' . $page ?>'" />
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
