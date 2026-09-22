<?php
/**
 * Demo content for BrickPoint.
 *
 * A fresh BrickPoint site should never look empty. This module installs a
 * complete, clearly-labelled demo set with one click (and automatically once
 * after a theme update):
 *
 * - photos and short demo clips bundled with the theme,
 * - products, videos, projects, blog posts and location details,
 * - Elementor layouts for the main pages, so every page opens in the Elementor
 *   editor with real, editable sections instead of an empty canvas,
 * - the four real BrickPoint locations with their Google Maps links.
 *
 * Rules kept everywhere:
 * - nothing is overwritten: an item is only created when it does not exist yet,
 * - everything created here is flagged `_bp_demo`, so it can be removed again,
 *   and the WordPress list screens show a "Demo" badge next to it,
 * - no invented prices, statistics, certifications, clients or supply claims.
 *
 * @package BrickPoint
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bundled demo images: key => file, title, alt text.
 *
 * @return array<string,array<string,string>>
 */
function brickpoint_demo_image_files() {
	return array(
		'hero-poster'          => array(
			'file'  => 'hero-poster.jpg',
			'title' => __( 'Brick kiln yard at sunset (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Rows of stacked clay bricks at a brick kiln yard in the evening light (demo photo)', 'brickpoint' ),
		),
		'hero-video-poster'    => array(
			'file'  => 'hero-video-poster.jpg',
			'title' => __( 'Hero video poster (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Brick kiln yard at sunset used as the hero video poster (demo photo)', 'brickpoint' ),
		),
		'brick-ss7'            => array(
			'file'  => 'brick-ss7.jpg',
			'title' => __( 'Stacked clay bricks (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Neatly stacked clay bricks with clean edges (demo photo)', 'brickpoint' ),
		),
		'brick-standard'       => array(
			'file'  => 'brick-standard.jpg',
			'title' => __( 'Brick stacking yard (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Brick stacking yard with rows of drying bricks (demo photo)', 'brickpoint' ),
		),
		'cement'               => array(
			'file'  => 'cement.jpg',
			'title' => __( 'Cement bags on pallets (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Stacked cement bags on wooden pallets in a warehouse (demo photo)', 'brickpoint' ),
		),
		'crush'                => array(
			'file'  => 'crush.jpg',
			'title' => __( 'Crushed stone aggregate (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Large pile of crushed stone aggregate at a materials yard (demo photo)', 'brickpoint' ),
		),
		'sand'                 => array(
			'file'  => 'sand.jpg',
			'title' => __( 'Sand stockpile (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Sand stockpile with a tipper truck at a materials yard (demo photo)', 'brickpoint' ),
		),
		'steel'                => array(
			'file'  => 'steel.jpg',
			'title' => __( 'Steel rebar bundles (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Bundles of ribbed steel reinforcement bar stacked on a warehouse floor (demo photo)', 'brickpoint' ),
		),
		'chemicals'            => array(
			'file'  => 'chemicals.jpg',
			'title' => __( 'Construction chemicals (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Drums and buckets of construction chemicals in a materials depot (demo photo)', 'brickpoint' ),
		),
		'pipes'                => array(
			'file'  => 'pipes.jpg',
			'title' => __( 'Pipes and fittings (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Stacked PVC pipes and coiled HDPE pipe at a materials depot (demo photo)', 'brickpoint' ),
		),
		'pavers'               => array(
			'file'  => 'pavers.jpg',
			'title' => __( 'Interlocking pavers and tiles (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Stacked interlocking concrete pavers at a materials yard (demo photo)', 'brickpoint' ),
		),
		'project-house'        => array(
			'file'  => 'project-house.jpg',
			'title' => __( 'House under construction (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Brick walls and concrete columns of a house under construction (demo photo)', 'brickpoint' ),
		),
		'project-commercial'   => array(
			'file'  => 'project-commercial.jpg',
			'title' => __( 'Commercial construction site (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Steel reinforcement and structure at a commercial construction site (demo photo)', 'brickpoint' ),
		),
		'project-boundary'     => array(
			'file'  => 'project-boundary.jpg',
			'title' => __( 'Masonry works (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Brick masonry courses in progress at a building site (demo photo)', 'brickpoint' ),
		),
		'office'               => array(
			'file'  => 'office.jpg',
			'title' => __( 'Warehouse and office (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Construction materials warehouse with stacked supplies (demo photo)', 'brickpoint' ),
		),
		'blog-brick-quality'   => array(
			'file'  => 'blog-brick-quality.jpg',
			'title' => __( 'Brick quality close-up (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Close-up of clay bricks showing size and edge finish (demo photo)', 'brickpoint' ),
		),
		'blog-delivery'        => array(
			'file'  => 'blog-delivery.jpg',
			'title' => __( 'Delivery loading (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Tipper truck being loaded at a materials yard (demo photo)', 'brickpoint' ),
		),
		'thumb-pavers'         => array(
			'file'  => 'thumb-pavers.jpg',
			'title' => __( 'Pavers stock (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Stacks of interlocking pavers ready for dispatch (demo photo)', 'brickpoint' ),
		),
		'thumb-pipes'          => array(
			'file'  => 'thumb-pipes.jpg',
			'title' => __( 'Pipes stock (demo photo)', 'brickpoint' ),
			'alt'   => __( 'Bundles of pipes ready for dispatch (demo photo)', 'brickpoint' ),
		),
	);
}

/**
 * Bundled demo video clips: key => file, title, duration.
 *
 * @return array<string,array<string,string>>
 */
function brickpoint_demo_video_files() {
	return array(
		'demo-brick-yard'     => array(
			'file'     => 'video/demo-brick-yard.mp4',
			'title'    => __( 'Brick yard walkthrough (demo clip)', 'brickpoint' ),
			'duration' => '0:06',
		),
		'demo-brick-stacking' => array(
			'file'     => 'video/demo-brick-stacking.mp4',
			'title'    => __( 'Brick stacking (demo clip)', 'brickpoint' ),
			'duration' => '0:06',
		),
		'demo-loading'        => array(
			'file'     => 'video/demo-loading.mp4',
			'title'    => __( 'Sand loading for delivery (demo clip)', 'brickpoint' ),
			'duration' => '0:06',
		),
		'demo-steel'          => array(
			'file'     => 'video/demo-steel.mp4',
			'title'    => __( 'Steel stock (demo clip)', 'brickpoint' ),
			'duration' => '0:06',
		),
	);
}

/**
 * Demo products.
 *
 * @return array<int,array<string,mixed>>
 */
