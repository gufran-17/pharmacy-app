<?php
include("../partials/navbar.php");

// ---------- LOAD EXISTING CATEGORY ----------
if (!isset($_GET['id'])) {
    header('location: ' . SITEURL . 'admin/categories.php');
    exit();
}

$id = intval($_GET['id']); // sanitize

$sql_fetch = "SELECT * FROM tbl_category WHERE id = $id";
$res_fetch  = mysqli_query($conn, $sql_fetch);

if (!$res_fetch || mysqli_num_rows($res_fetch) !== 1) {
    $_SESSION['cat-e'] = "Category not found.";
    header('location: ' . SITEURL . 'admin/categories.php');
    exit();
}

$category       = mysqli_fetch_assoc($res_fetch);
$old_image_name = $category['image_name'];

// ---------- PROCESS FORM SUBMIT ----------
if (isset($_POST['submit'])) {

    $title    = trim($_POST['title']);
    $featured = $_POST['featured'];
    $active   = $_POST['active'];

    $upload_dir   = "../img/category/";
    $new_image    = $old_image_name; // default: keep existing image
    $image_changed = false;

    // ---------- CHECK IF NEW IMAGE WAS UPLOADED ----------
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']);
        $max_size      = 5 * 1024 * 1024; // 5MB

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

        // Generate unique filename
        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image = "Med_Category_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_image)) {
            $_SESSION['cat-e'] = "Failed to upload new image! Check folder permissions on: img/category/";
            header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
            exit();
        }

        $image_changed = true;
    }

    // ---------- UPDATE DATABASE ----------
    $title_safe    = mysqli_real_escape_string($conn, $title);
    $featured_safe = mysqli_real_escape_string($conn, $featured);
    $active_safe   = mysqli_real_escape_string($conn, $active);
    $image_safe    = mysqli_real_escape_string($conn, $new_image);

    $sql_update = "UPDATE tbl_category 
                   SET title='$title_safe', image_name='$image_safe', 
                       featured='$featured_safe', active='$active_safe' 
                   WHERE id=$id";

    $res_update = mysqli_query($conn, $sql_update);

    if ($res_update) {
        // DB updated successfully — now safely delete old image if a new one was uploaded
        if ($image_changed && !empty($old_image_name)) {
            $old_path = $upload_dir . $old_image_name;
            if (file_exists($old_path)) {
                unlink($old_path);
            }
            // If old file doesn't exist on disk, silently ignore — DB is already updated
        }

        $_SESSION['cat-s'] = "Category updated successfully!";
        header('location: ' . SITEURL . 'admin/categories.php');
        exit();

    } else {
        // DB update failed — delete the newly uploaded image to avoid orphan files
        if ($image_changed && !empty($new_image) && file_exists($upload_dir . $new_image)) {
            unlink($upload_dir . $new_image);
        }
        $_SESSION['cat-e'] = "Database update failed: " . mysqli_error($conn);
        header('location: ' . SITEURL . 'admin/update-category.php?id=' . $id);
        exit();
    }
}
?>

<div class="main-content">
    <h2>Update Category</h2>

    <?php if (isset($_SESSION['cat-e'])): ?>
        <div class="red-msg" style="padding:10px; margin-bottom:15px;">
            <?php echo $_SESSION['cat-e']; unset($_SESSION['cat-e']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITEURL; ?>admin/update-category.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <table class="tbl-full">
            <tr>
                <td><label>Category Title *</label></td>
                <td>
                    <input type="text" name="title" class="input-responsive" required
                           value="<?php echo htmlspecialchars($category['title']); ?>">
                </td>
            </tr>
            <tr>
                <td><label>Current Image</label></td>
                <td>
                    <?php if (!empty($category['image_name'])): ?>
                        <?php
                        $img_path = "../img/category/" . $category['image_name'];
                        if (file_exists($img_path)): ?>
                            <img src="<?php echo SITEURL; ?>img/category/<?php echo $category['image_name']; ?>"
                                 style="width:80px; height:80px; object-fit:cover; border:1px solid #ccc;">
                            <br><small><?php echo $category['image_name']; ?></small>
                        <?php else: ?>
                            <span style="color:red;">Image file missing from disk: <?php echo $category['image_name']; ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span style="color:gray;">No image set</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td><label>Replace Image</label></td>
                <td>
                    <input type="file" name="image" accept="image/*">
                    <small style="color:gray;">Leave blank to keep current image. Max 5MB.</small>
                </td>
            </tr>
            <tr>
                <td><label>Featured</label></td>
                <td>
                    <select name="featured" class="input-responsive">
                        <option value="Yes" <?php echo ($category['featured'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No"  <?php echo ($category['featured'] == 'No')  ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Active</label></td>
                <td>
                    <select name="active" class="input-responsive">
                        <option value="Yes" <?php echo ($category['active'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No"  <?php echo ($category['active'] == 'No')  ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="Update Category" class="btn btn-success">
                    <a href="<?php echo SITEURL; ?>admin/categories.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include("../partials/footer.php"); ?>
