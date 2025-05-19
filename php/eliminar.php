<?php
include 'auth.php';
include 'conexion.php';

if (isset($_GET['tipo'])) {
  $tipo = $_GET['tipo'];

  if ($tipo === 'cotizacion' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM respuestas_cotizacion WHERE id_cotizacion = $id");
    $conn->query("DELETE FROM cotizaciones WHERE id = $id");
    header("Location: index.php");
    exit;
  }

  if ($tipo === 'pedido' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("DELETE FROM pedidos WHERE id = $id");
    header("Location: index.php");
    exit;
  }

  if ($tipo === 'pedido_y_cotizacion' && isset($_GET['id_pedido'], $_GET['id_cotizacion'])) {
    $idPedido = intval($_GET['id_pedido']);
    $idCot = intval($_GET['id_cotizacion']);

    $conn->query("DELETE FROM pedidos WHERE id = $idPedido");
    $conn->query("DELETE FROM respuestas_cotizacion WHERE id_cotizacion = $idCot");
    $conn->query("DELETE FROM cotizaciones WHERE id = $idCot");

    header("Location: index.php");
    exit;
  }
}
?>
