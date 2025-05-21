<?php
include 'conexion.php';

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

// Manejo de imagen
$imagen_nombre = $_FILES['imagen']['name'];
$imagen_temp = $_FILES['imagen']['tmp_name'];
$ruta = "imagenes/" . basename($imagen_nombre);

// Mover imagen a carpeta
if (move_uploaded_file($imagen_temp, $ruta)) {
  $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen)
          VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$imagen_nombre')";

  if ($conn->query($sql) === TRUE) {
    echo "Producto guardado correctamente. <a href='admin_cargar.html'>Volver</a>";
  } else {
    echo "Error: " . $conn->error;
  }
} else {
  echo "Error al subir imagen.";
}

$conn->close();
?>
