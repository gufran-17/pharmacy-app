<?php
include("../partials/navbar.php");

// Load categories for dropdown
$sql_cats = "SELECT id, title FROM tbl_category WHERE active='Yes' ORDER BY title ASC";
$res_cats  = mysqli_query($conn, $sql_cats);

if (isset($_POST['submit'])) {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $cat_id      = intval($_POST['cat_id']);
    $featured    = $_POST['featured'];
    $active      = $_POST['active'];

    // ---------- IMAGE UPLOAD ----------
    $image_name = "";
    $upload_dir = "../img/medicine/";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']);
        $max_size      = 5 * 1024 * 1024; // 5MB

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

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image_name)) {
            $_SESSION['med-e'] = "Failed to upload image! Check folder permissions on: img/medicine/";
            header('location: ' . SITEURL . 'admin/add-medicine.php');
            exit();
        }

    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $_SESSION['med-e'] = "Image upload error (code: " . $_FILES['image']['error'] . ").";
        header('location: ' . SITEURL . 'admin/add-medicine.php');
        exit();
    }

    // ---------- DATABASE INSERT ----------
    $title_safe   = mysqli_real_escape_string($conn, $title);
    $desc_safe    = mysqli_real_escape_string($conn, $description);
    $image_safe   = mysqli_real_escape_string($conn, $image_name);
    $featured_safe = mysqli_real_escape_string($conn, $featured);
    $active_safe  = mysqli_real_escape_string($conn, $active);

    $sql = "INSERT INTO tbl_med (title, description, price, image_name, cat_id, featured, active) 
            VALUES ('$title_safe', '$desc_safe', $price, '$image_safe', $cat_id, '$featured_safe', '$active_safe')";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        $_SESSION['med-s'] = "Medicine added successfully!";
        header('location: ' . SITEURL . 'admin/medicines.php');
        exit();
    } else {
        // Rollback: delete uploaded image since DB insert failed
        if (!empty($image_name) && file_exists($upload_dir . $image_name)) {
            unlink($upload_dir . $image_name);
        }
        $_SESSION['med-e'] = "Database error: " . mysqli_error($conn);
        header('location: ' . SITEURL . 'admin/add-medicine.php');
        exit();
    }
}
?>

<div class="main-content">
    <h2>Add New Medicine</h2>

    <?php if (isset($_SESSION['med-e'])): ?>
        <div class="red-msg" style="padding:10px; margin-bottom:15px;">
            <?php echo $_SESSION['med-e']; unset($_SESSION['med-e']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITEURL; ?>admin/add-medicine.php" method="POST" enctype="multipart/form-data">
        <table class="tbl-full">
            <tr>
                <td><label>Medicine Name *</label></td>
                <td><input type="text" name="title" class="input-responsive" required placeholder="e.g. Paracetamol 500mg"></td>
            </tr>
            <tr>
                <td><label>Description</label></td>
                <td><textarea name="description" class="input-responsive" rows="4" placeholder="Medicine description..."></textarea></td>
            </tr>
            <tr>
                <td><label>Price (Rs.) *</label></td>
                <td><input type="number" name="price" class="input-responsive" step="0.01" min="0" required placeholder="0.00"></td>
            </tr>
            <tr>
                <td><label>Category *</label></td>
                <td>
                    <select name="cat_id" class="input-responsive" required>
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
                <td><label>Medicine Image</label></td>
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
                    <input type="submit" name="submit" value="Add Medicine" class="btn btn-success">
                    <a href="<?php echo SITEURL; ?>admin/medicines.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include("../partials/footer.php"); ?>
