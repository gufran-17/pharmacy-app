<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
    if (isset($_SESSION['update'])){
      echo $_SESSION['update'];
      unset($_SESSION['update']);
    } 
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Update Admin</h2>
  </center>

<?php 
  $id = $_GET['id'];
  $sql = "SELECT * FROM tbl_admin WHERE id=$id";
  $res = mysqli_query($conn, $sql);
  if ($res == True) {
    $count = mysqli_num_rows($res);
    if ($count == 1) {
      // echo "Admin available";
      $row = mysqli_fetch_assoc($res);
      $full_name = $row['full_name'];
      $username = $row['username'];
    }
    else{
      header('location:'.SITEURL.'admin/manage-admin.php');
    }
  }
?>

  <form action="" method="POST">
    <table class="tbl-30">
      <tr>
        <td>Full Name: </td>
        <td><input type="text" name="full_name" placeholder="<?php echo $full_name; ?>"></td>
      </tr>
      <tr>
        <td>Username: </td>
        <td><input type="text" name="username" placeholder="<?php echo $username; ?>"></td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="hidden" name="id" value="<?php echo $id; ?>">
          <input type="submit" name="submit" value="Update Admin" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php 
  if (isset($_POST['submit'])) {
    // echo "button clicked";
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $sql = "UPDATE tbl_admin SET
    full_name='$full_name',
    username='$username'
    WHERE id = $id
  ";

  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
  if ($res == True) {
    // create session variable to display message
    $_SESSION['update'] = "Admin updated sucessfully!";
    // redirect page to manage admin
    header("location:" . SITEURL . 'admin/manage-admin.php');
  } else {
    // create session variable to display message
    $_SESSION['update'] = "Failed to update Admin!";
    // redirect page to add admin
    header("location:" . SITEURL . 'admin/update-admin.php');
  }
  }
?>

<?php include("./partials/footer.php"); ?>