<?php

require "conecta.php";
$con = conecta();

// Modifica tu consulta SQL para seleccionar 4 productos aleatorios
$sql = "SELECT productos.id, productos.nombre, productos.costo, productos.foto 
        FROM productos 
        WHERE productos.eliminado = 0 
        ORDER BY RAND() 
        LIMIT 4";
$stmt = $con->prepare($sql);
$stmt->execute();
$res = $stmt->get_result();

$total_productos = isset($_SESSION['total_productos']) ? $_SESSION['total_productos'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
  $id = $_GET["id"];

  $sql = "SELECT nombre, codigo, descripcion, costo, stock, status, foto FROM productos WHERE id = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($nombre, $codigo, $descripcion, $costo, $stock, $status, $foto);
    $stmt->fetch();

    // Convertir el estado a texto
    $status_texto = $status == 1 ? 'Disponible' : 'No Disponible';
  } else {
    echo "<p>Producto no encontrado.</p>";
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Detalles del Producto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
    integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA=="
    crossorigin="anonymous" />

</head>


<body>

  <div class="card-wrapper">
    <div class="card">
      <div class="product-imgs">

        <?php if (!empty($foto)) {
          echo "<img src='$foto' alt='Foto del producto'>";
        } else {
          echo "<p>No se ha proporcionado una foto para este producto.</p>";
        } ?>
      </div>
      <div class="product-content">
        <h2 class="product-title"><?php echo "<p>$nombre</p>"; ?></h2>

        <div class="product-price">
          <p class="new-price">Precio: <span>$<?php echo "$costo"; ?></span></p>
        </div>

        <div class="product-detail">
          <h2>Sobre este artículo: </h2>
          <p><?php echo "$descripcion"; ?></p>
          <ul>
            <li>Estatus: <span><?php echo "$status_texto"; ?></span></li>
          </ul>
        </div>

        <div class="purchase-info">
          <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
          <form action="pedidos_procesar.php" method="post">
            <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
            <input type="number" name="cantidad" min="1" value="1">
            <button type="submit" class="btn">Añadir al Carrito <i class="fas fa-shopping-cart"></i></button>
          </form>
          <a href="shop.php" class="btn" style="margin-top: 10px;">Regresar a la tienda</a>
        </div>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>

</html>