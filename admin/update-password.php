<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
    if (isset($_SESSION['user-not-found'])){
      echo $_SESSION['user-not-found'];
      unset($_SESSION['user-not-found']);
    } 
  ?>
</div>

<div class="red-msg">
  <?php
    if (isset($_SESSION['no-match-pass'])){
      echo $_SESSION['no-match-pass'];
      unset($_SESSION['no-match-pass']);
    } 
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Change Password</h2>
  </center>
  <?php 
    $id = $_GET['id'];
  ?>
  <form action="" method="POST">
    <table class="tbl-35">
      <tr>
        <td>Current Password: </td>
        <td><input type="password" name="current_passwrd" placeholder="Current Password"></td>
      </tr>
      <tr>
        <td>New Password: </td>
        <td><input type="password" name="new_passwrd" placeholder="New Password"></td>
      </tr>
      <tr>
        <td>Confirm Password: </td>
        <td><input type="password" name="confirm_passwrd" placeholder="Confirm Password"></td>
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
    // echo "clicked";
    $id = $_POST['id'];
    $current_passwrd = md5($_POST['current_passwrd']);
    $new_passwrd = md5($_POST['new_passwrd']);
    $confirm_passwrd = md5($_POST['confirm_passwrd']);

    $sql = "SELECT * FROM tbl_admin WHERE id = $id AND passwrd = '$current_passwrd'";

    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
        // echo "user found";
        if ($new_passwrd == $confirm_passwrd) {
          // echo "pass match";
          $sql2 = "UPDATE tbl_admin SET
          passwrd = '$new_passwrd'
          WHERE id = $id
          ";
          $res2 = mysqli_query($conn, $sql2);
          if ($res2 == True) {
            $_SESSION['pass-update'] = "Password updated successfully!";
            header("location:" . SITEURL . 'admin/manage-admin.php?');
          }
        } else {
          $_SESSION['no-match-pass'] = "New and Confirm Password do not match!";
          header("location:" . SITEURL . 'admin/update-password.php?id='.$id);
        }
      }
      else {
        $_SESSION['user-not-found'] = "User not found!";
        header("location:" . SITEURL . 'admin/update-password.php?id='.$id);
      }
    }
  }
?>

<?php include("./partials/footer.php"); ?>