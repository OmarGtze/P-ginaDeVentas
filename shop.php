<?php
session_start(); // Inicia la sesión

require "conecta.php";
$con = conecta();

// Variables para calcular el subtotal y el total
$subtotal = 0;
$total_productos = 0; // Variable para almacenar el total de productos


$total_productos = isset($_SESSION['total_productos']) ? $_SESSION['total_productos'] : 0;

$sql = "SELECT * FROM productos WHERE status = 1 AND eliminado = 0";
$res = $con->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />


  <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">

  <title>Wickles</title>

  <!-- slider stylesheet -->
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />
</head>

<body>
  <div class="hero_area">
    <!-- header  -->
    <header class="header_section">
      <nav class="navbar navbar-expand-lg custom_nav-container ">
        <a class="navbar-brand" href="index.html">
          <span>
            Wickles
          </span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class=""></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav  ">
            <li class="nav-item">
              <a class="nav-link" href="bienvenido.php">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shop.php">
                Productos
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contacto.php">Contáctanos</a>
            </li>
          </ul>
          <div class="user_option">
            <?php if (isset($_SESSION['nombreUser'])): ?>
              <a>
                <i class="fa fa-user" aria-hidden="true"></i>
                <span><?php echo htmlspecialchars($_SESSION['nombreUser']); ?></span>
              </a>
              <a href="logout.php">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                <span>Cerrar Sesión</span>
              </a>
            <?php else: ?>
              <a href="index.php">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Ingresar</span>
              </a>
            <?php endif; ?>
            <a href="carrito.php">Carrito (<?php echo $total_productos; ?>)</a>
          </div>
        </div>
      </nav>



    </header>
    <!--  header  -->
  </div>
  <!-- hero -->

  <!-- sección shop bienvenido inicio -->
  <section class="shop_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Nuestros Productos</h2>
      </div>
      <div class="row">
        <?php while ($row = $res->fetch_assoc()) { ?>
          <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="box">
              <a href="productos_detalle.php?id=<?php echo $row['id']; ?>">
                <div class="img-box">
                  <?php if (!empty($row['foto'])) { ?>
                    <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto del producto">
                  <?php } else { ?>
                    <p>No se ha proporcionado una foto para este producto.</p>
                  <?php } ?>
                </div>
                <div class="detail-box">
                  <h6><?php echo htmlspecialchars($row['nombre']); ?></h6>
                  <h6>Precio <span>$<?php echo htmlspecialchars($row['costo']); ?></span></h6>
                </div>
                <div class="new">
                  <span>Nuevo</span>
                </div>
              </a>
            </div>
            <div class="purchase-info">
              <form action="pedidos_procesar.php" method="post">
                <input type="hidden" name="id_producto" value="<?php echo $row['id']; ?>">
                <input type="number" name="cantidad" min="1" value="1">
                <button type="submit" class="btn">Añadir al Carrito</button>
              </form>
            </div>

          </div>
        <?php } ?>
      </div>
    </div>
  </section>
  <!-- sección shop bienvenido final -->

  <!-- info -->

  <section class="info_section layout_padding2-top">
    <div class="info_container">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-3 text-center">
            <h6>Sobre Nosotros</h6>
            <p>Somos una tienda de todo un poco, pero principalmente de tecnología</p>
          </div>
          <div class="col-md-6 col-lg-3 text-center">
            <h6>Contáctanos</h6>
            <div class="info_link-box">
              <a>
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <span> CUCEI </span>
              </a>
              <a>
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>+55 12345678901</span>
              </a>
              <a>
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span> revolution6978@gmail.com</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!--  info  -->

  <script src="js/jquery-3.4.1.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="js/custom.js"></script>

</body>

</html>