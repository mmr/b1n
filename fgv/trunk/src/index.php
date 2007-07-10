<?
/* $Id: index.php,v 1.50 2003/07/06 06:36:23 mmr Exp $ */

if(get_magic_quotes_gpc() || get_magic_quotes_runtime())
{
    die( 'nto desta aplicaçao e necessario desligar magic_quote_gpc e magic_quote_runtime do PHP' );
}

define( 'INCPATH',          'include' );
define( 'ALTURA_PADRAO',    '400' );
define( 'DEBUG',            '0' );

require_once( INCPATH . '/debug.inc.php' );             /* biblioteca para debug.           */
require_once( INCPATH . '/sql_link.inc.php' );          /* biblioteca para uso do BD.       */
require_once( INCPATH . '/trata_dados.inc.php' );       /* funcoes de tratamento de dados   */
require_once( INCPATH . '/busca.inc.php' );             /* funcoes para busca               */
require_once( INCPATH . '/select.inc.php' );            /* funcoes para gerar <select>      */
require_once( INCPATH . '/log.inc.php' );               /* funcoes para log de eventos      */
require_once( INCPATH . '/permissao.inc.php' );         /* funcoes de checagem de permissao */
require_once( INCPATH . '/upload.inc.php' );            /* funcoes pra upload               */

header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

$sql = new sqlLink( 'fgv',  INCPATH . '/sql_conf.inc.php' );

session_start();

extract_request_var('suppagina', $suppagina);
extract_request_var('pagina',    $pagina);
extract_request_var('subpagina', $subpagina);
extract_request_var('acao',      $acao);

if( ( !esta_logado() ) && ( !faz_login( $sql, $error_msgs, $logando ) ) )
    $inc = 'main/login.php';
else
{
    switch( $suppagina )
    {
    case 'logout':
        logout( $sql );
        $inc = 'main/login.php';
        break;
    case 'adm':
    case 'cadastro':
    case 'consultoria':
    case 'marketing':
    case 'rh':
    case 'timesheet':
    case 'relatorio':
    case 'grade_horario':
        $inc = $suppagina . "/index.php";
        break;
    case "download":
    case "etiquetas":
        include( $suppagina . "/index.php" );
        exit;
    case "imprimir_relatorio":
        include( "relatorio/rel_print.php" );
        exit;
    case "backup":
        extract_request_var( 'suppagina_old', $suppagina_old );

        if( ! tem_permissao( FUNC_ADM_BACKUP_CRIAR ) )
        {
            $suppagina = $suppagina_old;
            $inc = $suppagina . "/index.php";
            break;
        }
        
        $error_level    = 0;
        $retorno        = array( );
        $arquivo = "aberium-bkp-" . date( "Y-m-d" ) . ".sql";

        exec( 'echo  -e "fgv\n" | pg_dump fgv -u', $retorno, $error_level );

        if( $error_level == 0 )
        {
            include( $suppagina . "/index.php" );
        }
        else
        {
            $error_msgs = array( 'Erro inesperado! Não foi possível efetuar backup.' );
            $suppagina = $suppagina_old;
            $inc = $suppagina . "/index.php";
            break;
        }
        exit;
    default:
        if( isset( $logando ) )
        {
            header( "Location: " . $_SERVER['SCRIPT_NAME'] );
            exit;
        }
        $inc = "main/default.php";
        break;
    }
}
?>

<html>
<head>
  <title>Banco de Dados - Empresa Junior FGV</title>
</head>
<link rel="stylesheet" type="text/css" href="images/fgv.css">
  
<BODY BGCOLOR="#FFFFFF" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<!-- menu --> 
<table width="780" border="0" cellspacing="0" cellpadding="0">
    
  <!-- spacer -->
  <tr>
    <td bgcolor="#003366" height="30" valign="middle" class="textwhite" colspan='2'><IMG SRC="images/logo_mithos.gif">
