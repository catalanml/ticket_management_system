document.addEventListener('DOMContentLoaded', function () {
    const taskForm = document.getElementById('taskForm');
    const taskTitleInput = document.getElementById('taskTitle');
    const taskDescriptionInput = document.getElementById('taskDescription');
    const taskObservationInput = document.getElementById('taskObservation');
    const taskPrioritySelect = document.getElementById('taskPriority');
    const taskCategorySelect = document.getElementById('taskCategory');
    const deadlineDateInput = document.getElementById('deadlineDate');

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


    function createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alertContainer';
        document.body.prepend(container);
        return container;
    }


    if (taskForm) {
        taskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const title = taskTitleInput.value.trim();
            const description = taskDescriptionInput.value.trim();
            const observation = taskObservationInput.value.trim();
            const priorityId = taskPrioritySelect.value;
            const categoryId = taskCategorySelect.value;
            const deadlineDateValue = deadlineDateInput.value;

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

                        showAlert('Tarea creada con éxito.', 'success');

                        setTimeout(() => {
                            window.location.href = '/tasks/manageTasks';
                        }, 2000);
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
