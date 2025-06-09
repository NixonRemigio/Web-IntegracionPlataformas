<?php
require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AutoParts - Tienda de Autopartes</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <!-- Tu CSS personalizado -->
  <link rel="stylesheet" href="css/estilos.css" />
</head>
<body>

<header class="bg-dark py-3">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="#" class="navbar-brand text-warning fs-3 fw-bold">AutoParts</a>

    <div class="d-flex align-items-center gap-3">

      <!-- Selector tipo de usuario -->
      <label for="user-type" class="text-white mb-0">Tipo de usuario:</label>
      <select id="user-type" class="form-select form-select-sm" style="width: 180px;">
        <option value="b2c">Cliente Final</option>
        <option value="b2b">Distribuidor / Taller</option>
      </select>

      <!-- Icono carrito -->
      <div class="position-relative" style="cursor:pointer;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
          stroke-width="1.5" stroke="currentColor" class="icon-cart" width="32" height="32" style="color: yellow;">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 
               14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 
               2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 
               14.25L5.106 5.272M6 20.25a.75.75 0 1 1-1.5 
               0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 
               1-1.5 0 .75.75 0 0 1 1.5 0Z" />
        </svg>

        <div id="contador-productos" 
          class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-warning text-dark">
          0
        </div>

        <!-- Contenedor del carrito desplegable -->
        <div id="container-cart-products" class="container-cart-products bg-white shadow rounded p-3 d-none"
             style="width: 320px; position: absolute; right: 0; top: 40px; z-index: 999;">
          <div class="cart-products-list">
            <!-- Aquí se insertan productos del carrito vía JS -->
          </div>
          <div class="cart-empty-message text-center text-muted mt-3">
            El carrito está vacío.
          </div>
          <div class="cart-total d-flex justify-content-between align-items-center mt-3">
            <h5>Total:</h5>
            <span class="total-pagar fs-5 fw-bold">$0</span>
          </div>
          <button class="btn btn-warning w-100 mt-3 btn-pagar" disabled>Ir a pagar</button>
        </div>
      </div>

    </div>
  </div>
</header>

<main class="container my-4">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach($resultado as $row): ?>
      <?php
        $id = $row['id'];
        $imagen = "images/productos/" . $id . "/principal.jpg";
        if (!file_exists($imagen)) {
          $imagen = "images/no-photo.jpeg";
        }
      ?>
      <div class="col">
        <div class="card shadow-sm rounded-3 border-0">
          <img src="<?php echo $imagen ?>" class="card-img-top" alt="Producto">
          <div class="card-body">
            <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($row['nombre']); ?></h5>
            <p class="card-text fs-5 text-secondary">$<?php echo number_format($row['precio'], 0, ',', '.'); ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <a href="#" class="btn btn-primary btn-sm">Detalles</a>
              <button class="btn btn-success btn-sm btn-agregar" data-id="<?php echo $id ?>" data-nombre="<?php echo htmlspecialchars($row['nombre']); ?>" data-precio="<?php echo $row['precio'] ?>">Agregar</button>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<!-- Bootstrap JS Bundle (Popper incluido) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
 integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
 crossorigin="anonymous"></script>

<!-- Aquí podrías poner tu JS para carrito y funcionalidad -->
<script src="js/script.js"></script>

</body>
</html>