function brickpoint_demo_products() {
	return array(
		array(
			'key'       => 'product-ss7-bricks',
			'title'     => __( 'SS7 Bricks', 'brickpoint' ),
			'cats'      => array( 'ss7-bricks', 'bricks' ),
			'image'     => 'brick-ss7',
			'gallery'   => array( 'brick-ss7', 'brick-standard', 'project-boundary' ),
			'excerpt'   => __( 'Machine-moulded bricks from our own production, stacked with a focus on uniform size and clean edges. Rates and availability are confirmed on inquiry.', 'brickpoint' ),
			'content'   => __( "SS7 Bricks are produced at our own bhattas and supplied in bulk for grey structure, boundary walls and general masonry work.\n\nEvery consignment is stacked and loaded with care so the bricks reach your site in good condition. Tell us the quantity, the delivery location and when you need the material, and our team will confirm the current rate and loading schedule.", 'brickpoint' ),
			'unit'      => __( 'per 1,000 bricks', 'brickpoint' ),
			'badge'     => __( 'Own production', 'brickpoint' ),
			'sku'       => 'DEMO-SS7-001',
			'max_order' => __( 'Bulk loads — confirm quantity on inquiry', 'brickpoint' ),
			'area'      => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'     => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Fired clay brick', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Load-bearing walls, grey structure, boundary walls', 'brickpoint' ) ),
				array( 'label' => __( 'Supply', 'brickpoint' ), 'value' => __( 'Bulk loads — confirm sizes and rates on inquiry', 'brickpoint' ) ),
			),
			'features'  => __( "Uniform size and clean edges\nConsistent strength across the stack\nStacked and loaded with care\nBulk supply for projects and homes", 'brickpoint' ),
			'whatsapp'  => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for SS7 Bricks.', 'brickpoint' ),
			'featured'  => 1,
			'order'     => 1,
		),
		array(
			'key'      => 'product-clay-bricks',
			'title'    => __( 'Clay Bricks (Standard)', 'brickpoint' ),
			'cats'     => array( 'bricks' ),
			'image'    => 'brick-standard',
			'gallery'  => array( 'brick-standard', 'brick-ss7' ),
			'excerpt'  => __( 'Standard clay bricks for general construction work, supplied in bulk with loading support.', 'brickpoint' ),
			'content'  => __( "Standard clay bricks for walls, boundary walls and general masonry work. Available as loose loads or stacked bundles depending on your site's requirement.\n\nConfirm the current rate, the number of bricks per load and the loading time with our sales team before dispatch.", 'brickpoint' ),
			'unit'     => __( 'per 1,000 bricks', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-BRK-002',
			'max_order' => __( 'Bulk loads — confirm quantity on inquiry', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Fired clay brick', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Walls, grey structure, boundary walls', 'brickpoint' ) ),
			),
			'features' => __( "Supplied in bulk\nLoading support at the yard\nConfirm sizes and rates on inquiry", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for clay bricks.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 2,
		),
		array(
			'key'      => 'product-cement',
			'title'    => __( 'Cement', 'brickpoint' ),
			'cats'     => array( 'cement' ),
			'image'    => 'cement',
			'gallery'  => array( 'cement' ),
			'excerpt'  => __( 'Branded cement bags for grey structure and finishing work. Confirm the current brand and rate on inquiry.', 'brickpoint' ),
			'content'  => __( "Cement bags supplied for slab casting, masonry, plaster and finishing work.\n\nBrand, packing and the number of bags per load vary with availability, so please confirm the current brand and rate with our team before you plan the delivery.", 'brickpoint' ),
			'unit'     => __( 'per bag', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-CEM-003',
			'max_order' => __( 'Truck loads available on request', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Packing', 'brickpoint' ), 'value' => __( 'Standard bags — confirm brand on inquiry', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Slab casting, masonry, plaster, finishing', 'brickpoint' ) ),
			),
			'features' => __( "Delivered with your brick loads\nAvailability and brand confirmed on inquiry\nGrey structure to finishing", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for cement.', 'brickpoint' ),
			'featured' => 1,
			'order'    => 3,
		),
		array(
			'key'      => 'product-bajri-crush',
			'title'    => __( 'Bajri / Crush', 'brickpoint' ),
			'cats'     => array( 'bajri-crush' ),
			'image'    => 'crush',
			'gallery'  => array( 'crush', 'blog-delivery' ),
			'excerpt'  => __( 'Crushed aggregate for concrete and base layers, delivered by truck to your site.', 'brickpoint' ),
			'content'  => __( "Crushed stone aggregate for concrete mixes and base layers, supplied by truck load.\n\nTell us the grade and the quantity you need and we will confirm availability, rate and the delivery timeline.", 'brickpoint' ),
			'unit'     => __( 'per truck load', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-CRS-004',
			'max_order' => __( 'Single or multiple truck loads', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Crushed stone aggregate', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Concrete, base layers, soling', 'brickpoint' ) ),
			),
			'features' => __( "Delivered by truck\nGrade and quantity confirmed on inquiry\nSite delivery coordination", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a rate for bajri / crush.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 4,
		),
		array(
			'key'      => 'product-sand-rait',
			'title'    => __( 'Sand / Rait', 'brickpoint' ),
			'cats'     => array( 'sand-rait' ),
			'image'    => 'sand',
			'gallery'  => array( 'sand' ),
			'excerpt'  => __( 'Sand for plastering, masonry and concrete mixes, available in bulk loads.', 'brickpoint' ),
			'content'  => __( "Sand for masonry, plaster and concrete mixes, supplied in bulk loads.\n\nConfirm the quantity and the delivery location and we will respond with the current rate and the expected delivery time.", 'brickpoint' ),
			'unit'     => __( 'per truck load', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-SND-005',
			'max_order' => __( 'Single or multiple truck loads', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Sand / rait', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Plaster, masonry, concrete mixes', 'brickpoint' ) ),
			),
			'features' => __( "Bulk loads\nDelivery coordinated with your schedule\nRates confirmed on inquiry", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a rate for sand / rait.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 5,
		),
		array(
			'key'      => 'product-steel',
			'title'    => __( 'Steel Rebar', 'brickpoint' ),
			'cats'     => array( 'steel' ),
			'image'    => 'steel',
			'gallery'  => array( 'steel' ),
			'excerpt'  => __( 'Deformed steel bars supplied against your requirement list. Sizes and rates are confirmed on inquiry.', 'brickpoint' ),
			'content'  => __( "Deformed steel reinforcement bars supplied against your bar bending schedule or requirement list.\n\nShare the sizes, quantities and the delivery location and our team will confirm what is available and at what rate.", 'brickpoint' ),
			'unit'     => __( 'per tonne / per bundle', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-STL-006',
			'max_order' => __( 'Confirm quantity and sizes on inquiry', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Deformed steel reinforcement bar', 'brickpoint' ) ),
				array( 'label' => __( 'Sizes', 'brickpoint' ), 'value' => __( 'Common site sizes — confirm availability on inquiry', 'brickpoint' ) ),
			),
			'features' => __( "Supplied against your requirement list\nSizes confirmed before dispatch\nDelivery with your other materials", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a rate for steel rebar.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 6,
		),
		array(
			'key'      => 'product-pipes',
			'title'    => __( 'Pipes and Fittings', 'brickpoint' ),
			'cats'     => array( 'plumbing-pipes-fittings', 'electric-conduit-pipes' ),
			'image'    => 'pipes',
			'gallery'  => array( 'pipes', 'thumb-pipes' ),
			'excerpt'  => __( 'Plumbing and electrical conduit pipes with fittings, supplied along with your bricks and cement.', 'brickpoint' ),
			'content'  => __( "Plumbing pipes, electrical conduit pipes and the fittings that go with them — so a single inquiry covers the whole material list.\n\nTell us the sizes and quantities you need and we will confirm availability and rate.", 'brickpoint' ),
			'unit'     => __( 'per piece / per bundle', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-PIP-007',
			'max_order' => __( 'Confirm sizes and quantities on inquiry', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Types', 'brickpoint' ), 'value' => __( 'Plumbing pipes, conduit pipes, fittings', 'brickpoint' ) ),
				array( 'label' => __( 'Sizes', 'brickpoint' ), 'value' => __( 'Common site sizes — confirm on inquiry', 'brickpoint' ) ),
			),
			'features' => __( "Plumbing and electrical items together\nSizes confirmed before dispatch\nOne delivery for the whole list", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for pipes and fittings.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 7,
		),
		array(
			'key'      => 'product-chemicals',
			'title'    => __( 'Construction Chemicals', 'brickpoint' ),
			'cats'     => array( 'construction-chemicals', 'insulation-membrane' ),
			'image'    => 'chemicals',
			'gallery'  => array( 'chemicals' ),
			'excerpt'  => __( 'Waterproofing and admixture products for slabs, bathrooms, roofs and water tanks.', 'brickpoint' ),
			'content'  => __( "Construction chemicals for waterproofing, bonding and admixture use — including products for slabs, roofs, bathrooms and water tanks.\n\nProduct availability changes with stock, so please tell us the application and we will confirm what is available.", 'brickpoint' ),
			'unit'     => __( 'per pack', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-CHM-008',
			'max_order' => __( 'Confirm pack size on inquiry', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Types', 'brickpoint' ), 'value' => __( 'Waterproofing, bonding agents, admixtures', 'brickpoint' ) ),
				array( 'label' => __( 'Applications', 'brickpoint' ), 'value' => __( 'Slabs, roofs, bathrooms, water tanks', 'brickpoint' ) ),
			),
			'features' => __( "Guidance on the right product on request\nSupplied with your other materials\nConfirm pack sizes on inquiry", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for construction chemicals.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 8,
		),
		array(
			'key'      => 'product-pavers',
			'title'    => __( 'Interlocking Pavers and Tiles', 'brickpoint' ),
			'cats'     => array( 'other-construction-materials' ),
			'image'    => 'pavers',
			'gallery'  => array( 'pavers', 'thumb-pavers' ),
			'excerpt'  => __( 'Interlocking pavers, tuff tiles and kerb stones for driveways, streets and yards.', 'brickpoint' ),
			'content'  => __( "Interlocking concrete pavers, tuff tiles and kerb stones for driveways, streets, yards and boundary work.\n\nShare the area and the pattern you want and we will confirm the quantity needed and the rate.", 'brickpoint' ),
			'unit'     => __( 'per square foot', 'brickpoint' ),
			'badge'    => '',
			'sku'      => 'DEMO-PVR-009',
			'max_order' => __( 'Confirm area and quantity on inquiry', 'brickpoint' ),
			'area'     => __( 'Lahore, Kasur and nearby districts (confirm on inquiry)', 'brickpoint' ),
			'specs'    => array(
				array( 'label' => __( 'Material', 'brickpoint' ), 'value' => __( 'Concrete pavers and tuff tiles', 'brickpoint' ) ),
				array( 'label' => __( 'Typical use', 'brickpoint' ), 'value' => __( 'Driveways, streets, yards, kerbs', 'brickpoint' ) ),
			),
			'features' => __( "Quantity calculated from your area\nSupply with bricks and cement\nConfirm rates on inquiry", 'brickpoint' ),
			'whatsapp' => __( 'Assalam-o-Alaikum BrickPoint, I would like a quotation for pavers and tiles.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 9,
		),
	);
}

/**
 * Demo videos (self-hosted demo clips bundled with the theme).
 *
 * @return array<int,array<string,mixed>>
 */
function brickpoint_demo_videos() {
	return array(
		array(
			'key'      => 'video-brick-yard',
			'title'    => __( 'Brick yard walkthrough', 'brickpoint' ),
			'cat'      => 'ss7-bricks',
			'clip'     => 'demo-brick-yard',
			'thumb'    => 'hero-poster',
			'excerpt'  => __( 'Demo clip — replace with your own walkthrough of the brick yard. Record on a phone in landscape, then upload the MP4 in WordPress → Videos.', 'brickpoint' ),
			'featured' => 1,
			'order'    => 1,
		),
		array(
			'key'      => 'video-brick-stacking',
			'title'    => __( 'Brick stacking at the yard', 'brickpoint' ),
			'cat'      => 'brick-manufacturing',
			'clip'     => 'demo-brick-stacking',
			'thumb'    => 'brick-standard',
			'excerpt'  => __( 'Demo clip — replace with your own stacking or production footage.', 'brickpoint' ),
			'featured' => 1,
			'order'    => 2,
		),
		array(
			'key'      => 'video-loading',
			'title'    => __( 'Loading sand for site delivery', 'brickpoint' ),
			'cat'      => 'sand-rait',
			'clip'     => 'demo-loading',
			'thumb'    => 'sand',
			'excerpt'  => __( 'Demo clip — replace with footage of your own loading and dispatch process.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 3,
		),
		array(
			'key'      => 'video-steel',
			'title'    => __( 'Steel stock at the depot', 'brickpoint' ),
			'cat'      => 'steel',
			'clip'     => 'demo-steel',
			'thumb'    => 'steel',
			'excerpt'  => __( 'Demo clip — replace with your own stock or product footage.', 'brickpoint' ),
			'featured' => 0,
			'order'    => 4,
		),
	);
}

/**
 * Demo projects (clearly marked as illustrative).
 *
 * @return array<int,array<string,mixed>>
 */
function brickpoint_demo_projects() {
	$note = __( 'Illustrative construction reference. Demo content — replace with your own project details and photos.', 'brickpoint' );

	return array(
		array(
			'key'      => 'project-grey-structure-house',
			'title'    => __( 'Single-storey house — grey structure', 'brickpoint' ),
			'cat'      => 'residential',
			'cats'     => array( 'residential', 'grey-structure' ),
			'image'    => 'project-house',
			'gallery'  => array( 'project-house', 'brick-standard' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry. Use this space to describe the material supplied, the quantity, the delivery schedule and how the site was coordinated.\n\nThis is an illustrative construction reference, not a claim of supply to any named project, developer or housing society.", 'brickpoint' ),
			'location' => __( 'Lahore District, Punjab (demo entry)', 'brickpoint' ),
			'status'   => __( 'Completed (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Brick walls, slab casting material, plaster materials', 'brickpoint' ),
			'year'     => '2025',
			'order'    => 1,
			'featured' => 1,
		),
		array(
			'key'      => 'project-commercial-frames',
			'title'    => __( 'Commercial site — structure materials', 'brickpoint' ),
			'cats'     => array( 'commercial' ),
			'image'    => 'project-commercial',
			'gallery'  => array( 'project-commercial', 'steel' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry for commercial work: reinforcement, cement and brick supply across construction stages.\n\nReplace this text with your own project scope, quantities and delivery notes.", 'brickpoint' ),
			'location' => __( 'Punjab, Pakistan (demo entry)', 'brickpoint' ),
			'status'   => __( 'In progress (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Steel, cement and brick supply', 'brickpoint' ),
			'year'     => '2025',
			'order'    => 2,
			'featured' => 1,
		),
		array(
			'key'      => 'project-boundary-masonry',
			'title'    => __( 'Boundary wall and masonry works', 'brickpoint' ),
			'cats'     => array( 'grey-structure', 'residential' ),
			'image'    => 'project-boundary',
			'gallery'  => array( 'project-boundary', 'brick-ss7' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry for masonry and boundary wall work: brick supply, stacking space planning and loading schedule.\n\nEdit this entry with your own details.", 'brickpoint' ),
			'location' => __( 'Kasur District, Punjab (demo entry)', 'brickpoint' ),
			'status'   => __( 'Completed (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Brick supply for masonry and boundary walls', 'brickpoint' ),
			'year'     => '2024',
			'order'    => 3,
			'featured' => 0,
		),
		array(
			'key'      => 'project-paving',
			'title'    => __( 'Driveway and paving works', 'brickpoint' ),
			'cats'     => array( 'infrastructure' ),
			'image'    => 'thumb-pavers',
			'gallery'  => array( 'pavers', 'thumb-pavers' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry for paving work: interlocking pavers, tuff tiles and kerb stones supplied to a driveway or street project.\n\nReplace with your own paving project details.", 'brickpoint' ),
			'location' => __( 'Lahore, Punjab (demo entry)', 'brickpoint' ),
			'status'   => __( 'Completed (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Pavers, tuff tiles and kerb stones', 'brickpoint' ),
			'year'     => '2025',
			'order'    => 4,
			'featured' => 0,
		),
		array(
			'key'      => 'project-warehouse',
			'title'    => __( 'Warehouse shell — brickwork', 'brickpoint' ),
			'cats'     => array( 'industrial' ),
			'image'    => 'project-commercial',
			'gallery'  => array( 'project-commercial' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry for an industrial shell: brick infill, cement and aggregate supply.\n\nEdit or delete this entry.", 'brickpoint' ),
			'location' => __( 'Punjab, Pakistan (demo entry)', 'brickpoint' ),
			'status'   => __( 'In progress (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Bricks, cement, crush and sand', 'brickpoint' ),
			'year'     => '2025',
			'order'    => 5,
			'featured' => 0,
		),
		array(
			'key'      => 'project-extension',
			'title'    => __( 'House extension — renovation materials', 'brickpoint' ),
			'cats'     => array( 'renovation', 'residential' ),
			'image'    => 'project-house',
			'gallery'  => array( 'project-house' ),
			'excerpt'  => $note,
			'content'  => __( "Demo project entry for an extension or renovation: bricks, cement, sand and finishing materials in small staged deliveries.\n\nReplace with your own renovation work.", 'brickpoint' ),
			'location' => __( 'Lahore, Punjab (demo entry)', 'brickpoint' ),
			'status'   => __( 'Completed (demo entry)', 'brickpoint' ),
			'scope'    => __( 'Staged material supply for an extension', 'brickpoint' ),
			'year'     => '2024',
			'order'    => 6,
			'featured' => 0,
		),
	);
}

/**
 * Demo blog posts: practical guidance only, no invented company claims.
 *
 * @return array<int,array<string,mixed>>
 */
function brickpoint_demo_posts() {
	return array(
		array(
			'key'       => 'post-brick-quality',
			'title'     => __( 'How to check brick quality before you order', 'brickpoint' ),
			'cat'       => 'Buying Guides',
			'image'     => 'blog-brick-quality',
			'days_ago'  => 6,
			'excerpt'   => __( 'Six quick checks you can do at the yard before a brick load is dispatched to your site.', 'brickpoint' ),
			'content'   => __( "<p>Before a brick load leaves the yard, a few minutes of checking saves a lot of rework on site. These are the checks our team recommends, and they work for both machine-moulded and hand-made bricks.</p>\n<h2>1. Check the size consistency</h2>\n<p>Pick ten bricks at random from different places in the stack and compare their length, width and height. Consistent size means less mortar and straighter walls.</p>\n<h2>2. Look at the edges and corners</h2>\n<p>Sharp, unbroken edges stack better and give a cleaner face. Breakage at the corners usually means rough handling rather than a fault in the clay.</p>\n<h2>3. Listen to the sound</h2>\n<p>Tap two bricks together. A clear ringing sound usually means a well-fired brick; a dull thud can mean under-firing or internal cracks.</p>\n<h2>4. Check for cracks and lime spots</h2>\n<p>Fine hairline cracks or white lime spots can open up later and cause spalling on the face of the wall.</p>\n<h2>5. Confirm the count and the stacking</h2>\n<p>Confirm how many bricks are in a load and how they are stacked for transport. Well-stacked bricks arrive with fewer broken pieces.</p>\n<h2>6. Ask for the current rate in writing</h2>\n<p>Rates, sizes and availability change. Ask for the rate and the loading date over WhatsApp so both sides have a written record.</p>\n<p><em>Demo post — replace this article with your own content in WordPress → Posts.</em></p>", 'brickpoint' ),
		),
		array(
			'key'      => 'post-material-planning',
			'title'    => __( 'Planning material delivery for a grey structure', 'brickpoint' ),
			'cat'      => 'Site Planning',
			'image'    => 'project-house',
			'days_ago' => 13,
			'excerpt'  => __( 'How to sequence bricks, cement, crush and steel deliveries so the site never waits and never blocks.', 'brickpoint' ),
			'content'  => __( "<p>Material planning decides how smoothly a grey structure goes. The goal is simple: nothing on site blocks the work, and nothing sits in the way when the next trade arrives.</p>\n<h2>Start with the sequence</h2>\n<p>Bricks for walls, aggregate and cement for concrete, steel for the frame, sand for masonry and plaster. Each stage needs a place to store material without blocking access.</p>\n<h2>Plan storage space first</h2>\n<p>Bricks and aggregate need ground space. Stack bricks on firm, level ground, and keep aggregate off the mixing area.</p>\n<h2>Match deliveries to the schedule</h2>\n<p>Deliver in the week the material is used, not a month early. Long storage means breakage, moisture and blocked space.</p>\n<h2>Confirm each load on WhatsApp</h2>\n<p>Send the list, quantity and delivery location, and confirm the loading time on the morning of dispatch.</p>\n<p><em>Demo post — replace this article with your own content in WordPress → Posts.</em></p>", 'brickpoint' ),
		),
		array(
			'key'      => 'post-cement-crush-sand',
			'title'    => __( 'Cement, crush and sand: what to confirm before you order', 'brickpoint' ),
			'cat'      => 'Buying Guides',
			'image'    => 'cement',
			'days_ago' => 21,
			'excerpt'  => __( 'Brand, grade, quantity and delivery point — the four things to settle before a bulk order is dispatched.', 'brickpoint' ),
			'content'  => __( "<p>Bulk material orders go wrong for predictable reasons. Settle these four things before the truck is loaded.</p>\n<h2>1. Brand and packing</h2>\n<p>For cement, confirm the brand and bag packing. Brand availability changes with supply, so ask for what is available today.</p>\n<h2>2. Grade of aggregate</h2>\n<p>Confirm the grade of crush you need for your concrete mix and the base layer — they are often different.</p>\n<h2>3. Quantity and how it is measured</h2>\n<p>Confirm whether the rate is per bag, per tonne or per truck load, and how the load is measured at dispatch.</p>\n<h2>4. Delivery point and access</h2>\n<p>Send the exact location and check that the truck can reach the unloading point. Access problems cause the biggest delays.</p>\n<p><em>Demo post — replace this article with your own content in WordPress → Posts.</em></p>", 'brickpoint' ),
		),
		array(
			'key'      => 'post-brick-sizes',
			'title'    => __( 'Brick sizes and where each one is used', 'brickpoint' ),
			'cat'      => 'Construction Tips',
			'image'    => 'brick-ss7',
			'days_ago' => 34,
			'excerpt'  => __( 'A quick guide to the brick sizes used in walls, partition work and facing, and where each one fits.', 'brickpoint' ),
			'content'   => __( "<p>Brick size affects wall thickness, mortar quantity and how many pieces a load contains. These are the common uses.</p>\n<h2>Standard wall bricks</h2>\n<p>Used for load-bearing walls and general masonry. Confirm the current size with your supplier, because size affects how many bricks you need per square foot.</p>\n<h2>Partition and filler bricks</h2>\n<p>Lighter partition work can use thinner bricks where load bearing is not required.</p>\n<h2>Facing bricks</h2>\n<p>Where the brick face stays visible, edge quality and colour consistency matter much more than strength alone.</p>\n<h2>Always confirm before ordering</h2>\n<p>Sizes vary between production units. Send your wall drawing or area to your supplier and confirm the size and the quantity before dispatch.</p>\n<p><em>Demo post — replace this article with your own content in WordPress → Posts.</em></p>", 'brickpoint' ),
		),
	);
}

/**
 * Extra location details filled in for the four BrickPoint locations.
 *
 * @return array<string,array<string,mixed>>
 */
function brickpoint_demo_location_details() {
	return array(
		'Masha Allah Bricks Company – Ram Thaman' => array(
			'image'   => 'brick-standard',
			'content' => __( "Production and supply point for bricks and construction materials at Ram Thaman.\n\nBricks are stacked and loaded here for project deliveries. Please call or WhatsApp before visiting to confirm loading times and stock.", 'brickpoint' ),
			'days'    => 'Mon–Sat',
		),
		'Fine Bricks Company – Raja Jang'   => array(
			'image'   => 'brick-ss7',
			'content' => __( "Bhatta location supplying bricks and bulk construction materials from Raja Jang.\n\nLoading and dispatch are coordinated from here. Confirm quantity and loading time with the sales office before you send a vehicle.", 'brickpoint' ),
			'days'    => 'Mon–Sat',
		),
		'Masha Allah Bricks Company – Sattoki' => array(
			'image'   => 'hero-poster',
			'content' => __( "Brick production location at Sattoki with direct loading for project deliveries.\n\nShare your quantity and delivery location and we will confirm the loading schedule from this site.", 'brickpoint' ),
			'days'    => 'Mon–Sat',
		),
		'BrickPoint Office'                 => array(
			'image'   => 'office',
			'content' => __( "Sales office for quotations, order coordination and delivery scheduling.\n\nThis office handles inquiries; material is loaded from the production locations. Call or WhatsApp before visiting to make sure the right person is available.", 'brickpoint' ),
			'days'    => 'Mon–Sat',
		),
	);
}

/* ------------------------------------------------------------------ engine */

/**
 * Has this demo item already been created? Returns the post ID or 0.
 *
 * @param string $post_type Post type.
 * @param string $key       Demo key.
 * @return int
 */
function brickpoint_demo_find( $post_type, $key ) {
	$found = get_posts(
		array(
			'post_type'        => $post_type,
			'post_status'      => 'any',
			'posts_per_page'   => 1,
			'fields'           => 'ids',
			'no_found_rows'    => true,
			'suppress_filters' => false,
			'meta_query'       => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_bp_demo_key',
					'value' => $key,
				),
			),
		)
	);

	return $found ? (int) $found[0] : 0;
}

/**
 * Import one bundled file into the media library (once).
 *
 * The file is copied out of the theme folder so that deleting the theme, or
 * re-installing it, never breaks the media library entries.
 *
 * @param string $relative Relative path inside assets/demo/.
 * @param string $title    Attachment title.
 * @param string $alt      Alt text (images only).
 * @return int Attachment ID or 0.
 */
function brickpoint_demo_import_file( $relative, $title = '', $alt = '' ) {
	$option = 'brickpoint_demo_file_' . md5( $relative );
	$saved  = (int) get_option( $option );

	if ( $saved && get_post( $saved ) ) {
		return $saved;
	}

	$source = BRICKPOINT_DIR . 'assets/demo/' . $relative;

	if ( ! file_exists( $source ) ) {
		return 0;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$uploads = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return 0;
	}

	$filename = wp_unique_filename( $uploads['path'], basename( $source ) );
	$target   = trailingslashit( $uploads['path'] ) . $filename;

	if ( ! @copy( $source, $target ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		return 0;
	}

	$filetype = wp_check_filetype( $filename, null );
	$title    = $title ? $title : preg_replace( '/\.[^.]+$/', '', basename( $source ) );

	$attachment_id = wp_insert_attachment(
		array(
			'guid'           => trailingslashit( $uploads['url'] ) . $filename,
			'post_mime_type' => $filetype['type'] ? $filetype['type'] : 'application/octet-stream',
			'post_title'     => $title,
			'post_content'   => '',
			'post_status'    => 'inherit',
		),
		$target
	);

	if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
		return 0;
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $target );

	if ( $metadata ) {
		wp_update_attachment_metadata( $attachment_id, $metadata );
	}

	if ( $alt ) {
		update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
	}

	update_post_meta( $attachment_id, '_bp_demo', 1 );
	update_post_meta( $attachment_id, '_bp_demo_key', 'file:' . $relative );
	update_option( $option, (int) $attachment_id, false );

	return (int) $attachment_id;
}

/**
 * Import every bundled demo file.
 *
 * @param array $report Report array (updated by reference).
 * @return array<string,int> key => attachment ID (images and clips together).
 */
function brickpoint_demo_import_media( &$report = null ) {
	$media = array();
	$count = 0;

	foreach ( brickpoint_demo_image_files() as $key => $image ) {
		$id = brickpoint_demo_import_file( $image['file'], $image['title'], $image['alt'] );

		if ( $id ) {
			$media[ $key ] = $id;
			$count++;
		}
	}

	foreach ( brickpoint_demo_video_files() as $key => $video ) {
		$id = brickpoint_demo_import_file( $video['file'], $video['title'], '' );

		if ( $id ) {
			$media[ $key ] = $id;
			$count++;
		}
	}

	if ( is_array( $report ) ) {
		$report['media'] = $count;
	}

	return $media;
}

/**
 * Replace {{IMG_ID:key}} / {{IMG_URL:key}} / {{VID_*}} / {{URL:slug}} placeholders.
 *
 * @param string $json Layout JSON.
 * @param array  $map  Replacement map.
 * @return string
 */
function brickpoint_demo_replace_placeholders( $json, $map ) {
	$json = preg_replace_callback(
		'/"(?:\{\{(IMG_ID|VID_ID):([a-z0-9\-]+)\}\})"/i',
		function ( $matches ) use ( $map ) {
			$group = strtoupper( $matches[1] );
			$key   = $matches[2];

			return isset( $map[ $group ][ $key ] ) ? (string) (int) $map[ $group ][ $key ] : '0';
		},
		$json
	);

	$json = preg_replace_callback(
		'/\{\{(IMG_URL|VID_URL):([a-z0-9\-]+)\}\}/i',
		function ( $matches ) use ( $map ) {
			$group = strtoupper( $matches[1] );
			$key   = $matches[2];

			return isset( $map[ $group ][ $key ] ) ? (string) $map[ $group ][ $key ] : '';
		},
		$json
	);

	$json = preg_replace_callback(
		'/\{\{URL:([a-z0-9\-]+)\}\}/i',
		function ( $matches ) use ( $map ) {
			return isset( $map['URL'][ $matches[1] ] ) ? (string) $map['URL'][ $matches[1] ] : '';
		},
		$json
	);

	return $json;
}

/**
 * Build the placeholder map from the imported media and the site's pages.
 *
 * @param array $media Media map: key => attachment ID.
 * @return array<string,array<string,mixed>>
 */
function brickpoint_demo_placeholder_map( $media ) {
	$map = array(
		'IMG_ID'  => array(),
		'IMG_URL' => array(),
		'VID_ID'  => array(),
		'VID_URL' => array(),
		'URL'     => array(),
	);

	foreach ( $media as $key => $attachment_id ) {
		$url = wp_get_attachment_url( $attachment_id );

		if ( ! $url ) {
			continue;
		}

		if ( isset( brickpoint_demo_video_files()[ $key ] ) ) {
			$map['VID_ID'][ $key ]  = (int) $attachment_id;
			$map['VID_URL'][ $key ] = $url;
		} else {
			$map['IMG_ID'][ $key ]  = (int) $attachment_id;
			$map['IMG_URL'][ $key ] = $url;
		}
	}

	$slugs = array(
		'home',
		'about-us',
		'products',
		'product-categories',
		'ss7-bricks',
		'construction-materials',
		'projects',
		'videos',
		'locations',
		'for-contractors',
		'for-builders',
		'for-construction-companies',
		'contact',
		'blog',
	);

	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );

		if ( $page instanceof WP_Post ) {
			$url = get_permalink( $page );

			if ( $url ) {
				$map['URL'][ $slug ] = $url;
			}
		}
	}

	if ( empty( $map['URL']['products'] ) ) {
		$map['URL']['products'] = home_url( '/products/' );
	}

	if ( empty( $map['URL']['contact'] ) ) {
		$map['URL']['contact'] = home_url( '/contact/' );
	}

	return $map;
}

/**
 * Create one demo product.
 *
 * @param array $product Product definition.
 * @param array $media   Media map.
 * @return int Product ID or 0.
 */
function brickpoint_demo_create_product( $product, $media ) {
	$existing = brickpoint_demo_find( 'bp_product', $product['key'] );

	if ( $existing ) {
		return $existing;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'bp_product',
			'post_status'  => 'publish',
			'post_title'   => $product['title'],
			'post_excerpt' => $product['excerpt'],
			'post_content' => $product['content'],
			'menu_order'   => (int) $product['order'],
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	if ( ! empty( $product['cats'] ) ) {
		wp_set_object_terms( $post_id, $product['cats'], 'bp_product_category' );
	}

	update_post_meta( $post_id, '_bp_price', '' );
	update_post_meta( $post_id, '_bp_price_label', __( 'Rate on request', 'brickpoint' ) );
	update_post_meta( $post_id, '_bp_unit', $product['unit'] );
	update_post_meta( $post_id, '_bp_min_order', $product['max_order'] );
	update_post_meta( $post_id, '_bp_availability', 'on-request' );
	update_post_meta( $post_id, '_bp_badge', $product['badge'] );
	update_post_meta( $post_id, '_bp_sku', $product['sku'] );
	update_post_meta( $post_id, '_bp_delivery_area', $product['area'] );
	update_post_meta( $post_id, '_bp_features', $product['features'] );
	update_post_meta( $post_id, '_bp_whatsapp_msg', $product['whatsapp'] );

	if ( ! empty( $product['specs'] ) ) {
		update_post_meta( $post_id, '_bp_specs', $product['specs'] );
	}

	if ( ! empty( $product['featured'] ) ) {
		update_post_meta( $post_id, '_bp_featured', '1' );
	}

	if ( ! empty( $product['image'] ) && isset( $media[ $product['image'] ] ) ) {
		set_post_thumbnail( $post_id, $media[ $product['image'] ] );
	}

	if ( ! empty( $product['gallery'] ) ) {
		$gallery = array();

		foreach ( $product['gallery'] as $image_key ) {
			if ( isset( $media[ $image_key ] ) ) {
				$gallery[] = (int) $media[ $image_key ];
			}
		}

		if ( $gallery ) {
			update_post_meta( $post_id, '_bp_gallery', implode( ',', $gallery ) );
		}
	}

	brickpoint_demo_mark( $post_id, $product['key'] );

	return (int) $post_id;
}

/**
 * Create one demo video.
 *
 * @param array $video Video definition.
 * @param array $media Media map.
 * @return int Video ID or 0.
 */
function brickpoint_demo_create_video( $video, $media ) {
	$existing = brickpoint_demo_find( 'bp_video', $video['key'] );

	if ( $existing ) {
		return $existing;
	}

	$clip_id  = isset( $media[ $video['clip'] ] ) ? (int) $media[ $video['clip'] ] : 0;
	$thumb_id = isset( $media[ $video['thumb'] ] ) ? (int) $media[ $video['thumb'] ] : 0;
	$duration = '';

	if ( isset( brickpoint_demo_video_files()[ $video['clip'] ] ) ) {
		$duration = brickpoint_demo_video_files()[ $video['clip'] ]['duration'];
	}

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'bp_video',
			'post_status'  => 'publish',
			'post_title'   => $video['title'],
			'post_excerpt' => $video['excerpt'],
			'post_content' => $video['excerpt'],
			'menu_order'   => (int) $video['order'],
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	wp_set_object_terms( $post_id, $video['cat'], 'bp_video_category' );

	update_post_meta( $post_id, '_bp_video_source', 'self' );
	update_post_meta( $post_id, '_bp_video_file', $clip_id );
	update_post_meta( $post_id, '_bp_video_aspect', '16-9' );
	update_post_meta( $post_id, '_bp_video_duration', $duration );
	update_post_meta( $post_id, '_bp_video_order', (int) $video['order'] );

	if ( ! empty( $video['featured'] ) ) {
		update_post_meta( $post_id, '_bp_featured', '1' );
	}

	if ( $thumb_id ) {
		set_post_thumbnail( $post_id, $thumb_id );
	}

	brickpoint_demo_mark( $post_id, $video['key'] );

	return (int) $post_id;
}

/**
 * Create one demo project.
 *
 * @param array $project Project definition.
 * @param array $media   Media map.
 * @return int Project ID or 0.
 */
function brickpoint_demo_create_project( $project, $media ) {
	$existing = brickpoint_demo_find( 'bp_project', $project['key'] );

	if ( $existing ) {
		return $existing;
	}

	$note = __( 'Illustrative construction reference. Demo content — replace with your own project details and photos.', 'brickpoint' );

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'bp_project',
			'post_status'  => 'publish',
			'post_title'   => $project['title'],
			'post_excerpt' => $note,
			'post_content' => $project['content'],
			'menu_order'   => (int) $project['order'],
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	if ( ! empty( $project['cats'] ) ) {
		wp_set_object_terms( $post_id, $project['cats'], 'bp_project_category' );
	}

	update_post_meta( $post_id, '_bp_project_location', $project['location'] );
	update_post_meta( $post_id, '_bp_project_status', $project['status'] );
	update_post_meta( $post_id, '_bp_project_scope', $project['scope'] );
	update_post_meta( $post_id, '_bp_project_year', $project['year'] );
	update_post_meta( $post_id, '_bp_project_disclaimer', $note );
	update_post_meta( $post_id, '_bp_illustrative', '1' );

	if ( ! empty( $project['featured'] ) ) {
		update_post_meta( $post_id, '_bp_featured', '1' );
	}

	if ( ! empty( $project['image'] ) && isset( $media[ $project['image'] ] ) ) {
		set_post_thumbnail( $post_id, $media[ $project['image'] ] );
	}

	if ( ! empty( $project['gallery'] ) ) {
		$gallery = array();

		foreach ( $project['gallery'] as $image_key ) {
			if ( isset( $media[ $image_key ] ) ) {
				$gallery[] = (int) $media[ $image_key ];
			}
		}

		if ( $gallery ) {
			update_post_meta( $post_id, '_bp_project_gallery', implode( ',', $gallery ) );
		}
	}

	brickpoint_demo_mark( $post_id, $project['key'] );

	return (int) $post_id;
}

/**
 * Create one demo blog post (with its category).
 *
 * @param array $post  Post definition.
 * @param array $media Media map.
 * @return int Post ID or 0.
 */
function brickpoint_demo_create_post( $post, $media ) {
	$existing = brickpoint_demo_find( 'post', $post['key'] );

	if ( $existing ) {
		return $existing;
	}

	$date = gmdate( 'Y-m-d H:i:s', time() - ( (int) $post['days_ago'] * DAY_IN_SECONDS ) );

	$post_id = wp_insert_post(
		array(
			'post_type'     => 'post',
			'post_status'   => 'publish',
			'post_title'    => $post['title'],
			'post_excerpt'  => $post['excerpt'],
			'post_content'  => $post['content'],
			'post_date'     => get_date_from_gmt( $date ),
			'post_date_gmt' => $date,
		)
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	$term = term_exists( $post['cat'], 'category' );

	if ( ! $term ) {
		$term = wp_insert_term( $post['cat'], 'category' );
	}

	if ( ! is_wp_error( $term ) && $term ) {
		wp_set_object_terms( $post_id, array( (int) $term['term_id'] ), 'category' );
	}

	if ( ! empty( $post['image'] ) && isset( $media[ $post['image'] ] ) ) {
		set_post_thumbnail( $post_id, $media[ $post['image'] ] );
	}

	brickpoint_demo_mark( $post_id, $post['key'] );

	return (int) $post_id;
}

/**
 * Flag a post as demo content.
 *
 * @param int    $post_id Post ID.
 * @param string $key     Demo key.
 * @return void
 */
function brickpoint_demo_mark( $post_id, $key ) {
	update_post_meta( $post_id, '_bp_demo', 1 );
	update_post_meta( $post_id, '_bp_demo_key', $key );
}

/**
 * Fill in the demo details of the four real locations.
 *
 * Only empty fields are filled, so nothing the owner has edited is replaced.
 *
 * @param array $media Media map.
 * @return int Number of locations updated.
 */
function brickpoint_demo_fill_locations( $media ) {
	$updated  = 0;
	$details  = brickpoint_demo_location_details();
	$defaults = brickpoint_default_options();
	$products = get_posts(
		array(
			'post_type'      => 'bp_product',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'fields'         => 'ids',
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);

	foreach ( brickpoint_default_locations() as $location ) {
		$post_id = brickpoint_post_id_by_title( $location['title'], 'bp_location' );

		if ( ! $post_id ) {
			$post_id = wp_insert_post(
				array(
					'post_type'    => 'bp_location',
					'post_status'  => 'publish',
					'post_title'   => $location['title'],
					'post_excerpt' => $location['excerpt'],
					'menu_order'   => (int) $location['order'],
				)
			);
		}

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}

		$extra = isset( $details[ $location['title'] ] ) ? $details[ $location['title'] ] : array();

		if ( ! get_post_meta( $post_id, '_bp_location_company', true ) ) {
			update_post_meta( $post_id, '_bp_location_company', $location['company'] );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_address', true ) ) {
			update_post_meta( $post_id, '_bp_location_address', $location['address'] );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_map', true ) ) {
			update_post_meta( $post_id, '_bp_location_map', $location['map'] );
		}

		if ( ! empty( $location['coords'] ) && ! get_post_meta( $post_id, '_bp_location_coords', true ) ) {
			update_post_meta( $post_id, '_bp_location_coords', $location['coords'] );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_order', true ) ) {
			update_post_meta( $post_id, '_bp_location_order', (int) $location['order'] );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_phone', true ) && ! empty( $defaults['bp_phone'] ) ) {
			update_post_meta( $post_id, '_bp_location_phone', $defaults['bp_phone'] );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_hours', true ) && ! empty( $extra['days'] ) ) {
			update_post_meta( $post_id, '_bp_location_hours', sprintf( '%s — %s', $extra['days'], __( 'confirm timings on call', 'brickpoint' ) ) );
		}

		if ( ! get_post_meta( $post_id, '_bp_location_directions', true ) ) {
			update_post_meta( $post_id, '_bp_location_directions', __( 'Open the Google Maps link for turn-by-turn directions, or call us before you send a vehicle so loading is ready.', 'brickpoint' ) );
		}

		$current_content = get_post_field( 'post_content', $post_id );

		if ( '' === trim( (string) $current_content ) && ! empty( $extra['content'] ) ) {
			wp_update_post(
				array(
					'ID'           => $post_id,
					'post_content' => $extra['content'],
				)
			);
			update_post_meta( $post_id, '_bp_demo', 1 );
		}

		if ( ! has_post_thumbnail( $post_id ) && ! empty( $extra['image'] ) && isset( $media[ $extra['image'] ] ) ) {
			set_post_thumbnail( $post_id, $media[ $extra['image'] ] );
		}

		if ( $products && ! get_post_meta( $post_id, '_bp_location_products', true ) ) {
			update_post_meta( $post_id, '_bp_location_products', implode( ',', array_map( 'absint', $products ) ) );
		}

		$updated++;
	}

	return $updated;
}

/**
 * Import the demo Elementor layout of one page.
 *
 * A page the owner has already designed with Elementor is never touched.
 *
 * @param string $slug Page slug = layout file name.
 * @param array  $map  Placeholder map.
 * @return bool True when a layout was written.
 */
function brickpoint_demo_apply_layout( $slug, $map ) {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}

	$page = get_page_by_path( $slug );

	if ( ! $page instanceof WP_Post ) {
		return false;
	}

	$file = BRICKPOINT_DIR . 'demo/layouts/' . $slug . '.json';

	if ( ! file_exists( $file ) ) {
		return false;
	}

	$existing = get_post_meta( $page->ID, '_elementor_data', true );
	$ours     = get_post_meta( $page->ID, '_bp_demo_layout', true );

	// Someone else built this page with Elementor: never overwrite it.
	if ( $existing && '' !== trim( (string) $existing ) && '[]' !== trim( (string) $existing ) && ! $ours ) {
		return false;
	}

	$raw = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( ! $raw ) {
		return false;
	}

	$json     = brickpoint_demo_replace_placeholders( $raw, $map );
	$elements = json_decode( $json, true );

	if ( ! is_array( $elements ) || ! $elements ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( ! isset( $plugin->documents ) ) {
		return false;
	}

	$document = $plugin->documents->get( $page->ID );

	if ( ! $document || ! method_exists( $document, 'save' ) ) {
		return false;
	}

	$previous_user = get_current_user_id();

	if ( ! $previous_user ) {
		// Saving a document needs a user with edit rights on the page.
		$admins = get_users(
			array(
				'role'   => 'administrator',
				'number' => 1,
				'fields' => 'ID',
			)
		);

		if ( $admins ) {
			wp_set_current_user( (int) $admins[0] );
		}
	}

	if ( method_exists( $document, 'set_is_built_with_elementor' ) ) {
		$document->set_is_built_with_elementor( true );
	}

	try {
		$document->save( array( 'elements' => $elements ) );
	} catch ( \Throwable $e ) {
		// One page must never take the whole import (or wp-admin) down.
		error_log( '[BrickPoint] demo layout "' . $slug . '" failed: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log

		if ( ! $previous_user ) {
			wp_set_current_user( 0 );
		}

		return false;
	}

	if ( ! $previous_user ) {
		wp_set_current_user( 0 );
	}

	/*
	 * Store the hash of the data Elementor actually saved (not of our source
	 * file - Elementor re-encodes the JSON). Removal can then tell a page we
	 * built apart from a page the owner has since edited.
	 */
	$saved = (string) get_post_meta( $page->ID, '_elementor_data', true );

	update_post_meta( $page->ID, '_bp_demo_layout', $slug );
	update_post_meta( $page->ID, '_bp_demo_layout_hash', md5( $saved ) );
	update_post_meta( $page->ID, '_bp_demo', 1 );

	return true;
}

/**
 * Apply every demo layout.
 *
 * @param array $map    Placeholder map.
 * @param array $report Report array (by reference).
 * @return int Number of pages built.
 */
function brickpoint_demo_apply_layouts( $map, &$report = null ) {
	$built = 0;

	foreach ( array_keys( brickpoint_demo_layout_pages() ) as $slug ) {
		if ( brickpoint_demo_apply_layout( $slug, $map ) ) {
			$built++;
		}
	}

	if ( is_array( $report ) ) {
		$report['layouts'] = $built;
	}

	return $built;
}

/**
 * Pages that ship with a demo Elementor layout.
 *
 * @return array<string,string>
 */
function brickpoint_demo_layout_pages() {
	return array(
		'home'                       => __( 'Home', 'brickpoint' ),
		'about-us'                   => __( 'About Us', 'brickpoint' ),
		'products'                   => __( 'Products', 'brickpoint' ),
		'product-categories'         => __( 'Product Categories', 'brickpoint' ),
		'ss7-bricks'                 => __( 'SS7 Bricks', 'brickpoint' ),
		'construction-materials'     => __( 'Construction Materials', 'brickpoint' ),
		'projects'                   => __( 'Projects', 'brickpoint' ),
		'videos'                     => __( 'Videos', 'brickpoint' ),
		'locations'                  => __( 'Locations', 'brickpoint' ),
		'for-contractors'            => __( 'For Contractors', 'brickpoint' ),
		'for-builders'               => __( 'For Builders', 'brickpoint' ),
		'for-construction-companies' => __( 'For Construction Companies', 'brickpoint' ),
		'contact'                    => __( 'Contact', 'brickpoint' ),
	);
}

/**
 * The demo media that is already on the site, as key => attachment ID.
 *
 * Read back from the attachments themselves (every imported file stores the
 * key it was created from), so it works on any request - not only right after
 * an import.
 *
 * @return array<string,int>
 */
function brickpoint_demo_media_map() {
	$map   = array();
	$files = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => '_bp_demo_key',
					'compare' => 'LIKE',
					'value'   => 'file:',
				),
			),
		)
	);

	foreach ( $files as $attachment_id ) {
		$key = (string) get_post_meta( $attachment_id, '_bp_demo_key', true );

		if ( 0 !== strpos( $key, 'file:' ) ) {
			continue;
		}

		$relative = substr( $key, 5 );
		$name     = basename( $relative );
		$slug     = preg_replace( '/\.[a-z0-9]+$/i', '', $name );

		$map[ $slug ] = (int) $attachment_id;
	}

	return $map;
}

/**
 * Re-apply the shipped demo designs to pages the owner has not edited.
 *
 * Used after a theme update so improvements to the demo layouts (sizes,
 * spacing, colours) reach an existing site. A page whose Elementor data no
 * longer matches the hash we stored has been edited by the owner and is left
 * untouched.
 *
 * @return int Number of pages refreshed.
 */
function brickpoint_refresh_demo_layouts() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return 0;
	}

	$map      = brickpoint_demo_placeholder_map( brickpoint_demo_media_map() );
	$refreshed = 0;

	foreach ( array_keys( brickpoint_demo_layout_pages() ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$applied = get_post_meta( $page->ID, '_bp_demo_layout', true );
		$hash    = get_post_meta( $page->ID, '_bp_demo_layout_hash', true );

		if ( ! $applied || ! $hash ) {
			continue;
		}

		$current = (string) get_post_meta( $page->ID, '_elementor_data', true );

		if ( md5( $current ) !== $hash ) {
			// The owner has edited this page: never overwrite it.
			continue;
		}

		if ( brickpoint_demo_apply_layout( $slug, $map ) ) {
			$refreshed++;
		}
	}

	return $refreshed;
}

/**
 * Drop Elementor's generated CSS so changes to the widget defaults show up.
 *
 * @return void
 */
function brickpoint_clear_elementor_css() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return;
	}

	$plugin = \Elementor\Plugin::$instance;

	if ( isset( $plugin->files_manager ) && method_exists( $plugin->files_manager, 'clear_cache' ) ) {
		$plugin->files_manager->clear_cache();
	}
}

/**
 * Should the demo content be imported automatically?
 *
 * Skipped when the owner removed it before, or when the site opts out with
 * `define( 'BRICKPOINT_SKIP_DEMO_CONTENT', true );` in wp-config.php.
 *
 * @return bool
 */
function brickpoint_demo_auto_allowed() {
	if ( defined( 'BRICKPOINT_SKIP_DEMO_CONTENT' ) && BRICKPOINT_SKIP_DEMO_CONTENT ) {
		return false;
	}

	if ( get_option( 'brickpoint_demo_removed' ) ) {
		return false;
	}

	return true;
}

/**
 * Titles the earlier releases used for their placeholder items.
 *
 * Those items are shipped as drafts and are the reason a site can show the
 * same product or video twice (once from an older release, once from the demo
 * set). They are recognised by title, by the "(add your …)" marker and by the
 * untouched draft state, so nothing written by the owner is ever removed.
 *
 * @return string[]
 */
function brickpoint_normalize_title( $title ) {
	$title = wp_specialchars_decode( (string) $title, ENT_QUOTES );

	// Drop the "(add your video)" / "(add your photos)" marker an older
	// release put on its placeholders.
	$title = preg_replace( '/\((add your [a-z ]+)\)/i', '', $title );
	$title = preg_replace( '/[^a-z0-9]+/i', ' ', $title );

	return trim( strtolower( $title ) );
}

function brickpoint_legacy_starter_titles() {
	return array(
		'bp_product' => array(
			'SS7 Bricks',
			'Clay Bricks (Standard)',
			'Cement',
			'Bajri / Crush',
			'Sand / Rait',
			'Steel (Rebar)',
		),
		'bp_video'   => array(
			'SS7 Bricks – product overview (add your video)',
			'Brick manufacturing process (add your video)',
			'Inside our bhatta (add your video)',
		),
		'bp_project' => array(
			'Residential project (add your photos)',
			'Commercial project (add your photos)',
		),
	);
}

/**
 * Does this item still look exactly like the shipped placeholder?
 *
 * An item counts as untouched when it has no featured image and its content is
 * either empty or identical to its own short description (that is how the
 * earlier releases created their placeholders). Anything else means the owner
 * has written something into it and it is kept.
 *
 * @param WP_Post $candidate Post.
 * @return bool
 */
function brickpoint_is_pristine_starter_item( $candidate ) {
	if ( get_post_thumbnail_id( $candidate->ID ) ) {
		return false;
	}

	$content = trim( wp_strip_all_tags( strip_shortcodes( (string) $candidate->post_content ) ) );
	$excerpt = trim( wp_strip_all_tags( (string) $candidate->post_excerpt ) );

	if ( '' === $content ) {
		return true;
	}

	return $content === $excerpt;
}

/**
 * Remove the leftover placeholder items of earlier releases.
 *
 * Only ever deletes items that
 *   - were NOT created by this importer (no `_bp_demo`),
 *   - are still drafts (or carry an "(add your …)" placeholder title),
 *   - have no featured image,
 *   - and either duplicate a demo item by title or are a known placeholder.
 *
 * @return array<string,int> Number of removed items per post type.
 */
function brickpoint_cleanup_legacy_starter_content() {
	$removed = array();
	$legacy  = brickpoint_legacy_starter_titles();

	foreach ( array( 'bp_product', 'bp_video', 'bp_project' ) as $type ) {
		$removed[ $type ] = 0;

		// Titles that already exist as demo content on this site.
		$demo_titles = array();
		$demo_items  = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_bp_demo',
						'value' => 1,
					),
				),
			)
		);

		foreach ( $demo_items as $item_id ) {
			$key = brickpoint_normalize_title( get_the_title( $item_id ) );

			if ( '' !== $key ) {
				$demo_titles[ $key ] = true;
			}
		}

		$candidates = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => array( 'draft', 'pending', 'publish', 'private' ),
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
			)
		);

		foreach ( $candidates as $candidate ) {
			if ( get_post_meta( $candidate->ID, '_bp_demo', true ) ) {
				continue;
			}

			$title       = trim( (string) $candidate->post_title );
			$key         = brickpoint_normalize_title( $title );
			$placeholder = (bool) preg_match( '/\((add your [a-z ]+)\)/i', $title );
			$known       = in_array( $title, $legacy[ $type ], true );

			if ( ! $placeholder && ! $known ) {
				continue;
			}

			// A draft the owner has started working on is left alone.
			if ( 'draft' !== $candidate->post_status && ! $placeholder ) {
				continue;
			}

			if ( get_post_thumbnail_id( $candidate->ID ) ) {
				continue;
			}

			/*
			 * "Steel (Rebar)" of an older release and the demo "Steel Rebar"
			 * are the same item: compare titles ignoring case, brackets and
			 * punctuation. Keep anything the owner has written into.
			 */
			if ( ! isset( $demo_titles[ $key ] ) && ! $placeholder && ! $known ) {
				continue;
			}

			if ( ! brickpoint_is_pristine_starter_item( $candidate ) ) {
				// The owner has worked on it - leave it alone.
				continue;
			}

			if ( wp_delete_post( $candidate->ID, true ) ) {
				$removed[ $type ]++;
			}
		}
	}

	return $removed;
}

