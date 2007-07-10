-- $Id: scvp.old.sql,v 1.1.1.1 2004/01/25 15:18:50 mmr Exp $

-- Dropping
  -- Auth
DROP VIEW view_usr_ativo, view_usr_fnc
GO
DROP TABLE usr_grp, grp_fnc
GO
DROP TABLE log, funcao, grupo, usuario
GO

  -- Pub
DROP VIEW view_artigo, view_fasciculo, view_secao
GO
DROP TABLE autor, palchave, artigo_idioma, artigo, fasciculo, secao, idioma_pub
GO

  -- Site
DROP VIEW view_area, view_noticia, view_link
DROP TABLE link, noticia, area, idioma_site
GO

-- Tables
  -- Auth
CREATE TABLE usuario
(
  usr_id    INT IDENTITY  NOT NULL  PRIMARY KEY, 
  usr_login VARCHAR(128)  NOT NULL  UNIQUE,
  usr_senha VARCHAR(128)  NOT NULL,
  usr_nome  VARCHAR(128)  NOT NULL,
  usr_desc  VARCHAR(255)  NULL,
  usr_ativo   BIT       NOT NULL  DEFAULT 0,
  usr_exp_dt  DATETIME  NULL, -- Data de Expira
  usr_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE grupo
(
  grp_id    INT IDENTITY  NOT NULL  PRIMARY KEY,
  grp_nome  VARCHAR(128)  NOT NULL  UNIQUE,
  grp_desc  VARCHAR(255)  NULL,
  grp_ativo BIT       NOT NULL  DEFAULT 0,
  grp_exp_dt  DATETIME   NULL, -- Data de Expira
  grp_inc_dt  DATETIME   NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE funcao
(
  fnc_id    INT IDENTITY  NOT NULL  PRIMARY KEY,
  fnc_nome  VARCHAR(128)  NOT NULL  UNIQUE,
  fnc_desc  VARCHAR(255)  NULL,
  fnc_inc_dt  DATETIME   NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE log
(
  usr_id  INT NOT NULL, -- FK usuario
  fnc_id  INT NOT NULL, -- FK funcao

  log_id  INT   IDENTITY      NOT NULL  PRIMARY KEY,
  log_alvo_id   INT           NULL, -- Alvo (sera setado quando deletado)
  log_alvo_nome VARCHAR(255)  NULL, -- Rlvo (sera setado quando deletado)
  log_inc_dt    DATETIME     NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE usr_grp
(
  usr_id  INT NOT NULL, -- FK usuario
  grp_id  INT NOT NULL, -- FK grupo

  usr_grp_id  INT IDENTITY  NOT NULL PRIMARY KEY,
  usr_grp_inc_dt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(usr_id, grp_id)
)

CREATE TABLE grp_fnc
(
  grp_id  INT NOT NULL, -- FK grupo
  fnc_id  INT NOT NULL, -- FK funcao

  grp_fnc_id  INT IDENTITY  NOT NULL PRIMARY KEY,
  grp_fnc_inc_dt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(grp_id, fnc_id)
)

  -- Pub
CREATE TABLE idioma_pub
(
  idi_id    INT IDENTITY  NOT NULL PRIMARY KEY,
  idi_nome  VARCHAR(128)  NOT NULL UNIQUE,
  idi_nome_no_idioma  VARCHAR(128)  NOT NULL UNIQUE, 
  idi_desc  VARCHAR(255)  NULL,
  idi_padrao  BIT NOT NULL  DEFAULT 0,
  idi_ativo   BIT NOT NULL  DEFAULT 0,
  idi_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE secao
(
  idi_id   INT NOT NULL, -- FK idioma_pub
  sec_real_id INT NULL,     -- FK secao (secao do idioma padrao)

  sec_id      INT IDENTITY  NOT NULL PRIMARY KEY,
  sec_nome    VARCHAR(128)  NOT NULL,
  sec_desc    VARCHAR(255)  NULL,
  sec_ativo   BIT       NOT NULL  DEFAULT 0,
  sec_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(sec_nome, idi_id)
)

CREATE TABLE fasciculo
(
  fas_id  INT IDENTITY  NOT NULL PRIMARY KEY,
  fas_seq_num INT       NOT NULL UNIQUE, -- Numero sequencial
  fas_vol_num VARCHAR(10) NULL, -- Numero do volume
  fas_num     VARCHAR(10) NULL, -- numero do fasciculo
  fas_capa    BIT NOT NULL  DEFAULT 0,
  fas_capa_tipo CHAR(3) NULL,
  fas_ativo   BIT NOT NULL  DEFAULT 0,
  fas_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE artigo
(
  idi_id  INT NOT NULL, -- FK idioma_pub (idioma dos arquivos pdf/html)
  fas_id  INT NOT NULL, -- FK fasciculo
  sec_id  INT NULL,     -- FK secao

  art_id        INT IDENTITY  NOT NULL PRIMARY KEY,
  art_ordem     INT NOT NULL, -- Ordem
  art_pag_ini   VARCHAR(10) NOT NULL DEFAULT '0', -- Pagina inicial
  art_pag_fin   VARCHAR(10) NOT NULL DEFAULT '0', -- Pagina final
  art_pdf       BIT NOT NULL  DEFAULT 0,  -- Tem PDF
  art_html      BIT NOT NULL  DEFAULT 0,  -- Tem HTML
  art_html_pag  VARCHAR(255)  NULL,       -- Pagina inicial para o HTML
  art_ativo     BIT NOT NULL  DEFAULT 0,
  art_inc_dt    DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(fas_id, art_ordem)
  --CONSTRAINT chk_pagina CHECK (art_pag_ini <= art_pag_fim)
)

CREATE TABLE autor
(
  art_id    INT NOT NULL, -- FK artigo

  aut_id    INT IDENTITY  NOT NULL PRIMARY KEY,
  aut_prinome VARCHAR(128) NOT NULL,
  aut_sobnome VARCHAR(255) NOT NULL,
  aut_add_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE artigo_idioma
(
  art_id  INT NOT NULL, -- FK artigo
  idi_id  INT NOT NULL, -- FK idioma_pub

  aid_id        INT IDENTITY NOT NULL  PRIMARY KEY,  
  aid_titulo    VARCHAR(255) NULL,
  aid_resumo    TEXT  NULL,
  aid_add_dt    DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE palchave
(
  aid_id  INT NOT NULL, -- FK artigo_idioma

  pch_id    INT IDENTITY  NOT NULL  PRIMARY KEY,
  pch_cont  VARCHAR(128)  NOT NULL,
  pch_add_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

  -- Site
CREATE TABLE idioma_site
(
  idi_id    INT IDENTITY  NOT NULL PRIMARY KEY,
  idi_nome  VARCHAR(128)  NOT NULL UNIQUE,
  idi_nome_no_idioma  VARCHAR(128)  NOT NULL UNIQUE, 
  idi_desc  VARCHAR(255)  NULL,
  idi_padrao  BIT NOT NULL  DEFAULT 0,
  idi_ativo   BIT NOT NULL  DEFAULT 0,
  idi_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE area
(
  idi_id   INT NOT NULL,  -- FK idioma_site
  are_real_id INT NULL,   -- FK area (area do idioma padrao)

  are_id      INT IDENTITY  NOT NULL PRIMARY KEY,
  are_nome    VARCHAR(128)  NOT NULL,
  are_cont    TEXT          NULL,
  are_codigo  VARCHAR(32)   NULL,
  are_ordem   INT       NOT NULL,
  are_separador BIT     NOT NULL  DEFAULT 0,
  are_ativo    BIT      NOT NULL  DEFAULT 0,
  are_inc_dt   DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(are_nome,  idi_id),
  UNIQUE(are_ordem, idi_id)
)

CREATE TABLE noticia
(
  idi_id    INT NOT NULL, -- FK idioma_site
  not_real_id   INT NULL, -- FK noticia (noticia do idioma padrao)

  not_id    INT IDENTITY  NOT NULL PRIMARY KEY,
  not_nome  VARCHAR(128)  NOT NULL, -- Chamada/Manchete
  not_desc  VARCHAR(255)  NULL,     -- Resumo
  not_cont  TEXT      NULL,         -- Noticia na integra
  not_dt    DATETIME  NOT NULL,
  not_ativo BIT       NOT NULL  DEFAULT 0,
  not_inc_dt  DATETIME NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(idi_id, not_nome)
)

CREATE TABLE link
(
  idi_id    INT NOT NULL, -- FK idioma
  lnk_real_id   INT NULL, -- FK link (link do idioma padrao)

  lnk_id    INT IDENTITY  NOT NULL PRIMARY KEY,
  lnk_nome  VARCHAR(255)  NOT NULL,
  lnk_desc  TEXT  NULL,
  lnk_url   VARCHAR(255)  NOT NULL,
  lnk_ativo   BIT NOT NULL  DEFAULT 0,
  lnk_inc_dt  DATETIME  NOT NULL  DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(idi_id, lnk_nome)
)
GO

-- Views
  -- Auth
CREATE VIEW view_usr_ativo AS
  SELECT * FROM usuario
  WHERE
    usr_ativo = 1 AND
      (usr_exp_dt IS NULL OR usr_exp_dt > CURRENT_TIMESTAMP)
GO

CREATE VIEW view_usr_fnc AS
  SELECT
    u.usr_id, f.fnc_nome
  FROM
    usuario  u JOIN
    usr_grp ug ON (u.usr_id = ug.usr_id) JOIN
    grupo    g ON (g.grp_id = ug.grp_id) JOIN
    grp_fnc gf ON (g.grp_id = gf.grp_id) JOIN
    funcao   f ON (f.fnc_id = gf.fnc_id)
  WHERE
    u.usr_ativo = 1 AND 
      (u.usr_exp_dt IS NULL OR u.usr_exp_dt > CURRENT_TIMESTAMP) AND
    g.grp_ativo = 1 AND
      (g.grp_exp_dt IS NULL OR g.grp_exp_dt > CURRENT_TIMESTAMP)
GO

  -- Pub
CREATE VIEW view_secao AS
  SELECT
    s.sec_id,
    s.sec_nome,
    CASE WHEN (LEN(s.sec_desc) > 25) THEN
      SUBSTRING(s.sec_desc, 0, 25) + '...'
    ELSE
      s.sec_desc
    END AS sec_desc,
    s.sec_ativo,
    i.idi_nome
  FROM
    secao s JOIN
    idioma_pub i ON (s.idi_id = i.idi_id)
GO

CREATE VIEW view_fasciculo AS
  SELECT
    f.fas_id,
    f.fas_seq_num,
    f.fas_vol_num,
    f.fas_num,
    f.fas_capa,
    f.fas_ativo,
    (
      SELECT
        COUNT(art_id)
      FROM
        artigo a
      WHERE
        a.fas_id = f.fas_id
    ) AS fas_artigos
  FROM
    fasciculo f
GO

CREATE VIEW view_artigo AS
  SELECT
    a.fas_id,
    a.art_id,
    a.art_pdf,
    a.art_html,
    a.art_ordem,
    a.art_ativo,
    ai.aid_titulo,
    ai.aid_resumo,
    s.sec_nome
  FROM
    artigo        a JOIN
    artigo_idioma ai  ON (a.art_id  = ai.art_id)    JOIN
    idioma_pub    i   ON (i.idi_id  = ai.idi_id) LEFT JOIN
    secao         s   ON (a.sec_id  = s.sec_id)
  WHERE
    ai.idi_id =
      (SELECT TOP 1 idi_id FROM idioma_pub WHERE idi_padrao = '1')
GO

CREATE VIEW view_area AS
  SELECT
    a.are_real_id,
    a.are_id,
    a.are_nome,
    a.are_cont,
    a.are_ativo,
    a.are_ordem,
    a.are_separador,
    a.are_codigo,
    i.idi_nome
  FROM
    area a JOIN
    idioma_site i ON (a.idi_id = i.idi_id)
GO

CREATE VIEW view_noticia AS
  SELECT
    n.not_id,
    n.not_nome,
    n.not_dt,
    n.not_real_id,
    CASE WHEN (LEN(n.not_desc) > 25) THEN
      SUBSTRING(n.not_desc, 0, 25) + '...'
    ELSE
      n.not_desc
    END AS not_desc,
    n.not_ativo,
    i.idi_nome
  FROM
    noticia n JOIN
    idioma_site i ON (n.idi_id = i.idi_id)
GO

CREATE VIEW view_link AS
  SELECT
    l.lnk_id,
    l.lnk_nome,
    CASE WHEN (LEN(l.lnk_url) > 25) THEN
      SUBSTRING(l.lnk_url, 0, 25) + '...'
    ELSE
      l.lnk_url
    END AS lnk_url,
    l.lnk_real_id,
    l.lnk_ativo,
    i.idi_nome
  FROM
    link l JOIN
    idioma_site i ON (l.idi_id = i.idi_id)
GO

-- FKs
  -- Auth
ALTER TABLE log ADD FOREIGN KEY (usr_id) REFERENCES usuario (usr_id) ON DELETE NO ACTION ON UPDATE NO ACTION
ALTER TABLE log ADD FOREIGN KEY (fnc_id) REFERENCES funcao  (fnc_id) ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE usr_grp ADD FOREIGN KEY (usr_id) REFERENCES usuario (usr_id) ON DELETE CASCADE ON UPDATE CASCADE
ALTER TABLE usr_grp ADD FOREIGN KEY (grp_id) REFERENCES grupo   (grp_id) ON DELETE CASCADE ON UPDATE CASCADE

ALTER TABLE grp_fnc ADD FOREIGN KEY (grp_id) REFERENCES grupo (grp_id) ON DELETE CASCADE ON UPDATE CASCADE
ALTER TABLE grp_fnc ADD FOREIGN KEY (fnc_id) REFERENCES funcao  (fnc_id) ON DELETE CASCADE ON UPDATE CASCADE

  -- Pub
ALTER TABLE secao ADD FOREIGN KEY (idi_id) REFERENCES idioma_pub (idi_id) ON DELETE NO ACTION ON UPDATE NO ACTION
ALTER TABLE secao ADD FOREIGN KEY (sec_real_id) REFERENCES secao (sec_id) ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE artigo ADD FOREIGN KEY (fas_id) REFERENCES fasciculo (fas_id) ON DELETE NO ACTION ON UPDATE NO ACTION
ALTER TABLE artigo ADD FOREIGN KEY (sec_id) REFERENCES secao (sec_id)     ON DELETE NO ACTION ON UPDATE NO ACTION
ALTER TABLE artigo ADD FOREIGN KEY (idi_id) REFERENCES idioma_pub (idi_id)  ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE autor ADD FOREIGN KEY (art_id) REFERENCES artigo (art_id) ON DELETE CASCADE ON UPDATE CASCADE

ALTER TABLE artigo_idioma ADD FOREIGN KEY (art_id) REFERENCES artigo (art_id) ON DELETE CASCADE ON UPDATE CASCADE
ALTER TABLE artigo_idioma ADD FOREIGN KEY (idi_id) REFERENCES idioma_pub (idi_id) ON DELETE CASCADE ON UPDATE CASCADE

ALTER TABLE palchave ADD FOREIGN KEY (aid_id) REFERENCES artigo_idioma (aid_id) ON DELETE CASCADE ON UPDATE CASCADE

  -- Site
ALTER TABLE area ADD FOREIGN KEY (idi_id) REFERENCES idioma_site (idi_id) ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE area ADD FOREIGN KEY (are_real_id) REFERENCES area (are_id) ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE noticia ADD FOREIGN KEY (idi_id) REFERENCES idioma_site (idi_id) ON DELETE NO ACTION ON UPDATE NO ACTION

ALTER TABLE link ADD FOREIGN KEY (idi_id) REFERENCES idioma_site (idi_id) ON DELETE NO ACTION ON UPDATE NO ACTION
ALTER TABLE link ADD FOREIGN KEY (lnk_real_id) REFERENCES link (lnk_id) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

-- System Data
  -- Auth
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: usuario :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('comum :: grupo :: lista')
GO

  -- Pub
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: idioma :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: secao :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: fasciculo :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('pub :: artigo :: lista')
GO

  -- Site
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('site :: idioma :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('site :: area :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('site :: noticia :: lista')
GO

INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: adiciona')
INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: altera')
INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: ativa')
INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: exclui')
INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: visualiza')
INSERT INTO funcao (fnc_nome) VALUES ('site :: link :: lista')
GO

-- Default Data
  -- Pub
    -- Idiomas Reais
INSERT INTO idioma_pub (idi_nome, idi_nome_no_idioma, idi_padrao) VALUES ('Português', 'Portugues', '1')
INSERT INTO idioma_pub (idi_nome, idi_nome_no_idioma) VALUES ('Ingles', 'English')
INSERT INTO idioma_pub (idi_nome, idi_nome_no_idioma) VALUES ('Espanhol', 'Espanol')
GO
UPDATE idioma_pub SET idi_ativo = '1'
GO

  -- Site
    -- Idiomas
INSERT INTO idioma_site (idi_nome, idi_nome_no_idioma, idi_padrao) VALUES ('Português', 'Portugues', '1')
INSERT INTO idioma_site (idi_nome, idi_nome_no_idioma) VALUES ('Ingles', 'English')
INSERT INTO idioma_site (idi_nome, idi_nome_no_idioma) VALUES ('Espanhol', 'Espanol')
GO
UPDATE idioma_site SET idi_ativo = '1'
GO

    -- Areas Reais
INSERT INTO area (idi_id, are_nome, are_codigo, are_ordem) VALUES ('1', 'Busca Simples', 'busca_simples', '1')
INSERT INTO area (idi_id, are_nome, are_codigo, are_ordem) VALUES ('1', 'Busca Avançada', 'busca_avancada', '2')
INSERT INTO area (idi_id, are_nome, are_codigo, are_ordem) VALUES ('1', 'Coleção Completa', 'browse', '3')
INSERT INTO area (idi_id, are_nome, are_codigo, are_ordem) VALUES ('1', 'Vínculos', 'links', '4')
INSERT INTO area (idi_id, are_nome, are_codigo, are_ordem) VALUES ('1', 'Notícias', 'noticias', '5')
    -- Areas "falsas"
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('2', '1', 'Search', '1')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('2', '2', 'Advanced Search', '2')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('2', '3', 'Links', '3')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('2', '4', 'News', '4')

INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('3', '1', 'La Busca', '1')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('3', '2', 'La Busca Avancada', '2')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('3', '3', 'La Correlatos', '3')
INSERT INTO area (idi_id, are_real_id, are_nome, are_ordem) VALUES ('3', '4', 'La Noticias', '4')
GO
UPDATE area SET are_ativo = '1'
GO

  -- Dummy Data
    -- Secoes
INSERT INTO secao (idi_id, sec_nome) VALUES (1, 'Editorial')
INSERT INTO secao (idi_id, sec_nome) VALUES (1, 'Artigo Original')

INSERT INTO secao (idi_id, sec_real_id, sec_nome) VALUES (2, 1, 'Editorial')
INSERT INTO secao (idi_id, sec_real_id, sec_nome) VALUES (2, 2, 'Original Article')
INSERT INTO secao (idi_id, sec_real_id, sec_nome) VALUES (3, 1, 'Editorial')
INSERT INTO secao (idi_id, sec_real_id, sec_nome) VALUES (3, 2, 'Artigo Original')
GO
UPDATE secao SET sec_ativo = '1'
GO

    -- Usuario
INSERT INTO usuario
(usr_login, usr_senha, usr_nome, usr_ativo)
VALUES
('mmr', '6Ms8vR77l/fuSUi1MWL4yKiYl3RB4H94/plPyC7RHLo=', 'Marcio Ribeiro', 1)
GO

    -- Grupo
INSERT INTO grupo (grp_nome, grp_ativo) VALUES ('Grupo X', 1)
INSERT INTO usr_grp (usr_id, grp_id) VALUES (1,1)
INSERT INTO grp_fnc (grp_id, fnc_id) SELECT 1,fnc_id FROM funcao
GO
