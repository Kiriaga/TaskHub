<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/conexion.php';

$mensaje = '';
$tipo_alerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $roles_validos = ['Administrador', 'Lider', 'Colaborador'];

    if ($nombre === '' || strlen($nombre) < 3) {
        $mensaje = 'El nombre debe tener al menos 3 caracteres.';
        $tipo_alerta = 'alert-danger';
    } elseif ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'Ingresa un correo electrónico válido.';
        $tipo_alerta = 'alert-danger';
    } elseif (strlen($password) < 8) {
        $mensaje = 'La contraseña debe tener mínimo 8 caracteres.';
        $tipo_alerta = 'alert-danger';
    } elseif (!in_array($rol, $roles_validos, true)) {
        $mensaje = 'Selecciona un rol válido.';
        $tipo_alerta = 'alert-danger';
    } else {
        $stmtCheck = $conn->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
        if (!$stmtCheck) {
            $mensaje = 'Error al consultar usuarios (revisa si existe la tabla): ' . $conn->error;
            $tipo_alerta = 'alert-danger';
        } else {
            $stmtCheck->bind_param("s", $email);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $mensaje = 'Ese correo electrónico ya está registrado.';
                $tipo_alerta = 'alert-warning';
            } else {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmtInsert = $conn->prepare("INSERT INTO usuarios (nombre, email, password_hash, rol) VALUES (?, ?, ?, ?)");
                if (!$stmtInsert) {
                    $mensaje = 'Error al preparar inserción: ' . $conn->error;
                    $tipo_alerta = 'alert-danger';
                } else {
                    $stmtInsert->bind_param("ssss", $nombre, $email, $password_hash, $rol);
                    if ($stmtInsert->execute()) {
                        $mensaje = '¡Cuenta registrada en la base de datos! Ya puedes iniciar sesión.';
                        $tipo_alerta = 'alert-success';
                    } else {
                        $mensaje = 'No se pudo insertar el registro: ' . $stmtInsert->error;
                        $tipo_alerta = 'alert-danger';
                    }
                    $stmtInsert->close();
                }
            }
            $stmtCheck->close();
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa-solid fa-user-plus me-2"></i>Registro de Usuario</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($mensaje !== ''): ?>
                        <div class="alert <?php echo htmlspecialchars($tipo_alerta); ?> mb-3">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form id="registerForm" action="registro.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Juan Pérez">
                            <div class="invalid-feedback" id="msg-nombre"></div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com">
                            <div class="invalid-feedback" id="msg-email"></div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres">
                            <div class="invalid-feedback" id="msg-password"></div>
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol en el Sistema <span class="text-danger">*</span></label>
                            <select class="form-select" id="rol" name="rol">
                                <option value="">-- Seleccione un rol --</option>
                                <option value="Administrador">Administrador</option>
                                <option value="Lider">Líder de Proyecto</option>
                                <option value="Colaborador">Colaborador</option>
                            </select>
                            <div class="invalid-feedback" id="msg-rol"></div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terminos">
                            <label class="form-check-label small" for="terminos">Acepto los términos y condiciones del servicio</label>
                            <div class="invalid-feedback" id="msg-terminos"></div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Crear Cuenta</button>
                        </div>
                    </form>
                    <div id="form-alert" class="mt-3 alert d-none"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
