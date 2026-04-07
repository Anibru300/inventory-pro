<?php
// Test file to diagnose issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Version: " . PHP_VERSION . "\n";
echo "Current Directory: " . getcwd() . "\n";

try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "Autoloader: OK\n";
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "App bootstrap: OK\n";
    
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "Kernel: OK\n";
    
    // Test database
    $pdo = DB::connection()->getPdo();
    echo "Database: OK\n";
    echo "DB Driver: " . DB::getDriverName() . "\n";
    
    // Test tables
    $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Tables: " . count($tables) . "\n";
    foreach ($tables as $table) {
        echo "  - {$table->name}\n";
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
