<?php

require 'config/config.php';
require 'config/conexion.php';

$db = new Database();
$conn = $db->conectar();


function generarCaracterAleatorio()
{
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $longitud = strlen($caracteres);
    $caracterAleatorio = $caracteres[rand(0, $longitud - 1)];
    return $caracterAleatorio;
}

function idtransaccion()
{
    $codigo = '';
    for ($i = 0; $i < 15; $i++) {
        $codigo .= generarCaracterAleatorio();  // Genera un carácter aleatorio
    }
    return $codigo;
}

$codigo = idtransaccion();


$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $conn->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
} else {
    header("Location: index.php");
    exit;
}


$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';

$filtro = '';

$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC',
];

$order = $orders[$orden] ?? '';

if (!empty($order)) {
    $order = " ORDER BY $order";
}

$params = [];

$query = "SELECT id, nombre, precio, descuento, descripcion, stock FROM productos WHERE activo=1 $order";


if ($buscar != '') {
    $query .= " AND nombre LIKE ?";
    $params[] = "%$buscar%";
    //$filtro = "AND nombre LIKE '%$buscar%'";
}

if ($idCategoria != '') {
    $query .= " AND id_categoria = ?";
    $params[] = $idCategoria;
}

$query = $conn->prepare($query);
$query->execute($params);

$resultado = $query->fetchAll(PDO::FETCH_ASSOC);

$sqlCategorias = $conn->prepare("SELECT id, nombre FROM categorias WHERE activo=1 ORDER BY nombre ASC");
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
        <main class="productos container mt-4">
            <div class="row">
                <!-- Columna para el formulario -->
                <div class="col-lg-6 col-md-12">
                    <h4>Detalles de pago</h4>
                    <form id="formularioCompra" action="clases/capturapuntos.php" method="post">
                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="firstName" class="form-label">Nombre(s)</label>
                                <input type="text" name="nombres" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="lastName" class="form-label">Apellido(s)</label>
                                <input type="text" name="apellidos" class="form-control" id="lastName" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" name="usuario" class="form-control" id="username" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="form-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="you@example.com">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" id="address" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="address" class="form-label">Ciudad</label>
                                <input type="text" name="ciudad" class="form-control" id="city" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="state" class="form-label">Estado</label>
                                <input type="text" name="estado" class="form-control" id="state" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="zip" class="form-label">Código Postal</label>
                                <input type="text" name="cp" class="form-control" id="zip" required>
                            </div>

                            <div class="col-sm-6">
                                <h4 class="mb-3">Tipo de pago</h4>
                                <div class="form-check mb-2">
                                    <input id="credit" name="tipopago" value="TC" type="radio" class="form-check-input" checked required>
                                    <label class="form-check-label" for="credit">Tarjeta de Crédito</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input id="debit" name="tipopago" value="TD" type="radio" class="form-check-input" required>
                                    <label class="form-check-label" for="debit">Tarjeta de Débito</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input id="paypal" name="tipopago" value="Paypal" type="radio" class="form-check-input" required>
                                    <label class="form-check-label" for="paypal">Paypal</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="cc-name" class="form-label">Nombre de la tarjeta</label>
                                <input type="text" name="nombre" class="form-control" id="cc-name" placeholder="" required>
                                <small class="text-body-secondary">Nombre completo que aparece en la tarjeta</small>
                                <div class="invalid-feedback">
                                    Name on card is required
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label for="cc-number" class="form-label">Número de la tarjeta</label>
                                <input type="text" name="notarjeta" class="form-control" id="cc-number" placeholder="" required>
                                <div class="invalid-feedback">
                                    Credit card number is required
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 mb-3">
                                <label for="cc-expiration" class="form-label">Expiracion</label>
                                <input type="text" name="expira" class="form-control" id="cc-expiration" placeholder="" required>
                                <div class="invalid-feedback">
                                    Expiration date required
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="cc-cvv" class="form-label">CVV</label>
                                <input type="text" name="cvv" class="form-control" id="cc-cvv" placeholder="" required>
                                <div class="invalid-feedback">
                                    Security code required
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-primary w-50" data-bs-toggle="modal" data-bs-target="#comprarCPuntosModal">
                                Pagar
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Columna para el carrito -->
                <div class="col-lg-6 col-md-12 mt-4 mt-lg-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th class="text-center">Producto</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_carrito == null) { ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Lista vacía</td>
                                    </tr>
                                    <?php } else {
                                    $total = 0;
                                    foreach ($lista_carrito as $videojuegos) {
                                        $id = $videojuegos['id'];
                                        $nombre = $videojuegos['nombre'];
                                        $precio = $videojuegos['precio'];
                                        $cantidad = $videojuegos['cantidad'];
                                        $descuento = $videojuegos['descuento'];
                                        $precio_desc = $precio - (($precio * $descuento) / 100);
                                        $subtotal = $cantidad * $precio_desc;
                                        $total += $subtotal;
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
                                        <tr>
                                            <td><img src="ima/<?php echo $id; ?>/principal.<?php echo $imgformat; ?>" class="img-fluid" alt="Portada" style="width: 100px;"></td>
                                            <td class="text-center align-middle"><?php echo $nombre ?></td>
                                            <td class="text-center align-middle"><?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td><strong>Total: <?php echo MONEDA . number_format($total, 2, '.', ','); ?></strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
        </main>
        <!-- Modal -->
        <div class="modal fade" id="comprarCPuntosModal" tabindex="-1" aria-labelledby="comprarCPuntosModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="comprarCPuntosModalLabel">Confirmación de compra</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Desea realizar el pago?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger btn-lg" data-bs-dismiss="modal">Cancelar</button>
                        <a href="#" class="btn btn-success btn-lg" onclick="enviarFormularioConGet();">Aceptar</a>
                    </div>
                </div>
            </div>
        </div>
        <?php

        $_SESSION['carrito']['total'] = $total;

        ?>
        <?php include 'footer.php'; ?>
    </div>
</body>
<script>
    function enviarFormularioConGet() {
        // Agregar los parámetros GET a la acción del formulario
        let payment_id = "<?php echo $codigo; ?>";
        let status = "COMPLETADO";

        let form = document.getElementById('formularioCompra');
        form.action = "clases/capturapuntos.php?payment_id=" + payment_id + "&status=" + status;

        // Enviar el formulario
        form.submit();
    }
</script>

</html>