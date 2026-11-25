<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = (int) $_SESSION['id_usuario'];
$rol_usuario = $_SESSION['rol'] ?? 'Colaborador';
$mensaje = '';
$tipo_alerta = 'alert-success';

// --- 1. PROCESAR CREACIÓN DE PROYECTO Y ASIGNACIÓN DE MIEMBRO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_project') {
    $nombre_proyecto = trim($_POST['nombre_proyecto'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
    $fecha_fin = $_POST['fecha_fin'] ?? null;

    if ($nombre_proyecto !== '') {
        $stmtP = $conn->prepare("INSERT INTO proyectos (nombre_proyecto, descripcion, fecha_inicio, fecha_fin, id_creador) VALUES (?, ?, ?, ?, ?)");
        $stmtP->bind_param("ssssi", $nombre_proyecto, $descripcion, $fecha_inicio, $fecha_fin, $id_usuario);
        if ($stmtP->execute()) {
            $id_nuevo_proyecto = $stmtP->insert_id;
            // Asignar al creador como Líder del proyecto en la tabla asignaciones
            $stmtAsig = $conn->prepare("INSERT INTO asignaciones (id_proyecto, id_usuario, rol_proyecto) VALUES (?, ?, 'Lider')");
            $stmtAsig->bind_param("ii", $id_nuevo_proyecto, $id_usuario);
            $stmtAsig->execute();
            $stmtAsig->close();
            
            $mensaje = 'Proyecto creado y asignado con éxito.';
        } else {
            $mensaje = 'Error al crear el proyecto.';
            $tipo_alerta = 'alert-danger';
        }
        $stmtP->close();
    }
}

// --- 2. VINCULAR USUARIO A PROYECTO (TABLA ASIGNACIONES) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'assign_user_project') {
    $id_proyecto_asig = (int) ($_POST['id_proyecto'] ?? 0);
    $id_usuario_asig = (int) ($_POST['id_usuario'] ?? 0);
    $rol_proyecto = $_POST['rol_proyecto'] ?? 'Miembro';

    if ($id_proyecto_asig > 0 && $id_usuario_asig > 0) {
        // Verificar si la asignación ya existe
        $stmtCheck = $conn->prepare("SELECT id_asignacion FROM asignaciones WHERE id_proyecto = ? AND id_usuario = ?");
        $stmtCheck->bind_param("ii", $id_proyecto_asig, $id_usuario_asig);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows == 0) {
            $stmtAdd = $conn->prepare("INSERT INTO asignaciones (id_proyecto, id_usuario, rol_proyecto) VALUES (?, ?, ?)");
            $stmtAdd->bind_param("iis", $id_proyecto_asig, $id_usuario_asig, $rol_proyecto);
            $stmtAdd->execute();
            $stmtAdd->close();
            $mensaje = 'Usuario asignado al proyecto exitosamente.';
        } else {
            $mensaje = 'El usuario ya pertenece a este proyecto.';
            $tipo_alerta = 'alert-warning';
        }
        $stmtCheck->close();
    }
}

// --- 3. CREAR TAREA ASOCIADA A PROYECTO Y USUARIO ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'create_task') {
    $titulo = trim($_POST['titulo_tarea'] ?? '');
    $prioridad = $_POST['prioridad'] ?? 'Media';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $id_proyecto = (int) ($_POST['id_proyecto'] ?? 0);
    $id_asignado = (int) ($_POST['id_asignado'] ?? 0);

    if ($titulo !== '' && $fecha_vencimiento !== '' && $id_proyecto > 0) {
        $stmtT = $conn->prepare("INSERT INTO tareas (titulo, prioridad, fecha_vencimiento, id_proyecto, id_asignado) VALUES (?, ?, ?, ?, ?)");
        $stmtT->bind_param("sssii", $titulo, $prioridad, $fecha_vencimiento, $id_proyecto, $id_asignado);
        if ($stmtT->execute()) {
            $mensaje = 'Tarea registrada y asignada correctamente.';
        }
        $stmtT->close();
    }
}

