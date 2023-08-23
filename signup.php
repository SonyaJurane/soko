<?php 
    require 'config.php';
    if (isset($_POST["signup"])) {
        $name = $_POST["name"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $duplicate = mysqli_query($conn, "SELECT * FROM user WHERE user_email = '$email' OR user_phone = '$phone'");
        if (mysqli_num_rows($duplicate) > 0) {
            echo
            "<script>alert('Email or Phone Number were already used')</script>";
        } else {
            $query = "INSERT INTO user VALUES('','$name','$phone','$email','$address','$password')";
            mysqli_query($conn, $query);
            $_SESSION["account"] = $email;
            header('Location: index.php');
        }
    }
?>

<head>
    <?php include './components/headers.php' ?>
    <title>SOKO | Sign Up</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBRgMvD0tUbsGv6Gn6yedurKxGLc_Hh21I&libraries=places"></script>
</head>

<!-- AIzaSyCgDflAFKvmr5Ib8O544TdpE9Q6yJLWCaY -->

<body>
    <?php include './components/navbar.php' ?>

    <div class="dead-center-div">
        <form class="sl-form" action="" method="post" autocomplete="off">
            <h2>Sign Up</h2>

            <div class="double-block">
                <div class="field-block">
                    <label for="name">Name:</label>
                    <input type="name" name="name" required>
                </div>
                <div class="field-block">
                    <label for="phone">Phone:</label>
                    <input type="text" name="phone" required>
                </div>
            </div>

            <div class="field-block">
                <label for="address">Address:</label>
                <input id="address-input" type="text" name="address" placeholder="" required>
            </div>

            <div class="double-block">
                <div class="field-block">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="field-block">
                    <label for="password">Password:</label>
                    <input type="password" name="password" required>
                </div>
            </div>

            <div class="form-action">
                <input class="main-button" type="submit" name="signup" value="Sign Up">
                <p>Already have an account? <a href="login.php">Log In</a></p>
            </div>
        </form>
    </div>
</body>
<script>
    const autocomplete = new google.maps.places.Autocomplete((document.getElementById('address-input')), {
        types: ['geocode']
    });
</script>

</html>