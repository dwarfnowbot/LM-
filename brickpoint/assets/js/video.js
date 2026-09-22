/**
 * BrickPoint — video playback layer.
 *
 * 1. Lightbox player (shared markup printed in wp_footer).
 * 2. In-place players for inline video blocks.
 * 3. Hero background video (lazy embeds, pause when off-screen, play/pause toggle).
 *
 * Contract: elements are produced by inc/video-functions.php.
 * Triggers carry data-embed (iframe url), data-mp4 (self-hosted file),
 * data-captions and data-title.
 */
( function () {
	'use strict';

	var data = window.brickpointData || {};
	var i18n = data.i18n || {};
	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( data.reducedMotion === '1' ) {
		reduce = true;
	}

	/* ------------------------------------------------------------- Helpers  */

	function buildPlayer( trigger, autoplay ) {
		var embed   = trigger.getAttribute( 'data-embed' ) || '';
		var mp4     = trigger.getAttribute( 'data-mp4' ) || '';
		var caption = trigger.getAttribute( 'data-captions' ) || '';
		var poster  = trigger.getAttribute( 'data-poster' ) || '';
		var muted   = trigger.getAttribute( 'data-muted' ) === '1';
		var node;

		if ( mp4 ) {
			node = document.createElement( 'video' );
			node.setAttribute( 'controls', '' );
			node.setAttribute( 'playsinline', '' );
			node.setAttribute( 'preload', 'metadata' );
			node.className = 'bp-video__el';

			if ( poster ) {
				node.setAttribute( 'poster', poster );
			}
			if ( autoplay ) {
				node.setAttribute( 'autoplay', '' );
			}
			if ( muted ) {
				node.muted = true;
			}

			var source = document.createElement( 'source' );
			source.src = mp4;
			source.type = 'video/mp4';
			node.appendChild( source );

			if ( caption ) {
				var track = document.createElement( 'track' );
				track.kind = 'captions';
				track.src = caption;
				track.default = true;
				node.appendChild( track );
			}
		} else if ( embed ) {
			node = document.createElement( 'iframe' );
			node.src = embed + ( embed.indexOf( '?' ) === -1 ? '?' : '&' ) + 'autoplay=' + ( autoplay ? '1' : '0' );
			node.setAttribute( 'title', trigger.getAttribute( 'data-title' ) || i18n.playVideo || 'Video' );
			node.setAttribute( 'allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen' );
			node.setAttribute( 'allowfullscreen', '' );
			node.setAttribute( 'loading', 'lazy' );
			node.setAttribute( 'referrerpolicy', 'strict-origin-when-cross-origin' );
		}

		return node;
	}

	function startPlayback( node ) {
		if ( node && typeof node.play === 'function' ) {
			var attempt = node.play();

			if ( attempt && typeof attempt.catch === 'function' ) {
				attempt.catch( function () {
					/* Autoplay blocked — controls are visible, nothing to do. */
				} );
			}
		}
	}

	function isModified( event ) {
		return event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;
	}

	/* ------------------------------------------------------------ Lightbox  */

	var lightbox = document.getElementById( 'bp-video-lightbox' );
	var stage    = lightbox ? lightbox.querySelector( '[data-bp-lightbox-stage]' ) : null;
	var ltitle   = lightbox ? lightbox.querySelector( '[data-bp-lightbox-title]' ) : null;
	var lastFocus = null;

	function openLightbox( trigger ) {
		if ( ! lightbox || ! stage ) {
			return false;
		}

		stage.innerHTML = '';
		var player = buildPlayer( trigger, true );

		if ( ! player ) {
			return false;
		}

		stage.appendChild( player );
		startPlayback( player );

		if ( ltitle ) {
			ltitle.textContent = trigger.getAttribute( 'data-title' ) || '';
		}

		lastFocus = document.activeElement;
		lightbox.removeAttribute( 'hidden' );
		document.documentElement.classList.add( 'bp-no-scroll' );

		var closeBtn = lightbox.querySelector( '[data-bp-lightbox-close]' );

		if ( closeBtn ) {
			closeBtn.focus();
		}

		return true;
	}

	function closeLightbox() {
		if ( ! lightbox || lightbox.hasAttribute( 'hidden' ) ) {
			return;
		}

		lightbox.setAttribute( 'hidden', 'hidden' );
		document.documentElement.classList.remove( 'bp-no-scroll' );

		window.setTimeout( function () {
			stage.innerHTML = ''; /* Stops playback immediately. */
		}, 60 );

		if ( lastFocus && lastFocus.focus ) {
			lastFocus.focus();
		}
	}

	if ( lightbox ) {
		lightbox.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '[data-bp-lightbox-close]' ) ) {
				closeLightbox();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				closeLightbox();
			}
		} );

		/* Focus trap for keyboard users. */
		lightbox.addEventListener( 'keydown', function ( event ) {
			if ( event.key !== 'Tab' ) {
				return;
			}

			var focusable = lightbox.querySelectorAll( 'button, iframe, video, [href]' );

			if ( ! focusable.length ) {
				return;
			}

			var first = focusable[ 0 ];
			var last  = focusable[ focusable.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		} );
	}

	/* --------------------------------------------- Inline blocks + triggers */

	document.addEventListener( 'click', function ( event ) {
		var blockTrigger = event.target.closest( '.bp-js-video-trigger' );

		if ( ! blockTrigger || isModified( event ) ) {
			return;
		}

		var block = blockTrigger.closest( '.bp-js-video-block' );

		/* Inline blocks play where they are. */
		if ( block ) {
			event.preventDefault();

			var autoplay = block.getAttribute( 'data-autoplay' ) === '1' && ! reduce;
			var poster   = block.querySelector( '.bp-video__poster' );
			var inline   = buildPlayer( blockTrigger, autoplay !== false );

			if ( ! inline ) {
				return;
			}

			if ( poster ) {
				blockTrigger.setAttribute( 'data-poster', poster.getAttribute( 'data-poster' ) || '' );
				poster.parentNode.replaceChild( inline, poster );
			} else {
				block.appendChild( inline );
			}

			startPlayback( inline );
			block.classList.add( 'is-playing' );
			block.removeAttribute( 'data-bp-video-block' );
			return;
		}

		/* Cards and buttons: prefer the lightbox, fall back to the link. */
		if ( lightbox && openLightbox( blockTrigger ) ) {
			event.preventDefault();
		}
	} );

	/* ------------------------------------------------------- Hero video     */

	var heroWrap = document.querySelector( '[data-bp-hero-video]' );

	if ( heroWrap ) {
		var toggle  = heroWrap.querySelector( '.bp-js-video-toggle' );
		var video   = heroWrap.querySelector( 'video' );
		var embedEl = heroWrap.querySelector( '.bp-js-hero-embed' );

		function setToggleState( playing ) {
			if ( ! toggle ) {
				return;
			}

			var pauseIcon = toggle.querySelector( '.bp-js-icon-pause' );
			var playIcon  = toggle.querySelector( '.bp-js-icon-play' );

			if ( pauseIcon ) {
				pauseIcon.hidden = ! playing;
			}
			if ( playIcon ) {
				playIcon.hidden = playing;
			}

			toggle.setAttribute( 'aria-pressed', playing ? 'true' : 'false' );
		}

		if ( video ) {
			/* Reduced motion: never autoplay, let the poster stand in. */
			if ( reduce ) {
				video.removeAttribute( 'autoplay' );
				video.pause();
				setToggleState( false );
			} else {
				/* Only spend bandwidth/CPU while the hero is on screen. */
				if ( 'IntersectionObserver' in window ) {
					new IntersectionObserver( function ( entries ) {
						entries.forEach( function ( entry ) {
							if ( entry.isIntersecting && ! document.hidden ) {
								startPlayback( video );
								setToggleState( true );
							} else if ( ! video.paused ) {
								video.pause();
								setToggleState( false );
							}
						} );
					}, { threshold: 0.12 } ).observe( heroWrap );
				}

				document.addEventListener( 'visibilitychange', function () {
					if ( document.hidden ) {
						video.pause();
						setToggleState( false );
					} else if ( video.paused && ! reduce ) {
						startPlayback( video );
						setToggleState( true );
					}
				} );
			}

			video.addEventListener( 'play', function () {
				setToggleState( true );
			} );
			video.addEventListener( 'pause', function () {
				setToggleState( false );
			} );
		}

		if ( toggle && ( video || embedEl ) ) {
			toggle.addEventListener( 'click', function () {
				if ( video ) {
					if ( video.paused ) {
						startPlayback( video );
					} else {
						video.pause();
					}
					return;
				}

				var frame = heroWrap.querySelector( 'iframe' );

				if ( ! frame ) {
					/* First click on an embed hero replaces the poster. */
					var iframe = buildPlayer( embedEl, true );

					if ( iframe ) {
						embedEl.innerHTML = '';
						embedEl.appendChild( iframe );
					}
					setToggleState( true );
					return;
				}

				/* A cross-origin iframe cannot be paused: reload it or remove it. */
				if ( 'none' === frame.style.display ) {
					frame.style.display = '';
					setToggleState( true );
				} else {
					frame.style.display = 'none';
					setToggleState( false );
				}
			} );
		}

		/* Switch YouTube/Vimeo poster to an iframe once the hero is visible. */
		if ( embedEl && ! reduce ) {
			var activateEmbed = function () {
				if ( embedEl.querySelector( 'iframe' ) ) {
					return;
				}

				var iframe = buildPlayer( embedEl, true );

				if ( iframe ) {
					embedEl.appendChild( iframe );
					setToggleState( true );
				}
			};

			if ( 'IntersectionObserver' in window ) {
				new IntersectionObserver( function ( entries, observer ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							activateEmbed();
							observer.disconnect();
						}
					} );
				}, { threshold: 0.25 } ).observe( heroWrap );
			} else {
				window.addEventListener( 'load', activateEmbed );
			}
		}
	}
}() );
