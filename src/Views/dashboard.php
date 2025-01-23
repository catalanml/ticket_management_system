<?php ob_start(); ?>
<h2 class="text-dark">Bienvenido, <?= htmlspecialchars($user['firstname'] ?? 'Usuario'); ?></h2>
<p class="text-muted">Aquí tienes tus tareas asignadas:</p>

<?php if (!empty($tasks)): ?>
    <ul class="list-group">
        <?php foreach ($tasks as $task): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($task['title']); ?>
                <span class="badge bg-<?= $task['priority'] === 'high' ? 'danger' : ($task['priority'] === 'medium' ? 'warning' : 'success'); ?>">
                    <?= htmlspecialchars(ucfirst($task['priority'])); ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <div class="alert alert-info">
        No tienes tareas asignadas. <a href="/tasks/create" class="alert-link">Crear una nueva tarea</a>.
    </div>
<?php endif; ?>
<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>
