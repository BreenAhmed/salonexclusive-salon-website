<?php
/**
 * Database Configuration for Salon Exclusive
 * This file contains the database connection settings
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'u100517965_saloon');
define('DB_USER', 'u100517965_saloon');
define('DB_PASS', 'Brezzo#0751');
define('DB_CHARSET', 'utf8mb4');

/**
 * Database connection class
 */
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    private $charset = DB_CHARSET;
    public $conn;

    /**
     * Get database connection
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $socketPath = '/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock';
            if (file_exists($socketPath)) {
                $dsn = "mysql:unix_socket={$socketPath};dbname=" . $this->db_name . ";charset=" . $this->charset;
            } else {
                $dsn = "mysql:host=" . $this->host . ";port=3306;dbname=" . $this->db_name . ";charset=" . $this->charset;
            }
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            throw $exception;
        }

        return $this->conn;
    }
}

/**
 * Helper function to get database connection
 */
function getDBConnection() {
    $database = new Database();
    return $database->getConnection();
}

/**
 * Helper function to execute query and return results
 */
function executeQuery($query, $params = []) {
    try {
        $db = getDBConnection();
        if (!$db) {
            throw new Exception('Database connection is not available.');
        }
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        error_log("Database query error: " . $e->getMessage());
        throw new Exception("Database query failed: " . $e->getMessage());
    }
}

/**
 * Helper function to get all products by category
 */
function getProductsByCategory($category) {
    $query = "SELECT * FROM products WHERE category = ? AND status = 'active' ORDER BY featured DESC, name ASC";
    $stmt = executeQuery($query, [$category]);
    return $stmt->fetchAll();
}

/**
 * Helper function to get a single active product by ID
 */
function getProductById($id) {
    $query = "SELECT * FROM products WHERE id = ? AND status = 'active' LIMIT 1";
    $stmt = executeQuery($query, [$id]);
    return $stmt->fetch();
}

/**
 * Helper function to get all published blog posts
 */
function getPublishedBlogPosts($limit = null) {
    $query = "SELECT * FROM blog_posts WHERE status = 'published' AND published_at IS NOT NULL ORDER BY published_at DESC";
    if ($limit) {
        $query .= " LIMIT " . intval($limit);
    }
    $stmt = executeQuery($query);
    return $stmt->fetchAll();
}

/**
 * Helper function to get blog post by slug
 */
function getBlogPostBySlug($slug) {
    $query = "SELECT * FROM blog_posts WHERE slug = ? AND status = 'published'";
    $stmt = executeQuery($query, [$slug]);
    return $stmt->fetch();
}

/**
 * Helper function to get blog post by ID
 */
function getBlogPostById($id) {
    // First try with published_at check (matches getPublishedBlogPosts)
    $query = "SELECT * FROM blog_posts WHERE id = ? AND status = 'published' AND published_at IS NOT NULL";
    $stmt = executeQuery($query, [$id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // If not found but post exists and is published, return it anyway (fallback for posts without published_at)
    if (!$post) {
        $query = "SELECT * FROM blog_posts WHERE id = ? AND status = 'published'";
        $stmt = executeQuery($query, [$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return $post;
}

/**
 * Helper function to insert contact inquiry
 */
function insertContactInquiry($data) {
    $query = "INSERT INTO contact_inquiries (name, email, phone, subject, message, inquiry_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = executeQuery($query, [
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['subject'],
        $data['message'],
        $data['inquiry_type']
    ]);
    return $stmt->rowCount() > 0;
}


/**
 * Helper function to get all contact inquiries
 */
function getContactInquiries() {
    $query = "SELECT * FROM contact_inquiries ORDER BY created_at DESC";
    $stmt = executeQuery($query);
    return $stmt->fetchAll();
}
?>
