<?php
/**
 * CARES functions and definitions
 *
 * @package CARES
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'cares_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cares_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CARES, use a find and replace
	 * to change 'cares' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'cares', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'cares' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Enable support for featured images - "post thumbnails"
	add_theme_support( 'post-thumbnails' );

	if ( function_exists( 'add_image_size' ) ) { 
		// Used as the post banner on the front page and single post pages
		add_image_size( 'featured-desktop', 1024, 400, true ); //(cropped)
		// Used for responsive images, eh? See inc/template-tags.php->cares_responsive_thumbnail() for implementation
		add_image_size( 'featured-300', 300, 200, true ); //(cropped)
		add_image_size( 'featured-450', 450, 225, true ); //(cropped)
		add_image_size( 'featured-600', 600, 300, true ); //(cropped)
		add_image_size( 'featured-800', 800, 350, true ); //(cropped)
	}

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'cares_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );
}
endif; // cares_setup
add_action( 'after_setup_theme', 'cares_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function cares_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'cares' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'cares_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cares_scripts() {
	wp_enqueue_style( 'cares-style', get_stylesheet_uri() );

	wp_enqueue_script( 'cares-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'cares-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cares_scripts' );

function limit_front_page_posts( $query ) {
	if( is_front_page() && $query->is_main_query() ) {   	
        $query->set( 'posts_per_page', 1 );
        if ( $sticky_posts = get_option( 'sticky_posts' ) ) {
        	// get_option( 'sticky_posts' ) returns trashed and draft-status stickies, unhelpfully, so we've got to compare against the post_status, too.
        	$sticky_posts = implode( ',', $sticky_posts );
        	global $wpdb;
        	if ( $sticky_published_posts = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_type LIKE 'post' AND post_status LIKE 'publish' AND ID IN ( {$sticky_posts} )" ) ) {  
		        $query->set( 'post__in', $sticky_published_posts );
		        // Tell WP not to lift a finger on the whole sticky thing, since we just did the heavy lifting.
				$query->set( 'ignore_sticky_posts', 1 );
			}
		}
    }
}
add_filter( 'pre_get_posts', 'limit_front_page_posts' );

/**
 * Specify "no-post-thumbnail" in post class, so we don't add margin then remove it if the post has a thumbnail.
 */	
function cares_no_thumbnail_class( $classes ) {
    if ( ! in_array( 'has-post-thumbnail', $classes ) )
        $classes[] = 'no-post-thumbnail';
    
    return $classes;
}
add_filter('post_class', 'cares_no_thumbnail_class', 98);

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
