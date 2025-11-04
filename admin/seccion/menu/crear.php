<?php

include("../../bd.php");

if ($_POST) {

    $nombre = (isset($_POST["nombre"])) ? $_POST["nombre"] : "";
    $ingredientes = (isset($_POST["ingredientes"])) ? $_POST["ingredientes"] : "";
    $precio = (isset($_POST["precio"])) ? $_POST["precio"] : "";
    $foto = (isset($_POST["foto"])) ? $_POST["foto"] : ""; // Ahora viene como URL o ruta


    $sentencia = $conn->prepare("INSERT INTO `menu` (`nombre`, `ingredientes`, `precio`, `foto`) 
                                 VALUES (:nombre, :ingredientes, :precio, :foto)");

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":ingredientes", $ingredientes);
    $sentencia->bindParam(":precio", $precio);
    $sentencia->bindParam(":foto", $foto);

    $sentencia->execute();
    header("Location: index.php");
    exit;
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="es">
<head>
    <title>Crear Plato</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <main class="container mt-4">
        <div class="card">
            <div class="card-header">
                Crear Plato de Menú
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del plato:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del plato">
                    </div>
                    <div class="mb-3">
                        <label for="ingredientes" class="form-label">Ingredientes:</label>
                        <textarea class="form-control" name="ingredientes" id="ingredientes" rows="3" placeholder="Ingredientes del plato"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="text" class="form-control" name="precio" id="precio" placeholder="Precio">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Archivo de la Foto:</label>
                        <input type="file" class="form-control" name="foto" id="foto">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar</button>
                    <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
