// Detectar BASE_URL automáticamente
const pathArray = window.location.pathname.split('/');
const BASE_URL = window.location.origin + '/' + pathArray[1];
const API_URL = BASE_URL + '/modules/facturacion/api.php';

// Agregar primera fila de producto automáticamente
window.addEventListener('DOMContentLoaded', () => {
    agregarProducto();
});

// Agregar nueva fila de producto
function agregarProducto() {
    const template = document.getElementById('template-producto');
    const clone = template.content.cloneNode(true);
    document.getElementById('productos-factura').appendChild(clone);
}

// Eliminar fila de producto
function eliminarProducto(btn) {
    const items = document.querySelectorAll('.producto-item');
    if (items.length > 1) {
        btn.closest('.producto-item').remove();
        calcularTotales();
    } else {
        alert('Debe haber al menos un producto en la factura');
    }
}

// Actualizar precio cuando se selecciona un producto
function actualizarPrecio(select) {
    const option = select.options[select.selectedIndex];
    const precio = parseFloat(option.dataset.precio) || 0;
    const item = select.closest('.producto-item');
    const precioInput = item.querySelector('.precio-input');

    precioInput.value = precio.toFixed(2);
    calcularTotales();
}

// Calcular totales
function calcularTotales() {
    const items = document.querySelectorAll('.producto-item');
    let subtotalGeneral = 0;

    items.forEach(item => {
        const select = item.querySelector('.producto-select');
        const cantidadInput = item.querySelector('.cantidad-input');
        const precioInput = item.querySelector('.precio-input');
        const subtotalInput = item.querySelector('.subtotal-input');

        const precio = parseFloat(precioInput.value) || 0;
        const cantidad = parseInt(cantidadInput.value) || 0;
        const subtotal = precio * cantidad;

        subtotalInput.value = '$' + subtotal.toFixed(2);
        subtotalGeneral += subtotal;
    });

    const iva = subtotalGeneral * 0.13;
    const total = subtotalGeneral + iva;

    document.getElementById('subtotal').textContent = '$' + subtotalGeneral.toFixed(2);
    document.getElementById('iva').textContent = '$' + iva.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);
}

// Validar formulario
function validarFormulario() {
    const items = document.querySelectorAll('.producto-item');
    let valido = true;
    let mensaje = '';

    // Verificar que haya al menos un producto
    if (items.length === 0) {
        mensaje = 'Debe agregar al menos un producto';
        valido = false;
    }

    // Verificar que todos los productos estén seleccionados
    items.forEach((item, index) => {
        const select = item.querySelector('.producto-select');
        const cantidad = item.querySelector('.cantidad-input');

        if (!select.value) {
            mensaje = `Seleccione un producto en la fila ${index + 1}`;
            valido = false;
        }

        if (parseInt(cantidad.value) <= 0) {
            mensaje = `La cantidad debe ser mayor a 0 en la fila ${index + 1}`;
            valido = false;
        }
    });

    // Verificar que el total sea mayor a 0
    const totalText = document.getElementById('total').textContent;
    const total = parseFloat(totalText.replace('$', ''));
    if (total <= 0) {
        mensaje = 'El total debe ser mayor a 0';
        valido = false;
    }

    if (!valido) {
        mostrarMensaje(mensaje, 'danger');
    }

    return valido;
}

// Manejar envío del formulario
document.getElementById('formFactura').addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validarFormulario()) {
        return;
    }

    const btnGenerarFactura = document.getElementById('btnGenerarFactura');
    btnGenerarFactura.disabled = true;
    btnGenerarFactura.textContent = 'Generando...';

    const formData = new FormData(e.target);

    try {
        const response = await fetch(`${API_URL}?action=crear`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            mostrarMensaje(data.message, 'success');
            setTimeout(() => {
                window.location.href = `imprimir.php?id=${data.factura_id}`;
            }, 1000);
        } else {
            mostrarMensaje(data.message, 'danger');
            btnGenerarFactura.disabled = false;
            btnGenerarFactura.textContent = 'Generar Factura';
        }
    } catch (error) {
        mostrarMensaje('Error al generar la factura', 'danger');
        btnGenerarFactura.disabled = false;
        btnGenerarFactura.textContent = 'Generar Factura';
    }
});

// Mostrar mensajes
function mostrarMensaje(mensaje, tipo) {
    const div = document.getElementById('mensaje');
    div.innerHTML = `<div class="alert alert-${tipo}">${mensaje}</div>`;
    setTimeout(() => {
        div.innerHTML = '';
    }, 5000);

    // Scroll al top para ver el mensaje
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
