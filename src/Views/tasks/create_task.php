<?php
ob_start();
?>
<script src="/js/tasks/tasks.js" defer></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-dark text-center mb-4">Administración de Tareas</h2>

            <!-- Validación de existencia de categorías y prioridades -->
            <?php if (empty($categories) || empty($priorities)): ?>
                <div class="alert alert-warning text-center">
                    Antes de crear tareas, asegúrate de tener al menos una categoría y una prioridad registradas.
                </div>
                <div class="text-center">
                    <a href="/categories" class="btn btn-primary">Ir a Categorías</a>
                    <a href="/priorities" class="btn btn-primary">Ir a Prioridades</a>
                </div>
            <?php else: ?>
                <form id="taskForm" class="mb-4">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Título de la tarea</label>
                        <input type="text" name="task_title" id="taskTitle" class="form-control" placeholder="Título de la tarea" required>
                    </div>

                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Descripción</label>
                        <textarea id="taskDescription" name="description"  class="form-control" placeholder="Descripción de la tarea" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="taskObservation" class="form-label">Observaciones (Opcional)</label>
                        <textarea id="taskObservation" name="observation" class="form-control" placeholder="Observaciones adicionales" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="taskPriority" class="form-label">Prioridad</label>
                        <select name="priority_id"  id="taskPriority" class="form-select" required>
                            <option value="">Selecciona una prioridad</option>
                            <?php foreach ($priorities as $priority): ?>
                                <option value="<?= $priority['id']; ?>"><?= htmlspecialchars($priority['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="taskCategory" class="form-label">Categoría</label>
                        <select name="category_id" id="taskCategory" class="form-select" required>
                            <option value="">Selecciona una categoría</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id']; ?>"><?= htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="deadlineDate" class="form-label">Fecha de entrega</label>
                        <input name="deadline_date" id="deadlineDate" class="form-control" type="datetime-local" required/>

                    </div>


                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Crear Tarea</button>
                    </div>

                </form>

            <?php endif; ?>

        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>
