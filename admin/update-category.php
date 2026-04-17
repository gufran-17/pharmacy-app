<?php include("./partials/navbar.php"); ?>

<?php
// Load category data for the form
if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $sql = "SELECT * FROM tbl_category WHERE id=$id";
  $res = mysqli_query($conn, $sql);
  if ($res && mysqli_num_rows($res) == 1) {
    $row            = mysqli_fetch_assoc($res);
    $old_title      = $row['title'];
    $old_image_name = $row['image_name'];
    $old_featured   = $row['featured'];
    $old_active     = $row['active'];
  } else {
    header('location: ' . SITEURL . 'admin/manage-category.php');
    exit();
  }
} else {
  header('location: ' . SITEURL . 'admin/manage-category.php');
  exit();
}
?>

<div class="red-msg">
  <?php
  if (isset($_SESSION['cat-e'])) {
    echo $_SESSION['cat-e'];
    unset($_SESSION['cat-e']);
  }
  ?>
</div>

<div class="main-content">
  <center><h2>Update Category</h2></center>
  <form action="" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="old_image_name" value="<?php echo $old_image_name; ?>">
    <table class="tbl-30">
      <tr>
        <td>Category Title:</td>
        <td><input type="text" name="title" value="<?php echo htmlspecialchars($old_title); ?>" required></td>
      </tr>
      <tr>
        <td>Current Image:</td>
        <td>
          <?php if (!empty($old_image_name) && file_exists("../img/category/" . $old_image_name)): ?>
            <img src="<?php echo SITEURL; ?>img/category/<?php echo $old_image_name; ?>"
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
          <input type="submit" name="submit" value="Update Category" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php
if (isset($_POST['submit'])) {

  $id             = intval($_POST['id']);
  $title          = $_POST['title'];
  $featured       = $_POST['featured'];
  $active         = $_POST['active'];
  $old_image_name = $_POST['old_image_name'];
  $new_image      = $old_image_name; // default: keep existing
  $image_changed  = false;

  // Handle new image upload if provided
  if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $file_type     = mime_content_type($_FILES['image']['tmp_name']);
    $max_size      = 5 * 1024 * 1024;

    if (!in_array($file_type, $allowed_types)) {
      $_SESSION['cat-e'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
      header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
      exit();
    }

    if ($_FILES['image']['size'] > $max_size) {
      $_SESSION['cat-e'] = "File too large. Max 5MB allowed.";
      header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
      exit();
    }

    $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $new_image = "Med_Category_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);
    $upload_dir = "../img/category/";

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_image)) {
      $_SESSION['cat-e'] = "Failed to upload image! Check folder permissions.";
      header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
      exit();
    }

    $image_changed = true;
  }

  // Update database
  $title_safe    = mysqli_real_escape_string($conn, $title);
  $featured_safe = mysqli_real_escape_string($conn, $featured);
  $active_safe   = mysqli_real_escape_string($conn, $active);
  $image_safe    = mysqli_real_escape_string($conn, $new_image);

  $sql = "UPDATE tbl_category SET
    title='$title_safe',
    image_name='$image_safe',
    featured='$featured_safe',
    active='$active_safe'
    WHERE id=$id
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  if ($res == True) {
    // DB updated — now delete old image if a new one was uploaded
    if ($image_changed && !empty($old_image_name)) {
      $old_path = "../img/category/" . $old_image_name;
      if (file_exists($old_path)) {
        unlink($old_path);
      }
      // If file missing on disk, silently ignore — DB already updated
    }
    $_SESSION['cat-s'] = "Category updated successfully!";
    header('location: ' . SITEURL . 'admin/manage-category.php');
  } else {
    // DB failed — delete newly uploaded image to avoid orphan files
    if ($image_changed && !empty($new_image) && file_exists("../img/category/" . $new_image)) {
      unlink("../img/category/" . $new_image);
    }
    $_SESSION['cat-e'] = "Failed to update Category!";
    header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
  }
}
?>

<?php include("./partials/footer.php"); ?>
