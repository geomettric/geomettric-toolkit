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
			this._searchResultsDiv = $('.gtk-search-results');
			this._searchCountWrap = $('.gtk-search-results__top');
			this._searchResultsWrap = $('.gtk-search-results__wrapper');
			this._searchNoticesWrap = $('.gtk-search-results__notices');
			this.__setupListeners()
		},
		__setupListeners: function () {
			var $this = this;
			$('.gtk-search .gtk-search-form__input').on('keyup', function (e) {
				var searchInput = $(this),
					searchText = searchInput.val().trim();

				if (searchText.length >= 3) {
					console.info(searchText);
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
