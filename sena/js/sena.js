// Go to a particular number anchor in the calc listing
function b1n_show(n){
  location.href = '#'+n;
}

// Show info about a particular number
function b1n_inf(n){
  var url = g_url+'?p=inf&amp;ini='+g_ini+'&amp;fin='+g_fin+'&amp;n='+n;
  window.open(url, '', 'width=830,height=620,scrollbars=yes,resizeable=yes');
}
