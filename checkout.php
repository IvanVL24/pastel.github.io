<?php

require_once 'config/config.php';
require_once 'config/conexion.php';
$db = new Database();
$conn = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $conn->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}

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
        <main class="productos container">
            <div style="padding: 50px 0 0 0;" class="container">
                <div style="background-color: gray;" class="table-responsive">
                    <table class="table">
                        <thead style="font-size: 15px;">
                            <tr>
                                <th>Imagen</th>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 17px;">
                            <?php if ($lista_carrito == null) {
                                echo '<tr>
                                            <td colspan="5" class="text-center"><b>Lista vacía</b></td>;
                                        </tr>';
                            } else {
                                $total = 0;
                                foreach ($lista_carrito as $productos) {
                                    $_id = $productos['id'];
                                    $nombre = $productos['nombre'];
                                    $precio = $productos['precio'];
                                    $descuento = $productos['descuento'];
                                    $precio_desc = $precio - (($precio * $descuento) / 100);
                                    $cantidad = $productos['cantidad'];
                                    $subtotal = $precio_desc * $cantidad;
                                    $total += $subtotal;
                                    $formats = ['jpeg', 'jpg', 'png', 'jfif', 'webp'];
                                    $imgformat = '';
                                    foreach ($formats as $format) {
                                        $filePath = "ima/$_id/principal.$format";
                                        if (file_exists($filePath)) {
                                            $imgformat = $format;
                                            break;
                                        }
                                    }
                            ?>
                                    <tr>
                                        <td style="vertical-align:middle;"><img style="widht: 125px; height: 125px;" src="ima/<?php echo $_id; ?>/principal.<?php echo $imgformat; ?>"></td>
                                        <td style="vertical-align:middle;"><?php echo $nombre ?></td>
                                        <td style="vertical-align:middle;"><?php echo MONEDA . number_format($precio_desc, 2, '.', ','); ?></td>
                                        <td style="vertical-align:middle;">
                                            <input style="width: 80px; border-radius: 5px;" type="number" min="1" step="1" value="<?php echo $cantidad; ?>"
                                                size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]">
                                                <?php echo MONEDA . number_format($subtotal, 2, '.', ','); ?>
                                            </div>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            <a id="eliminar" class="btn btn-danger btn-lg" data-bs-id="<?php echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="4">
                                        <p class="h4" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>
                        </tbody>
                    <?php } ?>
                    </table>
                </div>
                <?php if ($lista_carrito != null) { ?>
                    <div class="row">
                        <div class="col-4 col-md-2 offset-md-10 d-grid gap-2 mt-3">
                            <a href="pago.php" class="btn btn-sm btn-primary">Realizar pago</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </main>
        <!-- Modal -->
        <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="eliminaModalLabel">Alerta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Desea eliminar el producto de la lista?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>

        <script>
            let eliminaModal = document.getElementById('eliminaModal')
            eliminaModal.addEventListener('show.bs.modal', function(event) {
                let button = event.relatedTarget
                let id = button.getAttribute('data-bs-id')
                let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
                buttonElimina.value = id
            })

            function actualizaCantidad(cantidad, id) {
                let url = 'clases/actualizar_carrito.php'
                let formData = new FormData()
                formData.append('action', 'agregar')
                formData.append('id', id)
                formData.append('cantidad', cantidad)

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {

                            let divsubtotal = document.getElementById('subtotal_' + id)
                            divsubtotal.innerHTML = data.sub

                            let total = 0.00
                            let list = document.getElementsByName('subtotal[]')

                            for (let i = 0; i < list.length; i++) {
                                total += parseFloat(list[i].innerHTML.replace(/[<?php echo MONEDA ?>,]/g, ''))
                            }

                            total = new Intl.NumberFormat('en-US', {
                                minimumFractionDigits: 2
                            }).format(total)
                            document.getElementById('total').innerHTML = '<?php echo MONEDA ?>' + total
                        } else {
                            let inputCantidad = document.getElementById('cantidad_' + id)
                            inputCantidad.value = data.cantidadAnterior
                            alert("No se cuenta con la cantidad que usted desea del producto")
                        }

                    })
            }

            function eliminar() {

                let botonElimina = document.getElementById('btn-elimina')
                let id = botonElimina.value

                let url = 'clases/actualizar_carrito.php'
                let formData = new FormData()
                formData.append('action', 'eliminar')
                formData.append('id', id)

                fetch(url, {
                        method: 'POST',
                        body: formData,
                        mode: 'cors'
                    }).then(response => response.json())
                    .then(data => {
                        if (data.ok) {
                            location.reload()
                        }
                    })
            }
        </script>
</body>

</html>