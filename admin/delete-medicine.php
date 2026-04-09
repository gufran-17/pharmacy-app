<?php
  include("../config/constants.php");
  echo $id = $_GET['id'];
  echo $image_name = $_GET['image_name'];
  if ($image_name != "") {
    $path = "../img/medicine/".$image_name;
    $remove = unlink($path); 
    if ($remove == False) {
      $_SESSION['remove'] = "Failed to delete medicine image, could not update medicine!";
      header('location:'.SITEURL.'admin/manage-medicine.php');
      die();
    }
  }
  $sql = "DELETE FROM tbl_med WHERE id=$id";
  $res = mysqli_query($conn, $sql);

  if ($res == True) {
    $_SESSION['delete_s'] = "medicine deleted sucessfully!";
    // redirect page to manage medicine
    header('location:'.SITEURL.'admin/manage-medicine.php');
  }
  else {
    $_SESSION['delete_f'] = "Failed to delete medicine!";
    // redirect page to manage medicine
    header('location:'.SITEURL.'admin/manage-medicine.php');
  }
?>