=== BrickPoint ===

Contributors: brickpoint
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Version: 1.0.6
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: brickpoint
Tags: business, construction, one-column, two-columns, custom-logo, custom-menu, featured-images, footer-widgets, block-styles, wide-blocks, translation-ready, threaded-comments

A complete construction-materials theme: bricks, cement, sand, crush, steel,
projects, videos and locations - with WhatsApp inquiries instead of a cart.

== Description ==

BrickPoint is a WordPress theme built for brick kilns and construction-material
suppliers. It ships its own content types so the site works with plain WordPress
and no page builder, and it adds deep Elementor support (widgets + Theme Builder
locations) for owners who want to design pages visually.

= What is included =

* Products (bp_product) with categories, gallery, specifications, features,
  price + price label, unit, availability, badge, SKU/reference, brochure (PDF),
  video, related products and a per-product WhatsApp message.
* Product categories (bp_product_category) with icon, short description, image,
  banner, category video and a "show on homepage" toggle.
* Videos (bp_video) with categories, thumbnail, self-hosted MP4 / YouTube /
  Vimeo / external URL sources, duration, captions, related products, projects
  and locations, plus a lightbox player.
* Projects (bp_project) with categories, location, status, scope, year,
  gallery, video, linked products and an optional illustrative-reference label.
* Locations (bp_location) with company, address, phone, opening hours, real
  Google Maps + directions links, optional video and linked products. Shipped
  with the BrickPoint locations (Masha Allah Bricks - Ram Thaman, Fine Bricks -
  Raja Jang, Masha Allah Bricks - Sattoki, Office).
* Demo content: photos and short demo clips are bundled with the theme and a
  complete demo set (products, videos, projects, blog posts, location details
  and Elementor designs for the main pages) can be installed with one click in
  BrickPoint -> Setup & Content. Every imported item carries a "Demo" badge in
  the WordPress list screens and can be removed again with one click.
* Blog, archives, search, breadcrumbs, comments and a 404 template.
* Contact / quotation form (stored in the WordPress admin + optional email).
  No WooCommerce: ordering happens on WhatsApp with a pre-filled message.
* Elementor: 17 BrickPoint widgets plus Theme Builder locations for the header,
  footer, single product, product archive, single video, video archive, single
  project and project archive.

= Design tokens =

Ink #0e0f11, brick #c1440e, accent #e2571e, sand #f5f1ea, text #2b2f36,
muted #6b7078. Headings use Barlow Condensed, body text uses Inter. All of these
can be changed in Appearance -> Customize -> BrickPoint -> Design.

== Installation ==

1. In WordPress go to Appearance -> Themes -> Add New -> Upload Theme.
2. Choose brickpoint.zip, click Install Now, then Activate.
3. The activation screen offers a guided setup:
   - Create the required pages (Home, About Us, Products, Product Categories,
     SS7 Bricks, Construction Materials, Projects, For Contractors,
     For Builders, For Construction Companies, Blog, Contact, Privacy Policy,
     Terms and Conditions).
   - Create the product / video / project categories.
   - Add the BrickPoint locations (with the real Google Maps links).
   - Create editable starter content (draft products and videos - replace the
     text with your own).
   - Re-save permalinks.
4. Appearance -> Customize -> BrickPoint: set your logo, phone, WhatsApp number,
   email, address, opening hours, social links, hero content and hero video.
5. Appearance -> Menus: the Primary, Top Bar and three footer menus are created
   and assigned automatically - edit the items to match your business.
6. Products -> Add New (and Videos / Projects / Locations) to publish content.

Full step-by-step documentation is inside WordPress: BrickPoint -> Help & Docs.

== Frequently Asked Questions ==

= Do I need Elementor? =

No. Every page has a designed template and the theme works on plain WordPress.
Elementor (free) unlocks the 17 BrickPoint widgets. Elementor Pro is required
only for the Theme Builder features (header, footer and the single/archive
templates). Without Elementor Pro the theme renders its own header, footer and
templates.

= Do I need WooCommerce? =

No, and BrickPoint deliberately does not include a cart, checkout or payments.
Every product and category page has WhatsApp buttons that open a chat with a
pre-filled message, plus call buttons and a quotation form.

= Where are the phone number and social links? =

Appearance -> Customize -> BrickPoint -> Brand / Social / WhatsApp. Nothing is
hard-coded in the templates, so you can change the number, address and links
without touching code.

= How do I change the fonts and colours? =

Appearance -> Customize -> BrickPoint -> Design. Google Fonts can also be
switched off completely for privacy or performance.

