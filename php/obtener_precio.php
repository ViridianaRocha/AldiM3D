<?php
include 'conexion.php';

if (isset($_GET['id_cotizacion'])) {
  $id = intval($_GET['id_cotizacion']);
  $stmt = $conn->prepare("SELECT precio FROM respuestas_cotizacion WHERE id_cotizacion = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode(["precio" => $row['precio']]);
  } else {
    echo json_encode(["error" => "Cotización no encontrada."]);
  }
}
?>
