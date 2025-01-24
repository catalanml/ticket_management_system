<?php
ob_start();
?>
<script src="/js/priorities/priorities.js" defer></script>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-dark text-center mb-4">Administrar Prioridades</h2>

            <!-- Contenedor de alertas -->
            <div class="alert-container" id="alertContainer"></div>

            <form id="priorityForm" class="mb-4">
                <div class="input-group">
                    <input type="text" id="priorityName" class="form-control" placeholder="Nombre de la Prioridad" required>

                    <select id="priorityType" class="form-select" required>
                        <option value="">Tipo de Prioridad</option>
                        <option value="high">Alta</option>
                        <option value="medium">Media</option>
                        <option value="low">Baja</option>
                    </select>
                    
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>

            <table id="priorityTable" class="table table-bordered table-striped" style="display: <?= empty($priorities) ? 'none' : ''; ?>;">
                <thead>
                <tr class="text-center">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo de Prioridad</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="priorityTableBody">
                <?php if (!empty($priorities)): ?>
                    <?php foreach ($priorities as $priority): ?>
                        <tr data-id="<?= $priority['id']; ?>">
                            <td class="text-center"><?= $priority['id']; ?></td>
                            <td><?= htmlspecialchars($priority['name']); ?></td>
                          
                            <td class="text-center">
                                <span class="badge bg-<?= $priority['type'] === 'high' ? 'danger' : ($priority['type'] === 'medium' ? 'warning' : 'success'); ?>">
                                    <?= htmlspecialchars(ucfirst( $priority['type'] === 'high' ? 'alta' : ($priority['type'] === 'medium' ? 'media' : 'baja')  )); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm editpriority">Editar</button>
                                <button class="btn btn-danger btn-sm deletepriority">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>

            <?php if (empty($priorities)): ?>
                <div id="noprioritiesMessage" class="alert alert-info text-center">
                    No hay Prioridades registradas. ¡Crea una nueva Prioridad para comenzar!
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include VIEW_PATH . 'layout.php'; ?>