/**
 * Merge duplicate terms that share a slug or a name.
 *
 * Categories are seeded by slug, so a renamed/differently-cased copy created by
 * an older release (or by a plugin) can leave two terms for the same thing.
 * Content is moved to the oldest term, then the duplicates are deleted.
 *
 * @return int Number of terms merged away.
 */
function brickpoint_merge_duplicate_terms() {
	$taxonomies = array( 'bp_product_category', 'bp_video_category', 'bp_project_category' );
	$merged     = 0;

	foreach ( $taxonomies as $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || ! $terms ) {
			continue;
		}

		$groups = array();

		foreach ( $terms as $term ) {
			$slug_key = strtolower( $term->slug );
			$name_key = strtolower( trim( wp_specialchars_decode( $term->name, ENT_QUOTES ) ) );

			$groups[ 'slug:' . $slug_key ][] = $term;
			$groups[ 'name:' . $name_key ][] = $term;
		}

		$handled = array();

		foreach ( $groups as $group ) {
			if ( count( $group ) < 2 ) {
				continue;
			}

			// Oldest term wins (smallest ID).
			usort(
				$group,
				static function ( $a, $b ) {
					return $a->term_id <=> $b->term_id;
				}
			);

			$primary = array_shift( $group );

			foreach ( $group as $duplicate ) {
				if ( isset( $handled[ $duplicate->term_id ] ) ) {
					continue;
				}

				$objects = get_objects_in_term( $duplicate->term_id, $taxonomy );

				if ( ! is_wp_error( $objects ) && $objects ) {
					foreach ( $objects as $object_id ) {
						wp_set_object_terms( (int) $object_id, array( (int) $primary->term_id ), $taxonomy, true );
					}
				}

				// Carry a chosen image over before the duplicate disappears.
				$primary_image = get_term_meta( $primary->term_id, '_bp_term_image', true );
				$dupe_image    = get_term_meta( $duplicate->term_id, '_bp_term_image', true );

				if ( ! $primary_image && $dupe_image ) {
					update_term_meta( $primary->term_id, '_bp_term_image', (int) $dupe_image );
				}

				if ( wp_delete_term( $duplicate->term_id, $taxonomy ) ) {
					$handled[ $duplicate->term_id ] = true;
					$merged++;
				}
			}
		}
	}

	return $merged;
}

