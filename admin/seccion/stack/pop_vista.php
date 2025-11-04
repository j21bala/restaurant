<?php
session_start();
require_once 'stack_logic.php';

$visitas_stack = new VisitasRecientesStack();
$visitas_stack->pop();

header("Location: ../../../index.php#stack-vistas");
exit;
?>