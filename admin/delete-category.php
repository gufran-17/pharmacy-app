<?php
include("../partials/navbar.php");

if (!isset($_POST['id'])) {
    header('location: ' . SITEURL . 'admin/categories.php');
    exit();
}

$id = intval($_POST['id']); // always sanitize IDs

// Step 1: Fetch the category to get the image name BEFORE deleting
$sql_fetch = "SELECT image_name FROM tbl_category WHERE id = $id";
$res_fetch  = mysqli_query($conn, $sql_fetch);

if (!$res_fetch || mysqli_num_rows($res_fetch) !== 1) {
    $_SESSION['cat-e'] = "Category not found or already deleted.";
    header('location: ' . SITEURL . 'admin/categories.php');
    exit();
}

$row        = mysqli_fetch_assoc($res_fetch);
$image_name = $row['image_name'];

// Step 2: DELETE from database FIRST — unconditionally
// Never let file system issues block a database operation
$sql_delete = "DELETE FROM tbl_category WHERE id = $id";
$res_delete = mysqli_query($conn, $sql_delete);

if ($res_delete) {
    // Step 3: AFTER DB success, attempt to delete image file
    // This is best-effort — we do NOT fail if file is missing
    if (!empty($image_name)) {
        $file_path = "../img/category/" . $image_name;

        if (file_exists($file_path)) {
            if (unlink($file_path)) {
                // File deleted successfully — all good
            } else {
                // Could not delete file (permissions?) — log it, don't crash
                error_log("[Pharmacy] Could not delete category image: " . realpath($file_path));
            }
        } else {
            // File wasn't on disk — already gone or never uploaded
            // This is fine — DB record is deleted, nothing else to do
            error_log("[Pharmacy] Category image not found on disk (already missing): " . $file_path);
        }
    }

    $_SESSION['cat-s'] = "Category deleted successfully!";

} else {
    $_SESSION['cat-e'] = "Failed to delete category from database: " . mysqli_error($conn);
}

header('location: ' . SITEURL . 'admin/categories.php');
exit();
?>
