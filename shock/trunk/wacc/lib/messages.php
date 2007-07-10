<?
#BOTOES
define (BOTAO_OK ,"OK");
define (BOTAO_CANCELAR ,"Cancelar");
define (BOTAO_RESTAURAR ,"Restaurar");
define (BOTAO_VOLTAR ,"Voltar");

#Textos

define (LABEL_TEXTO_VPN,"O servi�o de VPN permite que duas redes se interliguem criando uma rede WAN.");
define(LABEL_FIREWALL_ALERT_VPN, "<font color='#ff0000'>ATEN��O!</font> A configura��o do firewall pode interferir com o funcionamento deste servi�o. Altere as configura��es de firewall caso necess�rio");
# show_pubkey.php

define (LABEL_CHAVE_PUBLICA_SHOW_PUBKEY,"Chave p�blica RSA");
define (HINT_CHAVE_PUBLICA_SHOW_PUBKEY," Chave p�blica de criptografia");

# list_conns.php

define (LABEL_NENHUMA_CONEXAO_LIST_CONNS,"Nenhuma conex�o criada");
define (HINT_NENHUMA_CONEXAO_LIST_CONNS,"� necessario adicionar conex�es");

define (LABEL_NOME_CONEXAO_LIST_CONNS,"Nome conex�o");
define (HINT_NOME_CONEXAO_LIST_CONNS,"Nome descritivo desta conex�o");

define (LABEL_STATUS_LIST_CONNS ," Status");
define (HINT_STATUS_LIST_CONNS ," Conex�o ativa ou n�o");

define (LABEL_CONFIGURACAO_LIST_CONNS ," Configura��o");
define (HINT_CONFIGURACAO_LIST_CONNS ," Alterar dados da conex�o VPN");

define (LABEL_EXCLUSAO_LIST_CONNS ," Exclus�o");
define (HINT_EXCLUSAO_LIST_CONNS ," Excluir esta conex�o VPN");


define (LINK_EDITAR_LIST_CONNS ," Editar");
define (HINT_EDITAR_LIST_CONNS ," Alterar dados da conex�o VPN");

define (LINK_EXCLUIR_LIST_CONNS ," Excluir");
define (HINT_EXCLUIR_LIST_CONNS ,"Excluir esta conex�o VPN");

define (LINK_ADICIONAR_CONEXAO_LIST_CONNS ," Adicionar conex�o");
define (HINT_ADICIONAR_CONEXAO_LIST_CONNS ," Adicionar uma nova conex�o VPN");

define (LINK_VISUALIZAR_CHAVE_PUBLICA_LIST_CONNS ,"Visualizar chave p�blica");
define (HINT_VISUALIZAR_CHAVE_PUBLICA_LIST_CONNS ,"Visualizar chave p�blica de criptografia");

# form_serv_onoff

define (LABEL_VPN_FORM_SERV_ONOFF ,"VPN");
define (HINT_VPN_FORM_SERV_ONOFF ,"Ligar ou desligar servi�o da rede privada virtual"); # ***Rever***

define (LABEL_STATUS_FORM_SERV_ONOFF ,"Status");
define (HINT_STATUS_FORM_SERV_ONOFF ,""); # ***Rever***

define (LABEL_LIGADO_FORM_SERV_ONOFF ,"Ligado");
define (HINT_LIGADO_FORM_SERV_ONOFF ,"Ligar servi�o de VPN");

define (LABEL_DESLIGADO_FORM_SERV_ONOFF ,"Desligado");
define (HINT_DESLIGADO_FORM_SERV_ONOFF ,"Desligar servi�o de VPN");

# form_del_conn

define (LABEL_CONFIRMA_DEL_CONEXAO_FORM_DEL_CONN ,"Confirma deletar conex�o VPN: ");
define (HINT_CONFIRMA_DEL_CONEXAO_FORM_DEL_CONN ,"Confirma deletar conex�o VPN");

# form_add

define (LABEL_ADICIONANDO_CONEXAO_VPN_FORM_ADD ,"Adicionando uma conex�o VPN");
define (HINT_ADICIONANDO_CONEXAO_VPN_FORM_ADD ,"");

