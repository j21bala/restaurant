<?php
include("../../templates/header.php");
include("../../bd.php");
include_once('tree.php');

$tree_manager = new Tree($conn);
$category_tree = $tree_manager->getCategoryTree();

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">

<style>
.category-manager {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.tree, .tree ul {
    list-style-type: none;
    padding-left: 30px; 
    margin: 0;
}

.tree {
    padding-left: 0;
}

.tree ul {
    border-left: 2px solid #e9ecef;
    margin-left: 10px;
    padding-top: 10px;
}

.tree li {
    position: relative;
    padding-top: 5px;
}

.tree li::before {
    content: '';
    position: absolute;
    top: 25px;
    left: -20px;
    width: 18px;
    height: 2px;
    background-color: #e9ecef;
}

.category-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    transition: box-shadow 0.2s, transform 0.2s;
    margin-bottom: 10px;
    border: 1px solid #f0f0f0;
}

.category-row:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.category-label {
    font-weight: 500;
    color: #343a40;
}

.category-actions {
    display: flex;
    gap: 8px;
    opacity: 0;
    transition: opacity 0.2s;
}

.category-row:hover .category-actions {
    opacity: 1;
}

.category-actions .btn {
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}
</style>

<div class="card category-manager">
    <div class="card-header bg-light">
        <h3 class="mb-0">Administrador de Categorías</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">Pase el cursor sobre una categoría para ver las acciones. Use los botones para editar, agregar o eliminar categorías.</p>
        <a href="crear.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Agregar Categoría Principal</a>
        <div class="tree">
            <?php $tree_manager->displayTree($category_tree); ?>
        </div>
    </div>
</div>

<?php include("../../templates/footer.php"); ?>
