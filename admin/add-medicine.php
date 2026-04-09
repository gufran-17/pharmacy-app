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
    <h2>Add Medicine</h2>
  </center>
  <form action="" method="POST" enctype="multipart/form-data">
    <table class="tbl-45">
      <tr>
        <td>Title: </td>
        <td><input type="text" name="title" placeholder="Enter Title"></td>
      </tr>
      <tr>
        <td>Description: </td>
        <td>
          <textarea name="description" cols="30" rows="5" placeholder="Enter Description"></textarea>
        </td>
      </tr>
      <tr>
        <td>Price: </td>
        <td><input type="number" name="price" placeholder="Enter Price"></td>
      </tr>
      <tr>
        <td>Category: </td>
        <td>
          <select name="category">
            <?php
              $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
              $res = mysqli_query($conn, $sql);
              $count = mysqli_num_rows($res);
              if ($count > 0) {
                while ($row = mysqli_fetch_assoc($res))
                {
                  $id = $row['id'];
                  $title = $row['title'];
                  ?>
                  <option value="<?php echo $id ?>"><?php echo $title ?></option>
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
          <input type="submit" name="submit" value="Add Medicine" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div> 

<?php
  if (isset($_POST['submit'])) {
    // echo "clicked";
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
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
        $image_name = "Medicine_".rand(000, 999).".".$ext;

        $source_path = $_FILES['image']['tmp_name'];
        $destination_path = "../img/medicine/".$image_name;

        $upload = move_uploaded_file($source_path, $destination_path);

        if ($upload == False) {
          $_SESSION['upload'] = "Failed to upload image!";
          // redirect page to manage admin
          header("location:". SITEURL .'admin/add-medicine.php');
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
    $sql2 = "INSERT INTO tbl_med SET
    title='$title',
    description='$description',
    price=$price,
    image_name='$image_name',
    cat_id=$cat_id,
    featured='$featured',
    active='$active'
    ";
    $res2 = mysqli_query($conn, $sql2) or die(mysqli_error($conn));
    if ($res2 == True) {
      // echo "Data Inserted";
      // create session variable to display message
      $_SESSION['add'] = "Medicine added sucessfully!";
      // redirect page to manage medicine
      header("location:". SITEURL .'admin/manage-medicine.php');
    } 
    else {
      // echo "Failed to insert data";
      // create session variable to display message
      $_SESSION['add'] = "Failed to add Medicine!";
      // redirect page to add medicine
      header("location:" . SITEURL . 'admin/add-medicine.php');
    }
  }
?>

<?php include("./partials/footer.php"); ?>