<?php
session_start();
include("../bd.php"); // Incluye la conexión a la base de datos

// 1. Verifica si se ha seleccionado una mesa
if (!isset($_SESSION['mesa_seleccionada']) || empty($_SESSION['mesa_seleccionada'])) {
    // Si no hay mesa, redirige al usuario a la sección de selección de mesas con un mensaje de error
    $_SESSION['error'] = "Por favor, selecciona una mesa antes de realizar un pedido.";
    header("Location: ../../index.php#reserva");
    exit;
}

// 2. Verifica si el carrito está vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    // Si el carrito está vacío, redirige al usuario a la página principal
    header("Location: ../../index.php");
    exit;
}

// 3. Prepara los datos para insertar el pedido
$mesa = $_SESSION['mesa_seleccionada'];
$carrito = $_SESSION['carrito'];
$fecha_actual = date('Y-m-d H:i:s'); // Se añade la fecha y hora actuales

// Se utiliza un nombre de cliente genérico. Esto se puede mejorar en el futuro.
$nombre_cliente = "Cliente Mesa " . $mesa;

try {
    // Prepara la consulta SQL una sola vez para mayor eficiencia
    // Se añade la columna `fecha` a la consulta
    $sql = "INSERT INTO pedidos (nombre_cliente, numero_mesa, plato, estado, fecha, total) VALUES (:nombre_cliente, :numero_mesa, :plato, :estado, :fecha, :total)";
    $stmt = $conn->prepare($sql);

    // 4. Recorre los artículos del carrito e inserta cada uno como un pedido separado
    foreach ($carrito as $item) {
        // El nombre del plato incluirá el nombre del producto y la cantidad
        $nombre_plato = $item['nombre'] . ' (x' . $item['cantidad'] . ')';
        $total = $item['precio'] * $item['cantidad'];
        $estado = 'en_cola';
        
        // Vincula los parámetros a la consulta SQL
        $stmt->bindParam(':nombre_cliente', $nombre_cliente);
        $stmt->bindParam(':numero_mesa', $mesa);
        $stmt->bindParam(':plato', $nombre_plato);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':fecha', $fecha_actual); // Se vincula la fecha
        $stmt->bindParam(':total', $total);
        
        // Ejecuta la consulta para insertar el pedido
        $stmt->execute();
    }

    // 5. Limpia el carrito de la sesión una vez que los pedidos han sido procesados
    unset($_SESSION['carrito']);

    // 6. Redirige al usuario a la página principal con un mensaje de éxito
    $_SESSION['pedido_exitoso'] = "¡Tu pedido ha sido enviado a la cocina!";
    header("Location: ../../index.php");
    exit;

} catch (PDOException $e) {
    // En caso de un error en la base de datos, se guarda un mensaje de error y se redirige
    $_SESSION['error'] = "Hubo un error al procesar tu pedido. Por favor, intenta de nuevo.";
    error_log("Error al crear pedido: " . $e->getMessage()); // Opcional: registrar el error para depuración
    header("Location: ../../index.php");
    exit;
}
?>