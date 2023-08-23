<?php 

  if(!isset($_SESSION)) 
  { 
      session_start(); 
  } 

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "scs_database";

  $conn = mysqli_connect($servername, $username, $password, $dbname)

?>