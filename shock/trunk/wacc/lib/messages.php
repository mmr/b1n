<?
#BOTOES
define (BOTAO_OK ,"OK");
define (BOTAO_CANCELAR ,"Cancelar");
define (BOTAO_RESTAURAR ,"Restaurar");
define (BOTAO_VOLTAR ,"Voltar");

#Textos

define (LABEL_TEXTO_VPN,"O serviço de VPN permite que duas redes se interliguem criando uma rede WAN.");
define(LABEL_FIREWALL_ALERT_VPN, "<font color='#ff0000'>ATENÇÃO!</font> A configuração do firewall pode interferir com o funcionamento deste serviço. Altere as configurações de firewall caso necessário");
# show_pubkey.php

define (LABEL_CHAVE_PUBLICA_SHOW_PUBKEY,"Chave pública RSA");
define (HINT_CHAVE_PUBLICA_SHOW_PUBKEY," Chave pública de criptografia");

# list_conns.php

define (LABEL_NENHUMA_CONEXAO_LIST_CONNS,"Nenhuma conexão criada");
define (HINT_NENHUMA_CONEXAO_LIST_CONNS,"É necessario adicionar conexões");

define (LABEL_NOME_CONEXAO_LIST_CONNS,"Nome conexão");
define (HINT_NOME_CONEXAO_LIST_CONNS,"Nome descritivo desta conexão");

define (LABEL_STATUS_LIST_CONNS ," Status");
define (HINT_STATUS_LIST_CONNS ," Conexão ativa ou não");

define (LABEL_CONFIGURACAO_LIST_CONNS ," Configuração");
define (HINT_CONFIGURACAO_LIST_CONNS ," Alterar dados da conexão VPN");

define (LABEL_EXCLUSAO_LIST_CONNS ," Exclusão");
define (HINT_EXCLUSAO_LIST_CONNS ," Excluir esta conexão VPN");


define (LINK_EDITAR_LIST_CONNS ," Editar");
define (HINT_EDITAR_LIST_CONNS ," Alterar dados da conexão VPN");

define (LINK_EXCLUIR_LIST_CONNS ," Excluir");
define (HINT_EXCLUIR_LIST_CONNS ,"Excluir esta conexão VPN");

define (LINK_ADICIONAR_CONEXAO_LIST_CONNS ," Adicionar conexão");
define (HINT_ADICIONAR_CONEXAO_LIST_CONNS ," Adicionar uma nova conexão VPN");

define (LINK_VISUALIZAR_CHAVE_PUBLICA_LIST_CONNS ,"Visualizar chave pública");
define (HINT_VISUALIZAR_CHAVE_PUBLICA_LIST_CONNS ,"Visualizar chave pública de criptografia");

# form_serv_onoff

define (LABEL_VPN_FORM_SERV_ONOFF ,"VPN");
define (HINT_VPN_FORM_SERV_ONOFF ,"Ligar ou desligar serviço da rede privada virtual"); # ***Rever***

define (LABEL_STATUS_FORM_SERV_ONOFF ,"Status");
define (HINT_STATUS_FORM_SERV_ONOFF ,""); # ***Rever***

define (LABEL_LIGADO_FORM_SERV_ONOFF ,"Ligado");
define (HINT_LIGADO_FORM_SERV_ONOFF ,"Ligar serviço de VPN");

define (LABEL_DESLIGADO_FORM_SERV_ONOFF ,"Desligado");
define (HINT_DESLIGADO_FORM_SERV_ONOFF ,"Desligar serviço de VPN");

# form_del_conn

define (LABEL_CONFIRMA_DEL_CONEXAO_FORM_DEL_CONN ,"Confirma deletar conexão VPN: ");
define (HINT_CONFIRMA_DEL_CONEXAO_FORM_DEL_CONN ,"Confirma deletar conexão VPN");

# form_add

define (LABEL_ADICIONANDO_CONEXAO_VPN_FORM_ADD ,"Adicionando uma conexão VPN");
define (HINT_ADICIONANDO_CONEXAO_VPN_FORM_ADD ,"");

