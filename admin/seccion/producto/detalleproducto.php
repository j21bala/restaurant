<?php 
// Contenido de admin/seccion/producto/detalleproducto.php
// Este archivo se incluye en index.php, por lo que tiene acceso a $plato.

if (!isset($plato)) {
    return; 
}

$plato_id = htmlspecialchars($plato['id']);
$nombre = htmlspecialchars($plato['nombre']);
$foto = htmlspecialchars($plato['foto']);
$ingredientes = htmlspecialchars($plato['ingredientes'] ?? '');
$precio = htmlspecialchars($plato['precio']);
?>

<div class="modal fade" id="platoDetalleModal-<?php echo $plato_id; ?>" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?php echo $nombre; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <div class="modal-body row">
        <div class="col-md-5">
          <img src="images/<?php echo $foto; ?>" class="img-fluid rounded" alt="<?php echo $nombre; ?>">
        </div>
        
        <div class="col-md-7">
            <h2><?php echo $nombre; ?></h2>
            <hr>
            
            <p class="lead">Ingredientes:</p>
            <p class="text-muted"><?php echo $ingredientes; ?></p>

            <h3 class="text-success my-4">Precio: $<?php echo number_format($plato['precio'], 2); ?></h3>

            <form action="admin/seccion/producto/procesar_carrito.php" method="POST">
                <input type="hidden" name="plato_id" value="<?php echo $plato_id; ?>">
                <input type="hidden" name="plato_nombre" value="<?php echo $nombre; ?>">
                <input type="hidden" name="plato_precio" value="<?php echo $precio; ?>">

                <div class="mb-3">
                    <label for="cantidad-<?php echo $plato_id; ?>" class="form-label">Cantidad:</label>
                    <input type="number" id="cantidad-<?php echo $plato_id; ?>" name="cantidad" class="form-control" value="1" min="1" style="width: 150px;" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg w-100 mt-3">
                    Agregar al carrito
                </button>
            </form>
            
            <div class="mt-3 text-end">
                <a href="admin/login.php" class="text-muted small">
                    Gestionar inventario
                </a>
            </div>
            
        </div>
      </div>
    </div>
  </div>
</div>