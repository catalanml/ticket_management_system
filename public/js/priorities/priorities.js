document.addEventListener('DOMContentLoaded', function () {
    const priorityForm = document.getElementById('priorityForm');
    const priorityNameInput = document.getElementById('priorityName');
    const priorityTable = document.getElementById('priorityTable');
    const priorityTableBody = document.getElementById('priorityTableBody');
    const alertContainer = document.getElementById('alertContainer');
    const noElementsMessage = document.getElementById('noprioritiesMessage');
    let currentpriorityId = null;

    // Función para mostrar alertas estilizadas
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

    // Crear prioridad
    priorityForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = priorityNameInput.value.trim();

        if (!name) {
            showAlert('El nombre de la prioridad es obligatorio.', 'danger');
            return;
        }

        fetch('/priorities/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-id', data.priority.id);
                    newRow.innerHTML = `
                        <td class="text-center">${data.priority.id}</td>
                        <td>${data.priority.name}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm editpriority">Editar</button>
                            <button class="btn btn-danger btn-sm deletepriority">Eliminar</button>
                        </td>
                    `;


                    if (priorityTable.style.display === 'none') {
                        priorityTable.style.display = '';
                    }


                    if (noElementsMessage) {
                        noElementsMessage.style.display = 'none';
                    }



                    priorityTableBody.appendChild(newRow);
                    priorityNameInput.value = '';
                    showAlert('prioridad creada con éxito.', 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Ocurrió un error al crear la prioridad.', 'danger');
            });
    });

    // Validar si el cuerpo de la tabla existe antes de agregar eventos
    if (priorityTableBody) {
        // Manejar clic en editar prioridad
        priorityTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('editpriority')) {
                const row = e.target.closest('tr');
                currentpriorityId = row.getAttribute('data-id');
                const currentName = row.querySelector('td:nth-child(2)').textContent;

                // Crear modal de edición dinámicamente
                const editModalHtml = `
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Editar prioridad</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" id="editpriorityName" class="form-control" placeholder="Nuevo nombre de la prioridad" value="${currentName}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" id="saveEditpriority">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', editModalHtml);

                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                const saveEditpriorityButton = document.getElementById('saveEditpriority');

                saveEditpriorityButton.addEventListener('click', function () {
                    const newName = document.getElementById('editpriorityName').value.trim();

                    if (!newName) {
                        showAlert('El nombre de la prioridad es obligatorio.', 'danger');
                        return;
                    }

                    fetch('/priorities/edit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentpriorityId, name: newName }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.querySelector('td:nth-child(2)').textContent = newName;
                                editModal.hide();
                                document.getElementById('editModal').remove();
                                showAlert('prioridad actualizada con éxito.', 'success');
                            } else {
                                showAlert(data.message, 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Ocurrió un error al actualizar la prioridad.', 'danger');
                        });
                });

                editModal.show();
            }
        });

        // Manejar clic en eliminar prioridad
        priorityTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('deletepriority')) {
                const row = e.target.closest('tr');
                currentpriorityId = row.getAttribute('data-id');

                const deleteModalHtml = `
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Eliminar prioridad</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar esta prioridad?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger" id="confirmDeletepriority">Eliminar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', deleteModalHtml);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const confirmDeletepriorityButton = document.getElementById('confirmDeletepriority');

                confirmDeletepriorityButton.addEventListener('click', function () {
                    fetch('/priorities/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentpriorityId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.remove();

                                // Si no hay filas después de eliminar, ocultar la tabla
                                if (!priorityTableBody.querySelector('tr')) {
                                    priorityTable.style.display = 'none';

                                    if (noElementsMessage) {
                                        noElementsMessage.style.display = '';
                                    }
                                }

                                deleteModal.hide();
                                document.getElementById('deleteModal').remove();
                                showAlert('prioridad eliminada con éxito.', 'success');
                            } else {
                                showAlert(data.message, 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Ocurrió un error al eliminar la prioridad.', 'danger');
                        });
                });

                deleteModal.show();
            }
        });
    }
});