/**
 * Give every category that has products a real picture for its card.
 *
 * Uses the category image when one is set and otherwise the featured image of
 * the newest product in that category, so no category tile is ever a grey box.
 *
 * @param array $media Placeholder map (unused, kept for signature symmetry).
 * @return int Number of terms that received an image.
 */
function brickpoint_demo_assign_term_images( $media = array() ) {
	$assigned = 0;

	foreach ( array( 'bp_product_category' => 'bp_product', 'bp_video_category' => 'bp_video', 'bp_project_category' => 'bp_project' ) as $taxonomy => $post_type ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || ! $terms ) {
			continue;
		}

		foreach ( $terms as $term ) {
			if ( get_term_meta( $term->term_id, '_bp_term_image', true ) ) {
				continue;
			}

			$items = get_posts(
				array(
					'post_type'      => $post_type,
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'no_found_rows'  => true,
					'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'term_id',
							'terms'    => $term->term_id,
						),
					),
				)
			);

			if ( ! $items ) {
				continue;
			}

			$thumb_id = (int) get_post_thumbnail_id( $items[0] );

			if ( $thumb_id ) {
				update_term_meta( $term->term_id, '_bp_term_image', $thumb_id );
				$assigned++;
			}
		}
	}

	return $assigned;
}

/**
 * Take the import lock.
 *
 * Two requests can start the import at the same time on a fresh install: the
 * page view that notices the new theme, and the wp-cron loopback request that
 * WordPress fires from it. Both would then create the same items. The lock
 * makes sure only one import runs; the other one returns immediately.
 *
 * @return bool True when the lock was taken (the caller must release it).
 */
