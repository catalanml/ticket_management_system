document.addEventListener('DOMContentLoaded', function () {
    const taskTableBody = document.getElementById('taskTableBody');
    const deleteTaskModal = new bootstrap.Modal(document.getElementById('deleteTaskModal'));
    const alertContainer = document.getElementById('alertContainer'); 
    let currentTaskId = null;


    function showAlert(message, type = 'success') {
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


    taskTableBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('assignUserSelect')) {
            const row = e.target.closest('tr');
            const taskId = row.getAttribute('data-id');
            const userId = e.target.value;

            if (!userId) {
                showAlert('Debes seleccionar un usuario.', 'danger');
                return;
            }

            fetch('/tasks/assign', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ task_id: taskId, user_id: userId }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        showAlert('Usuario asignado con éxito.', 'success');
                    } else {
                        showAlert(data.message, 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error al asignar la tarea.', 'danger');
                });
        }
    });


    taskTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('deleteTask')) {
            const row = e.target.closest('tr');
            currentTaskId = row.getAttribute('data-id');
            deleteTaskModal.show();
        }
    });

    document.getElementById('confirmDeleteTask').addEventListener('click', function () {
        fetch('/tasks/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: currentTaskId }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = document.querySelector(`tr[data-id="${currentTaskId}"]`);
                    row.remove();
                    deleteTaskModal.hide();
                    showAlert('Tarea eliminada con éxito.', 'success');
                } else {
                    showAlert('Error al eliminar tarea: ' + data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Ocurrió un error al eliminar la tarea.', 'danger');
            });
    });
});
