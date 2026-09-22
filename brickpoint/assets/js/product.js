/**
 * BrickPoint — product page behaviour (gallery, quantity → WhatsApp).
 *
 * Contract: markup from inc/product-functions.php.
 *   .bp-js-gallery  [data-bp-gallery-main]  [data-bp-gallery-thumb][data-src]
 *   [data-bp-product-actions][data-product]  [data-bp-qty]  a[data-bp-whatsapp]
 */
( function () {
	'use strict';

	var data = window.brickpointData || {};
	var i18n = data.i18n || {};

	/* ------------------------------------------------------------- Gallery  */

	Array.prototype.forEach.call( document.querySelectorAll( '.bp-js-gallery' ), function ( gallery ) {
		var main   = gallery.querySelector( '[data-bp-gallery-main]' );
		var thumbs = gallery.querySelectorAll( '[data-bp-gallery-thumb]' );

		if ( ! main || thumbs.length < 2 ) {
			return;
		}

		var current = 0;

		function show( index ) {
			var thumb = thumbs[ index ];

			if ( ! thumb ) {
				return;
			}

			var src = thumb.getAttribute( 'data-src' );

			if ( ! src ) {
				return;
			}

			var full = thumb.getAttribute( 'data-full' );

			main.setAttribute( 'src', full || src );

			if ( thumb.getAttribute( 'data-srcset' ) ) {
				main.setAttribute( 'srcset', thumb.getAttribute( 'data-srcset' ) );
			}

			Array.prototype.forEach.call( thumbs, function ( item, i ) {
				item.classList.toggle( 'is-active', i === index );
				item.setAttribute( 'aria-current', i === index ? 'true' : 'false' );
			} );

			current = index;
		}

		Array.prototype.forEach.call( thumbs, function ( thumb, index ) {
			thumb.addEventListener( 'click', function () {
				show( index );
			} );

			thumb.addEventListener( 'keydown', function ( event ) {
				if ( event.key === 'Enter' || event.key === ' ' ) {
					event.preventDefault();
					show( index );
				}
			} );
		} );

		/* Arrow keys walk through the gallery when the main image is focused. */
		gallery.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'ArrowRight' && event.key !== 'ArrowLeft' ) {
				return;
			}

			event.preventDefault();
			show( event.key === 'ArrowRight' ? ( current + 1 ) % thumbs.length : ( current - 1 + thumbs.length ) % thumbs.length );
		} );
	} );

	/* ------------------------------------------- Quantity → WhatsApp message */

	Array.prototype.forEach.call( document.querySelectorAll( '[data-bp-product-actions]' ), function ( actions ) {
		var qty    = actions.querySelector( '[data-bp-qty]' );
		var button = actions.querySelector( 'a[data-bp-whatsapp]' );

		if ( ! qty || ! button ) {
			return;
		}

		var href    = button.getAttribute( 'href' ) || '';
		var baseUrl = href;
		var baseText = '';

		try {
			var url = new URL( href, window.location.origin );
			baseText = url.searchParams.get( 'text' ) || '';
			baseUrl  = url.toString();
		} catch ( error ) {
			baseText = '';
		}

		function sync() {
			var value = qty.value.trim();

			try {
				var url = new URL( baseUrl );

				if ( ! value ) {
					url.searchParams.set( 'text', baseText );
				} else {
					var separator = baseText.indexOf( '\n' ) === -1 ? ' - ' : '\n';
					url.searchParams.set( 'text', baseText + separator + 'Quantity: ' + value );
				}

				button.setAttribute( 'href', url.toString() );
			} catch ( error ) {
				/* Very old browsers: fall back to the original link. */
			}
		}

		qty.addEventListener( 'input', sync );
		qty.addEventListener( 'change', sync );
	} );

	/* --------------------------------------------------- Product tabs / specs */

	Array.prototype.forEach.call( document.querySelectorAll( '[data-bp-specs-toggle]' ), function ( toggle ) {
		var table = document.getElementById( toggle.getAttribute( 'aria-controls' ) );

		if ( ! table ) {
			return;
		}

		toggle.addEventListener( 'click', function () {
			var expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
			toggle.setAttribute( 'aria-expanded', expanded ? 'false' : 'true' );
			table.hidden = expanded;
		} );
	} );

	/* Print-friendly helper for the quotation CTA (no dependency on PDF libs). */
	Array.prototype.forEach.call( document.querySelectorAll( '[data-bp-print]' ), function ( button ) {
		button.addEventListener( 'click', function () {
			window.print();
		} );
	} );
}() );
