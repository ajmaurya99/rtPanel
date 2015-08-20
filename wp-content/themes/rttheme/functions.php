<?php
/**
 * rt camp functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since rt camp 1.0
 */
/**
 * Set up the content width value based on the theme's design.
 *
 * @see rttheme_content_width()
 *
 * @since rt camp 1.0
 */
if (!isset($content_width)) {
    $content_width = 474;
}

/**
 * rt camp only works in WordPress 3.6 or later.
 */
if (version_compare($GLOBALS['wp_version'], '3.6', '<')) {
    require get_template_directory() . '/inc/back-compat.php';
}

if (!function_exists('rttheme_setup')) :

    /**
     * rt camp setup.
     *
     * Set up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support post thumbnails.
     *
     * @since rt camp 1.0
     */
    function rttheme_setup() {

        /*
         * Make rt camp available for translation.
         *
         * Translations can be added to the /languages/ directory.
         * If you're building a theme based on rt camp, use a find and
         * replace to change 'rttheme' to the name of your theme in all
         * template files.
         */
        load_theme_textdomain('rttheme', get_template_directory() . '/languages');

        // Add RSS feed links to <head> for posts and comments.
        add_theme_support('automatic-feed-links');

        // Enable support for Post Thumbnails, and declare two sizes.
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in two locations.
        register_nav_menus(array(
            'primary' => __('Top primary menu', 'rttheme'),
            'secondary' => __('Secondary Menu', 'rttheme')
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));

        /*
         * Enable support for Post Formats.
         * See http://codex.wordpress.org/Post_Formats
         */
        add_theme_support('post-formats', array(
            'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
        ));


        // Add support for featured content.
        add_theme_support('featured-content', array(
            'featured_content_filter' => 'rttheme_get_featured_posts',
            'max_posts' => 6,
        ));

        // This theme uses its own gallery styles.
        add_filter('use_default_gallery_style', '__return_false');
    }

endif; // rttheme_setup
add_action('after_setup_theme', 'rttheme_setup');

/**
 * Adjust content_width value for image attachment template.
 *
 * @since rt camp 1.0
 */
function rttheme_content_width() {
    if (is_attachment() && wp_attachment_is_image()) {
        $GLOBALS['content_width'] = 810;
    }
}

add_action('template_redirect', 'rttheme_content_width');

/**
 * Getter function for Featured Content Plugin.
 *
 * @since rt camp 1.0
 *
 * @return array An array of WP_Post objects.
 */
function rttheme_get_featured_posts() {
    /**
     * Filter the featured posts to return in rt camp.
     *
     * @since rt camp 1.0
     *
     * @param array|bool $posts Array of featured posts, otherwise false.
     */
    return apply_filters('rttheme_get_featured_posts', array());
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since rt camp 1.0
 *
 * @return bool Whether there are featured posts.
 */
function rttheme_has_featured_posts() {
    return !is_paged() && (bool) rttheme_get_featured_posts();
}

/**
 * Register PT sans Google font for rt camp.
 *
 * @since rt camp 1.0
 *
 * @return string
 */
function rttheme_font_url() {
    $font_url = '';
    /*
     * Translators: If there are characters in your language that are not supported
     * by Lato, translate this to 'off'. Do not translate into your own language.
     */
    if ('off' !== _x('on', 'PT Sans font: on or off', 'rttheme')) {
        $query_args = array(
            'family' => urlencode('PT Sans:400,700'),
            'subset' => urlencode('latin,latin-ext'),
        );
        $font_url = add_query_arg($query_args, '//fonts.googleapis.com/css');
    }

    return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since rt camp 1.0
 */
function rttheme_scripts() {
    // Add Lato font, used in the main stylesheet.
    wp_enqueue_style('rttheme-ptsans', rttheme_font_url(), array(), null);
    wp_register_style('bootstrap-css', get_template_directory_uri() . '/css/bootstrap.css', array(), '3.3.4');
    wp_register_style('font-awesome-css', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.2.0');
    wp_register_style('owlcarousel-css', get_template_directory_uri() . '/css/owl.carousel.css', array(), '1.3.3');
    wp_register_style('owltheme-css', get_template_directory_uri() . '/css/owl.theme.css', array(), '1.3.3');
    wp_register_style('normalize-css', get_template_directory_uri() . '/css/normalize.css', array(), '3.0.2');

    // Load our main stylesheet.
    wp_enqueue_style('rttheme-style', get_stylesheet_uri(), array('bootstrap-css', 'font-awesome-css', 'owlcarousel-css', 'owltheme-css','normalize-css'));

    //Register Scripts.
    wp_register_script('bootstrap-js', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '3.3.4');
    wp_register_script('html5shiv-js', get_template_directory_uri() . '/js/html5shiv.js', array('jquery'), '3.7.2');
    wp_register_script('respond-js', get_template_directory_uri() . '/js/respond.js', array('jquery'), '1.0.0');
    wp_register_script('owlcarousel-js', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), '1.3.3');
    wp_register_script('modernizr-latest-js', get_template_directory_uri() . '/js/modernizr-latest.js', array('jquery'), '2.8.3');
    wp_enqueue_script('rttheme-script', get_template_directory_uri() . '/js/init.js', array('jquery', 'bootstrap-js', 'html5shiv-js', 'respond-js', 'owlcarousel-js', 'modernizr-latest-js'), '20140616', true);
}

add_action('wp_enqueue_scripts', 'rttheme_scripts');

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since rt camp 1.0
 */
function rttheme_admin_fonts() {
    wp_enqueue_style('rttheme-lato', rttheme_font_url(), array(), null);
}

add_action('admin_print_scripts-appearance_page_custom-header', 'rttheme_admin_fonts');

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since rt camp 1.0
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function rttheme_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name', 'display');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() )) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if (( $paged >= 2 || $page >= 2 ) && !is_404()) {
        $title = "$title $sep " . sprintf(__('Page %s', 'rttheme'), max($paged, $page));
    }

    return $title;
}

