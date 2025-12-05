<?php

/**
 * Custom autoloader for App classes
 * This ensures App classes are always loaded regardless of Composer issues
 */
spl_autoload_register(function ($className) {
    // Only handle App namespace
    if (strpos($className, 'App\\') !== 0) {
        return false;
    }
    
    // Convert namespace to file path
    $relativeClass = substr($className, 4);
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';
    
    // Check if file exists and load it
    if (file_exists($file)) {
        require $file;
        return true;
    }
    
    return false;
}, true, true);