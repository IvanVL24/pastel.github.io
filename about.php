<!DOCTYPE html>
<!--
	Resto by GetTemplates.co
	URL: https://gettemplates.co
-->
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Resto - Restaurant Bootstrap 4 Template by GetTemplates.co</title>
    <meta name="description" content="Resto">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- External CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" href="vendor/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/brands.css">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Josefin+Sans:300,400,700">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" href="css/style.min.css">

    <!-- Modernizr JS for IE8 support of HTML5 elements and media queries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

</head>
<?php
        include "header.php";
        ?>
<section id="gtco-welcome" class="bg-white section-padding">
            <div class="container">
                <div class="section-content">
                    <div class="row">
                        <div class="col-sm-5 img-bg d-flex shadow align-items-center justify-content-center justify-content-md-end img-2"
                            style="background-image: url(img/rollo.jpg);">

                        </div>
<div class="col-sm-7 py-5 pl-md-0 pl-4">
                            <div class="heading-section pl-lg-5 ml-md-5">
                                <span class="subheading">
                                    About
                                </span>
                                <h2>
                                    Bienvenido a Pasteleria Esperanza
                                </h2>
                            </div>
                            <div class="pl-lg-5 ml-md-5">
                                <p>Nuestra Historia

                                    ​​​​​​​ Desde 1975, endulzando la vida de México

                                    En el corazón de la Ciudad de México, nació un sueño: Pastelerías Esperanza. Fundada
                                    en 1975 por los hermanos Francisco Juamperez Barberena y Pedro J. B., esta panadería
                                    artesanal se convirtió rápidamente en un referente de sabor y tradición en la
                                    colonia Escuadrón 201, Iztapalapa.

                                    Un sabor que conquista paladares

                                    Su compromiso con la frescura, la calidad y el sabor excepcional los llevó al éxito.
                                    Sus productos se convirtieron en los favoritos de la zona, impulsando la expansión
                                    del negocio. Poco a poco, abrieron 13 sucursales más en Iztapalapa, consolidando su
                                    presencia en la zona.

                                    Un crecimiento imparable

                                    A principios del año 2000, la empresa experimentó un crecimiento exponencial. Para
                                    el 2005, ya contaban con 35 sucursales en la zona oriente de la Ciudad de México.
                                    Para optimizar la gestión y rentabilidad del negocio, implementaron un sistema de
                                    registro de ingresos y software, una innovación necesaria para su adaptación al
                                    mercado.</p>
                                
                                <div class="row">
                                    <?php
                                    $count = 0; // Inicializamos el contador
                                    foreach ($resultado as $row) {
                                        if ($row['id_categoria'] == 3) {
                                            if ($count >= 3) {
                                                break; // Rompemos el ciclo cuando hemos impreso 6 elementos
                                            }
                                    ?>
                                            <div class="col-4">
                                                <?php
                                                $id = $row['id'];
                                                $nombre = $row['nombre'];
                                                $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
                                                $imgformat = '';
                                                foreach ($formats as $format) {
                                                    $filePath = "ima/$id/principal.$format";
                                                    if (file_exists($filePath)) {
                                                        $imgformat = $format;
                                                        break;
                                                    }
                                                }
                                                ?>
                                                <a class="thumb-menu" href="details.php"
                                                </a>
                                            </div>
                                    <?php
                                            $count++; // Incrementamos el contador después de imprimir un elemento
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <section id="gtco-special-dishes" class="bg-grey section-padding">
            <div class="container">
                <div class="section-content">
                    <div class="heading-section text-center">
                        <span class="subheading">
                            Especialidad
                        </span>
                        <h2>
                            Reposteria
                        </h2>
                    </div>
                    <?php
                    $count = 0; // Inicializamos el contador
                    foreach ($resultado as $row) {
                        if ($row['id'] == 8) {
                            $id = $row['id'];
                            $nombre = $row['nombre'];
                            $descripcion = $row['descripcion'];
                            $precio = $row['precio_con_descuento'];
                            $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
                            $imgformat = '';
                            foreach ($formats as $format) {
                                $filePath = "ima/$id/principal.$format";
                                if (file_exists($filePath)) {
                                    $imgformat = $format;
                                    break;
                                }
                            }
                            if ($count >= 1) {
                                break; // Rompemos el ciclo cuando hemos impreso 6 elementos
                            }
                    ?>
                            <div class="row">
                                <div class="col-lg-5 col-md-6 align-self-center">
                                    <h2 class="special-number">01.</h2>
                                    <div class="dishes-text">
                                        <h3><span> <?php echo $nombre; ?></span></h3>
                                        <p class="pt-3">Encuentra este pastel en todas nuestras sucursales , <?php echo $descripcion; ?> como Sabor principal de este pastel
                                        </p>
                                        <h3 class="special-dishes-price"><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></h3>
                                        <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>" class="btn-primary mt-3">Encuentralo Aqui
                                            <i
                                                class="fa fa-long-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 offset-lg-2 col-md-6 align-self-center mt-4 mt-md-0">
                                    <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>">
                                        <img class="img-fluid shadow w-100" src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>">
                                    </a>
                                </div>
                            </div>
                    <?php
                            $count++; // Incrementamos el contador después de imprimir un elemento
                        }
                    }
                    ?>
                    <?php
                    $count = 0; // Inicializamos el contador
                    foreach ($resultado as $row) {
                        if ($row['id'] == 9) {
                            $id = $row['id'];
                            $nombre = $row['nombre'];
                            $descripcion = $row['descripcion'];
                            $precio = $row['precio_con_descuento'];
                            $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
                            $imgformat = '';
                            foreach ($formats as $format) {
                                $filePath = "ima/$id/principal.$format";
                                if (file_exists($filePath)) {
                                    $imgformat = $format;
                                    break;
                                }
                            }
                            if ($count >= 1) {
                                break; // Rompemos el ciclo cuando hemos impreso 6 elementos
                            }
                    ?>
                     <div class="row mt-5">
                                <div class="col-lg-5 col-md-6 align-self-center order-2 order-md-1 mt-4 mt-md-0">
                                    <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>">
                                        <img class="img-fluid shadow w-100" src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>">
                                    </a>
                                </div>
                                <div class="col-lg-5 offset-lg-2 col-md-6 align-self-center order-1 order-md-2 py-5">
                                    <h2 class="special-number">02.</h2>
                                    <div class="dishes-text">
                                        <h3><span><?php echo $nombre; ?></span></h3>
                                        <p class="pt-3">Prueba nuestro delicioso <?php echo $descripcion; ?>, Pastel de
                                            temporada solo disponible en nuestras sucursales de Nezahualcoyotl</p>
                                        <h3 class="special-dishes-price"><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></h3>
                                        <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>" class="btn-primary mt-3">
                                            <i
                                                class="fa fa-long-arrow-right"></i>Encuentralo Aqui</a>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $count++; // Incrementamos el contador después de imprimir un elemento
                        }
                    }
                    ?>
                    <?php
                    $count = 0; // Inicializamos el contador
                    foreach ($resultado as $row) {
                        if ($row['id'] == 10) {
                            $id = $row['id'];
                            $nombre = $row['nombre'];
                            $descripcion = $row['descripcion'];
                            $precio = $row['precio_con_descuento'];
                            $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
                            $imgformat = '';
                            foreach ($formats as $format) {
                                $filePath = "ima/$id/principal.$format";
                                if (file_exists($filePath)) {
                                    $imgformat = $format;
                                    break;
                                }
                            }
                            if ($count >= 1) {
                                break; // Rompemos el ciclo cuando hemos impreso 6 elementos
                            }
                    ?>
                            <div class="row mt-5">
                                <div class="col-lg-5 col-md-6 align-self-center py-5">
                                    <h2 class="special-number">03.</h2>
                                    <div class="dishes-text">
                                        <h3><span><?php echo $nombre; ?></span></h3>
                                        <p class="pt-3">Prueba nuestro delicioso <?php echo $nombre; ?> ,
                                            disponible en todas nuestras sucursales existentes , No dejes pasar esta increible
                                            oferta<br>
                                            Pruebalo ya!!! </p>
                                        <h3 class="special-dishes-price"><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></h3>
                                        <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>" class="btn-primary mt-3">Encuentralo Aqui
                                            <i
                                                class="fa fa-long-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-lg-5 offset-lg-2 col-md-6 align-self-center mt-4 mt-md-0">
                                    <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>">
                                        <img class="img-fluid shadow w-100" src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>">
                                    </a>
                                </div>
                            </div>
                    <?php
                            $count++; // Incrementamos el contador después de imprimir un elemento
                        }
                    }
                    ?>
                </div>
            </div>
        </section>


			

	<!-- External JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
	<script src="vendor/bootstrap/popper.min.js"></script>
	<script src="vendor/bootstrap/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js "></script>
	<script src="vendor/owlcarousel/owl.carousel.min.js"></script>
	<script src="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js"></script>
	<script src="vendor/stellar/jquery.stellar.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

	<!-- Main JS -->
	<script src="js/app.min.js "></script>
</body>
</html>