add_filter('wp_title', 'rttheme_wp_title', 10, 2);

if (!function_exists('rttheme_post_nav')) :

    /**
     * Display navigation to next/previous post when applicable.
     *
     * @since rt camp 1.0
     */
    function rttheme_post_nav() {
        /* Don't print empty markup if there's nowhere to navigate. */
        $previous = ( is_attachment() ) ? get_post(get_post()->post_parent) : get_adjacent_post(false, '', true);
        $next = get_adjacent_post(false, '', false);

        if (!$next && !$previous) {
            return;
        }
        ?>
        <nav class="navigation post-navigation clearfix" role="navigation">
            <div class="nav-links clearfix">
                <?php
                if (is_attachment()) :
                    previous_post_link('%link', __('<span class="meta-nav">Published In</span>%title', 'rttheme'));
                else :
                    previous_post_link('%link', __('<span class="meta-nav"> <<  Previous', 'rttheme'));
                    next_post_link('%link', __('<span class="meta-nav">Next  >> ', 'rttheme'));
                endif;
                ?>
            </div><!-- .nav-links -->
        </nav><!-- .navigation -->
        <?php
    }

endif;

/* Register Custom Navigation Walker */
require_once('wp_bootstrap_navwalker.php');

/* Register Widget */

function wpb_widgets_init() {
    register_sidebar(array(
        'name' => __('Weather Widget', 'rttheme'),
        'id' => 'rttheme-weather',
        'description' => __('This is used to display weather on the website', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Date and Time Widget', 'rttheme'),
        'id' => 'rttheme-datetime',
        'description' => __('This is used to display Date and Time on the website', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Footer One', 'rttheme'),
        'id' => 'rttheme-footerone',
        'description' => __('First Footer Column', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Footer Two', 'rttheme'),
        'id' => 'rttheme-footertwo',
        'description' => __('Second Footer Column', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Footer Three', 'rttheme'),
        'id' => 'rttheme-footerthree',
        'description' => __('Third Footer Column', 'wpb'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

add_action('widgets_init', 'wpb_widgets_init');


/* Custom Post Type  for home page post start */

function create_posts() {
    register_post_type('rt_posts', array(
        'labels' => array(
            'name' => 'Home Page Posts',
            'singular_name' => 'Home Page Post',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Post',
            'edit' => 'Edit',
            'edit_item' => 'Edit Post',
            'new_item' => 'New Post',
            'view' => 'View',
            'view_item' => 'View Post',
            'search_items' => 'Search Post',
            'not_found' => 'No Post found',
            'not_found_in_trash' => 'No Post found in Trash',
            'parent' => 'Parent Post'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array(''),
        'has_archive' => true
            )
    );
}

// Hooking up our function to theme setup
add_action('init', 'create_posts');

/* Custom Post Type  for home page post End */


/* Custom Post Type  for home page post start */

function create_sports() {
    register_post_type('rt_post_sports', array(
        'labels' => array(
            'name' => 'Bottom Posts Slider',
            'singular_name' => 'Bottom Posts Slider',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Post',
            'edit' => 'Edit',
            'edit_item' => 'Edit Post',
            'new_item' => 'New Post',
            'view' => 'View',
            'view_item' => 'View Post',
            'search_items' => 'Search Post',
            'not_found' => 'No Post found',
            'not_found_in_trash' => 'No Post found in Trash',
            'parent' => 'Parent Post'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'taxonomies' => array(''),
        'has_archive' => true
            )
    );
}

// Hooking up our function to theme setup
add_action('init', 'create_sports');

/* Custom Post Type  for home page post End */


/* options Page */
require_once( 'wptuts-options/wptuts-options.php' );
/* options page ends */
/* for Twitter */
require_once( 'inc/index.php' );
