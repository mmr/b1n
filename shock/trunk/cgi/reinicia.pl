#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

### vejo a hora do sistema que sera a hora de entrada ###

($sec,$min,$hour) = localtime(time);

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Cronometro Reiniciado</title>\n";
print " <meta http-equiv=\"refresh\" content=\"1; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";
print "<center><b>";
print "<br><H1>Cronometro Reiniciado</h1><br><br>";
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
	print NOVO "$reg[0];$reg[1];$reg[2];$reg[3];$reg[4];$hour:$min;$reg[6]\n";
    } else {
	print NOVO "$v_abto[$t]\n";	
    }
}
close NOVO;
