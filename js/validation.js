// js/validation.js
document.addEventListener('DOMContentLoaded', () => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const taskForm = document.getElementById('taskForm');

    function showError(inputId, msgId, message) {
        const input = document.getElementById(inputId);
        const msgContainer = document.getElementById(msgId);
        if (input) {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
        }
        if (msgContainer) {
            msgContainer.textContent = message;
            msgContainer.style.display = 'block';
        }
    }

    function showSuccess(inputId, msgId) {
        const input = document.getElementById(inputId);
        const msgContainer = document.getElementById(msgId);
        if (input) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
        if (msgContainer) {
            msgContainer.textContent = '';
            msgContainer.style.display = '';
        }
    }

    function showAlert(alertId, typeClass, message) {
        const alertDiv = document.getElementById(alertId);
        if (alertDiv) {
            alertDiv.className = `mt-3 alert ${typeClass}`;
            alertDiv.textContent = message;
            alertDiv.classList.remove('d-none');
        }
    }

    function hideAlert(alertId) {
        const alertDiv = document.getElementById(alertId);
        if (alertDiv) {
            alertDiv.classList.add('d-none');
            alertDiv.textContent = '';
        }
    }

    // --- Registro ---
    function checkNombre() {
        const el = document.getElementById('nombre');
        if (!el) return true;
        const val = el.value.trim();
        if (val === '') {
            showError('nombre', 'msg-nombre', 'El nombre completo es obligatorio.');
            return false;
        }
        if (val.length < 3) {
            showError('nombre', 'msg-nombre', 'El nombre debe tener al menos 3 caracteres.');
            return false;
        }
        showSuccess('nombre', 'msg-nombre');
        return true;
    }

    function checkEmailRegistro() {
        const el = document.getElementById('email');
        if (!el) return true;
        const val = el.value.trim();
        if (val === '') {
            showError('email', 'msg-email', 'El correo electrónico es obligatorio.');
            return false;
        }
        if (!emailRegex.test(val)) {
            showError('email', 'msg-email', 'Ingrese un formato de correo válido (ej. usuario@dominio.com).');
            return false;
        }
        showSuccess('email', 'msg-email');
        return true;
    }

    function checkPasswordRegistro() {
        const el = document.getElementById('password');
        if (!el) return true;
        const val = el.value;
        if (val === '') {
            showError('password', 'msg-password', 'La contraseña es obligatoria.');
            return false;
        }
        if (val.length < 8) {
            showError('password', 'msg-password', 'La contraseña debe tener mínimo 8 caracteres.');
            return false;
        }
        showSuccess('password', 'msg-password');
        return true;
    }

    function checkRol() {
        const el = document.getElementById('rol');
        if (!el) return true;
        if (el.value === '') {
            showError('rol', 'msg-rol', 'Debe seleccionar un rol para el usuario.');
            return false;
        }
        showSuccess('rol', 'msg-rol');
        return true;
    }

    function checkTerminos() {
        const el = document.getElementById('terminos');
        if (!el) return true;
        if (!el.checked) {
            showError('terminos', 'msg-terminos', 'Debe aceptar los términos y condiciones.');
            return false;
        }
        showSuccess('terminos', 'msg-terminos');
        return true;
    }

    if (registerForm) {
        document.getElementById('nombre')?.addEventListener('input', checkNombre);
        document.getElementById('email')?.addEventListener('input', checkEmailRegistro);
        document.getElementById('password')?.addEventListener('input', checkPasswordRegistro);
        document.getElementById('rol')?.addEventListener('change', checkRol);
        document.getElementById('terminos')?.addEventListener('change', checkTerminos);

        registerForm.addEventListener('submit', (e) => {
            hideAlert('form-alert');
            const ok = checkNombre() & checkEmailRegistro() & checkPasswordRegistro() & checkRol() & checkTerminos();
            if (!ok) {
                e.preventDefault();
                showAlert('form-alert', 'alert-danger', 'Por favor, corrija los errores resaltados en el formulario.');
            }
        });
    }

    // --- Login ---
    function checkLoginEmail() {
        const el = document.getElementById('login_email');
        if (!el) return true;
        const val = el.value.trim();
        if (val === '') {
            showError('login_email', 'msg-login-email', 'El correo es obligatorio.');
            return false;
        }
        if (!emailRegex.test(val)) {
            showError('login_email', 'msg-login-email', 'Ingrese un formato de correo válido (ej. usuario@dominio.com).');
            return false;
        }
        showSuccess('login_email', 'msg-login-email');
        return true;
    }

    function checkLoginPassword() {
        const el = document.getElementById('login_password');
        if (!el) return true;
        if (el.value === '') {
            showError('login_password', 'msg-login-password', 'La contraseña es obligatoria.');
            return false;
        }
        showSuccess('login_password', 'msg-login-password');
        return true;
    }

    if (loginForm) {
        document.getElementById('login_email')?.addEventListener('input', checkLoginEmail);
        document.getElementById('login_password')?.addEventListener('input', checkLoginPassword);

        loginForm.addEventListener('submit', (e) => {
            hideAlert('login-alert');
            const ok = checkLoginEmail() & checkLoginPassword();
            if (!ok) {
                e.preventDefault();
                showAlert('login-alert', 'alert-danger', 'Por favor, corrija los errores resaltados.');
            }
        });
    }

    // --- Tarea ---
    function checkTituloTarea() {
        const el = document.getElementById('titulo_tarea');
        if (!el) return true;
        if (el.value.trim() === '') {
            showError('titulo_tarea', 'msg-titulo', 'El título de la tarea es obligatorio.');
            return false;
        }
        showSuccess('titulo_tarea', 'msg-titulo');
        return true;
    }

    function checkPrioridadTarea() {
        const el = document.getElementById('prioridad');
        if (!el) return true;
        if (el.value === '') {
            showError('prioridad', 'msg-prioridad', 'Seleccione una prioridad.');
            return false;
        }
        showSuccess('prioridad', 'msg-prioridad');
        return true;
    }

    function checkFechaTarea() {
        const el = document.getElementById('fecha_vencimiento');
        if (!el) return true;
        if (el.value === '') {
            showError('fecha_vencimiento', 'msg-fecha', 'Seleccione una fecha de vencimiento.');
            return false;
        }
        showSuccess('fecha_vencimiento', 'msg-fecha');
        return true;
    }

    if (taskForm) {
        document.getElementById('titulo_tarea')?.addEventListener('input', checkTituloTarea);
        document.getElementById('prioridad')?.addEventListener('change', checkPrioridadTarea);
        document.getElementById('fecha_vencimiento')?.addEventListener('change', checkFechaTarea);

        taskForm.addEventListener('submit', (e) => {
            hideAlert('task-alert');
            const ok = checkTituloTarea() & checkPrioridadTarea() & checkFechaTarea();
            if (!ok) {
                e.preventDefault();
                showAlert('task-alert', 'alert-danger', 'Por favor, complete todos los campos requeridos.');
            }
        });
    }
});
