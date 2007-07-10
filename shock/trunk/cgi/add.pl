#!/usr/bin/perl

require "cgi-lib.pl";

print &PrintHeader;
&ReadParse(dado);

### Abro o a base de micros ###

### Pagina de entrada ###
print "<HTML>\n";
print "<HEAD>\n";
print "<TITLE>Add</TITLE>\n";
print "<META name=\"description\" content=\"\">\n";
print "<META name=\"keywords\" content=\"\">\n";
print "</HEAD>\n";
print "<BODY BGCOLOR=\"#FFFFFF\" TEXT=\"#000000\" LINK=\"#0000F0\">\n";
print "<H1 align=center><FONT color=#ff0000 face=Verdana>UNNAMED GAME CONTROL</FONT></H1>\n";
print "<H2>Adcionar gasto ao Cliente :</H2>\n";

print "<form action=\"adiciona.pl\" method=\"get\">\n"; 
print "<TABLE border=0 align=center height=34 style=\"HEIGHT: 34px; WIDTH: 731px\" width=731 border=1 731px\" WIDTH: 94px;>\n";
print "    <TR>\n";
print "        <TD><B>Micro : $dado{'micro'}</B>\n";
print "		<input type=hidden name=micro value=$dado{'micro'}>";
print "        <TD><b>Valor : <input type=text name=\"add\"></b></tr>\n";
print "        <tr><td><input type=submit value=\"Adcionar\"></td>\n";
print "        <td><a href=/cgi-bin/controle/cgi/controle.pl>Cancelar</a></td></TR></TABLE>\n";
print "<P></P>\n";
print "    <br>\n";

print "</form>\n";
print "</BODY>\n";
print "</HTML>\n";




