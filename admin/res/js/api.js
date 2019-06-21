jQuery(function($){
	var locale = ( (typeof(window.GtkApiLocale) !== 'undefined') ? window.GtkApiLocale : null);
	if( ! locale){
		throw new Error("[Geomettric Toolkit] Error: window.GtkApiLocale was not found, please localize the admin.res/js/api.js script.")
	}

	console.info('API.JS LOADED');

});
