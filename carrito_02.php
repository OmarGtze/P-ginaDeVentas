<?php
session_start(); // Inicia la sesión si no está iniciada

require "conecta.php";
$con = conecta();

$id_usuario = 1; // Asume que el usuario está identificado, ajusta esto según tu sistema de autenticación

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
$total = 0;

// Comprobar si hay productos en el carrito
if ($result->num_rows > 0) {
    // Calcular el subtotal y total
    while ($row = $result->fetch_assoc()) {
        $subtotal_producto = $row['costo'] * $row['cantidad'];
        $subtotal += $subtotal_producto;
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Pedido Final</title>
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

            th,
            td {
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

            .subtotal,
            .total {
                font-weight: bold;
            }
        </style>
    </head>

    <body>
        <h1>Pedido Final</h1>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mostrar la lista de productos en el carrito
                $result->data_seek(0); // Reiniciar el puntero del resultado
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['cantidad']); ?></td>
                        <td>$<?php echo htmlspecialchars($row['costo']); ?></td>
                        <td>$<?php $subtotal_producto = $row['costo'] * $row['cantidad'];
                        echo number_format($subtotal_producto, 2); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td>$<?php echo number_format($subtotal, 2); ?></td>
                </tr>
            </tbody>
        </table>
        <p>Este es tu pedido final. Ya no puedes modificarlo. Si deseas agregar más productos, realiza un nuevo pedido.</p>
        <!-- Botón para finalizar el pedido -->
        <form action="pedidos_finalizar.php" method="post">
            <button type="submit" class="btn">Finalizar Pedido</button>
        </form>

        <p> <a href="carrito.php" class="btn">Modificar Carrito</a><br><br> </p>
    </body>

    </html>
    <?php
} else {
    // Si no hay productos en el carrito, mostrar un mensaje y un enlace para volver a la tienda
    echo "Tu carrito está vacío. ¿Por qué no regresas a la tienda y te compras algo? :) ";
    echo '<a href="shop.php">Volver a la tienda</a>';
}
?>