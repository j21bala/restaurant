<?php

include("bd.php");


if (isset($_POST["enviar"])) {

    $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : '';
    $mensaje = isset($_POST['mensaje']) ? htmlspecialchars(trim($_POST['mensaje'])) : '';

    if (!empty($nombre) && !empty($email) && !empty($mensaje)) {
        // SQL query to insert the data.
        $sql = "INSERT INTO contacto (nombre, email, telefono, mensaje) VALUES (:nombre, :email, :telefono, :mensaje)";


        $sentencia = $conn->prepare($sql);

        $sentencia->bindParam(":nombre", $nombre);
        $sentencia->bindParam(":email", $email);
        $sentencia->bindParam(":telefono", $telefono);
        $sentencia->bindParam(":mensaje", $mensaje);

       
        if ($sentencia->execute()) {
       
            header("Location: ../index.php?mensaje=Enviado");
            exit();
        } else {
      
            header("Location: ../index.php?mensaje=Error");
            exit();
        }
    } else {
 
        header("Location: ../index.php?mensaje=CamposVacios");
        exit();
    }
} else {

    header("Location: ../index.php");
    exit();
}
?>