define (LABEL_VPN_FORM_ADD ,"VPN");
define (HINT_VPN_FORM_ADD ,"Rede privada virtual");

define (LABEL_DESCRICAO_FORM_ADD ,"Nome da conex�o");
define (HINT_DESCRICAO_FORM_ADD ,"Nome da conex�o a ser criada");

define (LABEL_HELP_CONN_NAME_FORM_ADD, "Caracteres validos: letras, n�meros e '-' (sem espa�os)");

define (LABEL_SERVIDOR_REMOTO_FORM_ADD ,"IP da maquina a ser conectada");
define (HINT_SERVIDOR_REMOTO_FORM_ADD ,"IP externo do servidor remoto");

define (LABEL_REDE_REMOTA_FORM_ADD ,"Endere�o IP da rede interna da maquina a ser conectada");
define (HINT_REDE_REMOTA_FORM_ADD ,"Rede interna remota");

define (LABEL_MASCARA_REDE_REMOTA_FORM_ADD ,"Mascara de rede remota");
define (HINT_MASCARA_REDE_REMOTA_FORM_ADD ,"Mascara de rede remota");

define (LABEL_CHAVE_PUBLICA_REMOTA_FORM_ADD ,"Chave p�blica remota *");
define (HINT_CHAVE_PUBLICA_REMOTA_FORM_ADD ,"Chave p�blica do servidor remoto");

define (LABEL_ATIVAR_CONEXAO_FORM_ADD ,"Ativar conex�o");
define (HINT_ATIVAR_CONEXAO_FORM_ADD ,"Ativar esta conex�o");

define (LABEL_EXPLICATIVO_CHAVE_PUBLICA_FORM_ADD,"* Voc� deve colar a chave publica do servidor a ser conectado. N�o esque�a tamb�m de ir � pagina visualizar chave p�blica e enviar a sua chave para o administrador da outra rede");

#Mensagens de ERRO:

$error_msg = create_error_msg(array(ERR_GENERIC    => "Falha de sistema",
				    ERR_START      => "Erro na inicializa��o do servi�o",
				    ERR_STOP       => "Erro na parando servi�o",
				    ERR_PARAM      => "Comando inv�lido",
				    ERR_NUM_PARAMS => "N�mero de parametross inv�lido",

				    ERR_RESTORE_BACKUP   => "Erro restaurando configura��es",
				    ERR_INVALID_STATUS   => "Status inv�lido",
				    ERR_ADD_CONN         => "Erro adicionando conex�o",
				    ERR_CONN_AEXIST      => "Uma conex�o com este nome j� existe",
				    ERR_EDIT_CONN        => "Erro alterando dados da conex�o",
				    ERR_CONN_NOTFOUND    => "Nao encontrou conex�o com este nome",
				    ERR_DEL_CONN         => "Erro removendo conex�o",
				    ERR_GEN_KEYS         => "Erro gerando chaves para criptografia",
				    ERR_INIT_CONN        => "Erro inicializando conex�o",
				    ERR_STARTING_CONN    => "Erro iniciando conex�o",
				    ERR_STOPING_CONN     => "Erro parando conex�o",

				    ERR_LOAD_XML         => "Falha na leitura de configura��es",
				    ERR_SAVE_XML         => "Falha na grava��o de configura��es",
				    ERR_SAVE_XML         => "Erro na inicializa��o do servi�o",
				    ERR_INVALID_STATUS      => "Status inv�lido",
				    ERR_SERVICE_OFF         => "Servi�o vpn est� desligado",
				    ERR_NULL_CONN_NAME      => "Nome da conex�o n�o foi preenchido",
				    ERR_INVALID_CONN_NAME   => "Nome da conex�o inv�lido",
				    ERR_NULL_CONN_ADDR      => "IP da m�quina a ser conectada n�o foi preenchido ",
				    ERR_INVALID_CONN_ADDR   => "IP da m�quina a ser conectada esta inv�lido",
				    ERR_NULL_CONN_STATUS    => "Status v�zio ",
				    ERR_INVALID_CONN_STATUS => "Status inv�lido",
				    ERR_NULL_CONN_NET       => "Endere�o IP da rede interna da maquina a ser conectada n�o foi preenchido",
				    ERR_INVALID_CONN_NET    => "Endere�o IP da rede interna da maquina a ser conectada inv�lido",
				    ERR_NULL_CONN_MASK      => "M�scara de rede remota n�o foi preenchido",
				    ERR_INVALID_CONN_MASK   => "M�scara de rede remota inv�lido",
				    ERR_NULL_CONN_RSAKEY    => "Chave p�blica n�o foi preenchida",
				    ERR_INVALID_CONN_RSAKEY => "Chave p�blica inv�lida" ));

