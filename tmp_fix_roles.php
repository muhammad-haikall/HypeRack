<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$kernel->handle($request = \Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;

try {
    // Add role column if not exists
    $hasColumn = DB::select("SHOW COLUMNS FROM petugas LIKE 'role'");
    if (empty($hasColumn)) {
        DB::statement("ALTER TABLE petugas ADD COLUMN role VARCHAR(20) DEFAULT 'petugas'");
        echo "Column 'role' created.\n";
    }
    
    // Set ID 1 as admin
    DB::table('petugas')->where('id', 1)->update(['role' => 'admin']);
    echo "ID 1 set to 'admin'.\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
