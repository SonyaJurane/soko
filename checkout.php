<?php require 'config.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include './components/headers.php' ?>
  <title>SOKO | Checkout</title>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRgMvD0tUbsGv6Gn6yedurKxGLc_Hh21I&libraries=places,directions"></script>
</head>

<body>
  <?php include './components/navbar.php' ?>

  <?php
  echo "<div class='checkout-main'>";

  if (isset($_POST["order_items"])) {
   
    echo "<h2>Order Summary</h2>";

    $_SESSION["last_order"] = $_POST["order_items"];

    $query = "SELECT * FROM item WHERE ";
    foreach ($_POST["order_items"] as $key => $order_item) {
      if ($key === array_key_last($_POST["order_items"])) {
        $query = $query . "item_id = '" . $order_item . "'";
      } else {
        $query = $query . "item_id = '" . $order_item . "' OR ";
      }
    }

    $items_in_order = mysqli_query($conn, $query);
    $order_total = 0;

    $user_email = $_SESSION["account"];
    $user_info_query = "SELECT user_name, user_address, user_phone FROM user WHERE user_email = '$user_email'";
    $user_info =  mysqli_fetch_assoc(mysqli_query($conn, $user_info_query));


    echo "<div style='margin: 0 auto;' class='items-container'>";

    while ($row = $items_in_order->fetch_assoc()) {
      echo "
        <div class='item' item_id='" . $row["item_id"] . "'>
          <img src='images/" . $row["item_image"] . " ' draggable='false'>
          <div class='item-description'>
            <p>" . $row["item_name"] . "</p>
            <p>" . $row["item_price"] . " CAD</p>
          </div>
        </div>
      ";
      $order_total = $order_total + $row["item_price"];
    }

    $_SESSION["last_order_total"] = $order_total;

    echo "</div>";

    echo "<h2>Order Total: " . number_format($order_total, 2, '.', '') . " CAD</h2>";

    echo "<hr style='margin-top: 20px; opacity: .2'>";

    echo "<h2 style='margin: 20px 0;'>Delivery Details</h2>";

    echo "
      <form style='width: 100%;' class='sl-form' action='' method='post'>
        <h3>Recepient</h3>
        <div class='triple-block'>
          <div class='field-block'>
            <label for='name'>Name:</label>
            <input type='name' name='name' readonly value='" . $user_info["user_name"] . "'>
          </div>
          <div class='field-block'>
            <label for='address'>Address:</label>
            <input id='user-address' type='address' name='address' readonly value='" . $user_info["user_address"] . "'>
          </div>
          <div class='field-block'>
            <label for='phone'>Phone:</label>
            <input type='phone' name='phone' readonly value='" . $user_info["user_phone"] . "'>
          </div>
        </div>
        <div class='triple-block'>
          <div class='field-block'>
            <label for='deliverydatetime'>Delivery Date and Time:</label>
            <input type='datetime-local' name='deliverydatetime' required>
          </div>
          <div class='field-block'>
          </div>
          <div class='field-block'>
          </div>
        </div>
        <h3>Payment information</h3>
        <div class='triple-block'>
          <div class='field-block'>
            <label for='ccn'>Card Number:</label>
            <input name='ccn' type='tel' inputmode='numeric' pattern='[0-9\s]{13,19}' maxlength='19' placeholder='xxxx xxxx xxxx xxxx' required>
          </div>
          <div class='field-block'>
            <label for='exp'>Expiry Date:</label>
            <input type='tel' inputmode='numeric' name='exp' placeholder='mm / yy' maxlength='7' required>
          </div>
          <div class='field-block'>
            <label for='cvv'>CVV:</label>
            <input type='password' inputmode='numeric' name='cvv' maxlength='3' placeholder='cvv' required>
          </div>
        </div>
        <div class='triple-block'>
          <div class='field-block'>
            <label for='deliver_from'>Deliver From:</label>
            <select id='branch_selection' name='deliver_from'>
              <option value='Eaton Center' lat='43.6544' lng='-79.3807'>Eaton Center</option>
              <option value='Hudson`s Bay' lat='43.6710' lng='-79.3856'>Hudson's Bay</option>
              <option value='Bielnino Shopping Mall' lat='43.6714' lng='-79.3950'>Bielnino Shopping Mall</option>
            </select>
          </div>
          <div class='field-block'>
            <button type='button' onClick='show_route()' class='form-button'>See Route</button>
          </div>
          <div class='field-block'>
            <button class='form-button' name='confirm-order'>Confirm Order</button>
          </div>
        </div>
      </form>
    ";
  } elseif (isset($_POST["confirm-order"])){
    $user_acc = $_SESSION["account"];
    $order_items = $_SESSION["last_order"];
    $order_total = $_SESSION["last_order_total"];
    $user_address = $_POST["address"];
    $branch_loc = $_POST["deliver_from"];
    $delivery_schedule_time = date("Y-m-d H:i:s",strtotime($_POST["deliverydatetime"]));

    $get_trucks_query = "SELECT * FROM truck";
    $truck_list = mysqli_query($conn, $get_trucks_query);

    foreach ($truck_list as $truck_info) {
      $truck_id = $truck_info["truck_id"];
      $truck_availability = mysqli_query($conn, "SELECT * FROM truck_unavailable WHERE truck_id = '$truck_id' AND date_unavailable = '$delivery_schedule_time' ");
      if (mysqli_num_rows($truck_availability) == 0) {
        $book_truck_query = "INSERT INTO truck_unavailable (date_unavailable, truck_id) VALUES ('$delivery_schedule_time', '$truck_id')";
        mysqli_query($conn, $book_truck_query);
        break;
      }
    }

    $create_trip_query = "INSERT INTO trip (trip_origin, trip_destination, truck_id) VALUES ('$branch_loc', '$user_address', '$truck_id')";
    mysqli_query($conn, $create_trip_query);

    $trip_id = mysqli_insert_id($conn);

    $get_user_id_query = "SELECT * FROM user WHERE user_email = '$user_acc'";
    $curr_user_id = mysqli_fetch_assoc(mysqli_query($conn, $get_user_id_query))["user_id"];

    $curr_time = date('Y-m-d H:i:s');

    $order_status = 1;

    $create_order_query = "INSERT INTO `order` (date_issued, date_scheduled, order_price, user_id, trip_id, order_status) VALUES ('$curr_time', '$delivery_schedule_time', '$order_total', '$curr_user_id', '$trip_id', '$order_status');";
    mysqli_query($conn, $create_order_query);
    $order_id = mysqli_insert_id($conn);



    foreach ($order_items as $order_item_id) {
      mysqli_query($conn, "INSERT INTO order_components (order_id, item_id) VALUES ('$order_id', '$order_item_id')");
    }


    echo "<p style='font-size: 20px;'>Order Confirmed. Go to <a href='my_orders.php'>My Orders</a> to see details</p>";
  } else {
    echo "<p style='font-size: 20px;'>Your cart is empty</p>";
  }



  echo "</div>";
  ?>
