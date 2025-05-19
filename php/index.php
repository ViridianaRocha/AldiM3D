<?php
include 'auth.php';
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de administración</title>
  
    <!--FONTS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
  <!-- CSS -->
  <link rel="stylesheet" href="../css/header-footer.css">
  <link rel="stylesheet" href="../css/header-footer_movil.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/index_movil.css">
  <link rel="stylesheet" href="../css/general.css">
  <link rel="stylesheet" href="../css/formularios.css">
  <link rel="stylesheet" href="../css/formularios_movil.css">
  <link rel="stylesheet" href="../css/tabla.css">

  <!-- STYLE -->
  <style>
    @media screen and (max-width: 768px) {
      .hide-mobile {
        display: none;
      }

      table {
        width: 100%;
        font-size: 0.9rem;
        table-layout: auto;
      }

      th, td {
        padding: 8px;
        white-space: nowrap;
      }
    }
  </style>
</head>
<body>

<nav class="menu_Nav">
  <div>
    <a class="a2" href="#" id="btn-cotizaciones">Cotizaciones</a>
    <a class="a2" href="#" id="btn-pedidos">Pedidos</a>
  </div>
</nav>

<section id="sc7">
  <div class="container" id="cotizaciones">
    <h2>Cotizaciones recibidas</h2>
    <table>
      <tr>
        <th>Id</th>
        <th class="hide-mobile">Email</th>
        <th class="hide-mobile">Fecha</th>
        <th class="hide-mobile">Responder</th>
        <th>Detalles</th>
      </tr>
      <?php
      $res = $conn->query("
        SELECT * FROM cotizaciones 
        WHERE id NOT IN (SELECT id_cotizacion FROM pedidos) 
        ORDER BY fecha DESC
      ");
      while($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id']}</td>
          <td class='hide-mobile'>{$row['email']}</td>
          <td class='hide-mobile'>{$row['fecha']}</td>
          <td class='hide-mobile'><a href='responder.php?id={$row['id']}'>Responder</a></td>
          <td><a href='cotizacion.php?id={$row['id']}'>Detalles</a></td>
        </tr>";
      }
      ?>
    </table>
  </div>

  <div class="container" id="pedidos" style="display:none;">
    <h2>Pedidos realizados</h2>
    <table>
      <tr>
        <th>Cotización</th>
        <th class="hide-mobile">Estado</th>
        <th class="hide-mobile">Fecha</th>
        <th>Detalles</th>
      </tr>
      <?php
      $res = $conn->query("SELECT * FROM pedidos ORDER BY fecha_pedido DESC");
      while($row = $res->fetch_assoc()) {
        echo "<tr>
          <td>{$row['id_cotizacion']}</td>
          <td class='hide-mobile'>{$row['estado']}</td>
          <td class='hide-mobile'>{$row['fecha_pedido']}</td>
          <td><a href='pedido.php?id={$row['id']}'>Detalles</a></td>
        </tr>";
      }
      ?>
    </table>
  </div>
</section>

<footer>
  <div class="madeBy">
    <a href="../html/desarrollador.html">Desarrollado por Viridiana Rocha</a>
  </div>
  <div class="container_Foot">
    <div class="extras">
      <a class="a2" href="registro.php">Registrar vendedor</a>
      <a class="a2" href="logout.php">Cerrar Sesión</a>
      <a class="a1" href="../index.html">AldiM 3D</a>
    </div>
  </div>
</footer>

<script>
  document.getElementById('btn-cotizaciones').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('cotizaciones').style.display = 'block';
    document.getElementById('pedidos').style.display = 'none';
  });

  document.getElementById('btn-pedidos').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('cotizaciones').style.display = 'none';
    document.getElementById('pedidos').style.display = 'block';
  });
</script>

</body>
</html>
