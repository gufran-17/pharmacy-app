<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
  if (isset($_SESSION['cat-e'])) {
    echo $_SESSION['cat-e'];
    unset($_SESSION['cat-e']);
  }
  ?>
</div>

<div class="main-content">
  <center><h2>Add Category</h2></center>
  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-30">
      <tr>
        <td>Category Title:</td>
        <td><input type="text" name="title" placeholder="Enter Category Title" required></td>
      </tr>
      <tr>
        <td>Image:</td>
        <td><input type="file" name="image" accept="image/*"></td>
      </tr>
      <tr>
        <td>Featured:</td>
        <td>
          <select name="featured">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Active:</td>
        <td>
          <select name="active">
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="submit" name="submit" value="Add Category" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php
if (isset($_POST['submit'])) {

  $title    = $_POST['title'];
  $featured = $_POST['featured'];
  $active   = $_POST['active'];
  $image_name = "";

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type     = mime_content_type($_FILES['image']['tmp_name']);
    $max_size      = 5 * 1024 * 1024;

    if (!in_array($file_type, $allowed_types)) {
      $_SESSION['cat-e'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
      header('location: ' . SITEURL . 'admin/add-category.php');
      exit();
    }

    if ($_FILES['image']['size'] > $max_size) {
      $_SESSION['cat-e'] = "File too large. Max 5MB allowed.";
      header('location: ' . SITEURL . 'admin/add-category.php');
      exit();
    }

    $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = "Med_Category_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);
    $upload_dir = "../img/category/";

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
      $_SESSION['cat-e'] = "Failed to upload image! Check folder permissions on img/category/";
      header('location: ' . SITEURL . 'admin/add-category.php');
      exit();
    }
  }

  $title_safe    = mysqli_real_escape_string($conn, $title);
  $featured_safe = mysqli_real_escape_string($conn, $featured);
  $active_safe   = mysqli_real_escape_string($conn, $active);
  $image_safe    = mysqli_real_escape_string($conn, $image_name);

  $sql = "INSERT INTO tbl_category SET
    title='$title_safe',
    image_name='$image_safe',
    featured='$featured_safe',
    active='$active_safe'
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($res == True) {
    $_SESSION['cat-s'] = "Category added successfully!";
    header('location: ' . SITEURL . 'admin/manage-category.php');
  } else {
    if (!empty($image_name) && file_exists("../img/category/" . $image_name)) {
      unlink("../img/category/" . $image_name);
    }
    $_SESSION['cat-e'] = "Failed to add Category!";
    header('location: ' . SITEURL . 'admin/add-category.php');
  }
}
?>

<?php include("./partials/footer.php"); ?>