function brickpoint_demo_lock_acquire() {
	$now  = time();
	$lock = get_option( 'brickpoint_demo_lock' );

	if ( $lock ) {
		// A lock older than 10 minutes belongs to a request that died.
		if ( $now - (int) $lock < 600 ) {
			return false;
		}

		delete_option( 'brickpoint_demo_lock' );
	}

	$taken = add_option( 'brickpoint_demo_lock', $now, '', false );

	if ( $taken ) {
		// Always give the lock back, even if the import hits a fatal error.
		register_shutdown_function( 'brickpoint_demo_lock_release' );
	}

	return $taken;
}

/**
 * Release the import lock.
 *
 * @return void
 */
function brickpoint_demo_lock_release() {
	delete_option( 'brickpoint_demo_lock' );
}

/**
 * Delete surplus demo items that share a demo key.
 *
 * Only ever touches items this importer created (`_bp_demo`), keeps the oldest
 * copy of each key, and never deletes locations.
 *
 * @return int Number of items removed.
 */
function brickpoint_demo_dedupe() {
	$removed = 0;

	foreach ( array( 'bp_product', 'bp_video', 'bp_project', 'post' ) as $type ) {
		$items = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'orderby'        => 'ID',
				'order'          => 'ASC',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_bp_demo',
						'value' => 1,
					),
				),
			)
		);

		$kept = array();

		foreach ( $items as $item ) {
			$key = (string) get_post_meta( $item->ID, '_bp_demo_key', true );
			$key = $key ? $key : 'id:' . $item->ID;

			if ( isset( $kept[ $key ] ) ) {
				if ( wp_delete_post( $item->ID, true ) ) {
					$removed++;
				}

				continue;
			}

			$kept[ $key ] = $item->ID;
		}
	}

	return $removed;
}

