<?php include 'includes/header.php'; ?>

<main class="container my-5">
    <!-- Banner Principal -->
    <div class="p-4 p-md-5 mb-4 rounded text-body-emphasis bg-white shadow-sm border-start border-4 border-primary">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold text-dark">Bienvenido a TaskHub</h1>
                <p class="lead my-3">Plataforma interactiva de gestión de proyectos y tareas colaborativas en tiempo real.</p>
            </div>
            <!-- Widget de Fecha Dinámica -->
            <div class="col-md-4 text-md-end">
                <div class="card bg-primary text-white p-3 shadow-sm text-center">
                    <h6 class="text-uppercase mb-1"><i class="fa-regular fa-calendar-days me-2"></i>Fecha Actual</h6>
                    <div id="current-date" class="fw-bold fs-5">Cargando fecha...</div>
                    <div id="current-time" class="small">--:--:--</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Información de Módulos -->
        <div class="col-md-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title text-primary"><i class="fa-solid fa-cubes me-2"></i>Características Principales</h3>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item"><i class="fa-solid fa-check text-success me-2"></i>Control de accesos basado en roles (RBAC)[cite: 2].</li>
                        <li class="list-group-item"><i class="fa-solid fa-check text-success me-2"></i>Tableros interactivos tipo Kanban / Scrum[cite: 2].</li>
                        <li class="list-group-item"><i class="fa-solid fa-check text-success me-2"></i>Gestión de equipos, invitaciones e historial[cite: 2].</li>
                        <li class="list-group-item"><i class="fa-solid fa-check text-success me-2"></i>Métricas de productividad en tiempo real[cite: 2].</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Calculadora de Horas/Productividad -->
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="fa-solid fa-calculator me-2"></i>Calculadora de Estimación de Horas
                </div>
                <div class="card-body">
                    <form id="calc-form">
                        <div class="mb-2">
                            <label class="form-label small">Número de Tareas:</label>
                            <input type="number" id="calc-tasks" class="form-control form-control-sm" placeholder="Ej. 5" min="1">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Horas Promedio por Tarea:</label>
                            <input type="number" id="calc-hours" class="form-control form-control-sm" placeholder="Ej. 3" step="0.5" min="0.5">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Número de Desarrolladores:</label>
                            <input type="number" id="calc-devs" class="form-control form-control-sm" placeholder="Ej. 2" min="1">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="button" onclick="calculateProjectHours()" class="btn btn-primary btn-sm">Calcular Tiempo Estimado</button>
                            <button type="button" onclick="resetCalculator()" class="btn btn-outline-secondary btn-sm">Limpiar</button>
                        </div>
                    </form>
                    <div id="calc-result" class="mt-3 alert alert-info d-none text-center small fw-bold"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>