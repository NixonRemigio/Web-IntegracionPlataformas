<?php
session_start();
require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id = ? AND activo = 1");
        $sql->execute([$clave]);
        $producto = $sql->fetch(PDO::FETCH_ASSOC);
        if ($producto) {
            $producto['cantidad'] = $cantidad;
            $lista_carrito[] = $producto;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tienda</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"><strong>AutoParts</strong></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader"></div>
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a href="#" class="nav-link active">Catálogo</a>
        </li>
      </ul>
      <a href="carrito.php" class="btn btn-primary">Carrito</a>
    </div>
  </div>
</header>

<main>
  <div class="container">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php if ($lista_carrito == null) { ?>
          <tr><td colspan="5" class="text-center"><b>Lista vacía</b></td></tr>
        <?php 
        } else {
          $total = 0;
          foreach ($lista_carrito as $producto) {
            $_id = $producto['id'];
            $nombre = $producto['nombre'];
            $precio = $producto['precio'];
            $cantidad = $producto['cantidad'];
            $subtotal = $precio * $cantidad;
            $total += $subtotal;
        ?>
          <tr>
            <td><?php echo htmlspecialchars($nombre); ?></td>
            <td><?php echo MONEDA . number_format($precio, 0, ',', '.'); ?></td>
            <td><input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange=""></td>
            <td>
              <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . number_format($subtotal, 0, ',', '.'); ?></div>
            </td>
            <td>
              <a href="#" class="btn btn-warning btn-sm" data-id="<?php echo $_id; ?>">Eliminar</a>
            </td>
          </tr>
        <?php 
          } 
        ?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-end"><strong>Total:</strong></td>
            <td colspan="2"><strong><?php echo MONEDA . number_format($total, 0, ',', '.'); ?></strong></td>
          </tr>
        </tfoot>
        <?php } ?>
      </table>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
