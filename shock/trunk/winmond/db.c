#include "config.h"
#include "proto.h"
#include <strings.h>
#include <pgsql/libpq-fe.h>

FILE *debug;
PGconn *conn;
/*
 * abre conexao com o database
 */
int DBopen() {
    char       *pghost, *pgport, *pgoptions,
               *pgtty, *dbName, *dbUser, *dbPass;

    pghost = NULL;              /* host name of the backend server */
    pgport = NULL;              /* port of the backend server */
    pgoptions = NULL;           /* special options to start up the backend
                                 * server */
    pgtty = NULL;               /* debugging tty for the backend server */
    dbName = "shock";
    dbUser = "postgres";
    dbPass = "";

    if(PQstatus(conn) == CONNECTION_MADE)
    {
        fprintf(stderr,"DB: Alreay connected\n");
        return 1;
    }
    PQfinish(conn);
    conn = PQsetdbLogin(pghost, pgport, pgoptions, pgtty, dbName,dbUser,dbPass);

    /*
     * check to see that the backend connection was successfully made
     */
    if (PQstatus(conn) == CONNECTION_BAD)
    {
        fprintf(stderr, "Connection to database '%s' failed.\n", dbName);
        fprintf(stderr, "%s", PQerrorMessage(conn));
        return 0;
    }
    // debug = fopen("trace.out","w");
    if(debug) PQtrace(conn, debug);
    return 1;
}

int DBput( char *usr_id, char *maq_id, unsigned long evt_tipo) {
    char query[1024];
    char *evt;
    PGresult *res;
    res = NULL;

    switch (evt_tipo) {
    case EVT_ONLINE:
        evt = "onl";
        break;
    case EVT_LOGOFF:
        evt = "kil";
        break;
    default:
        evt = "unk";
    }
    snprintf(query,sizeof(query),"SELECT atualiza('%s','%s','%s')",usr_id,
        maq_id,evt);
    res = PQexec(conn, query);
    if (!res || PQresultStatus(res) != PGRES_TUPLES_OK) {
        fprintf(stderr, "%s\n\t-> command failed: %s\n",query,
                 PQresultErrorMessage(res));
    }
    if(res) PQclear(res);
    return 1;
}

