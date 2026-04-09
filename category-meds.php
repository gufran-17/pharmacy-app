<?php 
  include("./partials/header.php");
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_category WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $image_name = $row['image_name'];
        $featured = $row['featured'];
        $active = $row['active'];
      }
      else {
        header('location:'.SITEURL);
      }
    }
  } 
  else {
    header('location:'.SITEURL);
  }
?> 

<div class="infobar">
  <center>
    <br>
    <h2>Medicines in Category: <?php echo $title; ?></h2>
  </center>
</div>
<br>
<br>
<br>
<center>
  <div class="main_r">
    <div class="row">
      <?php
        $sql2 = "SELECT * FROM tbl_med WHERE cat_id=$id AND active='Yes'";
        $res2 = mysqli_query($conn, $sql2);
        if($res == True){
          while ($row = mysqli_fetch_assoc($res2)) 
          {
            $id = $row['id'];
            $title = $row['title'];
            $image_name = $row['image_name'];
            $price = $row['price'];
            $description = $row['description'];
            ?>
            <div class="col d-flex">
              <div class="card h-100">
                <?php
                  if ($image_name != "") {
                    ?>
                    <img src="./img/medicine/<?php echo $image_name; ?>" alt="Medicine Image" style="width:100%">
                    <?php
                  }
                  else {
                    echo "Image Unavailable";
                  }
                ?>
                <h1><?php echo $title; ?></h1>
                <p class="price">Rs.<?php echo $price; ?></p>
                <p><?php echo $description; ?></p>
                <p><a class="btn btn-dark" href="<?php echo SITEURL; ?>order.php?id=<?php echo $id; ?>">Order Now</a></p>
              </div>
            </div>
            <?php
          }
        }
      ?>
    </div>  
    <br><br>
  </div>
</center>

<?php include("./partials/footer.php"); ?>  