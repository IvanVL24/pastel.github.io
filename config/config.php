<?php

date_default_timezone_set('America/Mexico_City');

define("MONEDA", "$");
define("PAIS", "MXN");
define("KEY_TOKEN", "ZAC.qer_050+");
define("SITE_URL", "http://localhost/Esperanza/");

session_name('panaderia_session');
session_start();

$num_cart = 0;
if (isset($_SESSION['carrito']['productos'])) {
    $num_cart = count($_SESSION['carrito']['productos']);
}

?>