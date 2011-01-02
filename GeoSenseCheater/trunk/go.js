/**
 * @author Marcio Ribeiro (mmr)
 */
//var MW = parseInt(document.getElementsByTagName('body')[0].style.width.replace('px',''));
var MW = 2048;
var MH = 1502;
//var PAN_X = -12;
// var PAN_Y = -27;
var PAN_X = 0;
var PAN_Y = 0;

function toDegrees(v) {
    return v * (180 / Math.PI);
}

function toRadians(v) {
    return v * (Math.PI / 180);
}

function P(lat, lng) {
    // Equilateral
    //var globe_x = lng;
    //var globe_y = lat;

    // Mercator
    //var globe_x = lng;
    //var globe_y = toDegrees(Math.log(Math.tan(toRadians(lat)) + (1 / Math.cos(toRadians(lat)))));

    // Miller
    log("lat : " + lat + " / lng : " + lng);
    var globe_x = lng;

    var lat_radians = toRadians(lat);
    log('lat em radians - ' + lat_radians);
    var doisquintos = 2.0/5.0 * lat_radians;
    log('dois quintos - ' + doisquintos);
    var piquartomais = (Math.PI/4.0) + doisquintos;
    log('piquarto - ' + piquartomais);
    var _tan = Math.tan(piquartomais);
    log('tan - ' + _tan);
    var _log = Math.log(_tan);
    log('log - ' + _log);
    var cincoquartos = 5.0/4.0 * _log; 
    log('5/4 - ' + cincoquartos);
    var emgraus = toDegrees(cincoquartos);
    log('emgraus - ' + emgraus);
    var globe_y = emgraus;
    log("globe_x : " + globe_x + " / globe_y : " + globe_y);
    //var globe_y = lat;

    this.x = (globe_x + 180) * (MW / 360) + PAN_X;
    this.y = (90 - globe_y) * (MH / 180) + PAN_Y;
    log("x : " + this.x + " / y : " + this.y);
}

/*
function P(latd, latm, lats, latNS, lngd, lngm, lngs, lngEW) {
    var deg = parseFloat(latd);
    var min = parseFloat(latm) / 60;
    var sec = parseFloat(lats) / 3600;
    var lat = (deg + min + sec) * (latNS == 'S' ? -1 : 1);

    deg = parseFloat(lngd);
    min = parseFloat(lngm) / 60;
    sec = parseFloat(lngs) / 3600;
    var lng = (deg + min + sec) * (lngEW == 'W' ? -1 : 1);

    //var globe_y = toDegrees(Math.log(Math.tan(toRadians(lat)) + (1 / Math.cos(toRadians(lat)))));
    var globe_x = lng;
    //var globe_y = lat;
    var globe_y = toDegrees(5.0/4.0 * Math.log(Math.tan((Math.PI/4.0) + (2.0/5.0 * toRadians(lat)))));

    this.x = (globe_x + 180) * (MW / 360) + PAN_X;
    this.y = (90 - globe_y) * (MH / 180) + PAN_Y;
}
*/

function createScript(url) {
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = url;
    return s;
}

function dump(arr,level) {
    var dumped_text = "";
    if(!level) level = 0;
    //The padding given at the beginning of the line.
    var level_padding = "";
    for(var j=0;j<level+1;j++) level_padding += "    ";
    if(typeof(arr) == 'object') { //Array/Hashes/Objects 
        for(var item in arr) {
            var value = arr[item];
            if(typeof(value) == 'object') { //If it is an array,
                dumped_text += level_padding + "'" + item + "' ...\n";
                dumped_text += dump(value,level+1);
            } else {
                dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
            }
        }
    } else { //Stings/Chars/Numbers etc.
        dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
    }
    return dumped_text;
}

