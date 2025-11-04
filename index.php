<?php 
session_start(); 

include("admin/bd.php");
include("admin/seccion/reservacion/reservas_logic.php");
include("admin/seccion/stack/stack_logic.php");



// 1. GESTIÓN DE LA MESA SELECCIONADA
if (isset($_GET['mesa'])) {
    $mesa_url = intval($_GET['mesa']);
    $_SESSION['mesa_seleccionada'] = ($mesa_url >= 1 && $mesa_url <= 16) ? $mesa_url : null;
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
} elseif (isset($_GET['mesa_seleccionada']) && $_GET['mesa_seleccionada'] === '') {
    unset($_SESSION['mesa_seleccionada']);
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}

// 2. OBTENER DATOS
$mesas_reservadas_hoy = obtener_estado_mesas($conn);
$mesa_seleccionada = $_SESSION['mesa_seleccionada'] ?? null;
$carrito = $_SESSION['carrito'] ?? [];
$visitas_stack = new VisitasRecientesStack(5);


// 3. MANEJO DE MENSAJES
$mensaje_error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
$mensaje_exitoso = $_SESSION['pedido_exitoso'] ?? '';
unset($_SESSION['pedido_exitoso']);

// 4. CONSULTAS PARA EL CONTENIDO DE LA PÁGINA
$listabanner = $conn->query("SELECT * FROM `banner` LIMIT 1")->fetchAll(PDO::FETCH_ASSOC);
$lista_colaboradores = $conn->query("SELECT * FROM `colaboradores`")->fetchAll(PDO::FETCH_ASSOC);
$lista_testimonios = $conn->query("SELECT * FROM `testimonios`")->fetchAll(PDO::FETCH_ASSOC);
$lista_menu = $conn->query("SELECT * FROM `menu`")->fetchAll(PDO::FETCH_ASSOC);



