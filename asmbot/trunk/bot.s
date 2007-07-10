
#
## $Id: bot.s,v 1.13 2005/11/24 22:36:04 mmr Exp $
#
## IRCBot escrito em Assembly
## Por mmr < mribeiro (a) gmail com >
#
## 
## A versao original desse programa foi escrita pra rodar em Linux, eu o reescrevi,
## para ser compilado em BSD Unix (OpenBSD).
## O compilei (e executei) com sucesso em OpenBSD e FreeBSD.
#
## Instrucoes de Compilacao:
## as -o bot.o bot.s && ld -o bot bot.o
#

.data
    porta = 6667
    # int main(int a,char**b){if(a>1)printf("%s=0x%x\n",b[1],inet_addr(b[1]));}
    # ip_servidor_irc = 0x8503dfc8    # IP do servidor irc.brasnet.org depois de passado por um inet_addr
    ip_servidor_irc = 0x1d00c180    # IP do servidor irc.freenode.net depois de passado por um inet_addr

nick:
    .ascii "NICK DebugIsOnTheTable\r\n"
    nick_tam = . - nick - 1

usuario:
    .ascii "USER asmbot 8 * :mmr's AsmBot\r\n"
    usuario_tam = . - usuario - 1

canal:
    .ascii "JOIN #qmail\r\n"
    canal_tam = . - canal - 1

ping:
    .ascii "PING"

pong:
    .ascii "PONG :irc.brasnet.org.\r\n"
    pong_tam = . - pong - 1


#
## Variaveis Auxiliares (Globais)
#

.bss
buffer:                       # quantos bytes ler a cada loop
    .space 512, 0
    buffer_tam = . - buffer - 1
sock:                         # file descriptor do socket
    .long 0

servidor:                     # struct sockaddr/sockaddr_in
    .space 16, 0
    servidor_tam = 16


#
## Codigo
#

.text
.global _start

_start:
    call    cria_socket
    call    conecta
    call    configura
    call    loop
    call    sai
    ret


#
## Funcoes
#

cria_socket:
    # int socket(int domain, int type, int protocol);
    # socket(AF_INET, SOCK_STREAM, IPPROTO_TCP)

    pushl   $6           # IPPROTO_TCP
    pushl   $1           # SOCK_STREAM
    pushl   $2           # AF_INET
    movl    $97, %eax    # Socket Syscall
    call    kernel
    addl    $12, %esp   # ( 4 bytes * 3 pushes = $12 ) Limpando a pilha

    cmp     $0x1, %eax  # Vendo se nao deu erro
    jb sai              # Se deu erro, ir para 'sai'
    ret



conecta:
    movl    %eax, sock          # pegando o file descriptor do socket de eax e colocando em sock

    #
    # Colocando dados para conexao e colocando em struct sockaddr_in (sockaddr Internet Style)    
    #
    # struct sockaddr_in
    # {
    #   u_int8_t    sin_len;
    #   sa_family_t sin_family;
    #   in_port_t   sin_port;
    #   struct      in_addr sin_addr;
    #   int8_t      sin_zero[8];
    # }; 
    #
    # Espaco em Bytes que cada consome
    #
    # u_int8_t          1
    # sa_family_t       1
    # in_port_t         2
    # struct in_addr    4
    #  `-> in_addr_t    4
    # int8_t            1
    #
    # struct sockaddr_in 16
    #

    movb    $16, servidor                                                   # u_int8_t(1) sin_len
    movb    $0x2, servidor + 1                                              # sa_family_t(1) sin_family (SOCK_STREAM)
    movw    $((porta & 0xff00) >> 8 | (porta & 0xff) << 8), servidor + 2    # in_port_t(2) sin_port
    movl    $ip_servidor_irc, servidor + 4                                  # struct in_addr(4)

    # int connect(int s, const struct sockaddr *name, socklen_t namelen);
    # connect(sock, servidor, servidor_tam)

    pushl   $16
    pushl   $servidor 
    pushl   sock
    movl    $98, %eax           # Connect Syscall
    call    kernel
    addl    $12, %esp

    cmp     $0x0, %eax
    jb      sai
    ret


configura:
    # Nick
    pushl   $nick_tam
    pushl   $nick
    pushl   sock
    movl    $4, %eax
    call    kernel
    addl    $12, %esp

    # Usuario
    movl    $4, %eax
    pushl   $usuario_tam
    pushl   $usuario
    pushl   sock
    call    kernel
    addl    $12, %esp

    # Join
    movl    $4, %eax
    pushl   $canal_tam
    pushl   $canal
    pushl   sock
    call    kernel
    addl    $12, %esp
    ret

loop:
    movl    $0x3, %eax
    pushl   $buffer_tam
    pushl   $buffer
    pushl   sock
    call    kernel
    addl    $12, %esp

    movl    $buffer, %esi   # Compara a string recebida pra ver se eh Ping
    movl    $ping, %edi
    movl    $0x04, %ecx
    repe                    # REPeat if Equal

    je      responde_ping   # Jump if Equal (sim, foi um ping, enviar o pong de resposta)
    jmp     loop            # Nao, nao foi um ping, le mais 512 bytes


responde_ping:
    movl    $0x4, %eax
    pushl   $pong_tam
    pushl   $pong
    pushl   sock
    call    kernel
    addl    $12, %esp
    jmp     loop


kernel:
    int     $0x80
    ret


sai:
    pushl   $0x0
    movl    $0x1, %eax
    call    kernel
    ret
