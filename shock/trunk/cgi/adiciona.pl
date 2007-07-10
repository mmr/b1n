#!/usr/bin/perl

require "cgi-lib.pl";

&ReadParse(dados);

print &PrintHeader;

print "<html>\n";
print "<head>\n";
print "	<title>Adicao de Consumo</title>\n";
print " <meta http-equiv=\"refresh\" content=\"1; url=/cgi-bin/controle/cgi/controle.pl\">";
print "</head>\n";
print "<body>\n";
print "<center><b>";
print "<br><H1>Consumo adicionado</h1><br><br>";
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
	$val = $reg[6] + $dados{'add'};
	print NOVO "$reg[0];$reg[1];$reg[2];$reg[3];$reg[4];$reg[5];$val\n";
    } else {
	print NOVO "$v_abto[$t]\n";	
    }
}
close NOVO;
