#!/usr/bin/perl

require "cgi-lib.pl";
print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>UNNAMED Game Control</title>\n";
print " <meta http-equiv=\"refresh\" content=\"60; url=/cgi-bin/controle/cgi/controle.pl\">\n";
print "</head>\n";

print "<body vlink=#0000ff link=#0000ff style=\"decoration: none\">";

print "<H1 align=center><FONT color=#ff0000 face=Verdana>UNNAMED GAME CONTROL</FONT></H1><P>\n";
print "<TABLE height=29 style=\"HEIGHT: 29px; WIDTH: 716px\" width=80% align=\"center\" border=\"0\">\n";
    
print "    <TR>\n";
#print "        <!--TD align="middle" width="50%"><A href="inclusao.html" style="LEFT: 148px; TOP: 83px" >Incluir Usu&aacute;rio</A></TD-->\n";
print "        <TD align=\"middle\" width=\"50%\"><A href=\"/cgi-bin/controle/cgi/entrada.pl\" style=\"LEFT: 148px; TOP: 83px\" >Incluir Usu&aacute;rio</A></TD>\n";
#print "        <TD align="middle"  width="50%"><A href="paga.html" style="LEFT: 148px; TOP: 83px" >Pagamento</A></TD>\n";
print "	</TR>\n";
print "</TABLE>\n";
print "<br>\n";
print "<hr width=\"90%\">\n";
print "<H3 align=center><FONT face=Verdana>MONITORA&Ccedil;&Atilde;O</FONT></H3>\n";

print "<TABLE align=\"center\" border=\"1\" width=\"90%\">\n";
    
print "    <TR align=\"middle\">\n";
print "        <TD><STRONG>C&oacute;digo</STRONG></TD>\n";
print "        <TD><STRONG>Computador</STRONG></TD>\n";
print "        <TD><STRONG>Pre&ccedil;o/Hora</STRONG></TD>\n";
#print "        <TD><STRONG>In&iacute;cio</STRONG></TD>\n";
print "        <TD><STRONG>T.Total</STRONG></TD>\n";
print "	       <TD><B>Valor</B></TD>\n";
print "        <TD><STRONG>Opções</STRONG></TD>\n";
print "	</TR>\n";
print "<TR><TD></td><TD></td></tr>\n";

# aqui entram os registros 

open(ABTO,"../dados/em_aberto");
read(ABTO,$m_abto,100000);
close(ABTO);
@v_abto = split(/\012/,$m_abto);

foreach $i (0..$#v_abto) {

	@cad = split(/;/,$v_abto[$i]);


### Calculo do total de horas ###
($sec,$min,$hour) = localtime(time);

#if ($cad[3] ne "0") {
#    @h_ini = split(/:/,$cad[3]);
#    $t_ini = ($h_ini[0]*60)+$h_ini[1];
#    $t_fim = ($hour*60)+$min;
#    $t_total = abs($t_ini - $t_fim) + $cad[4];
#} else {
#    $t_total = $cad[4];
#}

if ($cad[5] ne "0") { 
    @tpo_ini = split(/:/,$cad[5]);
    
    
    if ($tpo_ini[0] > $hour) {
	$h_total = (24 - $tpo_ini[0])+$hour;
    } else {
	$h_total = $hour - $tpo_ini[0];
    }

    if ($tpo_ini[1] > $min) {
	$m_total = (60 - $tpo_ini[1])+$min;
        $h_total--;
    } else {
	$m_total = $min - $tpo_ini[1];
    }

    $tpo_total = (($h_total*60)+$m_total) + $cad[4];

} else {
    $tpo_total = $cad[4];
}


$h_tmp = $tpo_total/60;
$m_tmp = ($h_tmp - (int($h_tmp)))*60;
$t_total = int($h_tmp).":".int($m_tmp);


### calculo do valor ###

$val_min = $cad[2]/60;
$val_total = $tpo_total * $val_min;


if (($cad[2] eq "3") || ($cad[2] eq "4") || ($cad[2] eq "6")) {
    if ($tpo_total < 31) { $val_total = "2.0";}
} elsif ($cad[2] eq "6") {
    if ($tpo_total < 16) { $val_total = "2.0";}    
}


if ($cad[6]) {
    $val_total = $val_total + $cad[6];
}


$val_dec = $val_total - int($val_total);
$val_int = $val_total - $val_dec;
$val_dec = int($val_dec*100);
$val_total = $val_int.".".$val_dec;

	    
	print "<TR align=center>\n";
	print "<TD>\n";
	    print $cad[0];
	print "</TD>\n";
	print "<TD>\n";
	    print $cad[1];
	print "</TD>\n";
	print "<TD>\n";
	    print $cad[2];
	print "</TD>\n";
	print "<TD>\n";
#	    print "$h_total:$m_total";
	    print "$t_total";
#	    print $cad[3];
	print "</TD>\n";
	print "<TD>\n";
	    print $val_total;
	print "</TD>\n";

	print "<TD>\n";
	    print "<a href=\"/cgi-bin/controle/cgi/fecha.pl?micro=$cad[1]\">Fecha</a>&nbsp;&nbsp;&nbsp;";
	    if ($cad[5] ne "0") {
    		print "<a href=\"/cgi-bin/controle/cgi/para.pl?micro=$cad[1]\">Para</a>&nbsp;&nbsp;&nbsp;";
	    } else {
    		print "<a href=\"/cgi-bin/controle/cgi/reinicia.pl?micro=$cad[1]\">Reinicia</a>&nbsp;&nbsp;&nbsp;";	    
	    }
	    print "<a href=\"/cgi-bin/controle/cgi/add.pl?micro=$cad[1]\">Consumo</a>&nbsp;&nbsp;&nbsp;";
    	    print "<a href=\"/cgi-bin/controle/cgi/mudar.pl?micro=$cad[1]\">Mudar</a>&nbsp;&nbsp;&nbsp;";
	print "</TD>\n";

}

print "</TABLE>\n";
print "<P>&nbsp;</P>\n";
print "<P>\n";
print "<HR width=90%>\n";



#open(MIC,"../dados/micros");
#read(MIC,$f_micros,100000);
#close(MIC);
#@m_micros = split(/\012/,$f_micros);


#print "<TABLE align=\"center\" border=\"1\" width=\"90%\">\n";

#    $cont = 0;
#foreach $m (0..$#m_micros) {
#    @v_micros = split(/;/,$m_micros[$m]);
#    if ($cont = 5) {
#        print "    <TR align=\"middle\">\n";
#    }
#    print "	   <td></td>";
#    print "	   <td></td>";    
#    print "	   <td></td>";
#    print "	   <td></td>";
#    print "	   <td></td>";
#    print "	   <td></td>";
#    if ($cont = 5) {    
#        print "    </tr>";    
#	$cont = 0;
#    }    
#    $cont++;
#}

print "<P></P>\n";
print "<P>&nbsp;</P>\n";
print "</body>\n";
print "</html>\n";


