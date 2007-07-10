<!--

/*
Criado por Infohiway (http://www.infohiway.com) em 1996
Modificado por mmr (mmr@b1n.org)  em 2001
*/

function Caps(objeto)
{
	txt = objeto.value;
	if( txt.length <= 0 )
		return false;

	txt=txt.toLowerCase();
	txt+=" "

	txtl="";
	prep=" de da do e dos das ";

	while ( (txt.length>0)&&(txt.indexOf(" ")>-1) )
	{
		pos=txt.indexOf(" ");
		wrd=txt.substring(0,pos);

		if (prep.indexOf(wrd)<0)
		{
			ltr=wrd.substring(0,1);
			ltr=ltr.toUpperCase();
			wrd=ltr+wrd.substring(1,wrd.length);
		}
		txtl+=wrd+" ";
		txt=txt.substring((pos+1),txt.length);
	}
	ltr=txtl.substring(0,1);
	ltr=ltr.toUpperCase();
	txtl=ltr+txtl.substring(1,txtl.length-1);

	objeto.value = txtl;
}

/* Checa se é um número */
function ChecaNum(objeto)
{
	txt = objeto.value;

	for (i=0 ; i<txt.length ; i++)
	{
		cmp="0123456789.,"
		tst=txt.charAt(i);
		if (cmp.indexOf(tst)<0)
		{
			alert("Apenas números, pontos e vírgulas são aceitos nesse campo.");
			objeto.focus();
			objeto.select();
			return false;
		}
	}
}

function troca(velho,novo,entrada)
{
	var saida="";
	var i=0;
    
	for(i=0 ; i < entrada.length ; i++)
	{
		if( entrada.charAt(i) == velho )
		{
			if( entrada.charAt(i+1) != velho )
				saida += novo;
		}
		else
			saida += entrada.charAt(i);
	}

	if( saida.charAt(0) == novo )
		saida = saida.substring(1,saida.length);
	if( saida.charAt(saida.length-1) == novo )
		saida = saida.substring(0,saida.length-1);

	return saida;
}   

function mmr_trim(str) {
	return troca(' ',' ',str);
}


/* Verifica por campos obrigatorios vazios */
function Verifica(form, campos)
{
	aux = new String(campos);
	aux = aux.split(",");

	for(i=0; i<aux.length; i++)
	{
		if(eval("mmr_trim(form." + aux[i] + ".value).length") <= 0)
		{
			alert("Os campos com * ao lado, são de preenchimento obrigatório");
			return false;
		}
	}

	return true;
}

/* Confirmacao de exclusao */
function Confirma()
{
	if(confirm("Tem certeza que deseja excluir definitivamente esse(s) cadastro(s) ?"))
		return true;
	else
		return false;
}

/* checa se senha eh igual a confirmacao */
function ChecaSenha(form)
{
	if(form.usr_senha.value != form.usr_senha2.value)
	{
		alert("Senha e Confirmação são diferentes");
		form.usr_senha.value  = "";
		form.usr_senha2.value = "";
		form.usr_senha.focus();
		return false;
	}
	return true;
}

/* Checa/Descheca todos os checkboxes quando clicado no botao 'chear todos' */
function ChecarTodos(form,botao)
{
	form = eval(form);
	
	/* 4 sao os campos hiddens mais os botoes (sn_acao, sn_inc, botao_excluir e todos ) */
	for(i=0; i<form.elements.length - 4; i++)
	{
		j = form.elements[i];
		
		if(form.todos.checked)
		{
			if(!j.checked)
			{
				j.checked = true;
			}
		}
		else
		{
			j.checked = false;	
		}
	}
	BotaoExcluir(form,botao);
}

/* Habilita o botao de excluir se algum check for selecionado */
function BotaoExcluir(form,botao)
{

	form  = eval(form);
	botao = eval(botao);

	/* 4 sao os campos hiddens mais os botoes (sn_acao, sn_inc, botao_excluir e todos ) */
	for(i=0; i<form.elements.length - 4; i++)
	{
		if(form.elements[i].checked)
		{
			botao.disabled = false;
			return true;
		}
	}
	botao.disabled = true;
}

//-->