</body>
<script>
  const get_address_loc = address => {
    return new Promise((resolve, resject) => {
      const geocoder = new google.maps.Geocoder()
      geocoder.geocode({
        address: address
      }, (results, status) => {
        if (status === 'OK') {
          resolve(results[0].geometry.location);
        } else {
          reject(status)
        }
      })
    })
  }

  const show_route = async () => {

    const branch_selector = document.querySelector('#branch_selection')
    const branch_selection = branch_selector.selectedIndex

    const branch_loc = {
      lat: parseFloat(branch_selector[branch_selection].getAttribute('lat')),
      lng: parseFloat(branch_selector[branch_selection].getAttribute('lng'))
    }

    const map_container = document.createElement('div')
    map_container.setAttribute('id', 'map-container')

    const user_address = document.querySelector('#user-address').getAttribute('value')

    let user_address_loc = await get_address_loc(user_address)

    map = new google.maps.Map(map_container, {
      center: {
        lat: 43.6532,
        lng: -79.3832
      },
      zoom: 8,
    })

    directionsService = new google.maps.DirectionsService()
    directionsDisplay = new google.maps.DirectionsRenderer()
    directionsDisplay.setMap(map)

    directionsService.route({
      origin: branch_loc,
      destination: user_address_loc,
      travelMode: google.maps.TravelMode.DRIVING
    }, (response, status) => {
      if (status === 'OK') {
        directionsDisplay.setDirections(response);
      }
    })


    if (document.querySelector('#map-container')) {
      document.querySelector('#map-container').remove()
    }

    document.querySelector('.checkout-main').appendChild(map_container)
  }
</script>

</html>