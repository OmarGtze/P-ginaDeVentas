<?php
require "conecta.php";
$con = conecta();

$id_usuario = 1; // Asume que el usuario está identificado, ajusta esto según tu sistema de autenticación

// Verificar si hay un pedido abierto para el usuario
$sql = "SELECT id FROM pedidos WHERE id_usuario = ? AND status = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Hay un pedido abierto, cerrarlo
    $pedido = $result->fetch_assoc();
    $id_pedido = $pedido['id'];

    $sql = "UPDATE pedidos SET status = 1 WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_pedido);
    $stmt->execute();
}

// Redirigir a la página de nuevo pedido para iniciar uno nuevo
header("Location: shop.php");
exit();
?>
