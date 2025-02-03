<?php
require_once "config/conexion.php";
require_once "config/config.php";

$db = new Database();
$conn = $db->conectar();

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = str_replace('+', '', urldecode(trim($_GET['q'] ?? '')));


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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
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

<body data-spy="scroll" data-target="#navbar" class="static-layout">
    <div class="boxed-page">
        <?php
        include "header.php";
        ?>
        <section id="gtco-menu" class="section-padding">
            <div class="container">
                <div class="section-content">
                    <div class="row row-cols-1 row-cols-sm-4 row-cols-md1 justify-content-end g-1 mb-4">
                        <div class="col">
                            <form action="buscador.php" id="ordenForm" method="get" onchange="submitForm()">
                                <select name="orden" id="orden" class="form-select form-select-sm">
                                    <option value="">Ordenar por...</option>
                                    <option value="descuento" <?php echo ($orden === 'descuento') ? 'selected' : ''; ?>>Descuentos</option>
                                    <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>Precios más altos</option>
                                    <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected' : ''; ?>>Precios más bajos</option>
                                    <option value="asc" <?php echo ($orden === 'asc') ? 'selected' : ''; ?>>Nombre A-Z</option>
                                    <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                                </select>
                                <input type="hidden" id="q" name="q" value="<?php echo $buscar; ?>">
                            </form>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-lg-12 mx-auto">
                                <div class="row">
                                    <?php
                                    $count = 0; // Inicializamos el contador
                                    foreach ($resultado as $row) {
                                    ?>
                                        <div class="col-md-6 mb-4">
                                            <div class="menus d-flex align-items-start">
                                                <?php
                                                $id = $row['id'];
                                                $nombre = $row['nombre'];
                                                $edicion = $row['descripcion'];
                                                $precio = $row['precio'];
                                                $stock = $row['stock'];
                                                $descuento = $row['descuento'];
                                                $precio_desc = $row['precio_con_descuento'];
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
                                                <div class="menu-img rounded-circle me-3">
                                                    <a href="details.php?id=<?php echo $id; ?>&token=<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>">
                                                        <img class="imagen" src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>">
                                                    </a>
                                                </div>
                                                <div class="container">
                                                    <div class="text-wrap">
                                                        <div class="row mb-2">
                                                            <div class="col-8">
                                                                <h4><?php echo $nombre; ?></h4>
                                                                <p><?php echo $edicion; ?></p>
                                                            </div>
                                                            <?php if ($descuento > 0) { ?>
                                                                <div class="col-4 text-end">
                                                                    <h4 class="text-muted menu-price">
                                                                        <del><?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?></del>
                                                                        <?php echo MONEDA . number_format($precio_desc, 2, '.', ',') . "MXN<br>"; ?>
                                                                    </h4>
                                                                    <small class="text-dark"><?php echo $descuento; ?>% descuento</small>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="col-4 text-end">
                                                                    <h4 class="text-muted menu-price">
                                                                        <?php echo MONEDA . number_format($precio, 2, '.', ',') . "MXN<br>"; ?>
                                                                    </h4>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($stock == 0) { ?>
                                                                <div class="col-12 text-center">
                                                                    <button class="btn btn-small btn-dark" disabled type="button">
                                                                        Agotado
                                                                    </button>
                                                                </div>
                                                            <?php } else { ?>
                                                                <div class="col-12 mt-3 text-center">
                                                                    <button class="btn btn-small btn-dark" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo hash_hmac('sha1', $id, KEY_TOKEN); ?>')">
                                                                        Agregar
                                                                    </button>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        include "footer.php";
        ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="vendor/bootstrap/popper.min.js"></script>
    <script src="vendor/bootstrap/bootstrap.min.js"></script>
    <script src="vendor/select2/select2.min.js "></script>
    <script src="vendor/owlcarousel/owl.carousel.min.js"></script>
    <script src="https://cdn.rawgit.com/noelboss/featherlight/1.7.13/release/featherlight.min.js"></script>
    <script src="vendor/stellar/jquery.stellar.js" type="text/javascript" charset="utf-8"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="js/app.min.js "></script>
    <script>
        function addProducto(id, token) {
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('token', token)

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero
                    } else {
                        alert("No se cuenta con la cantidad que usted desea del producto")
                    }

                })
        }

        function submitForm() {
            // Agregar los parámetros GET a la acción del formulario
            let q = "<?php echo $buscar; ?>";
            let orden = "<?php echo $orden; ?>";
            let form = document.getElementById('ordenForm');
            form.action = "buscador.php?q=" + q + "&orden=" + orden;
            // Enviar el formulario
            form.submit();
        }
    </script>
</body>

</html>