from django.db import models

class Producto(models.Model):  # <- aquí sí va el ":"
    nombre = models.CharField(max_length=100)  # <- aquí no se necesita punto y coma
    descripcion = models.TextField()
    precio = models.DecimalField(max_digits=10, decimal_places=2)
    imagen = models.ImageField(upload_to='productos/', null=True, blank=True)

    def __str__(self):  # <- sí va el ":"
        return self.nombre  # <- sin punto y coma
