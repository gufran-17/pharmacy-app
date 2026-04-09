<?php include("./partials/navbar.php"); ?>

<div class="red-msg">
  <?php
    if (isset($_SESSION['update'])){
      echo $_SESSION['update'];
      unset($_SESSION['update']);
    } 
  ?>
</div>

<div class="main-content">
  <center>
    <h2>Update Order Status</h2>
  </center>
<?php
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_order WHERE id=$id";
    $res = mysqli_query($conn, $sql);
    if ($res == True) {
      $count = mysqli_num_rows($res);
      if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $customer_name = $row['customer_name'];
        $med = $row['med'];
        $order_date = $row['order_date'];
        $status = $row['order_status'];
        ?>
        <table class="tbl-30">
          <tr>
            <td>Customer name: </td>
            <td><?php echo $customer_name; ?></td>
          </tr>
          <tr>
            <td>Medicine name: </td>
            <td><?php echo $med; ?></td>
          </tr>
          <tr>
            <td>Order Date: </td>
            <td><?php echo $order_date; ?></td>
          </tr>
          <form action="" method="POST">
            <tr>
              <td>Status: </td>
              <td>
                <select name="order_status">
                <option value="Placed" <?php if($status == 'Placed'){ echo "selected";} ?>>Placed</option>
                <option value="Out for Delivery" <?php if($status == 'Out for Delivery'){ echo "selected";} ?>>Out for Delivery</option>
                <option value="Delivered" <?php if($status == 'Delivered'){ echo "selected";} ?>>Delivered</option>
                <option value="Canceled" <?php if($status == 'Canceled'){ echo "selected";} ?>>Canceled</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input type="submit" name="submit" value="Update Status" class="btn btn-info">
              </td>
            </tr>
          </form>
        </table>
        <?php
      }
      else {
        header('location:' . SITEURL . 'admin/manage-order.php');
      }
    }
    else {
      header('location:' . SITEURL . 'admin/manage-order.php');
    }
  }
  else {
    header('location:' . SITEURL . 'admin/manage-order.php');
  }
?>
</div>

<?php
  if (isset($_POST['submit'])) {
    $new_status = $_POST['order_status'];
    $sql2 = "UPDATE tbl_order SET order_status='$new_status'";
    $res2 = mysqli_query($conn, $sql2);
    if ($res2 == True) {
      $_SESSION['update'] = "Order status updated sucessfully!";
      header("location:" . SITEURL . 'admin/manage-order.php');
    }
    else {
      $_SESSION['update'] = "Could not update order status!";
      header("location:" . SITEURL . 'admin/update-status.php?id=' . $id);
    }
  }
?>

<?php include("./partials/footer.php"); ?>