<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Sistema de Inventario</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <h1>Sistema de Inventario</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="<?php echo BASE_URL; ?>/index.php" class="nav-link">Inicio</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modules/productos/index.php" class="nav-link">Productos</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modules/facturacion/index.php" class="nav-link">Facturación</a></li>
                <li><a href="<?php echo BASE_URL; ?>/modules/ventas/index.php" class="nav-link">Ventas</a></li>
            </ul>
        </div>
    </nav>
    <main class="main-content">
