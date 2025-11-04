<?php
class Tree {
    private $conn;

    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }

    public function getCategoryTree() {
        $stmt = $this->conn->prepare("SELECT * FROM categorias ORDER BY nombre ASC");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categoryMap = [];
        foreach ($categories as $category) {
            $category['children'] = [];
            $categoryMap[$category['id']] = $category;
        }

        $tree = [];
        foreach ($categoryMap as $id => &$category) {
            if ($category['parent_id'] === null) {
                $tree[] = &$category;
            } else {
                if (isset($categoryMap[$category['parent_id']])) {
                     $categoryMap[$category['parent_id']]['children'][] = &$category;
                }
            }
        }
        return $tree;
    }

    public function displayTree($nodes) {
        if (empty($nodes)) {
            return;
        }

        echo '<ul>';
        foreach ($nodes as $node) {
            echo '<li>';
            echo '  <div class="category-row">';
            echo '    <span class="category-label">' . htmlspecialchars($node['nombre']) . '</span>';
            echo '    <div class="category-actions">';
            echo '      <a href="edit.php?id=' . $node['id'] . '" class="btn btn-sm btn-light" title="Editar Categoría"><i class="bi bi-pencil-fill"></i></a>';
            echo '      <a href="crear.php?parent=' . $node['id'] . '" class="btn btn-sm btn-success" title="Agregar Subcategoría"><i class="bi bi-plus-circle-fill"></i></a>';
            echo '      <a href="delete.php?id=' . $node['id'] . '" class="btn btn-sm btn-danger" title="Eliminar Categoría"><i class="bi bi-trash-fill"></i></a>';
            echo '    </div>';
            echo '  </div>';

            if (!empty($node['children'])) {
                $this->displayTree($node['children']);
            }

            echo '</li>';
        }
        echo '</ul>';
    }
}
?>