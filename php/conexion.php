<?php
$conn = new mysqli("localhost", "root", "", "aldim3d");
if ($conn->connect_error) {
  die("Error de conexión: " . $conn->connect_error);
}
?>
