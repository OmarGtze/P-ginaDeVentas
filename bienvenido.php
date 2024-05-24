<?php
session_start(); // Inicia la sesión

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

// Datos de las imágenes del banner
$slides = [
  ['image' => 'images/Wickles.png'],
  ['image' => 'images/Mantente conectado.png'],
  ['image' => 'images/Cyber Monday Sale.png'],
  ['image' => 'images/buenfin.jpg']
];

// Seleccionar una imagen aleatoria
$random_slide = $slides[array_rand($slides)];

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

  <title>
    Wickles
  </title>

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

<!-- Estilos para el banner -->
<style>
  .banner_section {
    position: relative;
    width: 100%;
    height: 400px;
    overflow: hidden;
  }

  .banner_container {
    position: relative;
    width: 100%;
    height: 100%;
  }

  .banner-item {
    position: absolute;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
  }

  .detail-box {
    position: relative;
    top: 50%;
    transform: translateY(-50%);
    color: white;
    background: rgba(0, 0, 0, 0.5);
    /* Fondo semitransparente para el texto */
    padding: 20px;
    border-radius: 5px;
  }

  .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-top: 10px;
  }

  .btn:hover {
    background-color: #45a049;
  }
</style>
<!-- Estilos para el banner fin -->

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
    <!-- banner section -->
    <section class="banner_section">
      <div class="banner_container">
        <div class="banner-item" style="background-image: url('<?php echo $random_slide['image']; ?>');"></div>
      </div>
    </section>
    <!-- end banner section -->
  </div>
  <!--  hero  -->

  <section class="shop_section layout_padding">
    <div class="container">
      <div class="heading_container heading_center">
        <h2>Lo último</h2>
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
          </div>
        <?php } ?>
      </div>
      <div class="btn-box">
        <a href="shop.php">
          Ver todos los productos
        </a>
      </div>
    </div>
  </section>
  <!--  shop termina -->

  <!-- contact section -->

  <section class="contact_section layout_padding">
    <div class="container px-0">
      <div class="heading_container ">
        <h2 class="">
          Contáctanos
        </h2>
      </div>
    </div>
    <div class="container container-bg">
      <div class="row">
        <div class="col-lg-7 col-md-6 px-0">
          <div class="map_container">
            <div class="map-responsive">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3733.36164026543!2d-103.3280246249792!3d20.654861080902545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8428b23a9bbba80d%3A0xdacdb7fd592feb90!2sUniversity%20Center%20of%20Exact%20Sciences%20and%20Engineering!5e0!3m2!1sen!2smx!4v1716264026918!5m2!1sen!2smx"
                width="600" height="300" frameborder="0" style="border:0; width: 100%; height:100%"
                allowfullscreen></iframe>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-5 px-0">
          <form action="enviarCorreo.php" method="post">
            <div class="mb-3">
              <label for="nombre" class="form-label">Nombre</label>
              <input type="text" name="nombre" class="form-control" id="nombre" />
            </div>
            <div>
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="email" />
            </div>
            <div class="form-floating">
              <textarea class="form-control" placeholder="Cuéntanos algo" id="texto" name="texto"
                style="height: 100px"></textarea>

            </div>
            <div class="d-flex ">
              <button type="submit" class="btn btn-success mt-3">
                ENVIAR
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- end contact section -->

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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <script src="js/custom.js"></script>

</body>

</html>