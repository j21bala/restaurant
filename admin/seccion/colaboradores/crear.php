<?php
include("../../bd.php");

if ($_POST) {
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $descripcion = (isset($_POST["descripcion"])) ? $_POST["descripcion"] : "";
    $linkfacebook = (isset($_POST["linkfacebook"])) ? $_POST["linkfacebook"] : "";
    $linkinstagram = (isset($_POST["linkinstagram"])) ? $_POST["linkinstagram"] : "";
    $linkyoutube = (isset($_POST["linkyoutube"])) ? $_POST["linkyoutube"] : "";
    $foto = (isset($_POST["foto"])) ? $_POST["foto"] : "";

    $sentencia = $conn->prepare("INSERT INTO `colaboradores` (`id`, `nombre`, `descripcion`, `linkfacebook`, `linkinstagram`, `linkyoutube`, `foto`) VALUES (NULL, :nombre, :descripcion, :linkfacebook, :linkinstagram, :linkyoutube, :foto)");

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":linkfacebook", $linkfacebook);
    $sentencia->bindParam(":linkinstagram", $linkinstagram);
    $sentencia->bindParam(":linkyoutube", $linkyoutube);
    $sentencia->bindParam(":foto", $foto);

    $sentencia->execute();
    header("Location:index.php");
    exit;
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
<head>
    <title>Colaboradores - Crear</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<body>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Crear Colaborador
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del colaborador">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="Descripción del colaborador">
                    </div>
                    <div class="mb-3">
                        <label for="linkfacebook" class="form-label">Enlace de Facebook:</label>
                        <input type="text" class="form-control" name="linkfacebook" id="linkfacebook" placeholder="Enlace a Facebook">
                    </div>
                    <div class="mb-3">
                        <label for="linkinstagram" class="form-label">Enlace de Instagram:</label>
                        <input type="text" class="form-control" name="linkinstagram" id="linkinstagram" placeholder="Enlace a Instagram">
                    </div>
                    <div class="mb-3">
                        <label for="linkyoutube" class="form-label">Enlace de YouTube:</label>
                        <input type="text" class="form-control" name="linkyoutube" id="linkyoutube" placeholder="Enlace a YouTube">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">URL de la Foto:</label>
                        <input type="text" class="form-control" name="foto" id="foto" placeholder="URL de la foto del colaborador">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
    <br>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>

<?php include("../../templates/footer.php");?>