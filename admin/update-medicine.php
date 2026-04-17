<?php include("./partials/navbar.php"); ?>

<?php
// Load medicine data for the form
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $sql = "SELECT * FROM tbl_med WHERE id=$id";
  $res = mysqli_query($conn, $sql);
  if ($res && mysqli_num_rows($res) == 1) {
    $med            = mysqli_fetch_assoc($res);
    $old_title      = $med['title'];
    $old_desc       = $med['description'];
    $old_price      = $med['price'];
    $old_cat_id     = $med['cat_id'];
    $old_image_name = $med['image_name'];
    $old_featured   = $med['featured'];
    $old_active     = $med['active'];
  } else {
    header('location: ' . SITEURL . 'admin/manage-medicine.php');
    exit();
  }
} else {
  header('location: ' . SITEURL . 'admin/manage-medicine.php');
  exit();
}

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
  <center><h2>Update Medicine</h2></center>
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="old_image_name" value="<?php echo $old_image_name; ?>">
    <table class="tbl-30">
      <tr>
        <td>Medicine Name:</td>
        <td><input type="text" name="title" value="<?php echo htmlspecialchars($old_title); ?>" required></td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea name="description" rows="4" style="width:100%;"><?php echo htmlspecialchars($old_desc); ?></textarea></td>
      </tr>
      <tr>
        <td>Price (Rs.):</td>
        <td><input type="number" name="price" value="<?php echo $old_price; ?>" step="0.01" min="0" required></td>
      </tr>
      <tr>
        <td>Category:</td>
        <td>
          <select name="cat_id" required>
            <option value="">-- Select Category --</option>
            <?php
            if ($res_cats && mysqli_num_rows($res_cats) > 0) {
              while ($cat = mysqli_fetch_assoc($res_cats)) {
                $sel = ($cat['id'] == $old_cat_id) ? 'selected' : '';
                echo "<option value='" . $cat['id'] . "' $sel>" . htmlspecialchars($cat['title']) . "</option>";
              }
            }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Current Image:</td>
        <td>
          <?php if (!empty($old_image_name) && file_exists("../img/medicine/" . $old_image_name)): ?>
            <img src="<?php echo SITEURL; ?>img/medicine/<?php echo $old_image_name; ?>"
                 style="width:60px; height:60px; object-fit:cover;">
            <br><small><?php echo $old_image_name; ?></small>
          <?php elseif (!empty($old_image_name)): ?>
            <span style="color:red;">File missing on disk: <?php echo $old_image_name; ?></span>
          <?php else: ?>
            <span style="color:gray;">No image set</span>
          <?php endif; ?>
        </td>
      </tr>
      <tr>
        <td>Replace Image:</td>
        <td>
          <input type="file" name="image" accept="image/*">
          <small>Leave blank to keep current image.</small>
        </td>
      </tr>
      <tr>
        <td>Featured:</td>
        <td>
          <select name="featured">
            <option value="Yes" <?php echo ($old_featured == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No"  <?php echo ($old_featured == 'No')  ? 'selected' : ''; ?>>No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Active:</td>
        <td>
          <select name="active">
            <option value="Yes" <?php echo ($old_active == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No"  <?php echo ($old_active == 'No')  ? 'selected' : ''; ?>>No</option>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="submit" name="submit" value="Update Medicine" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php
if (isset($_POST['submit'])) {

  $id             = intval($_POST['id']);
  $title          = $_POST['title'];
  $description    = $_POST['description'];
  $price          = floatval($_POST['price']);
  $cat_id         = intval($_POST['cat_id']);
  $featured       = $_POST['featured'];
  $active         = $_POST['active'];
  $old_image_name = $_POST['old_image_name'];
  $new_image      = $old_image_name; // default: keep existing
  $image_changed  = false;

  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type     = mime_content_type($_FILES['image']['tmp_name']);
    $max_size      = 5 * 1024 * 1024;

    if (!in_array($file_type, $allowed_types)) {
      $_SESSION['med-e'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
      header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
      exit();
    }

    if ($_FILES['image']['size'] > $max_size) {
      $_SESSION['med-e'] = "File too large. Max 5MB allowed.";
      header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
      exit();
    }

    $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_image = "Medicine_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);
    $upload_dir = "../img/medicine/";

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_image)) {
      $_SESSION['med-e'] = "Failed to upload image! Check folder permissions.";
      header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
      exit();
    }

    $image_changed = true;
  }

  $title_safe    = mysqli_real_escape_string($conn, $title);
  $desc_safe     = mysqli_real_escape_string($conn, $description);
  $image_safe    = mysqli_real_escape_string($conn, $new_image);
  $featured_safe = mysqli_real_escape_string($conn, $featured);
  $active_safe   = mysqli_real_escape_string($conn, $active);

  $sql = "UPDATE tbl_med SET
    title='$title_safe',
    description='$desc_safe',
    price=$price,
    image_name='$image_safe',
    cat_id=$cat_id,
    featured='$featured_safe',
    active='$active_safe'
    WHERE id=$id
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($res == True) {
    if ($image_changed && !empty($old_image_name)) {
      $old_path = "../img/medicine/" . $old_image_name;
      if (file_exists($old_path)) {
        unlink($old_path);
      }
    }
    $_SESSION['med-s'] = "Medicine updated successfully!";
    header('location: ' . SITEURL . 'admin/manage-medicine.php');
  } else {
    if ($image_changed && !empty($new_image) && file_exists("../img/medicine/" . $new_image)) {
      unlink("../img/medicine/" . $new_image);
    }
    $_SESSION['med-e'] = "Failed to update Medicine!";
    header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
  }
}
?>

<?php include("./partials/footer.php"); ?>
