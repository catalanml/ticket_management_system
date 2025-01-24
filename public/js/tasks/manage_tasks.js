document.addEventListener('DOMContentLoaded', function () {
    const taskTableBody = document.getElementById('taskTableBody');
    const deleteTaskModal = new bootstrap.Modal(document.getElementById('deleteTaskModal'));
    let currentTaskId = null;

    // Manejar selección de usuario
    taskTableBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('assignUserSelect')) {
            const row = e.target.closest('tr');
            const taskId = row.getAttribute('data-id');
            const userId = e.target.value;

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
                        alert('Usuario asignado con éxito.');
                    } 
                })
                .catch(error => console.error('Error:', error));
        }
    });

    // Manejar clic en eliminar tarea
    taskTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('deleteTask')) {
            const row = e.target.closest('tr');
            currentTaskId = row.getAttribute('data-id');
            deleteTaskModal.show();
        }
    });

    // Confirmar eliminación
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
                    alert('Tarea eliminada con éxito.');
                } else {
                    alert('Error al eliminar tarea: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    });
});