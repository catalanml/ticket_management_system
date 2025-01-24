<?php ob_start(); ?>
<script src="/js/categories/categories.js" defer></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-dark text-center mb-4">Administrar Categorías</h2>

   
            <div class="alert-container" id="alertContainer"></div>

            <form id="categoryForm" class="mb-4">
                <div class="input-group">
                    <input type="text" id="categoryName" class="form-control" placeholder="Nombre de la categoría" required>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>

            <table id="categoryTable" class="table table-bordered table-striped" style="display: <?= empty($categories) ? 'none' : ''; ?>;">
                <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="categoryTableBody">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr data-id="<?= $category['id']; ?>">
                            <td class="text-center"><?= $category['id']; ?></td>
                            <td><?= htmlspecialchars($category['name']); ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm editCategory">Editar</button>
                                <button class="btn btn-danger btn-sm deleteCategory">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if (empty($categories)): ?>
                <div id="noCategoriesMessage" class="alert alert-info text-center">
                    No hay categorías registradas. ¡Crea una nueva categoría para comenzar!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>
