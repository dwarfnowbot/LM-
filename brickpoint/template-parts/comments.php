<?php
/**
 * Comments template - clean, theme-styled comment list and form.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="bp-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="bp-comments__title">
			<?php
			$bp_count = get_comments_number();

			printf(
				esc_html(
					/* translators: %s: comment count. */
					_n( '%s comment', '%s comments', $bp_count, 'brickpoint' )
				),
				esc_html( number_format_i18n( $bp_count ) )
			);
			?>
		</h2>

		<ol class="bp-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 48,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => esc_html__( 'Older comments', 'brickpoint' ),
				'next_text' => esc_html__( 'Newer comments', 'brickpoint' ),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="bp-comments__closed"><?php esc_html_e( 'Comments are closed.', 'brickpoint' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_form'           => 'bp-comment-form',
			'title_reply_before'   => '<h2 class="bp-comments__title">',
			'title_reply_after'    => '</h2>',
			'comment_notes_before' => '<p class="bp-comments__notes">' . esc_html__( 'Your email address will not be published.', 'brickpoint' ) . '</p>',
			'label_submit'         => __( 'Post Comment', 'brickpoint' ),
			'class_submit'         => 'bp-btn bp-btn--primary',
		)
	);
	?>
</section>
