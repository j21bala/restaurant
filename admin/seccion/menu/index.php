<?php
// Include the database connection file.
include("../../bd.php");

// Logic to delete a record.
if(isset($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("DELETE FROM `menu` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    header("Location: index.php");
    exit;
}

// Logic to list all menu items.
try {
    $sentencia = $conn->prepare("SELECT * FROM `menu`");
    $sentencia->execute();
    $lista_menu = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los registros: " . $e->getMessage();
    $lista_menu = [];
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="es">
<head>
    <title>Administración del Menú</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<body>
    <header>
        </header>
    <main class="container mt-4">
        <div class="card">
            <div class="card-header">
                <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Plato</a>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre del plato</th>
                                <th scope="col">Ingredientes</th>
                                <th scope="col">Foto</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_menu as $plato) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($plato['id']); ?></td>
                                    <td><?php echo htmlspecialchars($plato['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($plato['ingredientes']); ?></td>
                                    <td>
                                        <?php if(!empty($plato['foto'])): ?>
                                            <img src="../../images/<?php echo htmlspecialchars($plato['foto']); ?>" alt="Foto del plato" width="80" class="img-thumbnail">
                                        <?php else: ?>
                                            <span class="text-muted">Sin foto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($plato['precio']); ?></td>
                                    <td>
                                        <a class="btn btn-info btn-sm" href="editar.php?txtID=<?php echo htmlspecialchars($plato['id']); ?>" role="button">Editar</a>
                                        <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo htmlspecialchars($plato['id']); ?>" role="button">Borrar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <footer>
        </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>