<?php
include("../../bd.php");


if($_POST){
    $usuario = (isset($_POST["usuario"])) ? $_POST["usuario"] : "";
    $password = (isset($_POST["password"])) ? $_POST["password"] : "";
    
    
   
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sentencia = $conn->prepare("INSERT INTO `usuarios` (`id`, `usuario`, `password`) VALUES (NULL, :usuario, :password)");

    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->bindParam(":password", $hashed_password);
   

    $sentencia->execute();
    
    header("Location:index.php");
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
    <head>
        <title>Usuarios - Crear</title>
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
            <div class="card-header">Crear Usuario</div>
              <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario:</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Nombre de usuario">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña:</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Escriba la contraseña">
                    </div>
                    
                    
                    <button type="submit" class="btn btn-success">Crear Usuario</button>
                    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
              </div>
          </div>
        </div>
        <br>
    </body>
</html>

<?php include("../../templates/footer.php");?>