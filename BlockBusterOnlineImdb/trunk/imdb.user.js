// ==UserScript==
// @name BlockBusterOnline IMDB
// @description BlockBusterOnline IMDB
// @include http://www.blockbusteronline.com.br/grupo/*
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
    var div = document.createElement('div');
    var s = div.style;
    s.float = 'left';
    s.top = '10px';
    s.left = '10px';
    s.width = '200px';
    s.height = '400px';
    s.fontSize = '12px';
    s.fontFamily = 'arial';
    s.position = 'absolute';
    s.border = '1px solid black';
    s.backgroundColor = '#fff';
    s.zIndex = 2000;
    s.color = '#000';
    s.overflow = 'auto';
    div.id = 'imdb';
    addTo('body', div);

    var up = document.createElement('script');
    up.type = 'text/javascript';
    up.innerHTML = 'var imdb=document.getElementById("imdb");function update(d){imdb.innerHTML+="<font color="+(parseFloat(d.Rating)>=7?"#0f0":"#000")+">"+d.Title+":"+d.Rating+"</font><br/>";}';
    addTo('head',up);

    var ts = document.getElementsByClassName('movieTitle');
    for (var t in ts) {
        var n = ts[t].innerHTML.replace(/^(?:blu-ray|dvd)\s*/i, '').capitalize();
        addTo('head',createScript('http://www.imdbapi.com/?t='+n+'&callback=update'));
    }
}
addRatings();
