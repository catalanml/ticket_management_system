<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de tareas</title>
    <link rel="stylesheet" href="/assets/bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
<div class="d-flex">
    <nav class="sidebar bg-dark text-white p-4" style="width: 250px; min-height: 100vh;">
        <h4 class="mb-4">Task Management</h4>
        <ul class="nav flex-column">

            <li class="nav-item">
                <div class="accordion" id="taskAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTasks">
                            <button class="accordion-button collapsed bg-dark text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTasks" aria-expanded="false" aria-controls="collapseTasks">
                                Tareas
                            </button>
                        </h2>
                        <div id="collapseTasks" class="accordion-collapse collapse" aria-labelledby="headingTasks" data-bs-parent="#taskAccordion">
                            <div class="accordion-body bg-dark p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item"><a class="nav-link text-white" href="/tasks/createForm">Crear tarea</a></li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="/dashboard">Todas las tareas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="/dashboard?assignedUserId=<?php echo $_SESSION['user_id']; ?>">Mis tareas asignadas</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link text-white" href="/tasks/manage">Administrar tareas</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>


            <li class="nav-item"><a class="nav-link text-white" href="/categories">Administrar categorias</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="/priorities">Administrar prioridades</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="/logout">Cerrar sesión</a></li>
        </ul>
    </nav>

    <div class="content p-4 w-100 bg-light">
        <?= $content ?? ''; ?>
    </div>
</div>
<script src="/assets/bootstrap-5.3.0-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
