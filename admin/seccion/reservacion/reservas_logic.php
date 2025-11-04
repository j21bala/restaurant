<?php
function obtener_estado_mesas($conn) {
    $fecha_hoy = date("Y-m-d");
    $mesas_reservadas_hoy = [];
    try {
        $horarios_totales = 9; // Número de bloques de horarios para considerar una mesa 'ocupada'
        $sql = "SELECT mesa, COUNT(*) as total_reservas 
                FROM reservas 
                WHERE DATE(fecha) = :fecha 
                GROUP BY mesa 
                HAVING total_reservas >= :total_horarios";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":fecha", $fecha_hoy);
        $stmt->bindParam(":total_horarios", $horarios_totales, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultado as $fila) {
            $mesas_reservadas_hoy[$fila['mesa']] = true;
        }
    } catch (PDOException $e) {
        error_log("Error al consultar mesas reservadas: " . $e->getMessage());
        $mesas_reservadas_hoy = [];
    }
    return $mesas_reservadas_hoy;
}
?>