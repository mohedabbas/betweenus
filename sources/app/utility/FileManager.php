<?php

namespace App\Utility;

class FileManager
{


    private static $baseUploadPath = '/../../uploads';
    private static $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    private static $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    private static $allowedSize = 10485760; // 10MB

    public static function uploadGalleryPhoto(array $file, int $userId, int $galleryId) : string|bool
    {
        // Check for file error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        // Validate file types
        if (!in_array($file['type'], self::$allowedMimeTypes)) {
            return '';
        }

        // Define the target directory for gallery photos
        $targetDir = __DIR__. self::$baseUploadPath . '/gallery_photos/' . $userId . '/gallery_' . $galleryId;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
            // Check if the directory was created
            if (!is_dir($targetDir)) {
                return false;
            }
        }

        // Generate a unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFilename = self::generateUniqueFilename($extension);
        $destination    = $targetDir . '/' . $uniqueFilename;

        // Check if the file size is within the allowed limit
        if ($file['size'] >= self::$allowedSize) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Return the relative path to the file
            return '/uploads/gallery_photos/' . $userId . '/gallery_' . $galleryId . '/' . $uniqueFilename;
        }

        return false;
    }


    // Delete a photo from the gallery
    public static function deleteGalleryPhoto(string $imagePath): bool
    {
        $fullPath = __DIR__ . self::$baseUploadPath . $imagePath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }


    public static function emptyGalleryPhotos($galleryId) {
        $targetDir = __DIR__ . self::$baseUploadPath . '/gallery_photos/' . $galleryId;
        if (is_dir($targetDir)) {
            $files = glob($targetDir . '/*'); // Get all file names
            foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Delete file
            }
            }
            return rmdir($targetDir); // Remove the directory itself
        }
        return false;
    }


    public static function generateUniqueFilename($extension)
    {
        return date('YmdHis') . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
    }

}


// public static function uploadProfilePhotos(array $file, $userId) {
//     // Check for file error
//     if ($file['error'] !== UPLOAD_ERR_OK) {
//         return false;
//     }

//     // Validate file types
//     if (!in_array($file['type'], self::$allowedMimeTypes)) {
//         return false;
//     }

//     // Define the target directory for profile pictures
//     $targetDir = self::$baseUploadPath . '/profile_pictures/';

//     if (!is_dir($targetDir)) {
//         mkdir($targetDir, 0775, true);
//         // Check if the directory was created
//         if (!is_dir($targetDir)) {
//             return false;
//         }
//     }

//     // Generate a unique filename
//     $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
//     $uniqueFilename = self::generateUniqueFilename($extension);

//     // Prefix the user id to the filename
//     $destination = $targetDir . '/' . $userId . '_' . $uniqueFilename;

//     // Move the uploaded file to the target directory
//     if (!move_uploaded_file($file['tmp_name'], $destination)) {
//         return false;
//     }

//     return $destination;

// }
