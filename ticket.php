<?php
require_once 'config/config.php';
require_once 'config/conexion.php';
require 'admin/fpdf/fpdf.php';

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

        $sql = $conn->prepare("SELECT id_compra, fecha, nombre, total FROM compra WHERE id_transaccion=? AND status=? LIMIT 1");
        $sql->execute([$id_transaccion, $status]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        $idCompra = $row['id_compra'];
        $total = $row['total'];
        $nombre = $row['nombre'];
        $fecha_conhora = new DateTime($row['fecha']);
        $fecha = $fecha_conhora->format('d/m/Y H:i:s');

        $sqlDet = $conn->prepare("SELECT nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
        $sqlDet->execute([$idCompra]);
    } else {
        $error = 'Error al comprobar la compra';
    }
}

class PDF extends FPDF {

    public function __construct($orientacion, $medidas, $tamanio) /** ES LA CONSTRUCCIÓN DE LA CLASE PADRE QUE SE MANDA A LLAMAR DE OTRO PHP **/
    {
        parent::__construct($orientacion, $medidas, $tamanio);
    }
    // Sobrescribir el método Header para definir el encabezado del ticket
    function Header() {
        $this->Image('img/la-esperanza-logo-1640101265089-1679603282445.png', 5, 10, 15);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 10, 'ESPERANZA', 0, 1, 'C');
        $this->Ln(5);

        $this->SetFont('Arial', 'B', 9);
        $this->Cell(0, 10, 'TICKET DE COMPRA', 0, 1, 'C');
        $this->Ln(5);

        // Línea divisoria antes de los detalles de compra
        $this->Line(5, $this->GetY(), 75, $this->GetY()); // Línea horizontal
        $this->Ln(5);

        // Encabezado de la tabla de detalles de compra
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(7, 5, 'Cant', 0, 0, 'L');
        $this->Cell(35, 5, 'Producto', 0, 0, 'L');
        $this->Cell(12, 5, 'Precio', 0, 0, 'L');
        $this->Cell(15, 5, 'Importe', 0, 1, 'L');
        $this->Ln(2);
    }

    // Sobrescribir el método Footer para el pie de página del ticket
    function Footer() {
        $this->SetY(-30); // Posición a 30 mm del fondo

        // Línea divisoria antes del pie de página
        $this->Line(5, $this->GetY(), 75, $this->GetY()); // Línea horizontal
        $this->Ln(5);

        // Mensaje de agradecimiento
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, mb_convert_encoding('¡Gracias por su compra!', 'ISO-8859-1', 'UTF-8'), 0, 0, 'C');
    }
}

// Función para convertir el total en letras
function numtoletras($numero){
    // Esta función convierte un número a letras. Puedes adaptarla o usar una librería ya existente.
    // Aquí te dejo un ejemplo básico:
    $formatter = new NumberFormatter("es", NumberFormatter::SPELLOUT);
    return $formatter->format($numero);
}

// Crear un nuevo PDF con tamaño personalizado (80 mm x 150 mm)
$pdf = new PDF('P', 'mm', array(80, 150)); // 80 mm de ancho, 150 mm de alto
$pdf->AddPage();

// Agregar el contenido del cuerpo (detalles de compra)
$pdf->SetFont('Arial', '', 8);
$total_cantidad = 0;
$total_importe = 0;

while ($row_det = $sqlDet->fetch(PDO::FETCH_ASSOC)) {
    $cantidad = $row_det['cantidad'];
    $producto = $row_det['nombre'];
    $precio = $row_det['precio'];
    $importe = $cantidad * $precio;

    // Ajustar el ancho del nombre del producto
    $producto_limpio = substr($producto, 0, 25); // Limitar a 25 caracteres para evitar que se salga del ancho
    
    // Imprimir cada producto
    $pdf->Cell(7, 5, $cantidad, 0, 0, 'L');
    $pdf->Cell(35, 5, $producto_limpio, 0, 0, 'L'); // Utilizar Cell en vez de MultiCell
    $pdf->Cell(12, 5, MONEDA . ' ' . number_format($precio, 2, '.', ','), 0, 0, 'L');
    $pdf->Cell(15, 5, MONEDA . ' ' . number_format($importe, 2, '.', ','), 0, 1, 'L');

    $total_cantidad += $cantidad;
    $total_importe += $importe;
}


// Línea divisoria antes del total
$pdf->Ln(5);
$pdf->Line(5, $pdf->GetY(), 75, $pdf->GetY());
$pdf->Ln(5);

// Total en números
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(50, 5, 'Total:', 0, 0, 'R');
$pdf->Cell(15, 5, MONEDA.' '.number_format($total_importe, 2, '.', ','), 0, 1, 'R');

// Total en letras
$pdf->SetFont('Arial', '', 8);
$pdf->MultiCell(0, 5, numtoletras($total_importe).' pesos', 0, 'L');

// Folio y fecha
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 5, 'Cantidad total: '.$total_cantidad, 0, 1, 'L');
$pdf->Cell(0, 5, 'Folio: '.$id_transaccion, 0, 1, 'L');
$pdf->Cell(0, 5, 'Fecha y hora: '.$fecha, 0, 1, 'L');

// Forzar la descarga del ticket en formato PDF
$archivo = 'Ticket de '. mb_convert_encoding($nombre, 'ISO-8859-1', 'UTF-8'). mb_convert_encoding(' del día ', 'ISO-8859-1', 'UTF-8').$fecha.'.pdf';
$pdf->Output('D', $archivo);
?>
?>
