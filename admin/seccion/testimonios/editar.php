<?php
include("../../bd.php");


if(isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("SELECT * FROM `testimonios` WHERE ID = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    
    if ($registro) {
        $opinion = $registro["opinion"];
        $nombre = $registro["nombre"];
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}


if($_POST){
    $opinion = (isset($_POST["opinion"])) ? $_POST["opinion"] : "";
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $txtID = (isset($_POST["txtID"])) ? $_POST["txtID"] : "";

    $sentencia = $conn->prepare("UPDATE `testimonios` SET `opinion` = :opinion, `nombre` = :nombre WHERE `id` = :id");

    $sentencia->bindParam(":opinion", $opinion);
    $sentencia->bindParam(":nombre", $nombre);
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
        <title>Testimonios - Editar</title>
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
            <div class="card-header">Editar Testimonio</div>
              <div class="card-body">
                <form action="?txtID=<?php echo htmlspecialchars($txtID); ?>" method="post">
                    <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">
                    <div class="mb-3">
                        <label for="opinion" class="form-label">Opinión:</label>
                        <input type="text" class="form-control" name="opinion" value="<?php echo htmlspecialchars($opinion); ?>" id="opinion" aria-describedby="helpId" placeholder="Escribe la opinión">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" id="nombre" aria-describedby="helpId" placeholder="Escribe el nombre">
                    </div>
                    <button type="submit" class="btn btn-success">Editar Testimonio</button>
                    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
              </div>
          </div>
        </div>
        <br>
    </body>
</html>

<?php include("../../templates/footer.php");?>