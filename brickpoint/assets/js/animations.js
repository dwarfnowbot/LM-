/**
 * BrickPoint — scroll reveals (IntersectionObserver, no dependencies).
 */
( function () {
	'use strict';

	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var items  = document.querySelectorAll( '.bp-reveal' );

	if ( ! items.length ) {
		return;
	}

	function showAll() {
		Array.prototype.forEach.call( items, function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}

	if ( reduce || ! ( 'IntersectionObserver' in window ) ) {
		showAll();
		return;
	}

	/*
	 * Content is never left hidden: everything is revealed when the page is
	 * printed, when the tab is pushed to the background (toolbars, previews,
	 * screenshot services) and when the browser restores a page from the cache.
	 */
	window.addEventListener( 'beforeprint', showAll );
	window.addEventListener( 'pageshow', function ( event ) {
		if ( event.persisted ) {
			showAll();
		}
	} );
	document.addEventListener( 'visibilitychange', function () {
		if ( document.hidden ) {
			showAll();
		}
	} );

	var observer;

	try {
		observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( ! entry.isIntersecting ) {
					return;
				}
				var el = entry.target;
				var delay = parseInt( el.getAttribute( 'data-bp-delay' ) || '0', 10 );

				window.setTimeout( function () {
					el.classList.add( 'is-visible' );
				}, delay );

				observer.unobserve( el );
			} );
		}, {
			rootMargin: '0px 0px -8% 0px',
			threshold: 0.08
		} );
	} catch ( error ) {
		showAll();
		return;
	}

	Array.prototype.forEach.call( items, function ( el ) {
		var rect = el.getBoundingClientRect();

		if ( rect.top < window.innerHeight && rect.bottom > 0 ) {
			/* Already on screen when the page opened - show it immediately. */
			el.classList.add( 'is-visible' );
		}
	} );

	Array.prototype.forEach.call( items, function ( el, index ) {
		/* Small stagger inside a grid so cards cascade instead of popping. */
		if ( ! el.hasAttribute( 'data-bp-delay' ) && el.parentNode && el.parentNode.children.length > 2 ) {
			el.setAttribute( 'data-bp-delay', String( ( index % 4 ) * 90 ) );
		}
		observer.observe( el );
	} );

	/* Counters used by the [bp_stats] shortcode and Stats widget. */
	var counters = document.querySelectorAll( '.bp-stat__value[data-count]' );

	if ( ! counters.length ) {
		return;
	}

	function runCounter( el ) {
		var target = parseFloat( el.getAttribute( 'data-count' ) );
		if ( isNaN( target ) ) {
			return;
		}

		if ( reduce ) {
			el.textContent = el.getAttribute( 'data-count' ) + ( el.getAttribute( 'data-suffix' ) || '' );
			return;
		}

		var suffix = el.getAttribute( 'data-suffix' ) || '';
		var start  = null;
		var dur    = 1300;

		function step( now ) {
			if ( start === null ) {
				start = now;
			}
			var progress = Math.min( ( now - start ) / dur, 1 );
			var eased    = 1 - Math.pow( 1 - progress, 3 );
			var value    = target * eased;

			el.textContent = ( target % 1 === 0 ? Math.round( value ) : value.toFixed( 1 ) ) + suffix;

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		}

		window.requestAnimationFrame( step );
	}

	if ( 'IntersectionObserver' in window ) {
		var counterObserver = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					runCounter( entry.target );
					counterObserver.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.4 } );

		Array.prototype.forEach.call( counters, function ( el ) {
			counterObserver.observe( el );
		} );
	} else {
		Array.prototype.forEach.call( counters, runCounter );
	}
}() );
