<?php
require_once '../../config/database.php';

$facturaId = $_GET['id'] ?? 0;

if (!$facturaId) {
    header('Location: index.php');
    exit;
}

try {
    $db = getDB();

    // Obtener factura
    $stmt = $db->prepare("SELECT * FROM facturas WHERE id = ?");
    $stmt->execute([$facturaId]);
    $factura = $stmt->fetch();

    if (!$factura) {
        header('Location: index.php');
        exit;
    }

    // Obtener detalles
    $stmt = $db->prepare("SELECT * FROM factura_detalle WHERE factura_id = ?");
    $stmt->execute([$facturaId]);
    $detalles = $stmt->fetchAll();

} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?php echo htmlspecialchars($factura['numero_factura']); ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
    <style>
        .factura-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            padding: 3rem;
            box-shadow: var(--shadow-lg);
        }

        .factura-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid var(--primary-color);
        }

        .factura-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
        }

        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .factura-container {
                box-shadow: none;
                margin: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: var(--bg-color); padding: 1rem 0;">
        <div class="container">
            <a href="index.php" class="btn btn-secondary">← Nueva Factura</a>
            <a href="../ventas/index.php" class="btn btn-secondary">Ver Historial</a>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimir</button>
        </div>
    </div>

    <div class="factura-container">
        <div class="factura-header">
            <h1 style="color: var(--primary-color); margin-bottom: 0.5rem;">FACTURA</h1>
            <h2 style="font-size: 1.5rem; font-weight: 600;"><?php echo htmlspecialchars($factura['numero_factura']); ?></h2>
        </div>

        <div class="factura-info">
            <div>
                <div class="info-label">Fecha:</div>
                <div><?php echo date('d/m/Y H:i', strtotime($factura['fecha'])); ?></div>
            </div>

            <div>
                <div class="info-label">Cliente:</div>
                <div><?php echo htmlspecialchars($factura['cliente_nombre']) ?: 'N/A'; ?></div>
            </div>

            <?php if ($factura['cliente_identificacion']): ?>
            <div>
                <div class="info-label">Identificación:</div>
                <div><?php echo htmlspecialchars($factura['cliente_identificacion']); ?></div>
            </div>
            <?php endif; ?>
        </div>

        <h3 style="margin-bottom: 1rem;">Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: right;">Precio Unit.</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                <tr>
                    <td><?php echo htmlspecialchars($detalle['producto_nombre']); ?></td>
                    <td style="text-align: center;"><?php echo $detalle['cantidad']; ?></td>
                    <td style="text-align: right;">$<?php echo number_format($detalle['precio_unitario'], 2); ?></td>
                    <td style="text-align: right;">$<?php echo number_format($detalle['subtotal'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid var(--border-color);">
            <div style="max-width: 300px; margin-left: auto;">
                <div class="d-flex justify-between mb-2">
                    <span class="info-label">Subtotal:</span>
                    <span>$<?php echo number_format($factura['subtotal'], 2); ?></span>
                </div>
                <div class="d-flex justify-between mb-2">
                    <span class="info-label">IVA (13%):</span>
                    <span>$<?php echo number_format($factura['iva'], 2); ?></span>
                </div>
                <div class="d-flex justify-between" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-color); padding-top: 0.5rem; border-top: 2px solid var(--border-color);">
                    <span>TOTAL:</span>
                    <span>$<?php echo number_format($factura['total'], 2); ?></span>
                </div>
            </div>
        </div>

        <?php if ($factura['observaciones']): ?>
        <div style="margin-top: 2rem; padding: 1rem; background: var(--bg-color); border-radius: 6px;">
            <div class="info-label">Observaciones:</div>
            <div><?php echo nl2br(htmlspecialchars($factura['observaciones'])); ?></div>
        </div>
        <?php endif; ?>

        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color); text-align: center; color: var(--text-secondary); font-size: 0.875rem;">
            <p>Gracias por su compra</p>
            <p>Sistema de Inventario y Facturación - <?php echo date('Y'); ?></p>
        </div>
    </div>
</body>
</html>
