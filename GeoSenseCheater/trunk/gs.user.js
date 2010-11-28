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

var MW = 1203;
var MH = 566;
//var PAN_X = -12;
// var PAN_Y = -27;
var PAN_X = -15;
var PAN_Y = 80;

function toDegrees(v) {
    return v * (180 / Math.PI);
}

function toRadians(v) {
    return v * (Math.PI / 180);
}

function P(latd, latm, lats, latNS, lngd, lngm, lngs, lngEW) {
    var deg = parseFloat(latd);
    var min = parseFloat(latm) / 60;
    var sec = parseFloat(lats) / 3600;
    var lat = (deg + min + sec) * (latNS == 'S' ? -1 : 1);

    deg = parseFloat(lngd);
    min = parseFloat(lngm) / 60;
    sec = parseFloat(lngs) / 3600;
    var lng = (deg + min + sec) * (lngEW == 'W' ? -1 : 1);

    var globe_x = lng;
    var globe_y = toDegrees(Math.log(Math.tan(toRadians(lat)) + (1 / Math.cos(toRadians(lat)))));

    this.x = (globe_x + 180) * (MW / 360) + PAN_X;
    this.y = (90 - globe_y) * (MH / 180) + PAN_Y;
}

function addButtons() {
    var el = document.createElement('div');
    with (el.style) {
        float = 'left';
        top = '10px';
        left = '10px';
        position = 'absolute';
        border = '1px solid black';
        backgroundColor = '#fff';
        zIndex = 2000;
        color = '#fff';
    }

    var b = document.createElement('input');
    b.type = 'button';
    b.value = 'CLICK ME!';
    b.addEventListener('click', hellYeah, true);
    el.appendChild(b);

    document.getElementsByTagName('body')[0].appendChild(el);
}

function go(r) {
    var p = r.query.pages;
    for (var pageId in p) {
        if (p.hasOwnProperty(pageId)) {
            c = p[pageId].revisions[0]['*'];
            break;
        }
    }
    var exp = /Coord\|(\d*)\|(\d*)\|?(\d*\.?\d*)?\|([NS])\|(\d*)\|(\d*)\|?(\d*\.?\d*)?\|([WE])/;
    if (m = exp.exec(c)) {
        p = new P(m[1], m[2], m[3] == undefined ? 0 : m[3], m[4], m[5], m[6], m[7] == undefined ? 0 : m[7], m[8]);
        if (typeof StoreGuess == 'function') {
            var e = new Object();
            e.pageX = Math.round(p.x) - 2;
            e.pageY = Math.round(p.y) - 3;
            StoreGuess(e);
        } else {
            alert("debug: x = " + p.x + ", y = " + p.y);
        }
    } else {
        alert('Didnt match pattern :(');
    }
}

function hellYeah() {
    var city = window.frames[0].document.getElementById('divCity');
    var cityName = city.innerHTML;
    var c = cityName.split(', ');
    cityName = c[1] + ', ' + c[0];

    //var cityName = document.getElementById('city').value.capitalize();
    //var cityName = document.getElementById('city').value;
    document.writeln("<script type='text/javascript'>function go(r){alert(r)}</script>");

    var url = 'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&redirects&callback=alert&titles=' + cityName.capitalize();
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = url;
    document.getElementsByTagName('head')[0].appendChild(s);
}

addButtons();
