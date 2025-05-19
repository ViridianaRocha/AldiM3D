<?php
include 'auth.php';
include 'conexion.php';

$mensaje = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Validar que no exista ya
  $check = $conn->prepare("SELECT * FROM vendedores WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $res = $check->get_result();

  if ($res->num_rows > 0) {
    $mensaje = "Ya existe un vendedor con ese correo.";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO vendedores (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hash);
    if ($stmt->execute()) {
      $mensaje = "Vendedor registrado correctamente.";
    } else {
      $mensaje = "Error: " . $stmt->error;
    }
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
    </style>
  <title>Registrar Vendedor</title>
</head>
<body>
  <section>
    <h2>Registrar vendedor</h2>
    <form method="POST">
      <div class="container_Form">
        <div class="form_Campos">
          <label>Correo:</label>
          <input type="email" name="email" required>
        </div>
        <div class="form_Campos">
          <label>Contraseña:</label>
          <input type="password" name="password" required>
        </div>
        <div class="form_Campos">
          <?php if ($mensaje): ?>
            <p class="<?= str_starts_with($mensaje, '✅') ? 'mensaje' : 'error' ?>"><?= $mensaje ?></p>
          <?php endif; ?>
        </div>
      <input class="submit" type="submit" value="Registrar">
    </form>
  </section>
</body>
</html>
