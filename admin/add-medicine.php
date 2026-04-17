<?php include("./partials/navbar.php"); ?>

<?php
// Load categories for dropdown
$sql_cats = "SELECT id, title FROM tbl_category WHERE active='Yes' ORDER BY title ASC";
$res_cats  = mysqli_query($conn, $sql_cats);
?>

<div class="red-msg">
  <?php
  if (isset($_SESSION['med-e'])) {
    echo $_SESSION['med-e'];
    unset($_SESSION['med-e']);
  }
  ?>
</div>

<div class="main-content">
  <center><h2>Add Medicine</h2></center>
  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-30">
      <tr>
        <td>Medicine Name:</td>
        <td><input type="text" name="title" placeholder="Enter Medicine Name" required></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name="description" placeholder="Enter Description" rows="4" style="width:100%;"></textarea></td>
      </tr>
      <tr>
        <td>Price (Rs.):</td>
        <td><input type="number" name="price" placeholder="0.00" step="0.01" min="0" required></td>
      </tr>
      <tr>
        <td>Category:</td>
        <td>
          <select name="cat_id" required>
            <option value="">-- Select Category --</option>
            <?php
            if ($res_cats && mysqli_num_rows($res_cats) > 0) {
              while ($cat = mysqli_fetch_assoc($res_cats)) {
                echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['title']) . "</option>";
              }
            }
            ?>
          </select>
        </td>
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
          <input type="submit" name="submit" value="Add Medicine" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php
if (isset($_POST['submit'])) {

  $title       = $_POST['title'];
  $description = $_POST['description'];
  $price       = floatval($_POST['price']);
  $cat_id      = intval($_POST['cat_id']);
  $featured    = $_POST['featured'];
  $active      = $_POST['active'];
  $image_name  = "";

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type     = mime_content_type($_FILES['image']['tmp_name']);
    $max_size      = 5 * 1024 * 1024;

    if (!in_array($file_type, $allowed_types)) {
      $_SESSION['med-e'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
      header('location: ' . SITEURL . 'admin/add-medicine.php');
      exit();
    }

    if ($_FILES['image']['size'] > $max_size) {
      $_SESSION['med-e'] = "File too large. Max 5MB allowed.";
      header('location: ' . SITEURL . 'admin/add-medicine.php');
      exit();
    }

    $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $image_name = "Medicine_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);
    $upload_dir = "../img/medicine/";

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
      $_SESSION['med-e'] = "Failed to upload image! Check folder permissions on img/medicine/";
      header('location: ' . SITEURL . 'admin/add-medicine.php');
      exit();
    }
  }

  $title_safe    = mysqli_real_escape_string($conn, $title);
  $desc_safe     = mysqli_real_escape_string($conn, $description);
  $image_safe    = mysqli_real_escape_string($conn, $image_name);
  $featured_safe = mysqli_real_escape_string($conn, $featured);
  $active_safe   = mysqli_real_escape_string($conn, $active);

  $sql = "INSERT INTO tbl_med SET
    title='$title_safe',
    description='$desc_safe',
    price=$price,
    image_name='$image_safe',
    cat_id=$cat_id,
    featured='$featured_safe',
    active='$active_safe'
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($res == True) {
    $_SESSION['med-s'] = "Medicine added successfully!";
    header('location: ' . SITEURL . 'admin/manage-medicine.php');
  } else {
    if (!empty($image_name) && file_exists("../img/medicine/" . $image_name)) {
      unlink("../img/medicine/" . $image_name);
    }
    $_SESSION['med-e'] = "Failed to add Medicine!";
    header('location: ' . SITEURL . 'admin/add-medicine.php');
  }
}
?>

<?php include("./partials/footer.php"); ?>
