<?php

/**
 * Custom amendments for the theme.
 *
 * @category   SS 2016 Theme
 * @package    Functions
 * @subpackage Functions
 * @author     Andrew Fife
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://ucc.co.za/
 * @since      1.0.0
 */

// Initialize Sandbox ** DON'T REMOVE **
require_once( get_stylesheet_directory() . '/lib/init.php');

add_action( 'genesis_setup', 'gs_theme_setup', 15 );

//Theme Set Up Function
function gs_theme_setup() {

	//Enable HTML5 Support
	add_theme_support( 'html5' );

	//Enable Post Navigation
	add_action( 'genesis_after_entry_content', 'genesis_prev_next_post_nav', 5 );

	/**
	 * 01 Set width of oEmbed
	 * genesis_content_width() will be applied; Filters the content width based on the user selected layout.
	 *
	 * @see genesis_content_width()
	 * @param integer $default Default width
	 * @param integer $small Small width
	 * @param integer $large Large width
	 */
	$content_width = apply_filters( 'content_width', 600, 430, 920 );

	//Custom Image Sizes
	add_image_size( 'featured-image', 225, 160, TRUE );

	// Enable Custom Background
	//add_theme_support( 'custom-background' );

	// Enable Custom Header
	//add_theme_support('genesis-custom-header');

	// Add support for Custom Logo
	 add_theme_support( 'custom-logo', array(
	'height'      => 57,
	'width'       => 115,
	'flex-height' => true,
	'flex-width'  => true,
	        ) );

/**
 * Add an image inline in the site title element for the main logo
 *
 * The custom logo is then added via the Customiser
 *
 * @param string $title All the mark up title.
 * @param string $inside Mark up inside the title.
 * @param string $wrap Mark up on the title.
 *
 */
function SS2016_custom_logo( $title, $inside, $wrap ) {
	// Check to see if the Custom Logo function exists and set what goes inside the wrapping tags.
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
		$logo = the_custom_logo();
	else :
	 	$logo = get_bloginfo( 'name' );
	endif;
 	 // Use this wrap if no custom logo - wrap around the site name
	 $inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( home_url() ), esc_attr( get_bloginfo( 'name' ) ), $logo );
	 // Determine which wrapping tags to use - changed is_home to is_front_page to fix Genesis bug.
	 $wrap = is_front_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
	 // A little fallback, in case an SEO plugin is active - changed is_home to is_front_page to fix Genesis bug.
	 $wrap = is_front_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;
	 // And finally, $wrap in h1 if HTML5 & semantic headings enabled.
	 $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;
	 $title = sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );
	 return $title;
}
add_filter( 'genesis_seo_title','SS2016_custom_logo', 10, 3 );
/**
 * Add class for screen readers to site description.
 * This will keep the site description mark up but will not have any visual presence on the page
 * This runs if their is a header image set in the Customiser.
 *
 * @param string $attributes Add screen reader class if custom logo is set.
 *
 */
 function SS2016_add_site_description_class( $attributes ) {
	if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$attributes['class'] .= ' screen-reader-text';
		return $attributes;
	}
	else {
		return $attributes;
	}
 }
 add_filter( 'genesis_attr_site-description', 'SS2016_add_site_description_class' );


	// Add support for structural wraps
	add_theme_support( 'genesis-structural-wraps', array(
		'header',
		'nav',
		'subnav',
		'inner',
		'footer-widgets',
		'footer'
	) );

	/**
	 * 07 Footer Widgets
	 * Add support for 3-column footer widgets
	 * Change 3 for support of up to 6 footer widgets (automatically styled for layout)
	 */
	add_theme_support( 'genesis-footer-widgets', 4 );

	/**
	 * 08 Genesis Menus
	 * Genesis Sandbox comes with 4 navigation systems built-in ready.
	 * Delete any menu systems that you do not wish to use.
	 */
	add_theme_support(
		'genesis-menus',
		array(
			'primary'   => __( 'Primary Navigation Menu', CHILD_DOMAIN ),
			'secondary' => __( 'Secondary Navigation Menu', CHILD_DOMAIN ),
			'footer'    => __( 'Footer Navigation Menu', CHILD_DOMAIN ),
			'mobile'    => __( 'Mobile Navigation Menu', CHILD_DOMAIN ),
		)
	);

	// Add Mobile Navigation
	add_action( 'genesis_before', 'gs_mobile_navigation', 5 );

	//Enqueue Sandbox Scripts
	add_action( 'wp_enqueue_scripts', 'gs_enqueue_scripts' );

	/**
	 * 13 Editor Styles
	 * Takes a stylesheet string or an array of stylesheets.
	 * Default: editor-style.css
	 */
	//add_editor_style();


	// Register Sidebars
	gs_register_sidebars();

} // End of Set Up Function

// Register Sidebars
function gs_register_sidebars() {
	$sidebars = array(
		array(
			'id'			=> 'home-top',
			'name'			=> __( 'Home Top', CHILD_DOMAIN ),
			'description'	=> __( 'This is the top homepage section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-01',
			'name'			=> __( 'Home Left Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage left section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-02',
			'name'			=> __( 'Home Middle Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage middle section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-middle-03',
			'name'			=> __( 'Home Right Middle', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage right section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'home-bottom',
			'name'			=> __( 'Home Bottom', CHILD_DOMAIN ),
			'description'	=> __( 'This is the homepage right section.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'portfolio',
			'name'			=> __( 'Portfolio', CHILD_DOMAIN ),
			'description'	=> __( 'Use featured posts to showcase your portfolio.', CHILD_DOMAIN ),
		),
		array(
			'id'			=> 'after-post',
			'name'			=> __( 'After Post', CHILD_DOMAIN ),
			'description'	=> __( 'This will show up after every post.', CHILD_DOMAIN ),
		),
	);

	foreach ( $sidebars as $sidebar )
		genesis_register_sidebar( $sidebar );
}

/**
 * Enqueue and Register Scripts - Twitter Bootstrap, Font-Awesome, and Common.
 */
require_once('lib/scripts.php');

/**
 * Add navigation menu
 * Required for each registered menu.
 *
 * @uses gs_navigation() Sandbox Navigation Helper Function in gs-functions.php.
 */

//Add Mobile Menu
function gs_mobile_navigation() {

	$mobile_menu_args = array(
		'echo' => true,
	);

	gs_navigation( 'mobile', $mobile_menu_args );
}

// Add Widget Area After Post
add_action('genesis_after_entry', 'gs_do_after_entry');
function gs_do_after_entry() {
 	if ( is_single() ) {
 	genesis_widget_area(
                'after-post',
                array(
                        'before' => '<aside id="after-post" class="after-post"><div class="home-widget widget-area">',
                        'after' => '</div></aside><!-- end #home-left -->',
                )
        );
 }
 }
