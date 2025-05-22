const carrito = [];
const botonesAgregar = document.querySelectorAll('.info-producto button');
const carritoContainer = document.querySelector('.container-cart-product');
const contadorCarrito = document.getElementById('contador-productos');
const totalPagar = document.querySelector('.total-pagar');
const iconoCarrito = document.querySelector('.container-icon');

// Mostrar/ocultar carrito
iconoCarrito.addEventListener('click', () => {
    carritoContainer.classList.toggle('hidden-cart');
});

function actualizarCarrito() {
    // Eliminar productos actuales del DOM
    const productosActuales = carritoContainer.querySelectorAll('.cart-product');
    productosActuales.forEach(producto => producto.remove());

    let total = 0;
    let cantidadTotal = 0;

    carrito.forEach(producto => {
        const div = document.createElement('div');
        div.classList.add('cart-product');
        div.innerHTML = `
            <div class="info-cart-product">
                <span class="cantidad-producto-carrito">${producto.cantidad}</span>
                <p class="titulo-producto-carrito">${producto.nombre}</p>
                <span class="precio-producto-carrito">$${(producto.precio * producto.cantidad).toLocaleString()}</span>
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

botonesAgregar.forEach((boton, index) => {
    boton.addEventListener('click', () => {
        const item = boton.closest('.items');
        const nombre = item.querySelector('h2').textContent.trim();
        const precioTexto = item.querySelector('.price').textContent.replace(/\$|\./g, '');
        const precio = parseInt(precioTexto);
        const id = `${nombre}-${index}`;

        const productoExistente = carrito.find(p => p.id === id);

        if (productoExistente) {
            productoExistente.cantidad++;
        } else {
            carrito.push({
                id,
                nombre,
                precio,
                cantidad: 1
            });
        }

        actualizarCarrito();
    });
});
