<?php
/**
 * Front page.
 *
 * If the page has content (Gutenberg or Elementor), that content wins.
 * Otherwise the theme renders the designed BrickPoint homepage sections, which
 * mirror the widgets available in Elementor for full rebuilds.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$front_id    = (int) get_option( 'page_on_front' );
$has_content = $front_id ? brickpoint_page_has_content( $front_id ) : false;

/*
 * While the Elementor editor is open, the page must render through the_content()
 * even when it is still empty - Elementor replaces that output with its own
 * container (`.elementor-{id}`), and without it the editor can never attach and
 * stays on the loading spinner forever.
 */
if ( ! $has_content && brickpoint_is_elementor_preview() ) {
	$has_content = true;
}

if ( $has_content ) {
	while ( have_posts() ) {
		the_post();

		// An Elementor home page brings its own full-width sections, so it is
		// printed without the theme's content wrapper.
		if ( brickpoint_is_elementor_page( get_the_ID() ) ) {
			the_content();
		} else {
			echo '<article class="bp-page bp-container">';
			the_content();
			echo '</article>';
		}
	}

	get_footer();
	return;
}

// ----------------------------------------------------------- Default homepage.
$products_url = brickpoint_page_url( 'products' );
$contact_url  = brickpoint_page_url( 'contact' );
$ss7_url      = brickpoint_page_url( 'ss7-bricks' );
$about_url    = brickpoint_page_url( 'about-us' );
$videos_url   = brickpoint_page_or_archive_url( 'videos', 'bp_video' );
$locations_url = brickpoint_page_or_archive_url( 'locations', 'bp_location' );
?>

<?php get_template_part( 'template-parts/hero' ); ?>

<!-- Trust / company intro -->
<section class="bp-section bp-section--sand bp-intro">
	<div class="bp-container">
		<div class="bp-intro__inner">
			<div class="bp-intro__content">
				<?php
				brickpoint_section_heading(
					array(
						'eyebrow' => __( 'Who we are', 'brickpoint' ),
						'title'   => __( 'BrickPoint supplies the materials that hold projects together', 'brickpoint' ),
						'text'    => __( 'BrickPoint is a construction-materials supplier providing bricks and building materials for contractors, builders, developers, architects and individual customers. Our production companies are Masha Allah Bricks Company, Fine Bricks Company and SS7 Bricks.', 'brickpoint' ),
					)
				);
				?>

				<?php
				brickpoint_feature_grid(
					array(
						array(
							'icon'  => 'shield',
							'title' => __( 'Quality-focused supply', 'brickpoint' ),
							'text'  => __( 'Materials sourced from our own production and trusted suppliers.', 'brickpoint' ),
						),
						array(
							'icon'  => 'truck',
							'title' => __( 'Reliable delivery', 'brickpoint' ),
							'text'  => __( 'Loading and dispatch coordinated with your site schedule.', 'brickpoint' ),
						),
						array(
							'icon'  => 'factory',
							'title' => __( 'Multiple production locations', 'brickpoint' ),
							'text'  => __( 'Ram Thaman, Raja Jang and Sattoki production sites.', 'brickpoint' ),
						),
						array(
							'icon'  => 'layers',
							'title' => __( 'Construction material solutions', 'brickpoint' ),
							'text'  => __( 'From bricks and cement to steel, crush, sand and finishing items.', 'brickpoint' ),
						),
					)
				);
				?>

				<?php if ( $about_url ) : ?>
					<a class="bp-btn bp-btn--primary" href="<?php echo esc_url( $about_url ); ?>">
						<span class="bp-btn__label"><?php esc_html_e( 'More about BrickPoint', 'brickpoint' ); ?></span>
						<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- Product categories -->
<section class="bp-section bp-categories-section">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'What we supply', 'brickpoint' ),
				'title'   => __( 'Materials for every stage of construction', 'brickpoint' ),
				'text'    => __( 'Explore the categories we supply most often. Every category page shows the products, specifications and a direct WhatsApp line to our team.', 'brickpoint' ),
			)
		);

		brickpoint_render_category_grid(
			array(
				'limit'          => 12,
				'columns'        => 4,
				'columns_tablet' => 3,
				'columns_mobile' => 2,
				'home_only'      => true,
			)
		);
		?>
	</div>
</section>

<!-- Featured products -->
<section class="bp-section bp-section--sand">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'Featured', 'brickpoint' ),
				'title'   => __( 'Popular materials this season', 'brickpoint' ),
			)
		);

		brickpoint_render_product_grid(
			array(
				'featured'     => true,
				'per_page'     => 4,
				'columns'      => 4,
				'show_excerpt' => false,
				'empty_text'   => '',
			)
		);

		if ( $products_url ) :
			?>
			<p class="bp-section__footer">
				<a class="bp-link-arrow" href="<?php echo esc_url( $products_url ); ?>">
					<?php esc_html_e( 'Browse all products', 'brickpoint' ); ?>
					<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</p>
			<?php
		endif;
		?>
	</div>
