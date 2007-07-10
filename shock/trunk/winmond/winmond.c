/*
 * |version|command|
 *   1byte   1byte
 */

#include "config.h"
#include "proto.h"
#include "db.h"
#include <stdlib.h>
#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <sys/time.h>

#define PORT 2869
#define MAX_BUFF    1024

int main(int argc, char **argv) {
    int sock;
    unsigned long ticks,count,signal;
    struct sockaddr_in servaddr, cliaddr;
    unsigned long mt,mot=0;
    socklen_t len;
    int n;
    char buf[MAX_BUFF];
    char user[50],host[50];

    if(!DBopen()) {
        exit(-1);
    }
    sock = socket(AF_INET,SOCK_DGRAM, 0);
    if(!sock) {
        perror(argv[0]);
        _exit(-1);
    }
    bzero( &servaddr, sizeof(servaddr));
    servaddr.sin_family = AF_INET;
    servaddr.sin_addr.s_addr = htonl(INADDR_ANY);
    servaddr.sin_port = htons(PORT);
    if(bind(sock, (struct sockaddr *) &servaddr, sizeof(servaddr)) != 0) {
        perror(argv[0]);
        _exit(-1);
    }
    for(;;) {
        n = recvfrom(sock,buf,MAX_BUFF,0,(struct sockaddr *)&cliaddr,&len);
        if(n==112) {
/*
            if(inet_ntop(AF_INET,&cliaddr.sin_addr,buf1,MAX_BUFF)==NULL) {
                perror(argv[0]);
            }
*/
            memcpy(&ticks,&buf[0],4);
            memcpy(&count,&buf[4],4);
            memcpy(&signal,&buf[8],4);
            strncpy(user,&buf[12],50);
            strncpy(host,&buf[62],50);
            // printf("ticks: %lu count: %lu Signal: %lx - %s@%s\n",
            //    ticks,count,signal,user,host);
            mot = mt;
            DBput(user,host,signal);
        }
        else { printf("size == %d\n",n); }
    }

    _exit(0);
}
