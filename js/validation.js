// js/validation.js
document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('registerForm');
    const loginForm = document.getElementById('loginForm');
    const taskForm = document.getElementById('taskForm');

    if (registerForm) {
        registerForm.addEventListener('submit', validateRegister);
    }
    if (loginForm) {
        loginForm.addEventListener('submit', validateLogin);
    }
    if (taskForm) {
        taskForm.addEventListener('submit', validateTask);
    }
});

// Auxiliar para establecer errores
function showError(inputId, msgId, message) {
    const input = document.getElementById(inputId);
    const msgContainer = document.getElementById(msgId);
    if (input && msgContainer) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        msgContainer.textContent = message;
    }
}

// Auxiliar para limpiar errores
function showSuccess(inputId, msgId) {
    const input = document.getElementById(inputId);
    const msgContainer = document.getElementById(msgId);
    if (input && msgContainer) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        msgContainer.textContent = '';
    }
}

// Validar Formulario de Registro
function validateRegister(e) {
    e.preventDefault();
    let isValid = true;

    // 1. Campo Obligatorio: Nombre
    const nombre = document.getElementById('nombre').value.trim();
    if (nombre === '') {
        showError('nombre', 'msg-nombre', 'El nombre completo es obligatorio.');
        isValid = false;
    } else if (nombre.length < 3) {
        showError('nombre', 'msg-nombre', 'El nombre debe tener al menos 3 caracteres.');
        isValid = false;
    } else {
        showSuccess('nombre', 'msg-nombre');
    }

    // 2. Formato de Datos: Email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        showError('email', 'msg-email', 'El correo electrónico es obligatorio.');
        isValid = false;
    } else if (!emailRegex.test(email)) {
        showError('email', 'msg-email', 'Ingrese un formato de correo válido (ej. usuario@dominio.com).');
        isValid = false;
    } else {
        showSuccess('email', 'msg-email');
    }

    // 3. Formato/Longitud: Contraseña
    const password = document.getElementById('password').value;
    if (password === '') {
        showError('password', 'msg-password', 'La contraseña es obligatoria.');
        isValid = false;
    } else if (password.length < 8) {
        showError('password', 'msg-password', 'La contraseña debe tener mínimo 8 caracteres.');
        isValid = false;
    } else {
        showSuccess('password', 'msg-password');
    }

    // 4. Control Select: Rol
    const rol = document.getElementById('rol').value;
    if (rol === '') {
        showError('rol', 'msg-rol', 'Debe seleccionar un rol para el usuario.');
        isValid = false;
    } else {
        showSuccess('rol', 'msg-rol');
    }

    // 5. Control Checkbox: Términos
    const terminos = document.getElementById('terminos').checked;
    if (!terminos) {
        showError('terminos', 'msg-terminos', 'Debe aceptar los términos y condiciones.');
        isValid = false;
    } else {
        showSuccess('terminos', 'msg-terminos');
    }

    // Alerta de estado
    const alertDiv = document.getElementById('form-alert');
    if (isValid) {
        alertDiv.className = 'mt-3 alert alert-success';
        alertDiv.textContent = '¡Registro validado exitosamente! Preparado para envío al BackEnd.';
        alertDiv.classList.remove('d-none');
    } else {
        alertDiv.className = 'mt-3 alert alert-danger';
        alertDiv.textContent = 'Por favor, corrija los errores resaltados en el formulario.';
        alertDiv.classList.remove('d-none');
    }
}

// Validar Formulario de Login
function validateLogin(e) {
    e.preventDefault();
    let isValid = true;

    const email = document.getElementById('login_email').value.trim();
    const password = document.getElementById('login_password').value;

    if (email === '') {
        showError('login_email', 'msg-login-email', 'El correo es obligatorio.');
        isValid = false;
    } else {
        showSuccess('login_email', 'msg-login-email');
    }

    if (password === '') {
        showError('login_password', 'msg-login-password', 'La contraseña es obligatoria.');
        isValid = false;
    } else {
        showSuccess('login_password', 'msg-login-password');
    }

    const alertDiv = document.getElementById('login-alert');
    if (isValid) {
        alertDiv.className = 'mt-3 alert alert-success';
        alertDiv.textContent = 'Datos de inicio de sesión válidos.';
        alertDiv.classList.remove('d-none');
    }
}

// Validar Formulario de Tarea
function validateTask(e) {
    e.preventDefault();
    let isValid = true;

    const titulo = document.getElementById('titulo_tarea').value.trim();
    const prioridad = document.getElementById('prioridad').value;
    const fecha = document.getElementById('fecha_vencimiento').value;

    if (titulo === '') {
        showError('titulo_tarea', 'msg-titulo', 'El título de la tarea es obligatorio.');
        isValid = false;
    } else {
        showSuccess('titulo_tarea', 'msg-titulo');
    }

    if (prioridad === '') {
        showError('prioridad', 'msg-prioridad', 'Seleccione una prioridad.');
        isValid = false;
    } else {
        showSuccess('prioridad', 'msg-prioridad');
    }

    if (fecha === '') {
        showError('fecha_vencimiento', 'msg-fecha', 'Seleccione una fecha de vencimiento.');
        isValid = false;
    } else {
        showSuccess('fecha_vencimiento', 'msg-fecha');
    }

    const alertDiv = document.getElementById('task-alert');
    if (isValid) {
        alertDiv.className = 'mt-3 alert alert-success';
        alertDiv.textContent = 'Tarea validada y lista para guardarse.';
        alertDiv.classList.remove('d-none');
    }
}