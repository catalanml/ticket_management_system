<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-dark text-center mb-4">Detalle de la Tarea</h2>

            <div class="card">
                <div class="card-body">
                    <form id="taskDetailForm">

                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <label for="taskTitle" class="form-label">Título</label>
                                <p id="taskTitle" class="form-control-plaintext"><?= htmlspecialchars($task['title']); ?></p>
                            </div>
                            <div>
                                <label class="form-label">Categoría</label>
                                <p> <span class="badge bg-primary"><?= htmlspecialchars($task['category_name']); ?></span></p>
                            </div>
                            <div>
                                <label class="form-label">Prioridad</label>
                                <p><span class="badge bg-<?= $task['priority_type'] === 'high' ? 'danger' : ($task['priority_type'] === 'medium' ? 'warning' : 'success'); ?>">
                                        <?= htmlspecialchars(ucfirst($task['priority_name'])); ?>W
                                    </span></p>
                            </div>
                        </div>

                        <!-- Fecha de entrega -->
                        <div class="mb-3">
                            <label for="deadlineDate" class="form-label">Fecha de entrega</label>
                            <p id="deadlineDate" class="text-muted">
                                <?= htmlspecialchars(date('d-m-Y H:i:s', strtotime($task['deadline_date']))); ?>
                            </p>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Descripción</label>
                            <p id="taskDescription" class="text-justify text-muted"><?= htmlspecialchars($task['description']); ?></p>
                        </div>

                        <!-- Observación -->
                        <div class="mb-3">
                            <label for="taskObservation" class="form-label">Observaciones</label>
                            <?php if ($task['assigned_user_id'] === $_SESSION['user_id']): ?>
                                <textarea id="taskObservation" class="form-control" rows="3" placeholder="Añade tus observaciones aquí"><?= htmlspecialchars($task['observation'] ?? ''); ?></textarea>
                            <?php else: ?>
                                <p id="taskObservation" class="text-justify text-muted"><?= htmlspecialchars($task['observation'] ?? 'Sin observaciones.'); ?></p>
                            <?php endif; ?>
                        </div>



                        <!-- Botón de completar tarea (solo para usuario asignado) -->
                        <?php if ($task['assigned_user_id'] === $_SESSION['user_id']): ?>
                            <div class="text-center mt-4">
                                <button type="button" id="completeTaskButton" class="btn btn-success">
                                    <i class="fas fa-check"></i> Completar Tarea
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>