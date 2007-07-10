/* $Id: ibest.sql,v 1.1.1.1 2003/03/29 19:55:21 binary Exp $ */

/*
 *      Esse Arquivo SQL é parte integrante de Projeto de Content Management pro Ibest
 * e não pode ser vendido separadamente.
 *
 * Marcio Ribeiro          <binary@aberium.com> 
 *
 */

/* ------------------------------------------------------------------------------------------------------------------- */

/* Apagando Sequencias */

/* Main */
DROP SEQUENCE materia_mat_id_seq;
DROP SEQUENCE destaque_des_id_seq;

/* ------------------------------------------------------------------------------------------------------------------- */

/* Apagando Tabelas */

/* Main */
DROP TABLE materia;
DROP TABLE destaque;
DROP TABLE mat_des;

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades Principais */

CREATE TABLE materia
(
    mat_id          SERIAL  NOT NULL    PRIMARY KEY,
    mat_id_mae      INT     NULL,
    mat_titulo      TEXT    NULL,
    mat_olho        TEXT    NULL,
    mat_modo        INT     NULL        DEFAULT '0',                        /* 0 - HTML; 1 - Texto Puro */
    mat_texto       TEXT    NULL,
    mat_pal_chave   TEXT    NULL,
    mat_fonte       TEXT    NULL,
    mat_status      TEXT    NOT NULL    DEFAULT 'Preview',
    /*
        P - Preview
        A - Ativada
        D - Desativada
    */
    mat_des_texto   TEXT    NULL,
    mat_des_arq_r   TEXT    NULL,    
    mat_des_arq_f   TEXT    NULL,    
    mat_des_imagem  TEXT    NULL,    
    mat_des_dt_ent  DATE    NULL,
    mat_dt_inc      DATE    NOT NULL    DEFAULT CURRENT_DATE,               /* Data de Inclusao */
    mat_dt_mod      DATE    NOT NULL    DEFAULT CURRENT_DATE                /* Date de ultima modificacao */
);

CREATE TABLE destaque
(
    des_id          SERIAL      NOT NULL    PRIMARY KEY,
    des_nome        TEXT        NOT NULL    UNIQUE,
    des_dt_inc      DATE        NOT NULL    DEFAULT CURRENT_DATE 
);

CREATE TABLE mat_des
(
    mat_id          INT     NOT NULL,
    des_id          INT     NOT NULL,
    mde_dt_inc      DATE    NOT NULL        DEFAULT CURRENT_DATE
);

ALTER TABLE materia ADD FOREIGN KEY ( mat_id_mae ) REFERENCES materia ( mat_id )    ON DELETE NO ACTION ON UPDATE CASCADE;
ALTER TABLE mat_des ADD FOREIGN KEY ( des_id ) REFERENCES destaque ( des_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE mat_des ADD FOREIGN KEY ( mat_id ) REFERENCES materia  ( mat_id )       ON DELETE CASCADE   ON UPDATE CASCADE;

INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 1' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 2' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 3' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 4' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 5' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 6' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 7' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 8' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 9' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 10' );
INSERT INTO destaque ( des_nome ) VALUES ( 'home: linha 11' );
