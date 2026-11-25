<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email !== '' && $password !== '') {
        $stmt = $conn->prepare("SELECT id_usuario, nombre, password_hash, rol FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($user = $res->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['nombre'] = $user['nombre'];
                $_SESSION['rol'] = $user['rol'];

                header("Location: proyectos.php");
                exit();
            } else {
                $mensaje = 'Contraseña incorrecta.';
            }
        } else {
            $mensaje = 'No existe una cuenta registrada con ese correo.';
        }
        $stmt->close();
    } else {
        $mensaje = 'Ingrese su correo y contraseña.';
    }
}

include 'includes/header.php';
?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($mensaje !== ''): ?>
                        <div class="alert alert-danger mb-3">
                            <?php echo htmlspecialchars($mensaje); ?>
                        </div>
                    <?php endif; ?>

                    <form id="loginForm" action="login.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="login_email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="login_email" name="email" placeholder="usuario@dominio.com">
                            <div class="invalid-feedback" id="msg-login-email"></div>
                        </div>
                        <div class="mb-3">
                            <label for="login_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="login_password" name="password" placeholder="••••••••">
                            <div class="invalid-feedback" id="msg-login-password"></div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark">Ingresar a TaskHub</button>
                        </div>
                    </form>
                    <div id="login-alert" class="mt-3 alert d-none"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
