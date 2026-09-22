/**
 * BrickPoint — admin media fields, galleries and repeaters.
 *
 * Contract (inc/meta-fields.php + inc/taxonomies.php):
 *   .bp-image-field      .bp-image-id  .bp-image-select  .bp-image-clear
 *   .bp-gallery-field    .bp-gallery-ids  .bp-gallery-preview  .bp-gallery-remove
 *   .bp-repeater[data-next]  .bp-repeater-add  .bp-repeater-remove  template.bp-repeater-template
 *   .bp-term-image-field .bp-term-image-id  .bp-term-image-select  .bp-term-image-clear
 */
( function ( $ ) {
	'use strict';

	var strings = {
		addImages: 'Add images',
		chooseFile: 'Choose file',
		selectFile: 'Select file',
		remove: 'Remove'
	};

	/* ------------------------------------------------------ Single image    */

	$( document ).on( 'click', '.bp-image-select', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-image-field' );
		var title = field.data( 'title' ) || strings.selectFile;

		var frame = wp.media( {
			title: title,
			button: { text: field.data( 'button' ) || strings.chooseFile },
			multiple: false,
			library: { type: [ 'image', 'application/pdf' ] }
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;

			field.find( '.bp-image-id' ).val( attachment.id );
			field.find( '.bp-image-preview' ).attr( 'src', url ).show();
			field.find( '.bp-image-clear' ).css( 'display', 'inline-block' );
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.bp-image-clear', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-image-field' );

		field.find( '.bp-image-id' ).val( '' );
		field.find( '.bp-image-preview' ).attr( 'src', '' ).hide();
		$( this ).hide();
	} );

	/* ---------------------------------------------------------- Galleries   */

	/* The PHP markup ships the preview + hidden input; the button is added here
	   so a broken/absent JS file never leaves an unusable "Add" control. */
	$( function () {
		$( '.bp-gallery-field' ).each( function () {
			if ( $( this ).find( '.bp-gallery-add' ).length ) {
				return;
			}

			$( '<button type="button" class="button bp-gallery-add"></button>' )
				.text( strings.addImages )
				.insertAfter( $( this ).find( '.bp-gallery-preview' ) );
		} );
	} );

	$( document ).on( 'click', '.bp-gallery-add', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-gallery-field' );

		var frame = wp.media( {
			title: strings.addImages,
			button: { text: strings.addImages },
			multiple: 'add',
			library: { type: 'image' }
		} );

		frame.on( 'select', function () {
			var ids = field.find( '.bp-gallery-ids' ).val();
			var list = ids ? String( ids ).split( ',' ) : [];

			frame.state().get( 'selection' ).each( function ( attachment ) {
				var data = attachment.toJSON();
				var url = data.sizes && data.sizes.thumbnail ? data.sizes.thumbnail.url : data.url;

				if ( list.indexOf( String( data.id ) ) !== -1 ) {
					return;
				}

				list.push( String( data.id ) );

				$( '<span class="bp-gallery-item"></span>' )
					.attr( 'data-id', data.id )
					.append( $( '<img alt="" />' ).attr( 'src', url ).css( { width: '80px', height: '80px', objectFit: 'cover', borderRadius: '6px', display: 'block' } ) )
					.append( $( '<button type="button" class="button-link bp-gallery-remove"></button>' ).text( strings.remove ).css( 'color', '#b32d2e' ) )
					.appendTo( field.find( '.bp-gallery-preview' ) );
			} );

			field.find( '.bp-gallery-ids' ).val( list.join( ',' ) );
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.bp-gallery-remove', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-gallery-field' );
		var item = $( this ).closest( '.bp-gallery-item' );
		var id = String( item.attr( 'data-id' ) );
		var list = String( field.find( '.bp-gallery-ids' ).val() || '' ).split( ',' );

		list = $.grep( list, function ( value ) {
			return value && value !== id;
		} );

		field.find( '.bp-gallery-ids' ).val( list.join( ',' ) );
		item.remove();
	} );

	/* ---------------------------------------------------------- Repeaters   */

	$( document ).on( 'click', '.bp-repeater-add', function ( event ) {
		event.preventDefault();

		var repeater = $( this ).closest( '.bp-repeater' );
		var template = repeater.find( '.bp-repeater-template' ).html();
		var next = parseInt( repeater.attr( 'data-next' ) || '0', 10 );

		if ( ! template ) {
			return;
		}

		repeater.attr( 'data-next', next + 1 );
		repeater.find( '.bp-repeater__table tbody' ).append( template.replace( /__INDEX__/g, String( next ) ) );
	} );

	$( document ).on( 'click', '.bp-repeater-remove', function ( event ) {
		event.preventDefault();

		var repeater = $( this ).closest( '.bp-repeater' );
		var rows = repeater.find( '.bp-repeater__row' );

		if ( rows.length <= 1 ) {
			$( this ).closest( '.bp-repeater__row' ).find( 'input' ).val( '' );
			return;
		}

		$( this ).closest( '.bp-repeater__row' ).remove();
	} );

	/* ------------------------------------------------------- Term images    */

	$( document ).on( 'click', '.bp-term-image-select', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-term-image-field' );

		var frame = wp.media( {
			title: strings.selectFile,
			button: { text: strings.chooseFile },
			multiple: false,
			library: { type: 'image' }
		} );

		frame.on( 'select', function () {
			var attachment = frame.state().get( 'selection' ).first().toJSON();
			var url = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;

			field.find( '.bp-term-image-id' ).val( attachment.id );
			field.find( '.bp-term-image-preview' ).attr( 'src', url ).show();
			field.find( '.bp-term-image-clear' ).show();
		} );

		frame.open();
	} );

	$( document ).on( 'click', '.bp-term-image-clear', function ( event ) {
		event.preventDefault();

		var field = $( this ).closest( '.bp-term-image-field' );

		field.find( '.bp-term-image-id' ).val( '' );
		field.find( '.bp-term-image-preview' ).attr( 'src', '' ).hide();
		$( this ).hide();
	} );
} )( window.jQuery );