define (LABEL_VPN_FORM_ADD ,"VPN");
define (HINT_VPN_FORM_ADD ,"Rede privada virtual");

define (LABEL_DESCRICAO_FORM_ADD ,"Nome da conexão");
define (HINT_DESCRICAO_FORM_ADD ,"Nome da conexão a ser criada");

define (LABEL_HELP_CONN_NAME_FORM_ADD, "Caracteres validos: letras, números e '-' (sem espaços)");

define (LABEL_SERVIDOR_REMOTO_FORM_ADD ,"IP da maquina a ser conectada");
define (HINT_SERVIDOR_REMOTO_FORM_ADD ,"IP externo do servidor remoto");

define (LABEL_REDE_REMOTA_FORM_ADD ,"Endereço IP da rede interna da maquina a ser conectada");
define (HINT_REDE_REMOTA_FORM_ADD ,"Rede interna remota");

define (LABEL_MASCARA_REDE_REMOTA_FORM_ADD ,"Mascara de rede remota");
define (HINT_MASCARA_REDE_REMOTA_FORM_ADD ,"Mascara de rede remota");

define (LABEL_CHAVE_PUBLICA_REMOTA_FORM_ADD ,"Chave pública remota *");
define (HINT_CHAVE_PUBLICA_REMOTA_FORM_ADD ,"Chave pública do servidor remoto");

define (LABEL_ATIVAR_CONEXAO_FORM_ADD ,"Ativar conexão");
define (HINT_ATIVAR_CONEXAO_FORM_ADD ,"Ativar esta conexão");

define (LABEL_EXPLICATIVO_CHAVE_PUBLICA_FORM_ADD,"* Você deve colar a chave publica do servidor a ser conectado. Não esqueça também de ir à pagina visualizar chave pública e enviar a sua chave para o administrador da outra rede");

#Mensagens de ERRO:

$error_msg = create_error_msg(array(ERR_GENERIC    => "Falha de sistema",
				    ERR_START      => "Erro na inicialização do serviço",
				    ERR_STOP       => "Erro na parando serviço",
				    ERR_PARAM      => "Comando inválido",
				    ERR_NUM_PARAMS => "Número de parametross inválido",

				    ERR_RESTORE_BACKUP   => "Erro restaurando configurações",
				    ERR_INVALID_STATUS   => "Status inválido",
				    ERR_ADD_CONN         => "Erro adicionando conexão",
				    ERR_CONN_AEXIST      => "Uma conexão com este nome já existe",
				    ERR_EDIT_CONN        => "Erro alterando dados da conexão",
				    ERR_CONN_NOTFOUND    => "Nao encontrou conexão com este nome",
				    ERR_DEL_CONN         => "Erro removendo conexão",
				    ERR_GEN_KEYS         => "Erro gerando chaves para criptografia",
				    ERR_INIT_CONN        => "Erro inicializando conexão",
				    ERR_STARTING_CONN    => "Erro iniciando conexão",
				    ERR_STOPING_CONN     => "Erro parando conexão",

				    ERR_LOAD_XML         => "Falha na leitura de configurações",
				    ERR_SAVE_XML         => "Falha na gravação de configurações",
				    ERR_SAVE_XML         => "Erro na inicialização do serviço",
				    ERR_INVALID_STATUS      => "Status inválido",
				    ERR_SERVICE_OFF         => "Serviço vpn está desligado",
				    ERR_NULL_CONN_NAME      => "Nome da conexão não foi preenchido",
				    ERR_INVALID_CONN_NAME   => "Nome da conexão inválido",
				    ERR_NULL_CONN_ADDR      => "IP da máquina a ser conectada não foi preenchido ",
				    ERR_INVALID_CONN_ADDR   => "IP da máquina a ser conectada esta inválido",
				    ERR_NULL_CONN_STATUS    => "Status vázio ",
				    ERR_INVALID_CONN_STATUS => "Status inválido",
				    ERR_NULL_CONN_NET       => "Endereço IP da rede interna da maquina a ser conectada não foi preenchido",
				    ERR_INVALID_CONN_NET    => "Endereço IP da rede interna da maquina a ser conectada inválido",
				    ERR_NULL_CONN_MASK      => "Máscara de rede remota não foi preenchido",
				    ERR_INVALID_CONN_MASK   => "Máscara de rede remota inválido",
				    ERR_NULL_CONN_RSAKEY    => "Chave pública não foi preenchida",
				    ERR_INVALID_CONN_RSAKEY => "Chave pública inválida" ));

