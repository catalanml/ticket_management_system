<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management - Tasks</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <script src="/assets/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>


</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">Task List</h1>

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
        <div class="alert alert-info" role="alert">
            No tasks found. <a href="/tasks/create" class="alert-link">Create a new task</a>.
        </div>
    <?php endif; ?>
</div>

</body>
</html>
