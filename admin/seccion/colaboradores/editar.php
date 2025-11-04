<?php
include("../../bd.php");

if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("SELECT * FROM `colaboradores` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if ($registro) {
        $nombre = $registro["nombre"];
        $descripcion = $registro["descripcion"];
        $linkfacebook = $registro["linkfacebook"];
        $linkinstagram = $registro["linkinstagram"];
        $linkyoutube = $registro["linkyoutube"];
        $foto = $registro["foto"];
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}


if ($_POST) {
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $descripcion = (isset($_POST["descripcion"])) ? $_POST["descripcion"] : "";
    $linkfacebook = (isset($_POST["linkfacebook"])) ? $_POST["linkfacebook"] : "";
    $linkinstagram = (isset($_POST["linkinstagram"])) ? $_POST["linkinstagram"] : "";
    $linkyoutube = (isset($_POST["linkyoutube"])) ? $_POST["linkyoutube"] : "";
    $foto = (isset($_POST["foto"])) ? $_POST["foto"] : "";
    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";

    $sentencia = $conn->prepare("UPDATE `colaboradores`
    SET `nombre` = :nombre, `descripcion` = :descripcion, `linkfacebook` = :linkfacebook, `linkinstagram` = :linkinstagram, `linkyoutube` = :linkyoutube, `foto` = :foto
    WHERE `id` = :id");

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":linkfacebook", $linkfacebook);
    $sentencia->bindParam(":linkinstagram", $linkinstagram);
    $sentencia->bindParam(":linkyoutube", $linkyoutube);
    $sentencia->bindParam(":foto", $foto);
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
    <title>Colaboradores - Editar</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<body>
    <br>
    <div class="container">
        <div class="card">
            <div class="card-header">
                Editar Colaborador
            </div>
            <div class="card-body">
                <form action="?txtID=<?php echo htmlspecialchars($txtID); ?>" method="post">
                    <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" id="nombre" placeholder="Nombre del colaborador">
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción:</label>
                        <input type="text" class="form-control" name="descripcion" value="<?php echo htmlspecialchars($descripcion); ?>" id="descripcion" placeholder="Descripción del colaborador">
                    </div>
                    <div class="mb-3">
                        <label for="linkfacebook" class="form-label">Enlace de Facebook:</label>
                        <input type="text" class="form-control" name="linkfacebook" value="<?php echo htmlspecialchars($linkfacebook); ?>" id="linkfacebook" placeholder="Enlace a Facebook">
                    </div>
                    <div class="mb-3">
                        <label for="linkinstagram" class="form-label">Enlace de Instagram:</label>
                        <input type="text" class="form-control" name="linkinstagram" value="<?php echo htmlspecialchars($linkinstagram); ?>" id="linkinstagram" placeholder="Enlace a Instagram">
                    </div>
                    <div class="mb-3">
                        <label for="linkyoutube" class="form-label">Enlace de YouTube:</label>
                        <input type="text" class="form-control" name="linkyoutube" value="<?php echo htmlspecialchars($linkyoutube); ?>" id="linkyoutube" placeholder="Enlace a YouTube">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Archivo de la Foto:</label>
                        <input type="file" class="form-control" name="foto" value="<?php echo htmlspecialchars($foto); ?>" id="foto" placeholder="Archivo de la foto del colaborador">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
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