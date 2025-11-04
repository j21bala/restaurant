<?php
include("../../bd.php");


// Fetch the record to be edited
if(isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("SELECT * FROM `usuarios` WHERE ID = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    
    if ($registro) {
        $usuario = $registro["usuario"];
        $correo = $registro["correo"];
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}


// Handle the form submission for updating
if($_POST){
    $usuario = (isset($_POST["usuario"])) ? $_POST["usuario"] : "";
    $password = (isset($_POST["password"])) ? $_POST["password"] : "";
    $correo = (isset($_POST["correo"])) ? $_POST["correo"] : "";
    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";

    // Build the SQL query dynamically
    if (!empty($password)) {
        // If a new password is provided, hash it and include it in the update
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sentencia = $conn->prepare("UPDATE `usuarios` SET `usuario` = :usuario, `password` = :password, `correo` = :correo WHERE `id` = :id");
        $sentencia->bindParam(":password", $hashed_password);
    } else {
        // If no new password is provided, update without changing the password
        $sentencia = $conn->prepare("UPDATE `usuarios` SET `usuario` = :usuario, `correo` = :correo WHERE `id` = :id");
    }

    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":correo", $correo);
    $sentencia->bindParam(":id", $txtID);

    $sentencia->execute();
    
    header("Location:index.php");
    exit;
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
    <head>
        <title>Usuarios - Editar</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </head>

    <body>
        <br>
        <div class="container">
          <div class="card">
            <div class="card-header">Editar Usuario</div>
              <div class="card-body">
                <form action="?txtID=<?php echo htmlspecialchars($txtID); ?>" method="post">
                    <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" id="usuario" placeholder="Nombre de usuario">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Nueva contraseña (dejar en blanco para no cambiarla)">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo:</label>
                        <input type="email" class="form-control" name="correo" value="<?php echo htmlspecialchars($correo); ?>" id="correo" placeholder="Correo del usuario">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
              </div>
          </div>
        </div>
        <br>
    </body>
</html>

<?php include("../../templates/footer.php");?>