<?php include 'includes/header.php'; ?>

<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0"><i class="fa-solid fa-user-plus me-2"></i>Registro de Usuario</h4>
                </div>
                <div class="card-body p-4">
                    <form id="registerForm" novalidate>
                        <!-- Campo Obligatorio: Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Juan Pérez">
                            <div class="invalid-feedback" id="msg-nombre"></div>
                        </div>

                        <!-- Campo Obligatorio y Formato: Correo -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="correo@ejemplo.com">
                            <div class="invalid-feedback" id="msg-email"></div>
                        </div>

                        <!-- Campo Formato/Longitud: Contraseña -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres">
                            <div class="invalid-feedback" id="msg-password"></div>
                        </div>

                        <!-- Control: Select de Rol -->
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

                        <!-- Control: Casilla de Verificación (Términos) -->
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

<?php include 'includes/footer.php'; ?>