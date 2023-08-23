<?php
require 'config.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include './components/headers.php' ?>
  <title>SOKO | My Orders</title>
</head>

<body>
  <?php include './components/navbar.php' ?>

  <div class="orders-container">
    <h2 style="margin-bottom: 40px;">My Orders</h2>
    <?php
    $user_acc = $_SESSION["account"];

    $get_user_id_query = "SELECT * FROM user WHERE user_email = '$user_acc'";
    $curr_user_id = mysqli_fetch_assoc(mysqli_query($conn, $get_user_id_query))["user_id"];

    $all_orders_query = "SELECT * FROM `order` WHERE user_id = '$curr_user_id'";
    $all_orders_result = mysqli_query($conn, $all_orders_query);


    while ($order_row = $all_orders_result->fetch_assoc()) {

      $order_id = $order_row["order_id"];

      $all_items = mysqli_fetch_all(mysqli_query($conn, "SELECT item_id FROM order_components WHERE order_id = '$order_id'"));
      
      $items_info_query = "SELECT * FROM item WHERE ";
      foreach ($all_items as $key => $order_item) {
        if ($key === array_key_last($all_items)) {
          $items_info_query = $items_info_query . "item_id = '" . $order_item[0] . "'";
        } else {
          $items_info_query = $items_info_query . "item_id = '" . $order_item[0] . "' OR ";
        }
      }

      $items_in_order = mysqli_query($conn, $items_info_query);



      echo "<div class='order-container'>";
      echo "<h3>Order #" . $order_id . "</h3>";
      echo "<div style='margin: 0 auto;' class='items-container'>";
      while ($item_row = $items_in_order->fetch_assoc()) {
        echo "
          <div class='item' item_id='" . $item_row["item_id"] . "'>
            <img src='images/" . $item_row["item_image"] . " ' draggable='false'>
            <div class='item-description'>
              <p>" . $item_row["item_name"] . "</p>
              <p>" . $item_row["item_price"] . " CAD</p>
            </div>
          </div>
        ";
      }
      echo "</div>";
      echo "<div class='order-info'>
              <div>
                <h4>Date Issued:</h4>
                <p>" . $order_row["date_issued"] . "</p>
              </div>
              <div>
                <h4>Date Scheduled:</h4>
                <p>" . $order_row["date_scheduled"] . "</p>
              </div>
              <div>
                <h4>Order Price:</h4>
                <p>" . number_format($order_row["order_price"], 2, '.', '') ."</p>
              </div>
            </div>
      ";

      echo "</div>";
    }
    ?>
  </div>


</body>

</html>