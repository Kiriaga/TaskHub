<?php include 'includes/header.php'; ?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa-solid fa-right-to-bracket me-2"></i>Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    <form id="loginForm" novalidate>
                        <div class="mb-3">
                            <label for="login_email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="login_email" placeholder="usuario@dominio.com">
                            <div class="invalid-feedback" id="msg-login-email"></div>
                        </div>
                        <div class="mb-3">
                            <label for="login_password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="login_password" placeholder="••••••••">
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