= How do I add my own hero video? =

Appearance -> Customize -> BrickPoint -> Hero. Upload an MP4 (recommended) or
paste a YouTube / Vimeo URL, and set a poster image. The video is muted, looped,
lazy and never blocks the first paint. It is disabled automatically for visitors
who prefer reduced motion.

= How do I edit the product page layout? =

Either edit the theme templates in a child theme, or (with Elementor Pro) build
a Single Product template in Elementor -> Theme Builder and assign it to
Products.

== Shortcodes ==

[bp_products]           Product grid.  category="ss7-bricks" featured="1" limit="6" columns="3" orderby="date"
[bp_product_categories] Category cards. limit="12" columns="4" home_only="0" style="card"
[bp_videos]             Video grid.    category="product-videos" limit="9" columns="3" featured="1"
[bp_projects]           Project grid.  category="residential" limit="6" columns="3"
[bp_locations]          Location cards with Maps links. show_video="1"
[bp_contact_form]       Inquiry form.  heading="Request a Quote"
[bp_whatsapp]           WhatsApp button. label="Order on WhatsApp" message="Hello BrickPoint"
[bp_video id="123"]     Inline video player (lazy, poster, captions). url="https://youtu.be/..."
[bp_stats items="Years::15::factory|Kilns::3::brick"]  Feature/stat row.

== Copyright ==

BrickPoint WordPress Theme, (C) 2026 BrickPoint.
BrickPoint is distributed under the terms of the GNU GPL v2 or later.

Barlow Condensed and Inter are licensed under the SIL Open Font License 1.1 and
are loaded from Google Fonts (can be disabled in the Customizer).

All screenshots and images bundled with the theme are for
demonstration/documentation purposes only and are licensed GPL-compatible.

The theme embeds no tracking, no analytics and no external requests other than
the optional Google Fonts stylesheet and video embeds you add yourself.

== Changelog ==

= 1.0.4 =
* New: the placeholder items of the earlier releases (the draft products, videos
  and projects created before the demo set existed) are removed automatically
  when this update is installed, so Products and the category lists no longer
  show anything twice. Only untouched placeholders are removed - anything with a
  photo or with text written by the owner is always kept.
* New: categories that have no image of their own now use the photo of the
  newest item in that category, so no category card is ever an empty grey box.
* Fixed: the hero video card was rendered very small (the widget default was
  38% of its column). It now fills its column, keeps a 16:10 box and can still
  be resized from Elementor (Video width / Video min height).
* Fixed: the SS7 brick in the hero could sit on top of the "Request a Quote"
  button on desktop. The brick now belongs to the grid, is anchored inside the
  hero and is measured so it can never cover the heading, the buttons or the
  video controls - in any of the three video layouts (corner, inline,
  background).
* Fixed: a global media rule (images, videos, embeds are capped to their
  container; card media uses object-fit: cover; content images keep their own
  ratio; inline videos keep a 16:9 box) so no picture or clip can overflow or
  distort anywhere on the site.
* Fixed: the desktop header kept wrapping the menu onto a second line. The
  header bar now uses the full viewport width and the menu spacing adapts, so
  the whole menu stays on one line on desktop; nothing is hidden at any width.
* Fixed: a category name containing "&" was shown as "&amp;" (WordPress escapes
  ampersands in term names; the theme now decodes them once on read).
* Improved: the demo page designs are re-applied automatically after an update
  (only on pages the owner has not edited), and Elementor's generated CSS is
  cleared, so design fixes reach an existing site without manual work.

= 1.0.3 =
* New: a complete, clearly-labelled demo set that is installed automatically
  after this update (and can be imported or removed any time from
  BrickPoint -> Setup & Content -> step 7):
  - 9 demo products with photos, galleries, specifications, highlights and
    WhatsApp messages,
  - 4 demo videos using self-hosted demo clips bundled with the theme (so the
    Videos page and grids have something to play immediately),
  - 6 demo project entries, each marked "Illustrative construction reference",
  - 4 demo blog posts with featured images and categories,
  - location details and photos for the four real BrickPoint locations,
  - 19 demo photos imported into the media library.
* New: every main page ships with a ready-made Elementor design (Home, About,
  Products, Product Categories, SS7 Bricks, Construction Materials, Projects,
  Videos, Locations, For Contractors, For Builders, For Construction Companies
  and Contact). Opening any of those pages in the Elementor editor now shows
  real, editable sections instead of an empty canvas.