<img src="images/trans.gif" WIDTH="485" HEIGHT="1"><img src="images/logo_fgv.gif">
    </td>
  </tr>
  <tr>
    <td  height="15" bgcolor="#ffffff" valign="top" colspan='2'>
      <table border="0" width="100%" bordercolor="#003366" cellspacing="0" cellpadding="0"  class="text">
        <tr>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>">HOME</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=adm">ADM</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=cadastro">CADASTROS</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=consultoria">CONSULTORIA</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=marketing">MARKETING</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=rh">RH</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=timesheet">TIMESHEET</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=relatorio">RELATÓRIOS</a></td>
          <td valign="bottom" align="center"><img src="images/pixel_azul.gif" width="1" height="15"></td>
          <td>&nbsp;&nbsp;&nbsp; <a href="<?= $_SERVER["SCRIPT_NAME"] ?>?suppagina=logout">LOGOUT</a></td>
          <td valign="bottom" align="center"><img src="images/trans.gif" width="1" height="20"></td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- spacer -->
  <tr>
    <td bgcolor="#003366" height="30" align="left">
      <font color="#ffffff" face="verdana, arial, helvetica, sans-serif" size="2">&nbsp;
<?
$seta = " <font color='#99CCFF' face='verdana, arial, helvetica, sans-serif' size='1'>&gt;&gt;</font> ";

if( session_is_registered("membro") && isset($_SESSION['membro']['nome']) )
{
    print "<a class='lmenu' href='" . $_SERVER['SCRIPT_NAME'] . "'>" . $_SESSION['membro']['nome'] . "</a>";

    if( isset($suppagina) && $suppagina != "" )
    {
        print $seta . "<a class='lmenu' href='" . $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "'>" . ucwords(str_replace("_"," ", $suppagina)) . "</a>";
        if( isset($pagina) && $pagina != "" )
        {
            print $seta . "<a class='lmenu' href='" . $_SERVER['SCRIPT_NAME'] . "?suppagina=" . $suppagina . "&pagina=" . $pagina . "'>" . ucwords(str_replace("_"," ", $pagina)) . "</a>";
            /*
            if( isset($subpagina) && $subpagina != "" )
                print $seta . ucwords( str_replace( "_", " ", $subpagina ) );
            if( isset($acao) && $acao != "" && $acao != "go" )
                print $seta . ucwords( str_replace( "_", " ", $acao ) );
            */
        }
    }
}
?>
      </font>
    </td>
    <td bgcolor="#003366" height="30" class='textb' align="right">
        <font color="#ffffff" face="verdana, arial, helvetica, sans-serif" size="1">
          <?= date( 'd/m/Y' ) ?>&nbsp;&nbsp;&nbsp;
        </font>
    </td>
  </tr>
</table>
<!-- fim  menu -->
    
<!-- Conteudo -->
<table width="780" cellspacing="0" cellpadding="0" border="0">
  <tr>
    <td bgcolor="#FFFFFF" width="778" valign='top'>
<?
if(isset($inc))
{
    include($inc);
    unset($inc);
}
?>
    </td>
    <td width="1" bgcolor="#000000" ><img src="images/trans.gif" width="1"></td>
  </tr>
</table>          
<!-- fim conteudo -->

<!-- rodape -->
<table border=0 width=780 bgcolor="#5a5a5a" cellspacing=0 cellpadding=0>
  <tr><td colspan=2><img src="images/trans.gif" border=0 width=1 height=1></td></tr>
  <tr><td class=textwhite width=95%>&nbsp;&nbsp;Copyright Aberium Systems (c) 2002</td> <td valign=middle width=5% align=right>&nbsp;</td></tr>
  <tr><td colspan=2><img src="images/trans.gif" border=0 width=1 height=2></td></tr>
</table>
<!-- fim rodape -->

<?
if( DEBUG == 1 || DEBUG == 3 )
{
    print '<pre>REQUEST ==&gt; ' . print_r( $_REQUEST ) . '</pre>';
}
?>

</body>
</html>
