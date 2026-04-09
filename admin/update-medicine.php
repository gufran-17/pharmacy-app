<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
    if (isset($_SESSION['update'])){
      echo $_SESSION['update'];
      unset($_SESSION['update']);
    } 
  ?>
</div>

<div class="red-msg">
  <?php
    if (isset($_SESSION['remove'])){
      echo $_SESSION['remove'];
      unset($_SESSION['remove']);
    } 
  ?>
</div>

<div class="red-msg">
  <?php
  if (isset($_SESSION['upload'])) {
    echo $_SESSION['upload'];
    unset($_SESSION['upload']);
  }
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Update Medicine</h2>
  </center>
  <?php
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_med WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
        // echo "Medicine available";
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $price = $row['price'];
        $current_image = $row['image_name'];
        $cat_id = $row['cat_id'];
        $featured = $row['featured'];
        $active = $row['active'];
      } else {
        header('location:' . SITEURL . 'admin/manage-medicine.php');
      }
    }
  } else {
    header('location:' . SITEURL . 'admin/manage-medicine.php');
  }
  ?>
  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-45">
    <tr>
        <td>Title: </td>
        <td><input type="text" name="title" value="<?php echo $title; ?>"></td>
      </tr>
      <tr>
        <td>Description: </td>
        <td>
          <textarea name="description" cols="30" rows="5" placeholder="<?php echo $description; ?>"></textarea>
        </td>
      </tr>
      <tr>
        <td>Price: </td>
        <td><input type="number" name="price" value="<?php echo $price; ?>"></td>
      </tr>
      <tr>
        <td>Category: </td>
        <td>
          <select name="category">
            <?php
              $sql2 = "SELECT * FROM tbl_category WHERE active='Yes'";
              $res2 = mysqli_query($conn, $sql2);
              $count = mysqli_num_rows($res2);
              if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res2))
                {
                  $id_c = $row['id'];
                  $title_c = $row['title'];
                  ?>
                  <option value="<?php echo $id_c ?>" <?php if($id_c == $cat_id){ echo "selected"; } ?>><?php echo $title_c ?></option>
                  <?php
                }
              }
              else{
                ?>
                <option value="0">No category Found</option>
                <?php
              }
            ?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Current Image:</td>
        <td>
          <?php 
            if ($current_image != "") {
              ?>
              <img src="../img/medicine/<?php echo $current_image; ?>" alt="Current Image" width="100px">
              <?php
            }
            else{
              echo "Image not Added";
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>Select Image:</td>
        <td><input type="file" name="image"></td>
      </tr>
      <tr>
        <td>Featured: </td>
        <td>
            <input type="radio" name="featured" value="Yes" <?php if ($featured == "Yes"){echo "checked";} ?> >Yes&nbsp;
            <input type="radio" name="featured" value="No" <?php if ($featured == "No"){echo "checked";} ?> >No
        </td>
      </tr>
      <tr>
        <td>Active: </td>
        <td>
          <input type="radio" name="active" value="Yes" <?php if ($active == "Yes"){echo "checked";} ?> >Yes&nbsp;
          <input type="radio" name="active" value="No" <?php if ($active == "No"){echo "checked";} ?> >No
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <input type="hidden" name="current_image" value="<?php echo $current_image; ?>">
          <input type="submit" name="submit" value="Update Medicine" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php 
  if (isset($_POST['submit'])) {
    // echo "button clicked";
    $id = $_POST['id'];
    $current_image = $_POST['current_image'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = isset($_POST['price']) && $_POST['price'] !== "" ? $_POST['price'] : 0;
    if($price == ""){
      $price = 0;
    }
    $cat_id = $_POST['category'];
    if (isset($_POST['featured'])) {
      $featured = $_POST['featured'];
    }
    else {
      $featured = "No";
    }
    if (isset($_POST['active'])) {
      $active = $_POST['active'];
    }
    else {
      $active = "No";
    } 

    if(isset($_FILES['image']['name'])){
      $image_name = $_FILES['image']['name'];

      if ($image_name != "") {
        $temp = explode(".", $image_name);
        $ext = end($temp);
        $image_name = "Med_".rand(000, 999).".".$ext;

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../img/medicine/".$image_name;

        $upload = move_uploaded_file($source_path, $destination_path);

        if ($upload == False) {
          $_SESSION['upload'] = "Failed to upload image!";
          header("location:". SITEURL .'admin/update-medicine.php');
          die();
        }
        
      }
      else {
        $image_name = $current_image;
      }
    }
    else {
      $image_name = $current_image;
    }

    $sql3 = "UPDATE tbl_med SET
    title='$title',
    description='$description',
    price=$price,
    image_name='$image_name',
    cat_id=$cat_id,
    featured='$featured',
    active='$active'
    WHERE id=$id
    ";


  $res3 = mysqli_query($conn, $sql3) or die(mysqli_error($conn));
  if ($res3 == True) {
    

    // ✅ Old image delete AFTER DB success
    if ($current_image != "" && $image_name != $current_image) {
      $remove_path = "../img/medicine/".$current_image;
      unlink($remove_path);
    }

    // create session variable to display message
    $_SESSION['update'] = "Medicine updated sucessfully!";
    // redirect page to manage medicine
    header("location:" . SITEURL . 'admin/manage-medicine.php');
  } else {
    // create session variable to display message
    $_SESSION['update'] = "Failed to update Medicine!";
    // redirect page to add medicine
    header("location:" . SITEURL . 'admin/update-medicine.php');
  }
  }
?>

<?php include("./partials/footer.php"); ?>