/**
 * Import the full demo set.
 *
 * Safe to run repeatedly: existing items are detected by their demo key and
 * left alone, and a page the owner has built with Elementor is never touched.
 *
 * @param array $args Optional: array( 'time_budget' => 25 ).
 * @return array<string,int|string> Report.
 */
function brickpoint_import_demo_content( $args = array() ) {
	if ( ! brickpoint_demo_lock_acquire() ) {
		// Another request is already importing (or has just finished).
		$report = get_option( 'brickpoint_demo_report' );

		return is_array( $report ) ? $report : array( 'locked' => 1 );
	}

	$args = wp_parse_args(
		$args,
		array(
			'time_budget' => 25,
			'force'       => false,
		)
	);

	$started = microtime( true );

	$report = array(
		'media'      => 0,
		'products'   => 0,
		'videos'     => 0,
		'projects'   => 0,
		'posts'      => 0,
		'locations'  => 0,
		'layouts'    => 0,
		'partial'    => 0,
		'time'       => 0,
	);

	// 1. The site skeleton everything else attaches to.
	if ( function_exists( 'brickpoint_register_post_types' ) ) {
		brickpoint_register_post_types();
	}

	if ( function_exists( 'brickpoint_register_taxonomies' ) ) {
		brickpoint_register_taxonomies();
	}

	brickpoint_create_required_pages();
	brickpoint_seed_taxonomy_terms( true );

	// 2. Media.
	$media = brickpoint_demo_import_media( $report );

	if ( microtime( true ) - $started > $args['time_budget'] ) {
		$report['partial'] = 1;
	}

	// 3. Content.
	foreach ( brickpoint_demo_products() as $product ) {
		if ( brickpoint_demo_create_product( $product, $media ) ) {
			$report['products']++;
		}
	}

	foreach ( brickpoint_demo_videos() as $video ) {
		if ( brickpoint_demo_create_video( $video, $media ) ) {
			$report['videos']++;
		}
	}

	foreach ( brickpoint_demo_projects() as $project ) {
		if ( brickpoint_demo_create_project( $project, $media ) ) {
			$report['projects']++;
		}
	}

	foreach ( brickpoint_demo_posts() as $post ) {
		if ( brickpoint_demo_create_post( $post, $media ) ) {
			$report['posts']++;
		}
	}

	$report['locations'] = brickpoint_demo_fill_locations( $media );

	// Category cards always show a real picture (never an empty grey tile).
	$report['term_images'] = brickpoint_demo_assign_term_images( $media );

	if ( microtime( true ) - $started > $args['time_budget'] ) {
		$report['partial'] = 1;
	}

	// 3b. Clean the site up before adding to it: drop the leftover placeholder
	// items of older releases, duplicates from an earlier race, and any
	// duplicate categories.
	$legacy = brickpoint_cleanup_legacy_starter_content();

	if ( array_sum( $legacy ) ) {
		$report['legacy_removed'] = array_sum( $legacy );
	}

	$deduped = brickpoint_demo_dedupe();

	if ( $deduped ) {
		$report['deduped'] = $deduped;
	}

	$merged_terms = brickpoint_merge_duplicate_terms();

	if ( $merged_terms ) {
		$report['terms_merged'] = $merged_terms;
	}

	// 4. Editable page designs (Elementor). Kept last because it is the step
	// that makes the pages look finished.
	$map = brickpoint_demo_placeholder_map( $media );

	try {
		brickpoint_demo_apply_layouts( $map, $report );
	} catch ( \Throwable $e ) {
		error_log( '[BrickPoint] demo layouts stopped: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	/*
	 * The theme works on plain WordPress: without Elementor there are no page
	 * designs to apply yet. Remember it, so the designs are added automatically
	 * the first time Elementor is active on the site.
	 */
	if ( did_action( 'elementor/loaded' ) ) {
		delete_option( 'brickpoint_demo_layouts_pending' );
	} else {
		update_option( 'brickpoint_demo_layouts_pending', 1 );
	}

	// 5. Hero video + poster, only when the owner has not chosen their own.
	if ( ! get_theme_mod( 'bp_hero_video' ) && isset( $map['VID_URL']['demo-brick-yard'] ) ) {
		set_theme_mod( 'bp_hero_video', $map['VID_URL']['demo-brick-yard'] );
	}

	if ( ! get_theme_mod( 'bp_hero_poster' ) && isset( $map['IMG_URL']['hero-video-poster'] ) ) {
		set_theme_mod( 'bp_hero_poster', $map['IMG_URL']['hero-video-poster'] );
	}

	// 6. Menus and permalinks so the new pages resolve.
	if ( function_exists( 'brickpoint_seed_menus' ) ) {
		brickpoint_seed_menus();
	}

	update_option( 'brickpoint_demo_imported', BRICKPOINT_VERSION );
	update_option( 'brickpoint_demo_report', $report, false );

	$report['time'] = round( microtime( true ) - $started, 1 );

	set_transient( 'brickpoint_demo_notice', $report, 300 );

	brickpoint_demo_lock_release();

	return $report;
}

/**
 * Remove everything the demo importer created.
 *
 * Pages, menus and the site structure stay (they are the site skeleton); only
 * the demo items, their images and the demo page designs are removed. A page
 * the owner has edited in Elementor since import keeps its design.
 *
 * @return array<string,int> Report.
 */
function brickpoint_remove_demo_content() {
	$report = array(
		'posts'          => 0,
		'media'          => 0,
		'layouts'        => 0,
		'kept'           => 0,
		'locations_kept' => 0,
		'legacy_removed' => 0,
		'deduped'        => 0,
		'terms_merged'   => 0,
		'refreshed'      => 0,
		'term_images'    => 0,
	);

	// 1. Page designs.
	foreach ( array_keys( brickpoint_demo_layout_pages() ) as $slug ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		$layout = get_post_meta( $page->ID, '_bp_demo_layout', true );

		if ( ! $layout ) {
			continue;
		}

		$stored_hash = (string) get_post_meta( $page->ID, '_bp_demo_layout_hash', true );
		$current     = (string) get_post_meta( $page->ID, '_elementor_data', true );

		if ( $stored_hash && md5( $current ) !== $stored_hash ) {
			// The owner has edited this page: keep the design, drop our marker.
			delete_post_meta( $page->ID, '_bp_demo_layout' );
			delete_post_meta( $page->ID, '_bp_demo_layout_hash' );
			delete_post_meta( $page->ID, '_bp_demo' );
			$report['kept']++;
			continue;
		}

		delete_post_meta( $page->ID, '_elementor_data' );
		delete_post_meta( $page->ID, '_elementor_edit_mode' );
		delete_post_meta( $page->ID, '_elementor_version' );
		delete_post_meta( $page->ID, '_bp_demo_layout' );
		delete_post_meta( $page->ID, '_bp_demo_layout_hash' );
		delete_post_meta( $page->ID, '_bp_demo' );
		$report['layouts']++;
	}

	// 2. Demo items. Locations are never deleted: they are the real business
	// locations (with their real Google Maps links) and are only ever filled
	// in, so removal just drops the "demo" marker.
	$types = array( 'bp_product', 'bp_video', 'bp_project', 'post' );

	foreach ( $types as $type ) {
		$items = get_posts(
			array(
				'post_type'      => $type,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => '_bp_demo',
						'value' => 1,
					),
				),
			)
		);

		foreach ( $items as $item_id ) {
			wp_delete_post( $item_id, true );
			$report['posts']++;
		}
	}

	// 2b. Locations: keep everything, just forget the demo marker (and a demo
	// featured image that no longer exists).
	$demo_locations = get_posts(
		array(
			'post_type'      => 'bp_location',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_bp_demo',
					'value' => 1,
				),
			),
		)
	);

	foreach ( $demo_locations as $location_id ) {
		delete_post_meta( $location_id, '_bp_demo' );
		delete_post_meta( $location_id, '_bp_demo_key' );
		$report['locations_kept'] = isset( $report['locations_kept'] ) ? $report['locations_kept'] + 1 : 1;
	}

	// 3. Demo attachments (only those imported by the importer).
	$attachments = get_posts(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_bp_demo',
					'value' => 1,
				),
			),
		)
	);

	foreach ( $attachments as $attachment_id ) {
		if ( wp_delete_attachment( $attachment_id, true ) ) {
			$report['media']++;
		}
	}

	// Forget the "already imported" markers so a later import starts clean.
	foreach ( array_merge( array_keys( brickpoint_demo_image_files() ), array_keys( brickpoint_demo_video_files() ) ) as $key ) {
		delete_option( 'brickpoint_demo_file_' . md5( $key . '' ) );
	}

	foreach ( brickpoint_demo_image_files() as $image ) {
		delete_option( 'brickpoint_demo_file_' . md5( $image['file'] ) );
	}

	foreach ( brickpoint_demo_video_files() as $video ) {
		delete_option( 'brickpoint_demo_file_' . md5( $video['file'] ) );
	}

	delete_option( 'brickpoint_demo_report' );
	delete_option( 'brickpoint_demo_imported' );
	update_option( 'brickpoint_demo_removed', time(), false );

	return $report;
}

