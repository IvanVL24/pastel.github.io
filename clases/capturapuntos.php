<?php

require_once '../config/config.php';
require_once '../config/conexion.php';
$db = new Database();
$conn = $db->conectar();

$total = isset($_SESSION['carrito']['total']) ? $_SESSION['carrito']['total'] : 0;
$id_transaccion = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$email = $_POST['email'];
$nombre = $_POST['nombre'];
$metodo = $_POST['tipopago'];
$tarjeta = $_POST['notarjeta'];
$expira = $_POST['expira'];
$cvv = $_POST['cvv'];
//$payment_type = $_GET ['payment_type'];
//$order_id = $_GET ['merchant_order_id'];

if ($id_transaccion != '' && $status != '' && $tarjeta != '' && $expira != '' && $cvv != '') {
    $fecha = date("Y-m-d H:i:s");
    $comando = $conn->prepare("INSERT INTO compra (id_transaccion, fecha, status, email, nombre, total, medio_pago)
        VALUES (?,?,?,?,?,?,?)");
    $comando->execute([$id_transaccion, $fecha, $status, $email, $nombre, $total, $metodo]);
    $id = $conn->lastInsertId();

    if ($id > 0) {
        $productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

        if ($productos != null) {
            foreach ($productos as $clave => $cantidad) {
                $sql = $conn->prepare("SELECT id, nombre, precio, descuento, id_categoria FROM productos WHERE id=? AND activo=1");
                $sql->execute([$clave]);
                $row_vg = $sql->fetch(PDO::FETCH_ASSOC);

                $precio = $row_vg['precio'];
                $descuento = $row_vg['descuento'];
                $categoria = $row_vg['id_categoria'];
                $precio_desc = $precio - (($precio * $descuento) / 100);
                $subtotal = $precio_desc * $cantidad;


                $sql_insert = $conn->prepare("INSERT INTO detalle_compra (id_compra, id, nombre, id_categoria, precio, cantidad, subtotal) VALUES (?,?,?,?,?,?,?)");
                if ($sql_insert->execute([$id, $clave, $row_vg['nombre'], $categoria, $precio_desc, $cantidad, $subtotal])) {
                    restarStock($clave, $cantidad, $conn);
                }
            }
            unset($_SESSION['carrito']);
            header("Location: " . SITE_URL . "/completado.php?key=$id_transaccion&status=$status");
        }
    }
} else {
    header("Location: ../fallo.php");
}
function restarStock($id, $cantidad, $conn)
{
    $sql = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");
    $sql->execute([$cantidad, $id]);
}
