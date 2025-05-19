<?php
include 'auth.php';
include 'conexion.php';

$id = $_GET['id'];

// Procesar cambio de estado si se envía el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nuevo_estado'])) {
  $nuevo_estado = $_POST['nuevo_estado'];
  $update = $conn->prepare("UPDATE pedidos SET estado = ? WHERE id = ?");
  $update->bind_param("si", $nuevo_estado, $id);
  $update->execute();
}

// Obtener pedido
$stmtP = $conn->prepare("SELECT * FROM pedidos WHERE id = ?");
$stmtP->bind_param("i", $id);
$stmtP->execute();
$pedido = $stmtP->get_result()->fetch_assoc();

if (!$pedido) {
  die("❌ Pedido no encontrado.");
}

$cot_id = $pedido['id_cotizacion'];
$cotizacion = $conn->query("SELECT * FROM cotizaciones WHERE id = $cot_id")->fetch_assoc();
$respuesta = $conn->query("SELECT * FROM respuestas_cotizacion WHERE id_cotizacion = $cot_id")->fetch_assoc();
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

    <link rel="stylesheet" href="../css/general.css">
    <link rel="stylesheet" href="../css/detalles.css">
    <!--STYLE-->
  <style>
  </style>
    <title>Detalle de Pedido</title>
</head>
<body>
  <section class="container">
    <section class="container_dt">
      <section class="dt1">
        <h2>Detalles de Cotización #<?= $cot_id ?></h2>
        <p><strong>Email:</strong> <?= $cotizacion['email'] ?></p>
        <p><strong>Material:</strong> <?= $cotizacion['material'] ?></p>
        <p><strong>Medidas:</strong> <?= $cotizacion['medidas'] ?></p>
        <p><strong>Color:</strong> <?= $cotizacion['color'] ?></p>
        <p><strong>Calidad:</strong> <?= $cotizacion['calidad'] ?></p>
        <p><strong>Lijado:</strong> <?= $cotizacion['lijado'] ? 'Sí' : 'No' ?></p>
        <p><strong>Pintado:</strong> <?= $cotizacion['pintado'] ? 'Sí' : 'No' ?></p>
        <p><strong>Base:</strong> <?= $cotizacion['base'] ? 'Sí' : 'No' ?></p>
        <p><strong>Forma base:</strong> <?= $cotizacion['forma_base'] ?></p>
        <p><strong>Color base:</strong> <?= $cotizacion['color_base'] ?></p>
        <p><strong>Detalles:</strong> <?= $cotizacion['detalles'] ?></p>
        <p><strong>Fecha:</strong> <?= $cotizacion['fecha'] ?></p>
        <p><strong>Archivo STL:</strong> <a href="../<?= $cotizacion['archivo_stl'] ?>" download>Descargar STL</a></p>
      </section>
      <section class="dt5">
          <form action="eliminar.php" method="GET" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este pedido y su cotización asociada? Esta acción no se puede deshacer.')">
            <input type="hidden" name="tipo" value="pedido_y_cotizacion">
            <input type="hidden" name="id_pedido" value="<?= $id ?>">
            <input type="hidden" name="id_cotizacion" value="<?= $cot_id ?>">
            <button class="submit" type="submit">Eliminar pedido y cotización</button>
          </form>
        </section>
    </section>
    <section class="container_dt">
      <section class="dt2">
        <h2>Detalle de Pedido #<?= $id ?></h2>
        <p><strong>Dirección:</strong> <?= $pedido['direccion_envio'] ?></p>
        <p><strong>Método de pago:</strong> Pagado</p>
        <p><strong>Estado actual:</strong> <?= $pedido['estado'] ?></p>
        <p><strong>Fecha de pedido:</strong> <?= $pedido['fecha_pedido'] ?></p>
      </section>

      <section class="dt3">
        <h3>Actualizar estado del pedido</h3>
      <form method="POST">
        <select name="nuevo_estado" required>
          <option value="">Seleccionar estado</option>
          <option value="Pendiente" <?= $pedido['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
          <option value="En producción" <?= $pedido['estado'] === 'En producción' ? 'selected' : '' ?>>En producción</option>
          <option value="Enviado" <?= $pedido['estado'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
          <option value="Entregado" <?= $pedido['estado'] === 'Entregado' ? 'selected' : '' ?>>Entregado</option>
        </select>
        <button type="submit">Actualizar</button>
      </form>
      </section>

      <section class="dt4">
        <?php if ($respuesta): ?>
          <h3>Respuesta</h3>
          <p><strong>Precio:</strong> $<?= $respuesta['precio'] ?></p>
          <p><strong>Entrega estimada:</strong> <?= $respuesta['fecha_entrega'] ?></p>
          <p><strong>Comentarios:</strong> <?= $respuesta['comentarios'] ?></p>
        <?php else: ?>
        <p><em>❗ La cotización aún no ha sido respondida.</em></p>
        <?php endif; ?>
        </section>
      </section>
    </section>
</body>
</html>
