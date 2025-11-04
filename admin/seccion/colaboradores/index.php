<?php

include("../../bd.php");


if(isset($_GET['txtID'])) {
    $txtID = (int)$_GET['txtID'];
    $sentencia = $conn->prepare("DELETE FROM `colaboradores` WHERE id = :id");
    $sentencia->bindParam(":id", $txtID, PDO::PARAM_INT);
    $sentencia->execute();
    header("Location: index.php");
    exit;
}


try {
    $sentencia = $conn->prepare("SELECT * FROM `colaboradores`");
    $sentencia->execute();
    $lista_colaboradores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los registros: " . $e->getMessage();
    $lista_colaboradores = [];
}
?>

<?php include("../../templates/header.php");?>

<!doctype html>
<html lang="es">
<head>
    <title>Colaboradores</title>
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
                <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Agregar Colaborador</a>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Foto</th>
                                <th scope="col">Enlace Facebook</th>
                                <th scope="col">Enlace Instagram</th>
                                <th scope="col">Enlace YouTube</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_colaboradores as $colaborador) { ?>
                                <tr>
    <td><?php echo htmlspecialchars($colaborador['id']); ?></td>
    <td><?php echo htmlspecialchars($colaborador['nombre']); ?></td>
    <td><?php echo htmlspecialchars($colaborador['descripcion']); ?></td>
    <td>
        <?php if(!empty($colaborador['foto'])): ?>
            
            <img src="<?php echo htmlspecialchars($colaborador['foto']); ?>" 
                 alt="Foto de <?php echo htmlspecialchars($colaborador['nombre']); ?>" 
                 width="80" class="img-thumbnail">
        <?php else: ?>
            <span class="text-muted">Sin foto</span>
        <?php endif; ?>
    </td>
    <td>
        <?php if(!empty($colaborador['linkfacebook'])): ?>
            <a href="<?php echo htmlspecialchars($colaborador['linkfacebook']); ?>" 
               target="_blank" class="btn btn-primary btn-sm">Facebook</a>
        <?php endif; ?>
    </td>
    <td>
        <?php if(!empty($colaborador['linkinstagram'])): ?>
            <a href="<?php echo htmlspecialchars($colaborador['linkinstagram']); ?>" 
               target="_blank" class="btn btn-danger btn-sm">Instagram</a>
        <?php endif; ?>
    </td>
    <td>
        <?php if(!empty($colaborador['linkyoutube'])): ?>
            <a href="<?php echo htmlspecialchars($colaborador['linkyoutube']); ?>" 
               target="_blank" class="btn btn-dark btn-sm">YouTube</a>
        <?php endif; ?>
    </td>
    <td>
        <a class="btn btn-info btn-sm" href="editar.php?txtID=<?php echo htmlspecialchars($colaborador['id']); ?>" role="button">Editar</a>
        <a class="btn btn-danger btn-sm" href="index.php?txtID=<?php echo htmlspecialchars($colaborador['id']); ?>" role="button">Borrar</a>
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