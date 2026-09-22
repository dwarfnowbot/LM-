/**
 * BrickPoint — small front-end behaviours that do not deserve their own file:
 * copy-link, smooth in-page scrolling, external link hardening.
 */
( function () {
	'use strict';

	var data = window.brickpointData || {};
	var i18n = data.i18n || {};

	/* Progressive enhancement flag: CSS can rely on .bp-js. */
	document.documentElement.classList.remove( 'no-js' );
	document.documentElement.classList.add( 'bp-js' );

	/* --------------------------------------------------- Copy link buttons  */

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '.bp-copy-link' );

		if ( ! button ) {
			return;
		}

		event.preventDefault();

		var url = button.getAttribute( 'data-url' ) || window.location.href;
		var original = button.getAttribute( 'aria-label' ) || '';

		function done() {
			button.classList.add( 'is-copied' );
			button.setAttribute( 'aria-label', i18n.copied || 'Link copied' );

			window.setTimeout( function () {
				button.classList.remove( 'is-copied' );
				button.setAttribute( 'aria-label', original );
			}, 1800 );
		}

		if ( navigator.clipboard && navigator.clipboard.writeText ) {
			navigator.clipboard.writeText( url ).then( done ).catch( function () {
				window.prompt( url, url ); // eslint-disable-line no-alert
			} );
			return;
		}

		var field = document.createElement( 'textarea' );
		field.value = url;
		field.setAttribute( 'readonly', '' );
		field.style.position = 'absolute';
		field.style.left = '-9999px';
		document.body.appendChild( field );
		field.select();

		try {
			document.execCommand( 'copy' );
			done();
		} catch ( error ) {
			window.prompt( url, url ); // eslint-disable-line no-alert
		}

		document.body.removeChild( field );
	} );

	/* ---------------------------------------------- In-page anchor scroll  */

	document.addEventListener( 'click', function ( event ) {
		var link = event.target.closest( 'a[href*="#"]' );

		if ( ! link ) {
			return;
		}

		var hash = link.getAttribute( 'href' );

		if ( ! hash || hash === '#' || hash.indexOf( '#' ) === 0 && hash.length < 2 ) {
			return;
		}

		var url;

		try {
			url = new URL( link.href, window.location.href );
		} catch ( error ) {
			return;
		}

		if ( url.pathname !== window.location.pathname || url.search !== window.location.search || ! url.hash ) {
			return;
		}

		var target = document.getElementById( url.hash.slice( 1 ) );

		if ( ! target ) {
			return;
		}

		event.preventDefault();

		var header = document.querySelector( '[data-bp-header]' );
		var offset = header ? header.getBoundingClientRect().height + 12 : 12;
		var top = target.getBoundingClientRect().top + window.pageYOffset - offset;
		var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		window.scrollTo( {
			top: top,
			behavior: reduce ? 'auto' : 'smooth'
		} );

		if ( history.replaceState ) {
			history.replaceState( null, '', url.hash );
		}

		target.setAttribute( 'tabindex', '-1' );
		target.focus( { preventScroll: true } );
	} );

	/* ------------------------------------------- External link hardening   */

	Array.prototype.forEach.call( document.querySelectorAll( '.bp-content a[target="_blank"], .bp-post-card a[target="_blank"]' ), function ( link ) {
		var rel = ( link.getAttribute( 'rel' ) || '' ).split( ' ' );

		if ( rel.indexOf( 'noopener' ) === -1 ) {
			rel.push( 'noopener' );
		}
		if ( rel.indexOf( 'noreferrer' ) === -1 ) {
			rel.push( 'noreferrer' );
		}

		link.setAttribute( 'rel', rel.join( ' ' ).trim() );
	} );

	/* ------------------------------- WhatsApp clicks: keep tab behaviour sane */

	document.addEventListener( 'click', function ( event ) {
		var wa = event.target.closest( 'a[data-bp-whatsapp]' );

		if ( ! wa ) {
			return;
		}

		/* Ensure the number is never leaked into the referrer. */
		var rel = ( wa.getAttribute( 'rel' ) || '' ).split( ' ' );

		if ( rel.indexOf( 'noreferrer' ) === -1 ) {
			rel.push( 'noreferrer' );
		}

		wa.setAttribute( 'rel', rel.join( ' ' ).trim() );
	} );

	/* ------------------------------------------------------ Sticky fallback */

	if ( 'ResizeObserver' in window ) {
		var header = document.querySelector( '[data-bp-header]' );
		var spacer = document.querySelector( '.bp-header-spacer' );

		if ( header && spacer ) {
			new window.ResizeObserver( function () {
				spacer.style.height = header.getBoundingClientRect().height + 'px';
			} ).observe( header );
		}
	}
}() );
