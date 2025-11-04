<?php
session_start();
include("bd.php");

// Validar que se recibió el parámetro mesa
if (!isset($_GET['mesa'])) {
    header("Location: ../index.php");
    exit;
}

$mesa = intval($_GET['mesa']);

// Validar que la mesa está en rango válido (1-16)
if ($mesa < 1 || $mesa > 16) {
    header("Location: ../index.php");
    exit;
}

$fecha_hoy = date("Y-m-d");
$mensaje = "";
$tipo_mensaje = "";

try {
    // Verificar que la mesa no esté completamente reservada hoy
    $sql_check = "SELECT COUNT(*) as total FROM reservas WHERE DATE(fecha) = :fecha AND mesa = :mesa";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindParam(":fecha", $fecha_hoy);
    $stmt_check->bindParam(":mesa", $mesa);
    $stmt_check->execute();
    $total_reservas = $stmt_check->fetch(PDO::FETCH_ASSOC)['total'];

    // Consultar horas ya ocupadas para esa mesa hoy, formateadas como HH:MM
    $sql = "SELECT DATE_FORMAT(fecha, '%H:%i') as hora FROM reservas WHERE DATE(fecha) = :fecha AND mesa = :mesa";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":fecha", $fecha_hoy);
    $stmt->bindParam(":mesa", $mesa);
    $stmt->execute();
    $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Horarios disponibles
    $horarios = ["12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00"];

    // Si todos los horarios están ocupados, redirigir
    if (count($horas_ocupadas) >= count($horarios)) {
        $_SESSION['error'] = "La mesa $mesa no tiene horarios disponibles hoy.";
        header("Location: ../index.php");
        exit;
    }

    // Procesar el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = trim($_POST['nombre']);
        $telefono = trim($_POST['telefono']);
        $hora = $_POST['hora'];
        $fecha_reserva = $fecha_hoy . ' ' . $hora;

        // Validaciones
        if (empty($nombre) || empty($telefono) || empty($hora)) {
            $mensaje = "⚠️ Todos los campos son obligatorios.";
            $tipo_mensaje = "warning";
        } elseif (!in_array($hora, $horarios)) {
            $mensaje = "⚠️ Horario no válido.";
            $tipo_mensaje = "warning";
        } elseif (in_array($hora, $horas_ocupadas)) {
            $mensaje = "⚠️ Esa hora ya está ocupada para la mesa $mesa. Por favor selecciona otra.";
            $tipo_mensaje = "warning";
            
            // Recargar horas ocupadas por si cambió
            $stmt->execute();
            $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            // Verificar nuevamente justo antes de insertar (evitar condiciones de carrera)
            $sql_verify = "SELECT COUNT(*) FROM reservas WHERE fecha = :fecha_reserva AND mesa = :mesa";
            $stmt_verify = $conn->prepare($sql_verify);
            $stmt_verify->bindParam(":fecha_reserva", $fecha_reserva);
            $stmt_verify->bindParam(":mesa", $mesa);
            $stmt_verify->execute();
            
            if ($stmt_verify->fetchColumn() > 0) {
                $mensaje = "⚠️ Lo sentimos, alguien acaba de reservar ese horario. Por favor selecciona otro.";
                $tipo_mensaje = "warning";
                
                // Recargar horas ocupadas
                $stmt->execute();
                $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } else {
                // Insertar la reserva
                $sql_insert = "INSERT INTO reservas (nombre_cliente, telefono, mesa, fecha, estado) 
                               VALUES (:nombre, :telefono, :mesa, :fecha_reserva, 'reservado')";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindParam(":nombre", $nombre);
                $stmt_insert->bindParam(":telefono", $telefono);
                $stmt_insert->bindParam(":mesa", $mesa);
                $stmt_insert->bindParam(":fecha_reserva", $fecha_reserva);
                
                if ($stmt_insert->execute()) {
                    // Limpiar sesión
                    unset($_SESSION['mesa_seleccionada']);
                    
                    $mensaje = "✅ ¡Reserva confirmada exitosamente!<br>
                               <strong>Mesa:</strong> $mesa<br>
                               <strong>Fecha:</strong> " . date("d/m/Y") . "<br>
                               <strong>Hora:</strong> $hora<br>
                               <strong>Nombre:</strong> " . htmlspecialchars($nombre);
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "❌ Error al procesar la reserva. Intenta nuevamente.";
                    $tipo_mensaje = "danger";
                }
            }
        }
    }

} catch (PDOException $e) {
    $mensaje = "❌ Error de conexión. Por favor intenta más tarde.";
    $tipo_mensaje = "danger";
    error_log("Error en confirmar.php: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Reserva - Mesa <?= $mesa ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                
                <!-- Encabezado -->
                <div class="text-center mb-4">
                    <a href="../index.php" class="btn btn-light btn-sm">
                        ← Volver a la selección
                    </a>
                </div>

                <!-- Card principal -->
                <div class="card card-custom">
                    <div class="card-header bg-primary text-white text-center py-3">
                        <h2 class="mb-0"> Confirmar Reserva</h2>
                        <p class="mb-0 mt-2">Mesa #<?= $mesa ?></p>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <!-- Mensaje de resultado -->
                        <?php if($mensaje): ?>
                            <div class="alert alert-<?= $tipo_mensaje ?> alert-dismissible fade show" role="alert">
                                <?= $mensaje ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                            
                            <?php if($tipo_mensaje === 'success'): ?>
                                <div class="text-center mt-4">
                                    <a href="../index.php" class="btn btn-primary btn-lg">
                                        Hacer otra reserva
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Formulario (solo si no hay éxito) -->
                        <?php if($tipo_mensaje !== 'success'): ?>
                            <form method="POST" class="needs-validation" novalidate>
                                
                                <div class="mb-3">
                                    <label for="nombre" class="form-label fw-bold">
                                        👤 Nombre completo:
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-lg" 
                                           id="nombre"
                                           name="nombre" 
                                           placeholder="Ej: Juan Pérez"
                                           required
                                           minlength="3"
                                           maxlength="100"
                                           value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Por favor ingresa tu nombre completo.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label fw-bold">
                                        📱 Teléfono:
                                    </label>
                                    <input type="tel" 
                                           class="form-control form-control-lg" 
                                           id="telefono"
                                           name="telefono" 
                                           placeholder="Ej: 3001234567"
                                           required
                                           pattern="[0-9]{7,15}"
                                           value="<?= isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : '' ?>">
                                    <div class="invalid-feedback">
                                        Ingresa un teléfono válido (solo números, 7-15 dígitos).
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="hora" class="form-label fw-bold">
                                        🕒 Hora de reserva:
                                    </label>
                                    <select name="hora" 
                                            id="hora"
                                            class="form-select form-select-lg" 
                                            required>
                                        <option value="">-- Selecciona un horario --</option>
                                        <?php foreach($horarios as $h): ?>
                                            <?php if(in_array($h, $horas_ocupadas)): ?>
                                                <option value="<?= $h ?>" disabled class="hora-ocupada">
                                                    <?= $h ?> - ❌ No disponible
                                                </option>
                                            <?php else: ?>
                                                <option value="<?= $h ?>" <?= (isset($_POST['hora']) && $_POST['hora'] === $h) ? 'selected' : '' ?>>
                                                    <?= $h ?> - ✅ Disponible
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor selecciona un horario.
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        ✓ Confirmar Reserva
                                    </button>
                                    <a href="../index.php" class="btn btn-outline-secondary">
                                        Cancelar
                                    </a>
                                </div>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Info adicional -->
               

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de formulario Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>