/**
 * IDs of every demo item of a post type.
 *
 * @param string $type Post type.
 * @return int[]
 */
function brickpoint_demo_ids( $type ) {
	return get_posts(
		array(
			'post_type'      => $type,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
			'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'   => '_bp_demo',
					'value' => 1,
				),
			),
		)
	);
}

/**
 * Count the demo items currently on the site.
 *
 * @return array<string,int>
 */
function brickpoint_demo_counts() {
	$counts = array(
		'products'  => 0,
		'videos'    => 0,
		'projects'  => 0,
		'posts'     => 0,
		'locations' => 0,
		'media'     => 0,
	);

	$map = array(
		'products'  => 'bp_product',
		'videos'    => 'bp_video',
		'projects'  => 'bp_project',
		'posts'     => 'post',
		'locations' => 'bp_location',
	);

	foreach ( $map as $label => $type ) {
		$counts[ $label ] = count( brickpoint_demo_ids( $type ) );
	}

	$counts['media'] = count( brickpoint_demo_ids( 'attachment' ) );

	return $counts;
}

/* ------------------------------------------------------------------- admin */

/**
 * Show a "Demo" badge in the WordPress list screens for demo items.
 *
 * @param array  $columns   Columns.
 * @param string $post_type Post type.
 * @return array
 */