function hellYeah() {
    document.getElementById('txt').value = '';
    var city = document.getElementById('city');
/*
    var city = window.frames[0].document.getElementById('divCity');
    var cityName = city.innerHTML;
    var c = cityName.split(', ');
    cityName = c[1] + ', ' + c[0];
    */

    //var cityName = city.options[city.selectedIndex].value;
    var cityName = city.value;
    //var cityName = document.getElementById('city').value.capitalize();
    //var cityName = document.getElementById('city').value;
    //document.writeln("<script type='text/javascript'>function go(r){alert(r)}</script>");

/*
    var url = 'http://b1n.googlecode.com/svn/GeoSenseCheater/trunk/go.js';
    var s = createScript(url);
    document.getElementsByTagName('head')[0].appendChild(s);
*/
    //url = 'http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=json&redirects&callback=go&titles=' + cityName.toLowerCase().capitalize();
    url = 'http://ajax.googleapis.com/ajax/services/search/local?v=3.0&callback=go&q=' + cityName.toLowerCase().capitalize();
    log(url);
    s = createScript(url);
    document.getElementsByTagName('head')[0].appendChild(s);
}

function log(m) {
    document.getElementById('txt').value += m + "\n";
}

function criaPonto(x,y,c) {
    var d = document.createElement('div');
    with (d.style) {
        backgroundColor=c;
        border='2px solid white';
        position = 'absolute';
        width= '5px'; 
        height= '5px';
        top=y + 'px';
        left=x + 'px';
    }
    document.getElementsByTagName('body')[0].appendChild(d);
}

function go(r) {
    if (r.responseStatus != 200) {
        alert('Not found! :(');
        return;
    }
    var c = r.responseData.viewport.center;
    var p = new P(parseFloat(c.lat), parseFloat(c.lng));
    if (typeof StoreGuess == 'function') {
        var e = new Object();
        e.pageX = Math.round(p.x) - 2;
        e.pageY = Math.round(p.y) - 3;
        StoreGuess(e);
    } else {
        criaPonto((Math.round(p.x) - 2), (Math.round(p.y) - 3), 'purple');
    }
}

/*
function go(r) {
    var p = r.query.pages;
    var c = '';
    for (var pageId in p) {
        if (p[pageId].revisions == 'undefined') {
            alert('Not found');
            return;
        }
        if (p.hasOwnProperty(pageId)) {
            c = p[pageId].revisions[0]['*'];
            break;
        }
    }

    c = c.substring(0, 5000);
    c = c.replace(/\s+/g,'');
    var exp1 = /Coord\|(\d*)\|(\d*)\|?(\d*\.?\d*)?\|([NS])\|(\d*)\|(\d*)\|?(\d*\.?\d*)?\|([WE])/;
    var exp2 = /latd=(\d*).*latm=(\d*).*(?:lats=(\d*\.?\d*))?.*latNS=([NS]).*longd=(\d*).*longm=(\d*).*(?:longs=(\d*\.?\d*))?.*longEW=([EW])/;
    if ((m = exp1.exec(c)) != null || (m = exp2.exec(c)) != null) {
        var p = new P(m[1], m[2], m[3] == undefined ? 0 : m[3], m[4], m[5], m[6], m[7] == undefined ? 0 : m[7], m[8]);
        //var p = new P(41,54,0, 'N', 12,30,0,'E');

        if (typeof StoreGuess == 'function') {
            var e = new Object();
            e.pageX = Math.round(p.x) - 2;
            e.pageY = Math.round(p.y) - 3;
            StoreGuess(e);
        } else {
            criaPonto((Math.round(p.x) - 2), (Math.round(p.y) - 3), 'purple');
        }
    } else {
        alert('Didnt match pattern :(');
        document.getElementById('txt').value = c;
    }
}
*/

String.prototype.capitalize = function(){
    return this.replace( /(^|\s)([a-z])/g , function(m,p1,p2){ return p1+p2.toUpperCase(); } );
};

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
    b.value = 'CLICK ME3!';
    b.addEventListener('click', hellYeah, true);
    el.appendChild(b);

    document.getElementsByTagName('body')[0].appendChild(el);
    //document.onmousedown = function(e) {criaPonto(e.pageX, e.pageY, 'red')};
}
addButtons();
