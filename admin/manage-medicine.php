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

<div class="red-msg">
  <?php
    if (isset($_SESSION['remove'])){
      echo $_SESSION['remove'];
      unset($_SESSION['remove']);
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

<div class="main-content">
  <center><h2>Manage Medicine</h2></center>
  <!-- button to add Medicine -->
  <a href="<?php echo SITEURL; ?>admin/add-medicine.php" class="btn btn-info">Add Medicine</a>
  <br>
  <table class="tbl-full">
    <tr>
      <th>S.no.</th>
      <th>Title</th>
      <th>Price</th>
      <th>Image</th>
      <th>Category</th>
      <th>Featured</th>
      <th>Active</th>
      <th>Action</th>
    </tr>
    <?php 
      $sql = "SELECT * FROM tbl_med";
      $res = mysqli_query($conn, $sql);
      if ($res == True) {
        // count rows to check if data present or not
        $count = mysqli_num_rows($res);
        if ($count>0) {
          $sn = 1;
          while($rows= mysqli_fetch_assoc($res))
          {
            $id = $rows['id'];
            $title = $rows['title'];
            $description = $rows['description'];
            $price = $rows['price'];
            $image_name = $rows['image_name'];
            $cat_id = $rows['cat_id'];
            $featured = $rows['featured'];
            $active = $rows['active'];
           ?>
            <tr>
              <td><?php echo $sn++; ?></td>
              <td><?php echo $title; ?></td>
              <td><?php echo $price; ?></td>
              <td>
                <?php 
                  if ($image_name != "") {
                    ?>
                    <img src="<?php echo SITEURL; ?>img/medicine/<?php echo $image_name; ?>" alt="<?php echo $image_name; ?>" width="50px">
                    <?php 
                  }
                  else {
                    echo "Image not Added";
                  }
                ?>
              </td>
              <td>
                <?php
                  $sql2 = "SELECT * FROM tbl_category WHERE id=$cat_id";
                  $res2 = mysqli_query($conn, $sql2);
                  if ($res2 == True) {
                    $count = mysqli_num_rows($res2);
                    if ($count == 1) {
                      $row = mysqli_fetch_assoc($res2);
                      echo $row['title'];
                    }
                    else {
                      echo "No Category";
                    }
                  }
                  else {
                    echo "No Category";
                  }
                ?>
              </td>
              <td><?php echo $featured ?></td>
              <td><?php echo $active ?></td>
              <td>
                <a href="<?php echo SITEURL; ?>admin/update-medicine.php?id=<?php echo $id; ?>" class="btn btn-success">Update Medicine</a>
                <a href="<?php echo SITEURL; ?>admin/delete-medicine.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-danger">Delete Medicine</a> 
              </td>
            </tr>
            <?php
          }
        }
        else {
          ?>
          <td colspan="8"><div class="red-msg">No Medicine Added</div></td>
          <?php
        }
      }
    ?>
  </table>
</div>

<?php include("./partials/footer.php"); ?>