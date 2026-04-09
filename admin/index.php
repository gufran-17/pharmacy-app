<?php include("./partials/navbar.php"); ?>

<div class="green-msg">
  <?php
    if (isset($_SESSION['login'])){
      echo $_SESSION['login'];
      unset($_SESSION['login']);
    } 
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Dashboard</h2>
  </center>
  <br>
  <div class="row">
    <div class="col"></div>
    <div class="col-2">
      <?php
        $sql1 = "SELECT * FROM tbl_admin";
        $res1 = mysqli_query($conn, $sql1);
        if ($res1 == True) {
          $count1 = mysqli_num_rows($res1);
        }
      ?>
      <h1><?php echo $count1; ?></h1>
      <br>
      Admins
    </div>
    <div class="col-2 offset-1">
    <?php
        $sql2 = "SELECT * FROM tbl_category";
        $res2 = mysqli_query($conn, $sql2);
        if ($res2 == True) {
          $count2 = mysqli_num_rows($res2);
        }
      ?>
      <h1><?php echo $count2; ?></h1>
      <br>
      Categories
    </div>
    <div class="col-2 offset-1">
    <?php
        $sql3 = "SELECT * FROM tbl_med";
        $res3 = mysqli_query($conn, $sql3);
        if ($res3 == True) {
          $count3 = mysqli_num_rows($res3);
        }
      ?>
      <h1><?php echo $count3; ?></h1>
      <br>
      Medicines
    </div>
    <div class="col-2 offset-1">
    <?php
        $sql4 = "SELECT * FROM tbl_order";
        $res4 = mysqli_query($conn, $sql4);
        if ($res4 == True) {
          $count4 = mysqli_num_rows($res4);
        }
      ?>
      <h1><?php echo $count4; ?></h1>
      <br>
      Orders
    </div>
    <div class="col"></div>
  </div>
</div>

<?php include("./partials/footer.php"); ?>