* New: three additional pages - Projects, Videos and Locations - with working
  grids, so /projects/, /videos/ and /locations/ always show content. Menus,
  buttons and breadcrumbs point at the page when it exists and fall back to the
  archive otherwise.
* New: "Demo" badge column in the Products, Videos, Projects, Locations and
  Posts list screens, a blog-posts card and one-click "Add new" cards on the
  BrickPoint dashboard.
* Elementor pages are no longer wrapped in the theme's 1240px container, so
  full-width hero and dark sections stretch edge to edge.
* Fixed a slug-collision follow-up: /projects/, /videos/ and /locations/ pages
  now take precedence over the matching archives everywhere (menus, "All"
  filter chips, homepage links).
* Nothing is ever overwritten: demo items are created only when they are
  missing, and a page you have already designed with Elementor is left alone.
* Fixed: BrickPoint widgets could stop the Elementor editor or the page save
  with "Cannot add a control outside of a section". The shared style helpers now
  open a section on demand, so all 17 widgets register their controls cleanly.
* Fixed (fresh installs): /products/, /projects/, /videos/ and /locations/ used
  to be answered by the matching archive after activation, because the pages are
  created in the same request that already cached the archive permalinks. The
  content types are now re-registered and their stale archive permastruct is
  dropped before the rewrite rules are rebuilt, and the rebuild itself runs on
  the first front-end page view when it is still pending - so the designed pages
  always win, with no trip to Settings -> Permalinks.
* Fixed: the demo import takes a lock, so two simultaneous requests (the first
  page view plus the wp-cron loopback) can no longer import the demo twice; a
  de-duplication pass cleans up any surplus items from an earlier attempt.
* Fixed: the untouched WordPress "Hello world!" post and "Sample Page" are moved
  to the trash during setup, so the site starts on BrickPoint content.
* Fixed: the Customizer's editor style is printed through the WordPress style
  API instead of being echoed, which removes the "Cannot modify header
  information" warnings from admin requests (REST, admin-ajax and the editor).
* Fixed: the header can no longer push the page wider than the viewport on
  1280-1439px screens; menu items shrink and wrap instead. No horizontal
  scrolling at 320/375/390/414/480/768/820/1024/1280/1366/1440/1600/1920.
* Fixed: scroll-reveal content can no longer stay invisible - items on screen
  are shown immediately and everything is revealed on print and when the tab is
  hidden (previews, screenshots, background tabs).
* The demo layout importer isolates every page save, so one failing page cannot
  abort the import or the admin request.

= 1.0.2 =
* Critical fix: the custom post types no longer pass a `capabilities` array to
  register_post_type(). That array re-registered WordPress primitive caps
  ("read", "edit_posts", "delete_posts") as post-type meta caps, so every
  `current_user_can( 'read' )` check ran map_meta_cap( 'read_post' ) without a
  post ID and returned "do_not_allow". Result on affected sites: the admin menu
  disappeared, wp-admin showed "Sorry, you are not allowed to access this page.",
  the Elementor editor stayed on the loading spinner and add/edit/delete buttons
  were missing. Fixed by using the standard `capability_type => 'post'` +
  `map_meta_cap => true` (the WordPress defaults).
* Fixed a second Elementor problem: opening the Elementor editor on a page that
  was still empty set `_elementor_edit_mode` but saved no data, which made the
  front page render an empty content area. The front page now checks for real
  content (`brickpoint_page_has_content()`) and still renders the designed
  homepage sections until something is actually built.
* Fixed the Elementor editor hanging on the Home page in general: while the
  editor preview is open the front page renders through `the_content()` so
  Elementor finds its container element (`brickpoint_is_elementor_preview()`).
* Fixed a slug collision: a post type archive used to shadow a page with the
  same slug (`/products/` rendered the product archive instead of the Products
  page, which also made "Edit with Elementor" fail on that page). An archive now
  steps aside when a published page already uses its slug.
* Front page links to Videos / Locations fall back to the pages as well.

= 1.0.1 =
* Safety hardening: every theme module loads inside its own try/catch, a dashboard
  notice lists damaged files, a self-contained fallback page replaces the
  "critical error" screen, `BRICKPOINT_SAFE_MODE` in wp-config.php skips the theme
  completely and activation is wrapped in a try/catch.

= 1.0.0 =
* First release: products, categories, videos, projects, locations, WhatsApp
  inquiries, contact form, blog templates, Elementor widgets and Theme Builder
  locations, Customizer settings, admin dashboard and guided setup.
