<?php
session_start(); 
include 'conexion.php';

$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM vendedores WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['vendedor_id'] = $user['id'];
      $_SESSION['email'] = $user['email'];
      header("Location: index.php");
      exit;
    } else {
      $error = "Contraseña incorrecta.";
    }
  } else {
    $error = "Correo no encontrado.";
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
        nav{
            position: absolute;
        }
        .error { color: red; }
    </style>
  <title>Login Vendedor</title>
</head>
<body>
  <section>
    <h2>Login del Vendedor</h2>
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
      </div>
      <div class="form_Campos">
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </div>
      <input class="submit" type="submit" value="Iniciar sesión">
    </form>
  </section>
</body>
</html>
