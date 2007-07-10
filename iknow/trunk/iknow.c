/*
$Id: iknow.c,v 1.6 2004/08/02 06:57:23 mmr Exp $
    
IKnow.c
By mmr <mmr@b1n.org>

Compilacao:
    cc -O3 -o iknow -lcurses iknow.c 

Descricao:
    O programa escolhera randomicamicamente uma frase em 'sentence' e
    a imprimira na tela enquanto voce, sorrateiramente, digita a resposta,
    apos ter digitado a resposta, pressione BackSpace DUAS vezes, complete
    a pergunta e pressione ENTER para que a resposta seja mostrada e supreenda
    a todos, hehehe :)

Lembre-se: A mente eh mais rapida que os olhos.
*/

#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <string.h>
#include <curses.h>

int
main(void)
{
    int i, j, k;
    int c;
    char *answer;
    char *tmp;

    /* Voce eh livre pra colocar novas frases abaixo */
    char *sentence[] =
    {
        "Ilustrissimo ",
        "Computador ",
        "Satanas ",
        "Capeta ",
        "Senhor das Trevas ",
        "Oh, todo poderoso "
    };

    answer = (char *) malloc(BUFSIZ);
    tmp = answer;
    i = j = k = 0;

    srandom((unsigned int) ((long) time((time_t *) NULL) ^ getpid()));
    j = (random() % (sizeof(sentence)/(sizeof(int))));

    (void)initscr();
    (void)keypad(stdscr, TRUE);
    (void)nonl();
    (void)noecho();
    (void)cbreak();

    while((c = getch()) != 13)
    {
        if(c == KEY_BACKSPACE)
        {
            if(getch() == KEY_BACKSPACE)
            {
                k = 1;
            }
        }
        else if(k == 0)
        {
            *tmp++ = c;
            c = sentence[j][i];
            i++;

            if(i > strlen(sentence[j]))
            {
                i = 0;
                j = (random() % (sizeof(sentence)/(sizeof(int))));
            }
        }

        (void)putchar(c);
        refresh();
    }

    printf("\033[3;0HResposta: %s\n", answer);
    refresh();

    (void)getch();
    (void)endwin();

    free(answer);
    return 0;
}
