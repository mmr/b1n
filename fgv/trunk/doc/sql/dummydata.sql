INSERT INTO tipo_task( ttk_nome, ttk_desc ) VALUES( 'Tipo1', 'Tipo de task 1' );
INSERT INTO tipo_task( ttk_nome, ttk_desc ) VALUES( 'Tipo2', 'Tipo de task 2' );
INSERT INTO tipo_task( ttk_nome, ttk_desc ) VALUES( 'Tipo3', 'Tipo de task 3' );

INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_endereco, agv_bairro, agv_telefone, agv_ramal, agv_cep, agv_celular, agv_email, agv_dt_nasci ) VALUES( 'Matricula1', 'Ivan Bittenourt', 'Rua do endereco 1', 'Bairro1', 'Telefone1', 'Ramal1', 'Cep1', 'Celular1', 'E-mail1', '2002-01-01' );
INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_endereco, agv_bairro, agv_telefone, agv_ramal, agv_cep, agv_celular, agv_email, agv_dt_nasci ) VALUES( 'Matricula2', 'Marcio Ribeiro', 'Rua do endereco 2', 'Bairro2', 'Telefone2', 'Ramal2', 'Cep2', 'Celular2', 'E-mail2', '2002-01-02' );
INSERT INTO aluno_gv( agv_matricula, agv_nome, agv_endereco, agv_bairro, agv_telefone, agv_ramal, agv_cep, agv_celular, agv_email, agv_dt_nasci ) VALUES( 'Matricula3', 'Ze Mane 3', 'Rua do endereco 3', 'Bairro3', 'Telefone3', 'Ramal3', 'Cep3', 'Celular3', 'E-mail3', '2002-01-03' );

INSERT INTO membro( agv_id, mem_login, mem_senha, mem_dt_entrada, mem_apelido ) VALUES( '2', 'binary', 'binary', '2000-01-02', 'binary' );
INSERT INTO membro( agv_id, mem_login, mem_senha, mem_dt_entrada, mem_apelido ) VALUES( '3', 'zemane', 'zemane', '2000-01-03', 'zemane' );

INSERT INTO ramo( ram_nome, ram_desc ) VALUES( 'Ramo1', 'Ramo1' );
INSERT INTO ramo( ram_nome, ram_desc ) VALUES( 'Ramo2', 'Ramo2' );
INSERT INTO ramo( ram_nome, ram_desc ) VALUES( 'Ramo3', 'Ramo3' );
INSERT INTO ramo( ram_nome, ram_desc ) VALUES( 'Ramo4', 'Ramo4' );

INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato 1' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato 2' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato 3' );
INSERT INTO status_contato( stc_nome ) VALUES( 'Status Contato 4' );

INSERT INTO cargo( cgo_nome ) VALUES( 'Cargo 1' );
INSERT INTO cargo( cgo_nome ) VALUES( 'Cargo 2' );
INSERT INTO cargo( cgo_nome ) VALUES( 'Cargo 3' );
INSERT INTO cargo( cgo_nome ) VALUES( 'Cargo 4' );

INSERT INTO fornecedor( ram_id, cgo_id, for_nome ) VALUES( 1, 1, 'Fornecedor 1' );
INSERT INTO fornecedor( ram_id, cgo_id, for_nome ) VALUES( 1, 1, 'Fornecedor 2' );
INSERT INTO fornecedor( ram_id, cgo_id, for_nome ) VALUES( 1, 1, 'Fornecedor 3' );
INSERT INTO fornecedor( ram_id, cgo_id, for_nome ) VALUES( 1, 1, 'Fornecedor 4' );
INSERT INTO fornecedor( ram_id, cgo_id, for_nome ) VALUES( 1, 1, 'Fornecedor 5' );

INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade 1' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade 2' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade 3' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade 4' );
INSERT INTO ts_subatividade( tsa_nome ) VALUES( 'TS Sub-atividade 5' );

INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria 1', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria 2', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria 3', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria 4', 'Bla' );
INSERT INTO categoria( cat_nome, cat_desc ) VALUES( 'Categoria 5', 'Bla' );

INSERT INTO status_evento( ste_nome ) VALUES( 'Status Evento 1' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Evento 2' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Evento 3' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Evento 4' );
INSERT INTO status_evento( ste_nome ) VALUES( 'Status Evento 5' );
