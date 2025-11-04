<?php
include_once __DIR__ . '/grafo.php';

$grafo_mesas = new Grafo();

// Agregar 16 mesas como nodos
for ($i = 1; $i <= 16; $i++) {
    $grafo_mesas->agregarNodo("Mesa $i");
}

// Agregar Entrada y Cocina
$grafo_mesas->agregarNodo("Entrada");
$grafo_mesas->agregarNodo("Cocina");

// Conectar mesas en una cuadrícula de 4x4
for ($i = 1; $i <= 16; $i++) {
    // Conectar con la mesa de la derecha (si no está en el borde derecho)
    if ($i % 4 != 0) {
        $grafo_mesas->agregarArista("Mesa $i", "Mesa " . ($i + 1));
        $grafo_mesas->agregarArista("Mesa " . ($i + 1), "Mesa $i");
    }

    // Conectar con la mesa de abajo (si no está en la última fila)
    if ($i <= 12) {
        $grafo_mesas->agregarArista("Mesa $i", "Mesa " . ($i + 4));
        $grafo_mesas->agregarArista("Mesa " . ($i + 4), "Mesa $i");
    }
}

// Conectar Entrada a las mesas más cercanas
$grafo_mesas->agregarArista("Entrada", "Mesa 13");
$grafo_mesas->agregarArista("Mesa 13", "Entrada");
$grafo_mesas->agregarArista("Entrada", "Mesa 16");
$grafo_mesas->agregarArista("Mesa 16", "Entrada");

// Conectar Cocina a las mesas más cercanas
$grafo_mesas->agregarArista("Cocina", "Mesa 1");
$grafo_mesas->agregarArista("Mesa 1", "Cocina");
$grafo_mesas->agregarArista("Cocina", "Mesa 4");
$grafo_mesas->agregarArista("Mesa 4", "Cocina");

?>