<?php
include ("../../bd.php");


if($_POST){

    
    $titulo=(isset($_POST["titulo"]))?$_POST["titulo"]:"";
    $descripcion=(isset($_POST["Descripcion"]))?$_POST["Descripcion"]:""; 
    $enlace=(isset($_POST["Enlace"]))?$_POST["Enlace"]:""; 
    $foto=(isset($_POST["foto"]))?$_POST["foto"]:"";

    
    $sentencia=$conn->prepare("INSERT INTO `banner`
    (`id`, `titulo`, `descripcion`, `link`, `foto`)
    VALUES (NULL, :titulo, :descripcion, :link, :foto)");

    

    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":link", $enlace);
    $sentencia->bindParam(":foto", $foto);  

  
    $sentencia->execute();
    
    
    header("Location:index.php");
    
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
    <head>
        <title>Banners - Crear</title>
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </head>

<body>

    <br>

    <div class="container">
      <div class="card">
        <div class="card-header">Banner</div>
          <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Titulo:</label>
                    <input type="text" class="form-control" name="titulo" id="titulo" aria-describedby="helpId" placeholder="Escriba el título del banner">
                </div>

                <div class="mb-3">
                    <label for="Descripcion" class="form-label">Descripción:</label>
                    <input type="text" class="form-control" name="Descripcion" id="Descripcion" aria-describedby="helpId" placeholder="Escriba la descripción del banner">
                </div>

                <div class="mb-3">
                    <label for="Enlace" class="form-label">Enlace:</label>
                    <input type="text" class="form-control" name="Enlace" id="Enlace" aria-describedby="helpId" placeholder="Escriba el enlace del banner">
                </div>

                <div class="mb-3">
                        <label for="foto" class="form-label">Foto:</label>
                        <input type="file" class="form-control" name="foto" id="foto" placeholder="Foto del banner">
                 </div>

                <button type="submit" class="btn btn-success">Crear Banner</button>
                <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
            </form>
          </div>
      </div>
    </div>
    <br>
    </body>
</html>

<?php include("../../templates/footer.php");?>