#Mensagens de Ok:



$success_msg = array(MSG_RESTART_OK          => "Serviço reinicializado.",
		     MSG_SERV_STATUS_ALTERED => "Status do serviço alterado com sucesso.",
		     MSG_CONN_STATUS_ALTERED => "Status da conexão alterado com sucesso.",
		     MSG_GEN_KEYS_OK         => "Chaves geradas com sucesso.",
		     MSG_CONN_ADDED_OK       => "Conexão adicionada com sucesso.",
		     MSG_CONN_EDITED_OK      => "Conexão alterada com sucesso.",
		     MSG_CONN_DELETED_OK     => "Conexão removida com sucesso.",
		     MSG_CONN_EDITED_OK      => "Gerando chaves públicas, aguarde.");

define(MSG_CADASTRO_SUC,"Cadastro concluído com sucesso");
define(MSG_EDITA_SUC,	"Editando cadastro...");
define(MSG_ALTERA_SUC,	"Alterado com sucesso");
define(MSG_EXCLUI_SUC,	"Exclusão concluída com sucesso");
define(MSG_CADASTRO_ERR,"Erro: Erro durante o cadastro");
define(MSG_EDITA_ERR,	"Erro: Erro durante a edição do cadastro, preencha todos os campos");
define(MSG_ALTERA_ERR,	"Erro: Erro durante a alteração do cadastro, verifique se já não existem registros com esse nome");
define(MSG_EXCLUI_ERR,	"Erro: Verifique se esse cadastro não possui dependências");
define(MSG_CADASTRO_ERR_SENHA,	"Erro: A senha e a confirmação diferem");
define(MSG_CADASTRO_ERR_CAMPO,	"Erro: Os campos com '*' ao lado, são de preenchimento obrigatório");
define(CNS_CAD_SUC,"Cadastrando produto");
define(CNS_CAD_ERR,"Erro: Erro ao cadastrar consumo");
define(CNS_LST_SUC,"Listando consumos...");
define(CNS_LST_ERR,"Não há consumos pendentes");
define(CNS_LST_JOG_ERR,"Não há jogos pendentes");
define(CNS_BAX_SUC,"Baixa em ");
define(CNS_BAX_ERR,"Nada à ser pago por ");
define(CNS_PAG_CNS_ERR,"Erro: Erro ao pagar consumo para ");
define(CNS_PAG_JOG_ERR,"Erro: Erro ao pagar tempo jogo para ");
define(CNS_PAG_SUC,"Pago com sucesso para ");
define(CNS_LST_JOG_SUC,"Listando jogos...");

define(MSG_DESC_SN,"Permite o controle do Consumo e Tempo de Jogo dos usuários");

define(MSG_CFG_LST_SUC,"Listando configurações...");
define(MSG_CFG_ERR,"Erro: Erro ao tentar listar as configurações.");
define(MSG_CFG_PPU_ERR,"Erro: Erro ao alterar o Preço por Unidade de Tempo.");
define(MSG_CFG_TUN_ERR,"Erro: Erro ao alterar a Unidade de Tempo .");
define(MSG_CFG_TOL_ERR,"Erro: Erro ao alterar a Tolerância.");
define(MSG_CFG_SUC,"Alterado com sucesso");

define(CNS_MSG_ONLINE,"Conectado");
define(CNS_MSG_OFFLINE,"Encerrado");
define(CNS_MSG_DOWN,"ALERTA");

?>

