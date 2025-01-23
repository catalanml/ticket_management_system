document.addEventListener('DOMContentLoaded', function () {
    const categoryForm = document.getElementById('categoryForm');
    const categoryNameInput = document.getElementById('categoryName');
    const categoryTable = document.getElementById('categoryTable');
    const categoryTableBody = document.getElementById('categoryTableBody');
    const alertContainer = document.getElementById('alertContainer');
    const noElementsMessage = document.getElementById('noCategoriesMessage');
    let currentCategoryId = null;

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

    // Crear categoría
    categoryForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = categoryNameInput.value.trim();

        if (!name) {
            showAlert('El nombre de la categoría es obligatorio.', 'danger');
            return;
        }

        fetch('/categories/create', {
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
                    newRow.setAttribute('data-id', data.category.id);
                    newRow.innerHTML = `
                        <td class="text-center">${data.category.id}</td>
                        <td>${data.category.name}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm editCategory">Editar</button>
                            <button class="btn btn-danger btn-sm deleteCategory">Eliminar</button>
                        </td>
                    `;


                    if (categoryTable.style.display === 'none') {
                        categoryTable.style.display = '';
                    }


                    if (noElementsMessage) {
                        noElementsMessage.style.display = 'none';
                    }



                    categoryTableBody.appendChild(newRow);
                    categoryNameInput.value = '';
                    showAlert('Categoría creada con éxito.', 'success');
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Ocurrió un error al crear la categoría.', 'danger');
            });
    });

    // Validar si el cuerpo de la tabla existe antes de agregar eventos
    if (categoryTableBody) {
        // Manejar clic en editar categoría
        categoryTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('editCategory')) {
                const row = e.target.closest('tr');
                currentCategoryId = row.getAttribute('data-id');
                const currentName = row.querySelector('td:nth-child(2)').textContent;

                // Crear modal de edición dinámicamente
                const editModalHtml = `
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Editar Categoría</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" id="editCategoryName" class="form-control" placeholder="Nuevo nombre de la categoría" value="${currentName}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" id="saveEditCategory">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', editModalHtml);

                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                const saveEditCategoryButton = document.getElementById('saveEditCategory');

                saveEditCategoryButton.addEventListener('click', function () {
                    const newName = document.getElementById('editCategoryName').value.trim();

                    if (!newName) {
                        showAlert('El nombre de la categoría es obligatorio.', 'danger');
                        return;
                    }

                    fetch('/categories/edit', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentCategoryId, name: newName }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.querySelector('td:nth-child(2)').textContent = newName;
                                editModal.hide();
                                document.getElementById('editModal').remove();
                                showAlert('Categoría actualizada con éxito.', 'success');
                            } else {
                                showAlert(data.message, 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Ocurrió un error al actualizar la categoría.', 'danger');
                        });
                });

                editModal.show();
            }
        });

        // Manejar clic en eliminar categoría
        categoryTableBody.addEventListener('click', function (e) {
            if (e.target.classList.contains('deleteCategory')) {
                const row = e.target.closest('tr');
                currentCategoryId = row.getAttribute('data-id');

                const deleteModalHtml = `
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Categoría</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    ¿Estás seguro de que deseas eliminar esta categoría?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Eliminar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', deleteModalHtml);

                const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                const confirmDeleteCategoryButton = document.getElementById('confirmDeleteCategory');

                confirmDeleteCategoryButton.addEventListener('click', function () {
                    fetch('/categories/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: currentCategoryId }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                row.remove();

                                // Si no hay filas después de eliminar, ocultar la tabla
                                if (!categoryTableBody.querySelector('tr')) {
                                    categoryTable.style.display = 'none';

                                    if (noElementsMessage) {
                                        noElementsMessage.style.display = '';
                                    }
                                }

                                deleteModal.hide();
                                document.getElementById('deleteModal').remove();
                                showAlert('Categoría eliminada con éxito.', 'success');
                            } else {
                                showAlert(data.message, 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showAlert('Ocurrió un error al eliminar la categoría.', 'danger');
                        });
                });

                deleteModal.show();
            }
        });
    }
});
