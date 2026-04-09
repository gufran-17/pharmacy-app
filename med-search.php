<?php include("./partials/header.php"); ?>

<?php
if (isset($_POST['submit'])) {
  $search = $_POST['search'];
?>
  <div class="infobar">
    <center>
      <br>
      <h2>Search Results for Medicine: "<?php echo $search; ?>"</h2>
    </center>
  </div>
  <br>
  <br>
  <br>
  <center>
    <div class="main_r">
      <div class="row">
        <?php
        $sql = "SELECT * FROM tbl_med 
            WHERE title LIKE '%$search%' 
            OR description LIKE '%$search%'
          ";
        $res = mysqli_query($conn, $sql);
        if ($res == True) {
          $count = mysqli_num_rows($res);
          if ($count > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
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
                  } else {
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
          } else {
            ?>
            <h3>Looks like there are no results for "<?php echo $search; ?>". Try something else.</h3>
      <?php
          }
        } else {
          header('location:' . SITEURL);
        }
      } else {
        header('location:' . SITEURL);
      }
      ?>
      </div>
    </div>
  </center>

  <?php include("./partials/footer.php"); ?>