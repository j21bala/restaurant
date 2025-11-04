<?php
session_start();
require_once 'stack_logic.php';

if (isset($_GET['id'])) {
    $plato_id = (int)$_GET['id'];
    
    $visitas_stack = new VisitasRecientesStack(5);
    $visitas_stack->push($plato_id);
    
    // Redirigir de vuelta a la página principal sin parámetros de acción
    header("Location: ../../../index.php#recomendados");
    exit;
}
?>