<?php
$servername = "localhost";



$conn = new mysqli($CTAdb );


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>