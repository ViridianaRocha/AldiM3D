<?php
$conn = new mysqli("localhost", "root", "", "aldim3d");
if ($conn->connect_error) die("Error: " . $conn->connect_error);

// Obtener datos del formulario
$id_cotizacion = $_POST['id_cotizacion'];
$direccion_envio = $_POST['direccion_envio'];
$metodo_pago = $_POST['metodo_pago'];

// Validar que la cotización existe y fue respondida
$check = $conn->query("SELECT * FROM respuestas_cotizacion WHERE id_cotizacion = $id_cotizacion");
if ($check->num_rows === 0) {
  die("Esta cotización aún no ha sido respondida por el vendedor.");
}

// Insertar pedido
$sql = "INSERT INTO pedidos (id_cotizacion, direccion_envio, metodo_pago) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $id_cotizacion, $direccion_envio, $metodo_pago);

if ($stmt->execute()) {
  echo "Pedido registrado correctamente. ¡Gracias!";
} else {
  echo "Error al registrar el pedido: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
