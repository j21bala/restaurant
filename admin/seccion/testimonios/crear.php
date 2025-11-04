<?php
include("../../bd.php");


if($_POST){
    // Get data from the form
    $opinion = (isset($_POST["opinion"])) ? $_POST["opinion"] : "";
    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";

    // Prepare the SQL statement, omitting the 'id' column as it's auto-incrementing
    $sentencia = $conn->prepare("INSERT INTO `testimonios` (`opinion`, `nombre`) VALUES (:opinion, :nombre)");

    // Bind the parameters
    $sentencia->bindParam(":opinion", $opinion);
    $sentencia->bindParam(":nombre", $nombre);

    // Execute the statement
    $sentencia->execute();
    
    // Redirect after successful creation
    header("Location:index.php");
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
    <head>
        <title>Testimonios - Crear</title>
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
            <div class="card-header">Crear Testimonio</div>
              <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="opinion" class="form-label">Opinión:</label>
                        <input type="text" class="form-control" name="opinion" id="opinion" aria-describedby="helpId" placeholder="Escribe la opinión">
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Escribe el nombre">
                    </div>
                    
                    <button type="submit" class="btn btn-success">Crear Testimonio</button>
                    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
              </div>
          </div>
        </div>
        <br>
    </body>
</html>

<?php include("../../templates/footer.php");?>