</section>

<!-- SS7 Bricks -->
<section class="bp-section bp-ss7 bp-ss7--dark">
	<div class="bp-container bp-ss7__inner">
		<div class="bp-ss7__media">
			<?php
			$ss7_term = get_term_by( 'slug', 'ss7-bricks', 'bp_product_category' );
			$ss7_img  = $ss7_term ? brickpoint_term_image_url( $ss7_term->term_id, 'bp-card' ) : '';

			if ( $ss7_img ) {
				printf(
					'<figure class="bp-ss7__figure"><img class="bp-ss7-brick" src="%1$s" alt="%2$s" loading="lazy" decoding="async" /></figure>',
					esc_url( $ss7_img ),
					esc_attr__( 'SS7 bricks', 'brickpoint' )
				);
			} else {
				echo '<div class="bp-ss7__figure bp-ss7__figure--placeholder">' . brickpoint_placeholder( '4x3' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>

		<div class="bp-ss7__content">
			<p class="bp-eyebrow"><?php esc_html_e( 'SS7 Bricks', 'brickpoint' ); ?></p>
			<h2 class="bp-ss7__title"><?php esc_html_e( 'The Strength Behind Every Structure', 'brickpoint' ); ?></h2>
			<p class="bp-ss7__text">
				<?php esc_html_e( 'SS7 Bricks are produced, stacked and loaded with a focus on uniform size, clean edges and consistent strength. Share your project requirement and we will confirm sizes, rate and availability.', 'brickpoint' ); ?>
			</p>

			<?php
			brickpoint_feature_grid(
				array(
					array(
						'icon'  => 'check',
						'title' => __( 'Uniform size', 'brickpoint' ),
						'text'  => __( 'Consistent dimensions help keep plaster and masonry neat.', 'brickpoint' ),
					),
					array(
						'icon'  => 'shield',
						'title' => __( 'Consistent strength', 'brickpoint' ),
						'text'  => __( 'Produced and checked for structural use.', 'brickpoint' ),
					),
					array(
						'icon'  => 'truck',
						'title' => __( 'Loading support', 'brickpoint' ),
						'text'  => __( 'Dispatch arranged with your delivery schedule.', 'brickpoint' ),
					),
				)
			);
			?>

			<div class="bp-ss7__actions">
				<?php if ( $ss7_url ) : ?>
					<a class="bp-btn bp-btn--primary bp-btn--lg" href="<?php echo esc_url( $ss7_url ); ?>">
						<span class="bp-btn__label"><?php esc_html_e( 'View SS7 Bricks', 'brickpoint' ); ?></span>
						<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endif; ?>

				<?php
				echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'label'   => __( 'Ask about SS7 on WhatsApp', 'brickpoint' ),
						'message' => brickpoint_general_inquiry_message( __( 'SS7 Bricks', 'brickpoint' ) ),
						'class'   => 'bp-btn bp-btn--whatsapp bp-btn--lg',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>

<?php
// Video sections A–D.
$featured_video = get_posts(
	array(
		'post_type'      => 'bp_video',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'bp_video_category',
				'field'    => 'slug',
				'terms'    => array( 'ss7-bricks', 'brick-manufacturing' ),
			),
		),
	)
);

if ( $featured_video ) :
	$video_id = (int) $featured_video[0]->ID;
	?>
	<section class="bp-section bp-video-feature">
		<div class="bp-container">
			<?php
			brickpoint_section_heading(
				array(
					'eyebrow' => __( 'Watch', 'brickpoint' ),
					'title'   => __( 'See the Strength Behind Every Brick', 'brickpoint' ),
					'text'    => __( 'A closer look at our bricks, the production process and the people who get them to your site.', 'brickpoint' ),
				)
			);
			?>

			<div class="bp-video-feature__inner">
				<?php brickpoint_inline_video( array( 'video_id' => $video_id, 'autoplay' => false ) ); ?>

				<div class="bp-video-feature__side">
					<h3 class="bp-video-feature__title"><?php echo esc_html( get_the_title( $video_id ) ); ?></h3>
					<p><?php echo esc_html( brickpoint_excerpt( 22, get_post_field( 'post_excerpt', $video_id ) ? get_post_field( 'post_excerpt', $video_id ) : get_post_field( 'post_content', $video_id ) ) ); ?></p>

					<?php if ( $videos_url ) : ?>
						<a class="bp-btn bp-btn--ghost" href="<?php echo esc_url( $videos_url ); ?>">
							<span class="bp-btn__label"><?php esc_html_e( 'View all videos', 'brickpoint' ); ?></span>
							<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Video section B: Behind the bricks -->
