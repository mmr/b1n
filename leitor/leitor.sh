#!/bin/bash

#
# Le o que for passado.
#
# Usa o tradutor do google.
#
# @author Marcio Ribeiro (mmr (a) b1n org)
# @created May 2012
#

if [ $# -lt 1 ]; then
    echo "$0 texto_a_ser_lido"
    exit
fi

texto=$*

URL="http://translate.google.com/translate_tts?ie=UTF-8&q=$texto&tl=pt&total=1&idx=0&prev=input"
wget -q -O- --user-agent="Mozilla" "$URL" |
    mplayer -really-quiet -nolirc -cache 8192 -
