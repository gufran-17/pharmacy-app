<?php
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

// Step 2: Delete from DB FIRST
$sql_delete = "DELETE FROM tbl_med WHERE id = $id";
$res_delete = mysqli_query($conn, $sql_delete);

if ($res_delete) {

    // Step 3: Delete image (best effort)
    if (!empty($image_name)) {
        $file_path = "../img/medicine/" . $image_name;

        if (file_exists($file_path)) {
            if (!unlink($file_path)) {
                error_log("Could not delete image: " . $file_path);
            }
        }
    }

    $_SESSION['med-s'] = "Medicine deleted successfully!";
} else {
    $_SESSION['med-e'] = "Failed to delete medicine: " . mysqli_error($conn);
}

header('location: ' . SITEURL . 'admin/medicines.php');
exit();
?>