const carrito = [];
const botonesAgregar = document.querySelectorAll('.info-producto button');
const carritoContainer = document.querySelector('.container-cart-products');
const contadorCarrito = document.getElementById('contador-productos');
const totalPagar = document.querySelector('.total-pagar');
const iconoCarrito = document.querySelector('.container-icon');
const userSelect = document.getElementById('userSelect');

// Mostrar/ocultar carrito
iconoCarrito.addEventListener('click', () => {
  carritoContainer.classList.toggle('hidden-cart');
});

botonesAgregar.forEach((boton, index) => {
  boton.addEventListener('click', () => {
    const item = boton.closest('.items');
    const nombre = item.getAttribute('data-nombre');
    const tipoUsuario = userSelect.value;
    const precio = parseInt(item.getAttribute(`data-precio-${tipoUsuario}`));
    const id = `${nombre}-${index}`;

    const productoExistente = carrito.find(p => p.id === id);
    if (productoExistente) {
      productoExistente.cantidad++;
    } else {
      carrito.push({ id, nombre, precio, cantidad: 1 });
    }

    actualizarCarrito();
  });
});

function actualizarCarrito() {
  const productosExistentes = carritoContainer.querySelectorAll('.cart-product');
  productosExistentes.forEach(p => p.remove());

  let total = 0;
  let cantidadTotal = 0;

  carrito.forEach(producto => {
    const div = document.createElement('div');
    div.classList.add('cart-product');
    div.innerHTML = `
      <div class="info-cart-product">
        <span>${producto.cantidad} x ${producto.nombre}</span>
        <span>$${(producto.precio * producto.cantidad).toLocaleString()}</span>
      </div>
      <svg xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        class="icon-close" data-id="${producto.id}">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M6 18 18 6M6 6l12 12" />
      </svg>
    `;
    carritoContainer.insertBefore(div, carritoContainer.querySelector('.cart-total'));

    total += producto.precio * producto.cantidad;
    cantidadTotal += producto.cantidad;
  });

  totalPagar.textContent = `$${total.toLocaleString()}`;
  contadorCarrito.textContent = cantidadTotal;

  activarBotonesEliminar();
}

function activarBotonesEliminar() {
  const botonesEliminar = carritoContainer.querySelectorAll('.icon-close');
  botonesEliminar.forEach(boton => {
    boton.addEventListener('click', () => {
      const id = boton.getAttribute('data-id');
      const index = carrito.findIndex(p => p.id === id);
      if (index !== -1) {
        carrito.splice(index, 1);
        actualizarCarrito();
      }
    });
  });
}

// Simulación de pago
document.getElementById('pagar').addEventListener('click', () => {
  alert('Gracias por su compra. En breve será redirigido a la pasarela de pagos.');
});
