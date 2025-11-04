<?php
// Contenido de admin/seccion/producto/procesar_carrito.php
session_start();

// ZONA DE LÓGICA ARRAY/LISTA: Inicializamos el carrito si no existe
$_SESSION['carrito'] = $_SESSION['carrito'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $action = $_POST['action'] ?? null;
    $key = $_POST['plato_key'] ?? null;
    
    // ------------------------------------------
    // LÓGICA DE ACTUALIZACIÓN DE CANTIDAD (+ / -)
    // ------------------------------------------
    if ($action === 'add_one' || $action === 'remove_one') {
        
        if ($key !== null && isset($_SESSION['carrito'][$key])) {
            
            // ZONA DE LÓGICA ARRAY/LISTA: Modifica el valor 'cantidad' del array asociativo interno
            if ($action === 'add_one') {
                $_SESSION['carrito'][$key]['cantidad']++; // Incrementar
            } elseif ($action === 'remove_one' && $_SESSION['carrito'][$key]['cantidad'] > 1) {
                $_SESSION['carrito'][$key]['cantidad']--; // Decrementar si es mayor a 1
            }
        }
        
        $_SESSION['carrito_abierto'] = true; // Abrir Offcanvas

    // ------------------------------------------
    // LÓGICA DE ELIMINACIÓN (Papelera/Trash)
    // ------------------------------------------
    } elseif ($action === 'delete') {
        
        if ($key !== null && isset($_SESSION['carrito'][$key])) {
            // ZONA DE LÓGICA ARRAY/LISTA: Elimina el elemento del array principal por su índice ($key)
            unset($_SESSION['carrito'][$key]); 
            // ZONA DE LÓGICA ARRAY/LISTA: Reindexa el array para que los índices sigan siendo 0, 1, 2...
            $_SESSION['carrito'] = array_values($_SESSION['carrito']); 
        }
        
        $_SESSION['carrito_abierto'] = true; // Abrir Offcanvas

    // ------------------------------------------
    // LÓGICA DE AGREGAR (Desde el Modal)
    // ------------------------------------------
    } elseif (isset($_POST['plato_id'])) {
        
        // ZONA DE LÓGICA ARRAY/LISTA: Construye el nuevo array asociativo del ítem
        $plato_id = $_POST['plato_id'];
        $nombre = $_POST['plato_nombre'];
        $precio = (float)$_POST['plato_precio'];
        $cantidad = (int)$_POST['cantidad'];
        
        $item = [
            'id' => $plato_id,
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];

        // Lógica para AÑADIR/ACTUALIZAR
        $encontrado = false;
        // ZONA DE LÓGICA ARRAY/LISTA: Itera sobre el carrito para ver si el producto ya existe
        foreach ($_SESSION['carrito'] as $key => $existing_item) {
            if ($existing_item['id'] === $plato_id) {
                // ZONA DE LÓGICA ARRAY/LISTA: Actualiza la cantidad si ya está en la lista
                $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
                $encontrado = true;
                break;
            }
        }
        
        if (!$encontrado) {
            // ZONA DE LÓGICA ARRAY/LISTA: Añade el nuevo array asociativo al array principal
            $_SESSION['carrito'][] = $item;
        }

        $_SESSION['carrito_abierto'] = true; // Abrir Offcanvas
    }
}

// ⬅️ REDIRECCIÓN FINAL: Vuelve al index.php
header('Location: /restaurante/index.php');
exit();
?>