<?php
  include("../config/constants.php");
  echo $id = $_GET['id'];
  echo $image_name = $_GET['image_name'];
  if ($image_name != "") {
    $path = "../img/category/".$image_name;
    $remove = unlink($path); 
    if ($remove == False) {
      $_SESSION['remove'] = "Failed to delete category image, could not update category!";
      header('location:'.SITEURL.'admin/manage-category.php');
      die();
    }
  }
  $sql = "DELETE FROM tbl_category WHERE id=$id";
  $res = mysqli_query($conn, $sql);

  if ($res == True) {
    $_SESSION['delete_s'] = "Category deleted sucessfully!";
    // redirect page to manage category
    header('location:'.SITEURL.'admin/manage-category.php');
  }
  else {
    $_SESSION['delete_f'] = "Failed to delete category!";
    // redirect page to manage category
    header('location:'.SITEURL.'admin/manage-category.php');
  }
?>