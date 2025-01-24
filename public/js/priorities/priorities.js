document.addEventListener('DOMContentLoaded', function () {
    const priorityForm = document.getElementById('priorityForm');
    const priorityNameInput = document.getElementById('priorityName');
    const priorityTypeSelect = document.getElementById('priorityType');
    const priorityTableBody = document.getElementById('priorityTableBody');
    const alertContainer = document.getElementById('alertContainer');
    const noElementsMessage = document.getElementById('noprioritiesMessage');
    let currentpriorityId = null;

    // Función para mostrar alertas estilizadas
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

    // Crear prioridad
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

    // Validar si el cuerpo de la tabla existe antes de agregar eventos
    if (priorityTableBody) {
        // Manejar clic en editar prioridad
        priorityTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('editpriority')) {
                const row = e.target.closest('tr');
                currentpriorityId = row.getAttribute('data-id');
                const currentName = row.querySelector('td:nth-child(2)').textContent;
                const currentType = row.querySelector('td:nth-child(3)').textContent;

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
    }
});
