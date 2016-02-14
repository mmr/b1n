var observerUtils = {
  	openLinkToNewTab: function(url) {
	 	var windowManager = ( Components.classes[ "@mozilla.org/appshell/window-mediator;1" ] ).getService();
		var windowManagerInterface = windowManager.QueryInterface( Components.interfaces.nsIWindowMediator );
		var browser = ( windowManagerInterface.getMostRecentWindow( "navigator:browser" ) ).getBrowser();
	    var newTab = browser.addTab( url );
	    browser.selectedTab = newTab;
	 }
}
