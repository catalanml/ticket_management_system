document.addEventListener('DOMContentLoaded', function () {
    const taskForm = document.getElementById('taskForm');
    const taskTitleInput = document.getElementById('taskTitle');
    const taskDescriptionInput = document.getElementById('taskDescription');
    const taskObservationInput = document.getElementById('taskObservation');
    const taskPrioritySelect = document.getElementById('taskPriority');
    const taskCategorySelect = document.getElementById('taskCategory');
    const taskTableBody = document.getElementById('taskTableBody');
    const deadlineDateInput = document.getElementById('deadlineDate');

    // Mostrar alerta estilizada
    function showAlert(message, type = 'danger') {
        const alertContainer = document.getElementById('alertContainer') || createAlertContainer();
        alertContainer.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 3000);
    }

    // Crear contenedor de alertas si no existe
    function createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alertContainer';
        document.body.prepend(container);
        return container;
    }

    // Crear tarea
    if (taskForm) {
        taskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const title = taskTitleInput.value.trim();
            const description = taskDescriptionInput.value.trim();
            const observation = taskObservationInput.value.trim();
            const priorityId = taskPrioritySelect.value;
            const categoryId = taskCategorySelect.value;
            const deadlineDateValue = deadlineDateInput.value; // Corrección aquí

            if (!title || !description || !priorityId || !categoryId || !deadlineDateValue) {
                showAlert('Todos los campos obligatorios deben estar llenos.', 'danger');
                return;
            }

            fetch('/tasks/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    title,
                    description,
                    observation,
                    priority_id: priorityId,
                    category_id: categoryId,
                    deadline_date: deadlineDateValue
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const newRow = document.createElement('tr');
                        newRow.setAttribute('data-id', data.task.id);
                        newRow.innerHTML = `
                            <td>${data.task.id}</td>
                            <td>${data.task.title}</td>
                            <td>${data.task.description}</td>
                            <td>${data.task.priority}</td>
                            <td>${data.task.category}</td>
                            <td>${data.task.deadline_date}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editTask">Editar</button>
                                <button class="btn btn-danger btn-sm deleteTask">Eliminar</button>
                            </td>
                        `;

                        taskForm.reset();
                        showAlert('Tarea creada con éxito.', 'success');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error al crear la tarea.', 'danger');
                });
        });
    }
});
