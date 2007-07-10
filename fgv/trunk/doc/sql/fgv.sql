/* $Id: fgv.sql,v 1.165 2002/08/05 13:30:21 binary Exp $ */

/*
 *      Esse Arquivo SQL é parte integrante de Projeto de Banco de Dados da FGV 
 * e não pode ser vendido separadamente.
 *
 * Projeto Banco de Dados - Empresa Junior - FGV
 * ------- ----- -- -----   ------- ------   ---
 *
 * Marcio Ribeiro          <binary@aberium.com> 
 * Ivan Bittencourt Neto <ivanneto@aberium.com>
 *
*/

/* ------------------------------------------------------------------------------------------------------------------- */

/* Apagando Funcoes */
DROP FUNCTION ipg_inscrito_upd_fnc( int );

/* Apagando Triggers */
-- DROP TRIGGER eme_evt_mem_upd_tri;

/* Apagando Rules */
DROP RULE gho_membro_ins;
DROP RULE ipg_inscrito_upd;

/* Apagando Views */

DROP VIEW membro_vivo;
DROP VIEW membro_todos;
DROP VIEW aluno_vivo;
DROP VIEW aluno_vivo_nao_membro;
DROP VIEW membro_funcao;
DROP VIEW busca_grupo;
DROP VIEW busca_consultoria;
DROP VIEW busca_ts_atividade;

/* Apagando Sequencias */

/* Main */
DROP SEQUENCE aviso_auto_ava_id_seq;
DROP SEQUENCE aluno_gv_agv_id_seq;
DROP SEQUENCE aluno_nao_gv_ang_id_seq;
DROP SEQUENCE area_are_id_seq;
DROP SEQUENCE arquivo_arq_id_seq;
DROP SEQUENCE cargo_gv_cgv_id_seq;
DROP SEQUENCE cargo_ext_cex_id_seq;
DROP SEQUENCE cliente_cli_id_seq;
DROP SEQUENCE empresa_junior_eju_id_seq;
DROP SEQUENCE departamento_dpt_id_seq;
DROP SEQUENCE fornecedor_for_id_seq;
DROP SEQUENCE funcao_fnc_id_seq;
DROP SEQUENCE funcionario_gv_fgv_id_seq;
DROP SEQUENCE feriado_frd_id_seq;
DROP SEQUENCE tipo_servico_tse_id_seq;
DROP SEQUENCE logo_lgo_id_seq;
DROP SEQUENCE membro_mem_id_seq;
DROP SEQUENCE grade_horario_gho_id_seq;
DROP SEQUENCE grupo_grp_id_seq;
DROP SEQUENCE log_log_id_seq;
DROP SEQUENCE palestrante_pal_id_seq;
DROP SEQUENCE patrocinador_pat_id_seq;
DROP SEQUENCE pat_class_cla_id_seq;
DROP SEQUENCE professor_prf_id_seq;
DROP SEQUENCE ramo_ram_id_seq;
DROP SEQUENCE setor_set_id_seq;
DROP SEQUENCE status_contato_stc_id_seq;
DROP SEQUENCE regiao_reg_id_seq;

/* Consultoria */
DROP SEQUENCE consultoria_cst_id_seq;
DROP SEQUENCE brinde_bri_id_seq;
DROP SEQUENCE comentario_com_id_seq;
DROP SEQUENCE cst_atividade_atv_id_seq;
DROP SEQUENCE cobranca_cob_id_seq;
DROP SEQUENCE plano_pgto_ppg_id_seq;
DROP SEQUENCE tipo_projeto_tpj_id_seq;
DROP SEQUENCE cst_etapa_etp_id_seq;

/* Evento */
DROP SEQUENCE evento_evt_id_seq;
DROP SEQUENCE criterio_cri_id_seq;
DROP SEQUENCE status_evento_ste_id_seq;
DROP SEQUENCE categoria_cat_id_seq;
DROP SEQUENCE tipo_evento_tev_id_seq;
DROP SEQUENCE equipe_eqp_id_seq;
DROP SEQUENCE material_grafico_mgf_id_seq;
DROP SEQUENCE item_final_ifi_id_seq;
DROP SEQUENCE tipo_convidado_tcv_id_seq;
DROP SEQUENCE inscrito_gv_igv_id_seq;
DROP SEQUENCE inscrito_ngv_ing_id_seq;
DROP SEQUENCE evt_tarefa_eta_id_seq;
DROP SEQUENCE ferramenta_frm_id_seq;
DROP SEQUENCE evt_custo_cto_id_seq;
DROP SEQUENCE evt_arquivo_ear_id_seq;


/* Processo Seletivo */
DROP SEQUENCE p_seletivo_psl_id_seq;
DROP SEQUENCE dinamica_din_id_seq;
DROP SEQUENCE palestra_plt_id_seq;

/* TimeSheet */
DROP SEQUENCE timesheet_tsh_id_seq;
DROP SEQUENCE ts_atividade_tat_id_seq;
DROP SEQUENCE ts_subatividade_tsa_id_seq;
DROP SEQUENCE prj_interno_pin_id_seq;

/* TaskList */
DROP SEQUENCE task_tsk_id_seq;
DROP SEQUENCE tipo_task_ttk_id_seq;
DROP SEQUENCE status_task_stt_id_seq;


/* ------------------------------------------------------------------------------------------------------------------- */

/* Apagando Tabelas */

/* Main */
DROP TABLE ava_cgv;
DROP TABLE aviso_auto;
DROP TABLE aluno_gv;
DROP TABLE aluno_nao_gv;
DROP TABLE grade_horario;
DROP TABLE area;
DROP TABLE arquivo;
DROP TABLE cargo_gv;
DROP TABLE cargo_ext;
DROP TABLE cliente;
DROP TABLE empresa_junior;
DROP TABLE departamento;
DROP TABLE fornecedor;
DROP TABLE funcao;
DROP TABLE funcionario_gv;
DROP TABLE feriado;
DROP TABLE tipo_servico;
DROP TABLE logo;
DROP TABLE membro;
DROP TABLE grupo;
DROP TABLE grp_fnc;
DROP TABLE grp_mem;
DROP TABLE log;
DROP TABLE palestrante;
DROP TABLE patrocinador;
DROP TABLE pat_class;
DROP TABLE professor;
DROP TABLE ramo;
DROP TABLE setor;
DROP TABLE status_contato;
DROP TABLE regiao;

/* Consultoria */
DROP TABLE cst_arq;
DROP TABLE consultoria;
DROP TABLE brinde;
DROP TABLE comentario;
DROP TABLE cst_atividade;
DROP TABLE cobranca;
DROP TABLE plano_pgto;
DROP TABLE tipo_projeto;
DROP TABLE cst_etapa;
DROP TABLE cst_mem;
DROP TABLE cst_tpj;
DROP TABLE cst_prf;

/* Evento */
DROP TABLE evento; 
DROP TABLE evt_arquivo;
DROP TABLE criterio;
DROP TABLE status_evento; 
DROP TABLE categoria; 
DROP TABLE tipo_evento; 
DROP TABLE equipe; 
DROP TABLE material_grafico; 
DROP TABLE item_final; 
DROP TABLE tipo_convidado;
DROP TABLE inscrito_gv; 
DROP TABLE inscrito_ngv; 
DROP TABLE inscrito_pg;
DROP TABLE evt_tarefa; 
DROP TABLE ferramenta; 
DROP TABLE frm_cst;
DROP TABLE frm_evt;
DROP TABLE evt_custo; 
DROP TABLE eqp_agv; 
DROP TABLE evt_mem;
DROP TABLE evt_for; 
DROP TABLE evt_pat; 
DROP TABLE evt_pal;
DROP TABLE evt_prf;

/* Processo Seletivo */
DROP TABLE p_seletivo;
DROP TABLE dinamica;
DROP TABLE palestra;
DROP TABLE acompanha;
DROP TABLE candidato_din;
DROP TABLE candidato_psl;
DROP TABLE abastece;
DROP TABLE audita;

/* TimeSheet */
DROP TABLE timesheet; 
DROP TABLE ts_atividade; 
DROP TABLE ts_subatividade;
DROP TABLE prj_interno;
DROP TABLE tat_tsa;

/* TaskList */
DROP TABLE task;
DROP TABLE tipo_task;
DROP TABLE status_task;

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades Principais */

/* Avisos Automaticos */
CREATE TABLE aviso_auto
(
    ava_id          SERIAL      NOT NULL    PRIMARY KEY,
    ava_dt          TIMESTAMP   NOT NULL,
    ava_mne         TEXT        NOT NULL,
    ava_assunto     TEXT        NULL,
    ava_mensagem    TEXT        NULL,
    ava_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ava_tipo	    TEXT        NOT NULL
);

CREATE TABLE ava_cgv
(
    ava_id          INT         NOT NULL,
    cgv_id          INT         NOT NULL,

    avc_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( ava_id, cgv_id )
);

