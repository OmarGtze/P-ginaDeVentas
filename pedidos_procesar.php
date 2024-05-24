<?php
require "conecta.php";
$con = conecta();

$id_usuario = 1; // Asume que el usuario está identificado, ajusta esto según tu sistema de autenticación
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];

// Obtener el costo del producto
$sql = "SELECT costo FROM productos WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
$costo = $producto['costo'];

// Llamar a la función para agregar el producto al pedido
agregarProductoAPedido($id_usuario, $id_producto, $cantidad, $costo);

// Redirigir al formulario para seguir agregando productos
header("Location: carrito.php");
exit();

function agregarProductoAPedido($id_usuario, $id_producto, $cantidad, $costo) {
    global $con;

    // Verificar si hay un pedido abierto para el usuario
    $sql = "SELECT id FROM pedidos WHERE id_usuario = ? AND status = 0";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hay un pedido abierto, usar su ID
        $pedido = $result->fetch_assoc();
        $id_pedido = $pedido['id'];
    } else {
        // No hay pedido abierto, crear uno nuevo
        $sql = "INSERT INTO pedidos (fecha, id_usuario, status) VALUES (NOW(), ?, 0)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $id_pedido = $stmt->insert_id;
    }

    // Verificar si el producto ya está en el pedido
    $sql = "SELECT id, cantidad FROM pedidos_productos WHERE id_pedido = ? AND id_producto = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $id_pedido, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El producto ya está en el pedido, actualizar la cantidad
        $pedido_producto = $result->fetch_assoc();
        $nueva_cantidad = $pedido_producto['cantidad'] + $cantidad;

        $sql = "UPDATE pedidos_productos SET cantidad = ?, precio = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("idi", $nueva_cantidad, $costo, $pedido_producto['id']);
    } else {
        // El producto no está en el pedido, agregarlo
        $sql = "INSERT INTO pedidos_productos (id_pedido, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiid", $id_pedido, $id_producto, $cantidad, $costo);
    }

    $stmt->execute();
}
?>
