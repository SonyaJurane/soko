<?php 
  require 'config.php';
  if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE user_email = '$email' AND user_password = '$password'");
    if (mysqli_num_rows($result) > 0) {
      $_SESSION["account"] = $email;
      header('Location: index.php');
    } else {
      echo
      "<script>alert('Wrong Email or Password')</script>";
    }
  }

?>

<head>
  <?php include './components/headers.php' ?>
  <title>SOKO | Log In</title>
</head>

<body>
  <?php include './components/navbar.php' ?>

  <div class="dead-center-div">
    <form class="sl-form" action="" method="post" autocomplete="off">
      <h2>Log In</h2>

      <div class="field-block">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
      </div>

      <div class="field-block">
        <label for="password">Password:</label>
        <input type="password" name="password" required>
      </div>

      <div class="form-action">
        <input class="main-button" type="submit" name="login" value="Log In">
        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
      </div>
    </form>
  </div>


</body>

</html>