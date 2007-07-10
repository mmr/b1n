#
# $Id: Makefile,v 1.1.1.1 2004/11/25 13:41:57 mmr Exp $
# mmr <mmr@b1n.org> 
# Started: Fri Oct 29 21:15:31 BRST 2004
#

LATEX  ?= /usr/local/bin/latex 
DVIPS  ?= /usr/local/bin/dvips
PS2PDF ?= /usr/local/bin/ps2pdf
PDFLATEX  ?= /usr/local/bin/pdflatex 

ARQ = knuckles-doc

.SUFFIXES: .tex .dvi .ps .pdf

#all: $(ARQ).dvi $(ARQ).ps $(ARQ).pdf

all:
	$(PDFLATEX) $(ARQ).tex

.ps.pdf:
	$(PS2PDF) $< $@

.dvi.ps:
	$(DVIPS) -o $@ $<

.tex.dvi:
	$(LATEX) $<

clean:
	rm -f *.dvi *.aux *.log *.toc *.out

distclean: clean
	rm -f *.dvi *.ps *.pdf

