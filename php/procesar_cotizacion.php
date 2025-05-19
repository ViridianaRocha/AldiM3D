<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "aldim3d";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Error conexión: " . $conn->connect_error);

// Obtener datos
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$material = $_POST['material'];
$medidas = $_POST['medidas'];
$color = $_POST['color'] ?? null;
$calidad = $_POST['calidad'];
$lijado = isset($_POST['lijado']) ? 1 : 0;
$pintado = isset($_POST['pintado']) ? 1 : 0;
$base = isset($_POST['base']) ? 1 : 0;
$forma_base = $_POST['forma_base'] ?? null;
$color_base = $_POST['color_base'] ?? null;
$detalles = $_POST['detalles'] ?? null;

// Subir STL
$stl_path = null;
if (isset($_FILES['archivo_stl']) && $_FILES['archivo_stl']['error'] === 0) {
  $stl_dir = "uploads/stl/";
  if (!is_dir($stl_dir)) mkdir($stl_dir, 0777, true);
  $stl_path = $stl_dir . basename($_FILES['archivo_stl']['name']);
  move_uploaded_file($_FILES['archivo_stl']['tmp_name'], $stl_path);
}

// Subir imágenes de pintura
$imagenes_paths = [];
if ($pintado && isset($_FILES['imagenes_pintado'])) {
  $img_dir = "uploads/pintado/";
  if (!is_dir($img_dir)) mkdir($img_dir, 0777, true);
  foreach ($_FILES['imagenes_pintado']['tmp_name'] as $i => $tmp) {
    if ($_FILES['imagenes_pintado']['error'][$i] === 0) {
      $name = basename($_FILES['imagenes_pintado']['name'][$i]);
      $path = $img_dir . $name;
      move_uploaded_file($tmp, $path);
      $imagenes_paths[] = $path;
    }
  }
}
$imagenes_json = json_encode($imagenes_paths);

$sql = "INSERT INTO cotizaciones (nombre,
  email, material, medidas, archivo_stl, color, calidad,
  lijado, pintado, imagenes_pintado, base, forma_base,
  color_base, detalles
) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
  "sssssssiiissss",
  $nombre, $email, $material, $medidas, $stl_path, $color, $calidad,
  $lijado, $pintado, $imagenes_json, $base, $forma_base,
  $color_base, $detalles
);

if ($stmt->execute()) {
  echo "Cotización enviada con éxito.";
} else {
  echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
