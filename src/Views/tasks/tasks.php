<?php
ob_start();
?>
<script src="/js/tasks/tasks.js" defer></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-dark text-center mb-4">Administración de Tareas</h2>
            
            <!-- Listado de tareas -->
            <?php if (!empty($tasks)): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Prioridad</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody id="taskTableBody">
                    <?php foreach ($tasks as $task): ?>
                        <tr data-id="<?= $task['id']; ?>">
                            <td><?= $task['id']; ?></td>
                            <td><?= htmlspecialchars($task['title']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td><?= htmlspecialchars($task['priority']); ?></td>
                            <td><?= htmlspecialchars($task['category']); ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm editTask">Editar</button>
                                <button class="btn btn-danger btn-sm deleteTask">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">
                    No hay tareas registradas. ¡Crea una nueva tarea para comenzar!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>
