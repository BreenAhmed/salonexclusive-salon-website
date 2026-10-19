<?php
/**
 * File Upload Handler for Admin Panel
 * Handles product image uploads with validation and security
 */

// Set maximum file size (5MB)
$maxFileSize = 5 * 1024 * 1024; // 5MB in bytes

// Allowed file types
$allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

// Upload directory
$uploadDir = '../uploads/products/';

// Function to validate file
function validateFile($file) {
    global $maxFileSize, $allowedTypes;
    
    $errors = [];
    
    // Check if file was uploaded
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'No file uploaded or upload error occurred.';
        return $errors;
    }
    
    // Check file size
    if ($file['size'] > $maxFileSize) {
        $errors[] = 'File size exceeds 5MB limit.';
    }
    
    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedTypes)) {
        $errors[] = 'Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.';
    }
    
    // Check file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (!in_array($extension, $allowedExtensions)) {
        $errors[] = 'Invalid file extension.';
    }
    
    return $errors;
}

// Function to generate unique filename
function generateUniqueFilename($originalName) {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $timestamp = time();
    $randomString = substr(md5(uniqid(rand(), true)), 0, 8);
    return $timestamp . '_' . $randomString . '.' . $extension;
}

// Function to resize image if needed
function resizeImage($sourcePath, $destinationPath, $maxWidth = 800, $maxHeight = 600) {
    $imageInfo = getimagesize($sourcePath);
    $width = $imageInfo[0];
    $height = $imageInfo[1];
    $mimeType = $imageInfo['mime'];
    
    // Calculate new dimensions
    $ratio = min($maxWidth / $width, $maxHeight / $height);
    $newWidth = intval($width * $ratio);
    $newHeight = intval($height * $ratio);
    
    // Create image resource based on type
    switch ($mimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourcePath);
            break;
        case 'image/webp':
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }
    
    if (!$sourceImage) {
        return false;
    }
    
    // Create new image
    $newImage = imagecreatetruecolor($newWidth, $newHeight);
    
    // Preserve transparency for PNG and GIF
    if ($mimeType == 'image/png' || $mimeType == 'image/gif') {
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);
        $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
        imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
    }
    
    // Resize image
    imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
    
    // Save resized image
    $result = false;
    switch ($mimeType) {
        case 'image/jpeg':
            $result = imagejpeg($newImage, $destinationPath, 85);
            break;
        case 'image/png':
            $result = imagepng($newImage, $destinationPath, 8);
            break;
        case 'image/gif':
            $result = imagegif($newImage, $destinationPath);
            break;
        case 'image/webp':
            $result = imagewebp($newImage, $destinationPath, 85);
            break;
    }
    
    // Clean up
    imagedestroy($sourceImage);
    imagedestroy($newImage);
    
    return $result;
}

// Handle AJAX upload request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['product_image'])) {
    header('Content-Type: application/json');
    
    $file = $_FILES['product_image'];
    
    // Validate file
    $errors = validateFile($file);
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit;
    }
    
    // Generate unique filename
    $filename = generateUniqueFilename($file['name']);
    $uploadPath = $uploadDir . $filename;
    
    // Ensure upload directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Resize image
        $resizedPath = $uploadDir . 'resized_' . $filename;
        if (resizeImage($uploadPath, $resizedPath)) {
            // Use resized image
            unlink($uploadPath);
            rename($resizedPath, $uploadPath);
        }
        
        echo json_encode([
            'success' => true,
            'filename' => $filename,
            'path' => 'uploads/products/' . $filename,
            'message' => 'File uploaded successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'errors' => ['Failed to move uploaded file.']
        ]);
    }
    exit;
}

// Handle file deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_file') {
    header('Content-Type: application/json');
    
    $filename = $_POST['filename'] ?? '';
    $filePath = $uploadDir . $filename;
    
    if (file_exists($filePath) && unlink($filePath)) {
        echo json_encode([
            'success' => true,
            'message' => 'File deleted successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'errors' => ['Failed to delete file.']
        ]);
    }
    exit;
}
?>