?>
<!doctype html>
<html lang="es">
    <head>
        <title>Sendai Restaurant - Inicio</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
        <style>
            .social-icons a { color: #6c757d; font-size: 1.2rem; margin: 0 8px; transition: color 0.3s ease; }
            .social-icons a:hover { color: #007bff; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
            .animate-fade-in { animation: fadeIn 0.4s ease-out; }
            .btn-success:not(.disabled):hover { box-shadow: 0 6px 20px rgba(25, 135, 84, 0.4); transform: scale(1.05) !important; }
            .btn-warning:hover { box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4); transform: scale(1.05) !important; }
            .btn-danger.disabled { opacity: 0.6; }
        </style>
    </head>
    <body>
        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Sendai Restaurant</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="#banner">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#recomendados">Menú del día</a></li>
                        <li class="nav-item"><a class="nav-link" href="#chef">Chef</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonios">Testimonios</a></li>
                        <li class="nav-item"><a class="nav-link" href="#reserva">Reservas</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <?php $num_items = array_sum(array_column($carrito, 'cantidad')); ?>
                            <button class="btn btn-outline-light position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCarrito" aria-controls="offcanvasCarrito">
                                <i class="bi bi-cart"></i> Mi Orden
                                <?php if ($num_items > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $num_items ?></span>
                                <?php endif; ?>
                            </button>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="admin/login.php"><i class="bi bi-person-fill"></i> Iniciar Sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <?php if ($mensaje_exitoso): ?>
            <div class="container mt-4">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Éxito:</strong> <?= htmlspecialchars($mensaje_exitoso) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($mensaje_error): ?>
            <div class="container mt-4">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>⚠️</strong> <?= htmlspecialchars($mensaje_error) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <!-- BANNER -->
        <section id="banner" class="container-fluid p-0">
            <?php foreach($listabanner as $banner): ?>
                <div class="banner-img" style="position:relative; height:400px; overflow: hidden;">
                    <img src="images/<?= htmlspecialchars($banner['foto']); ?>" class="card-img-top" alt="Banner" style="height: 400px; object-fit: cover;">
                    <div class="banner-text" style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center; color: white; z-index: 10; width: 100%;">
                        <h1><?= htmlspecialchars($banner['titulo']);?></h1>
                        <p><?= htmlspecialchars($banner['descripcion']);?></p>
                        <a href="#reserva" class="btn btn-primary" style="margin-top: 20px;">Reserva tu mesa</a>
                    </div>
                </div>
            <?php break; endforeach; ?>
        </section>

        <!-- CHEFS -->
        <section id="chef" class="bg-light py-5">
            <div class="container">
                <h2 class="text-center mb-4">Nuestros Chefs</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach($lista_colaboradores as $colaborador): ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="images/<?= htmlspecialchars($colaborador['foto']); ?>" class="card-img-top" alt="<?= htmlspecialchars($colaborador['nombre']); ?>" style="height: 400px; object-fit: cover;">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($colaborador['nombre']); ?></h5>
                                <p class="card-text small"><?= htmlspecialchars($colaborador['descripcion']); ?></p>
                                <div class="social-icons mt-3">
                                    <a href="<?= htmlspecialchars($colaborador['linkfacebook']); ?>" title="Facebook"><i class="bi bi-facebook"></i></a>
                                    <a href="<?= htmlspecialchars($colaborador['linkinstagram']); ?>" title="Instagram"><i class="bi bi-instagram"></i></a>
                                    <a href="<?= htmlspecialchars($colaborador['linkyoutube']); ?>" title="YouTube"><i class="bi bi-youtube"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- TESTIMONIOS -->
        <section id="testimonios" class="bg-light py-5">
            <div class="container"> 
                <h2 class="text-center mb-4">Testimonios</h2>
                <div class="row">
                    <?php foreach($lista_testimonios as $testimonio): ?>
                    <div class="col-md-6 d-flex">
                        <div class="card mb-4 w-100">
                            <div class="card-body">
                                <p class="card-text"><?= htmlspecialchars($testimonio['opinion']); ?></p>
                            </div>
                            <div class="card-footer text-muted"><?= htmlspecialchars($testimonio['nombre']); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div> 
        </section>

        <!-- MENÚ RECOMENDADOS -->
        <section id="recomendados" class="py-5"> 
            <div class="container">
                <h2 class="text-center mb-4">Recomendados</h2>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                    <?php foreach($lista_menu as $plato): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                        
                            <img src="images/<?= htmlspecialchars($plato['foto']); ?>" class="card-img-top" alt="<?= htmlspecialchars($plato['nombre']); ?>" style="height: 200px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#platoDetalleModal-<?= htmlspecialchars($plato['id']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($plato['nombre']); ?></h5>
                                <p class="card-text small"><?= htmlspecialchars($plato['ingredientes']); ?></p>
                                <p class="card-text"><strong>Precio: $<?= number_format($plato['precio'], 2); ?></strong></p>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#platoDetalleModal-<?= htmlspecialchars($plato['id']); ?>" onclick="registrarVista(<?= $plato['id']; ?>)">Ver y Comprar</button>
                            </div>
                        </div>
                    </div>
                    <?php include 'admin/seccion/producto/detalleproducto.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div> 
        </section>

        <!-- ALGORITMO VORAZ DE CAMBIO -->
        <section id="cambio-voraz" class="py-5 bg-light">
            <div class="container">
                <h2 class="text-center mb-4">Calculadora de Cambio</h2>
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Cambio para un total de $<?= number_format($total_cambio, 2) ?></h5>
                                <p class="card-text">Utilizando un algoritmo voraz, el cambio se distribuye de la siguiente manera:</p>
                                <ul>
                                    <?php foreach ($cambio as $denominacion => $cantidad): ?>
                                        <li><strong>$<?= number_format((float)$denominacion, 2) ?>:</strong> <?= $cantidad ?> unidad(es)</li>
                                    <?php endforeach; ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN DE RESERVAS -->
        <?php include 'admin/seccion/reservacion/mesas_view.php'; ?>

        <!-- MAPA DEL RESTAURANTE -->
        <?php include 'admin/seccion/grafos/mapa_view.php'; ?>

        <!-- STACK DE VISTAS -->
        <?php include 'admin/seccion/stack/stack_view.php'; ?>

        <!-- CONTACTO -->
        <section id="contacto" class="container mt-4 mb-5">
            <h2>Contacto</h2>
            <p>Para cualquier consulta o pedido, no dudes en contactarnos.</p>
            <form action="admin/enviar_mensaje.php" method="post">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese su nombre" required><br>
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese su correo electrónico" required><br>
                    <label for="telefono">Teléfono:</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Ingrese su número de teléfono"><br>
                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="6" placeholder="Escriba su mensaje" required></textarea>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Enviar mensaje" name="enviar" id="enviar">
                </div>
            </form>
        </section>

        <!-- FOOTER -->
        <footer class="bg-dark text-light text-center py-3">
            <p class="mb-0">&copy; 2025 Sendai Restaurant. Todos los derechos reservados.</p>
        </footer>
        
        <!-- OFFCANVAS CARRITO -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCarrito" aria-labelledby="offcanvasCarritoLabel">
            <div class="offcanvas-header bg-dark text-light">
              <h5 class="offcanvas-title" id="offcanvasCarritoLabel">🛒 Mi Orden</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php if (empty($carrito)): ?>
                    <p class="text-center text-muted">Tu carrito está vacío. ¡Añade algo del menú!</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush mb-4">
                        <?php 
                        $total_general = 0;
                        foreach ($carrito as $key => $item): 
                            $subtotal = $item['precio'] * $item['cantidad'];
                            $total_general += $subtotal;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="me-2">
                                <h6 class="mb-0"><?= htmlspecialchars($item['nombre']); ?></h6>
                                <small class="text-muted">$<?= number_format($item['precio'], 2); ?> c/u</small>
                            </div>
                            <div class="text-end d-flex align-items-center">
                                <div class="input-group input-group-sm me-3" style="width: 120px;">
                                    <button class="btn btn-outline-secondary btn-update-qty" type="button" data-key="<?= $key; ?>" data-action="remove_one" <?= ($item['cantidad'] <= 1) ? 'disabled' : ''; ?>>><i class="bi bi-dash"></i></button>
                                    <input type="text" class="form-control text-center bg-white" value="<?= $item['cantidad']; ?>" readonly>
                                    <button class="btn btn-outline-secondary btn-update-qty" type="button" data-key="<?= $key; ?>" data-action="add_one"><i class="bi bi-plus"></i></button>
                                </div>
                                <strong class="text-success me-3">$<?= number_format($subtotal, 2); ?></strong>
                                <button class="btn btn-outline-danger btn-sm p-1 btn-delete-item" type="button" data-key="<?= $key; ?>"><i class="bi bi-trash"></i></button>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="d-flex justify-content-between fw-bold mb-3 border-top pt-3">
                        <span>Total a Pagar:</span>
                        <span class="text-danger">$<?= number_format($total_general, 2); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="offcanvas-footer p-3 border-top">
                 <a href="admin/seccion/crear_pedido.php" class="btn btn-success w-100 btn-lg <?= empty($carrito) ? 'disabled' : ''; ?>">Proceder al Pago</a>
            </div>
        </div>
        
        <!-- FORM OCULTO PARA CARRITO -->
        <form id="carrito-form" action="admin/seccion/producto/procesar_carrito.php" method="POST" style="display:none;">
            <input type="hidden" name="plato_key" id="form-plato-key" value="">
            <input type="hidden" name="action" id="form-action" value="">
        </form>

        <!-- SCRIPTS -->
        <?php
        if (isset($_SESSION['carrito_abierto']) && $_SESSION['carrito_abierto'] === true) {
            echo '<script>document.addEventListener("DOMContentLoaded", function() { new bootstrap.Offcanvas(document.getElementById("offcanvasCarrito")).show(); });</script>';
            unset($_SESSION['carrito_abierto']); 
        }
        ?>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
        <script>
            function registrarVista(id) {
                // Perform a fetch request to register the view
                fetch('admin/seccion/stack/registrar_vista.php?id=' + id);
            }

            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById('carrito-form');
                const keyInput = document.getElementById('form-plato-key');
                const actionInput = document.getElementById('form-action');

                function submitCarritoAction(key, action) {
                    keyInput.value = key;
                    actionInput.value = action;
                    form.submit();
                }

                document.querySelectorAll('.btn-update-qty').forEach(button => {
                    button.addEventListener('click', function() {
                        submitCarritoAction(this.getAttribute('data-key'), this.getAttribute('data-action'));
                    });
                });

                document.querySelectorAll('.btn-delete-item').forEach(button => {
                    button.addEventListener('click', function() {
                        if (confirm("¿Estás seguro de que quieres eliminar este producto del carrito?")) {
                            submitCarritoAction(this.getAttribute('data-key'), 'delete');
                        }
                    });
                });
            });
        </script>
    </body>
</html>