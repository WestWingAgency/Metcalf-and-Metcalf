
<?php
/**
 * Plugin Name: Metcalf Legal System
 * Plugin URI: https://metcalflegal.com
 * Description: Comprehensive legal platform system with multi-database architecture, custom post types, and HIPAA-compliant security features.
 * Version: 1.0.0
 * Author: Metcalf Legal
 * License: GPL2
 * Text Domain: metcalf-legal-system
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('METCALF_LEGAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('METCALF_LEGAL_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('METCALF_LEGAL_VERSION', '1.0.0');

// Include required files
require_once METCALF_LEGAL_PLUGIN_PATH . 'classes/class-database-abstraction.php';
require_once METCALF_LEGAL_PLUGIN_PATH . 'cpt-registration.php';

/**
 * Main plugin class
 */
class Metcalf_Legal_System {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize database abstraction
        new Metcalf_Database_Abstraction();
        
        // Initialize custom post types
        new Metcalf_CPT_Registration();
        
        // Add admin notices if needed
        add_action('admin_notices', array($this, 'admin_notices'));
    }
    
    /**
     * Plugin activation hook
     */
    public function activate() {
        // Create databases and tables
        $this->create_databases();
        $this->create_tables();
        
        // Flush rewrite rules for custom post types
        flush_rewrite_rules();
        
        // Set activation flag
        update_option('metcalf_legal_activated', true);
    }
    
    /**
     * Plugin deactivation hook
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Remove activation flag
        delete_option('metcalf_legal_activated');
    }
    
    /**
     * Create the four required databases
     */
    private function create_databases() {
        global $wpdb;
        
        $databases = array(
            'wp_metcalf_portal',
            'wp_metcalf_cases', 
            'wp_metcalf_docs',
            'wp_metcalf_analytics'
        );
        
        foreach ($databases as $database) {
            $sql = $wpdb->prepare("CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci", $database);
            
            // Note: $wpdb->prepare() doesn't work with database names, so we need to sanitize manually
            $database_name = sanitize_text_field($database);
            $sql = "CREATE DATABASE IF NOT EXISTS `{$database_name}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
            
            $wpdb->query($sql);
            
            if ($wpdb->last_error) {
                error_log("Metcalf Legal System: Error creating database {$database}: " . $wpdb->last_error);
            }
        }
    }
    
    /**
     * Create all required tables in their respective databases
     */
    private function create_tables() {
        $this->create_portal_tables();
        $this->create_cases_tables();
        $this->create_docs_tables();
        $this->create_analytics_tables();
    }
    
    /**
     * Create tables for wp_metcalf_portal database
     */
    private function create_portal_tables() {
        global $wpdb;
        
        // Switch to portal database
        $original_db = $wpdb->dbname;
        $wpdb->select('wp_metcalf_portal');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Client users table
        $sql = "CREATE TABLE `client_users` (
            `portal_user_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `wp_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `last_login_at` TIMESTAMP NULL DEFAULT NULL,
            `two_factor_secret` VARCHAR(255) NULL DEFAULT NULL,
            PRIMARY KEY (`portal_user_id`),
            UNIQUE KEY `wp_user_id` (`wp_user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Secure messages table
        $sql = "CREATE TABLE `secure_messages` (
            `message_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `case_id` BIGINT(20) UNSIGNED NOT NULL,
            `sender_id` BIGINT(20) UNSIGNED NOT NULL,
            `recipient_id` BIGINT(20) UNSIGNED NOT NULL,
            `message_content` TEXT NOT NULL,
            `sent_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `read_at` TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (`message_id`),
            KEY `case_id` (`case_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Switch back to original database
        $wpdb->select($original_db);
    }
    
    /**
     * Create tables for wp_metcalf_cases database
     */
    private function create_cases_tables() {
        global $wpdb;
        
        // Switch to cases database
        $original_db = $wpdb->dbname;
        $wpdb->select('wp_metcalf_cases');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Cases table
        $sql = "CREATE TABLE `cases` (
            `case_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `client_portal_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `lead_attorney_wp_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `case_title` VARCHAR(255) NOT NULL,
            `case_status` VARCHAR(50) NOT NULL DEFAULT 'open',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`case_id`),
            KEY `client_portal_user_id` (`client_portal_user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Case timeline table
        $sql = "CREATE TABLE `case_timeline` (
            `timeline_event_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `case_id` BIGINT(20) UNSIGNED NOT NULL,
            `event_title` VARCHAR(255) NOT NULL,
            `event_description` TEXT NULL,
            `event_date` DATE NOT NULL,
            `is_completed` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`timeline_event_id`),
            FOREIGN KEY (`case_id`) REFERENCES `cases`(`case_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Switch back to original database
        $wpdb->select($original_db);
    }
    
    /**
     * Create tables for wp_metcalf_docs database
     */
    private function create_docs_tables() {
        global $wpdb;
        
        // Switch to docs database
        $original_db = $wpdb->dbname;
        $wpdb->select('wp_metcalf_docs');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Documents table
        $sql = "CREATE TABLE `documents` (
            `doc_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `case_id` BIGINT(20) UNSIGNED NOT NULL,
            `uploader_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `file_name` VARCHAR(255) NOT NULL,
            `file_path` VARCHAR(1024) NOT NULL,
            `file_hash_sha256` VARCHAR(64) NOT NULL,
            `uploaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`doc_id`),
            KEY `case_id` (`case_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Document access log table
        $sql = "CREATE TABLE `document_access_log` (
            `log_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `doc_id` BIGINT(20) UNSIGNED NOT NULL,
            `viewer_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `action` VARCHAR(50) NOT NULL,
            `accessed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`log_id`),
            FOREIGN KEY (`doc_id`) REFERENCES `documents`(`doc_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Switch back to original database
        $wpdb->select($original_db);
    }
    
    /**
     * Create tables for wp_metcalf_analytics database
     */
    private function create_analytics_tables() {
        global $wpdb;
        
        // Switch to analytics database
        $original_db = $wpdb->dbname;
        $wpdb->select('wp_metcalf_analytics');
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // Client satisfaction surveys table
        $sql = "CREATE TABLE `client_satisfaction_surveys` (
            `survey_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            `case_id` BIGINT(20) UNSIGNED NOT NULL,
            `client_portal_user_id` BIGINT(20) UNSIGNED NOT NULL,
            `overall_rating` TINYINT(2) NOT NULL,
            `comments` TEXT NULL,
            `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`survey_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        dbDelta($sql);
        
        // Switch back to original database
        $wpdb->select($original_db);
    }
    
    /**
     * Show admin notices
     */
    public function admin_notices() {
        if (get_option('metcalf_legal_activated')) {
            echo '<div class="notice notice-success is-dismissible">';
            echo '<p><strong>Metcalf Legal System:</strong> Plugin activated successfully. All databases and tables have been created.</p>';
            echo '</div>';
            delete_option('metcalf_legal_activated');
        }
    }
}

// Initialize the plugin
new Metcalf_Legal_System();