// --- 4. ACTUALIZAR ESTADO DE TAREA ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update_status') {
    $id_tarea = (int) ($_POST['id_tarea'] ?? 0);
    $estado = $_POST['estado'] ?? 'Por Hacer';

    if ($id_tarea > 0) {
        $stmtU = $conn->prepare("UPDATE tareas SET estado = ? WHERE id_tarea = ?");
        $stmtU->bind_param("si", $estado, $id_tarea);
        $stmtU->execute();
        $stmtU->close();
    }
}

// --- 5. ELIMINAR TAREA ---
if (isset($_GET['delete'])) {
    $id_tarea = (int) $_GET['delete'];
    if ($id_tarea > 0) {
        $stmtD = $conn->prepare("DELETE FROM tareas WHERE id_tarea = ?");
        $stmtD->bind_param("i", $id_tarea);
        $stmtD->execute();
        $stmtD->close();
    }
    header("Location: proyectos.php");
    exit();
}

// CONSULTAS PARA DESPLEGAR INFORMACIÓN
$lista_usuarios = $conn->query("SELECT id_usuario, nombre, rol FROM usuarios ORDER BY nombre ASC");
$lista_proyectos = $conn->query("SELECT p.*, u.nombre AS creador FROM proyectos p JOIN usuarios u ON p.id_creador = u.id_usuario ORDER BY p.id_proyecto DESC");
$lista_tareas = $conn->query("SELECT t.*, p.nombre_proyecto, u.nombre AS responsable FROM tareas t JOIN proyectos p ON t.id_proyecto = p.id_proyecto LEFT JOIN usuarios u ON t.id_asignado = u.id_usuario ORDER BY t.id_tarea DESC");

include 'includes/header.php';
?>