/* Aluno da FGV */
CREATE TABLE aluno_gv
(
    agv_id          SERIAL  NOT NULL    PRIMARY KEY,
    agv_vivo        INT     NULL    DEFAULT 1,                          /* Aluno vivo / ativo */
    agv_matricula   TEXT    NULL    UNIQUE,
    agv_nome        TEXT    NULL,
    agv_rg          TEXT    NULL,
    agv_cpf         TEXT    NULL,
    agv_endereco    TEXT    NULL,
    agv_bairro      TEXT    NULL,
    agv_ddd         TEXT    NULL,
    agv_ddi         TEXT    NULL,
    agv_telefone    TEXT    NULL,
    agv_ramal       TEXT    NULL,
    agv_cep         TEXT    NULL,
    agv_celular     TEXT    NULL,
    agv_email       TEXT    NULL,
    agv_dt_nasci    TIMESTAMP   NULL,
    agv_dt_saida    TIMESTAMP   NULL,                                       /* Para Ex-Alunos */
    agv_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Coisa Feia ( bota CPU ai... ) */
CREATE TABLE grade_horario
(
    mem_id       INT     NOT NULL,                                         /* FK - Membro */
    gho_id       SERIAL  NOT NULL     PRIMARY KEY,
    gho_seg_1    TEXT    NULL,
    gho_seg_2    TEXT    NULL,
    gho_seg_3    TEXT    NULL,
    gho_seg_4    TEXT    NULL,
    gho_seg_5    TEXT    NULL,
    gho_seg_6    TEXT    NULL,
    gho_seg_7    TEXT    NULL,
    gho_ter_1    TEXT    NULL,
    gho_ter_2    TEXT    NULL,
    gho_ter_3    TEXT    NULL,
    gho_ter_4    TEXT    NULL,
    gho_ter_5    TEXT    NULL,
    gho_ter_6    TEXT    NULL,
    gho_ter_7    TEXT    NULL,
    gho_qua_1    TEXT    NULL,
    gho_qua_2    TEXT    NULL,
    gho_qua_3    TEXT    NULL,
    gho_qua_4    TEXT    NULL,
    gho_qua_5    TEXT    NULL,
    gho_qua_6    TEXT    NULL,
    gho_qua_7    TEXT    NULL,
    gho_qui_1    TEXT    NULL,
    gho_qui_2    TEXT    NULL,
    gho_qui_3    TEXT    NULL,
    gho_qui_4    TEXT    NULL,
    gho_qui_5    TEXT    NULL,
    gho_qui_6    TEXT    NULL,
    gho_qui_7    TEXT    NULL,
    gho_sex_1    TEXT    NULL,
    gho_sex_2    TEXT    NULL,
    gho_sex_3    TEXT    NULL,
    gho_sex_4    TEXT    NULL,
    gho_sex_5    TEXT    NULL,
    gho_sex_6    TEXT    NULL,
    gho_sex_7    TEXT    NULL
);

/* Tipos de Serviço/Produto */
CREATE TABLE tipo_servico 
(
    tse_id      SERIAL  NOT NULL    PRIMARY KEY,
    tse_nome    TEXT    NULL,
    tse_desc    TEXT    NULL,
    tse_tipo    CHAR( 1 ) NOT NULL    DEFAULT 's',
        /*
            s - Servico
            p - Produto
        */
    tse_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Feriados para validacao de dias uteis */
CREATE TABLE feriado
(
    frd_id      SERIAL      NOT NULL    PRIMARY KEY,
    frd_nome    TEXT        NULL    UNIQUE,
    frd_desc    TEXT        NULL,
    frd_dt_data TIMESTAMP   NULL,
    frd_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Membros/Ex-Membros da Empresa Junior ( Obrigatoriamente um Aluno/Ex-Aluno ) */
CREATE TABLE membro 
(
    agv_id          INT     NOT NULL,                                   /* FK - pra aluno_gv, todo membro/ex-membro tem de existir la */
    cgv_id          INT     NULL,
    
    mem_id          SERIAL  NOT NULL    PRIMARY KEY,
    mem_login       TEXT    NOT NULL UNIQUE,
    mem_senha       TEXT    NULL,
    mem_vivo        INT     NULL    DEFAULT 1,                      /* Membro Ativo ? 0 - Nao; 1 - Sim */
    mem_dt_entrada  TIMESTAMP   NULL,                                   /* Data de entrada do Membro na EJ */
    mem_dt_saida    TIMESTAMP   NULL,                                   /* Data de saida do Membro da EJ */
    mem_apelido     TEXT    NULL,
    mem_cod_banco   TEXT    NULL,                                       /* Codigo do Banco do Membro */
    mem_ag_banco    TEXT    NULL,                                       /* Agencia do Banco do Membro */
    mem_cc_banco    TEXT    NULL,                                       /* Conta Corrente do Membro */
    mem_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Grupo de usuarios */
CREATE TABLE grupo 
(
    grp_id      SERIAL  NOT NULL    PRIMARY KEY,
    grp_nome    TEXT    NULL    UNIQUE,
    grp_desc    TEXT    NULL,
    grp_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Grupo X Funcao */
CREATE TABLE grp_fnc
(
    grp_id      INT         NOT NULL,                                   /* FK - grupo */
    fnc_id      INT         NOT NULL,                                   /* FK - funcao */

    gfn_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ( grp_id, fnc_id )
);

/* Grupo X Membro */
CREATE TABLE grp_mem
(
    grp_id      INT         NOT NULL,                                   /* FK - grupo */
    mem_id      INT         NOT NULL,                                   /* FK - membr */

    gme_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ( grp_id, mem_id )
);

CREATE TABLE log 
(
    mem_id          INT     NOT NULL,                                   /* FK - membro */
    fnc_id          INT     NOT NULL,                                   /* FK - funcao */

    log_id          SERIAL  NOT NULL    PRIMARY KEY,
    fnc_target_id   INT     NULL,
    fnc_comentario  TEXT    NULL,
    log_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE professor 
(
    dpt_id          INT     NULL,                                       /* FK - Departamento */

    prf_id          SERIAL  NOT NULL    PRIMARY KEY,
    prf_nome        TEXT    NULL,
    prf_ddd         TEXT    NULL,
    prf_ddi         TEXT    NULL,
    prf_telefone    TEXT    NULL,
    prf_ramal       TEXT    NULL,
    prf_fax         TEXT    NULL,
    prf_celular     TEXT    NULL,
    prf_email       TEXT    NULL,
    prf_dt_nasci    TIMESTAMP  NULL,
    prf_ajuda_ej    INT     NOT NULL    DEFAULT 0,                      /* Ajuda EJ? 0 - Nao; 1 - Sim */
    prf_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Departamento */
CREATE TABLE departamento 
(
    dpt_id          SERIAL  NOT NULL    PRIMARY KEY,
    dpt_nome        TEXT    NULL,
    dpt_desc        TEXT    NULL,
    dpt_andar       TEXT    NULL,
    dpt_ramal       TEXT    NULL,
    dpt_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Cliente eh uma Empresa */
CREATE TABLE cliente 
(
    cex_id      INT     NULL,                                        /* FK - cargo_ext */
    ram_id      INT     NULL,                                        /* FK - ramo */ 
    reg_id      INT     NULL,                                        /* FK - regiao */ 

    cli_id      SERIAL  NOT NULL        PRIMARY KEY,
    cli_nome    TEXT    NULL,
    cli_razao   TEXT    NULL,
    cli_endereco    TEXT NULL,
    cli_bairro  TEXT    NULL,
    cli_cidade  TEXT    NULL,
    cli_estado  TEXT    NULL,
    cli_cep     TEXT    NULL,
    cli_nome_contato    TEXT    NULL,
    cli_celular_contato TEXT    NULL,
    cli_ddd             TEXT    NULL,
    cli_ddi             TEXT    NULL,
    cli_telefone        TEXT    NULL,
    cli_fax             TEXT    NULL,
    cli_ramal           TEXT    NULL,
    cli_email           TEXT    NULL,
    cli_homepage        TEXT    NULL,
    cli_conheceu_ej     TEXT    NULL,
    cli_faturamento     NUMERIC( 30, 2 )    NULL,                           /* Faturamento da Empresa */

    cli_cob_contato     TEXT    NULL,                                   /* cob = Cobranca */
    cli_cob_resp        TEXT    NULL,                                   /* Responsavel Legal */
    cli_cob_cnpj        TEXT    NULL,                                   /* CPF/CNPJ */
    cli_cob_endereco    TEXT    NULL,
    cli_cob_cep         TEXT    NULL,
    cli_cob_ddd         TEXT    NULL,
    cli_cob_ddi         TEXT    NULL,
    cli_cob_telefone    TEXT    NULL,
    cli_cob_fax         TEXT    NULL,
    cli_dt_inc          TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE empresa_junior
(
    cex_id          INT     NULL,                                       /* FK - cargo_ext - Cargo do Contato */ 
    reg_id          INT     NULL,                                       /* FK - Regiao */
    
    eju_id          SERIAL  NOT NULL    PRIMARY KEY,
    eju_nome        TEXT    NULL,                                   /* Nome da Empresa */
    eju_razao       TEXT    NULL,                                       /* Razao Social */
    eju_endereco    TEXT    NULL,                                       /* Endereco da Empresa */
    eju_bairro      TEXT    NULL,
    eju_cidade      TEXT    NULL,
    eju_estado      CHAR( 2 )   NULL,
    eju_cep         TEXT        NULL,
    eju_nome_contato TEXT       NULL,                                   /* Nome do Contato com a Empresa */
    eju_celular_contato TEXT    NULL,
    eju_ddd         TEXT    NULL,
    eju_ddi         TEXT    NULL,
    eju_telefone    TEXT    NULL,
    eju_ramal       TEXT    NULL,
    eju_fax         TEXT    NULL,
    eju_email       TEXT    NULL,
    eju_homepage    TEXT    NULL,
    eju_faculdade   TEXT    NULL,                                       /* Como conheceu a Empresa Junior */
    eju_rel_estreita INT        NOT NULL    DEFAULT '0',                               /* Relações Estreitas ? 0 - Nao; 1 - Sim */
    eju_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Ramos de Atividade para os Clientes ( Empresas ) */
CREATE TABLE ramo 
(
    ram_id          SERIAL  NOT NULL    PRIMARY KEY,
    ram_nome        TEXT    NULL,                                   /* Nome do Ramo de Atividade */
    ram_desc        TEXT    NULL,
    ram_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Arquivos Upload */
CREATE TABLE arquivo
(
    arq_id          SERIAL  NOT NULL    PRIMARY KEY,
    arq_texto       TEXT    NULL,                                       /* Textos atribuidos a um arquivo, descricao, observacoes, etc... */
    arq_nome_real   TEXT    NULL,                                       /* Nome real como ele foi gravado no sistema de arquivos pra garantir unicidade */
    arq_nome_falso  TEXT    NULL,                                       /* Nome que o usuario passou */
    arq_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Entidade Logo ( vai entender... ) */
CREATE TABLE logo 
(
    lgo_id          SERIAL  NOT NULL    PRIMARY KEY,
    lgo_nome        TEXT    NULL,                                   /* Nome do Logo */
    lgo_desc        TEXT    NULL,                                       /* Descricao do Logo */
    lgo_nome_real   TEXT    NULL,                                       /* Nome Real do arquivo do logo gravado no sistema de arquivos */
    lgo_nome_falso  TEXT    NULL,                                       /* Nome do arquivo que o cara fez upload */
    lgo_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Alunos que nao sao da FGV */
CREATE TABLE aluno_nao_gv 
(
    ang_id          SERIAL  NOT NULL    PRIMARY KEY,
    ang_nome        TEXT    NULL,
    ang_endereco    TEXT    NULL,
    ang_bairro      TEXT    NULL,
    ang_ddi         TEXT    NULL,
    ang_ddd         TEXT    NULL,
    ang_telefone    TEXT    NULL,
    ang_ramal       TEXT    NULL,
    ang_cep         TEXT    NULL,
    ang_celular     TEXT    NULL,
    ang_email       TEXT    NULL,
    ang_dt_nasci    TIMESTAMP   NULL,
    ang_faculdade   TEXT    NULL,
    ang_curso       TEXT    NULL,
    ang_convidado   INT     NULL,                                       /* Boolean: 0 - Nao; 1 - Sim */
    ang_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE patrocinador 
(
    set_id          INT     NULL,                                       /* FK - Setor do Patrocinador */
    cla_id          INT     NULL,                                       /* FK - pat_class  ( classificacao ) */
    cex_id          INT     NULL,                                       /* FK - cargo_ext  ( cargo do contato ) */

    pat_id          SERIAL  NOT NULL    PRIMARY KEY,
    pat_nome        TEXT    NULL,                                   /* Nome do patrocinador */
    pat_nome_contato    TEXT    NULL,                                   /* Nome do contato */
    pat_ddi         TEXT    NULL,
    pat_ddd         TEXT    NULL,
    pat_telefone    TEXT    NULL,
    pat_ramal       TEXT    NULL,
    pat_fax         TEXT    NULL,
    pat_email       TEXT    NULL,
    pat_celular     TEXT    NULL,
    pat_apoiador    INT     NULL    DEFAULT '0',                        /* 0 - Patrocinador ; 1 - Apoiador */
    pat_texto       TEXT    NULL,                                       /* Comentarios / Observacoes */
    pat_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pat_class 
(
    cla_id          SERIAL  NOT NULL,
    cla_nome        TEXT    NULL,
    cla_desc        TEXT    NULL,
    cla_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE fornecedor 
(
    ram_id          INT     NULL,                                       /* FK - Ramo de Atividade */
    cex_id          INT     NULL,                                       /* FK - cargo_ext ( cargo do contato ) */

    for_id          SERIAL  NOT NULL    PRIMARY KEY,
    for_nome        TEXT    NULL,
    for_servicos    TEXT    NULL,
    for_nome_contato    TEXT    NULL,                                   /* Nome do Contato */
    for_ddd         TEXT    NULL,
    for_ddi         TEXT    NULL,
    for_telefone    TEXT    NULL,
    for_ramal       TEXT    NULL,
    for_fax         TEXT    NULL,
    for_email       TEXT    NULL,
    for_celular     TEXT    NULL,
    for_homepage    TEXT    NULL,
    for_texto       TEXT    NULL,                                       /* Comentarios / Observacoes */
    for_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE status_contato 
(
    stc_id          SERIAL  NOT NULL    PRIMARY KEY,
    stc_nome        TEXT    NULL    UNIQUE,
    stc_desc        TEXT    NULL,
    stc_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE palestrante
(
    cex_id          INT     NULL,                                        /* FK - cargo_ext ( cargo do contato ) */
    pal_cargo       INT     NULL,                                        /* FK - cargo_ext ( cargo do palestrante ) */

    pal_id          SERIAL  NOT NULL    PRIMARY KEY,
    pal_nome        TEXT    NULL,
    pal_curriculo   TEXT    NULL,
    pal_nome_contato    TEXT    NULL,
    pal_ddd         TEXT    NULL,
    pal_ddi         TEXT    NULL,
    pal_telefone    TEXT    NULL,
    pal_ramal       TEXT    NULL,
    pal_fax         TEXT    NULL,
    pal_email       TEXT    NULL,
    pal_celular     TEXT    NULL,
    pal_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Setor de Atuacao da Empresa/Cliente */
CREATE TABLE setor 
(
    set_id          SERIAL  NOT NULL    PRIMARY KEY,
    set_nome        TEXT    NULL,
    set_desc        TEXT    NULL,
    set_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Cargo da EJ */
CREATE TABLE cargo_gv 
(
    cgv_id          SERIAL  NOT NULL    PRIMARY KEY,
    cgv_nome        TEXT    NULL,
    cgv_desc        TEXT    NULL,
    cgv_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Cargo externo */
CREATE TABLE cargo_ext 
(
    cex_id          SERIAL  NOT NULL    PRIMARY KEY,
    cex_nome        TEXT    NULL,
    cex_desc        TEXT    NULL,
    cex_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);


/* Funcionarios da FGV */
CREATE TABLE funcionario_gv 
(
    fgv_id      SERIAL  NOT NULL   PRIMARY KEY,
    fgv_nome    TEXT    NULL,
    fgv_funcao  TEXT    NULL,
    fgv_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE area 
(
    are_id      SERIAL  NOT NULL    PRIMARY KEY,
    are_nome    TEXT    NULL,
    are_desc    TEXT    NULL,
    are_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE regiao 
(
    reg_id      SERIAL  NOT NULL    PRIMARY KEY,
    reg_nome    TEXT    NULL    UNIQUE,
    reg_desc    TEXT    NULL,
    reg_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE funcao 
(
    fnc_id      SERIAL  NOT NULL    PRIMARY KEY,
    fnc_nome    TEXT    NULL    UNIQUE,
    fnc_desc    TEXT    NULL,
    fnc_soh_log INT     NOT NULL    DEFAULT 0,
    fnc_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades e Relacionamentos de Consultoria */
/* if( $status == 8 ) Consultoria = Projeto */
CREATE TABLE consultoria
(
    cli_id                  INT         NOT NULL,                               /* FK - id do cliente/empresa */
    ppg_id                  INT         NULL,
    bri_id                  INT         NULL,                                   /* FK - para brinde */
    cst_prp_coordenador     INT         NULL,                                   /* FK - id do membro coordenador de proposta */

    cst_id                  SERIAL      NOT NULL    PRIMARY KEY,
    cst_nome                TEXT        NULL,                               /* Nome da consultoria */
    cst_texto               TEXT        NULL,                                   /* Problema apresentado / Conteudo da proposta */
    cst_valor               NUMERIC ( 9,2 ) NULL  DEFAULT '0.0',            /* Valor ( R$ ) da consultoria */
    cst_local_reuniao       TEXT        NULL,                                   /* Local da Reuniao */
    cst_dt_contato          TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  /* Data de Contato */
    cst_dt_retorno_u        INT         NULL,                                   /* Data de retorno em dias uteis */
    cst_dt_retorno          TIMESTAMP   NULL,                                   /* Data de retorno ( calculada a partir do contato e dias uteis ) */
    cst_dt_reuniao          TIMESTAMP   NULL        DEFAULT CURRENT_TIMESTAMP,  /* Data da Reuniao */
    cst_dt_prj_ini          TIMESTAMP   NULL        DEFAULT CURRENT_TIMESTAMP,  /* Data de inicio de projeto */
    cst_dt_prj_fim          TIMESTAMP   NULL        DEFAULT CURRENT_TIMESTAMP,  /* Data de fim de projeto */

    cst_local_prp_reuniao   TEXT        NULL,                                   /* Local de reuniao para Entrega da Proposta */
    cst_dt_prp_envio        TIMESTAMP   NULL        DEFAULT CURRENT_TIMESTAMP,  /* Data de Envio da Proposta */
    cst_dt_prp_entrega      TIMESTAMP   NULL        DEFAULT CURRENT_TIMESTAMP,  /* prazo final entrega proposta */
    cst_dt_prp_retorno_u    INT         NULL,
    cst_dt_prp_retorno      TIMESTAMP   NULL,                                   /* prazo para retorno do cliente ( automatico ) */
    cst_dt_prp_reuniao      TIMESTAMP   NULL,                                   /* Data de reuniao para entrega da proposta */
    cst_status              TEXT        NOT NULL    DEFAULT 'nova consultoria',
        /*
            Possiveis:
                - nova consultoria
                - consultoria nao confirmada
                - reuniao marcada
                - proposta em andamento
                - proposta concluida
                - reuniao nao gerou proposta
                - proposta enviada
                    - stand by
                    - follow up
                - contrato em andamento
                - projeto em andamento
                - projeto finalizado
        */
    cst_dt_inc              TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Brinde para Cliente */
CREATE TABLE brinde
(
    bri_id      SERIAL      NOT NULL    PRIMARY KEY,                /* Id do brinde */
    bri_nome    TEXT        NULL,
    bri_desc    TEXT        NULL,
    bri_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Comentario de Consultoria */
CREATE TABLE comentario
(
    cst_id      INT         NOT NULL,                               /* FK - id da consultoria */
    cst_status  TEXT        NULL,                                   /* Status da consultoria */

    com_id      SERIAL      NOT NULL    PRIMARY KEY,                /* Id do comentario */
    com_texto   TEXT        NULL,
    com_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Atividade de uma Consultoria ( Status: Gerar Proposta ) */
CREATE TABLE cst_atividade 
(
    cst_id      INT         NOT NULL,                               /* FK - id da consultoria */

    atv_id      SERIAL      NOT NULL    PRIMARY KEY,                /* Id da Atividade */
    atv_ordem   INT         NOT NULL,                               /* Ordem das Atividade */
    atv_desc    TEXT        NULL,                                   /* Descricao da Atividade */
    atv_dt_ini  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  /* Data de Inicio  */
    atv_dt_fim_u  INT       NULL,                                   /* Quantidade de dias uteis para o termino */
    atv_dt_fim  TIMESTAMP   NULL,                                   /* Data final ( calculada a partir dos dias uteis )  */
    atv_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cobranca 
(
    cst_id      INT         NOT NULL,                               /* FK - Consultoria */

    cob_id      SERIAL      NOT NULL    PRIMARY KEY,
    cob_parcela INT             NULL,                               /* Numero da Parcela  ( <= ppg_plano )*/
    cob_valor   NUMERIC( 9,2 )    NULL    DEFAULT '0.0',            /* Valor da Cobranca */
    cob_dt_venc TIMESTAMP   NOT NULL,                               /* Data de vencimento da cobranca */
    cob_nota        INT     NULL,                                   /* Nota fiscal */
    cob_protocolo   INT     NOT NULL    DEFAULT '0',                /* protocolo assinado? 0 - Nao; 1 - Sim */
    cob_pago        INT     NOT NULL    DEFAULT '0',                /* pago? 0 - Nao; 1 - Sim */
    cob_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);


/* Plano de Pagamento para cobranca de Consultoria */
CREATE TABLE plano_pgto 
(
    ppg_id      SERIAL      NOT NULL    PRIMARY KEY,
    ppg_nome    TEXT        NULL,
    ppg_desc    TEXT        NULL,
    ppg_plano   INT         NULL        DEFAULT '1',                /* Plano: Quantidade de Parcelas */
    ppg_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Lembrar!!: Consultoria = Projeto */
CREATE TABLE tipo_projeto
(
    tpj_id      SERIAL      NOT NULL    PRIMARY KEY,
    tpj_nome    TEXT        NULL,
    tpj_desc    TEXT        NULL,
    tpj_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Etapa de Consultoria */
CREATE TABLE cst_etapa 
(
    cst_id      INT         NOT NULL,                               /* FK - id da consultoria */
    etp_id      SERIAL      NOT NULL    PRIMARY KEY,                /* Id da etapa */
    etp_ordem   INT         NOT NULL,                               /* Ordem das etapas */
    etp_desc    TEXT        NULL,                                   /* Descricao da Etapa */
    etp_dt_ini  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  /* Data de Inicio  */
    etp_dt_fim_u INT        NULL,                                   /* Data de Termino ( dias uteis ) */
    etp_dt_fim  TIMESTAMP   NULL,
    etp_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP
);

/* Consultoria X Membro */
/* Consultor para reuniao */
CREATE TABLE cst_mem 
(
    cst_id      INT         NOT NULL,                               /* FK - Id da consultoria */
    cst_status  TEXT        NOT NULL,                               /* status da consultoria */
    mem_id      INT         NOT NULL,                               /* FK - Id do Membro */
    
    cme_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( mem_id, cst_id, cst_status )                         /* Primary Key dupla com os dois Ids garantindo Unicidade */
);

/* Consultoria X Tipo_Projeto */
CREATE TABLE cst_tpj 
(
    cst_id      INT         NOT NULL,                               /* FK - Id da consultoria */
    tpj_id      INT         NOT NULL,                               /* FK - Id do tipo_projeto */
    ctp_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( cst_id, tpj_id )
);

/* Consultoria X Professor */
CREATE TABLE cst_prf
(
    cst_id      INT         NOT NULL,                               /* FK - Id da consultoria */
    cst_status  TEXT        NOT NULL,                               /* status da consultoria */
    prf_id      INT         NOT NULL,                               /* FK - Id do professor */
    cpr_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( cst_id, prf_id, cst_status )
);

/* Consultoria X Arquivo */
CREATE TABLE cst_arq
(
    cst_id          INT     NOT NULL,                                   /* FK - consultoria */
    cst_status      TEXT    NOT NULL,                                   /* status consultoria */
    arq_id          INT     NOT NULL,                                   /* FK - arquivo     */
    ast_dt_inc      TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( cst_id, cst_status, arq_id )                             /* Primary Key tripla com os IDs e o cst_status */
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades e Relacionamentos de Evento */
/* Entidade Evento */
CREATE TABLE evento
(
    ste_id          INT     NULL,                                       /* FK - Status_Evento */
    tev_id          INT     NOT NULL,                                   /* FK - Tipo_Evento */

    evt_id          SERIAL  NOT NULL    PRIMARY KEY,
    evt_edicao      TEXT    NOT NULL,
    evt_tema        TEXT    NULL,
    evt_local       TEXT    NULL,
    evt_dt          TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    evt_dt_fim      TIMESTAMP   NULL,
    evt_dt_ent_art  TIMESTAMP   NULL,                                   /* Data de Entrega do Artigo ( Evento Premio Gestao ) */
    evt_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Evento x Professor */
CREATE TABLE evt_prf
(
    evt_id          INT     NOT NULL,
    prf_id          INT     NOT NULL,
    cat_id          INT     NOT NULL,
    stc_id          INT     NOT NULL,
    epr_texto       TEXT    NULL,
    epr_entregue    INT     NULL    DEFAULT '0',                        /* 0 - Nao; 1- Sim */
    epr_dt_inc      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, prf_id )
);

/* Criterios ( Evento Prêmio Gestão ) */
CREATE TABLE criterio
(
    cri_id          SERIAL  NOT NULL    PRIMARY KEY,
    cri_nome        TEXT    NULL,
    cri_peso        INT     NULL        DEFAULT '1',
    cri_desc        TEXT    NULL,
    cri_dt_inc      TIMESTAMP NOT NULL  DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE status_evento
(
    ste_id          SERIAL  NOT NULL    PRIMARY KEY,
    ste_nome        TEXT    NULL,
    ste_desc        TEXT    NULL,
    ste_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Categoria de Evento */
CREATE TABLE categoria
(
    cat_id          SERIAL  NOT NULL     PRIMARY KEY,
    cat_nome        TEXT    NULL,
    cat_desc        TEXT    NULL,
    cat_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipo_evento
(
    tev_id          SERIAL  NOT NULL     PRIMARY KEY,
    tev_nome        TEXT    NULL,
    tev_desc        TEXT    NULL,
    tev_mne         TEXT    NULL,                                       /* Coisa do switch */
    tev_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE equipe
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    agv_id          INT     NULL,                                       /* FK - Aluno_gv - LIDER */

    eqp_id          SERIAL  NOT NULL    PRIMARY KEY,
    eqp_nome        TEXT    NULL,
    eqp_colocacao   INT     NULL,
    eqp_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE material_grafico
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */

    mgf_id          SERIAL  NOT NULL    PRIMARY KEY,
    mgf_nome        TEXT    NULL,
    mgf_desc        TEXT    NULL,
    mgf_arq_real    TEXT    NULL,                                       /* Nome Real do Arquivo de Upload de Material Grafico no FS */
    mgf_arq_falso   TEXT    NULL,                                       /* Nome que o Usuario colocou */
    mgf_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evt_arquivo
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */

    ear_id          SERIAL  NOT NULL    PRIMARY KEY,
    ear_nome        TEXT    NULL,
    ear_desc        TEXT    NULL,
    ear_arq_real    TEXT    NULL,                                       /* Nome Real do Arquivo de Upload de Material Grafico no FS */
    ear_arq_falso   TEXT    NULL,                                       /* Nome que o Usuario colocou */
    ear_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE item_final
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */

    ifi_id          SERIAL  NOT NULL    PRIMARY KEY,
    ifi_nome        TEXT    NULL,
    ifi_desc        TEXT    NULL,
    ifi_arq_real    TEXT    NULL,                                       /* Nome Real do Arquivo de Upload de Material Grafico no FS */
    ifi_arq_falso   TEXT    NULL,                                       /* Nome que o Usuario colocou */
    ifi_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Tipo Convidado */
CREATE TABLE tipo_convidado
(
    tcv_id          SERIAL  NOT NULL    PRIMARY KEY,
    tcv_nome        TEXT    NULL,
    tcv_desc        TEXT    NULL,
    tcv_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Evento_Premio_gestao X aluno_gv */
CREATE TABLE inscrito_pg
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    agv_id          INT     NOT NULL,                                   /* FK - Aluno GV */
    cat_id          INT     NOT NULL,                                   /* FK - Categoria */
    prf_id_1        INT     NULL,                                       /* FK - Professor 1 */
    prf_id_2        INT     NULL,                                       /* FK - Professor 2 */
    cri_id_1        INT     NULL,                                       /* FK - Criterio  1 */
    cri_id_2        INT     NULL,                                       /* FK - Criterio  2 */

    ipg_resumo      INT     NULL    DEFAULT '0',                        /* 0 - Nao; 1 - Sim */
    ipg_nota_1      NUMERIC( 3,1 )    NULL,                             /* Nota 1 */
    ipg_nota_2      NUMERIC( 3,1 )    NULL,                             /* Nota 2 */
    ipg_peso_1      INT     NULL,                                       /* Peso Nota 1 */
    ipg_peso_2      INT     NULL,                                       /* Peso Nota 2 */
    ipg_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, agv_id )
);

/* Evento X Aluno_Gv */
CREATE TABLE inscrito_gv
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    agv_id          INT     NOT NULL,                                   /* FK - Aluno GV */
    tcv_id          INT     NULL,                                       /* FK - Tipo de Convidado */

    igv_id          SERIAL  NOT NULL    PRIMARY KEY,                    /* Id do Inscrito */
    igv_convidado   INT     NOT NULL DEFAULT '0',                       /* Convidado: BOOLEAN 0 - Nao; 1 - Sim */
    igv_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Evento X Aluno_Nao_Gv */
CREATE TABLE inscrito_ngv
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    ang_id          INT     NOT NULL,                                   /* FK - Aluno Nao GV */
    tcv_id          INT     NULL,                                       /* FK - Tipo de Convidado */

    ing_id          SERIAL  NOT NULL    PRIMARY KEY,                    /* Id do Inscrito */
    ing_convidado   INT     NOT NULL    DEFAULT '0',                    /* Convidado: BOOLEAN 0 - Nao; 1 - Sim */
    ing_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE evt_tarefa
(
    evt_id          INT     NOT NULL,                               /* FK - Evento */
    mem_id          INT     NOT NULL,                               /* FK - Membro */
    ste_id          INT     NOT NULL,                               /* FK - Staus Evento */

    eta_id          SERIAL  NOT NULL    PRIMARY KEY,
    eta_desc    TEXT        NULL,                                   /* Descricao da Atividade */
    eta_dt_ini  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  /* Data de Inicio  */
    eta_dt_fim  TIMESTAMP   NULL,
    eta_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ferramenta
(
    are_id      INT     NULL,                                       /* FK - Area */

    frm_id      SERIAL  NOT NULL    PRIMARY KEY,
    frm_nome    TEXT    NULL,
    frm_arq_real    TEXT    NULL,
    frm_arq_falso   TEXT    NULL,
    frm_desc    TEXT    NULL,
    frm_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Ferramenta X Consultoria */
CREATE TABLE frm_cst 
(
    frm_id      INT         NOT NULL,                               /* FK - Ferramenta */
    cst_id      INT         NOT NULL,                               /* FK - Consultoria */
    fcs_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( frm_id, cst_id )
);

/* Ferramenta X Evento */
CREATE TABLE frm_evt 
(
    frm_id      INT         NOT NULL,                               /* FK - Ferramenta */
    evt_id      INT         NOT NULL,                               /* FK - Evento */
    fev_dt_inc  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( frm_id, evt_id )
);

CREATE TABLE evt_custo 
(
    evt_id          INT     NOT NULL,                               /* FK - Evento */

    cto_id          SERIAL  NOT NULL    PRIMARY KEY,
    cto_nome        TEXT    NULL,
    cto_t_movimento INT     NOT NULL,                               
        /*
            Tipo de Movimento:
            0 - Receita ( + )
            1 - Despesa ( - )
        */
    cto_valor       NUMERIC( 9,2 )    NOT NULL    DEFAULT '0.0',
    cto_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);


/* Aluno_Gv X Equipe */
CREATE TABLE eqp_agv
(
    agv_id          INT     NOT NULL,                                   /* FK - Aluno_Gv */
    eqp_id          INT     NOT NULL,                                   /* FK - Equipe */
    eqa_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( agv_id, eqp_id )
);


/* Equipe Alocada */
/* Evento X Membro */
CREATE TABLE evt_mem
(
    evt_id          INT NOT NULL,                       /* FK - Evento */
    mem_id          INT NOT NULL,                       /* FK - Membro */ 

    eme_coordenador INT NULL    DEFAULT '0',            /* para evento premio gestao somente */
    eme_dt_inc      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, mem_id )
);

/* Evento X Palestrante */
CREATE TABLE evt_pal 
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    pal_id          INT     NOT NULL,                                   /* FK - Palestrante */
    mem_id          INT     NOT NULL,                                   /* FK - Membro - Responsavel pela empresa */
    stc_id          INT     NULL,                                       /* FK - Status_contato */

    epl_texto       TEXT    NULL,                                       /* Comentarios */
    epl_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, pal_id )
);

/* Evento X Fornecedor */
CREATE TABLE evt_for 
(
    evt_id          INT     NOT NULL,                                   /* FK - Evento */
    for_id          INT     NOT NULL,                                   /* FK - Fornecedor */
    mem_id          INT     NOT NULL,                                   /* FK - Membro - Responsavel pela empresa */
    stc_id          INT     NULL,                                       /* FK - Status_contato */

    efo_texto       TEXT    NULL,
    efo_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, for_id )
);

/* Evento X Patrocinador */
CREATE TABLE evt_pat 
(
    evt_id          INT     NOT NULL,                                       /* FK - Evento */ 
    pat_id          INT     NOT NULL,                                       /* FK - Patrocinador */
    mem_id          INT     NOT NULL,                                       /* FK - Membro */
    stc_id          INT     NULL,                                           /* FK - Status_contato */

    epa_texto       TEXT    NULL,
    epa_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( evt_id, pat_id )
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades P_Seletivo */
/* Processo Seletivo : Rotatividade de Membros da Empresa Junior */
CREATE TABLE p_seletivo 
(
    psl_id          SERIAL  NOT NULL    PRIMARY KEY,
    psl_dt_selecao  TIMESTAMP   NOT NULL    DEFAULT CURRENT_TIMESTAMP,  /* Data do Processo Seletivo */
    psl_arq_real    TEXT    NULL,                                       /* Arquivo de Cronograma: Nome Real do arquivo como ele eh gravado no FS */
    psl_arq_falso   TEXT    NULL,                                       /* Nome que o cara passou */
    psl_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE dinamica
(
    psl_id      INT     NOT NULL,                                       /* FK - Processo Seletivo */
    din_id      SERIAL  NOT NULL    PRIMARY KEY,
    din_fase    INT     NOT NULL,
        /*
            1 - Primeira Fase
            2 - Segunda Fase
            3 - Terceira Fase ( Entrevista )
        */
    din_numero  INT     NULL,
    din_local   TEXT    NULL,
    din_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,
    din_ent_nome    TEXT    NULL,                                       /* Nome da Entrevista ( quando em fase 3 ) */
    din_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE palestra
(
    psl_id      INT     NOT NULL,                                       /* FK - Processo Seletivo */

    plt_id      SERIAL  NOT NULL,
    plt_nome    TEXT    NULL,
    plt_desc    TEXT    NULL,
    plt_local   TEXT    NULL,
    plt_dt      TIMESTAMP   NULL,
    plt_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Dinamica X Aluno_Gv */
CREATE TABLE candidato_din
(
    din_id      INT     NOT NULL,                                       /* FK - dinamica */
    agv_id      INT     NOT NULL,                                       /* FK - aluno_gv */

    cnd_status  INT     NOT NULL    DEFAULT '0',
        /*
            0 - Novo
            1 - Aprovado
            2 - Reprovado
        */
    cnd_fb_solic    INT         NOT NULL    DEFAULT '0',                /* FeedBack - Solicitado? ( 0 - Nao; 1 - Sim ) */
    cnd_fb_dt       TIMESTAMP   NULL,                                   /* FeedBack - Data de FeedBack */
    cnd_fb_mem_id   INT         NULL,                                   /* FeedBack - FK - Membro ( Consultor ) */
    cnd_fb_realizado    INT     NOT NULL    DEFAULT '0',                /* FeedBack - Realizado?  ( 0 - Nao; 1 - Sim ) */
    cnd_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( din_id, agv_id )
);

/* Dinamica X Membro */
CREATE TABLE acompanha
(
    din_id      INT     NOT NULL,                                       /* FK - dinamica */
    mem_id      INT     NOT NULL,                                       /* FK - membro */

    acm_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( din_id, mem_id )
);

/* P_seletivo X Aluno_Gv */
CREATE TABLE candidato_psl
(
    psl_id      INT     NOT NULL,                                       /* FK - dinamica */
    agv_id      INT     NOT NULL,                                       /* FK - aluno_gv */
    cps_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( psl_id, agv_id )
);

/* P_seletivo X Fornecedor ( Empresa Contratada ) */
CREATE TABLE abastece
(
    psl_id      INT     NOT NULL,                                       /* FK - Processo Seletivo */
    for_id      INT         NULL,                                       /* FK - Fornecedor ( Empresa Contratada ) */
    aba_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( psl_id, for_id )
);

/* P_seletivo X Membro ( Auditor / Consultor ) */
CREATE TABLE audita
(
    psl_id      INT     NOT NULL,                                       /* FK - dinamica */
    mem_id      INT         NULL,                                       /* FK - Membro ( Auditor / Consultor ) */
    aud_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( psl_id, mem_id )
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades TimeSheet */
/* TimeSheet */
CREATE TABLE timesheet
(
    mem_id          INT     NOT NULL,                                   /* FK - membro */
    are_id          INT     NULL,                                       /* FK - area */
    cst_id          INT     NULL,                                       /* FK - consultoria */
    tsa_id          INT     NULL,                                       /* FK - ts_subatividade */
    cli_id          INT     NULL,                                       /* FK - cliente/empresa */
    pin_id          INT     NULL,                                       /* FK - prj_interno */
    evt_id          INT     NULL,                                       /* FK - evento */
    tat_id          INT     NULL,                                       /* FK - ts_atividade */

    tsh_id          SERIAL          NOT NULL    PRIMARY KEY,
    tsh_dt          TIMESTAMP       NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    tsh_duracao     NUMERIC( 3,1 )  NOT NULL    DEFAULT '0.0',          /* Duracao em horas ( 4,5 = 4 horas e meia = 4:30 ) */
    tsh_texto       TEXT    NULL,                                       /* Observacao ... */
    tsh_dt_inc      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ts_atividade
(
    are_id          INT     NULL,                                      /* FK - area */

    tat_id          SERIAL  NOT NULL    PRIMARY KEY,
    tat_nome        TEXT    NULL,
    tat_mne         TEXT    NULL,
    tat_list_emp    INT     NOT NULL,
        /*
        Listar empresa / Evento ?
        0 - Nao
        1 - Sim
        */
    tat_list_subat  INT     NOT NULL,
        /*
        Listar Sub-atividade ?
        0 - Nao
        1 - Sim
        */
    tat_desc        TEXT    NULL,                                       /* Descricao da Atividade */
    tat_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE ts_subatividade
(
    tsa_id          SERIAL  NOT NULL    PRIMARY KEY,
    tsa_nome        TEXT    NULL,
    tsa_desc        TEXT    NULL,                                       /* Descricao da Sub Atividade */
    tsa_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* Ts_atividade X Ts_subatividade */
CREATE TABLE tat_tsa
(
    tat_id          INT     NOT NULL,                                   /* FK - ts_atividade */
    tsa_id          INT     NOT NULL,                                   /* FK - ts_subatividade */
    tss_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY( tat_id, tsa_id )
);

CREATE TABLE prj_interno
(
    pin_id          SERIAL  NOT NULL    PRIMARY KEY,
    pin_nome        TEXT    UNIQUE  NULL,
    pin_desc        TEXT    NULL,
    pin_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Entidades TaskList */
CREATE TABLE task
(
    ttk_id      INT     NOT NULL,                                   /* FK - Tipo_Task */
    stt_id      INT     NOT NULL,                                   /* FK - Status_Tastk */
    mem_id_de   INT     NULL,                                       /* FK - Membro, ID do Destinatario ( cara que recebe ) */
    mem_id_para INT     NOT NULL,                                   /* FK - Membro, ID do Remetente ( cara que envia ) */
    tsk_gemea   INT     NULL,					    /* Pseudo FK */

    tsk_acao    INT     NOT NULL,                                   /* Acao: 0 - enviada, 1 - recebida */
    tsk_id      SERIAL  NOT NULL    PRIMARY KEY,
    tsk_dt      TIMESTAMP   NULL    DEFAULT CURRENT_TIMESTAMP,      /* Data/Hora de envio do task */
    tsk_assunto TEXT    NOT NULL,                                   /* Titulo da Task */
    tsk_mensagem    TEXT    NULL,
    tsk_dt_inc      TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tipo_task 
(
    ttk_id      SERIAL  NOT NULL    PRIMARY KEY,
    ttk_nome    TEXT    NULL,
    ttk_desc    TEXT    NULL,                                       /* Descricao do Tipo de Task */
    ttk_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE status_task 
(
    stt_id      SERIAL  NOT NULL    PRIMARY KEY,   
    stt_nome    TEXT    NULL,
    stt_desc    TEXT    NULL,                                       /* Descricao do Status de Task */
    stt_dt_inc  TIMESTAMP   NOT NULL DEFAULT CURRENT_TIMESTAMP
);

/* ------------------------------------------------------------------------------------------------------------------- */

/* Foreign Keys */

/* Principais */
ALTER TABLE membro          ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE membro          ADD FOREIGN KEY ( cgv_id ) REFERENCES cargo_gv ( cgv_id )       ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE grade_horario   ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE grp_fnc         ADD FOREIGN KEY ( grp_id ) REFERENCES grupo ( grp_id )          ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE grp_fnc         ADD FOREIGN KEY ( fnc_id ) REFERENCES funcao ( fnc_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE grp_mem         ADD FOREIGN KEY ( grp_id ) REFERENCES grupo ( grp_id )          ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE grp_mem         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE professor       ADD FOREIGN KEY ( dpt_id ) REFERENCES departamento ( dpt_id )   ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE cliente         ADD FOREIGN KEY ( ram_id ) REFERENCES ramo ( ram_id )           ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE cliente         ADD FOREIGN KEY ( cex_id ) REFERENCES cargo_ext ( cex_id )      ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE cliente         ADD FOREIGN KEY ( reg_id ) REFERENCES regiao ( reg_id )         ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE empresa_junior  ADD FOREIGN KEY ( reg_id ) REFERENCES regiao ( reg_id )         ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE empresa_junior  ADD FOREIGN KEY ( cex_id ) REFERENCES cargo_ext ( cex_id )      ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE fornecedor      ADD FOREIGN KEY ( ram_id ) REFERENCES ramo ( ram_id )           ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE fornecedor      ADD FOREIGN KEY ( cex_id ) REFERENCES cargo_ext ( cex_id )      ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE palestrante     ADD FOREIGN KEY ( cex_id ) REFERENCES cargo_ext ( cex_id )      ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE palestrante     ADD FOREIGN KEY ( pal_cargo ) REFERENCES cargo_ext ( cex_id )   ON DELETE SET NULL  ON UPDATE CASCADE;

/* Consultoria */
ALTER TABLE consultoria     ADD FOREIGN KEY ( cli_id ) REFERENCES cliente ( cli_id )        ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE consultoria     ADD FOREIGN KEY ( cst_prp_coordenador ) REFERENCES membro ( mem_id )    ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE consultoria     ADD FOREIGN KEY ( bri_id ) REFERENCES brinde ( bri_id )         ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE consultoria     ADD FOREIGN KEY ( ppg_id ) REFERENCES plano_pgto ( ppg_id )     ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE comentario      ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_atividade   ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cobranca        ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_prf         ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_prf         ADD FOREIGN KEY ( prf_id ) REFERENCES professor ( prf_id )      ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_tpj         ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_tpj         ADD FOREIGN KEY ( tpj_id ) REFERENCES tipo_projeto ( tpj_id )   ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_mem         ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_mem         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_etapa       ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_arq         ADD FOREIGN KEY ( arq_id ) REFERENCES arquivo ( arq_id )        ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE cst_arq         ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE patrocinador    ADD FOREIGN KEY ( set_id ) REFERENCES setor ( set_id )          ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE patrocinador    ADD FOREIGN KEY ( cla_id ) REFERENCES pat_class ( cla_id )      ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE patrocinador    ADD FOREIGN KEY ( cex_id ) REFERENCES cargo_ext ( cex_id )      ON DELETE SET NULL  ON UPDATE CASCADE;

/* Evento */
ALTER TABLE evento          ADD FOREIGN KEY ( ste_id ) REFERENCES status_evento ( ste_id )  ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE evento          ADD FOREIGN KEY ( tev_id ) REFERENCES tipo_evento ( tev_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_arquivo     ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_mem         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_mem         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_prf         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_prf         ADD FOREIGN KEY ( prf_id ) REFERENCES professor ( prf_id )      ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pat         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pat         ADD FOREIGN KEY ( pat_id ) REFERENCES patrocinador ( pat_id )   ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pat         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pat         ADD FOREIGN KEY ( stc_id ) REFERENCES status_contato ( stc_id ) ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_for         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_for         ADD FOREIGN KEY ( for_id ) REFERENCES fornecedor ( for_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_for         ADD FOREIGN KEY ( stc_id ) REFERENCES status_contato ( stc_id ) ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_for         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pal         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pal         ADD FOREIGN KEY ( pal_id ) REFERENCES palestrante ( pal_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pal         ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_pal         ADD FOREIGN KEY ( stc_id ) REFERENCES status_contato ( stc_id ) ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE eqp_agv         ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE eqp_agv         ADD FOREIGN KEY ( eqp_id ) REFERENCES equipe ( eqp_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE ferramenta      ADD FOREIGN KEY ( are_id ) REFERENCES area ( are_id )           ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE frm_cst         ADD FOREIGN KEY ( frm_id ) REFERENCES ferramenta ( frm_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE frm_cst         ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE frm_evt         ADD FOREIGN KEY ( frm_id ) REFERENCES ferramenta ( frm_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE frm_evt         ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_tarefa      ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_tarefa      ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_tarefa      ADD FOREIGN KEY ( ste_id ) REFERENCES status_evento ( ste_id )  ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE evt_custo       ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE equipe          ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE equipe          ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE material_grafico ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )        ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE item_final      ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_gv     ADD FOREIGN KEY ( tcv_id ) REFERENCES tipo_convidado ( tcv_id ) ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_gv     ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_ngv    ADD FOREIGN KEY ( tcv_id ) REFERENCES tipo_convidado ( tcv_id ) ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_ngv    ADD FOREIGN KEY ( ang_id ) REFERENCES aluno_nao_gv ( ang_id )   ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( cat_id ) REFERENCES categoria ( cat_id )      ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( prf_id_1 ) REFERENCES professor ( prf_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( prf_id_2 ) REFERENCES professor ( prf_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( cri_id_1 ) REFERENCES criterio ( cri_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE inscrito_pg     ADD FOREIGN KEY ( cri_id_2 ) REFERENCES criterio ( cri_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE abastece        ADD FOREIGN KEY ( psl_id ) REFERENCES p_seletivo ( psl_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE abastece        ADD FOREIGN KEY ( for_id ) REFERENCES fornecedor ( for_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE audita          ADD FOREIGN KEY ( psl_id ) REFERENCES p_seletivo ( psl_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE audita          ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE dinamica        ADD FOREIGN KEY ( psl_id ) REFERENCES p_seletivo ( psl_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE candidato_psl   ADD FOREIGN KEY ( psl_id ) REFERENCES p_seletivo ( psl_id )     ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE candidato_psl   ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE candidato_din   ADD FOREIGN KEY ( din_id ) REFERENCES dinamica ( din_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE candidato_din   ADD FOREIGN KEY ( agv_id ) REFERENCES aluno_gv ( agv_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE candidato_din   ADD FOREIGN KEY ( cnd_fb_mem_id ) REFERENCES membro ( mem_id )  ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE acompanha       ADD FOREIGN KEY ( din_id ) REFERENCES dinamica ( din_id )       ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE acompanha       ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;

/* Processo Seletivo */
ALTER TABLE palestra        ADD FOREIGN KEY ( psl_id ) REFERENCES p_seletivo ( psl_id )     ON DELETE CASCADE   ON UPDATE CASCADE;

/* TimeSheet */
ALTER TABLE timesheet       ADD FOREIGN KEY ( tsa_id ) REFERENCES ts_subatividade ( tsa_id )    ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( are_id ) REFERENCES area ( are_id )           ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( cli_id ) REFERENCES cliente ( cli_id )        ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( evt_id ) REFERENCES evento ( evt_id )         ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( mem_id ) REFERENCES membro ( mem_id )         ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( tat_id ) REFERENCES ts_atividade ( tat_id )   ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( pin_id ) REFERENCES prj_interno ( pin_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE timesheet       ADD FOREIGN KEY ( cst_id ) REFERENCES consultoria ( cst_id )    ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE ts_atividade    ADD FOREIGN KEY ( are_id ) REFERENCES area ( are_id )           ON DELETE SET NULL  ON UPDATE CASCADE;
ALTER TABLE tat_tsa         ADD FOREIGN KEY ( tat_id ) REFERENCES ts_atividade ( tat_id )   ON DELETE CASCADE   ON UPDATE CASCADE;
ALTER TABLE tat_tsa         ADD FOREIGN KEY ( tsa_id ) REFERENCES ts_subatividade ( tsa_id )    ON DELETE CASCADE   ON UPDATE CASCADE;

/* Task */
ALTER TABLE task            ADD FOREIGN KEY ( ttk_id ) REFERENCES tipo_task ( ttk_id )      ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE task            ADD FOREIGN KEY ( stt_id ) REFERENCES status_task ( stt_id )    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE task            ADD FOREIGN KEY ( mem_id_de )   REFERENCES membro ( mem_id )    ON DELETE CASCADE ON UPDATE CASCADE;
ALTER TABLE task            ADD FOREIGN KEY ( mem_id_para ) REFERENCES membro ( mem_id )    ON DELETE CASCADE ON UPDATE CASCADE;

/* Avisos Automáticos */
ALTER TABLE ava_cgv         ADD FOREIGN KEY ( ava_id ) REFERENCES aviso_auto ( ava_id )     ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE ava_cgv         ADD FOREIGN KEY ( cgv_id ) REFERENCES cargo_gv ( cgv_id )       ON DELETE CASCADE ON UPDATE CASCADE; 


/* ------------------------------------------------------------------------------------------------------------------- */

/* Views */

/* Todos Membros */
CREATE VIEW membro_todos 
        AS
            SELECT
                cgv_id,
                mem_id,
                agv_nome AS mem_nome,
                mem_login,
                mem_senha, 
                mem_apelido,
                agv_rg    AS mem_rg,
                agv_cpf   AS mem_cpf,
                agv_email AS mem_email,
                agv_ddd AS mem_ddd,
                agv_ddi AS mem_ddi,
                agv_telefone AS mem_telefone,
                agv_celular  AS mem_celular,
                mem_dt_saida,
                mem_vivo,
                agv_dt_nasci AS mem_dt_nasci
        FROM
            membro
            NATURAL JOIN aluno_gv;

/* View pra Matricula */
/*
CREATE VIEW busca_matricula
        AS
            SELECT
                SUBSTR( agv_matricula, 1, 2 ) AS curso,
                SUBSTR( agv_matricula, 3, 2 ) AS ano,
                SUBSTR( agv_matricula, 5, 1 ) AS semestre
            FROM
                aluno_gv;
*/

/* Membros ativos/vivos */
CREATE VIEW membro_vivo 
        AS
            SELECT *
        FROM
            membro_todos
        WHERE
            mem_vivo = 1
            AND mem_dt_saida IS NULL;
            
/* Alunos ativos/vivos */
CREATE VIEW aluno_vivo 
        AS SELECT
                agv_id,
                agv_nome
        FROM
            aluno_gv
        WHERE
            agv_vivo = 1
            AND agv_dt_saida IS NULL;

/* Alunos ativos/vivos Nao membros */
CREATE VIEW aluno_vivo_nao_membro
        AS SELECT
                agv_id,
                agv_nome
        FROM
            aluno_gv
        WHERE
            agv_vivo = 1
            AND agv_dt_saida IS NULL
            AND agv_id NOT IN
                (
                    SELECT
                        mem_id
                    FROM
                        membro_todos
                );

/* Funcoes permitidas ao Membro */
CREATE VIEW membro_funcao
        AS
            SELECT
                DISTINCT mem_id,
                fnc_nome 
        FROM
            funcao
            NATURAL JOIN grp_fnc
            NATURAL JOIN grp_mem
            NATURAL JOIN membro_vivo;

CREATE VIEW busca_grupo
        AS
        SELECT
            grp_id,
            grp_nome,
            fnc_id,
            fnc_nome,
            mem_id,
            mem_nome
        FROM
            grupo
            NATURAL LEFT OUTER JOIN ( grp_fnc NATURAL JOIN funcao )
            NATURAL LEFT OUTER JOIN ( grp_mem NATURAL JOIN membro_vivo ); 

CREATE VIEW busca_ts_atividade
        AS
        SELECT
            tsa_id,
            are_nome,
            tsa_nome,
            tat_id,
            tat_nome
        FROM
            ts_atividade
            NATURAL JOIN area
            NATURAL LEFT OUTER JOIN ( tat_tsa NATURAL JOIN ts_subatividade );

CREATE VIEW busca_consultoria
        AS
        SELECT
            cst_id,
            cst_nome,
            cst_status,
            cli_nome,
            cli_ddd,
            cli_ddi,
            cli_telefone,
            cli_homepage
        FROM
            consultoria
            NATURAL JOIN cliente;

/* ------------------------------------------------------------------------------------------------------------------- */
/* Functions ( Stored Procedures ) */

/* Atualizacao de inscrito_pg se mudar a banca julgadora ( evt_prf ) */
CREATE FUNCTION ipg_inscrito_upd_fnc( int ) 
RETURNS INT AS '
    DECLARE
        foo RECORD;
        prf_id ALIAS FOR $1;
    BEGIN
        FOR foo IN 
            SELECT 
                evt_id,
                agv_id,
                prf_id_1,
                prf_id_2
            FROM
                inscrito_pg
       LOOP
            IF( foo.prf_id_1 = prf_id )
            THEN
                UPDATE inscrito_pg
                SET
                    prf_id_1 = NULL
                WHERE
                    evt_id = foo.evt_id
                    AND agv_id = foo.agv_id;
            END IF;

            IF( foo.prf_id_2 = prf_id )
            THEN
                UPDATE inscrito_pg
                SET
                    prf_id_2 = NULL
                WHERE
                    evt_id = foo.evt_id
                    AND agv_id = foo.agv_id;
            END IF;
        END LOOP;
        RETURN 0;
    END;'
    LANGUAGE 'plpgsql';

/* ------------------------------------------------------------------------------------------------------------------- */
/* Rules */
/* Insercao de grade de horario para membro */

CREATE RULE gho_membro_ins
    AS ON
        INSERT TO membro
        DO
        (
            INSERT INTO grade_horario   
            (
                mem_id
            )
            VALUES
            (
                NEW.mem_id
            )
        );

CREATE RULE ipg_inscrito_upd
    AS ON
        UPDATE TO evt_prf
        DO
            SELECT ipg_inscrito_upd_fnc( NEW.prf_id );

/* ------------------------------------------------------------------------------------------------------------------- */
/* Triggers */

/* Garantindo que soh tem um coordenador na equipe alocada */
/*
CREATE TRIGGER eme_evt_mem_upd_tri
    BEFORE UPDATE ON evt_mem
    FOR EACH ROW
        EXECUTE PROCEDURE eme_evt_mem_upd_fnc( );
*/

/* Dados Fixos do Sistema */
/* EJ-Geral / ADM */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: aluno gv apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: aluno gv inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: aluno gv alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: aluno gv consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: aluno gv listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: empresa junior apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: empresa junior inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: empresa junior alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: empresa junior consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: empresa junior listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: fornecedor apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: fornecedor inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: fornecedor alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: fornecedor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: fornecedor listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: ferramenta apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: ferramenta inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: ferramenta alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: ferramenta consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: ferramenta listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: projeto interno apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: projeto interno inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: projeto interno alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: projeto interno consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: projeto interno listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: funcionario gv apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: funcionario gv inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: funcionario gv alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: funcionario gv consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: funcionario gv listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'adm: etiquetas criar' );

/* Cadastros */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo email apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo email inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo email alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo email consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo email listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo task apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo task inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo task alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo task consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo task listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status task apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status task inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status task alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status task consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status task listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status contato apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status contato inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status contato alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status contato consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status contato listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status cronograma apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status cronograma inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status cronograma alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status cronograma consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: status cronograma listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet atividade apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet atividade inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet atividade alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet atividade consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet atividade listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet subatividade apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet subatividade inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet subatividade alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet subatividade consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: timesheet subatividade listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: feriado apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: feriado inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: feriado alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: feriado consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: feriado listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: ramo apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: ramo inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: ramo alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: ramo consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: ramo listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo servico apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo servico inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo servico alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo servico consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: tipo servico listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: aviso auto listar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: aviso auto alterar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: backup criar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: backup recuperar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: area apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: area inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: area alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: area consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: area listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: plano pgto apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: plano pgto inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: plano pgto alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: plano pgto consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: plano pgto listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: classificacao apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: classificacao inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: classificacao alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: classificacao consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: classificacao listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: setor apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: setor inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: setor alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: setor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: setor listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: regiao apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: regiao inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: regiao alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: regiao consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: regiao listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: grupo apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: grupo inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: grupo alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: grupo consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: grupo listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo ej apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo ej inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo ej alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo ej consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo ej listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo externo apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo externo inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo externo alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo externo consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cad: cargo externo listar' );

/* Consultoria */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: departamento apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: departamento inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: departamento alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: departamento consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: departamento listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: professor apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: professor inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: professor alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: professor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: professor listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria professor apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria professor inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria professor alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria professor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria professor listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria atividade apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria atividade inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria atividade alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria atividade consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria atividade listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria etapa apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria etapa inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria etapa alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria etapa consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria etapa listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria tipo projeto apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria tipo projeto inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria tipo projeto alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria tipo projeto consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria tipo projeto listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor reuniao apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor reuniao inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor reuniao alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor reuniao consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor reuniao listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor projeto apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor projeto inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor projeto alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor projeto consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria consultor projeto listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria cobranca apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria cobranca inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria cobranca alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria cobranca consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria cobranca listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria brinde apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria brinde inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria brinde alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria brinde consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: consultoria brinde listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: cliente apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: cliente inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: cliente alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: cliente consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: cliente listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: tipo projeto apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: tipo projeto inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: tipo projeto alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: tipo projeto consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'cst: tipo projeto listar' );

/* Marketing */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: evento apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: evento inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: evento listar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: evento alterar parte organizacional' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: evento alterar parte publica' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: patrocinador apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: patrocinador inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: patrocinador alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: patrocinador consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: patrocinador listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: palestrante apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: palestrante inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: palestrante alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: palestrante consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: palestrante listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo produto apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo produto inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo produto alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo produto consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo produto listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo evento apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo evento inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo evento alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo evento consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo evento listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: criterio apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: criterio inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: criterio alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: criterio consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: criterio listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: categoria apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: categoria inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: categoria alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: categoria consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: categoria listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: logo apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: logo inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: logo alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: logo consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: logo listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: brinde apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: brinde inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: brinde alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: brinde consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: brinde listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo convidado apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo convidado inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo convidado alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo convidado consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'mkt: tipo convidado listar' );

/* RH */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: processo seletivo apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: processo seletivo inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: processo seletivo alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: processo seletivo consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: processo seletivo listar' );

INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: membro apagar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: membro inserir' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: membro alterar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: membro consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rh: membro listar' );

/* Relatorios */
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: cliente consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: consultoria consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: evento consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: premio gestao consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: membro e ex-membro consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: empresa junior consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: fornecedor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: patrocinador consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: palestrante consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: professor consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: aluno da gv consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: aluno nao gv consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: timesheet consultar' );
INSERT INTO funcao ( fnc_nome ) VALUES ( 'rel: processo seletivo consultar' );

/* MISC */
INSERT INTO funcao ( fnc_nome, fnc_soh_log ) VALUES ( 'login',  1 );
INSERT INTO funcao ( fnc_nome, fnc_soh_log ) VALUES ( 'logout', 1 );

/* ------------------------------------------------------------------------------------------------------------------- */

/* Status para as tasks */
INSERT INTO status_task( stt_nome, stt_desc ) VALUES( 'Em aberto',  'Task em aberto' );
INSERT INTO status_task( stt_nome, stt_desc ) VALUES( 'Baixada',    'Task baixada'   );
INSERT INTO status_task( stt_nome, stt_desc ) VALUES( 'Arquivada',  'Task baixada'   );

/* Tipos de tasks */
INSERT INTO tipo_task( ttk_nome, ttk_desc ) VALUES( 'Task do Sistema',  'Task do Sistema' );

/* Areas do timesheet */
INSERT INTO area( are_nome, are_desc ) VALUES( 'Consultoria', 'Consultoria' );
INSERT INTO area( are_nome, are_desc ) VALUES( 'Marketing',   'Marketing'   );
INSERT INTO area( are_nome, are_desc ) VALUES( 'R.H.',        'R.H.'        );
INSERT INTO area( are_nome, are_desc ) VALUES( 'EJ-Geral',    'EJ-Geral'    );
INSERT INTO area( are_nome, are_desc ) VALUES( 'ADM',         'ADM'         );
INSERT INTO area( are_nome, are_desc ) VALUES( 'Diretoria',   'Diretoria'   );

/* Atividades do timesheet */
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Consultoria' ), 'Atendimento',   'atendimento',   'Atendimento',   1, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Consultoria' ), 'Projeto',       'projeto',       'Projeto',       1, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Consultoria' ), 'Coordenadoria', 'coordenadoria', 'Coordenadoria', 1, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Consultoria' ), 'Reunião',       'reuniao',       'Reunião',       0, 0 );

INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'Eventos',           'eventos',           'Eventos',           1, 0 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'Projetos Internos', 'projetos_internos', 'Projetos Internos', 1, 0 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'FUP',               'fup',               'FUP',               1, 0 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'Comunicação',       'comunicacao',       'Comunicação',       0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'Tarefa Interna',    'tarefa_interna',    'Tarefa Interna',    0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Marketing' ), 'Reunião',           'reuniao',           'Reunião',           0, 0 );

INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'R.H.' ), 'Seleção',     'selecao',     'Seleção',     0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'R.H.' ), 'Treinamento', 'treinamento', 'Treinamento', 1, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'R.H.' ), 'Reunião',     'reuniao',     'Reunião',     0, 0 );

INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'EJ-Geral' ), 'Reunião Gestão',  'premio_gestao',   'Reunião Gestão',  0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'EJ-Geral' ), 'Planejamento',    'planejamento',    'Planejamento',    0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'EJ-Geral' ), 'Assembléia',      'assembleia',      'Assembléia',      0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'EJ-Geral' ), 'Integração',      'integracao',      'Integração',      0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'EJ-Geral' ), 'Outra Atividade', 'outra_atividade', 'Outra Atividade', 0, 1 );

INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'ADM' ), 'Financeiro',      'financeiro',      'Financeiro',      0, 0 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'ADM' ), 'Projeto Interno', 'projeto_interno', 'Projeto Interno', 1, 0 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'ADM' ), 'Tarefa Interna',  'tarefa_interna',  'Tarefa Interna',  0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'ADM' ), 'Reunião',         'reuniao',         'Reunião',         0, 0 );

INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'Consultoria', 'consultoria', 'Consultoria', 0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'Marketing',   'marketing',   'Marketing',   0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'ADM',         'adm',         'ADM',         0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'R.H.',        'rh',          'R.H.',        0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'Presidência', 'presidencia', 'Presidência', 0, 1 );
INSERT INTO ts_atividade( are_id, tat_nome, tat_mne, tat_desc, tat_list_emp, tat_list_subat ) VALUES( ( SELECT are_id FROM area WHERE are_nome = 'Diretoria' ), 'Reunião',     'reuniao',     'Reunião',     0, 0 );

/* Tipos de Evento Padrão */
INSERT INTO tipo_evento( tev_nome, tev_mne ) VALUES( 'Café & Negócios',  NULL );
INSERT INTO tipo_evento( tev_nome, tev_mne ) VALUES( 'SuperAção',        'super_acao' );
INSERT INTO tipo_evento( tev_nome, tev_mne ) VALUES( 'Prêmio Gestão',    'premio_gestao' );


/* Avisos Automaticos- Prêmio Gestão */
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'email_antes_entrega_artigo', 'Entregar de artigo em uma semana', 'Você deve entregar seu artigo dentro de uma semana.', 'email' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'email_entrega_artigo', 'Entrega de artigo hoje', 'Você deve entregar seu artigo hoje.', 'email' );

/* Avisos Automaticos- Evento Superação */
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'email_membros_equipes', 'Evento Superação', 'Sua equipe participará amanhã de um evento Superação.', 'email' );

/* Avisos Automaticos- Cadastro de Clientes */
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_novo_cliente', 'Nova consultoria cadastrada', 'A seguinte consultoria foi cadastrada:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_retorno_telefonico_vencido', 'Retorno telefônico vencido', 'O retorno telefônico do(s) seguinte(s) cliente(s) venceu:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_reuniao_marcada', 'Reunião Marcada', 'Você tem reunião(ões) marcada(s).', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_entrega_proposta', 'Entrega de proposta', 'Prazo final de entrega da proposta:', 'task' );

/* Avisos Automaticos- Diversos */
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_feriado', 'Cadastrar Feriados', 'Cadastrar feriados do ano corrente.', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_carta_agradecimento', 'Carta de agradecimento', 'Enviar carta de agradecimento ao(s) professor(es):', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_parcela_vencer', 'Parcelas a vencer', 'Parcela(s) a vencer na próxima semana:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_parcela_vencida', 'Parcelas vencidas', 'A(s) seguinte(s) parcela(s) venceu(ram) ontem:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_enviar_brinde_professor', 'Enviar brinde', 'Enviar brinde para o(s) seguinte(s) professore(s):', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_atualizar_posicao_membro_janeiro', 'Atualizar posição de membros / Janeiro', 'Atualizar posição de membros.', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-03-23', 'task_atualizar_posicao_membro_marco', 'Atualizar posição de membros / Março', 'Atualizar posição de membros.', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-09-23', 'task_atualizar_posicao_membro_setembro', 'Atualizar posição de membros / Setembro', 'Atualizar posição de membros.', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-03-01', 'task_atualizar_grade_horario_marco', 'Atualizar grade de horários / Março', 'Atualizar grade de horários.', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-08-07', 'task_atualizar_grade_horario_agosto', 'Atualizar grade de horários / Agosto', 'Atualizar grade de horário(s).', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_aniversario_membro', 'Aniversário de membro', 'Os sequinte(s) membro(s) faz(em) aniversário:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_empresa_junior', 'Atualizar cadastros de EJs', 'As sequintes Empresas Jrs devem atualizar seus dados de cadastro:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_aniversario_professor', 'Aniversário de professor', 'Os sequinte(s) professor(es) faz(em) aniversário:', 'task' );
INSERT INTO aviso_auto ( ava_dt, ava_mne, ava_assunto, ava_mensagem, ava_tipo ) VALUES ( '1970-01-01', 'task_feedback_membro', 'Feedback', 'Dar feedback ao(s) seguinte(s) aluno(s):', 'task' );

/* Cargos EJ Default */ 
INSERT INTO cargo_gv ( cgv_nome ) VALUES ( 'RH' );
INSERT INTO cargo_gv ( cgv_nome ) VALUES ( 'Presidente' );
INSERT INTO cargo_gv ( cgv_nome ) VALUES ( 'Diretor de Marketing' );

/* Status de Contatos Fixos */
INSERT INTO status_contato( stc_nome ) VALUES( 'Confirmado' );


/*-------------------------------------------------DUMMY DATA----------------------------------------------------*/

INSERT INTO cargo_ext ( cex_nome ) VALUES ( 'RH' );
INSERT INTO cargo_ext ( cex_nome ) VALUES ( 'Presidente' );
INSERT INTO cargo_ext ( cex_nome ) VALUES ( 'Diretor de Marketing' );

INSERT INTO tipo_task( ttk_nome ) VALUES( 'Tipo1' );
INSERT INTO tipo_task( ttk_nome ) VALUES( 'Tipo2' );
INSERT INTO tipo_task( ttk_nome ) VALUES( 'Tipo3' );

INSERT INTO ramo( ram_nome ) VALUES( 'Ramo1' );
INSERT INTO ramo( ram_nome ) VALUES( 'Ramo2' );
INSERT INTO ramo( ram_nome ) VALUES( 'Ramo3' );
INSERT INTO ramo( ram_nome ) VALUES( 'Ramo4' );

INSERT INTO setor( set_nome ) VALUES( 'Setor1' );
INSERT INTO setor( set_nome ) VALUES( 'Setor2' );
INSERT INTO setor( set_nome ) VALUES( 'Setor3' );
INSERT INTO setor( set_nome ) VALUES( 'Setor4' );

INSERT INTO departamento( dpt_nome ) VALUES( 'Departamento1' );
INSERT INTO departamento( dpt_nome ) VALUES( 'Departamento2' );
INSERT INTO departamento( dpt_nome ) VALUES( 'Departamento3' );
INSERT INTO departamento( dpt_nome ) VALUES( 'Departamento4' );

INSERT INTO pat_class( cla_nome ) VALUES( 'Classificacao1' );
INSERT INTO pat_class( cla_nome ) VALUES( 'Classificacao2' );
INSERT INTO pat_class( cla_nome ) VALUES( 'Classificacao3' );
INSERT INTO pat_class( cla_nome ) VALUES( 'Classificacao4' );

INSERT INTO tipo_convidado( tcv_nome ) VALUES( 'Tipo Convidado1' );
INSERT INTO tipo_convidado( tcv_nome ) VALUES( 'Tipo Convidado2' );
INSERT INTO tipo_convidado( tcv_nome ) VALUES( 'Tipo Convidado3' );
INSERT INTO tipo_convidado( tcv_nome ) VALUES( 'Tipo Convidado4' );

INSERT INTO tipo_projeto( tpj_nome ) VALUES( 'Tipo Projeto1' );
INSERT INTO tipo_projeto( tpj_nome ) VALUES( 'Tipo Projeto2' );
INSERT INTO tipo_projeto( tpj_nome ) VALUES( 'Tipo Projeto3' );
INSERT INTO tipo_projeto( tpj_nome ) VALUES( 'Tipo Projeto4' );

INSERT INTO criterio( cri_nome ) VALUES( 'Criterio1' );
INSERT INTO criterio( cri_nome ) VALUES( 'Criterio2' );
INSERT INTO criterio( cri_nome ) VALUES( 'Criterio3' );
INSERT INTO criterio( cri_nome ) VALUES( 'Criterio4' );

INSERT INTO regiao( reg_nome ) VALUES( 'Regiao1' );
INSERT INTO regiao( reg_nome ) VALUES( 'Regiao2' );
INSERT INTO regiao( reg_nome ) VALUES( 'Regiao3' );
INSERT INTO regiao( reg_nome ) VALUES( 'Regiao4' );

INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade1' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade2' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade3' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade4' );

INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria1', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria2', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria3', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria4', 'Bla' );

INSERT INTO status_evento( ste_nome ) VALUES( 'Status Cronograma1' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Cronograma2' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Cronograma3' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Cronograma4' );

INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato1' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato2' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato3' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato4' );

INSERT INTO fornecedor( ram_id, cex_id, for_nome ) VALUES( 1, 1, 'Fornecedor1' );
INSERT INTO fornecedor( ram_id, cex_id, for_nome ) VALUES( 1, 1, 'Fornecedor2' );
INSERT INTO fornecedor( ram_id, cex_id, for_nome ) VALUES( 1, 1, 'Fornecedor3' );
INSERT INTO fornecedor( ram_id, cex_id, for_nome ) VALUES( 1, 1, 'Fornecedor4' );

INSERT INTO patrocinador( set_id, cla_id, cex_id, pat_nome ) VALUES( 1, 1, 1, 'Patrocinador1' );
INSERT INTO patrocinador( set_id, cla_id, cex_id, pat_nome ) VALUES( 1, 1, 1, 'Patrocinador2' );
INSERT INTO patrocinador( set_id, cla_id, cex_id, pat_nome ) VALUES( 1, 1, 1, 'Patrocinador3' );
INSERT INTO patrocinador( set_id, cla_id, cex_id, pat_nome ) VALUES( 1, 1, 1, 'Patrocinador4' );

INSERT INTO palestrante( cex_id, pal_cargo, pal_nome ) VALUES( 1, 1, 'Palestrante1' );
INSERT INTO palestrante( cex_id, pal_cargo, pal_nome ) VALUES( 1, 1, 'Palestrante2' );
INSERT INTO palestrante( cex_id, pal_cargo, pal_nome ) VALUES( 1, 1, 'Palestrante3' );
INSERT INTO palestrante( cex_id, pal_cargo, pal_nome ) VALUES( 1, 1, 'Palestrante4' );

INSERT INTO cliente( cex_id, ram_id, reg_id, cli_nome ) VALUES( 1, 1, 1, 'Aberium Systems' );
INSERT INTO cliente( cex_id, ram_id, reg_id, cli_nome ) VALUES( 1, 1, 1, 'X Factor' );
INSERT INTO cliente( cex_id, ram_id, reg_id, cli_nome ) VALUES( 1, 1, 1, 'Shuberies S/A' );
INSERT INTO cliente( cex_id, ram_id, reg_id, cli_nome ) VALUES( 1, 1, 1, 'CrazY Inc.' );

INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_dt_nasci ) VALUES( '1301012', 'Marcio Ribeiro' );
INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_dt_nasci ) VALUES( '1202031', 'Ze Mane 3' );
INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_dt_nasci ) VALUES( '1101011', 'Ivan Neto' );

INSERT INTO membro( mem_id, agv_id, mem_login, mem_senha, mem_dt_entrada ) VALUES( 1, 1, 'binary', 'RK4IbntxYvuxHN8TvSc0zjMcr7n2ljRypFI7ni2pBRI=', '2000-01-02' );
INSERT INTO membro( mem_id, agv_id, mem_login, mem_senha, mem_dt_entrada ) VALUES( 2, 2, 'zemane', 'RK4IbntxYvuxHN8TvSc0zjMcr7n2ljRypFI7ni2pBRI=', '2000-01-03' );
INSERT INTO membro( mem_id, agv_id, mem_login, mem_senha, mem_dt_entrada ) VALUES( 3, 3, 'ivanneto', 'RK4IbntxYvuxHN8TvSc0zjMcr7n2ljRypFI7ni2pBRI=', '2000-01-03' );
SELECT SETVAL( 'membro_mem_id_seq', 3, 't' );

INSERT INTO grupo( grp_nome ) VALUES( 'Grupo X' );
INSERT INTO grp_mem( grp_id, mem_id ) VALUES( '1', '1' );
INSERT INTO grp_mem( grp_id, mem_id ) VALUES( '1', '2' );
INSERT INTO grp_mem( grp_id, mem_id ) VALUES( '1', '3' );

INSERT INTO grp_fnc( grp_id, fnc_id ) VALUES( '1', ( SELECT fnc_id FROM funcao WHERE fnc_nome = 'cad: grupo apagar' ) );
INSERT INTO grp_fnc( grp_id, fnc_id ) VALUES( '1', ( SELECT fnc_id FROM funcao WHERE fnc_nome = 'cad: grupo inserir' ) );
INSERT INTO grp_fnc( grp_id, fnc_id ) VALUES( '1', ( SELECT fnc_id FROM funcao WHERE fnc_nome = 'cad: grupo alterar' ) );
INSERT INTO grp_fnc( grp_id, fnc_id ) VALUES( '1', ( SELECT fnc_id FROM funcao WHERE fnc_nome = 'cad: grupo consultar' ) );
INSERT INTO grp_fnc( grp_id, fnc_id ) VALUES( '1', ( SELECT fnc_id FROM funcao WHERE fnc_nome = 'cad: grupo listar' ) );
