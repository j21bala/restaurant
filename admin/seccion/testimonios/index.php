<?php
include("../../bd.php");


if(isset($_GET['txtID']) && !empty($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("DELETE FROM `testimonios` WHERE ID = ?");
    $sentencia->execute([$txtID]);
    header("Location: index.php");
    exit;
}

try {
    $sentencia = $conn->prepare("SELECT * FROM `testimonios`");
    $sentencia->execute();
    $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching records: " . $e->getMessage();
    $resultado = []; 
}
?>
<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="en">
    <head>
        <title>Testimonios - Listado</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    </head>
    <body>
        <div class="container mt-4">
            <div class="card">
                <div class="card-header">
                    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar testimonio</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Opinión</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($resultado as $value) { ?>
                                <tr class="">
                                <td scope="row"><?php echo htmlspecialchars($value["id"]); ?></td>
                                <td><?php echo htmlspecialchars($value["opinion"]); ?></td>
                                <td><?php echo htmlspecialchars($value["nombre"]); ?></td>
                                <td>
                                <a name="" id="" class="btn btn-info" href="editar.php?txtID=<?php echo htmlspecialchars($value['id']);?>" role="button">Editar</a>
                                <a name="" id="" class="btn btn-danger" href="index.php?txtID=<?php echo htmlspecialchars($value['id']);?>" role="button">Borrar</a>
                                </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    </body>
</html>

<?php include("../../templates/footer.php");?>