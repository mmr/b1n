// DynClick - Menu
// Copyright 2001 AgênciaClick
// http://www.agenciaclick.com.br
// Versão 1.0 - 08/12/2001


DMarr=new Array()
function newDM(tit,act,tit2,act2){return(DMarr[DMarr.length]=new DM(tit,act,tit2,act2))}
function DM(tit,act,tit2,act2){this.id=toId(tit);this.tit=tit;this.act=act;this.tit2=tit2;this.act2=act2;this.itens=new Array();this.addItem=newDMitem}
function newDMitem(tit,act){return(this.itens[this.itens.length]=new DMitem(tit,act))}
function DMitem(tit,act){this.id=toId(tit);this.tit=tit;this.act=act}
function getDM(tit){for(var i=0;i<DMarr.length;i++)if(DMarr[i].tit==tit)return DMarr[i]}
