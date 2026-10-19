<?php
session_start();
require_once '../config/database.php';

// Authentication check
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['media']) || $_FILES['media']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['media'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

// Define allowed file types
$allowed_image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$allowed_video_extensions = ['mp4', 'webm', 'ogg', 'avi', 'mov'];
$allowed_extensions = array_merge($allowed_image_extensions, $allowed_video_extensions);

// Validate file extension
if (!in_array($file_extension, $allowed_extensions)) {
    echo json_encode(['success' => false, 'message' => 'File type not allowed. Allowed: ' . implode(', ', $allowed_extensions)]);
    exit;
}

// Check file size (10MB limit)
$max_file_size = 10 * 1024 * 1024; // 10MB
if ($file['size'] > $max_file_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size: 10MB']);
    exit;
}

// Determine upload directory based on file type
$upload_dir = in_array($file_extension, $allowed_image_extensions) ? '../uploads/blog/images/' : '../uploads/blog/videos/';

// Create directory if it doesn't exist
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generate unique filename
$file_name = uniqid() . '_' . time() . '.' . $file_extension;
$file_path = $upload_dir . $file_name;

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $file_path)) {
    // Determine file type and generate appropriate response
    $file_type = in_array($file_extension, $allowed_image_extensions) ? 'image' : 'video';
    $relative_path = 'uploads/blog/' . ($file_type === 'image' ? 'images/' : 'videos/') . $file_name;
    
    // Generate HTML based on file type
    if ($file_type === 'image') {
        $html = '<img src="' . $relative_path . '" alt="Uploaded image" style="max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0;">';
    } else {
        $html = '<video controls style="max-width: 100%; height: auto; border-radius: 8px; margin: 10px 0;"><source src="' . $relative_path . '" type="video/' . $file_extension . '">Your browser does not support the video tag.</video>';
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'File uploaded successfully',
        'file_path' => $relative_path,
        'file_type' => $file_type,
        'html' => $html
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}
?>
