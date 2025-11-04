<?php 
session_start();
include("bd.php");

if($_POST){
    $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : "";
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";
    
    // Use a prepared statement to prevent SQL injection
    $sentencia = $conn->prepare("SELECT * FROM `usuarios` WHERE usuario = :usuario");
    $sentencia->bindParam(":usuario", $usuario);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if($registro && password_verify($password, $registro['password'])){
        $_SESSION['usuario'] = $registro['usuario'];
        $_SESSION['logueado'] = true;
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Error: El usuario o la contraseña son incorrectos.";
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
        
    </head>

    <body>
        <main>
            <div class="container">
                <div class="row w-100">
                    <div class="col-md-4 mx-auto">
                        <div class="card">
                            <div class="card-header text-center">Login</div>
                            <div class="card-body">
                                <style>
                                    body {
                                        background-color: #f8f9fa;
                                    }
                                    .card {
                                        margin-top: 50px;
                                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                    }
                                    .card-header {
                                        background-color: #007bff;
                                        color: white;
                                        font-weight: bold;
                                    }
                                    .btn-primary {
                                        background-color: #007bff;
                                        border: none;
                                    }
                                    .btn-primary:hover {
                                        background-color: #0056b3;
                                    }
                                </style>
                                <?php if(isset($mensaje)) { ?>
                                    <div class="alert alert-danger" role="alert">
                                        <strong><?php echo $mensaje; ?></strong>
                                    </div>
                                <?php } ?>
                                <form action="login.php" method="post">
                                    <div class="mb-3">
                                        <label for="usuario" class="form-label">Usuario:</label>
                                        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Ingrese su usuario">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Contraseña:</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese su contraseña">
                                    </div>
                                    <button type="submit" class="btn btn-primary d-block w-100">Ingresar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>