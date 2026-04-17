<?php
include("../partials/navbar.php");

// Only process if form is submitted
if (isset($_POST['submit'])) {

    $title    = trim($_POST['title']);
    $featured = $_POST['featured'];
    $active   = $_POST['active'];

    // ---------- IMAGE UPLOAD ----------
    $image_name = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']); // safer than ['type']
        $max_size      = 5 * 1024 * 1024; // 5MB

        if (!in_array($file_type, $allowed_types)) {
            $_SESSION['cat-e'] = "Invalid file type. Only JPG, PNG, GIF, WEBP allowed.";
            header('location: ' . SITEURL . 'admin/add-category.php');
            exit();
        }

        if ($_FILES['image']['size'] > $max_size) {
            $_SESSION['cat-e'] = "File too large. Max size is 5MB.";
            header('location: ' . SITEURL . 'admin/add-category.php');
            exit();
        }

        // Generate unique filename to avoid collisions
        $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "Med_Category_" . time() . "_" . rand(100, 999) . "." . strtolower($ext);

        // Path relative to admin/ → go up one level
        $upload_dir = "../img/category/";

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $upload_path = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $_SESSION['cat-e'] = "Failed to upload image! Check folder permissions on: img/category/";
            header('location: ' . SITEURL . 'admin/add-category.php');
            exit();
        }

    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        // An error occurred that wasn't "no file chosen"
        $_SESSION['cat-e'] = "Image upload error (code: " . $_FILES['image']['error'] . "). Check PHP upload settings.";
        header('location: ' . SITEURL . 'admin/add-category.php');
        exit();
    }

    // ---------- DATABASE INSERT ----------
    $title_safe    = mysqli_real_escape_string($conn, $title);
    $featured_safe = mysqli_real_escape_string($conn, $featured);
    $active_safe   = mysqli_real_escape_string($conn, $active);
    $image_safe    = mysqli_real_escape_string($conn, $image_name);

    $sql = "INSERT INTO tbl_category (title, image_name, featured, active) 
            VALUES ('$title_safe', '$image_safe', '$featured_safe', '$active_safe')";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['cat-s'] = "Category added successfully!";
        header('location: ' . SITEURL . 'admin/categories.php');
        exit();
    } else {
        // DB failed — delete uploaded image to keep things in sync
        if (!empty($image_name) && file_exists($upload_dir . $image_name)) {
            unlink($upload_dir . $image_name);
        }
        $_SESSION['cat-e'] = "Database error: " . mysqli_error($conn);
        header('location: ' . SITEURL . 'admin/add-category.php');
        exit();
    }
}
?>

<div class="main-content">
    <h2>Add New Category</h2>

    <?php if (isset($_SESSION['cat-e'])): ?>
        <div class="red-msg" style="padding:10px; margin-bottom:15px;">
            <?php echo $_SESSION['cat-e']; unset($_SESSION['cat-e']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITEURL; ?>admin/add-category.php" method="POST" enctype="multipart/form-data">
        <table class="tbl-full">
            <tr>
                <td><label>Category Title *</label></td>
                <td><input type="text" name="title" class="input-responsive" required placeholder="e.g. Cold & Cough"></td>
            </tr>
            <tr>
                <td><label>Category Image</label></td>
                <td>
                    <input type="file" name="image" accept="image/*">
                    <small style="color:gray;">Optional. Max 5MB. JPG/PNG/GIF/WEBP.</small>
                </td>
            </tr>
            <tr>
                <td><label>Featured</label></td>
                <td>
                    <select name="featured" class="input-responsive">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Active</label></td>
                <td>
                    <select name="active" class="input-responsive">
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="Add Category" class="btn btn-success">
                    <a href="<?php echo SITEURL; ?>admin/categories.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include("../partials/footer.php"); ?>
