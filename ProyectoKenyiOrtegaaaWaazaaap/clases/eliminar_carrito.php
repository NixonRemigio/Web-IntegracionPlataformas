<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        if (isset($_SESSION['carrito']['productos'][$id])) {
            unset($_SESSION['carrito']['productos'][$id]);

            // Opcional: si el carrito queda vacío, puedes borrar el índice completo
            if (empty($_SESSION['carrito']['productos'])) {
                unset($_SESSION['carrito']['productos']);
            }

            echo json_encode(['ok' => true]);
            exit;
        }
    }
}

// Si algo falla
echo json_encode(['ok' => false]);