#Mensagens de Ok:



$success_msg = array(MSG_RESTART_OK          => "Servi�o reinicializado.",
		     MSG_SERV_STATUS_ALTERED => "Status do servi�o alterado com sucesso.",
		     MSG_CONN_STATUS_ALTERED => "Status da conex�o alterado com sucesso.",
		     MSG_GEN_KEYS_OK         => "Chaves geradas com sucesso.",
		     MSG_CONN_ADDED_OK       => "Conex�o adicionada com sucesso.",
		     MSG_CONN_EDITED_OK      => "Conex�o alterada com sucesso.",
		     MSG_CONN_DELETED_OK     => "Conex�o removida com sucesso.",
		     MSG_CONN_EDITED_OK      => "Gerando chaves p�blicas, aguarde.");

define(MSG_CADASTRO_SUC,"Cadastro conclu�do com sucesso");
define(MSG_EDITA_SUC,	"Editando cadastro...");
define(MSG_ALTERA_SUC,	"Alterado com sucesso");
define(MSG_EXCLUI_SUC,	"Exclus�o conclu�da com sucesso");
define(MSG_CADASTRO_ERR,"Erro: Erro durante o cadastro");
define(MSG_EDITA_ERR,	"Erro: Erro durante a edi��o do cadastro, preencha todos os campos");
define(MSG_ALTERA_ERR,	"Erro: Erro durante a altera��o do cadastro, verifique se j� n�o existem registros com esse nome");
define(MSG_EXCLUI_ERR,	"Erro: Verifique se esse cadastro n�o possui depend�ncias");
define(MSG_CADASTRO_ERR_SENHA,	"Erro: A senha e a confirma��o diferem");
define(MSG_CADASTRO_ERR_CAMPO,	"Erro: Os campos com '*' ao lado, s�o de preenchimento obrigat�rio");
define(CNS_CAD_SUC,"Cadastrando produto");
define(CNS_CAD_ERR,"Erro: Erro ao cadastrar consumo");
define(CNS_LST_SUC,"Listando consumos...");
define(CNS_LST_ERR,"N�o h� consumos pendentes");
define(CNS_LST_JOG_ERR,"N�o h� jogos pendentes");
define(CNS_BAX_SUC,"Baixa em ");
define(CNS_BAX_ERR,"Nada � ser pago por ");
define(CNS_PAG_CNS_ERR,"Erro: Erro ao pagar consumo para ");
define(CNS_PAG_JOG_ERR,"Erro: Erro ao pagar tempo jogo para ");
define(CNS_PAG_SUC,"Pago com sucesso para ");
define(CNS_LST_JOG_SUC,"Listando jogos...");

define(MSG_DESC_SN,"Permite o controle do Consumo e Tempo de Jogo dos usu�rios");

define(MSG_CFG_LST_SUC,"Listando configura��es...");
define(MSG_CFG_ERR,"Erro: Erro ao tentar listar as configura��es.");
define(MSG_CFG_PPU_ERR,"Erro: Erro ao alterar o Pre�o por Unidade de Tempo.");
define(MSG_CFG_TUN_ERR,"Erro: Erro ao alterar a Unidade de Tempo .");
define(MSG_CFG_TOL_ERR,"Erro: Erro ao alterar a Toler�ncia.");
define(MSG_CFG_SUC,"Alterado com sucesso");

define(CNS_MSG_ONLINE,"Conectado");
define(CNS_MSG_OFFLINE,"Encerrado");
define(CNS_MSG_DOWN,"ALERTA");

?>

