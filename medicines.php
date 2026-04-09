<?php include("./partials/header.php"); ?> 

<div class="infobar">
  <center>
    <br>
    <h2>Medicines</h2>
    <div class="col-4">
      <form action="<?php echo SITEURL; ?>med-search.php" method="POST">
        <div class="input-group">
          <input type="search" name="search" class="form-control rounded" placeholder="Search Medicines" required/>
          <input type="submit" name="submit" value="Search" class="btn btn-danger">
        </div>
      </form>
    </div>
    <br>
  </center>
</div>

<br>
<br>
<br>

<center>
  <div class="main_r">
    <div class="row">
      <?php
        $sql2 = "SELECT * FROM tbl_med WHERE active='Yes'";
        $res2 = mysqli_query($conn, $sql2);
        if($res2 == True){
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
  </div>
</center>

<?php include("./partials/footer.php"); ?>  