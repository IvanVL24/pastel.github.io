<?php

require_once 'config/config.php';
require_once 'config/conexion.php';
$db = new Database();
$conn = $db->conectar();

$id_transaccion = isset($_GET['key']) ? $_GET['key'] : '0';
$status = isset($_GET['status']) ? $_GET['status'] : '0';

$error = '';
if ($id_transaccion == '' and $status == '') {
    $error = 'Error al procesar la petición';
} else {
    $sql = $conn->prepare("SELECT count(id_compra) FROM compra WHERE id_transaccion=? AND status=?");
    $sql->execute([$id_transaccion, $status]);
    if ($sql->fetchColumn() > 0) {

        $sql = $conn->prepare("SELECT id_compra, fecha, email, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
        $sql->execute([$id_transaccion, $status]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idCompra = $row['id_compra'];
        $total = $row['total'];
        $fecha_conhora = new DateTime($row['fecha']);
        $fecha = $fecha_conhora->format('d/m/Y H:i:s');

        $sqlDet = $conn->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
        $sqlDet->execute([$idCompra]);
    } else {
        $error = 'Error al comprobar la compra';
    }
}
unset($_SESSION['carrito']);

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
        <main style="padding: 50px;">
            <div class="container" style="padding: 0 150px;">
                <?php if (strlen($error) > 0) { ?>
                    <div class="row">
                        <div class="col">
                            <h3><?php echo $error; ?></h3>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col">
                            <b>Folio de la compra: </b><?php echo $id_transaccion; ?><br><br>
                            <b>Fecha de la compra: </b><?php echo $fecha; ?><br><br>
                            <b>Total: </b><?php echo MONEDA . number_format($total, 2, '.', ','); ?><br><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="color: black;">Cantidad</th>
                                        <th style="color: black;">Producto</th>
                                        <th style="color: black;">Precio</th>
                                        <th style="color: black;">Importe</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
                                        $importe = $row_det['precio'] * $row_det['cantidad']; ?>

                                        <tr>
                                            <td style="color: black;"><?php echo $row_det['cantidad']; ?></td>
                                            <td style="color: black;"><?php echo $row_det['nombre']; ?></td>
                                            <td style="color: black;"><?php echo MONEDA . number_format($row_det['precio'], 2, '.', ','); ?></td>
                                            <td style="color: black;"><?php echo MONEDA . number_format($importe, 2, '.', ','); ?></td>
                                        </tr>
                                    <?php  } ?>
                                    <tr>
                                        <td style="color: black;" colspan="3" class="text-end"><b>Total: </b></td>
                                        <td style="color: black;"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <a href="ticket.php?key=<?php echo $id_transaccion; ?>&status=<?php echo $status; ?>" class="btn btn-primary">Descargar PDF</a>
                        </div>
                    </div>
                <?php   } ?>
            </div>
        </main>
        <?php include 'footer.php'; ?>
</body>

</html>