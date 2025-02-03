<?php
require_once 'config/config.php';
require_once 'config/conexion.php';
$db = new Database();
$conn = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Eror al procesar la petición';
    exit;
} else {

    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

    if ($token == $token_tmp) {

        $sql = $conn->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {

            $sql = $conn->prepare("SELECT productos.* FROM productos WHERE id=? AND activo=1
            LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $edicion = $row['descripcion'];
            $precio = $row['precio'];
            $descuento = $row['descuento'];
            $stock = $row['stock'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
            $imgformat = '';
            foreach ($formats as $format) {
                $filePath = "ima/$id/principal.$format";
                if (file_exists($filePath)) {
                    $imgformat = $format;
                    break;
                }
            }
        }
    } else {
        echo 'Error al procesar la petición';
        exit;
    }
}

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';

$filtro = '';

$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio_con_descuento DESC',
    'precio_bajo' => 'precio_con_descuento ASC',
    'descuento' => 'descuento DESC',
];

$order = $orders[$orden] ?? '';

$params = [];

$query = "SELECT id, nombre, precio, descuento, descripcion, stock, id_categoria, precio - ((precio * descuento) / 100) AS precio_con_descuento FROM productos WHERE activo=1";

if ($buscar != '') {
    $query .= " AND nombre LIKE ?";
    $params[] = "%$buscar%";
    //$filtro = "AND nombre LIKE '%$buscar%'";
}

if ($idCategoria != '') {
    $query .= " AND id_categoria = ?";
    $params[] = $idCategoria;
}

if (!empty($order)) {
    $order = " ORDER BY $order";
    $query .= $order;
}

$query = $conn->prepare($query);
$query->execute($params);

/*if(!empty($idCategoria)){
 $sql = $conn->prepare("SELECT id, nombre, precio, descuento, Edicion, stock FROM videojuegos WHERE activo=1 $filtro AND id_categoria = ? $order");
 $sql->execute([$idCategoria]);
}else {
$sql = $conn->prepare("SELECT id, nombre, precio, descuento, Edicion, stock FROM videojuegos WHERE activo=1 $filtro $order");
$sql->execute();   
}*/
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $conn->prepare("SELECT id, nombre FROM categorias WHERE activo=1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);

$sqll = $conn->prepare("SELECT categorias.nombre FROM productos, categorias WHERE productos.id = ? AND productos.activo=1 AND productos.id_categoria = categorias.id");
$sqll->execute([$id]);
$raw = $sqll->fetch(PDO::FETCH_ASSOC);
$cat = $raw['nombre'];

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pasteleria Esperanza</title>
    <meta name="description" content="Resto">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/select2/select2.min.css">
    <link rel="stylesheet" href="vendor/owlcarousel/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/brands.css">


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700|Josefin+Sans:300,400,700">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


    <link rel="stylesheet" href="css/style.min.css">
    <link rel="stylesheet" href="css/style.css">

    <script src="https://kit.fontawesome.com/ca4f209874.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>

</head>
<script src="https://kit.fontawesome.com/81581fb069.js"
    crossorigin="anonymous"></script>
<script type="text/javascript">
    (function() {
        var ldk = document.createElement('script');
        ldk.type = 'text/javascript';
        ldk.async = true;
        ldk.src = 'https://s.cliengo.com/weboptimizer/65eeadf1c7ad2d00325cbd97/65eeadf5c7ad2d00325cbd9a.js?platform=view_installation_code';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ldk, s);
    })();
</script>

<body data-spy="scroll" data-target="#navbar" class="static-layout">
    <div class="boxed-page">
        <?php include 'header.php'; ?>
        <main style="padding: 50px;">
            <section>
                <center>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6 order-md-1">
                                <div id="carouselimg" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active" data-bs-interval="10000">
                                            <img class="d-block w-50" src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 order-md-2">
                                <h1 class="display-3"><?php echo $nombre; ?></h1>
                                <p class="lead"><?php echo $edicion . "<br>"; ?></p><br>
                                <?php if ($descuento > 0) { ?>
                                    <p><del><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></del></p>
                                    <h3><?php echo MONEDA . number_format($precio_desc, 2, '.', ',') . "MXN<br>"; ?>
                                        <small class="text-success"><?php echo $descuento; ?>% descuento</small>
                                    </h3>
                                    <br>
                                <?php } else { ?>
                                    <h3><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></h3><br> <?php } ?>
                                <div>
                                    <p> <?php echo $cat . "<br>"; ?></p>
                                </div>
                                <?php if ($stock == 0) { ?>
                                    <p class="text-danger mb-1 lead"><b>Agotado</b></p>
                                    <br>
                                <?php } else { ?>
                                    <div class="col-3 my-3">
                                        <label for="cantidad">Cantidad:</label>
                                        <input class="form-control-md" id="cantidad" name="cantidad" type="number" min="1" max="10" value="1">
                                    </div>
                                    <div class="col-md-6 order-md-2 mb-3">
                                        <button class="btn btn-primary btn-lg" type="button" onclick="comprarAhora(<?php echo $id ?>, cantidad.value , '<?php echo $token_tmp; ?>')">Comprar ahora</button>
                                    </div>
                                    <div class="col-md-6 order-md-2 mb-3">
                                        <button class="btn btn-primary btn-lg" type="button" onclick="addProducto(<?php echo $id ?>, cantidad.value , '<?php echo $token_tmp; ?>')">Agregar al carrito</button>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </center>
            </section>
        </main>
        <?php include 'footer.php'; ?>
        <script>
            function addProducto(id, cantidad, token) {
                var url = 'clases/carrito.php';
                var formData = new FormData();
                formData.append('id', id);
                formData.append('cantidad', cantidad);
                formData.append('token', token);

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors',
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            let elemento = document.getElementById("num_cart")
                            elemento.innerHTML = data.numero;
                        } else {
                            alert("No se cuenta con la cantidad que usted desea del producto")
                        }
                    })
            }

            function comprarAhora(id, cantidad, token) {
                var url = 'clases/carrito.php';
                var formData = new FormData();
                formData.append('id', id);
                formData.append('cantidad', cantidad);
                formData.append('token', token)

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors',
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            let elemento = document.getElementById("num_cart")
                            elemento.innerHTML = data.numero;
                            location.href = 'checkout.php';
                        }
                    })
            }
        </script>
    </div>
</body>

</html>