<section class="bp-section bp-section--sand">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'Behind the bricks', 'brickpoint' ),
				'title'   => __( 'From the Bhatta to Your Building', 'brickpoint' ),
				'text'    => __( 'Brick manufacturing, our bhatta locations, firing, stacking, loading and quality checks.', 'brickpoint' ),
			)
		);

		brickpoint_render_video_grid(
			array(
				'categories'     => array( 'brick-manufacturing', 'our-bhattas', 'brick-quality', 'behind-the-scenes' ),
				'per_page'       => 3,
				'columns'        => 3,
				'columns_tablet' => 2,
				'columns_mobile' => 1,
				'empty_text'     => __( 'Add videos to the “Brick Manufacturing”, “Our Bhattas” or “Brick Quality” categories and they will appear here automatically.', 'brickpoint' ),
			)
		);
		?>
	</div>
</section>

<!-- Video section C: Construction projects -->
<section class="bp-section">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'Projects', 'brickpoint' ),
				'title'   => __( 'Materials That Become Landmarks', 'brickpoint' ),
				'text'    => __( 'Construction-site visuals for context. Project references are published only when they can be confirmed.', 'brickpoint' ),
			)
		);

		brickpoint_render_video_grid(
			array(
				'categories'  => array( 'construction-projects', 'construction-materials' ),
				'per_page'    => 3,
				'columns'     => 3,
				'show_excerpt' => false,
				'empty_text'  => __( 'Videos assigned to “Construction Projects” or “Construction Materials” show up here.', 'brickpoint' ),
			)
		);

		$projects_url = brickpoint_page_or_archive_url( 'projects', 'bp_project' );

		if ( $projects_url ) :
			?>
			<p class="bp-section__footer">
				<a class="bp-link-arrow" href="<?php echo esc_url( $projects_url ); ?>">
					<?php esc_html_e( 'View projects', 'brickpoint' ); ?>
					<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>

<!-- Video section D: Construction materials -->
<section class="bp-section bp-section--sand">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'Materials', 'brickpoint' ),
				'title'   => __( 'Construction Materials in Motion', 'brickpoint' ),
				'text'    => __( 'Cement, crush, sand, steel, pipes, chemicals, cables, paints and finishing items.', 'brickpoint' ),
			)
		);

		brickpoint_render_video_grid(
			array(
				'categories'   => array( 'construction-materials', 'cement', 'bajri-crush', 'sand-rait', 'steel', 'product-videos' ),
				'per_page'     => 3,
				'columns'      => 3,
				'show_excerpt' => false,
				'empty_text'   => __( 'Add product or material videos to fill this section.', 'brickpoint' ),
			)
		);
		?>
	</div>
</section>

<!-- Locations -->
<section class="bp-section bp-locations-section">
	<div class="bp-container">
		<?php
		brickpoint_section_heading(
			array(
				'eyebrow' => __( 'Where we are', 'brickpoint' ),
				'title'   => __( 'Production locations & office', 'brickpoint' ),
				'text'    => __( 'Visit us at any BrickPoint location, or call ahead so loading and paperwork are ready when you arrive.', 'brickpoint' ),
			)
		);

		brickpoint_render_location_grid(
			array(
				'columns'        => 2,
				'columns_tablet' => 2,
				'columns_mobile' => 1,
				'show_video'     => false,
			)
		);
		?>
	</div>
</section>

<!-- CTA / contact -->
<section class="bp-section bp-cta">
	<div class="bp-container bp-cta__inner">
		<div>
			<h2 class="bp-cta__title"><?php esc_html_e( 'Tell us what your site needs', 'brickpoint' ); ?></h2>
			<p class="bp-cta__text"><?php esc_html_e( 'Share the material list, quantity and delivery location. We will confirm availability, rate and dispatch.', 'brickpoint' ); ?></p>
		</div>

		<div class="bp-cta__actions">
			<?php
			echo brickpoint_whatsapp_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				array(
					'label' => __( 'Order on WhatsApp', 'brickpoint' ),
					'class' => 'bp-btn bp-btn--whatsapp bp-btn--lg',
				)
			);

			if ( $contact_url ) :
				?>
				<a class="bp-btn bp-btn--primary bp-btn--lg" href="<?php echo esc_url( $contact_url ); ?>">
					<span class="bp-btn__label"><?php esc_html_e( 'Request a Quote', 'brickpoint' ); ?></span>
					<?php echo brickpoint_icon( 'arrow-right', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
