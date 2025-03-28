<?php

namespace Utility;

class Functions
{
    /**
     * Sanitize a string by removing potentially dangerous characters for PHP, MySQL, and other systems.
     *
     * @param string $input - The input string to sanitize.
     * @return string - The sanitized string.
     */
    public static function sanitizeInput(string $input): string
    {
        // Remove risky characters using regex
        $input = preg_replace('/[;\'"\\\|&`<>]/', '', $input); // Remove characters ; ' " \ | & ` < >

        // Strip PHP tags and HTML tags (prevents embedding harmful code)
        $input = strip_tags($input);

        // Trim whitespace (not necessarily a security risk, but helpful for cleaning input)
        $input = trim($input);

        // Remove null bytes (used for injection attacks)
        $input = str_replace("\0", '', $input);

        // Limit further encoding-based attacks
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        return $input;
    }

    /**
     * Sanitize an array by removing potentially dangerous characters for PHP, MySQL, and other systems.
     *
     * @param array $data - The input array to sanitize.
     * @return array - The sanitized array.
     */
    public static function sanitizeArray(array $data) {
        return array_map(function($item) {
            if (is_array($item)) {
                return self::sanitizeArray($item);
            } else {
                return Functions::sanitizeInput($item);
            }
        }, $data);
    }

    /**
     * Uploads an image file to the server.
     *
     * @param array $file - The uploaded file data.
     * @param string $targetFilePath - The target file path to save the uploaded file.
     * @return string - The status message of the upload operation.
     */
    public static function uploadImage(array $file, string $targetFilePath): string
    {
        $uploadDir = "../images/";

        // Ensure the directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Check if a file is uploaded
        if (!isset($file)) {
            return "No file uploaded.";
        }

        if ($file["size"] > 2 * 1024 * 1024) { // 2MB limit
            return "File is too large.";
        }

        // Validate MIME type (ensure it's actually a PNG file)
        $fileType = mime_content_type($file["tmp_name"]);
        if ($fileType !== "image/png") {
            return "Invalid file type. Only PNG images are allowed.";
        }

        // Move the uploaded file to the target location
        if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
            return "File uploaded successfully.";
        } else {
            $error = error_get_last();
            return "Error uploading file: " . $error["message"];
        }
    }

    /**
     * Deletes an image file from the server.
     *
     * @param string $filePath - The path to the file to delete.
     * @return string - The status message of the delete operation.
     */
    public static function deleteImage(string $filePath): string
    {
        // Check if the file exists
        if (file_exists($filePath)) {
            // Try to delete the file
            if (unlink($filePath)) {
                return "File deleted successfully: " . basename($filePath);
            } else {
                $error = error_get_last();
                return "Error: Could not delete the file " . $error["message"];
            }
        } else {
            $error = error_get_last();
            return "Error: File does not exist." . $error["message"];
        }
    }
}