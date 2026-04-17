<?php 
  include("./partials/header.php");
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_med WHERE id='$id' AND active='Yes'";
    $res = mysqli_query($conn, $sql);
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
        // echo "Medicine available";
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $price = $row['price'];
        $image_name = $row['image_name'];
        $cat_id = $row['cat_id'];
        $featured = $row['featured'];
        $active = $row['active'];
      } else {
        header('location:'.SITEURL);
        die();
      }
    }
  } 
  else {
    header('location:'.SITEURL);
    die();
  }
?> 

<div class="red-msg">
  <?php
  if (isset($_SESSION['order-f'])) {
    echo $_SESSION['order-f'];
    unset($_SESSION['order-f']);
  }
  ?>
</div>

<div class="infobar">
  <center>
    <br>
    <h2>Fill this form to confirm your order.</h2>
  </center>
</div>

  <section class="med-order">
    <div class="container">

      <form action="#" method="POST" class="order">
        <fieldset>
          <legend>Selected Medicine</legend>

          <div class="med-menu-img">
          <?php
            if ($image_name != "") {
              ?>
              <img src="./img/medicine/<?php echo $image_name; ?>" alt="Medicine Image" class="img-responsive img-curve">
              <?php
            }
            else {
              echo "Image Unavailable";
            }
          ?>
          </div>
          <div class="med-menu-desc">
            <h3>&nbsp <?php echo $title; ?></h3>
            <input type="hidden" name="title" value= "<?php echo $title; ?>">
            <p>&nbsp&nbsp Rs.<?php echo $price; ?></p>
            <input type="hidden" name="price" value= "<?php echo $price; ?>">
            <div class="order-label">&nbsp&nbsp Quantity</div>
            <input type="number" name="qty" class="input-responsive" min="1" oninput= "this.value = !!this.value && Math.abs(this.value) >= 1 ? Math.abs(this.value) : null" required>
          </div>

        </fieldset>

        <fieldset>
          <legend>Delivery Details</legend>
          <div class="order-label">Full Name</div>
          <input type="text" name="full-name" placeholder="E.g. Gufran Ansari" class="input-responsive" required>

          <div class="order-label">Phone Number</div>
          <input type="tel" name="contact" placeholder="E.g. 9860xxxxxx" class="input-responsive" required>

          <div class="order-label">Email</div>
          <input type="email" name="email" placeholder="E.g. hi@gmail.com" class="input-responsive" required>

          <div class="order-label">Address</div>
          <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive"required></textarea>

          <input type="submit" name="submit" value="Confirm Order" class="btn btn-info">
        </fieldset>

      </form>

    </div>
  </section>

<?php
  if (isset($_POST['submit'])) {
    $med = $_POST['title']; 
    $med_price = $_POST['price']; 
    $qty = $_POST['qty'];
    $customer_name = $_POST['full-name'];
    $customer_contact = $_POST['contact'];
    $customer_email = $_POST['email'];
    $customer_address = $_POST['address'];

    $total = $med_price * $qty;
    $order_date = date('Y-m-d H:i:s');
    $status = 'Placed';

    $sql2 = "INSERT INTO tbl_order SET
      med = '$med',
      price	= $med_price,
      qty	= $qty,
      total	= $total,
      order_date = '$order_date',
      order_status = 'pending',	
      customer_name	= '$customer_name',
      customer_contact = '$customer_contact',
      customer_email = '$customer_email',
      customer_address = '$customer_address'
    ";
    $res2 = mysqli_query($conn, $sql2);
    if ($res2 == True) {
      $_SESSION['order-s'] = "Order Placed Successfully!";
      header("location:" . SITEURL);
      die();
    }
    else {
      $_SESSION['order-f'] = "Could not place Order. Try again!";
      header("location:" . SITEURL . 'order.php?id=' . $id);
      die();
    }
  }
?>

<?php include("./partials/footer.php"); ?>