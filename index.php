<?php
$pageTitle = 'Dashboard';
include 'includes/header.php';

try {
    $db = getDB();

    // Obtener estadísticas
    $stmt = $db->query("SELECT COUNT(*) as total FROM productos");
    $totalProductos = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COUNT(*) as total FROM facturas");
    $totalFacturas = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total FROM facturas");
    $totalVentas = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COALESCE(AVG(total), 0) as promedio FROM facturas");
    $promedioVenta = $stmt->fetch()['promedio'];

} catch(PDOException $e) {
    $error = "Error al obtener estadísticas: " . $e->getMessage();
}
?>

<div class="container">
    <div class="card">
        <h2 class="card-title">Bienvenido al Sistema de Inventario y Facturación</h2>
        <p style="color: var(--text-secondary);">Gestiona tus productos, genera facturas y consulta el histórico de ventas desde un solo lugar.</p>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total de Productos</div>
            <div class="stat-value"><?php echo number_format($totalProductos); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Facturas Generadas</div>
            <div class="stat-value"><?php echo number_format($totalFacturas); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Ventas Totales</div>
            <div class="stat-value">$<?php echo number_format($totalVentas, 2); ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Promedio por Venta</div>
            <div class="stat-value">$<?php echo number_format($promedioVenta, 2); ?></div>
        </div>
    </div>

    <div class="card">
        <h3 class="card-title">Accesos Rápidos</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
            <a href="modules/productos/index.php" class="btn btn-primary" style="padding: 2rem;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📦</div>
                <div>Gestionar Productos</div>
            </a>

            <a href="modules/facturacion/index.php" class="btn btn-success" style="padding: 2rem;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">🧾</div>
                <div>Nueva Factura</div>
            </a>

            <a href="modules/ventas/index.php" class="btn btn-secondary" style="padding: 2rem;">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">📊</div>
                <div>Ver Ventas</div>
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
