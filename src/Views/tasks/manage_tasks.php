<?php ob_start(); ?>
<script src="/js/tasks/manage_tasks.js" defer></script>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-dark text-center mb-4">Administración de Tareas</h2>
            <div class="alert-container" id="alertContainer"></div>
            <?php if (!empty($tasks)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Prioridad</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th> Entrega </th>
                                <th>Usuario asignado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="taskTableBody">
                            <?php foreach ($tasks as $task): ?>
                                <tr data-id="<?= $task['id']; ?>">
                                    <td><?= $task['id']; ?></td>
                                    <td><?= htmlspecialchars($task['title']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $task['priority_type'] === 'high' ? 'danger' : ($task['priority_type'] === 'medium' ? 'warning' : 'success'); ?>">
                                            <?= htmlspecialchars(ucfirst($task['priority_name'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary"><?= htmlspecialchars($task['category_name']); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $task['status'] === 'completed' ? 'success' : 'secondary'; ?>">
                                            <?= $task['status'] === 'completed' ? 'Completada' : 'Pendiente'; ?>
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge bg-<?= strtotime($task['deadline_date']) >= strtotime(date('Y-m-d')) ? 'success' : 'danger'; ?>">
                                            <?= strtotime($task['deadline_date']) >= strtotime(date('Y-m-d')) ? 'Al día' : 'Atrasada'; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($task['status'] === 'completed'): ?>
                                            <?= htmlspecialchars($task['assigned_user_name'] ?? 'No asignado'); ?>
                                        <?php else: ?>
                                            <select class="form-select assignUserSelect">
                                                <option value="">Seleccionar Usuario</option>
                                                <?php foreach ($users as $user): ?>
                                                    <option
                                                        value="<?= $user['id']; ?>"
                                                        <?= $task['assigned_user_id'] == $user['id'] ? 'selected' : ''; ?>>
                                                        <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </td>

                                    <td class="text-center">
                                        <a href="/tasks/detail?id=<?= $task['id']; ?>" class="btn btn-info btn-sm">Ver Detalle</a>
                                        <a href="/tasks/edit?id=<?= $task['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                                        <button class="btn btn-danger btn-sm deleteTask">Eliminar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    No hay tareas registradas. ¡Crea una nueva tarea para comenzar!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteTaskModal" tabindex="-1" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTaskModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta tarea?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteTask">Eliminar</button>
            </div>
        </div>
    </div>
</div>


<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>