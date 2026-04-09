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
  <center><h2>Manage Category</h2></center>
  <!-- button to add category -->
  <a href= "<?php echo SITEURL; ?>admin/add-category.php" class="btn btn-info">Add Category</a>
  <br>
  <table class="tbl-full">
    <tr>
      <th>S.no.</th>
      <th>Title</th>
      <th>Image</th>
      <th>Featured</th>
      <th>Active</th>
      <th>Actions</th>
    </tr>
    <?php 
      $sql = "SELECT * FROM tbl_category";
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
            $image_name = $rows['image_name'];
            $featured = $rows['featured'];
            $active = $rows['active'];
           ?>
            <tr>
              <td><?php echo $sn++; ?></td>
              <td><?php echo $title; ?></td>
              <td>
                <?php 
                  if ($image_name != "") {
                    ?>
                    <img src="<?php echo SITEURL; ?>img/category/<?php echo $image_name; ?>" alt="<?php echo $image_name; ?>" width="50px">
                    <?php 
                  }
                  else {
                    echo "Image not Added";
                  }
                ?>
              </td>
              <td><?php echo $featured; ?></td>
              <td><?php echo $active; ?></td>
              <td>
                <a href="<?php echo SITEURL; ?>admin/update-category.php?id=<?php echo $id; ?>" class="btn btn-success">Update Category</a>
                <a href="<?php echo SITEURL; ?>admin/delete-category.php?id=<?php echo $id; ?>&image_name=<?php echo $image_name; ?>" class="btn btn-danger">Delete Category</a> 
              </td>
            </tr>
            <?php

          }
        }
        else {
          ?>
          <td colspan="6"><div class="red-msg">No Category Added</div></td>
          <?php
        }
      }
    ?>
  </table>
</div>

<?php include("./partials/footer.php"); ?>