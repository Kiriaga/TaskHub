<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskHub - Gestión de Proyectos Colaborativos</title>
    <!-- Framework CSS: Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="index.php">
            <i class="fa-solid fa-list-check me-2"></i>TaskHub
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-house me-1"></i>Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="proyectos.php"><i class="fa-solid fa-diagram-project me-1"></i>Proyectos</a></li>
                <?php if (isset($_SESSION['id_usuario'])): ?>
                    <li class="nav-item ms-lg-2">
                        <span class="navbar-text text-light small me-2">
                            <i class="fa-solid fa-user me-1"></i><?php echo htmlspecialchars($_SESSION['nombre']); ?>
                            <span class="badge bg-primary ms-1"><?php echo htmlspecialchars($_SESSION['rol']); ?></span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger btn-sm" href="logout.php"><i class="fa-solid fa-right-from-bracket me-1"></i>Salir</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php"><i class="fa-solid fa-right-to-bracket me-1"></i>Iniciar Sesión</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-2" href="registro.php"><i class="fa-solid fa-user-plus me-1"></i>Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
