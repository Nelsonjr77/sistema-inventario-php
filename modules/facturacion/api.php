<?php
require_once '../../config/database.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    $db = getDB();

    switch ($action) {
        case 'crear':
            if ($method === 'POST') {
                crearFactura($db);
            }
            break;

        case 'obtener':
            if ($method === 'GET' && isset($_GET['id'])) {
                obtenerFactura($db, $_GET['id']);
            }
            break;

        default:
            jsonResponse(['success' => false, 'message' => 'Acción no válida'], 400);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}

function crearFactura($db) {
    // Validar datos
    $numeroFactura = sanitize($_POST['numero_factura'] ?? '');
    $clienteNombre = sanitize($_POST['cliente_nombre'] ?? '');
    $clienteIdentificacion = sanitize($_POST['cliente_identificacion'] ?? '');
    $observaciones = sanitize($_POST['observaciones'] ?? '');
    $productos = $_POST['productos'] ?? [];
    $cantidades = $_POST['cantidades'] ?? [];

    if (empty($numeroFactura) || empty($productos)) {
        jsonResponse(['success' => false, 'message' => 'Datos incompletos'], 400);
    }

    if (count($productos) !== count($cantidades)) {
        jsonResponse(['success' => false, 'message' => 'Datos inconsistentes'], 400);
    }

    // Iniciar transacción
    $db->beginTransaction();

    try {
        // Calcular totales
        $subtotal = 0;
        $detalles = [];

        for ($i = 0; $i < count($productos); $i++) {
            $productoId = intval($productos[$i]);
            $cantidad = intval($cantidades[$i]);

            if ($cantidad <= 0) continue;

            // Obtener información del producto
            $stmt = $db->prepare("SELECT * FROM productos WHERE id = ?");
            $stmt->execute([$productoId]);
            $producto = $stmt->fetch();

            if (!$producto) {
                throw new Exception("Producto no encontrado");
            }

            $subtotalProducto = $producto['precio'] * $cantidad;
            $subtotal += $subtotalProducto;

            $detalles[] = [
                'producto_id' => $productoId,
                'producto_nombre' => $producto['nombre'],
                'cantidad' => $cantidad,
                'precio_unitario' => $producto['precio'],
                'subtotal' => $subtotalProducto
            ];
        }

        if (empty($detalles)) {
            throw new Exception("Debe agregar al menos un producto");
        }

        $iva = $subtotal * 0.13;
        $total = $subtotal + $iva;

        // Insertar factura
        $sql = "INSERT INTO facturas (numero_factura, subtotal, iva, total, cliente_nombre, cliente_identificacion, observaciones)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$numeroFactura, $subtotal, $iva, $total, $clienteNombre, $clienteIdentificacion, $observaciones]);

        $facturaId = $db->lastInsertId();

        // Insertar detalles
        $sql = "INSERT INTO factura_detalle (factura_id, producto_id, producto_nombre, cantidad, precio_unitario, subtotal)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);

        foreach ($detalles as $detalle) {
            $stmt->execute([
                $facturaId,
                $detalle['producto_id'],
                $detalle['producto_nombre'],
                $detalle['cantidad'],
                $detalle['precio_unitario'],
                $detalle['subtotal']
            ]);
        }

        // Confirmar transacción
        $db->commit();

        jsonResponse([
            'success' => true,
            'message' => 'Factura generada exitosamente',
            'factura_id' => $facturaId
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

function obtenerFactura($db, $id) {
    // Obtener factura
    $sql = "SELECT * FROM facturas WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $factura = $stmt->fetch();

    if (!$factura) {
        jsonResponse(['success' => false, 'message' => 'Factura no encontrada'], 404);
    }

    // Obtener detalles
    $sql = "SELECT * FROM factura_detalle WHERE factura_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $detalles = $stmt->fetchAll();

    $factura['detalles'] = $detalles;

    jsonResponse(['success' => true, 'factura' => $factura]);
}
?>
