<?php
include("../../templates/header.php");
include("../../bd.php");

$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);

if (!$id) {
    header("Location: index.php");
    exit;
}

// Prevent deletion of the root category
if ($id == 1) {
    echo "<div class='alert alert-danger'>The root 'Menú' category cannot be deleted.</div>";
    echo '<a href="index.php" class="btn btn-primary">Return to Category Manager</a>';
    include("../../templates/footer.php");
    exit;
}

if ($_POST) {
    try {
        // The FOREIGN KEY with ON DELETE SET NULL will handle child categories,
        // making them root categories.
        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        header("Location: index.php");
        exit;
    } catch(PDOException $e) {
        echo "<div class='alert alert-danger'>Error deleting category: " . $e->getMessage() . "</div>";
    }

} else {
    $stmt = $conn->prepare("SELECT nombre FROM categorias WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $category_name = $stmt->fetchColumn();

    if ($category_name === false) {
        echo "<div class='alert alert-danger'>Category not found.</div>";
        include("../../templates/footer.php");
        exit;
    }
?>

<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        <h3>Confirmar Eliminación</h3>
    </div>
    <div class="card-body">
        <p>¿Está seguro de que desea eliminar la categoría <strong>'<?= htmlspecialchars($category_name) ?>'</strong>?</p>
        <p class="text-muted">Si esta categoría tiene subcategorías, no se eliminarán. Se convertirán en categorías principales.</p>
        <form action="delete.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <button type="submit" class="btn btn-danger">Sí, Eliminar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php
}

include("../../templates/footer.php"); 
?>
