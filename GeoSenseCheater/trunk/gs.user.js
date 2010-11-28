// ==UserScript==
// @name GeoSense Cheater
// @description GeoSense Cheater
// @include http://www.geosense.net/*
// @copyright Marcio Ribeiro
// @version 1.0.0
// ==/UserScript==

String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

function addButtons() {
    var div = document.createElement('div');
    var s = div.style;
    s.float = 'left';
    s.top = '10px';
    s.left = '10px';
    s.position = 'absolute';
    s.border = '1px solid black';
    s.backgroundColor = '#fff';
    s.zIndex = 2000;
    s.color = '#fff';

    var b = document.createElement('input');
    b.type = 'button';
    b.value = 'CLICK ME!';
    b.addEventListener('click', hellYeah, true);
    div.appendChild(b);

    document.getElementsByTagName('body')[0].appendChild(div);
}

function createScript(url) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = url;
    return s;
}

function hellYeah() {
    var city = window.frames[0].document.getElementById('divCity');
    var cityName = city.innerHTML;
    var c = cityName.split(', ');
    cityName = c[1] + ', ' + c[0];

    //var cityName = document.getElementById('city').value.capitalize();
    //var cityName = document.getElementById('city').value;
    //document.writeln("<script type='text/javascript'>function go(r){alert(r)}</script>");

    var url = 'http://b1n.googlecode.com/svn/GeoSenseCheater/trunk/go.js';
    var s = createScript(url);
    window.frames[0].document.getElementsByTagName('head')[0].appendChild(s);

    url = 'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&redirects&callback=go&titles=' + cityName.toLowerCase().capitalize();
    s = createScript(url);
    window.frames[0].document.getElementsByTagName('head')[0].appendChild(s);
}

addButtons();
