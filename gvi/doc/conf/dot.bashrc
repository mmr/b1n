#!/bin/bash

# aliases
alias ls='ls -GF'
alias ll='ls -aolh'
alias lsd='ll | grep ^d'
alias du='du -sh'
alias df='df -h'
alias cp='cp -i'
alias rm='rm -i'
alias mv='mv -i'
alias ftp='ftp -i'
alias j='jobs | tail -20'
alias h='history | tail -20'
alias pstree='pstree -chnpul'
alias suck='wget -t0 -c -r -np -nc -L --user-agent=Lynx-2.0'
alias lis='netstat -an | grep LISTEN'
alias wget='wget -c -v -t0'

# functions
lw(){ ll $(which $*); }

### ANSI Colors
# Bold colors
i=0
for color in black red green brown blue violet cyan white; do
  eval ${color}='\\[\\033[0\;3${i}m\\]'
  i=$((i+1))
done

# Light colors
i=0
for color in lblack lred lgreen lbrown lblue lviolet lcyan lwhite; do
  eval ${color}='\\[\\033[1\;3${i}m\\]'
  i=$((i+1))
done
        
no_more_colors='\[\033[0m\]'

# PS1/PROMPT
host=`/bin/hostname -s | tr '[a-z]' '[A-Z]'`
#PS1="[\u@${h}:\w]\\$ "

PS1="\
$lgreen[\
$green\u\
$red@\
$green${host}\
$lbrown:\
$lgreen\W\
$lgreen]\
$red\\$ $no_more_colors"

PROMPT=$PS1
export PS1 PROMPT
unset host 
unset black red green brown blue violet cyan white
unset lblack lred lgreen lbrown lblue lviolet lcyan lwhite

# PATH
bin="/bin:/usr/bin:/usr/local/bin"
sys="/sbin:/usr/sbin:/usr/local/sbin"
misc="~/bin:~/hosts"
PATH="${sys}:${bin}:${misc}"
export PATH
unset bin sys misc h

# completes
complete -A hostname telnet nmap ping ssh ftp
complete -A directory cd rmdir du
complete -A user chfn finger passwd rmuser
complete -A command lw man
complete -A file cat tail head
complete -A alias unalias alias

# LS
export LSCOLORS="ExGxCxDxCxExDxHbHcHbHc"

# Editor padrao
EDITOR="/usr/local/bin/vim"
VISUAL=$EDITOR
export EDITOR VISUAL

# Historico de comandos
export HISTSIZE="1536"

# Ignore End Of File = 0 (Ctrl+D)
export ignoreeof=0

# TERM
case $TERM in
  *[Tt][Ee][Rr][Mm]) TERM='xterm-color';;
  *) TERM='cons25';;
esac
export TERM

# Msgs
[ -x "`which mesg 2> /dev/null`" ] && mesg n > /dev/null 2>&1

# Mail
[ -x "`which biff 2> /dev/null`" ] && biff n > /dev/null 2>&1
