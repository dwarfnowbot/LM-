/**
 * BrickPoint — navigation (sticky header, burger, submenus, search panel).
 */
( function () {
	'use strict';

	var header = document.querySelector( '[data-bp-header]' );
	var burger = document.querySelector( '.bp-burger' );
	var nav    = document.getElementById( 'bp-primary-nav' );
	var searchToggle;

	if ( ! header ) {
		return;
	}

	/* ------------------------------------------------------------- Sticky  */
	var stickyOffset = 0;
	var isStickyOn   = header.getAttribute( 'data-sticky' ) !== '0';

	function recalcOffset() {
		stickyOffset = header.getBoundingClientRect().height > 0
			? header.offsetTop + header.offsetHeight
			: 0;
	}

	function onScroll() {
		if ( ! isStickyOn || window.innerWidth < 782 ) {
			return;
		}
		if ( window.pageYOffset > stickyOffset ) {
			header.classList.add( 'is-sticky' );
		} else {
			header.classList.remove( 'is-sticky' );
		}
	}

	recalcOffset();
	onScroll();
	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', function () {
		recalcOffset();
		onScroll();
	}, { passive: true } );

	/* --------------------------------------------------- Submenu on touch  */
	if ( nav ) {
		nav.addEventListener( 'click', function ( event ) {
			var link = event.target.closest( 'a' );
			if ( ! link || ! nav.contains( link ) ) {
				return;
			}

			var parent = link.parentNode;
			var hasSub = parent && parent.classList && parent.classList.contains( 'menu-item-has-children' );

			if ( ! hasSub ) {
				closeNav();
				return;
			}

			/* On mobile the first tap opens the submenu instead of navigating. */
			if ( window.innerWidth <= 1024 && ! parent.classList.contains( 'is-open' ) ) {
				event.preventDefault();
				parent.classList.add( 'is-open' );
			}
		} );

		/* Keyboard support: Escape closes the drawer. */
		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				closeNav();
			}
		} );
	}

	/* -------------------------------------------------------- Burger menu  */
	function openNav() {
		if ( ! nav ) {
			return;
		}
		nav.classList.add( 'is-open' );
		backdrop( true );
		if ( burger ) {
			burger.setAttribute( 'aria-expanded', 'true' );
		}
		document.body.classList.add( 'bp-nav-open' );
	}

	function closeNav() {
		if ( ! nav ) {
			return;
		}
		nav.classList.remove( 'is-open' );
		backdrop( false );
		if ( burger ) {
			burger.setAttribute( 'aria-expanded', 'false' );
		}
		document.body.classList.remove( 'bp-nav-open' );
	}

	var backdropEl = null;

	function backdrop( show ) {
		if ( ! backdropEl ) {
			backdropEl = document.createElement( 'div' );
			backdropEl.className = 'bp-nav-backdrop';
			backdropEl.addEventListener( 'click', closeNav );
			document.body.appendChild( backdropEl );
		}
		window.requestAnimationFrame( function () {
			backdropEl.classList.toggle( 'is-active', !! show );
		} );
	}

	if ( burger && nav ) {
		burger.addEventListener( 'click', function () {
			if ( nav.classList.contains( 'is-open' ) ) {
				closeNav();
			} else {
				openNav();
			}
		} );
	}

	/* ------------------------------------------------------ Search panel   */
	searchToggle = document.querySelector( '.bp-header__search-toggle' );
	var searchPanel = document.getElementById( 'bp-header-search' );

	if ( searchToggle && searchPanel ) {
		searchToggle.addEventListener( 'click', function () {
			var isHidden = searchPanel.hasAttribute( 'hidden' );
			if ( isHidden ) {
				searchPanel.removeAttribute( 'hidden' );
				searchToggle.setAttribute( 'aria-expanded', 'true' );
				var input = searchPanel.querySelector( 'input[type="search"]' );
				if ( input ) {
					input.focus();
				}
			} else {
				searchPanel.setAttribute( 'hidden', 'hidden' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && ! searchPanel.hasAttribute( 'hidden' ) ) {
				searchPanel.setAttribute( 'hidden', 'hidden' );
				searchToggle.setAttribute( 'aria-expanded', 'false' );
				searchToggle.focus();
			}
		} );
	}

	/* -------------------------------------------------- Menu accessibility */
	if ( nav ) {
		var links = nav.querySelectorAll( 'a' );
		Array.prototype.forEach.call( links, function ( link ) {
			var li = link.parentNode;
			if ( li && li.classList && li.classList.contains( 'menu-item-has-children' ) ) {
				li.addEventListener( 'focusin', function () {
					li.classList.add( 'is-open' );
				} );
				li.addEventListener( 'focusout', function ( event ) {
					if ( ! li.contains( event.relatedTarget ) ) {
						li.classList.remove( 'is-open' );
					}
				} );
			}
		} );
	}

	/* Keep body scroll locked while the drawer is open on small screens. */
	window.addEventListener( 'resize', function () {
		if ( window.innerWidth > 1024 ) {
			closeNav();
		}
	} );
}() );
