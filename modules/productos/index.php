<?php
$pageTitle = 'Gestión de Productos';
include '../../includes/header.php';

try {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM productos ORDER BY fecha_registro DESC");
    $productos = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error al obtener productos: " . $e->getMessage();
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Inventario de Productos</h2>
            <button class="btn btn-primary" onclick="abrirModalCrear()">+ Nuevo Producto</button>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div id="mensaje"></div>

        <?php if (empty($productos)): ?>
            <div class="text-center" style="padding: 3rem; color: var(--text-secondary);">
                <p style="font-size: 3rem;">📦</p>
                <p>No hay productos registrados</p>
                <button class="btn btn-primary mt-2" onclick="abrirModalCrear()">Agregar el primer producto</button>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($productos as $producto): ?>
                    <div class="product-card" id="producto-<?php echo $producto['id']; ?>">
                        <?php if ($producto['imagen']): ?>
                            <img src="<?php echo UPLOAD_URL . $producto['imagen']; ?>"
                                 alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                 class="product-image">
                        <?php else: ?>
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: var(--bg-color); font-size: 4rem;">
                                📦
                            </div>
                        <?php endif; ?>

                        <div class="product-body">
                            <h3 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                            <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                            <div class="product-price">$<?php echo number_format($producto['precio'], 2); ?></div>
                            <div class="product-actions">
                                <button class="btn btn-primary btn-sm" onclick="editarProducto(<?php echo $producto['id']; ?>)">Editar</button>
                                <button class="btn btn-danger btn-sm" onclick="eliminarProducto(<?php echo $producto['id']; ?>, '<?php echo htmlspecialchars($producto['nombre']); ?>')">Eliminar</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Crear/Editar Producto -->
<div id="modalProducto" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitulo">Nuevo Producto</h3>
            <button class="modal-close" onclick="cerrarModal()">&times;</button>
        </div>
        <form id="formProducto" enctype="multipart/form-data">
            <div class="modal-body">
                <input type="hidden" id="producto_id" name="id">

                <div class="form-group">
                    <label class="form-label">Nombre del Producto *</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Precio *</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" min="0" value="0">
                </div>

                <div class="form-group">
                    <label class="form-label">Imagen del Producto</label>
                    <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                    <div id="preview-imagen" style="margin-top: 1rem;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="btnGuardar">Guardar Producto</button>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/productos.js"></script>

<?php include '../../includes/footer.php'; ?>
