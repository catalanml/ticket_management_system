document.addEventListener('DOMContentLoaded', function () {
    const priorityForm = document.getElementById('priorityForm');
    const priorityNameInput = document.getElementById('priorityName');
    const priorityTypeSelect = document.getElementById('priorityType');
    const priorityTableBody = document.getElementById('priorityTableBody');
    const alertContainer = document.getElementById('alertContainer');
    const noElementsMessage = document.getElementById('noprioritiesMessage');
    let currentpriorityId = null;

    function showAlert(message, type = 'danger', inModal = false) {

        if (inModal) {
            const modalAlertContainer = document.getElementById('modalAlertContainer');
            modalAlertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            setTimeout(() => {
                modalAlertContainer.innerHTML = '';
            }
                , 3000);
            return;
        }


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
    priorityForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = priorityNameInput.value.trim();
        const prioritytype = priorityTypeSelect.value.trim();

        if (!name || !prioritytype) {
            showAlert('Todos los campos son obligatorios.', 'danger');
            return;
        }

        fetch('/priorities/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, prioritytype }),
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
                            <span class="badge bg-${data.priority.type === 'high' ? 'danger' : (data.priority.type === 'medium' ? 'warning' : 'success')}">
                                ${data.priority.type === 'high' ? 'Alta' : (data.priority.type === 'medium' ? 'Media' : 'Baja')}
                            </span>
                        </td>
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
                    priorityTypeSelect.value = '';
                    showAlert('Prioridad creada con éxito.', 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Ocurrió un error al crear la prioridad.', 'danger');
            });
    });

    if (priorityTableBody) {
        priorityTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('editpriority')) {
                const row = e.target.closest('tr');
                currentpriorityId = row.getAttribute('data-id');
                const currentName = row.querySelector('td:nth-child(2)').textContent;
                const currentType = row.querySelector('td:nth-child(3)').textContent;

                const editModalHtml = `
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Editar prioridad</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert-container" id="modalAlertContainer"></div>
                                    <label for="editpriorityName" class="form-label">Nuevo nombre</label>
                                    <input type="text" id="editpriorityName" class="form-control" value="${currentName}" required>
                                    <label for="editpriorityType" class="form-label mt-3">Tipo de Prioridad</label>
                                    <select id="editpriorityType" class="form-select" required>
                                        <option value="" ${!currentType ? 'selected' : ''}>Seleccionar</option>
                                        <option value="high" ${currentType === 'high' ? 'selected' : ''}>Alta</option>
                                        <option value="medium" ${currentType === 'medium' ? 'selected' : ''}>Media</option>
                                        <option value="low" ${currentType === 'low' ? 'selected' : ''}>Baja</option>
                                    </select>
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
                    const newType = document.getElementById('editpriorityType').value.trim();

                    if (!newName || !newType) {
                        showAlert('Todos los campos son obligatorios.', 'danger', true);
                        return;
                    }

                    fetch('/priorities/edit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentpriorityId, name: newName, prioritytype: newType }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.querySelector('td:nth-child(2)').textContent = newName;
                                row.querySelector('td:nth-child(3)').innerHTML = `
                                    <span class="badge bg-${newType === 'high' ? 'danger' : (newType === 'medium' ? 'warning' : 'success')}">
                                        ${newType === 'high' ? 'Alta' : (newType === 'medium' ? 'Media' : 'Baja')}
                                    </span>
                                `;
                                editModal.hide();
                                document.getElementById('editModal').remove();
                                showAlert('Prioridad actualizada con éxito.', 'success');
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
                currentPriorityId = row.getAttribute('data-id');

                // Crear modal dinámico para confirmar eliminación
                const deleteModalHtml = `
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Eliminar Prioridad</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Estás seguro de que deseas eliminar esta prioridad?</p>
                                <p><strong>Esta acción eliminará todas las tareas relacionadas.</strong></p>
                                <p><strong>Esta acción no se puede deshacer.</strong></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-danger" id="confirmDeletePriority">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                // Añadir modal al DOM
                document.body.insertAdjacentHTML('beforeend', deleteModalHtml);
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                deleteModal.show();

                // Manejar confirmación de eliminación
                document.getElementById('confirmDeletePriority').addEventListener('click', function () {
                    fetch('/priorities/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentPriorityId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.remove(); // Eliminar fila de la tabla

                                // Mostrar mensaje de "No hay prioridades" si la tabla queda vacía
                                if (!priorityTableBody.querySelector('tr')) {
                                    document.getElementById('priorityTable').style.display = 'none';
                                    const noElementsMessage = document.getElementById('noprioritiesMessage');
                                    if (noElementsMessage) noElementsMessage.style.display = '';
                                }

                                deleteModal.hide();
                                document.getElementById('deleteModal').remove(); // Eliminar modal del DOM
                                showAlert('Prioridad eliminada con éxito.', 'success');
                            } else {
                                showAlert(data.message || 'Error al eliminar la prioridad.', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Ocurrió un error al eliminar la prioridad.', 'danger');
                        });
                });

                // Eliminar modal del DOM al cerrarlo
                document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
                    document.getElementById('deleteModal').remove();
                });
            }
        });
    }
});
