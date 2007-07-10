# $Id: Makefile,v 1.1.1.1 2003/02/17 07:14:20 binary Exp $
NAME=vmot
INC=-I/usr/local/include
CC=gcc
CFLAGS= -Wall  -DHAVE_CONFIG_H -g3
LDFLAGS= -lpq 
OBJS=winmond.o db.o

srcdir = .
top_srcdir = .

prefix = /usr/local
exec_prefix = ${prefix}

bindir = ${exec_prefix}/bin
sbindir = ${exec_prefix}/sbin
libexecdir = ${exec_prefix}/libexec
datadir = ${prefix}/share
sysconfdir = ${prefix}/etc
sharedstatedir = ${prefix}/com
localstatedir = ${prefix}/var
libdir = ${exec_prefix}/lib
infodir = ${prefix}/info
mandir = ${prefix}/man
includedir = ${prefix}/include


all: winmond 

winmond: winmond.o db.o
	$(CC) -o $@ winmond.o db.o $(LDFLAGS)

winmond.o: winmond.c proto.h
	$(CC) $(CFLAGS) $(CPPFLAGS) winmond.c -c -o $@ 

db.o: db.c
	$(CC) $(CFLAGS) $(CPPFLAGS) db.c -c -o $@

clean:
	rm -f $(OBJS)

distclean:
	rm -rf CVS
	rm -f $(OBJS) config.status config.log config.h winmond Makefile 

install:
	install winmond $(bindir)/winmond
