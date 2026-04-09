<?php include("../config/constants.php"); ?>

<html>
  <head>
    <title>Login Page</title>
    <link rel="icon" href="../favicon.ico" type="image/ico">
    <link href="../css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap"
    rel="stylesheet" />
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/login.css">
  </head>
  <body>
    <?php
      if (isset($_SESSION['no-login-message'])) {
        echo $_SESSION['no-login-message'];
        unset($_SESSION['no-login-message']);
      }
    ?>
    <div class="login">
      <h2>Admin Login</h2>
      <br>
      <form action="" method="POST">
        Username: 
        <input type="text" name="username" placeholder="Enter username">
        <br>
        <br>
        Password:
        <input type="password" name="passwrd" placeholder="Enter password">
        <br><br>
        <div class="red-msg">
          <?php
            if (isset($_SESSION['login'])){
              echo $_SESSION['login'];
              unset($_SESSION['login']);
            } 
          ?>
        </div>
        <br>
        <input type="submit" name="submit" value="Login" class="btn btn-dark">
      </form>
    </div>
  </body>
</html>

<?php
  if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $passwrd = md5($_POST['passwrd']);

    $sql = "SELECT * FROM tbl_admin WHERE username = '$username' AND passwrd = '$passwrd'";
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $count = mysqli_num_rows($res);
    if ($res == True) {
      if ($count == 1) {
        $_SESSION['login'] = "Login Successful!";
        $_SESSION['user'] = $username;
        header("location:" . SITEURL . 'admin/index.php');
      }
      else {
        $_SESSION['login'] = "Login Failed! Please check credentials!";
        header("location:" . SITEURL . 'admin/login.php');
      }
    }
  }
?>