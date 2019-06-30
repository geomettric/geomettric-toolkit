/*
 * Enable the basic functionality for the ajax search overlay: show/hide
 */
jQuery(function ($) {
	"use strict";

	$('.js-search-trigger').on('click', function (e) {
		e.preventDefault();
		e.stopPropagation();
		var overlay = $('.gtk-search');
		if (typeof (overlay) !== 'undefined') {
			overlay.removeClass('u-hidden');

			$('.js-close-search').on('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				overlay.addClass('u-hidden');
			})
		}
	});
});
