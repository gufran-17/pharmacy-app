<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
  if (isset($_SESSION['add'])) {
    echo $_SESSION['add'];
    unset($_SESSION['add']);
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
    <h2>Add Category</h2>
  </center>
  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-45">
      <tr>
        <td>Title: </td>
        <td><input type="text" name="title" placeholder="Category title"></td>
      </tr>
      <tr>
        <td>Select Image:</td>
        <td><input type="file" name="image"></td>
      </tr>
      <tr>
        <td>Featured: </td>
        <td>
          <input type="radio" name="featured" value="Yes">Yes&nbsp;
          <input type="radio" name="featured" value="No">No
        </td>
      </tr>
      <tr>
        <td>Active: </td>
        <td>
          <input type="radio" name="active" value="Yes">Yes&nbsp;
          <input type="radio" name="active" value="No">No
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
    // echo "clicked";
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
          // redirect page to manage admin
          header("location:". SITEURL .'admin/add-category.php');
          die();
        }
      }
      else {
        $image_name = "";
      }
    }
    else {
      $image_name = "";
    }

    $sql = "INSERT INTO tbl_category SET
      title='$title',
      image_name='$image_name',
      featured='$featured',
      active='$active'
    ";
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($res == True) {
      // echo "Data Inserted";
      // create session variable to display message
      $_SESSION['add'] = "Category added sucessfully!";
      // redirect page to manage category
      header("location:". SITEURL .'admin/manage-category.php');
    } 
    else {
      // echo "Failed to insert data";
      // create session variable to display message
      $_SESSION['add'] = "Failed to add Category!";
      // redirect page to add category
      header("location:" . SITEURL . 'admin/add-category.php');
    }
  }
?>

<?php include("./partials/footer.php"); ?>