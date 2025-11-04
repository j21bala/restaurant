<?php

include("../../bd.php");


if (isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("SELECT * FROM `menu` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    $registro = $sentencia->fetch(PDO::FETCH_LAZY);

    if ($registro) {
        $nombre = $registro["nombre"];
        $ingredientes = $registro["ingredientes"];
        $precio = $registro["precio"];
        $foto_actual = $registro["foto"];
    } else {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}


if ($_POST) {
    $txtID = $_POST["txtID"] ?? "";
    $nombre = $_POST["nombre"] ?? "";
    $ingredientes = $_POST["ingredientes"] ?? "";
    $precio = $_POST["precio"] ?? "";
    $foto_nueva = $_POST["foto"] ?? $foto_actual;

    $sentencia = $conn->prepare("UPDATE `menu`
        SET `nombre` = :nombre, 
            `ingredientes` = :ingredientes, 
            `precio` = :precio, 
            `foto` = :foto
        WHERE `id` = :id");

    $sentencia->bindParam(":nombre", $nombre);
    $sentencia->bindParam(":ingredientes", $ingredientes);
    $sentencia->bindParam(":precio", $precio);
    $sentencia->bindParam(":foto", $foto_nueva);
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);

    $sentencia->execute();
    header("Location: index.php");
    exit;
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="es">
<head>
    <title>Editar Plato</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <main class="container mt-4">
        <div class="card">
            <div class="card-header">
                Editar Plato de Menú
            </div>
            <div class="card-body">
                <form action="?txtID=<?php echo htmlspecialchars($txtID); ?>" method="post">
                    <input type="hidden" name="txtID" value="<?php echo htmlspecialchars($txtID); ?>">

                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del plato:</label>
                        <input type="text" class="form-control" name="nombre" 
                               value="<?php echo htmlspecialchars($nombre); ?>" id="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="ingredientes" class="form-label">Ingredientes:</label>
                        <textarea class="form-control" name="ingredientes" id="ingredientes" rows="3"><?php echo htmlspecialchars($ingredientes); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio:</label>
                        <input type="text" class="form-control" name="precio" 
                               value="<?php echo htmlspecialchars($precio); ?>" id="precio">
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Archivo de la foto:</label>
                        <?php if (!empty($foto_actual)): ?>
                            <p>Foto actual:<br> 
                                <img  src="<?php echo htmlspecialchars($foto_actual); ?>" alt="Foto del plato" width="120" class="img-thumbnail">
                            </p>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="foto" id="foto" 
                               value="<?php echo htmlspecialchars($foto_actual); ?>">
                    </div>
                    <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    <a class="btn btn-primary" href="index.php" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
