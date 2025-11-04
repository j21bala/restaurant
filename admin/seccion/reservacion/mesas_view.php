<?php
// Recibe: $mesas_reservadas_hoy (array) y $mesa_seleccionada (int)
// Retorna: HTML para la vista de selección de mesa
?>
<section id="reserva" class="container my-5">
    <h2 class="text-center mb-4">Selecciona tu Mesa</h2>
    <p class="text-center text-muted mb-5">Haz clic en una mesa <strong>Libre</strong> para seleccionarla y proceder con la reserva.</p>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card p-3 shadow-lg border-0 bg-light">
                <div class="card-body">
                    <div class="d-grid mx-auto" style="grid-template-columns: repeat(4, 1fr); gap: 10px;">
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <?php for ($j = 0; $j < 4; $j++): 
                                $numMesa = ($i * 4) + $j + 1;
                                $seleccionada = ($mesa_seleccionada == $numMesa);
                                
                                $estadoMesa = 'Libre';
                                $claseBoton = 'success';
                                $link = '?mesa=' . $numMesa;
                                $disabled = '';
                                
                                if (isset($mesas_reservadas_hoy[$numMesa])) {
                                    $estadoMesa = 'Ocupada';
                                    $claseBoton = 'danger';
                                    $link = 'javascript:void(0);';
                                    $disabled = 'disabled';
                                } elseif ($seleccionada) {
                                    $estadoMesa = 'Seleccionada';
                                    $claseBoton = 'warning';
                                    $link = '#reserva';
                                }
                            ?>
                                <a href="<?= $link ?>" 
                                   class="btn btn-<?= $claseBoton ?> p-3 fs-5 fw-bold shadow-sm d-flex flex-column align-items-center justify-content-center text-decoration-none <?= $disabled ?>"
                                   style="height: 90px; border-radius: 0.5rem; transition: all 0.3s ease; <?= $disabled ? 'cursor: not-allowed;' : '' ?>"
                                   <?= $disabled ? 'aria-disabled="true" tabindex="-1"' : '' ?>>
                                    <span class="d-block">Mesa #<?= $numMesa ?></span>
                                    <small class="mt-1 text-uppercase fw-normal" style="font-size: 0.75rem;"><?= $estadoMesa ?></small>
                                </a>
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="d-flex justify-content-center gap-4 mt-4 text-muted">
                <span class="d-flex align-items-center gap-1"><span class="badge bg-success">🟢</span> Libre</span>
                <span class="d-flex align-items-center gap-1"><span class="badge bg-warning">🟡</span> Seleccionada</span>
                <span class="d-flex align-items-center gap-1"><span class="badge bg-danger">🔴</span> Ocupada</span>
            </div>
        </div>
    </div>

    <!-- Panel de confirmación -->
    <?php if ($mesa_seleccionada && !isset($mesas_reservadas_hoy[$mesa_seleccionada])): ?>
        <div class="text-center mt-5 p-4 rounded-3 bg-white border shadow mx-auto animate-fade-in" style="max-width: 450px;">
            <div class="mb-3">
                <p class="lead mb-2 text-secondary">Mesa seleccionada:</p>
                <h3 class="display-4 text-primary fw-bold mb-0">#<?= htmlspecialchars($mesa_seleccionada) ?></h3>
            </div>
            <div class="d-grid gap-2">
                <a href="admin/confirmar.php?mesa=<?= htmlspecialchars($mesa_seleccionada) ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Confirmar Reserva
                </a>
                <a href="?mesa_seleccionada=" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar Selección
                </a>
            </div>
        </div>
    <?php endif; ?>
</section>