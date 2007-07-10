#
# $Id: Makefile.pri,v 1.1 2006/10/15 21:38:17 mmr Exp $
# mmr <mmr@b1n.org> 
# Started: Fri Oct 29 21:15:31 BRST 2004
#

#XSLT ?= /usr/local/bin/sabcmd
#XSLT  ?= /usr/local/bin/xsltproc
XSLT  ?= /sw/bin/xsltproc
#LATEX ?= /usr/local/bin/latex 
LATEX ?= /sw/bin/latex
#DVIPS ?= /usr/local/bin/dvips
DVIPS ?= /sw/bin/dvips
#PDFLATEX ?= /usr/local/bin/pdflatex 
PDFLATEX ?= /sw/bin/pdflatex 

ARQ = cv
XML = cv-pri.xml
PDF = cv-pri.pdf

all: pdf ps rtf xhtml clean

rtf:

xhtml:
	$(XSLT) -o index.html $(ARQ)Xhtml.xsl $(XML)

pdf: tex
	$(PDFLATEX) $(ARQ).tex
	mv $(ARQ).pdf $(PDF)

ps: dvi
	$(DVIPS) -o $(ARQ).ps $(ARQ).dvi

dvi: tex
	$(LATEX) $(ARQ).tex

tex:
	$(XSLT) -o $(ARQ).tex $(ARQ)Tex.xsl $(XML)

clean:
	rm -f *.aux *.log *.out

distclean: clean
	rm -f *.dvi *.tex *.ps *.pdf *.html? *.rtf
