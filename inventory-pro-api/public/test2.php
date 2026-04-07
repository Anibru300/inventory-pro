<?php
echo "PHP is working\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script Name: " . $_SERVER['SCRIPT_NAME'] . "\n";

// Check if Laravel is accessible
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoloader: OK\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App loaded: OK\n";
    
    echo "Routes loaded: " . count(Route::getRoutes()) . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
