<?php include 'includes/header.php'; ?>

<main class="container my-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa-solid fa-plus me-2"></i>Registrar Nueva Tarea</h5>
                </div>
                <div class="card-body">
                    <form id="taskForm" novalidate>
                        <div class="mb-3">
                            <label for="titulo_tarea" class="form-label">Título de la Tarea <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="titulo_tarea">
                            <div class="invalid-feedback" id="msg-titulo"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="prioridad" class="form-label">Prioridad <span class="text-danger">*</span></label>
                                <select class="form-select" id="prioridad">
                                    <option value="">-- Seleccionar --</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Media">Media</option>
                                    <option value="Baja">Baja</option>
                                </select>
                                <div class="invalid-feedback" id="msg-prioridad"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fecha_vencimiento">
                                <div class="invalid-feedback" id="msg-fecha"></div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                        </div>
                    </form>
                    <div id="task-alert" class="mt-3 alert d-none"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>