document.addEventListener('DOMContentLoaded', function () {
    const editTaskForm = document.getElementById('editTaskForm');
    const alertContainer = document.createElement('div');

    alertContainer.className = 'alert-container';
    document.querySelector('.container').prepend(alertContainer);


    function showAlert(message, type = 'danger') {
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


    if (editTaskForm) {
        editTaskForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(editTaskForm);
            const payload = {
                id: formData.get('task_id'),
                title: formData.get('task_title').trim(),
                description: formData.get('description').trim(),
                observation: formData.get('observation').trim(),
                priority_id: formData.get('priority_id'),
                category_id: formData.get('category_id'),
                deadline_date: formData.get('deadline_date')
            };

            if (!payload.title || !payload.description || !payload.priority_id || !payload.category_id || !payload.deadline_date) {
                showAlert('Todos los campos obligatorios deben estar llenos.', 'danger');
                return;
            }

            fetch('/tasks/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showAlert('Tarea actualizada con éxito.', 'success');

                        setTimeout(() => {
                            window.location.href = '/tasks/manageTasks';
                        }, 2000);
                    } else {
                        showAlert(data.message || 'No se pudo actualizar la tarea.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error al actualizar la tarea.', 'danger');
                });
        });
    }
});
