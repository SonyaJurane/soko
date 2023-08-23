<?php
require 'config.php'
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include './components/headers.php' ?>
  <title>SOKO | About us</title>
</head>

<body>
  <?php include './components/navbar.php' ?>
  <div class='about-main'>
  
    <h2>About us</h2>
    <p>SOKO is an online system that aims to plan for smart green trips inside the city and its neighborhood for online shopping and then delivery to the destinations. 
      Considering the traffic as a serious threat to the quality of life these years, the world has been looking for various solutions to decrease the stress, frustration, 
      delays and terrible air pollutions being caused through it. SOKO provides a smart green solution on this regard by providing online shopping services and then
      delivery of the purchased items from the warehouses to the destination address.</p>
    <h2>Our Team</h2>
  
    <div class='about-row'>
      <div class="about-column">
        <img src="./about_images/dan.jpg">
      </div>
      <div class="about-column">
        <h3>Dan Kotov</h3>
        <p>Studying Computer Science at Ryerson University<br>Freelance motion and graphic design<br><i>The Office</i> enthusiast & professional Hookah maker</p>
        <div button-box>
          <button class="about-button" style="margin-right:10px" onclick="window.location.href='https://www.linkedin.com/in/'">LinkedIn</button>
          <button class="about-button" style="background-color:black; color:white" onclick="window.location.href='https://github.com/kotovrdtrlf'">Github</button>
        </div>
      </div>
    </div>

    <div class='about-row'>
      <div class="about-column">
        <img src="./about_images/sonya.jpg">
      </div>
      <div class="about-column">
        <h3>Sonya Jurane</h3>
        <p>Computer Science student at Ryerson University<br>Climber, kitesurfer & aspiring pilot<br>Cheese pizza addict</p>
        <div button-box>
          <button class="about-button" style="margin-right:10px" onclick="window.location.href='https://www.linkedin.com/in/sonyajurane/'">LinkedIn</button>
          <button class="about-button" style="background-color:black; color:white" onclick="window.location.href='https://github.com/SonyaJurane'">Github</button>
        </div>
      </div>
    </div>
  </div>
</body>

</html>