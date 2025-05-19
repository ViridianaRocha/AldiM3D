<?php 
include 'conexion.php';
include 'auth.php';

$id = $_GET['id'];
$cot = $conn->query("SELECT * FROM cotizaciones WHERE id=$id")->fetch_assoc();
$respuesta = $conn->query("SELECT * FROM respuestas_cotizacion WHERE id_cotizacion=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $precio = $_POST['precio'];
  $fecha = $_POST['fecha_entrega'];
  $comentarios = $_POST['comentarios'];

  if ($respuesta) {
    $stmt = $conn->prepare("UPDATE respuestas_cotizacion SET precio=?, fecha_entrega=?, comentarios=? WHERE id_cotizacion=?");
    $stmt->bind_param("dssi", $precio, $fecha, $comentarios, $id);
  } else {
    $stmt = $conn->prepare("INSERT INTO respuestas_cotizacion (id_cotizacion, precio, fecha_entrega, comentarios) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $id, $precio, $fecha, $comentarios);
  }

  if ($stmt->execute()) {
    $to = $cot['email'];
    $subject = "Respuesta a tu cotización #$id en AldiM 3D";
    $message = "Hola,\n\nHemos respondido a tu cotización #$id con la siguiente información:\n\n" .
               "Precio estimado: $$precio\n" .
               "Fecha de entrega: $fecha\n" .
               "Comentarios adicionales: $comentarios\n\n" .
               "En caso de dudas, respondenos a este correo: dudas_aldim3d@gmail.com\n\n" .
               "Para continuar con tu pedido, por favor visita nuestro sitio web y sigue los pasos.\n\n" .
               "Gracias por confiar en AldiM 3D.";

    $headers = "From: no-reply@aldim3d.com";

    if (mail($to, $subject, $message, $headers)) {
      echo "<p style='color:green;'>Respuesta guardada y correo enviado al cliente.</p>";
    } else {
      echo "<p style='color:orange;'>Respuesta guardada, pero no se pudo enviar el correo.</p>";
    }
  } else {
    echo "<p style='color:red;'>Error al guardar la respuesta: {$stmt->error}</p>";
  }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-SLQM0BRC3E"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'G-SLQM0BRC3E');
    </script>
    <!--ICONOS-->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!--FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <!--CSS-->
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/login_movil.css">
    <!--STYLE-->
    <style>
        .error { color: red; }
        section form .submit {
          width: 40%;
        }
    </style>
  <title>Login Vendedor</title>
</head>
<body>
  <section>
    <h2>Responder Cotización #<?= $id ?></h2>
    <form method="POST">
      <div class="container_Form">
        <div class="form_Campos">
          <label>Precio:</label>
          <input type="text" name="precio" value="<?= $respuesta['precio'] ?? '' ?>" required>
        </div>
        <div class="form_Campos">
          <label>Fecha estimada de entrega:</label>
          <input type="date" name="fecha_entrega" value="<?= $respuesta['fecha_entrega'] ?? '' ?>" required>
        </div>
        <div class="form_Campos">
          <label>Comentarios:</label>
          <textarea name="comentarios"><?= $respuesta['comentarios'] ?? '' ?></textarea>
        </div>
      </div>
      <input class="submit" type="submit" value="Guardar respuesta">
    </form>
  </section>
</body>
</html>


