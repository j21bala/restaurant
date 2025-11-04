<?php
// Recibe: $visitas_stack (VisitasRecientesStack)
//         $lista_menu (array de todos los platos)
?>
<section id="stack-vistas" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Platos Vistos Recientemente</h5>
                    <span class="badge bg-primary rounded-pill">Total: <?= $visitas_stack->size() ?></span>
                </div>
                <div class="card-body p-4">
                    <?php if ($visitas_stack->isEmpty()): ?>
                        <div class="text-center text-muted">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <p class="mt-2">Aún no has visto ningún plato. ¡Explora nuestro menú!</p>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center mb-4">
                            El último plato que viste fue <strong class="text-dark">"<?= htmlspecialchars(get_nombre_plato($lista_menu, $visitas_stack->peek())) ?>"</strong>. 
                            A continuación, el historial de tus visitas.
                        </p>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($visitas_stack->getStack() as $index => $plato_id): 
                                $nombre_plato = get_nombre_plato($lista_menu, $plato_id);
                            ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center animate-fade-in">
                                    <span class="fw-bold">
                                        <?= htmlspecialchars($nombre_plato) ?>
                                        <?php if ($index === 0): // Es el elemento en la cima (peek) ?>
                                            <span class="badge bg-warning text-dark ms-2">Último Visto</span>
                                        <?php endif; ?>
                                    </span>
                                    <a href="admin/seccion/stack/pop_vista.php" class="btn btn-outline-danger btn-sm" title="Eliminar de la lista">
                                        <i class="bi bi-x-circle"></i> Quitar
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
function get_nombre_plato($menu, $id) {
    foreach ($menu as $plato) {
        if ($plato['id'] == $id) {
            return $plato['nombre'];
        }
    }
    return 'Plato Desconocido';
}
?>