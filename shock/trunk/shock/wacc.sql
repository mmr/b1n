DROP SEQUENCE	produto_prd_id_seq;
DROP SEQUENCE	evento_evt_id_seq;
DROP SEQUENCE	consumo_cns_id_seq;
DROP VIEW	evento_u;
DROP TABLE	config;
DROP TABLE	consumo;
DROP TABLE	evento;
DROP TABLE	maquina;
DROP TABLE	produto;
DROP TABLE	usuario;

CREATE TABLE maquina (
	maq_id		TEXT	NOT NULL PRIMARY KEY,	/* nome NetBIOS da maquina */
	maq_desc	TEXT	NULL					/* Descricao */
);

/* tabela de relacionamento usuario -> maquina */
CREATE TABLE evento (
	evt_id		SERIAL	NOT NULL	PRIMARY KEY,	/* id do evento */
	usr_id		TEXT	NOT NULL,	/* FK - login do usuario na rede */
	maq_id		TEXT	NOT NULL,	/* FK - nome netbios da maquina */
	evt_ts_ini	TIMESTAMP	NOT NULL    DEFAULT CURRENT_TIMESTAMP,	/* hora do inicio do acesso */
	evt_ts_fim	TIMESTAMP	NOT NULL    DEFAULT CURRENT_TIMESTAMP,	/* hora do ultimo acesso */
	evt_tipo	char(3) NOT NULL,  /* tipo de evento */
	evt_baixa	boolean NOT NULL		DEFAULT 'f'	/* fim dos eventos  */
);
CREATE VIEW evento_u as SELECT *,date_part('epoch', evt_ts_ini) as evt_uts_ini, date_part('epoch', evt_ts_fim) as evt_uts_fim from evento;

CREATE TABLE usuario (
	usr_id		TEXT	NOT NULL	PRIMARY KEY,  /* login do usuario na rede */
	usr_senha	TEXT	NOT NULL,	/* senha do usuario */
	usr_nome	TEXT	NULL,
    usr_ts		TIMESTAMP	NOT NULL    DEFAULT CURRENT_TIMESTAMP /* hora de cadastro */
);

/* tabela de relacionamento usuario -> produto */
CREATE TABLE consumo (
	cns_id		SERIAL	NOT NULL	PRIMARY KEY,	/* id do consumo */
	usr_id		TEXT	NOT NULL, /* FK - login do usuario */
	prd_id		INT		NOT NULL, /* FK - id do produto */
	cns_ts		TIMESTAMP	NOT NULL    DEFAULT CURRENT_TIMESTAMP,	/* hora do consumo */
	cns_baixa	boolean NOT NULL		DEFAULT 'f'	/* fim do consumo */
);

CREATE TABLE produto (
	prd_id		SERIAL	NOT NULL	PRIMARY KEY,	/* id do produto */
	prd_desc	TEXT	NULL,		/* descricao do produto */
	prd_preco	NUMERIC(9,2)	NOT NULL	DEFAULT 0.0, /* preco do produto */
	prd_qtde	INT		NOT NULL	DEFAULT 1, /* quantidade do produto */
	prd_ativo	boolean		NOT NULL	DEFAULT 'false'
);

CREATE TABLE config (
    cfg_id		TEXT	NOT NULL	PRIMARY KEY,
    cfg_int		INT,
    cfg_txt		TEXT
);
insert into config (cfg_id,cfg_int) values('session_timeout',120);

/* FKs */
-- ALTER TABLE evento		ADD FOREIGN KEY (usr_id) REFERENCES usuario (usr_id) ON DELETE NO ACTION;
-- ALTER TABLE evento		ADD FOREIGN KEY (maq_id) REFERENCES maquina (maq_id) ON DELETE NO ACTION;
ALTER TABLE consumo		ADD FOREIGN KEY (usr_id) REFERENCES usuario (usr_id) ON DELETE NO ACTION;
ALTER TABLE consumo		ADD FOREIGN KEY (prd_id) REFERENCES produto (prd_id) ON DELETE NO ACTION;



-- $ createlang plpgsql shock
DROP FUNCTION atualiza(text,text,char(3));
CREATE function atualiza(text,text,char(3)) RETURNS float8 AS '
    DECLARE
	r record;
	timeout int;
	user ALIAS FOR $1;
	host ALIAS FOR $2;
	evt  ALIAS FOR $3;
    BEGIN
	SELECT cfg_int from config into timeout WHERE cfg_id=''session_timeout'';
	SELECT evt_ts_fim,date_part(''epoch'',(now() - evt_ts_fim)) AS delta into r
	    from evento 
	    WHERE evt_baixa=''f''
	    	AND usr_id = user
--	          AND evt_tipo != ''lof''
	    order by evt_ts_fim
	    desc limit 1;
	
	IF NOT FOUND THEN
	    INSERT INTO evento (usr_id,maq_id,evt_tipo) values(user,host,evt);
	ELSE 
	    IF r.delta > timeout THEN
		INSERT INTO evento (usr_id,maq_id,evt_tipo) values(user,host,evt);
	    ELSE
		UPDATE evento SET evt_ts_fim=now(), evt_tipo=evt WHERE usr_id=user AND maq_id=host AND evt_ts_fim=r.evt_ts_fim;
	    END IF;
	    return (timeout - r.delta);
	END IF;
	return 0;
    END;
' LANGUAGE 'plpgsql';
