<?php
$pageTitle = 'Histórico de Ventas';
include '../../includes/header.php';

try {
    $db = getDB();

    // Obtener facturas
    $stmt = $db->query("SELECT * FROM facturas ORDER BY fecha DESC");
    $facturas = $stmt->fetchAll();

    // Obtener estadísticas
    $stmt = $db->query("
        SELECT
            p.nombre,
            SUM(fd.cantidad) as total_vendido,
            SUM(fd.subtotal) as total_ingresos
        FROM factura_detalle fd
        INNER JOIN productos p ON fd.producto_id = p.id
        GROUP BY fd.producto_id, p.nombre
        ORDER BY total_vendido DESC
        LIMIT 5
    ");
    $productosMasVendidos = $stmt->fetchAll();

    // Estadísticas generales
    $stmt = $db->query("SELECT COUNT(*) as total FROM facturas");
    $totalFacturas = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COALESCE(SUM(total), 0) as total FROM facturas");
    $totalVentas = $stmt->fetch()['total'];

    $stmt = $db->query("SELECT COALESCE(AVG(total), 0) as promedio FROM facturas");
    $promedioVenta = $stmt->fetch()['promedio'];

} catch(PDOException $e) {
    $error = "Error al obtener datos: " . $e->getMessage();
}
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Histórico de Ventas</h2>
            <a href="../facturacion/index.php" class="btn btn-success">+ Nueva Factura</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total de Ventas</div>
                <div class="stat-value">$<?php echo number_format($totalVentas, 2); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Total Facturas</div>
                <div class="stat-value"><?php echo number_format($totalFacturas); ?></div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Promedio por Venta</div>
                <div class="stat-value">$<?php echo number_format($promedioVenta, 2); ?></div>
            </div>
        </div>

        <!-- Productos más vendidos -->
        <?php if (!empty($productosMasVendidos)): ?>
        <div class="card" style="background: var(--bg-color); margin-bottom: 2rem;">
            <h3 style="margin-bottom: 1rem;">Productos Más Vendidos</h3>
            <div class="table" style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Posición</th>
                            <th>Producto</th>
                            <th style="text-align: center;">Unidades Vendidas</th>
                            <th style="text-align: right;">Ingresos Generados</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productosMasVendidos as $index => $producto): ?>
                        <tr>
                            <td>
                                <span style="display: inline-block; width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; text-align: center; line-height: 30px; font-weight: 600;">
                                    <?php echo $index + 1; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                            <td style="text-align: center;"><?php echo number_format($producto['total_vendido']); ?></td>
                            <td style="text-align: right; font-weight: 600; color: var(--success-color);">
                                $<?php echo number_format($producto['total_ingresos'], 2); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <!-- Lista de facturas -->
        <h3 style="margin-bottom: 1rem;">Historial de Facturas</h3>

        <?php if (empty($facturas)): ?>
            <div class="text-center" style="padding: 3rem; color: var(--text-secondary);">
                <p style="font-size: 3rem;">📊</p>
                <p>No hay facturas registradas</p>
                <a href="../facturacion/index.php" class="btn btn-primary mt-2">Generar primera factura</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N° Factura</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th style="text-align: right;">Subtotal</th>
                            <th style="text-align: right;">IVA</th>
                            <th style="text-align: right;">Total</th>
                            <th style="text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($facturas as $factura): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($factura['numero_factura']); ?></strong>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($factura['fecha'])); ?></td>
                            <td><?php echo htmlspecialchars($factura['cliente_nombre']) ?: 'N/A'; ?></td>
                            <td style="text-align: right;">$<?php echo number_format($factura['subtotal'], 2); ?></td>
                            <td style="text-align: right;">$<?php echo number_format($factura['iva'], 2); ?></td>
                            <td style="text-align: right; font-weight: 600; color: var(--primary-color);">
                                $<?php echo number_format($factura['total'], 2); ?>
                            </td>
                            <td style="text-align: center;">
                                <a href="../facturacion/imprimir.php?id=<?php echo $factura['id']; ?>"
                                   class="btn btn-primary btn-sm"
                                   target="_blank">
                                    Ver
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
