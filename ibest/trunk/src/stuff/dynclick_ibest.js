// DynClick - Customized to iBest - Home
// Copyright 2001 AgênciaClick
// http://www.agenciaclick.com.br
// Versão 1.0 - 31/10/2001


d=document


// Basics

function isDef(S){return(eval('typeof('+S+')')!='undefined'&&eval('typeof('+S+')')!='unknown')}

function toId(S){
	S=S.toLowerCase()
	S=S.replace(/[áàãâ]/g,'a')
	S=S.replace(/[éèêë&]/g,'e')
	S=S.replace(/[íìîï]/g,'i')
	S=S.replace(/[óòõôö]/g,'o')
	S=S.replace(/[úùûü]/g,'u')
	S=S.replace(/[ç]/g,'c')
	S=S.replace(/[ \.:,\+\-\*\'\"\\\/<>?=]/g,'')
	return S
}

function checkBrowser(){
	T=this
	b=navigator.appName
	v=navigator.appVersion
	u=navigator.userAgent
	if(b=='Netscape')T.b='ns'
	else if(b=='Microsoft Internet Explorer')T.b='ie'
	else T.b=b
	T.v=parseInt(v)
	T.ns=(T.b=='ns'&&T.v>=4)
	T.ns4=(T.b=='ns'&&T.v==4)
	T.ns5=(T.b=='ns'&&T.v==5)
	T.ns6=(T.b=='ns'&&T.v==5)
	T.ie=(T.b=='ie'&&T.v>=4)
	T.ie4=(u.indexOf('MSIE 4')>0)
	T.ie5=(u.indexOf('MSIE 5.0')>0)
	T.ie55=(u.indexOf('MSIE 5.5')>0)
	T.ie6=(u.indexOf('MSIE 6.0')>0)
	if(T.ie5)T.v=5
	if(T.ie55)T.v=5.5
	if(T.ie6)T.v=6
	T.min=(T.ns||T.ie)
	T.dom=(T.v>=5)
	T.win=(u.indexOf('Win')>0)
	T.mac=(u.indexOf('Mac')>0)
}
is=new checkBrowser()

function openPopup(url,w,h,other){
	url=url.replace(/[ ]/g,'%20')
	other=','+other||''
	popup=window.open(url,'popup_'+toId(url),'left=18,top=18,width='+w+',height='+h+',scrollbars=1'+other)
	if(is.ie&&other.indexOf('fullscreen')!=-1){popup.moveTo(0,0);popup.resizeTo(screen.width,screen.height)}
	popup.focus()
}

function openBlank(url){if(url){window.open(url)}else{return false}}


// Images

function pI(src){
	obj=src.substring(src.lastIndexOf('/')+1,src.lastIndexOf('.'))
	eval(obj+'=new Image()')
	eval(obj+'.src="'+src+'"')
}

function cI(id,obj,lyr){
	id=(d.layers&&lyr)?'d.layers.'+lyr+'.document.images.'+id:'d.images[\''+id+'\']'
	if(isDef(id)&&isDef(obj))eval(id).src=eval(obj).src
}
