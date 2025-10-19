const BASE_URL = window.location.origin + '/sistema-inventario';
const API_URL = BASE_URL + '/modules/productos/api.php';

// Abrir modal para crear producto
function abrirModalCrear() {
    document.getElementById('modalTitulo').textContent = 'Nuevo Producto';
    document.getElementById('formProducto').reset();
    document.getElementById('producto_id').value = '';
    document.getElementById('preview-imagen').innerHTML = '';
    document.getElementById('modalProducto').classList.add('active');
}

// Cerrar modal
function cerrarModal() {
    document.getElementById('modalProducto').classList.remove('active');
    document.getElementById('formProducto').reset();
}

// Editar producto
async function editarProducto(id) {
    try {
        const response = await fetch(`${API_URL}?action=obtener&id=${id}`);
        const data = await response.json();

        if (data.success) {
            const producto = data.producto;
            document.getElementById('modalTitulo').textContent = 'Editar Producto';
            document.getElementById('producto_id').value = producto.id;
            document.getElementById('nombre').value = producto.nombre;
            document.getElementById('descripcion').value = producto.descripcion;
            document.getElementById('precio').value = producto.precio;
            document.getElementById('stock').value = producto.stock;

            // Mostrar imagen actual si existe
            if (producto.imagen) {
                document.getElementById('preview-imagen').innerHTML = `
                    <img src="${BASE_URL}/uploads/${producto.imagen}"
                         style="max-width: 200px; border-radius: 6px;">
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 0.5rem;">
                        Imagen actual (puedes subir una nueva para reemplazarla)
                    </p>
                `;
            }

            document.getElementById('modalProducto').classList.add('active');
        } else {
            mostrarMensaje(data.message, 'danger');
        }
    } catch (error) {
        mostrarMensaje('Error al cargar el producto', 'danger');
    }
}

// Eliminar producto
async function eliminarProducto(id, nombre) {
    if (!confirm(`¿Estás seguro de eliminar el producto "${nombre}"?`)) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append('id', id);

        const response = await fetch(`${API_URL}?action=eliminar`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById(`producto-${id}`).remove();
            mostrarMensaje(data.message, 'success');

            // Recargar si no quedan productos
            if (document.querySelectorAll('.product-card').length === 0) {
                setTimeout(() => location.reload(), 1500);
            }
        } else {
            mostrarMensaje(data.message, 'danger');
        }
    } catch (error) {
        mostrarMensaje('Error al eliminar el producto', 'danger');
    }
}

// Manejar envío del formulario
document.getElementById('formProducto').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btnGuardar = document.getElementById('btnGuardar');
    btnGuardar.disabled = true;
    btnGuardar.textContent = 'Guardando...';

    const formData = new FormData(e.target);
    const id = document.getElementById('producto_id').value;
    const action = id ? 'actualizar' : 'crear';

    try {
        const response = await fetch(`${API_URL}?action=${action}`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            mostrarMensaje(data.message, 'success');
            cerrarModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            mostrarMensaje(data.message, 'danger');
        }
    } catch (error) {
        mostrarMensaje('Error al guardar el producto', 'danger');
    } finally {
        btnGuardar.disabled = false;
        btnGuardar.textContent = 'Guardar Producto';
    }
});

// Preview de imagen
document.getElementById('imagen').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-imagen').innerHTML = `
                <img src="${e.target.result}"
                     style="max-width: 200px; border-radius: 6px; margin-top: 0.5rem;">
            `;
        };
        reader.readAsDataURL(file);
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('modalProducto').addEventListener('click', (e) => {
    if (e.target.id === 'modalProducto') {
        cerrarModal();
    }
});

// Mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    const div = document.getElementById('mensaje');
    div.innerHTML = `<div class="alert alert-${tipo}">${mensaje}</div>`;
    setTimeout(() => {
        div.innerHTML = '';
    }, 5000);
}
