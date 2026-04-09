<?php include("./partials/navbar.php"); ?>

<div class="green-msg">
  <?php
    if (isset($_SESSION['add'])){
      echo $_SESSION['add'];
      unset($_SESSION['add']);
    } 
  ?>
</div>

<div class="green-msg">
  <?php
    if (isset($_SESSION['delete_s'])){
      echo $_SESSION['delete_s'];
      unset($_SESSION['delete_s']);
    } 
  ?>
</div>

<div class="red-msg">
  <?php
    if (isset($_SESSION['delete_f'])){
      echo $_SESSION['delete_f'];
      unset($_SESSION['delete_f']);
    } 
  ?>
</div>

<div class="green-msg">
  <?php
    if (isset($_SESSION['update'])){
      echo $_SESSION['update'];
      unset($_SESSION['update']);
    } 
  ?>
</div>

<div class="green-msg">
  <?php
    if (isset($_SESSION['pass-update'])){
      echo $_SESSION['pass-update'];
      unset($_SESSION['pass-update']);
    } 
  ?>
</div>

<div class="main-content">
  <center><h2>Manage Admin</h2></center>
  <!-- button to add admin -->
  <a href="<?php echo SITEURL; ?>admin/add-admin.php" class="btn btn-info">Add Admin</a>
  <br>
  <table class="tbl-full">
    <tr>
      <th>S.no.</th>
      <th>Full Name</th>
      <th>Username</th>
      <th>Action</th>
    </tr>
    <?php 
      $sql = "SELECT * FROM tbl_admin";
      $res = mysqli_query($conn, $sql);
      if ($res == True) {
        // count rows to check if data present or not
        $count = mysqli_num_rows($res);
        if ($count>0) {
          $sn = 1;
          while($rows= mysqli_fetch_assoc($res))
          {
            $id = $rows['id'];
            $full_name = $rows['full_name'];
            $username = $rows['username'];
           ?>
            <tr>
              <td><?php echo $sn++ ?></td>
              <td><?php echo $full_name ?></td>
              <td><?php echo $username ?></td>
              <td>
                <a href="<?php echo SITEURL; ?>admin/update-password.php?id=<?php echo $id; ?>" class="btn btn-info">Change Password</a>
                <a href="<?php echo SITEURL; ?>admin/update-admin.php?id=<?php echo $id; ?>" class="btn btn-success">Update Admin</a>
                <a href="<?php echo SITEURL; ?>admin/delete-admin.php?id=<?php echo $id; ?>" class="btn btn-danger">Delete Admin</a> 
              </td>
            </tr>
            <?php

          }
        }
      }
    ?>
  </table>
</div>

<?php include("./partials/footer.php"); ?>