<?php
session_start(); // Inicia la sesión si no está iniciada

require "conecta.php";
$con = conecta();

// Verifica si se ha enviado el ID del producto a eliminar
if (isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    
    // Verifica si el producto existe en el carrito
    $sql_check = "SELECT * FROM pedidos_productos WHERE id_producto = ?";
    $stmt_check = $con->prepare($sql_check);
    if (!$stmt_check) {
        die("Error en la preparación de la consulta: " . $con->error);
    }
    $stmt_check->bind_param("i", $id_producto);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // Elimina el producto del carrito
        $sql_delete = "DELETE FROM pedidos_productos WHERE id_producto = ?";
        $stmt_delete = $con->prepare($sql_delete);
        if (!$stmt_delete) {
            die("Error en la preparación de la consulta: " . $con->error);
        }
        $stmt_delete->bind_param("i", $id_producto);
        
        if ($stmt_delete->execute()) {
            // Producto eliminado correctamente
            header("Location: carrito.php"); // Redirige de vuelta al carrito
            exit();
        } else {
            // Error al eliminar el producto
            echo "Error al eliminar el producto del carrito.";
        }
    } else {
        // El producto no existe en el carrito
        echo "El producto no existe en el carrito.";
    }
} else {
    // Si no se proporcionó un ID de producto válido, redirige de vuelta al carrito
    header("Location: carrito.php");
    exit();
}

// Cierra las declaraciones y la conexión
$stmt_check->close();
if (isset($stmt_delete)) {
    $stmt_delete->close();
}
$con->close();
?>
