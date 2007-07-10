#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

### vejo a hora do sistema que sera a hora de entrada ###

($sec,$min,$hour) = localtime(time);


open(MIC,"< ../dados/em_aberto");
read(MIC,$f_abto,1000);
close(MIC);
@m_abto = split(/\012/,$f_abto);

foreach $t (0..$#m_abto) {
    @tmp = split(/;/,$m_abto[$t]);
    if ($dados{'micro'} eq $tmp[1]) {
	$m_cli = $m_abto[$t];
    }
}

@v_cli = split(/;/,$m_cli);

### pagina de retorno com a confirmacaum ###

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Parar Cronometro de cliente</title>\n";
print " <meta http-equiv=\"refresh\" content=\"100; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";

### Calculo do total de horas ###
#@h_ini = split(/:/,$v_cli[5]);

#$t_ini = ($h_ini[0]*60)+$h_ini[1];
#$t_fim = ($hour*60)+$min;
#$t_total = abs($t_ini - $t_fim) + $v_cli[4];

#$h_tmp = $t_total/60;
#$m_tmp = ($h_tmp- (int($h_tmp)))*60;
#$h_total = int($h_tmp).":".int($m_tmp);

#$val_total = ($v_cli[2]/60)*$t_total;




### Calculo do total de horas ###
($sec,$min,$hour) = localtime(time);


if ($v_cli[5] ne "0") { 
    @tpo_ini = split(/:/,$v_cli[5]);
    
    
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

    $tpo_total = (($h_total*60)+$m_total) + $v_cli[4];

} else {
    $tpo_total = $v_cli[4];
}


$h_tmp = $tpo_total/60;
$m_tmp = ($h_tmp - (int($h_tmp)))*60;
$t_total = int($h_tmp).":".int($m_tmp);


### calculo do valor ###

$val_min = $v_cli[2]/60;
$val_total = $tpo_total * $val_min;


if (($v_cli[2] eq "3") || ($v_cli[2] eq "4") || ($v_cli[2] eq "5")) {
    if ($tpo_total < 31) { $val_total = "2.5";}
} elsif ($v_cli[2] eq "6") {
    if ($tpo_total < 16) { $val_total = "2.0";}    
}


if ($v_cli[6]) {
    $val_total = $val_total + $v_cli[6];
}


$val_dec = $val_total - int($val_total);
$val_int = $val_total - $val_dec;
$val_dec = int($val_dec*100);
$val_total = $val_int.".".$val_dec;







### retorno os dados que chegaram do form entrada.pl ###

print "<center><b>";
print "<br><H1>Cronometro Parado</h1><br><br>";
print "Codigo: $v_cli[0]<br>Micro : $v_cli[1]<br>Valor/Hora: $v_cli[2]<br>Hora Inicial : $v_cli[3]<br>Hora da Parada: $hour:$min:$sec<br>Tempo Atual em minutos : $tpo_total<br>Valor Atual : $val_total<BR>Tempo Atual em horas $t_total\n";
print "<br><br><br>";
print "<table align=center border=0><tr>";
print "<td><a href=/cgi-bin/controle/cgi/controle.pl>Controle</a>&nbsp;&nbsp;&nbsp;</td>";
print "<td><a href=\"/cgi-bin/controle/cgi/reinicia.pl?micro=$v_cli[1]\">Reinicia</a></td>";
print "</tr></table>";
print "</center></b>";

print "</body>\n";
print "</html>\n";


### apendo na base de micros abertos o novo cliente ###

open(NOVO,"../dados/em_aberto");
read(NOVO,$f_abto,1000000);
close NOVO;
@v_abto = split(/\012/,$f_abto);

open(NOVO,"> ../dados/em_aberto");

foreach $t (0..$#v_abto) {
    @reg = split(/;/,$v_abto[$t]);
    if ($reg[1] eq $dados{'micro'}) {
	print NOVO "$reg[0];$reg[1];$reg[2];$reg[3];$tpo_total;0;$reg[6]\n";
    } else {
	print NOVO "$v_abto[$t]\n";	
    }
}
close NOVO;

