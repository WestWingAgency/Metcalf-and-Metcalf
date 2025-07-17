
<?php
/**
 * Custom Post Type Registration for Metcalf Legal System
 * 
 * Registers all custom post types required for the legal platform.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Metcalf_CPT_Registration {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
        add_action('init', array($this, 'register_taxonomies'));
    }
    
    /**
     * Register all custom post types
     */
    public function register_post_types() {
        $this->register_attorneys();
        $this->register_practice_areas();
        $this->register_case_results();
        $this->register_media_mentions();
    }
    
    /**
     * Register attorneys custom post type
     */
    private function register_attorneys() {
        $labels = array(
            'name' => _x('Attorneys', 'Post type general name', 'metcalf-legal-system'),
            'singular_name' => _x('Attorney', 'Post type singular name', 'metcalf-legal-system'),
            'menu_name' => _x('Attorneys', 'Admin Menu text', 'metcalf-legal-system'),
            'name_admin_bar' => _x('Attorney', 'Add New on Toolbar', 'metcalf-legal-system'),
            'add_new' => __('Add New', 'metcalf-legal-system'),
            'add_new_item' => __('Add New Attorney', 'metcalf-legal-system'),
            'new_item' => __('New Attorney', 'metcalf-legal-system'),
            'edit_item' => __('Edit Attorney', 'metcalf-legal-system'),
            'view_item' => __('View Attorney', 'metcalf-legal-system'),
            'all_items' => __('All Attorneys', 'metcalf-legal-system'),
            'search_items' => __('Search Attorneys', 'metcalf-legal-system'),
            'parent_item_colon' => __('Parent Attorneys:', 'metcalf-legal-system'),
            'not_found' => __('No attorneys found.', 'metcalf-legal-system'),
            'not_found_in_trash' => __('No attorneys found in Trash.', 'metcalf-legal-system'),
            'featured_image' => _x('Attorney Image', 'Overrides the "Featured Image" phrase', 'metcalf-legal-system'),
            'set_featured_image' => _x('Set attorney image', 'Overrides the "Set featured image" phrase', 'metcalf-legal-system'),
            'remove_featured_image' => _x('Remove attorney image', 'Overrides the "Remove featured image" phrase', 'metcalf-legal-system'),
            'use_featured_image' => _x('Use as attorney image', 'Overrides the "Use as featured image" phrase', 'metcalf-legal-system'),
            'archives' => _x('Attorney archives', 'The post type archive label', 'metcalf-legal-system'),
            'insert_into_item' => _x('Insert into attorney', 'Overrides the "Insert into post" phrase', 'metcalf-legal-system'),
            'uploaded_to_this_item' => _x('Uploaded to this attorney', 'Overrides the "Uploaded to this post" phrase', 'metcalf-legal-system'),
            'filter_items_list' => _x('Filter attorneys list', 'Screen reader text for the filter links', 'metcalf-legal-system'),
            'items_list_navigation' => _x('Attorneys list navigation', 'Screen reader text for the pagination', 'metcalf-legal-system'),
            'items_list' => _x('Attorneys list', 'Screen reader text for the items list', 'metcalf-legal-system'),
        );
        
        $args = array(
            'labels' => $labels,
            'description' => __('Attorney profiles and information.', 'metcalf-legal-system'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'attorneys'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-businessperson',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
            'rest_base' => 'attorneys',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        
        register_post_type('attorneys', $args);
    }
    
    /**
     * Register practice areas custom post type
     */
    private function register_practice_areas() {
        $labels = array(
            'name' => _x('Practice Areas', 'Post type general name', 'metcalf-legal-system'),
            'singular_name' => _x('Practice Area', 'Post type singular name', 'metcalf-legal-system'),
            'menu_name' => _x('Practice Areas', 'Admin Menu text', 'metcalf-legal-system'),
            'name_admin_bar' => _x('Practice Area', 'Add New on Toolbar', 'metcalf-legal-system'),
            'add_new' => __('Add New', 'metcalf-legal-system'),
            'add_new_item' => __('Add New Practice Area', 'metcalf-legal-system'),
            'new_item' => __('New Practice Area', 'metcalf-legal-system'),
            'edit_item' => __('Edit Practice Area', 'metcalf-legal-system'),
            'view_item' => __('View Practice Area', 'metcalf-legal-system'),
            'all_items' => __('All Practice Areas', 'metcalf-legal-system'),
            'search_items' => __('Search Practice Areas', 'metcalf-legal-system'),
            'parent_item_colon' => __('Parent Practice Areas:', 'metcalf-legal-system'),
            'not_found' => __('No practice areas found.', 'metcalf-legal-system'),
            'not_found_in_trash' => __('No practice areas found in Trash.', 'metcalf-legal-system'),
            'featured_image' => _x('Practice Area Image', 'Overrides the "Featured Image" phrase', 'metcalf-legal-system'),
            'set_featured_image' => _x('Set practice area image', 'Overrides the "Set featured image" phrase', 'metcalf-legal-system'),
            'remove_featured_image' => _x('Remove practice area image', 'Overrides the "Remove featured image" phrase', 'metcalf-legal-system'),
            'use_featured_image' => _x('Use as practice area image', 'Overrides the "Use as featured image" phrase', 'metcalf-legal-system'),
            'archives' => _x('Practice Area archives', 'The post type archive label', 'metcalf-legal-system'),
            'insert_into_item' => _x('Insert into practice area', 'Overrides the "Insert into post" phrase', 'metcalf-legal-system'),
            'uploaded_to_this_item' => _x('Uploaded to this practice area', 'Overrides the "Uploaded to this post" phrase', 'metcalf-legal-system'),
            'filter_items_list' => _x('Filter practice areas list', 'Screen reader text for the filter links', 'metcalf-legal-system'),
            'items_list_navigation' => _x('Practice areas list navigation', 'Screen reader text for the pagination', 'metcalf-legal-system'),
            'items_list' => _x('Practice areas list', 'Screen reader text for the items list', 'metcalf-legal-system'),
        );
        
        $args = array(
            'labels' => $labels,
            'description' => __('Legal practice areas and services.', 'metcalf-legal-system'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'practice-areas'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-portfolio',
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'show_in_rest' => true,
            'rest_base' => 'practice-areas',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        
        register_post_type('practice_areas', $args);
    }
    
    /**
     * Register case results custom post type
     */
    private function register_case_results() {
        $labels = array(
            'name' => _x('Case Results', 'Post type general name', 'metcalf-legal-system'),
            'singular_name' => _x('Case Result', 'Post type singular name', 'metcalf-legal-system'),
            'menu_name' => _x('Case Results', 'Admin Menu text', 'metcalf-legal-system'),
            'name_admin_bar' => _x('Case Result', 'Add New on Toolbar', 'metcalf-legal-system'),
            'add_new' => __('Add New', 'metcalf-legal-system'),
            'add_new_item' => __('Add New Case Result', 'metcalf-legal-system'),
            'new_item' => __('New Case Result', 'metcalf-legal-system'),
            'edit_item' => __('Edit Case Result', 'metcalf-legal-system'),
            'view_item' => __('View Case Result', 'metcalf-legal-system'),
            'all_items' => __('All Case Results', 'metcalf-legal-system'),
            'search_items' => __('Search Case Results', 'metcalf-legal-system'),
            'parent_item_colon' => __('Parent Case Results:', 'metcalf-legal-system'),
            'not_found' => __('No case results found.', 'metcalf-legal-system'),
            'not_found_in_trash' => __('No case results found in Trash.', 'metcalf-legal-system'),
            'featured_image' => _x('Case Result Image', 'Overrides the "Featured Image" phrase', 'metcalf-legal-system'),
            'set_featured_image' => _x('Set case result image', 'Overrides the "Set featured image" phrase', 'metcalf-legal-system'),
            'remove_featured_image' => _x('Remove case result image', 'Overrides the "Remove featured image" phrase', 'metcalf-legal-system'),
            'use_featured_image' => _x('Use as case result image', 'Overrides the "Use as featured image" phrase', 'metcalf-legal-system'),
            'archives' => _x('Case Result archives', 'The post type archive label', 'metcalf-legal-system'),
            'insert_into_item' => _x('Insert into case result', 'Overrides the "Insert into post" phrase', 'metcalf-legal-system'),
            'uploaded_to_this_item' => _x('Uploaded to this case result', 'Overrides the "Uploaded to this post" phrase', 'metcalf-legal-system'),
            'filter_items_list' => _x('Filter case results list', 'Screen reader text for the filter links', 'metcalf-legal-system'),
            'items_list_navigation' => _x('Case results list navigation', 'Screen reader text for the pagination', 'metcalf-legal-system'),
            'items_list' => _x('Case results list', 'Screen reader text for the items list', 'metcalf-legal-system'),
        );
        
        $args = array(
            'labels' => $labels,
            'description' => __('Successful case results and outcomes.', 'metcalf-legal-system'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'case-results'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 22,
            'menu_icon' => 'dashicons-awards',
            'supports' => array('title', 'editor'),
            'show_in_rest' => true,
            'rest_base' => 'case-results',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        
        register_post_type('case_results', $args);
    }
    
    /**
     * Register media mentions custom post type
     */
    private function register_media_mentions() {
        $labels = array(
            'name' => _x('Media Mentions', 'Post type general name', 'metcalf-legal-system'),
            'singular_name' => _x('Media Mention', 'Post type singular name', 'metcalf-legal-system'),
            'menu_name' => _x('Media Mentions', 'Admin Menu text', 'metcalf-legal-system'),
            'name_admin_bar' => _x('Media Mention', 'Add New on Toolbar', 'metcalf-legal-system'),
            'add_new' => __('Add New', 'metcalf-legal-system'),
            'add_new_item' => __('Add New Media Mention', 'metcalf-legal-system'),
            'new_item' => __('New Media Mention', 'metcalf-legal-system'),
            'edit_item' => __('Edit Media Mention', 'metcalf-legal-system'),
            'view_item' => __('View Media Mention', 'metcalf-legal-system'),
            'all_items' => __('All Media Mentions', 'metcalf-legal-system'),
            'search_items' => __('Search Media Mentions', 'metcalf-legal-system'),
            'parent_item_colon' => __('Parent Media Mentions:', 'metcalf-legal-system'),
            'not_found' => __('No media mentions found.', 'metcalf-legal-system'),
            'not_found_in_trash' => __('No media mentions found in Trash.', 'metcalf-legal-system'),
            'featured_image' => _x('Media Mention Image', 'Overrides the "Featured Image" phrase', 'metcalf-legal-system'),
            'set_featured_image' => _x('Set media mention image', 'Overrides the "Set featured image" phrase', 'metcalf-legal-system'),
            'remove_featured_image' => _x('Remove media mention image', 'Overrides the "Remove featured image" phrase', 'metcalf-legal-system'),
            'use_featured_image' => _x('Use as media mention image', 'Overrides the "Use as featured image" phrase', 'metcalf-legal-system'),
            'archives' => _x('Media Mention archives', 'The post type archive label', 'metcalf-legal-system'),
            'insert_into_item' => _x('Insert into media mention', 'Overrides the "Insert into post" phrase', 'metcalf-legal-system'),
            'uploaded_to_this_item' => _x('Uploaded to this media mention', 'Overrides the "Uploaded to this post" phrase', 'metcalf-legal-system'),
            'filter_items_list' => _x('Filter media mentions list', 'Screen reader text for the filter links', 'metcalf-legal-system'),
            'items_list_navigation' => _x('Media mentions list navigation', 'Screen reader text for the pagination', 'metcalf-legal-system'),
            'items_list' => _x('Media mentions list', 'Screen reader text for the items list', 'metcalf-legal-system'),
        );
        
        $args = array(
            'labels' => $labels,
            'description' => __('Media mentions and press coverage.', 'metcalf-legal-system'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'media-mentions'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 23,
            'menu_icon' => 'dashicons-megaphone',
            'supports' => array('title', 'editor'),
            'show_in_rest' => true,
            'rest_base' => 'media-mentions',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );
        
        register_post_type('media_mentions', $args);
    }
    
    /**
     * Register custom taxonomies
     */
    public function register_taxonomies() {
        // Register practice area categories for attorneys
        $labels = array(
            'name' => _x('Practice Area Categories', 'taxonomy general name', 'metcalf-legal-system'),
            'singular_name' => _x('Practice Area Category', 'taxonomy singular name', 'metcalf-legal-system'),
            'search_items' => __('Search Practice Area Categories', 'metcalf-legal-system'),
            'all_items' => __('All Practice Area Categories', 'metcalf-legal-system'),
            'parent_item' => __('Parent Practice Area Category', 'metcalf-legal-system'),
            'parent_item_colon' => __('Parent Practice Area Category:', 'metcalf-legal-system'),
            'edit_item' => __('Edit Practice Area Category', 'metcalf-legal-system'),
            'update_item' => __('Update Practice Area Category', 'metcalf-legal-system'),
            'add_new_item' => __('Add New Practice Area Category', 'metcalf-legal-system'),
            'new_item_name' => __('New Practice Area Category Name', 'metcalf-legal-system'),
            'menu_name' => __('Practice Area Categories', 'metcalf-legal-system'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'practice-area-category'),
            'show_in_rest' => true,
            'rest_base' => 'practice-area-categories',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
        );
        
        register_taxonomy('practice_area_category', array('attorneys', 'practice_areas'), $args);
    }
}
