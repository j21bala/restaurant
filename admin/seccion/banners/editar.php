<?php

include("../../bd.php");


if(isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("SELECT * FROM `banner` WHERE ID = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);
    
    if ($registro) {
        $titulo = $registro["titulo"];
        $descripcion = $registro["descripcion"];
        $link = $registro["link"];
        $foto = $registro["foto"];
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}


if($_POST){



    $titulo=(isset($_POST["titulo"]))?$_POST["titulo"]:"";
    $descripcion=(isset($_POST["Descripcion"]))?$_POST["Descripcion"]:"";
    $link=(isset($_POST["Enlace"]))?$_POST["Enlace"]:""; 
    $foto=(isset($_POST["foto"]))?$_POST["foto"]:"";
    $txtID=(isset($_POST["txtID"]))?$_POST["txtID"]:"";

    $sentencia=$conn->prepare("UPDATE `banner`
    SET `titulo` = :titulo, `descripcion` = :descripcion, `link` = :link, `foto` = :foto
    WHERE `id` = :id");

    $sentencia->bindParam(":titulo", $titulo);
    $sentencia->bindParam(":descripcion", $descripcion);
    $sentencia->bindParam(":link", $link);
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
        <title>Banners - Editar</title>
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

            <form action="?txtID=<?php echo htmlspecialchars($txtID); ?>" method="post">
    <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">

    <div class="mb-3">
        <label for="titulo" class="form-label">Titulo:</label>
        <input type="text" class="form-control" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" id="titulo" aria-describedby="helpId" placeholder="Escriba el título del banner">
    </div>

    <div class="mb-3">
        <label for="Descripcion" class="form-label">Descripción:</label>
        <input type="text" class="form-control" name="Descripcion" value="<?php echo htmlspecialchars($descripcion); ?>" id="Descripcion" aria-describedby="helpId" placeholder="Escriba la descripción del banner">
    </div>

    <div class="mb-3">
        <label for="Enlace" class="form-label">Enlace:</label>
        <input type="text" class="form-control" name="Enlace" value="<?php echo htmlspecialchars($link); ?>" id="Enlace" aria-describedby="helpId" placeholder="Escriba el enlace del banner">
    </div>

    <div class="mb-3">
        <label for="foto" class="form-label">Archivo de la Foto:</label>
        <input type="file" class="form-control" name="foto" value="<?php echo htmlspecialchars($foto); ?>" id="foto" placeholder="Archivo de la foto del colaborador">
    </div>

    <button type="submit" class="btn btn-success">Editar Banner</button>
    <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
</form>
          </div>
      </div>
    </div>
</br>

    </body>
</html>

<?php include("../../templates/footer.php");?>