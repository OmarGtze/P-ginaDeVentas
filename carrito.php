<?php
session_start(); // Inicia la sesión si no está iniciada

require "conecta.php";
$con = conecta();

$id_usuario = 1; // Asume que el usuario está identificado, ajusta esto según tu sistema de autenticación

// Verifica si NO hay una sesión abierta
if (!isset($_SESSION['nombreUser'])) {
    // Si no hay una sesión abierta, redirige al usuario a index.php
    header("Location: index.php");
    exit();
}

// Obtener los productos en el carrito del usuario
$sql = "SELECT productos.id, productos.nombre, productos.costo, pedidos_productos.cantidad 
        FROM pedidos_productos 
        INNER JOIN productos ON pedidos_productos.id_producto = productos.id 
        INNER JOIN pedidos ON pedidos_productos.id_pedido = pedidos.id 
        WHERE pedidos.id_usuario = ? AND pedidos.status = 0";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Variables para calcular el subtotal y el total
$subtotal = 0;
$total_productos = 0; // Variable para almacenar el total de productos

// Comprobar si hay productos en el carrito
if ($result->num_rows > 0) {
    // Mostrar la lista de productos en el carrito
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Carrito de Compras</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            h1 {
                text-align: center;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
            tr:hover {
                background-color: #f5f5f5;
            }
            td.actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .btn {
                padding: 8px 12px;
                background-color: #f89cab;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .btn:hover {
                background-color: #E9967A;
            }
            .subtotal, .total {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <h1>Carrito de Compras</h1>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['costo']); ?></td>
                        <td>$<?php $subtotal_producto = $row['costo'] * $row['cantidad']; echo number_format($subtotal_producto, 2); ?></td>
                        <td class="actions">
                            <form action="pedidos_procesar.php" method="post">
                                <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
                                <input type="number" name="cantidad" min="1" value="1">
                                <button type="submit" class="btn">Agregar más</button>
                            </form>
                            <form action="producto_eliminarCarrito.php" method="post">
                                <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn">Quitar</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                    // Sumar el subtotal del producto al subtotal total
                    $subtotal += $subtotal_producto;
                    $total_productos += $row['cantidad']; // Sumar la cantidad de cada producto al total
                    ?>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <!-- Guardar el total de productos en una variable de sesión -->
        <?php $_SESSION['total_productos'] = $total_productos; ?>

        <!-- Botón para proceder al pedido final (carrito_02.php) -->
        <form action="carrito_02.php" method="post">
            <button type="submit" class="btn">Proceder al Pedido Final</button>
        </form>

       <p> <a href="shop.php" class="btn">Seguir Comprando</a><br><br> </p>
    </body>
    </html>
    <?php
} else {
    // Si no hay productos en el carrito, mostrar un mensaje y un enlace para volver a la tienda
    $_SESSION['total_productos'] = 0; // Asegurar que la variable de sesión esté en 0 si el carrito está vacío
    echo "Tu carrito está vacío. ¿Por qué no regresas a la tienda y te compras algo? :) ";
    echo '<a href="shop.php">Volver a la tienda</a>';
}
?>
