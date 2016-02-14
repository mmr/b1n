<?
/**
 * @author Marcio Ribeiro <mribeiro (a) gmail com>
 * @created 2005-10-15
 * @version $Id: index.php,v 1.10 2005/10/29 16:46:24 mmr Exp $
 */
echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.1//EN' '/comum/dtd/xhtml11.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' >
<head>
  <link rel='shortcut icon' href='/comum/img/favicon.ico' />
  <title>.:b1n.org:.</title>
  <link rel='stylesheet' href='/comum/css/css.css' />
  <style type='text/css'>
<?
define('b1n_D_QTD_MIN', 1);
define('b1n_D_QTD_MAX', 21);
define('b1n_D_QTD_DEF', 1);
if (isset($_GET['d'])) {
  $d_qtd = $_GET['d'];
  if($d_qtd < b1n_D_QTD_MIN || $d_qtd > b1n_D_QTD_MAX){
    $d_qtd = b1n_D_QTD_DEF;
  }
} else {
  $d_qtd = rand(b1n_D_QTD_MIN, b1n_D_QTD_MAX/2);
}
$common = array();
$divs = '';
for ($i=0;$i<$d_qtd;$i++) {
    $col = sprintf('%02x%02x%02x', rand(0,128), rand(0,128), rand(200,255));
    $common[] = 'div.d'.$i;
    $divs .= "div.d".$i."{color: #".$col.";z-index:".$i.";top:".(16+$i)."px;left:".(35+$i)."%}\n";
}
$d_qtd++;
$common[] = 'div.d'.$i;
$divs .= "div.d".$i."{color: #ffffff;z-index:".$i.";top:".(16+$i)."px;left:".(35+$i)."%}\n";

