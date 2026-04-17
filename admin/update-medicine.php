<?php
include("../partials/navbar.php");

// ---------- LOAD EXISTING MEDICINE ----------
if (!isset($_GET['id'])) {
    header('location: ' . SITEURL . 'admin/medicines.php');
    exit();
}

$id = intval($_GET['id']);

$sql_fetch = "SELECT * FROM tbl_med WHERE id = $id";
$res_fetch  = mysqli_query($conn, $sql_fetch);

if (!$res_fetch || mysqli_num_rows($res_fetch) !== 1) {
    $_SESSION['med-e'] = "Medicine not found.";
    header('location: ' . SITEURL . 'admin/medicines.php');
    exit();
}

$medicine       = mysqli_fetch_assoc($res_fetch);
$old_image_name = $medicine['image_name'];

// Load categories for dropdown
$sql_cats = "SELECT id, title FROM tbl_category WHERE active='Yes' ORDER BY title ASC";
$res_cats  = mysqli_query($conn, $sql_cats);

// ---------- PROCESS FORM SUBMIT ----------
if (isset($_POST['submit'])) {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = floatval($_POST['price']);
    $cat_id      = intval($_POST['cat_id']);
    $featured    = $_POST['featured'];
    $active      = $_POST['active'];

    $upload_dir    = "../img/medicine/";
    $new_image     = $old_image_name; // default: keep existing
    $image_changed = false;

    // ---------- CHECK IF NEW IMAGE WAS UPLOADED ----------
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

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_image)) {
            $_SESSION['med-e'] = "Failed to upload new image! Check folder permissions.";
            header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
            exit();
        }

        $image_changed = true;
    }

    // ---------- UPDATE DATABASE ----------
    $title_safe    = mysqli_real_escape_string($conn, $title);
    $desc_safe     = mysqli_real_escape_string($conn, $description);
    $image_safe    = mysqli_real_escape_string($conn, $new_image);
    $featured_safe = mysqli_real_escape_string($conn, $featured);
    $active_safe   = mysqli_real_escape_string($conn, $active);

    $sql_update = "UPDATE tbl_med 
                   SET title='$title_safe', description='$desc_safe', price=$price,
                       image_name='$image_safe', cat_id=$cat_id, 
                       featured='$featured_safe', active='$active_safe'
                   WHERE id=$id";

    $res_update = mysqli_query($conn, $sql_update);

    if ($res_update) {
        // DB updated — now safely delete old image if a new one replaced it
        if ($image_changed && !empty($old_image_name)) {
            $old_path = $upload_dir . $old_image_name;
            if (file_exists($old_path)) {
                unlink($old_path);
                // Silent fail is fine — DB is already updated
            }
        }

        $_SESSION['med-s'] = "Medicine updated successfully!";
        header('location: ' . SITEURL . 'admin/medicines.php');
        exit();

    } else {
        // DB failed — rollback: delete newly uploaded image
        if ($image_changed && !empty($new_image) && file_exists($upload_dir . $new_image)) {
            unlink($upload_dir . $new_image);
        }
        $_SESSION['med-e'] = "Database update failed: " . mysqli_error($conn);
        header('location: ' . SITEURL . 'admin/update-medicine.php?id=' . $id);
        exit();
    }
}
?>

<div class="main-content">
    <h2>Update Medicine</h2>

    <?php if (isset($_SESSION['med-e'])): ?>
        <div class="red-msg" style="padding:10px; margin-bottom:15px;">
            <?php echo $_SESSION['med-e']; unset($_SESSION['med-e']); ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo SITEURL; ?>admin/update-medicine.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <table class="tbl-full">
            <tr>
                <td><label>Medicine Name *</label></td>
                <td>
                    <input type="text" name="title" class="input-responsive" required
                           value="<?php echo htmlspecialchars($medicine['title']); ?>">
                </td>
            </tr>
            <tr>
                <td><label>Description</label></td>
                <td>
                    <textarea name="description" class="input-responsive" rows="4">
                        <?php echo htmlspecialchars($medicine['description']); ?>
                    </textarea>
                </td>
            </tr>
            <tr>
                <td><label>Price (Rs.) *</label></td>
                <td>
                    <input type="number" name="price" class="input-responsive" step="0.01" min="0" required
                           value="<?php echo $medicine['price']; ?>">
                </td>
            </tr>
            <tr>
                <td><label>Category *</label></td>
                <td>
                    <select name="cat_id" class="input-responsive" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        if ($res_cats && mysqli_num_rows($res_cats) > 0) {
                            while ($cat = mysqli_fetch_assoc($res_cats)) {
                                $selected = ($cat['id'] == $medicine['cat_id']) ? 'selected' : '';
                                echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($cat['title']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Current Image</label></td>
                <td>
                    <?php if (!empty($medicine['image_name'])): ?>
                        <?php $img_path = "../img/medicine/" . $medicine['image_name']; ?>
                        <?php if (file_exists($img_path)): ?>
                            <img src="<?php echo SITEURL; ?>img/medicine/<?php echo $medicine['image_name']; ?>"
                                 style="width:80px; height:80px; object-fit:cover; border:1px solid #ccc;">
                            <br><small><?php echo $medicine['image_name']; ?></small>
                        <?php else: ?>
                            <span style="color:red;">Image file missing from disk: <?php echo $medicine['image_name']; ?></span>
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
                        <option value="Yes" <?php echo ($medicine['featured'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No"  <?php echo ($medicine['featured'] == 'No')  ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Active</label></td>
                <td>
                    <select name="active" class="input-responsive">
                        <option value="Yes" <?php echo ($medicine['active'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                        <option value="No"  <?php echo ($medicine['active'] == 'No')  ? 'selected' : ''; ?>>No</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" name="submit" value="Update Medicine" class="btn btn-success">
                    <a href="<?php echo SITEURL; ?>admin/medicines.php" class="btn btn-secondary">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php include("../partials/footer.php"); ?>
