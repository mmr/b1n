<?
header( "Content-Type: octet/stream" );
header( "Content-Disposition: attachment; filename=" . $arquivo );
$retorno = implode( "\n", $retorno );
print str_replace(  "\\connect - fgv",
                    "\\connect - fgv" .
                    "\nDROP FUNCTION ipg_inscrito_upd_fnc( int );" .
                    "\nDROP RULE gho_membro_ins;" .
                    "\nDROP RULE ipg_inscrito_upd;" .
                    "\nDROP VIEW membro_vivo;" .
                    "\nDROP VIEW membro_todos;" .
                    "\nDROP VIEW aluno_vivo;" .
                    "\nDROP VIEW aluno_vivo_nao_membro;" .
                    "\nDROP VIEW membro_funcao;" .
                    "\nDROP VIEW busca_grupo;" .
                    "\nDROP VIEW busca_consultoria;" .
                    "\nDROP VIEW busca_ts_atividade;" .
                    "\nDROP SEQUENCE aviso_auto_ava_id_seq;" .
                    "\nDROP SEQUENCE aluno_gv_agv_id_seq;" .
                    "\nDROP SEQUENCE aluno_nao_gv_ang_id_seq;" .
                    "\nDROP SEQUENCE area_are_id_seq;" .
                    "\nDROP SEQUENCE arquivo_arq_id_seq;" .
                    "\nDROP SEQUENCE cargo_gv_id_seq;" .
                    "\nDROP SEQUENCE cargo_cex_id_seq;" .
                    "\nDROP SEQUENCE cliente_cli_id_seq;" .
                    "\nDROP SEQUENCE empresa_junior_eju_id_seq;" .
                    "\nDROP SEQUENCE departamento_dpt_id_seq;" .
                    "\nDROP SEQUENCE fornecedor_for_id_seq;" .
                    "\nDROP SEQUENCE funcao_fnc_id_seq;" .
                    "\nDROP SEQUENCE funcionario_gv_fgv_id_seq;" .
                    "\nDROP SEQUENCE feriado_frd_id_seq;" .
                    "\nDROP SEQUENCE tipo_servico_tse_id_seq;" .
                    "\nDROP SEQUENCE logo_lgo_id_seq;" .
                    "\nDROP SEQUENCE membro_mem_id_seq;" .
                    "\nDROP SEQUENCE grade_horario_gho_id_seq;" .
                    "\nDROP SEQUENCE grupo_grp_id_seq;" .
                    "\nDROP SEQUENCE log_log_id_seq;" .
                    "\nDROP SEQUENCE palestrante_pal_id_seq;" .
                    "\nDROP SEQUENCE patrocinador_pat_id_seq;" .
                    "\nDROP SEQUENCE pat_class_cla_id_seq;" .
                    "\nDROP SEQUENCE professor_prf_id_seq;" .
                    "\nDROP SEQUENCE ramo_ram_id_seq;" .
                    "\nDROP SEQUENCE setor_set_id_seq;" .
                    "\nDROP SEQUENCE status_contato_stc_id_seq;" .
                    "\nDROP SEQUENCE regiao_reg_id_seq;" .
                    "\nDROP SEQUENCE consultoria_cst_id_seq;" .
                    "\nDROP SEQUENCE brinde_bri_id_seq;" .
                    "\nDROP SEQUENCE comentario_com_id_seq;" .
                    "\nDROP SEQUENCE cst_atividade_atv_id_seq;" .
                    "\nDROP SEQUENCE cobranca_cob_id_seq;" .
                    "\nDROP SEQUENCE plano_pgto_ppg_id_seq;" .
                    "\nDROP SEQUENCE tipo_projeto_tpj_id_seq;" .
                    "\nDROP SEQUENCE cst_etapa_etp_id_seq;" .
                    "\nDROP SEQUENCE evento_evt_id_seq;" .
                    "\nDROP SEQUENCE criterio_cri_id_seq;" .
                    "\nDROP SEQUENCE status_evento_ste_id_seq;" .
                    "\nDROP SEQUENCE categoria_cat_id_seq;" .
                    "\nDROP SEQUENCE tipo_evento_tev_id_seq;" .
                    "\nDROP SEQUENCE equipe_eqp_id_seq;" .
                    "\nDROP SEQUENCE material_grafico_mgf_id_seq;" .
                    "\nDROP SEQUENCE item_final_ifi_id_seq;" .
                    "\nDROP SEQUENCE tipo_convidado_tcv_id_seq;" .
                    "\nDROP SEQUENCE inscrito_gv_igv_id_seq;" .
                    "\nDROP SEQUENCE inscrito_ngv_ing_id_seq;" .
                    "\nDROP SEQUENCE evt_tarefa_eta_id_seq;" .
                    "\nDROP SEQUENCE ferramenta_frm_id_seq;" .
                    "\nDROP SEQUENCE evt_custo_cto_id_seq;" .
                    "\nDROP SEQUENCE evt_arquivo_ear_id_seq;" .
                    "\nDROP SEQUENCE p_seletivo_psl_id_seq;" .
                    "\nDROP SEQUENCE dinamica_din_id_seq;" .
                    "\nDROP SEQUENCE palestra_plt_id_seq;" .
                    "\nDROP SEQUENCE timesheet_tsh_id_seq;" .
                    "\nDROP SEQUENCE ts_atividade_tat_id_seq;" .
                    "\nDROP SEQUENCE ts_subatividade_tsa_id_seq;" .
                    "\nDROP SEQUENCE prj_interno_pin_id_seq;" .
                    "\nDROP SEQUENCE task_tsk_id_seq;" .
                    "\nDROP SEQUENCE tipo_task_ttk_id_seq;" .
                    "\nDROP SEQUENCE status_task_stt_id_seq;" .
                    "\nDROP TABLE ava_cgv;" .
                    "\nDROP TABLE aviso_auto;" .
                    "\nDROP TABLE aluno_gv;" .
                    "\nDROP TABLE aluno_nao_gv;" .
                    "\nDROP TABLE grade_horario;" .
                    "\nDROP TABLE area;" .
                    "\nDROP TABLE arquivo;" .
                    "\nDROP TABLE cargo_gv;" .
                    "\nDROP TABLE cargo_ext;" .
                    "\nDROP TABLE cliente;" .
                    "\nDROP TABLE empresa_junior;" .
                    "\nDROP TABLE departamento;" .
                    "\nDROP TABLE fornecedor;" .
                    "\nDROP TABLE funcao;" .
                    "\nDROP TABLE funcionario_gv;" .
                    "\nDROP TABLE feriado;" .
                    "\nDROP TABLE tipo_servico;" .
                    "\nDROP TABLE logo;" .
                    "\nDROP TABLE membro;" .
                    "\nDROP TABLE grupo;" .
                    "\nDROP TABLE grp_fnc;" .
                    "\nDROP TABLE grp_mem;" .
                    "\nDROP TABLE log;" .
                    "\nDROP TABLE palestrante;" .
                    "\nDROP TABLE patrocinador;" .
                    "\nDROP TABLE pat_class;" .
                    "\nDROP TABLE professor;" .
                    "\nDROP TABLE ramo;" .
                    "\nDROP TABLE setor;" .
                    "\nDROP TABLE status_contato;" .
                    "\nDROP TABLE regiao;" .
                    "\nDROP TABLE cst_arq;" .
                    "\nDROP TABLE consultoria;" .
                    "\nDROP TABLE brinde;" .
                    "\nDROP TABLE comentario;" .
                    "\nDROP TABLE cst_atividade;" .
                    "\nDROP TABLE cobranca;" .
                    "\nDROP TABLE plano_pgto;" .
                    "\nDROP TABLE tipo_projeto;" .
                    "\nDROP TABLE cst_etapa;" .
                    "\nDROP TABLE cst_mem;" .
                    "\nDROP TABLE cst_tpj;" .
                    "\nDROP TABLE cst_prf;" .
                    "\nDROP TABLE evento; " .
                    "\nDROP TABLE evt_arquivo;" .
                    "\nDROP TABLE criterio;" .
                    "\nDROP TABLE status_evento; " .
                    "\nDROP TABLE categoria; " .
                    "\nDROP TABLE tipo_evento; " .
                    "\nDROP TABLE equipe; " .
                    "\nDROP TABLE material_grafico; " .
                    "\nDROP TABLE item_final; " .
                    "\nDROP TABLE tipo_convidado;" .
                    "\nDROP TABLE inscrito_gv; " .
                    "\nDROP TABLE inscrito_ngv; " .
                    "\nDROP TABLE inscrito_pg;" .
                    "\nDROP TABLE evt_tarefa; " .
                    "\nDROP TABLE ferramenta; " .
                    "\nDROP TABLE frm_cst;" .
                    "\nDROP TABLE frm_evt;" .
                    "\nDROP TABLE evt_custo; " .
                    "\nDROP TABLE eqp_agv; " .
                    "\nDROP TABLE evt_mem;" .
                    "\nDROP TABLE evt_for; " .
                    "\nDROP TABLE evt_pat; " .
                    "\nDROP TABLE evt_pal;" .
                    "\nDROP TABLE evt_prf;" .
                    "\nDROP TABLE p_seletivo;" .
                    "\nDROP TABLE dinamica;" .
                    "\nDROP TABLE palestra;" .
                    "\nDROP TABLE acompanha;" .
                    "\nDROP TABLE candidato_din;" .
                    "\nDROP TABLE candidato_psl;" .
                    "\nDROP TABLE abastece;" .
                    "\nDROP TABLE audita;" .
                    "\nDROP TABLE timesheet; " .
                    "\nDROP TABLE ts_atividade; " .
                    "\nDROP TABLE ts_subatividade;" .
                    "\nDROP TABLE prj_interno;" .
                    "\nDROP TABLE tat_tsa;" .
                    "\nDROP TABLE task;" .
                    "\nDROP TABLE tipo_task;" .
                    "\nDROP TABLE status_task;", $retorno );
?>
