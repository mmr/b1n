function Color(v1,g,b) {
	if (arguments[2]==null) {
		var h = this.hex = v1
		this.r = parseInt(h.substring(1,3),16)
		this.g = parseInt(h.substring(3,5),16)
		this.b = parseInt(h.substring(5,7),16)
	}
	else {
		this.r = v1
		this.g = g
		this.b = b
		r=v1.toString(16)
		g=g.toString(16)
		b=b.toString(16)
		r=(r.length>1)?r:('0'+r)
		g=(g.length>1)?g:('0'+g)
		b=(b.length>1)?b:('0'+b)
		this.hex=('#'+r+g+b).toUpperCase()
	}
}

Color.prototype.toString=function() {
	return this.hex
}

Color.prototype.stepsTo=function(n,c) {
	steps = new Array()
	steps[0] = this
	steps[n-1] = c

	var rst = (c.r-this.r)/n
	var gst = (c.g-this.g)/n
	var bst = (c.b-this.b)/n
	
	var r=this.r
	var g=this.g
	var b=this.b
	for (var i=1; i<steps.length-1; ++i) {
		r+=rst
		g+=gst
		b+=bst
		steps[i] = new Color(Math.round(r),Math.round(g),Math.round(b))
	}
	return steps
}

var FadeLinksArr = new Array()

function FadeLinks(classe,passos,fadeIn,fadeOut) {
	var cor1 = '#000000'
	var cor2 = '#FFFFFF'
	this.classe = classe
	estilos = document.styleSheets
	for (var i = 0; i < estilos.length; ++i) {
		regras = estilos[i].rules
		for (var j = 0; j < regras.length; ++j) {
			seletor = regras[j].selectorText
			if (seletor.indexOf('A.'+classe) != -1) {
				if (seletor.indexOf(':link') != -1) cor1 = regras[j].style.color
				if (seletor.indexOf(':hover') != -1) cor2 = regras[j].style.color
			}
		}
	}
	this.cor1 = new Color(cor1.toUpperCase())
	this.cor2 = new Color(cor2.toUpperCase())
	this.passos = this.cor1.stepsTo((passos) ? passos : 10,this.cor2)
	this.fadeIn = (fadeIn) ? fadeIn : 10
	this.fadeOut = (fadeOut) ? fadeOut : this.fadeIn
}

function newFadeLinks(classe,passos,fadeIn,fadeOut) {
	return (FadeLinksArr[FadeLinksArr.length] = new FadeLinks(classe,passos,fadeIn,fadeOut))
}

function FadeLinksChangeOver() {
	var tempo = 0
	var start = null
	var steps = this.fl.passos
	for (var i = 0; i < steps.length; i++) {
		if (this.style.color.toUpperCase() == steps[i]) start = i
		if (eval('typeof('+this.id+'_tempo_'+i+'_Out'+')') != 'undefined') clearTimeout(eval(this.id+'_tempo_'+i+'_Out'))
	}
	for (var i = start; i < steps.length; i++) {
		eval(this.id+'_tempo_'+i+'_Over = setTimeout(\''+this.id+'.style.color = "'+steps[i]+'"\','+tempo+')')
		tempo = (tempo+this.fadeIn)*1.2
	}
}

function FadeLinksChangeOut() {
	var tempo = 0
	var start = null
	var steps = this.fl.passos
	for (var i = 0; i < steps.length; i++) {
		if (this.style.color.toUpperCase() == steps[i]) start = i
		if (eval('typeof('+this.id+'_tempo_'+i+'_Over'+')') != 'undefined') clearTimeout(eval(this.id+'_tempo_'+i+'_Over'))
	}
	for (var i = start; i >= 0; i--) {
		eval(this.id+'_tempo_'+i+'_Out = setTimeout(\''+this.id+'.style.color = "'+steps[i]+'"\','+tempo+')')
		tempo = (tempo+this.fadeOut)*1.2
	}
}

function initFadeLinks() {
	for (var i = 0; i < FadeLinksArr.length; i++) {
		linksArr = document.all.tags('A')
		for (var j = 0; j < linksArr.length; j++) {
			if (linksArr[j].className == FadeLinksArr[i].classe) {
				linksArr[j].fl = FadeLinksArr[i]
				linksArr[j].onmouseover = FadeLinksChangeOver
				linksArr[j].onmouseout = FadeLinksChangeOut
				linksArr[j].id = 'FadeLinks_'+i+'_'+j
				linksArr[j].style.color = FadeLinksArr[i].passos[0].toString()
				linksArr[j].fadeIn = FadeLinksArr[i].fadeIn
				linksArr[j].fadeOut = FadeLinksArr[i].fadeOut
			}
		}
	}
}
