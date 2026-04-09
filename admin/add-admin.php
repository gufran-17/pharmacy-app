<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
  if (isset($_SESSION['add'])) {
    echo $_SESSION['add'];
    unset($_SESSION['add']);
  }
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Add Admin</h2>
  </center>
  <form action="" method="POST">
    <table class="tbl-30">
      <tr>
        <td>Full Name: </td>
        <td><input type="text" name="full_name" placeholder="Enter the Name"></td>
      </tr>
      <tr>
        <td>Username: </td>
        <td><input type="text" name="username" placeholder="Enter the Username"></td>
      </tr>
      <tr>
        <td>Password: </td>
        <td><input type="password" name="passwrd" placeholder="Enter the Password"></td>
      </tr>
      <tr>
        <td colspan="2">
          <input type="submit" name="submit" value="Add Admin" class="btn btn-info">
        </td>
      </tr>
    </table>
  </form>
</div>

<?php
// Process value from form
if (isset($_POST['submit'])) {
  $full_name = $_POST['full_name'];
  $username = $_POST['username'];
  $passwrd = md5($_POST['passwrd']);


  // Make sql querty
  $sql = "INSERT INTO tbl_admin SET
    full_name='$full_name',
    username='$username',
    passwrd='$passwrd'
  ";
  // Execute query and save data in database(in config-constants)
  $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

  //check if query is executed 
  if ($res == True) {
    // echo "Data Inserted";
    // create session variable to display message
    $_SESSION['add'] = "Admin added sucessfully!";
    // redirect page to manage admin
    header("location:" . SITEURL . 'admin/manage-admin.php');
  } else {
    // echo "Failed to insert data";
    // create session variable to display message
    $_SESSION['add'] = "Failed to add Admin!";
    // redirect page to add admin
    header("location:" . SITEURL . 'admin/add-admin.php');
  }
}
?>

<?php include("./partials/footer.php"); ?>