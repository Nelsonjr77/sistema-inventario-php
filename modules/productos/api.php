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
                crearProducto($db);
            }
            break;

        case 'obtener':
            if ($method === 'GET' && isset($_GET['id'])) {
                obtenerProducto($db, $_GET['id']);
            }
            break;

        case 'actualizar':
            if ($method === 'POST') {
                actualizarProducto($db);
            }
            break;

        case 'eliminar':
            if ($method === 'POST' && isset($_POST['id'])) {
                eliminarProducto($db, $_POST['id']);
            }
            break;

        default:
            jsonResponse(['success' => false, 'message' => 'Acción no válida'], 400);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}

function crearProducto($db) {
    $nombre = sanitize($_POST['nombre'] ?? '');
    $descripcion = sanitize($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    if (empty($nombre) || $precio <= 0) {
        jsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
    }

    // Manejo de imagen
    $nombreImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = subirImagen($_FILES['imagen']);
    }

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreImagen]);

    jsonResponse([
        'success' => true,
        'message' => 'Producto creado exitosamente',
        'id' => $db->lastInsertId()
    ]);
}

function obtenerProducto($db, $id) {
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    if ($producto) {
        jsonResponse(['success' => true, 'producto' => $producto]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Producto no encontrado'], 404);
    }
}

function actualizarProducto($db) {
    $id = intval($_POST['id'] ?? 0);
    $nombre = sanitize($_POST['nombre'] ?? '');
    $descripcion = sanitize($_POST['descripcion'] ?? '');
    $precio = floatval($_POST['precio'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    if ($id <= 0 || empty($nombre) || $precio <= 0) {
        jsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
    }

    // Obtener imagen actual
    $sql = "SELECT imagen FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $productoActual = $stmt->fetch();

    $nombreImagen = $productoActual['imagen'];

    // Si se subió una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        // Eliminar imagen anterior si existe
        if ($nombreImagen && file_exists(UPLOAD_DIR . $nombreImagen)) {
            unlink(UPLOAD_DIR . $nombreImagen);
        }
        $nombreImagen = subirImagen($_FILES['imagen']);
    }

    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$nombre, $descripcion, $precio, $stock, $nombreImagen, $id]);

    jsonResponse([
        'success' => true,
        'message' => 'Producto actualizado exitosamente'
    ]);
}

function eliminarProducto($db, $id) {
    // Verificar si el producto tiene ventas asociadas
    $sql = "SELECT COUNT(*) as total FROM factura_detalle WHERE producto_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch();

    if ($result['total'] > 0) {
        jsonResponse([
            'success' => false,
            'message' => 'No se puede eliminar el producto porque tiene ventas asociadas'
        ], 400);
    }

    // Obtener imagen para eliminarla
    $sql = "SELECT imagen FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);
    $producto = $stmt->fetch();

    // Eliminar producto
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$id]);

    // Eliminar imagen si existe
    if ($producto && $producto['imagen'] && file_exists(UPLOAD_DIR . $producto['imagen'])) {
        unlink(UPLOAD_DIR . $producto['imagen']);
    }

    jsonResponse([
        'success' => true,
        'message' => 'Producto eliminado exitosamente'
    ]);
}

function subirImagen($file) {
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        throw new Exception('Tipo de archivo no permitido');
    }

    $nombreArchivo = uniqid('prod_') . '.' . $extension;
    $rutaDestino = UPLOAD_DIR . $nombreArchivo;

    // Crear directorio si no existe
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $rutaDestino)) {
        throw new Exception('Error al subir la imagen');
    }

    return $nombreArchivo;
}
?>
