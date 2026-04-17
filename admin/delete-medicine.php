<?php
include("./partials/login-check.php");
include("./partials/navbar.php");

if (!isset($_POST['id'])) {
    header('location: ' . SITEURL . 'admin/medicines.php');
    exit();
}

$id = intval($_POST['id']);

// Step 1: Fetch image name BEFORE deleting the record
$sql_fetch = "SELECT image_name FROM tbl_med WHERE id = $id";
$res_fetch  = mysqli_query($conn, $sql_fetch);

if (!$res_fetch || mysqli_num_rows($res_fetch) !== 1) {
    $_SESSION['med-e'] = "Medicine not found or already deleted.";
    header('location: ' . SITEURL . 'admin/medicines.php');
    exit();
}

$row        = mysqli_fetch_assoc($res_fetch);
$image_name = $row['image_name'];

// Step 2: Delete from DB FIRST — always
$sql_delete = "DELETE FROM tbl_med WHERE id = $id";
$res_delete = mysqli_query($conn, $sql_delete);

if ($res_delete) {
    // Step 3: Attempt file deletion — best effort, never block on failure
    if (!empty($image_name)) {
        $file_path = "../img/medicine/" . $image_name;

        if (file_exists($file_path)) {
            if (!unlink($file_path)) {
                error_log("[Pharmacy] Could not delete medicine image: " . realpath($file_path));
            }
        } else {
            error_log("[Pharmacy] Medicine image not on disk (already missing): " . $file_path);
        }
    }

    $_SESSION['med-s'] = "Medicine deleted successfully!";

} else {
    $_SESSION['med-e'] = "Failed to delete medicine from database: " . mysqli_error($conn);
}

header('location: ' . SITEURL . 'admin/medicines.php');
exit();
?>
