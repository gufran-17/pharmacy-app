<?php include("./partials/header.php"); ?> 

<div class="infobar">
  <center>
    <br>
    <h2>Categories</h2>
  </center>
</div>
<br>
<br>
<center>
  <div style="padding: 5%;">
    <div class="row">
      <?php
        $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
        $res = mysqli_query($conn, $sql);
        if ($res == True) {
          while($row = mysqli_fetch_assoc($res))
          {
            $id = $row['id'];
            $title = $row['title'];
            $image_name = $row['image_name'];
            ?>
            <div class="col">
              <div class="card">
              <?php
                  if ($image_name != "") {
                    ?>
                    <img src="./img/category/<?php echo $image_name; ?>" alt="Category Image" style="width:100%">
                    <?php
                  }
                  else {
                    echo "Image Unavailable";
                  }
                ?>
                <h1><?php echo $title; ?></h1>
                <p><a class="btn btn-dark" href="<?php echo SITEURL; ?>category-meds.php?id=<?php echo $id; ?>">Browse Category</a></p>
              </div>
            </div>
            <?php
          }
        }
      ?>
    </div>
  </div>
</center>

<?php include("./partials/footer.php"); ?> 