<main class="container my-5">
    <?php if ($mensaje !== ''): ?>
        <div class="alert <?php echo htmlspecialchars($tipo_alerta); ?> alert-dismissible fade show mb-4" role="alert">
            <?php echo htmlspecialchars($mensaje); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- SECCIÓN 1: CREAR PROYECTO Y ASIGNAR EQUIPO -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 fs-6"><i class="fa-solid fa-folder-plus me-2"></i>Nuevo Proyecto</h5>
                </div>
                <div class="card-body">
                    <form action="proyectos.php" method="POST">
                        <input type="hidden" name="action" value="create_project">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Nombre del Proyecto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre_proyecto" class="form-control form-control-sm" required placeholder="Ej. Rediseño Web">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Descripción</label>
                            <textarea name="descripcion" class="form-control form-control-sm" rows="2" placeholder="Detalles del proyecto..."></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Fecha Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Fecha Entrega</label>
                                <input type="date" name="fecha_fin" class="form-control form-control-sm">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark btn-sm w-100">Crear Proyecto</button>
                    </form>
                </div>
            </div>

            <!-- FORMULARIO ASIGNACIÓN DE EQUIPOS (ASIGNACIONES N:M) -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0 fs-6"><i class="fa-solid fa-user-gear me-2"></i>Asignar Miembro a Proyecto</h5>
                </div>
                <div class="card-body">
                    <form action="proyectos.php" method="POST">
                        <input type="hidden" name="action" value="assign_user_project">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Seleccionar Proyecto</label>
                            <select name="id_proyecto" class="form-select form-select-sm" required>
                                <option value="">-- Proyecto --</option>
                                <?php 
                                $lista_proyectos->data_seek(0);
                                while($p = $lista_proyectos->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $p['id_proyecto']; ?>"><?php echo htmlspecialchars($p['nombre_proyecto']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Seleccionar Usuario</label>
                            <select name="id_usuario" class="form-select form-select-sm" required>
                                <option value="">-- Usuario --</option>
                                <?php 
                                $lista_usuarios->data_seek(0);
                                while($u = $lista_usuarios->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $u['id_usuario']; ?>"><?php echo htmlspecialchars($u['nombre']); ?> (<?php echo $u['rol']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Rol en el Proyecto</label>
                            <select name="rol_proyecto" class="form-select form-select-sm">
                                <option value="Lider">Líder de Proyecto</option>
                                <option value="Colaborador" selected>Colaborador</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm w-100">Vincular a Proyecto</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- SECCIÓN 2: CREAR TAREAS CON PROYECTO Y ASIGNADO COMPLETO -->
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 fs-6"><i class="fa-solid fa-list-check me-2"></i>Registrar y Asignar Tarea</h5>
                </div>
                <div class="card-body">
                    <form id="taskForm" action="proyectos.php" method="POST" novalidate>
                        <input type="hidden" name="action" value="create_task">
                        <div class="row">
                            <div class="col-md-7 mb-2">
                                <label for="titulo_tarea" class="form-label small fw-semibold">Título de la Tarea <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="titulo_tarea" name="titulo_tarea" required placeholder="Ej. Prototipo de interfaz">
                                <div class="invalid-feedback" id="msg-titulo"></div>
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="form-label small fw-semibold">Proyecto <span class="text-danger">*</span></label>
                                <select name="id_proyecto" class="form-select form-select-sm" required>
                                    <option value="">-- Proyecto --</option>
                                    <?php 
                                    $lista_proyectos->data_seek(0);
                                    while($p = $lista_proyectos->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $p['id_proyecto']; ?>"><?php echo htmlspecialchars($p['nombre_proyecto']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="prioridad" class="form-label small fw-semibold">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select form-select-sm" id="prioridad" name="prioridad" required>
                                    <option value="">-- Prioridad --</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Media" selected>Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                                <div class="invalid-feedback" id="msg-prioridad"></div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="fecha_vencimiento" class="form-label small fw-semibold">Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control form-control-sm" id="fecha_vencimiento" name="fecha_vencimiento" required>
                                <div class="invalid-feedback" id="msg-fecha"></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small fw-semibold">Asignar a</label>
                                <select name="id_asignado" class="form-select form-select-sm">
                                    <option value="">-- Sin Asignar --</option>
                                    <?php 
                                    $lista_usuarios->data_seek(0);
                                    while($u = $lista_usuarios->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo $u['id_usuario']; ?>"><?php echo htmlspecialchars($u['nombre']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm w-100">Guardar Tarea</button>
                    </form>
                    <div id="task-alert" class="mt-2 alert d-none"></div>
                </div>
            </div>

            <!-- TABLA GENERAL DE TAREAS Y ACCIONES CRUD -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0 fs-6"><i class="fa-solid fa-table-list me-2"></i>Monitoreo de Tareas Registradas</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small">
                                <tr>
                                    <th>Tarea</th>
                                    <th>Proyecto</th>
                                    <th>Asignado</th>
                                    <th>Estado</th>
                                    <th class="text-center">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($lista_tareas && $lista_tareas->num_rows > 0): ?>
                                    <?php while ($t = $lista_tareas->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold small"><?php echo htmlspecialchars($t['titulo']); ?></div>
                                                <span class="badge bg-<?php echo ($t['prioridad'] === 'Alta') ? 'danger' : (($t['prioridad'] === 'Media') ? 'warning' : 'secondary'); ?>">
                                                    <?php echo htmlspecialchars($t['prioridad']); ?>
                                                </span>
                                            </td>
                                            <td class="small text-muted"><?php echo htmlspecialchars($t['nombre_proyecto']); ?></td>
                                            <td class="small fw-semibold"><?php echo htmlspecialchars($t['responsable'] ?? 'Sin asignar'); ?></td>
                                            <td>
                                                <form action="proyectos.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="action" value="update_status">
                                                    <input type="hidden" name="id_tarea" value="<?php echo (int) $t['id_tarea']; ?>">
                                                    <select name="estado" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                        <?php foreach (['Por Hacer', 'En Proceso', 'En Revisión', 'Completado'] as $est): ?>
                                                            <option value="<?php echo $est; ?>" <?php if ($t['estado'] === $est) echo 'selected'; ?>>
                                                                <?php echo $est; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <a href="proyectos.php?delete=<?php echo (int) $t['id_tarea']; ?>"
                                                   class="btn btn-outline-danger btn-sm py-0 px-2"
                                                   onclick="return confirm('¿Eliminar esta tarea?');">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4 small">No hay tareas o proyectos registrados aún.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
