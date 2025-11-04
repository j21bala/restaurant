<?php
include_once __DIR__ . '/grafo_mapa.php';

$camino_calculado = null;
$nodo_inicio = $_POST['inicio'] ?? 'Entrada'; // Default start
$nodo_fin = $_POST['fin'] ?? 'Mesa 11';      // Default end

if ($nodo_inicio && $nodo_fin) {
    $camino_calculado = $grafo_mesas->encontrarCaminoMasCorto($nodo_inicio, $nodo_fin);
}

// Helper to get all graph data for JavaScript
$adyacencias = [];
$todos_los_nodos = $grafo_mesas->obtenerNodos();
foreach($todos_los_nodos as $nodo) {
    $aristas = $grafo_mesas->obtenerAristas($nodo);
    if ($aristas) {
        $adyacencias[$nodo] = $aristas;
    }
}

// Helper to define CSS classes for nodes
function get_node_class($nodo, $camino) {
    if ($camino && in_array($nodo, $camino)) {
        // Highlight start and end points
        if ($nodo === ($camino[0] ?? null) || $nodo === (end($camino) ?? null)) {
            return 'btn-primary'; 
        }
        return 'btn-success'; // Highlight path
    }
    // Default styles for special nodes
    if ($nodo === 'Cocina' || $nodo === 'Entrada') {
        return 'btn-info';
    }
    return 'btn-secondary';
}

?>
<section id="mapa" class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-11 col-lg-9">
            <div class="card p-3 shadow-lg border-0 bg-light">
                <div class="card-body">
                    <h3 class="text-center mb-4">Calculadora de Ruta Más Corta</h3>

                    <!-- Formulario de Selección -->
                    <form method="POST" action="#mapa" class="row g-3 align-items-center justify-content-center mb-4">
                        <div class="col-md-4">
                            <label for="inicio" class="form-label fw-bold">Desde:</label>
                            <select id="inicio" name="inicio" class="form-select">
                                <?php foreach ($todos_los_nodos as $nodo): ?>
                                    <option value="<?= $nodo ?>" <?= ($nodo == $nodo_inicio) ? 'selected' : '' ?>><?= $nodo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="fin" class="form-label fw-bold">Hasta:</label>
                            <select id="fin" name="fin" class="form-select">
                                <?php foreach ($todos_los_nodos as $nodo): ?>
                                    <option value="<?= $nodo ?>" <?= ($nodo == $nodo_fin) ? 'selected' : '' ?>><?= $nodo ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mt-auto">
                            <button type="submit" class="btn btn-warning w-100">Encontrar Ruta</button>
                        </div>
                    </form>

                    <!-- Resultado del Camino -->
                    <?php if ($camino_calculado): ?>
                    <div class="text-center bg-white p-3 rounded border shadow-sm mb-4 mx-auto" style="max-width: 600px;">
                        <p class="mb-1 fw-bold">Ruta sugerida:</p>
                        <span class="text-success fw-bold fs-5"><?= implode(' → ', $camino_calculado) ?></span>
                    </div>
                    <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                    <div class="text-center bg-white p-3 rounded border shadow-sm mb-4">
                        <p class="mb-1 fw-bold text-danger">No se encontró una ruta directa.</p>
                    </div>
                    <?php endif; ?>

                    <!-- Contenedor del Mapa -->
                    <div class="mapa-restaurante-container" style="position: relative; max-width: 700px; margin: auto;">
                        <div id="mapa-layout" style="display: flex; flex-direction: column; gap: 30px; padding: 20px 0;">
                            
                            <!-- Cocina -->
                            <div class="text-center">
                                <?php $nodo = 'Cocina'; $clase = get_node_class($nodo, $camino_calculado); ?>
                                <div id="mapa-nodo-<?= $nodo ?>" class="btn <?= $clase ?> p-3 fs-5 fw-bold shadow-sm"><?= $nodo ?></div>
                            </div>

                            <!-- Mesas -->
                            <div class="mapa-restaurante" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
                                <?php 
                                $mesas = array_filter($todos_los_nodos, fn($n) => str_starts_with($n, 'Mesa'));
                                foreach ($mesas as $nodo):
                                    preg_match('/\d+/', $nodo, $matches);
                                    $numMesa = $matches[0];
                                    $clase = get_node_class($nodo, $camino_calculado);
                                ?>
                                <div id="mapa-nodo-<?= $nodo ?>" class="btn <?= $clase ?> p-3 fs-5 fw-bold shadow-sm d-flex align-items-center justify-content-center" style="height: 90px;">
                                    #<?= $numMesa ?>
                                </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Entrada -->
                             <div class="text-center">
                                <?php $nodo = 'Entrada'; $clase = get_node_class($nodo, $camino_calculado); ?>
                                <div id="mapa-nodo-<?= $nodo ?>" class="btn <?= $clase ?> p-3 fs-5 fw-bold shadow-sm"><?= $nodo ?></div>
                            </div>
                        </div>
                        <canvas id="mapa-canvas-conexiones" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('mapa-canvas-conexiones');
    const ctx = canvas.getContext('2d');
    const container = document.getElementById('mapa-layout');

    const adyacencias = <?= json_encode($adyacencias) ?>;
    const camino = <?= json_encode($camino_calculado) ?>;

    function getNodeId(nodo) {
        return `mapa-nodo-${nodo}`;
    }

    function dibujarConexion(nodo1, nodo2, color = '#adb5bd', lineWidth = 3) {
        const el1 = document.getElementById(getNodeId(nodo1));
        const el2 = document.getElementById(getNodeId(nodo2));
        
        if (!el1 || !el2) return;

        const containerRect = container.getBoundingClientRect();
        const r1 = el1.getBoundingClientRect();
        const r2 = el2.getBoundingClientRect();

        const x1 = r1.left + r1.width / 2 - containerRect.left;
        const y1 = r1.top + r1.height / 2 - containerRect.top;
        const x2 = r2.left + r2.width / 2 - containerRect.left;
        const y2 = r2.top + r2.height / 2 - containerRect.top;
        
        ctx.beginPath();
        ctx.moveTo(x1, y1);
        ctx.lineTo(x2, y2);
        ctx.strokeStyle = color;
        ctx.lineWidth = lineWidth;
        ctx.stroke();
    }

    function dibujarMapaBase() {
        const dibujadas = new Set();
        for (const origen in adyacencias) {
            for (const destino in adyacencias[origen]) {
                const edge = [origen, destino].sort().join('--');
                if (!dibujadas.has(edge)) {
                    dibujarConexion(origen, destino);
                    dibujadas.add(edge);
                }
            }
        }
    }

    function dibujarRuta(camino) {
        for (let i = 0; i < camino.length - 1; i++) {
            dibujarConexion(camino[i], camino[i+1], '#198754', 5); // Dark Green
        }
    }

    function dibujarTodo() {
        // Match canvas to layout dimensions
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        dibujarMapaBase();
        if (camino && camino.length > 1) {
            dibujarRuta(camino);
        }
    }

    // Initial drawing and redraw on resize
    dibujarTodo();
    window.addEventListener('resize', dibujarTodo);

    // If a route was calculated, scroll to the map
    if (camino) {
        document.getElementById('mapa').scrollIntoView({ behavior: 'smooth' });
    }
});
</script>