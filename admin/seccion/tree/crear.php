<?php
include("../../templates/header.php");
include("../../bd.php");

if ($_POST) {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    if (!empty($nombre)) {
        try {
            $sql = "INSERT INTO categorias (nombre, parent_id) VALUES (:nombre, :parent_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':parent_id', $parent_id, $parent_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
            $stmt->execute();
            
            header("Location: index.php");
            exit;
        } catch(PDOException $e) {
            echo "<div class='alert alert-danger'>Error creating category: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Category name cannot be empty.</div>";
    }
}

$parent_id = isset($_GET['parent']) ? $_GET['parent'] : '';
$parent_name = '';
if (!empty($parent_id)) {
    $stmt = $conn->prepare("SELECT nombre FROM categorias WHERE id = :id");
    $stmt->bindParam(':id', $parent_id, PDO::PARAM_INT);
    $stmt->execute();
    $parent_name = $stmt->fetchColumn();
}

?>

<div class="card">
    <div class="card-header">
        <h3>Crear Nueva Categoría</h3>
    </div>
    <div class="card-body">
        <?php if(!empty($parent_name)): ?>
            <p class="text-muted">Creando una subcategoría bajo <strong><?= htmlspecialchars($parent_name) ?></strong>.</p>
        <?php endif; ?>
        <form action="crear.php" method="post">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre de la Categoría:</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required>
            </div>
            <input type="hidden" name="parent_id" value="<?= htmlspecialchars($parent_id) ?>">
            <button type="submit" class="btn btn-success">Crear Categoría</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
