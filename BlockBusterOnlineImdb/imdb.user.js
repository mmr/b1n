// ==UserScript==
// @name BlockBusterOnline IMDB
// @description BlockBusterOnline IMDB
// @include http://www.blockbusteronline.com.br/grupo/*
// @include https://carrinho.blockbusteronline.com.br/locaonline/*
// @copyright Marcio Ribeiro
// @version 1.0.0
// ==/UserScript==

String.prototype.capitalize = function(){
    return this.toLowerCase().replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

function createScript(url) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = url;
    return s;
}

function addTo(tag,el) {
    document.getElementsByTagName(tag)[0].appendChild(el);
}

function addRatings() {
	var buf = 'var G={};function U(t){this.t=t;this.u=function(d){document.getElementById(G[this.t]).innerHTML+=(typeof d.Rating=="undefined")?"(-)":" ("+d.Title+":"+d.Year+":<font color="+(parseFloat(d.Rating)>=7?"#00ff00":"#000")+">"+d.Rating+"</font>)";}}';
	var i = 0;
    var ts = document.getElementsByClassName('movieTitle');
    for (var t in ts) {
	    var o = ts[t];
        var n = o.innerHTML.replace(/^(?:blu-ray|dvd)\s*/i, '').capitalize();
		o.innerHTML = n;
		o.id = '_t' + i;
		buf += "G['" + n + "'] = '" + o.id + "';";
		i++;
    }
    var up = document.createElement('script');
    up.type = 'text/javascript';
	up.innerHTML = buf;
	addTo('head', up);

	for (var t in ts) {
	    var o = ts[t];
        var n = o.innerHTML;
        addTo('head', createScript('http://www.imdbapi.com/?t='+n+'&callback=new U("'+n+'").u'));
    }
}
addRatings();
