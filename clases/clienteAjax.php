<?php

require_once '../config/conexion.php';
require_once 'clientefunciones.php';

    $datos = [];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    $db = new Database();
    $conn = $db->conectar();

    if ($action == 'existeUsuario'){
        $datos['ok'] = usuarioExiste($_POST['usuario'], $conn);
    }elseif ($action = 'existeEmail') {
        $datos['ok'] = emailExiste($_POST['email'], $conn);
    }
}

echo json_encode($datos);