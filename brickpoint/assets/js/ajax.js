/**
 * BrickPoint — AJAX layer.
 *
 * Endpoints (see inc/ajax.php):
 *   brickpoint_load_more      type, page, args(JSON)
 *   brickpoint_filter_videos  category, per_page, featured, orderby
 *   brickpoint_contact        any form field (name, phone, email, material, message, ...)
 *   brickpoint_search         term
 *
 * Every request sends { action, nonce } and receives { html, page, max, count, done }
 * or { success, message } for the contact form.
 */
( function () {
	'use strict';

	var data = window.brickpointData || {};
	var i18n = data.i18n || {};

	if ( ! data.ajaxUrl ) {
		return;
	}

	function request( action, params ) {
		var body = new FormData();
		body.append( 'action', action );
		body.append( 'nonce', data.nonce || '' );

		Object.keys( params || {} ).forEach( function ( key ) {
			var value = params[ key ];

			if ( value === null || typeof value === 'undefined' ) {
				return;
			}
			body.append( key, typeof value === 'object' ? JSON.stringify( value ) : value );
		} );

		return window.fetch( data.ajaxUrl, {
			method: 'POST',
			body: body,
			credentials: 'same-origin'
		} ).then( function ( response ) {
			return response.json();
		} );
	}

	function gridBefore( node ) {
		/* Find the grid that belongs to this load-more / filter wrapper. */
		if ( ! node ) {
			return null;
		}

		var inside = node.querySelector( '[data-bp-grid]' );

		if ( inside ) {
			return inside;
		}

		var scope = node.closest( '[data-bp-video-grid-wrap]' ) ||
			node.closest( '.bp-section, section, article, main' ) ||
			document.body;

		var grids = scope.querySelectorAll( '[data-bp-grid]' );
		var best  = null;

		Array.prototype.forEach.call( grids, function ( grid ) {
			if ( grid.compareDocumentPosition( node ) & window.Node.DOCUMENT_POSITION_FOLLOWING ) {
				best = grid;
			}
		} );

		return best;
	}

	/* ------------------------------------------------------------ Load more */

	function handleLoadMore( button ) {
		var wrap = button.closest( '[data-bp-load-more]' );

		if ( ! wrap ) {
			return;
		}

		var grid = gridBefore( wrap );
		var page = parseInt( wrap.getAttribute( 'data-page' ) || '1', 10 );
		var max  = parseInt( wrap.getAttribute( 'data-max' ) || '1', 10 );
		var next = page + 1;

		if ( wrap.classList.contains( 'is-loading' ) || next > max ) {
			return;
		}

		wrap.classList.add( 'is-loading' );

		var label = button.querySelector( '.bp-btn__label' );
		var originalLabel = label ? label.textContent : button.textContent;

		if ( label ) {
			label.textContent = i18n.loading || 'Loading…';
		} else {
			button.textContent = i18n.loading || 'Loading…';
		}

		request( 'brickpoint_load_more', {
			type: wrap.getAttribute( 'data-type' ) || 'product',
			page: next,
			args: wrap.getAttribute( 'data-args' ) || '{}'
		} ).then( function ( response ) {
			if ( ! response || ! response.success || ! response.data ) {
				return;
			}

			var result = response.data;

			if ( grid && result.html ) {
				grid.insertAdjacentHTML( 'beforeend', result.html );

				/* Reveal newly added cards straight away. */
				Array.prototype.forEach.call( grid.querySelectorAll( '.bp-reveal' ), function ( el ) {
					el.classList.add( 'is-visible' );
				} );
			}

			wrap.setAttribute( 'data-page', String( result.page || next ) );
			wrap.setAttribute( 'data-max', String( result.max || max ) );

			if ( result.done || parseInt( wrap.getAttribute( 'data-max' ), 10 ) <= next ) {
				wrap.remove();
				return;
			}

			if ( label ) {
				label.textContent = i18n.loadMore || originalLabel;
			} else {
				button.textContent = i18n.loadMore || originalLabel;
			}
		} ).catch( function () {
			var status = document.createElement( 'p' );
			status.className = 'bp-form__status is-error';
			status.textContent = i18n.error || 'Something went wrong. Please try again.';
			wrap.appendChild( status );
		} ).then( function () {
			wrap.classList.remove( 'is-loading' );
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.bp-js-load-more' );

		if ( button ) {
			event.preventDefault();
			handleLoadMore( button );
		}
	} );

	/* --------------------------------------------------------- Video filter */

	function handleFilter( button ) {
		var filters = button.closest( '[data-bp-video-filters]' );

		if ( ! filters ) {
			return;
		}

		var wrap = filters.closest( '[data-bp-video-grid-wrap]' ) ||
			filters.parentNode.querySelector( '[data-bp-video-grid-wrap]' );

		var grid = gridBefore( wrap ) || gridBefore( filters );
		var category = button.getAttribute( 'data-filter' ) || '';
		var perPage = parseInt( filters.getAttribute( 'data-per-page' ) || '9', 10 );
		var orderby = filters.getAttribute( 'data-orderby' ) || 'date';

		Array.prototype.forEach.call( filters.querySelectorAll( '.bp-chip' ), function ( chip ) {
			chip.classList.remove( 'is-active' );
		} );
		button.classList.add( 'is-active' );
		button.setAttribute( 'aria-pressed', 'true' );

		if ( grid ) {
			grid.classList.add( 'is-loading' );
		}

		request( 'brickpoint_filter_videos', {
			category: category,
			per_page: perPage,
			orderby: orderby,
			featured: filters.getAttribute( 'data-featured' ) === '1' ? 1 : ''
		} ).then( function ( response ) {
			if ( ! response || ! response.success || ! response.data || ! grid ) {
				return;
			}

			grid.innerHTML = response.data.html ||
				'<p class="bp-empty-state">' + ( i18n.noResults || 'No items found' ) + '</p>';

			grid.classList.remove( 'is-loading' );

			/* Keep "load more" in sync with the filtered set. */
			var loadMore = wrap ? wrap.querySelector( '[data-bp-load-more]' ) : null;

			if ( loadMore ) {
				var args = {};

				try {
					args = JSON.parse( loadMore.getAttribute( 'data-args' ) || '{}' );
				} catch ( error ) {
					args = {};
				}

				args.category = category;
				loadMore.setAttribute( 'data-args', JSON.stringify( args ) );
				loadMore.setAttribute( 'data-page', '1' );
				loadMore.setAttribute( 'data-max', String( response.data.max || 1 ) );
				loadMore.hidden = ( response.data.max || 1 ) <= 1;
			}

			Array.prototype.forEach.call( grid.querySelectorAll( '.bp-reveal' ), function ( el ) {
				el.classList.add( 'is-visible' );
			} );
		} ).catch( function () {
			if ( grid ) {
				grid.classList.remove( 'is-loading' );
			}
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		var chip = event.target.closest( '[data-bp-video-filters] .bp-chip' );

		if ( chip ) {
			event.preventDefault();
			handleFilter( chip );
		}
	} );

	/* -------------------------------------------------------- Contact form  */

	document.addEventListener( 'submit', function ( event ) {
		var form = event.target.closest( '[data-bp-form]' );

		if ( ! form ) {
			return;
		}

		event.preventDefault();

		var status = form.querySelector( '[data-bp-form-status]' );
		var submit = form.querySelector( '[type="submit"]' );

		if ( status ) {
			status.className = 'bp-form__status';
			status.textContent = i18n.sending || 'Sending…';
		}

		if ( submit ) {
			submit.disabled = true;
		}

		var body = new FormData( form );

		if ( ! body.get( 'action' ) ) {
			body.append( 'action', 'brickpoint_contact' );
		}

		body.append( 'nonce', data.nonce || '' );

		window.fetch( data.ajaxUrl, {
			method: 'POST',
			body: body,
			credentials: 'same-origin'
		} ).then( function ( response ) {
			return response.json();
		} ).then( function ( response ) {
			var payload = response && response.data ? response.data : {};
			var ok = !!( response && response.success );

			if ( status ) {
				status.className = 'bp-form__status ' + ( ok ? 'is-success' : 'is-error' );
				status.textContent = payload.message || ( ok ? ( i18n.sent || 'Thank you.' ) : ( i18n.error || 'Something went wrong.' ) );
				status.setAttribute( 'role', 'status' );
			}

			if ( ok ) {
				form.reset();
			}
		} ).catch( function () {
			if ( ! status ) {
				return;
			}
			status.className = 'bp-form__status is-error';
			status.textContent = i18n.error || 'Something went wrong. Please try again.';
		} ).then( function () {
			if ( submit ) {
				submit.disabled = false;
			}
		} );
	} );

	/* --------------------------------------------------------- Live search  */

	var searchForms = document.querySelectorAll( '[data-bp-search-form]' );

	Array.prototype.forEach.call( searchForms, function ( form ) {
		var input   = form.querySelector( '[data-bp-search-input]' ) || form.querySelector( 'input[type="search"]' );
		var results = form.querySelector( '[data-bp-search-results]' );
		var timer   = null;

		if ( ! input || ! results ) {
			return;
		}

		function run() {
			var term = input.value.trim();

			if ( term.length < 2 ) {
				results.innerHTML = '';
				results.hidden = true;
				return;
			}

			window.clearTimeout( timer );
			timer = window.setTimeout( function () {
				request( 'brickpoint_search', { term: term } ).then( function ( response ) {
					if ( ! response || ! response.success || ! response.data ) {
						return;
					}

					results.innerHTML = response.data.html || '';
					results.hidden = ! response.data.html;
				} );
			}, 300 );
		}

		input.addEventListener( 'input', run );
		input.addEventListener( 'search', run );

		input.addEventListener( 'focus', function () {
			if ( results.innerHTML.trim() ) {
				results.hidden = false;
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! form.contains( event.target ) ) {
				results.hidden = true;
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				results.hidden = true;
			}
		} );
	} );
}() );
