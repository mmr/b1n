# $Id: Makefile,v 1.1 2003/07/14 02:03:42 mmr Exp $

PROG=	bot

all: bot

bot:
	as -o $(PROG).o $(PROG).s && ld -o $(PROG) $(PROG).o

clean:
	rm -f $(PROG) $(PROG).o
