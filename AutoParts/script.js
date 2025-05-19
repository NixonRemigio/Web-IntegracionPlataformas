// Productos con precio B2C y B2B
const productos = [
  {
    id: "neumaticos",
    nombre: "Neumáticos",
    precioB2C: 15000,
    precioB2B: 12000,
    stock: 20,
    imagen: "https://reducereutilizarecicla.org/web/app/uploads/2018/08/post-neumaticos.png",
  },
  {
    id: "manilla",
    nombre: "Manilla Exterior",
    precioB2C: 15000,
    precioB2B: 10000,
    stock: 15,
    imagen: "https://images.implementos.cl/img/1000/MARLAT0881-2.jpg",
  },
  {
    id: "tapa-rueda",
    nombre: "Tapa rueda",
    precioB2C: 15000,
    precioB2B: 11000,
    stock: 10,
    imagen: "https://http2.mlstatic.com/D_NQ_NP_656053-MLC76831028488_062024-O-tapa-rueda-aro-14-plateada-universal-calidad.webp",
  },
];

let carrito = [];

const contenedorItems = document.querySelector(".container-items");
const carritoContainer = document.querySelector(".container-cart-products");
const contadorCarrito = document.getElementById("contador-productos");
const totalPagar = document.querySelector(".total-pagar");
const iconoCarrito = document.querySelector(".container-icon");
const selectUserType = document.getElementById("user-type");
const btnPagar = document.querySelector(".btn-pagar");

// Mostrar/ocultar carrito
iconoCarrito.addEventListener("click", () => {
  carritoContainer.classList.toggle("hidden-cart");
});

// Cargar productos según tipo de usuario
function cargarProductos() {
  contenedorItems.innerHTML = "";
  const tipoUsuario = selectUserType.value;

  productos.forEach((producto, index) => {
    const precio = tipoUsuario === "b2b" ? producto.precioB2B : producto.precioB2C;
    const stockHtml = tipoUsuario === "b2b" ? `<p>Stock: ${producto.stock}</p>` : "";

    const div = document.createElement("div");
    div.classList.add("items");
    div.innerHTML = `
      <figure>
        <img src="${producto.imagen}" alt="${producto.nombre}" />
      </figure>
      <div class="info-producto">
        <h2>${producto.nombre}</h2>
        <p class="price">$${precio.toLocaleString()}</p>
        ${stockHtml}
        <button data-id="${producto.id}" data-index="${index}" ${producto.stock === 0 ? 'disabled' : ''}>
          Añadir al carrito
        </button>
      </div>
    `;
    contenedorItems.appendChild(div);
  });

  activarBotonesAgregar();
}

// Agregar productos al carrito
function activarBotonesAgregar() {
  const botonesAgregar = document.querySelectorAll(".info-producto button");
  botonesAgregar.forEach((boton) => {
    boton.addEventListener("click", () => {
      const id = boton.getAttribute("data-id");
      const tipoUsuario = selectUserType.value;
      const producto = productos.find((p) => p.id === id);
      const precio = tipoUsuario === "b2b" ? producto.precioB2B : producto.precioB2C;

      // Verificar si hay stock disponible
      if (producto.stock <= 0) {
        alert("No hay stock disponible para este producto.");
        return;
      }

      const productoExistente = carrito.find((p) => p.id === id);

      if (productoExistente) {
        // Solo agregar si hay stock
        if (producto.stock > 0) {
          productoExistente.cantidad++;
          producto.stock--;
        }
      } else {
        carrito.push({
          id,
          nombre: producto.nombre,
          precio,
          cantidad: 1,
        });
        producto.stock--;
      }

      actualizarCarrito();
      cargarProductos(); // Actualizar stock en la UI
    });
  });
}

// Actualizar carrito
function actualizarCarrito() {
  const listaProductos = carritoContainer.querySelector(".cart-products-list");
  const mensajeVacio = carritoContainer.querySelector(".cart-empty-message");
  const btnPagar = carritoContainer.querySelector(".btn-pagar");

  // Limpiar lista actual del DOM
  listaProductos.innerHTML = "";

  let total = 0;
  let cantidadTotal = 0;

  if (carrito.length === 0) {
    mensajeVacio.style.display = "block";
    btnPagar.disabled = true;
  } else {
    mensajeVacio.style.display = "none";
    btnPagar.disabled = false;

    carrito.forEach((producto) => {
      const div = document.createElement("div");
      div.classList.add("cart-product");
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
      listaProductos.appendChild(div);

      total += producto.precio * producto.cantidad;
      cantidadTotal += producto.cantidad;
    });
  }

  // Actualizar total y contador
  totalPagar.textContent = `$${total.toLocaleString()}`;
  contadorCarrito.textContent = cantidadTotal;

  activarBotonesEliminar();
}

// Eliminar productos del carrito
function activarBotonesEliminar() {
  const botonesEliminar = carritoContainer.querySelectorAll(".icon-close");
  botonesEliminar.forEach((boton) => {
    boton.addEventListener("click", () => {
      const id = boton.getAttribute("data-id");
      const index = carrito.findIndex((p) => p.id === id);
      if (index !== -1) {
        // Devolver el stock al producto original según la cantidad eliminada
        const productoOriginal = productos.find((p) => p.id === id);
        productoOriginal.stock += carrito[index].cantidad;

        carrito.splice(index, 1);
        actualizarCarrito();
        cargarProductos(); // Actualizar stock visible
      }
    });
  });
}

// Cambiar tipo de usuario
selectUserType.addEventListener("change", () => {
  carrito = [];
  // Resetear stock a valores iniciales si quieres (opcional)
  // O mantén el stock actual si quieres persistir cambios durante la sesión
  actualizarCarrito();
  cargarProductos();
});

// Ir a pagar
btnPagar.addEventListener("click", () => {
  const tipoUsuario = selectUserType.value;
  if (tipoUsuario === "b2b") {
    generarPedidoB2B();
  } else {
    alert("Funcionalidad de pago no implementada para B2C aún.");
  }
});

// Simular envío a API externa
async function enviarPedidoAPI(pedido) {
  try {
    const respuesta = await fetch("https://tu-backend.com/api/pedidos", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(pedido),
    });
    const datos = await respuesta.json();
    console.log("Respuesta backend:", datos);
    alert("Pedido enviado al sistema externo correctamente.");
  } catch (error) {
    console.error("Error enviando pedido:", error);
    alert("Error al enviar pedido. Intente más tarde.");
  }
}

// Generar pedido B2B
async function generarPedidoB2B() {
  if (carrito.length === 0) {
    alert("El carrito está vacío.");
    return;
  }

  await enviarPedidoAPI(carrito);

  carrito = [];
  actualizarCarrito();
  cargarProductos();
}

// Carga inicial
cargarProductos();
actualizarCarrito();
