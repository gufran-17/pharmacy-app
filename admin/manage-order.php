<?php include("./partials/navbar.php"); ?>

<div class="green-msg">
  <?php
    if (isset($_SESSION['update'])){
      echo $_SESSION['update'];
      unset($_SESSION['update']);
    } 
  ?>
</div>

<div class="main-content">
  <center><h2>Manage Order</h2></center>
</div>  
  <center>
  <table class="tbl-full">
    <tr>
      <th>S.no.</th>
      <th>Medicine</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Total</th>
      <th>Customer</th>
      <th>Contact</th>
      <th>Email</th>
      <th>Address</th>
      <th>Order Date</th>
      <th>Status</th>
    </tr>
    <?php
      $sql = "SELECT * FROM tbl_order";
      $res = mysqli_query($conn, $sql);
      if ($res == True) {
        $count = mysqli_num_rows($res);
        if ($count > 0) {
          $sn = 1;
          while ($rows = mysqli_fetch_assoc($res)) {
            $id = $rows['id'];
            $med = $rows['med'];
            $price = $rows['price'];
            $qty = $rows['qty'];
            $total = $rows['total'];
            $order_date = $rows['order_date'];
            $status = $rows['order_status'];
            $customer_name = $rows['customer_name'];
            $customer_contact = $rows['customer_contact'];
            $customer_email = $rows['customer_email'];
            $customer_address = $rows['customer_address'];
            ?>
            <tr>
              <td><?php echo $sn++; ?></td>
              <td><?php echo $med; ?></td>
              <td><?php echo $price; ?></td>
              <td><?php echo $qty; ?></td>
              <td><?php echo $total; ?></td>
              <td><?php echo $customer_name; ?></td>
              <td><?php echo $customer_contact; ?></td>
              <td><?php echo $customer_email; ?></td>
              <td><?php echo $customer_address; ?></td>
              <td><?php echo $order_date; ?></td>
              <td>
                <?php echo $status; ?>
                <a href="<?php echo SITEURL; ?>admin/update-status.php?id=<?php echo $id; ?>" class="btn btn-info">Change</a>
              </td>
            </tr>
            <?php
          }
        }
        else {
          ?>
          <td colspan="12"><div class="red-msg">No Orders Added</div></td>
          <?php
        }
      }
    ?>
  </table>
  </center>


<?php include("./partials/footer.php"); ?>