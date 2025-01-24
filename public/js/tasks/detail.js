document.addEventListener('DOMContentLoaded', function () {
    const completeTaskButton = document.getElementById('completeTaskButton');
    const taskObservationInput = document.getElementById('taskObservation');
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


    if (completeTaskButton) {
        completeTaskButton.addEventListener('click', function () {
            const taskId = new URLSearchParams(window.location.search).get('id');
            const observation = taskObservationInput ? taskObservationInput.value.trim() : '';
            const assignedUserId = document.getElementById('assignedUserId').value;

            if (!taskId) {
                showAlert('El ID de la tarea no se encuentra en la URL.', 'danger');
                return;
            }

            const payload = {
                id: taskId,
                status: 'completed',
            };

            if (observation) {
                payload.observation = observation;
            }

            fetch('/tasks/complete', {
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
                            window.location.href = '/dashboard?assignedUserId=' + assignedUserId;
                        }, 2000);
                    } else {
                        showAlert(data.message || 'No se pudo completar la tarea.', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Ocurrió un error al completar la tarea.', 'danger');
                });
        });
    }
});
