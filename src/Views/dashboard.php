<?php ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-dark text-center mb-4">Bienvenido, <?= htmlspecialchars($user['firstname'] ?? 'Usuario'); ?></h2>
            <?php if (isset($assignedUserId)): ?>
                <p class="text-muted text-center">Aquí tienes tus tareas asignadas:</p>
            <?php else: ?>
                <p class="text-muted text-center">Esta es la lista de todas las tareas:</p>
            <?php endif; ?>

            <?php if (!empty($tasks)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-4">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Título</th>
                                <th>Prioridad</th>
                                <th>Estado</th>
                                <th>Entrega</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['title']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $task['priority_type'] === 'high' ? 'danger' : ($task['priority_type'] === 'medium' ? 'warning' : 'success'); ?>">
                                            <?= htmlspecialchars(ucfirst($task['priority_name'])); ?>
                                        </span>
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
                                    <td class="text-center">
                                        <a href="/tasks/detail?id=<?= $task['id']; ?>" class="btn btn-info btn-sm">Ver Detalle</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mt-4 text-center">
                    <?php if (isset($assignedUserId)): ?>
                        No tienes tareas asignadas.
                    <?php else: ?>
                        No hay tareas registradas. <a href="/tasks/create" class="alert-link">Crear una nueva tarea</a>.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>