/*
 * Provides the Ajax Search functionality if option enabled
 */
jQuery(function ($) {
	"use strict";

	//#! Must be localized
	var locale = (typeof (window.GtkAjaxSearchLocale) !== 'undefined' ? window.GtkAjaxSearchLocale : null);
	if (!locale) {
		throw new Error("The .. locale not found.");
	}


	// todo


	var AjaxSearch = {
		_searchResultsDiv: null,
		_searchCountWrap: null,
		_searchResultsWrap: null,
		_searchNoticesWrap: null,
		_isRunning: false,
		_prevSearchTerm: '',

		init: function () {


			//#! Inject markup
			var searchContentWrap = $('.bx-search__layout .container');
			searchContentWrap.append('<div class="gtk-ajax-search">\n' +
									 '\t<div class="gtk-ajax-search__top"><!-- Renders the results number --></div>\n' +
									 '\t<div class="gtk-ajax-search__wrapper"><!-- Render the results --></div>\n' +
									 '\t<div class="gtk-ajax-search__notices"><!-- <p>Render notices</p> --></div>\n' +
									 '</div>');

			//#! Setup references
			this._searchResultsDiv = $('.gtk-ajax-search');
			this._searchCountWrap = $('.gtk-ajax-search__top');
			this._searchResultsWrap = $('.gtk-ajax-search__wrapper');
			this._searchNoticesWrap = $('.gtk-ajax-search__notices');
			this.__setupListeners()
		},
		__setupListeners: function () {
			var $this = this;
			$('.bx-search .bx-search-form__input').on('keyup', function (e) {
				var searchInput = $(this),
					searchText = searchInput.val().trim();

				if (searchText.length >= 3) {
					//#! debounce
					window.setTimeout(function () {
						if (!$this._prevSearchTerm || searchText !== $this._prevSearchTerm) {
							$this._prevSearchTerm = searchText
						}
						$this.__search(searchText, $this);
					}, 250);
				}
			});
		},
		__search: function (searchText, $this) {
			if ($this._isRunning) {
				return false;
			}

			$this._isRunning = true;
			$this._searchNoticesWrap.html('');
			$this._searchCountWrap.html('');
			$this._searchResultsWrap.html('');
			$this._searchResultsDiv.addClass('is-loading');

			// do ajax
			var ajaxConfig = {
				url: locale.ajax.ajaxurl,
				timeout: 25000,
				cache: false,
				async: true,
				method: 'POST',
				// dataType: 'json',
				data: {
					[locale.ajax.nonce_name]: locale.ajax.nonce_value,
					search: searchText,
					action: 'gtk_ajax_search'
				}
			};
			$.ajax(ajaxConfig)
				.done(function (r) {
					if (!r) {
						$this._searchNoticesWrap.html('<p>' + locale.ajax.text.no_response + '</p>');
						$this._searchCountWrap.html('');
						$this._searchResultsWrap.html('');
					}
					else if (!r.success) {
						var message = (r.data ? r.data : locale.ajax.text.no_results);
						$this._searchNoticesWrap.html('<p>' + message + '</p>');
						$this._searchCountWrap.html('');
						$this._searchResultsWrap.html('');
					}
					else {
						$this._searchNoticesWrap.html('');
						$this._searchCountWrap.html(locale.ajax.text.results_count + '' + r.data.count);
						$this._searchResultsWrap.html(r.data.html);
					}
				})
				.fail(function (x, s, e) {
					$this._searchNoticesWrap.html('<p>' + e + '</p>');
					$this._searchCountWrap.html('');
					$this._searchResultsWrap.html('');
				})
				.done(function () {
					$this._isRunning = false;
					$this._searchResultsDiv.removeClass('is-loading');
				});
		}
	};

	AjaxSearch.init();

});
