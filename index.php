<!DOCTYPE html>
<html lang="en">

<head>
  <?php include './components/headers.php' ?>
  <title>SOKO | Home</title>
</head>

<body>
  <?php include './components/navbar.php' ?>

  <?php
  $query = "SELECT * FROM item";
  $result = mysqli_query($conn, $query);

  $logged_in = isset($_SESSION["account"]);

  echo "<main class='main'>";

  if ($logged_in) {
    echo "<div class='items-container'>";
  } else {
    echo "<div style='margin: 0 auto;' class='items-container'>";
  }


  while ($row = $result->fetch_assoc()) {
    echo "
        <div class='item draggable' draggable='true' item_id='" . $row["item_id"] . "'>
          <img src='images/" . $row["item_image"] . " ' draggable='false'>
          <div class='item-description'>
            <p>" . $row["item_name"] . "</p>
            <p>" . $row["item_price"] . " CAD</p>
          </div>
        </div>
      ";
  }

  echo "</div>";

  if ($logged_in) {
    echo "
        <div class='cart'>
          <div style='display: flex; align-items: center; margin-bottom: 40px; justify-content: space-between;'>
            <h2>Cart</h2>
            <button id='checkout-btn' style='background: none; border: none; height: 25px; margin-left: 20px; margin-top: 5px; cursor: pointer;' class='main-button'>Checkout</button>
          </div>
          <div class='cart-item-container'>

          </div>
        </div>
      ";
  }

  echo "</main>";
  ?>


</body>

<script>
  const draggables = document.querySelectorAll('.draggable')
  const catalogue = document.querySelector('.items-container')
  const cart = document.querySelector('.cart')
  const cart_item_container = document.querySelector('.cart-item-container')

  if (cart) {
    draggables.forEach(draggable => {
      draggable.addEventListener('dragstart', () => {
        draggable.classList.add('dragging')

        cart.classList.add('accepting-draggables')
      })

      draggable.addEventListener('dragend', () => {
        draggable.classList.remove('dragging')

        cart.classList.remove('accepting-draggables')
      })
    })

    catalogue.addEventListener('dragover', e => {
        e.preventDefault()
        const draggable = document.querySelector('.dragging')
        catalogue.appendChild(draggable)
    })

    cart_item_container.addEventListener('dragover', e => {
        e.preventDefault()
        const draggable = document.querySelector('.dragging')
        cart_item_container.appendChild(draggable)
    })

  }
</script>

<script>
  checkoutbtn = document.getElementById("checkout-btn")

  checkoutbtn.addEventListener('click', () => {
    cart_items = document.querySelectorAll('.cart-item-container .item')

    order_items = []

    const form = document.createElement('form')
    form.method = 'post'
    form.action = 'checkout.php'

    let idx = 0
    cart_items.forEach((item) => {
      const hiddenInput = document.createElement('input')
      hiddenInput.type = 'hidden'
      hiddenInput.name = `order_items[${idx}]`
      hiddenInput.value = item.getAttribute('item_id')

      form.appendChild(hiddenInput)

      idx++; 
    })

    document.body.appendChild(form)
    form.submit()

  })
</script>

</html>