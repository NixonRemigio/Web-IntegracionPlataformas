<?php
require 'config/config.php';
require 'config/database.php';

$db = new Database();
$con = $db->conectar();

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $cantidad) {
        $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id = ? AND activo=1");
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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<header>
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"><strong>AutoParts</strong></a>
      <a href="carrito.php" class="btn btn-primary">Carrito</a>
    </div>
  </div>
</header>

<main class="container mt-4">
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
            <?php if (empty($lista_carrito)) { ?>
                <tr><td colspan="5" class="text-center"><b>Lista Vacía</b></td></tr>
            <?php } else {
                $total = 0;
                foreach ($lista_carrito as $producto) {
                    $_id = $producto['id'];
                    $nombre = $producto['nombre'];
                    $precio = $producto['precio'];
                    $cantidad = $producto['cantidad'];
                    $subtotal = $cantidad * $precio;
                    $total += $subtotal;
            ?>
                <tr>
                    <td><?php echo $nombre; ?></td>
                    <td><?php echo MONEDA . number_format($precio, 0, ',', '.'); ?></td>
                    <td>
                        <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" 
                            size="5" id="cantidad_<?php echo $_id; ?>" 
                            onchange="actualizaCantidad(<?php echo $_id; ?>)">
                    </td>
                    <td>
                        <div id="subtotal_<?php echo $_id; ?>">
                            <?php echo MONEDA . number_format($subtotal, 0, ',', '.'); ?>
                        </div>
                    </td>
                    <td>
                        <a href="#" class="btn btn-warning btn-sm" onclick="eliminarProducto(<?php echo $_id; ?>)">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td colspan="2"><strong><?php echo MONEDA . number_format($total, 0, ',', '.'); ?></strong></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="row"> <div class="col-md-5 offset-md-7 d-grid-gap-2"> <button class="btn btn-primary btn-lg">Realizar Pago</button> </div> </div>
</main>

<script>
function actualizaCantidad(id) {
    const cantidad = document.getElementById('cantidad_' + id).value;
    const url = 'clases/actualizar_carrito.php';

    const formData = new FormData();
    formData.append('id', id);
    formData.append('cantidad', cantidad);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            location.reload();
        }
    });
}

function eliminarProducto(id) {
    const url = 'clases/eliminar_carrito.php';

    const formData = new FormData();
    formData.append('id', id);

    fetch(url, {
        method: 'POST',
        body: formData,
        mode: 'cors'
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            location.reload();
        }
    });
}
</script>

</body>
</html>
