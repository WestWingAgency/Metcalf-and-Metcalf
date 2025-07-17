
<?php
/**
 * Database Abstraction Layer for Metcalf Legal System
 * 
 * This class provides a secure abstraction layer for interacting with
 * the custom databases while maintaining HIPAA compliance through
 * proper sanitization and prepared statements.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Metcalf_Database_Abstraction {
    
    /**
     * Database configurations
     */
    private $databases = array(
        'portal' => 'wp_metcalf_portal',
        'cases' => 'wp_metcalf_cases',
        'docs' => 'wp_metcalf_docs',
        'analytics' => 'wp_metcalf_analytics'
    );
    
    /**
     * Constructor
     */
    public function __construct() {
        // Hook into WordPress init
        add_action('init', array($this, 'init'));
    }
    
    /**
     * Initialize the database abstraction layer
     */
    public function init() {
        // Additional initialization if needed
    }
    
    /**
     * Get a database connection for a specific database
     * 
     * @param string $database_key Database key (portal, cases, docs, analytics)
     * @return wpdb|false Database connection or false on error
     */
    private function get_database_connection($database_key) {
        global $wpdb;
        
        if (!isset($this->databases[$database_key])) {
            error_log("Metcalf Legal System: Invalid database key: " . $database_key);
            return false;
        }
        
        // Create a new wpdb instance for the specific database
        $db = new wpdb(DB_USER, DB_PASSWORD, $this->databases[$database_key], DB_HOST);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Database connection error for {$database_key}: " . $db->last_error);
            return false;
        }
        
        return $db;
    }
    
    /**
     * Execute a prepared query on a specific database
     * 
     * @param string $database_key Database key
     * @param string $query SQL query with placeholders
     * @param array $args Query arguments
     * @return mixed Query result
     */
    public function query($database_key, $query, $args = array()) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        if (!empty($args)) {
            $prepared_query = $db->prepare($query, $args);
        } else {
            $prepared_query = $query;
        }
        
        $result = $db->query($prepared_query);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Query error: " . $db->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Get results from a prepared query
     * 
     * @param string $database_key Database key
     * @param string $query SQL query with placeholders
     * @param array $args Query arguments
     * @param string $output Output type (OBJECT, ARRAY_A, ARRAY_N)
     * @return mixed Query results
     */
    public function get_results($database_key, $query, $args = array(), $output = OBJECT) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        if (!empty($args)) {
            $prepared_query = $db->prepare($query, $args);
        } else {
            $prepared_query = $query;
        }
        
        $results = $db->get_results($prepared_query, $output);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Get results error: " . $db->last_error);
            return false;
        }
        
        return $results;
    }
    
    /**
     * Get a single row from a prepared query
     * 
     * @param string $database_key Database key
     * @param string $query SQL query with placeholders
     * @param array $args Query arguments
     * @param string $output Output type (OBJECT, ARRAY_A, ARRAY_N)
     * @return mixed Single row result
     */
    public function get_row($database_key, $query, $args = array(), $output = OBJECT) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        if (!empty($args)) {
            $prepared_query = $db->prepare($query, $args);
        } else {
            $prepared_query = $query;
        }
        
        $result = $db->get_row($prepared_query, $output);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Get row error: " . $db->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Get a single variable from a prepared query
     * 
     * @param string $database_key Database key
     * @param string $query SQL query with placeholders
     * @param array $args Query arguments
     * @return mixed Single variable result
     */
    public function get_var($database_key, $query, $args = array()) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        if (!empty($args)) {
            $prepared_query = $db->prepare($query, $args);
        } else {
            $prepared_query = $query;
        }
        
        $result = $db->get_var($prepared_query);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Get var error: " . $db->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Insert data into a table with prepared statements
     * 
     * @param string $database_key Database key
     * @param string $table Table name
     * @param array $data Data to insert
     * @param array $format Data format (optional)
     * @return int|false Insert ID on success, false on error
     */
    public function insert($database_key, $table, $data, $format = null) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        $result = $db->insert($table, $data, $format);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Insert error: " . $db->last_error);
            return false;
        }
        
        return $db->insert_id;
    }
    
    /**
     * Update data in a table with prepared statements
     * 
     * @param string $database_key Database key
     * @param string $table Table name
     * @param array $data Data to update
     * @param array $where Where conditions
     * @param array $format Data format (optional)
     * @param array $where_format Where format (optional)
     * @return int|false Number of affected rows on success, false on error
     */
    public function update($database_key, $table, $data, $where, $format = null, $where_format = null) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        $result = $db->update($table, $data, $where, $format, $where_format);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Update error: " . $db->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Delete data from a table with prepared statements
     * 
     * @param string $database_key Database key
     * @param string $table Table name
     * @param array $where Where conditions
     * @param array $where_format Where format (optional)
     * @return int|false Number of affected rows on success, false on error
     */
    public function delete($database_key, $table, $where, $where_format = null) {
        $db = $this->get_database_connection($database_key);
        
        if (!$db) {
            return false;
        }
        
        $result = $db->delete($table, $where, $where_format);
        
        if ($db->last_error) {
            error_log("Metcalf Legal System: Delete error: " . $db->last_error);
            return false;
        }
        
        return $result;
    }
    
    /**
     * Sanitize and validate input data for HIPAA compliance
     * 
     * @param mixed $data Data to sanitize
     * @param string $type Data type (text, email, int, etc.)
     * @return mixed Sanitized data
     */
    public function sanitize_data($data, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($data);
            case 'int':
                return intval($data);
            case 'float':
                return floatval($data);
            case 'url':
                return esc_url_raw($data);
            case 'text':
            default:
                return sanitize_text_field($data);
        }
    }
    
    /**
     * Log security events for HIPAA compliance
     * 
     * @param string $action Action performed
     * @param array $data Additional data
     */
    public function log_security_event($action, $data = array()) {
        $log_entry = array(
            'timestamp' => current_time('mysql'),
            'user_id' => get_current_user_id(),
            'action' => sanitize_text_field($action),
            'ip_address' => $this->get_client_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : '',
            'data' => wp_json_encode($data)
        );
        
        error_log('Metcalf Legal Security Log: ' . wp_json_encode($log_entry));
    }
    
    /**
     * Get client IP address securely
     * 
     * @return string Client IP address
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
    }
}
