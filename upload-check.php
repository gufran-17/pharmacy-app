<?php
/**
 * UPLOAD DIAGNOSTICS TOOL
 * Place this file temporarily in your ROOT folder (not admin/)
 * Visit: http://your-domain/upload-check.php
 * DELETE THIS FILE after fixing the issue — do not leave it public!
 */

// Only allow admin session access
session_start();
// Uncomment the lines below to restrict to logged-in admins only:
// if (!isset($_SESSION['admin'])) { die("Access denied."); }

echo "<h2>Upload Diagnostics</h2><pre>";

// 1. PHP upload settings
echo "=== PHP Upload Settings ===\n";
echo "file_uploads:        " . ini_get('file_uploads') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size:       " . ini_get('post_max_size') . "\n";
echo "upload_tmp_dir:      " . (ini_get('upload_tmp_dir') ?: sys_get_temp_dir()) . "\n";
echo "max_file_uploads:    " . ini_get('max_file_uploads') . "\n";
echo "\n";

// 2. Folder existence and permissions
$folders = [
    './img/category/',
    './img/medicine/',
];

echo "=== Folder Status ===\n";
foreach ($folders as $folder) {
    $exists   = is_dir($folder)     ? "EXISTS"     : "MISSING";
    $readable = is_readable($folder) ? "READABLE"   : "NOT READABLE";
    $writable = is_writable($folder) ? "WRITABLE ✅" : "NOT WRITABLE ❌";
    $perms    = file_exists($folder) ? substr(sprintf('%o', fileperms($folder)), -4) : "N/A";

    echo "$folder\n";
    echo "  Status:   $exists\n";
    echo "  Read:     $readable\n";
    echo "  Write:    $writable\n";
    echo "  Perms:    $perms\n\n";
}

// 3. Test write a temp file
echo "=== Write Test ===\n";
$test_file = './img/category/write_test_' . time() . '.txt';
$write_ok  = file_put_contents($test_file, 'test');
if ($write_ok !== false) {
    echo "Write test PASSED ✅ ($test_file)\n";
    unlink($test_file);
    echo "Cleanup done.\n";
} else {
    echo "Write test FAILED ❌\n";
    echo "Fix: Run on your server: chmod 755 img/category img/medicine\n";
    echo "Or:  chown www-data:www-data img/category img/medicine\n";
}

// 4. PHP version and server info
echo "\n=== Server Info ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server:      " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "Script path:   " . __FILE__ . "\n";

echo "</pre>";
echo "<p style='color:red;'><strong>⚠️ Delete this file (upload-check.php) when done!</strong></p>";
?>
