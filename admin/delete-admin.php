<?php
  include("../config/constants.php");
  echo $id = $_GET['id'];
  $sql = "DELETE FROM tbl_admin WHERE id=$id";
  $res = mysqli_query($conn, $sql);

  if ($res == True) {
    $_SESSION['delete_s'] = "Admin deleted sucessfully!";
    // redirect page to manage admin
    header('location:'.SITEURL.'admin/manage-admin.php');
  }
  else {
    $_SESSION['delete_f'] = "Failed to delete Admin!";
    // redirect page to manage admin
    header('location:'.SITEURL.'admin/manage-admin.php');
  }
?>