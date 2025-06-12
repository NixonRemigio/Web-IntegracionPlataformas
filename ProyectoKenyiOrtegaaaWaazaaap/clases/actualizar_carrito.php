<?php
require '../config/config.php';

if (isset($_POST['id']) && isset($_POST['cantidad'])) {
    $id = $_POST['id'];
    $cantidad = (int) $_POST['cantidad'];

    if ($cantidad > 0 && isset($_SESSION['carrito']['productos'][$id])) {
        $_SESSION['carrito']['productos'][$id] = $cantidad;
        $datos['ok'] = true;
    } else {
        $datos['ok'] = false;
    }
} else {
    $datos['ok'] = false;
}

echo json_encode($datos);
