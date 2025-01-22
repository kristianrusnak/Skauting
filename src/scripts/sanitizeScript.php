<?php
/**
 * Sanitize a string by removing potentially dangerous characters for PHP, MySQL, and other systems.
 *
 * @param string $input - The input string to sanitize.
 * @return string - The sanitized string.
 */
function sanitizeInput($input) {
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
?>