<?php include("./partials/navbar.php"); ?>

<?php
if (isset($_POST['id'])) {

  $id = intval($_POST['id']);

  // Step 1: Fetch image name BEFORE deleting
  $sql_fetch = "SELECT image_name FROM tbl_category WHERE id=$id";
  $res_fetch  = mysqli_query($conn, $sql_fetch);

  if ($res_fetch && mysqli_num_rows($res_fetch) == 1) {
    $row        = mysqli_fetch_assoc($res_fetch);
    $image_name = $row['image_name'];

    // Step 2: Delete from DB FIRST — always, regardless of file
    $sql_delete = "DELETE FROM tbl_category WHERE id=$id";
    $res_delete = mysqli_query($conn, $sql_delete) or die(mysqli_error($conn));

    if ($res_delete == True) {
      // Step 3: Delete image file AFTER DB success — best effort
      if (!empty($image_name)) {
        $file_path = "../img/category/" . $image_name;
        if (file_exists($file_path)) {
          unlink($file_path);
        }
        // If file is missing, silently ignore — DB is already deleted
      }
      $_SESSION['cat-s'] = "Category deleted successfully!";
    } else {
      $_SESSION['cat-e'] = "Failed to delete Category!";
    }

  } else {
    $_SESSION['cat-e'] = "Category not found.";
  }

} else {
  $_SESSION['cat-e'] = "Invalid request.";
}

header('location: ' . SITEURL . 'admin/manage-category.php');
exit();
?>