function brickpoint_demo_column( $columns, $post_type = '' ) {
	if ( ! in_array( $post_type, array( 'bp_product', 'bp_video', 'bp_project', 'bp_location', 'post' ), true ) ) {
		return $columns;
	}

	$columns['bp_demo'] = __( 'Demo', 'brickpoint' );

	return $columns;
}
add_filter( 'manage_posts_columns', 'brickpoint_demo_column', 10, 2 );

/**
 * Render the "Demo" badge.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 * @return void
 */
function brickpoint_demo_column_content( $column, $post_id ) {
	if ( 'bp_demo' !== $column ) {
		return;
	}

	if ( get_post_meta( $post_id, '_bp_demo', true ) ) {
		echo '<span class="bp-demo-badge">' . esc_html__( 'Demo', 'brickpoint' ) . '</span>';
	} else {
		echo '&mdash;';
	}
}
add_action( 'manage_posts_custom_column', 'brickpoint_demo_column_content', 10, 2 );

/**
 * Notice after an import, telling the owner exactly what happened and how to
 * remove it again.
 *
 * @return void
 */
function brickpoint_demo_notice() {
	$report = get_transient( 'brickpoint_demo_notice' );

	if ( ! $report || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	delete_transient( 'brickpoint_demo_notice' );

	$setup_url = admin_url( 'admin.php?page=brickpoint-setup' );

	printf(
		'<div class="notice notice-success is-dismissible"><p><strong>%1$s</strong> %2$s</p><p><a href="%3$s" class="button button-secondary">%4$s</a></p></div>',
		esc_html__( 'Demo content installed.', 'brickpoint' ),
		sprintf(
			/* translators: 1: products, 2: videos, 3: projects, 4: posts, 5: pages, 6: media files. */
			esc_html__( 'Products: %1$d, videos: %2$d, projects: %3$d, blog posts: %4$d, page designs built with Elementor: %5$d, media files: %6$d. Everything is marked "Demo" in the WordPress lists — edit or replace it whenever you like.', 'brickpoint' ),
			(int) $report['products'],
			(int) $report['videos'],
			(int) $report['projects'],
			(int) $report['posts'],
			(int) $report['layouts'],
			(int) $report['media']
		),
		esc_url( $setup_url ),
		esc_html__( 'Open Setup & Content', 'brickpoint' )
	);
}
add_action( 'admin_notices', 'brickpoint_demo_notice' );

/**
 * Flush the rewrite rules on the next admin request when the theme asks for it.
 *
 * A safety net for the case where rules were generated in the same request that
 * created the pages: the stored rules are then rebuilt in a later request, when
 * the new pages are already visible.
 *
 * @return void
 */
function brickpoint_run_pending_flush() {
	if ( ! get_option( 'brickpoint_flush_needed' ) ) {
		return;
	}

	delete_option( 'brickpoint_flush_needed' );

	// Runs once per install. Front end included: until the rules are rebuilt the
	// new pages (Projects, Videos, Locations ...) would still be answered by the
	// matching archive, so the site must fix itself on the first page view.
	flush_rewrite_rules();
}
add_action( 'init', 'brickpoint_run_pending_flush', 20 );

/**
 * Version upgrade routine: runs the setup and the demo import once after a
 * theme update, so an existing site gets the new pages, designs and videos
 * without the owner clicking anything.
 *
 * @return void
 */
function brickpoint_maybe_upgrade() {
	if ( ! is_admin() || ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$installed = (string) get_option( 'brickpoint_version' );

	if ( $installed === BRICKPOINT_VERSION ) {
		return;
	}

	try {
		if ( function_exists( 'brickpoint_run_activation_setup' ) ) {
			brickpoint_run_activation_setup();
		}

		if ( brickpoint_demo_auto_allowed() ) {
			brickpoint_import_demo_content();
		}

		// Improvements to the shipped page designs (and to the widget defaults
		// they use) reach a site that already has the demo content, without
		// touching a page the owner has edited.
		brickpoint_refresh_demo_layouts();
		brickpoint_clear_elementor_css();
	} catch ( \Throwable $e ) {
		error_log( '[BrickPoint] upgrade routine stopped: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	update_option( 'brickpoint_version', BRICKPOINT_VERSION );
}
add_action( 'admin_init', 'brickpoint_maybe_upgrade', 5 );

/**
 * Add the demo page designs as soon as Elementor is available.
 *
 * Runs after an install where Elementor was not active yet (the theme also
 * works without Elementor). Pages the owner has built or edited are never
 * touched - see brickpoint_demo_apply_layout().
 *
 * @return void
 */
function brickpoint_maybe_apply_pending_layouts() {
	if ( ! get_option( 'brickpoint_demo_layouts_pending' ) ) {
		return;
	}

	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	if ( ! brickpoint_demo_auto_allowed() ) {
		delete_option( 'brickpoint_demo_layouts_pending' );

		return;
	}

	try {
		brickpoint_demo_apply_layouts( brickpoint_demo_placeholder_map( brickpoint_demo_media_map() ) );
		brickpoint_clear_elementor_css();
	} catch ( \Throwable $e ) {
		error_log( '[BrickPoint] pending page designs stopped: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
	}

	delete_option( 'brickpoint_demo_layouts_pending' );
}
add_action( 'admin_init', 'brickpoint_maybe_apply_pending_layouts', 6 );
