<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-tags"></i> Gestion des catégories</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterCategorieModal">
        <i class="bi bi-plus-circle"></i> Ajouter une catégorie
    </button>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Aucune catégorie pour le moment.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= $cat['id'] ?></td>
                            <td><strong><?= htmlspecialchars($cat['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($cat['description']) ?></td>
                            <td><?= date('d/m/Y', strtotime($cat['created_at'])) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modifierCategorieModal<?= $cat['id'] ?>">
                                        <i class="bi bi-pencil"></i> Modifier
                                    </button>
                                    <a href="/admin/categorie/supprimer/<?= $cat['id'] ?>" 
                                       class="btn btn-danger" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                        
                        <!-- Modal Modifier -->
                        <div class="modal fade" id="modifierCategorieModal<?= $cat['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="/admin/categorie/modifier/<?= $cat['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier la catégorie</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Nom *</label>
                                                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($cat['nom']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($cat['description']) ?></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Ajouter une catégorie -->
<div class="modal fade" id="ajouterCategorieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/categorie/ajouter">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Ajouter une nouvelle catégorie</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
