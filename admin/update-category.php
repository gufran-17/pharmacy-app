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
    <h2>Update Category</h2>
  </center>

<?php 
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_category WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
      // echo "Category available";
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $current_image = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
      }
      else{
        header('location:'.SITEURL.'admin/manage-category.php');
      }
    }
  }
  else {
    header('location:'.SITEURL.'admin/manage-category.php');
  }
?>

  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-45">
    <tr>
        <td>Title: </td>
        <td><input type="text" name="title" value="<?php echo $title; ?>"></td>
      </tr>
      <tr>
        <td>Current Image:</td>
        <td>
          <?php 
            if ($current_image != "") {
              ?>
              <img src="../img/category/<?php echo $current_image; ?>" alt="Current Image" width="100px">
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
          <input type="submit" name="submit" value="Update Category" class="btn btn-info">
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
        $image_name = "Med_Category_".rand(000, 999).".".$ext;

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../img/category/".$image_name;

        $upload = move_uploaded_file($source_path, $destination_path);

        if ($upload == False) {
          $_SESSION['upload'] = "Failed to upload image!";
          header("location:". SITEURL .'admin/update-category.php');
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

    $sql = "UPDATE tbl_category SET
    title='$title',
    image_name='$image_name',
    featured='$featured',
    active='$active'
    WHERE id = $id
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  if ($res == True) {
    // delete old image AFTER DB success
    if ($current_image != "" && $image_name != $current_image) {
      $remove_path = "../img/category/".$current_image;

      if(file_exists($remove_path)){
        unlink($remove_path);
      }
    }
    // create session variable to display message
    $_SESSION['update'] = "Category updated sucessfully!";
    // redirect page to manage category
    header("location:" . SITEURL . 'admin/manage-category.php');
  } else {
    // create session variable to display message
    $_SESSION['update'] = "Failed to update Category!";
    // redirect page to add category
    header("location:" . SITEURL . 'admin/update-category.php');
  }
  }
?>

<?php include("./partials/footer.php"); ?>