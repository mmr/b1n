//pI('/img/xtra_estadao.gif')
//pI('/img/xtra_estadao_animado.gif')

function initDefault(){
	if(is.ie&&is.v>=5){
		newFadeLinks('link-barra',10,10,20)
		newFadeLinks('link-cinza-1',10,5)
		newFadeLinks('link-cinza-2',10,5)
		initFadeLinks()
		fadeLinksLoop()
	}
	if(isDef('init'))init()
}

function fadeLinksLoop(){
	var j=1
	for (var i=0;i<linksArr.length;i++){
		if(linksArr[i].className=='link-barra'){
			setTimeout('linksArr['+i+'].onmouseover()',1+j)
			setTimeout('linksArr['+i+'].onmouseout()',750+j)
			j+=100
		}
	}
	setTimeout('fadeLinksLoop()',20000)
}

function cFormField(obj){ 
    if(obj.value.length==obj.maxLength){ 
        for(var i=0;i<obj.form.length;i++){ 
            if(obj.form[i]==obj){obj.form[i+1].focus();break} 
        } 
    } 
}

onload=initDefault
