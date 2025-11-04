<?php
include("../../templates/header.php");
include("../../bd.php");

$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);

if (!$id) {
    header("Location: index.php");
    exit;
}

if ($_POST) {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';

    if (!empty($nombre)) {
        try {
            $sql = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            header("Location: index.php");
            exit;
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error updating category: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}

$stmt = $conn->prepare("SELECT nombre FROM categorias WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$current_name = $stmt->fetchColumn();

if ($current_name === false) {
    echo "<div class='alert alert-danger'>Category not found.</div>";
    include("../../templates/footer.php");
    exit;
}

?>

<div class="card">
    <div class="card-header">
        <h3>Editar Categoría</h3>
    </div>
    <div class="card-body">
        <form action="edit.php" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nuevo Nombre de la Categoría:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($current_name) ?>" required>
            </div>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
