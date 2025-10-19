<?php
$pageTitle = 'Nueva Factura';
include '../../includes/header.php';

try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM productos WHERE stock > 0 ORDER BY nombre");
    $productos = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al obtener productos: " . $e->getMessage();
}

// Generar número de factura
$numeroFactura = 'FAC-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Generar Nueva Factura</h2>
            <a href="../ventas/index.php" class="btn btn-secondary">Ver Historial</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div id="mensaje"></div>

        <form id="formFactura">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div class="form-group">
                    <label class="form-label">Número de Factura</label>
                    <input type="text" class="form-control" id="numero_factura" name="numero_factura"
                           value="<?php echo $numeroFactura; ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Fecha</label>
                    <input type="text" class="form-control" value="<?php echo date('d/m/Y H:i'); ?>" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">Nombre del Cliente</label>
                    <input type="text" class="form-control" id="cliente_nombre" name="cliente_nombre">
                </div>

                <div class="form-group">
                    <label class="form-label">Identificación del Cliente</label>
                    <input type="text" class="form-control" id="cliente_identificacion" name="cliente_identificacion">
                </div>
            </div>

            <div class="card-header" style="padding: 0; margin-bottom: 1rem;">
                <h3>Productos</h3>
                <button type="button" class="btn btn-primary" onclick="agregarProducto()">+ Agregar Producto</button>
            </div>

            <?php if (empty($productos)): ?>
                <div class="alert alert-info">No hay productos disponibles en el inventario.</div>
            <?php else: ?>
                <div id="productos-factura">
                    <!-- Los productos se agregan dinámicamente aquí -->
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--border-color);">
                    <div style="max-width: 400px; margin-left: auto;">
                        <div class="d-flex justify-between mb-2">
                            <span style="font-weight: 500;">Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-between mb-2">
                            <span style="font-weight: 500;">IVA (13%):</span>
                            <span id="iva">$0.00</span>
                        </div>
                        <div class="d-flex justify-between" style="font-size: 1.25rem; font-weight: 700; color: var(--primary-color);">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label class="form-label">Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
                </div>

                <div style="margin-top: 2rem; text-align: right;">
                    <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success" id="btnGenerarFactura">Generar Factura</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Template para fila de producto -->
<template id="template-producto">
    <div class="producto-item" style="display: grid; grid-template-columns: 2fr 1fr 1fr 1fr 60px; gap: 1rem; align-items: end; padding: 1rem; background: var(--bg-color); border-radius: 6px; margin-bottom: 1rem;">
        <div class="form-group" style="margin: 0;">
            <label class="form-label">Producto</label>
            <select class="form-control producto-select" name="productos[]" required onchange="actualizarPrecio(this)">
                <option value="">Seleccionar producto...</option>
                <?php foreach ($productos as $producto): ?>
                    <option value="<?php echo $producto['id']; ?>"
                            data-precio="<?php echo $producto['precio']; ?>"
                            data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>">
                        <?php echo htmlspecialchars($producto['nombre']); ?> - $<?php echo number_format($producto['precio'], 2); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin: 0;">
            <label class="form-label">Cantidad</label>
            <input type="number" class="form-control cantidad-input" name="cantidades[]" min="1" value="1" required onchange="calcularTotales()">
        </div>

        <div class="form-group" style="margin: 0;">
            <label class="form-label">Precio Unit.</label>
            <input type="number" class="form-control precio-input" step="0.01" readonly>
        </div>

        <div class="form-group" style="margin: 0;">
            <label class="form-label">Subtotal</label>
            <input type="text" class="form-control subtotal-input" readonly>
        </div>

        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(this)" style="height: 38px;">×</button>
    </div>
</template>

<script>
    const productosData = <?php echo json_encode($productos); ?>;
</script>
<script src="<?php echo BASE_URL; ?>/assets/js/facturacion.js"></script>

<?php include '../../includes/footer.php'; ?>
