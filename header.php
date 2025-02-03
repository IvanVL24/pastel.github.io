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

<header style="position: sticky; top: 0; left: 0; right:0; z-index: 100; background-color: white;">
    <nav id="navbar-header" class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand navbar-brand-center d-flex align-items-center p-0 only-mobile" href="index.php">
                <img src="img/la-esperanza-logo-1640101265089-1679603282445.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="lnr lnr-menu"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav d-flex justify-content-between w-100">
                    <!-- Sección Izquierda -->
                    <div class="d-flex flex-lg-row flex-column">
                        <a class="navbar-brand d-flex align-items-center only-desktop" href="index.php">
                            <img src="img/la-esperanza-logo-1640101265089-1679603282445.png" alt="" style="max-height: 50px;">
                        </a>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about.php">Conocemos</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Pasteleria Esperanza
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="ubi.php">Ubicaciones</a>
                            </div>
                        </li>
                    </div>

                    <!-- Sección Central -->
                    <div class="d-flex flex-lg-row flex-column">
                        <?php foreach ($categorias as $categoria) { 
                            $idcate = $categoria['id'];
                            $nombrecat = $categoria['nombre'];?>
                            <li class="nav-item">
                                <a class="nav-link" href="productos.php?idcat=<?php echo $idcate; ?>&token=<?php echo hash_hmac('sha256', $idcate, KEY_TOKEN); ?>"><?php echo $nombrecat; ?></a>
                            </li>
                        <?php } ?>
                    </div>

                    <!-- Sección Derecha -->
                    <div class="d-flex flex-lg-row flex-column gap-3"> <!-- gap-3 añade un espaciado uniforme -->
                        <li class="nav-item">
                            <form action="buscador.php" method="get">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Buscar..." name="q" id="q" aria-describedby="icon-buscar">
                                    <button class="btn btn-dark" type="submit" id="icon-buscar">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </li>
                    </div>
                </ul>
            </div>
            <div class="d-flex flex-lg-row flex-column gap-3 ">
                <a href="checkout.php">
                    <div class="input-group">
                        <i class="fa-solid fa-cart-shopping" style="font-size: 25px; position: relative;">
                            <span id="num_cart" class="badge bg-dark" style="font-size: 11px; position: absolute; top: -10px; right: -10px; color: white;">
                                <?php echo $num_cart;
                                ?>
                            </span>
                        </i>
                    </div>
                </a>
            </div>
        </div>
    </nav>
</header>