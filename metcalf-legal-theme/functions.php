
<?php
/**
 * Metcalf Legal Theme functions and definitions
 * 
 * This file contains the core functionality for the Metcalf Legal Theme.
 * It's designed to be minimal and performance-oriented while providing
 * essential functionality for a legal platform using Elementor Pro.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define theme version
define('METCALF_LEGAL_THEME_VERSION', '1.0.0');

/**
 * Theme setup
 */
function metcalf_legal_theme_setup() {
    
    // Make theme available for translation
    load_theme_textdomain('metcalf-legal-theme', get_template_directory() . '/languages');
    
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Add support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    
    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
    
    // Add support for wide alignment
    add_theme_support('align-wide');
    
    // Add support for custom line height
    add_theme_support('custom-line-height');
    
    // Add support for custom units
    add_theme_support('custom-units');
    
    // Add support for link color
    add_theme_support('link-color');
    
    // Add support for block templates
    add_theme_support('block-templates');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'metcalf-legal-theme'),
        'footer'  => esc_html__('Footer Menu', 'metcalf-legal-theme'),
    ));
    
    // Add image sizes for different contexts
    add_image_size('metcalf-legal-hero', 1920, 1080, true);
    add_image_size('metcalf-legal-card', 400, 300, true);
    add_image_size('metcalf-legal-thumbnail', 150, 150, true);
}
add_action('after_setup_theme', 'metcalf_legal_theme_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function metcalf_legal_content_width() {
    $GLOBALS['content_width'] = apply_filters('metcalf_legal_content_width', 1200);
}
add_action('after_setup_theme', 'metcalf_legal_content_width', 0);

/**
 * Enqueue scripts and styles
 */
function metcalf_legal_scripts() {
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'metcalf-legal-style',
        get_stylesheet_uri(),
        array(),
        METCALF_LEGAL_THEME_VERSION
    );
    
    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
    
    // Add async loading for non-critical scripts
    add_filter('script_loader_tag', 'metcalf_legal_async_scripts', 10, 2);
}
add_action('wp_enqueue_scripts', 'metcalf_legal_scripts');

/**
 * Add async attribute to specific scripts
 */
function metcalf_legal_async_scripts($tag, $handle) {
    $async_scripts = array('comment-reply');
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    return $tag;
}

/**
 * Register widget area
 */
function metcalf_legal_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'metcalf-legal-theme'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'metcalf-legal-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer', 'metcalf-legal-theme'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add footer widgets here.', 'metcalf-legal-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'metcalf_legal_widgets_init');

/**
 * Custom template tags for this theme
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Elementor compatibility
 */
function metcalf_legal_elementor_support() {
    
    // Add Elementor support
    add_theme_support('elementor');
    
    // Add support for Elementor Pro features
    add_theme_support('elementor-pro');
    
    // Set Elementor page title to false to prevent conflicts
    add_filter('elementor/page_title/enabled', '__return_false');
    
    // Remove theme's post content wrapper when Elementor is active
    if (class_exists('\Elementor\Plugin')) {
        add_action('elementor/frontend/after_enqueue_styles', function() {
            wp_dequeue_style('metcalf-legal-blocks-style');
        });
    }
}
add_action('after_setup_theme', 'metcalf_legal_elementor_support');

/**
 * Optimize performance
 */
function metcalf_legal_performance_optimizations() {
    
    // Remove unnecessary WordPress features for better performance
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove emojis support for faster loading
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Disable XML-RPC for security
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove REST API links from head
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Disable pingbacks
    add_filter('wp_headers', function($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    });
    
    // Remove query strings from static resources
    add_filter('script_loader_src', 'metcalf_legal_remove_script_version', 15, 1);
    add_filter('style_loader_src', 'metcalf_legal_remove_script_version', 15, 1);
}
add_action('init', 'metcalf_legal_performance_optimizations');

/**
 * Remove query strings from static resources
 */
function metcalf_legal_remove_script_version($src) {
    $parts = explode('?ver', $src);
    return $parts[0];
}

/**
 * Security enhancements
 */
function metcalf_legal_security_enhancements() {
    
    // Hide WordPress version
    add_filter('the_generator', '__return_empty_string');
    
    // Remove version from scripts and styles
    add_filter('style_loader_src', 'metcalf_legal_remove_version_strings');
    add_filter('script_loader_src', 'metcalf_legal_remove_version_strings');
    
    // Disable file editing in WordPress admin
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
    
    // Add security headers
    add_action('send_headers', function() {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    });
}
add_action('init', 'metcalf_legal_security_enhancements');

/**
 * Remove version strings for security
 */
function metcalf_legal_remove_version_strings($src) {
    global $wp_version;
    parse_str(parse_url($src, PHP_URL_QUERY), $query);
    if (!empty($query['ver']) && $query['ver'] === $wp_version) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Add body classes for better styling control
 */
function metcalf_legal_body_classes($classes) {
    
    // Add class for Elementor pages
    if (class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->frontend->is_elementor_page(get_the_ID())) {
        $classes[] = 'elementor-page';
    }
    
    // Add class for legal-specific post types
    if (is_singular(array('attorneys', 'practice_areas', 'case_results', 'media_mentions'))) {
        $classes[] = 'legal-post-type';
        $classes[] = 'post-type-' . get_post_type();
    }
    
    return $classes;
}
add_filter('body_class', 'metcalf_legal_body_classes');

/**
 * REST API enhancements for headless architecture
 */
function metcalf_legal_rest_api_enhancements() {
    
    // Enable REST API for custom post types
    add_filter('register_post_type_args', function($args, $post_type) {
        if (in_array($post_type, array('attorneys', 'practice_areas', 'case_results', 'media_mentions'))) {
            $args['show_in_rest'] = true;
        }
        return $args;
    }, 10, 2);
    
    // Add CORS headers for API requests
    add_action('rest_api_init', function() {
        remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
        add_filter('rest_pre_serve_request', function($value) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
            header('Access-Control-Allow-Headers: Authorization, Content-Type');
            return $value;
        });
    });
}
add_action('init', 'metcalf_legal_rest_api_enhancements');

/**
 * Custom excerpt length for legal content
 */
function metcalf_legal_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'metcalf_legal_excerpt_length');

/**
 * Custom excerpt more text
 */
function metcalf_legal_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'metcalf_legal_excerpt_more');