if (is_array($common) && sizeof($common)) {
?>
  <?= implode(',',$common); ?> {
    background: none;
    font-family: Verdana, Helvetica, sans-serif;
    font-size: 3em;
    font-weight: bold;
    text-decoration: none;
    position: absolute
  }
<?
  echo $divs;
}
?>
  p{text-align:justify;text-indent:1cm}
  h3{text-transform:capitalize;background-color:#88aacc;color:#ffffff;width:100%}
  a:link,a:visited,a:hover,a:active{font-weight:bold}
  td.t{width:100%;height:100%;background-color:#88aacc}
  a.t:link,a.t:visited{color:#ffffff}
  a.t:active,a.t:hover{text-decoration:underline overline}
  body{padding-left:7%;padding-right:7%}
  </style>
</head>
<body>
<div style='text-align:center'>
<?
for ($i=0;$i<$d_qtd;$i++) {
?>
  <div class='d<?=$i?>'>B1N.ORG</div>
<?
}

$por_linha_i = 1;
$por_linha_j = 3;
$largura_j = ceil(100/$por_linha_j); 
?>
  <br /><br /><br />
</div>
<hr />
<div>
<h3><img src='/comum/img/ico.gif' alt='' /><a id='saudacoes'></a>Sauda&ccedil;&otilde;es</h3>
<p>
Ol&aacute;, sou <a rel='_blank' href='http://cv.b1n.org/'>Marcio Ribeiro</a> e por algum motivo voc&ecirc; chegou at&eacute; meu espa&ccedil;o na Internet, espero que tudo que ler por essas bandas lhe seja &uacute;til de alguma forma.
</p>

<br />
<h3><img src='/comum/img/ico.gif' alt='' /><a id='sobre'></a>Sobre mim</h3>
<p>
Sou mmr (Marcio Marcos Ribeiro), nascido em S&atilde;o Paulo em 12 de Mar&ccedil;o de 1983, casado (oficialmente) com Priscila em 18 de Janeiro de 2003 e pai de tr&ecirc;s lindos filhos. Marcio, nascido em 05 de Dezembro de 1999, Melissa, nascida em 06 de Agosto de 2003 e Marcos nascido em 18 de Janeiro de 2005 (sim! no meu anivers&aacute;rio de casamento). Mais sobre mim pode ser lido em <a rel='_blank' href='http://cv.b1n.org/'>http://cv.b1n.org/</a>.
</p>
<p>
Amante antigo de jogos de todas as sortes (eletr&ocirc;nicos, RPGs, cartas), gaitista iniciante, zagueiro persistente, ecl&eacute;tico quanto &agrave; m&uacute;sica, aficionado por tecnologia, estudo e trabalho com computadores desde os 14 anos de idade, tendo trabalhado, em tempos remotos, com Clipper, Cobol e Pascal, acompanhando, de perto, a populariza&ccedil;&atilde;o das BBS's (Bulletin Board System) e, mais tarde, da Internet no Brasil. Atualmente atuo, mais especificamente, nas &aacute;reas de admnistra&ccedil;&atilde;o de servidores Unix, desenvolvimento voltado para a Internet e modelamento/desenho de Bancos de Dados (SGBD).
</p>
<br />
<h3><img src='/comum/img/ico.gif' alt='' /><a id='dominio'></a>O dom&iacute;nio</h3>
<p>
A hist&oacute;ria do nome do dom&iacute;nio data de alguns anos, quando trabalhava na <a rel='_blank' href='http://www.aberium.com/'>Aberium</a>, onde conheci pessoas extremamente inteligentes e divertidas, entre elas estava Felipe Gustavo Almeida (Galmeida), CTO e grande amigo, que tinha (e, at&eacute; a escrita desse texto, tem) o dom&iacute;nio <a rel='_blank' href='http://a0z.org/'>a0z.org</a>, gostei da id&eacute;ia de ter um dom&iacute;nio com poucos caracteres, f&aacute;cil de se lembrar. Escrevi um programa simples que trabalhava em conjunto &agrave; algumas entidades respons&aacute;veis por registros .com, .org e .net e buscava dom&iacute;nios dispon&iacute;veis com o m&iacute;nimo de caracteres v&aacute;lidos poss&iacute;vel. Depois de algum tempo, percebi que simplesmente n&atilde;o haviam dom&iacute;nios dispon&iacute;veis com menos que tr&ecirc;s caracteres. Compilei uma lista dos dom&iacute;nios poss&iacute;veis com tr&ecirc;s caracteres e gostei de b1n por coincidir com um pseud&ocirc;nimo que usava na &eacute;poca (binary, BinarySoul) se trocar o '1' por 'i'.
</p>
<br />
<h3><img src='/comum/img/ico.gif' alt='' /><a id='maquina'></a>A m&aacute;quina</h3>
<p>
O dom&iacute;nio (e todos seus filhos) aponta para meu servidor pessoal, <a rel='_blank' href='http://server.b1n.org/'>Jaspion</a>. Jaspion "nasceu" em alguma semana de outubro de 2001, comprado de um colega da Ma&iacute;ra (amiga da <a rel='_blank' href='http://www.aberium.com/'>Aberium</a>), o antigo nome da m&aacute;quina era George e trabalhava arduamente dia ap&oacute;s dia em um CPD, ao lado de seus "irm&atilde;os" (que, como ele, herdaram nomes de Beatles, ali&aacute;s, nesse mesmo dia, o Galmeida, que voc&ecirc;s j&aacute; conhecem, comprou o Ringo que tinha configura&ccedil;&atilde;o bastante parecida com a do George, atual Jaspion), com <a rel='_blank' href='http://www.microsoft.com/'>Windows NT</a> e servi&ccedil;os diversos como PDC (Primary Domain Controler), MTA (Mail Transfer Agent) e afins instalados. Atualmente roda <a rel='_blank' href='http://www.openbsd.org/'>OpenBSD</a> e uma s&eacute;rie de servi&ccedil;os.
</p>

<hr />
<div style='text-align: center'>
  &copy; 2000-2004 <a href='http://marcio.b1n.org/' rel='_blank'>Marcio Ribeiro</a>
  <a href='http://validator.w3.org/check/referer' rel='_blank'><img
      style='border:0;width:88px;height:31px'
      src='/comum/img/valid-xhtml11'
      alt='Valid XHTML 1.1!' /></a>
  <a href='http://jigsaw.w3.org/css-validator/' rel='_blank'><img
        style='border:0;width:88px;height:31px'
        src='/comum/img/vcss' 
        alt='Valid CSS!' /></a><br />
$Id: index.php,v 1.10 2005/10/29 16:46:24 mmr Exp $
</div>
<script type='text/javascript' src='/comum/js/targets.js'></script>
</body>
</html>
