<?php
session_start(); // Necesario para usar $_SESSION

require 'config/config.php';
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

// Obtener id y token por GET
$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

// Validar que id y token no estén vacíos
if ($id == '' || $token == '') {
    echo 'Error al procesar la petición';
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

if ($token != $token_tmp) {
    echo 'Error al procesar la petición';
    exit;
}

// Verificar que el producto existe y está activo
$sql = $con->prepare("SELECT count(id) FROM productos WHERE id = ? AND activo = 1");
$sql->execute([$id]);

if ($sql->fetchColumn() > 0) {
    $sql = $con->prepare("SELECT nombre, descripcion, precio FROM productos WHERE id = ? AND activo = 1 LIMIT 1");
    $sql->execute([$id]);
    $row = $sql->fetch(PDO::FETCH_ASSOC);
    $nombre = $row['nombre'];
    $descripcion = $row['descripcion'];
    $precio = $row['precio'];
} else {
    echo 'Producto no encontrado';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoParts - Producto</title>
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

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
              <a href="index.php" class="nav-link active">Catálogo</a>
          </li>
        </ul>
      </div>

      <a href="checkout.php" class="btn btn-primary">
        Carrito <span id="num_cart" class="badge bg-secondary">
          <?php echo (isset($_SESSION['carrito']['productos'])) ? count($_SESSION['carrito']['productos']) : 0; ?>
        </span>
      </a>
    </div>
  </div>
</header>

<main>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-6">
        <img src="images/productos/<?php echo $id; ?>/principal.jpg" class="img-fluid" alt="Imagen del producto">
      </div>

      <div class="col-md-6">
        <h2><?php echo htmlspecialchars($nombre); ?></h2>
        <h3><?php echo MONEDA . number_format($precio, 0, ',', '.'); ?></h3>
        <p class="lead"><?php echo nl2br(htmlspecialchars($descripcion)); ?></p>

        <div class="d-grid gap-3 col-10 mx-auto">
          <button class="btn btn-primary" type="button">Comprar Ahora</button>
          <button class="btn btn-primary" type="button" onclick="addProducto(<?php echo $id; ?>, '<?php echo $token_tmp; ?>')">
            Agregar al carrito
          </button>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
function addProducto(id, token) {
  let url = 'clases/carrito.php';
  let formData = new FormData();
  formData.append('id', id);
  formData.append('token', token);

  fetch(url, {
    method: 'POST',
    body: formData,
    mode: 'cors'
  })
  .then(response => response.json())
  .then(data => {
    console.log(data); // DEBUG: mostrar respuesta
    if (data.ok) {
      let elemento = document.getElementById("num_cart");
      elemento.innerHTML = data.numero;
    } else {
      alert("Error al agregar al carrito");
    }
  });
}
</script>

</body>
</html>
