<div class="row">
    <div class="col-12">
        <div id="mes-objets-feedback"></div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-box-seam"></i> 
                <?php if (isset($mode) && $mode == 'exchange'): ?>
                    Objets échangeables pour "<?= htmlspecialchars($objet_selectionne['titre']) ?>" (±<?= $percent ?>%)
                <?php else: ?>
                    Mes objets
                <?php endif; ?>
            </h2>
            <?php if (isset($mode) && $mode == 'exchange'): ?>
                <a href="/mes-objets" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à mes objets
                </a>
            <?php else: ?>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ajouterObjetModal">
                    <i class="bi bi-plus-circle"></i> Ajouter un objet
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$displayObjets = isset($mode) && $mode == 'exchange' ? $objets_echange : $objets;
?>

<?php if (empty($displayObjets)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> 
        <?php if (isset($mode) && $mode == 'exchange'): ?>
            Aucun objet disponible dans cette fourchette de prix.
        <?php else: ?>
            Vous n'avez pas encore d'objets. Commencez par en ajouter !
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($displayObjets as $objet): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <?php if ($objet['photo_principale']): ?>
                    <img src="/uploads/<?= $objet['photo_principale'] ?>" class="card-img-top" alt="<?= htmlspecialchars($objet['titre']) ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <span class="badge bg-<?= $objet['statut'] == 'disponible' ? 'success' : 'warning' ?> mb-2">
                        <?= ucfirst($objet['statut']) ?>
                    </span>
                    <h5 class="card-title"><?= htmlspecialchars($objet['titre']) ?></h5>
                    <p class="card-text text-muted small"><?= substr(htmlspecialchars($objet['description']), 0, 100) ?>...</p>
                    <p class="card-text">
                        <span class="badge bg-info"><?= htmlspecialchars($objet['categorie_nom']) ?></span>
                        <span class="text-success fw-bold"><?= number_format($objet['prix_estimatif'], 0, ',', ' ') ?> Ar</span>
                        <?php if (isset($mode) && $mode == 'exchange'): ?>
                            <span class="badge bg-<?= $objet['prix_diff_percent'] >= 0 ? 'warning' : 'info' ?>">
                                <?= $objet['prix_diff_sign'] ?><?= $objet['prix_diff_percent'] ?>%
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <?php if (isset($mode) && $mode == 'exchange'): ?>
                            <a href="/objet/<?= $objet['id'] ?>?proposer=<?= $objet_selectionne['id'] ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-arrow-left-right"></i> Échanger
                            </a>
                            <a href="/objet/<?= $objet['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Voir détails
                            </a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modifierObjetModal<?= $objet['id'] ?>">
                                <i class="bi bi-pencil"></i> Modifier
                            </button>
                            <a href="/objet/supprimer/<?= $objet['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet objet ?')">
                                <i class="bi bi-trash"></i> Supprimer
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php if (!isset($mode) || $mode != 'exchange'): ?>
                    <div class="mt-2 text-center">
                        <small class="text-muted">Filtres par prix :</small>
                        <a href="/mes-objets?objet_id=<?= $objet['id'] ?>&percent=10" class="btn btn-sm btn-outline-secondary ms-1">±10%</a>
                        <a href="/mes-objets?objet_id=<?= $objet['id'] ?>&percent=20" class="btn btn-sm btn-outline-secondary">±20%</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if (!isset($mode) || $mode != 'exchange'): ?>
        <!-- Modal Modifier -->
        <div class="modal fade" id="modifierObjetModal<?= $objet['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="/objet/modifier/<?= $objet['id'] ?>" data-ajax-form="objet" novalidate>
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier l'objet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="ajax-form-feedback"></div>
                            <div class="mb-3">
                                <label class="form-label">Catégorie *</label>
                                <select class="form-select" name="categorie_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $objet['categorie_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback" data-field-error="categorie_id"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Titre *</label>
                                <input type="text" class="form-control" name="titre" value="<?= htmlspecialchars($objet['titre']) ?>" required>
                                <div class="invalid-feedback" data-field-error="titre"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($objet['description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix estimatif (Ar) *</label>
                                <input type="number" class="form-control" name="prix_estimatif" value="<?= $objet['prix_estimatif'] ?>" required>
                                <div class="invalid-feedback" data-field-error="prix_estimatif"></div>
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
        <?php endif; ?>
        
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal Ajouter un objet -->
<div class="modal fade" id="ajouterObjetModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="/objet/ajouter" enctype="multipart/form-data" data-ajax-form="objet" novalidate>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Ajouter un nouvel objet</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="ajax-form-feedback"></div>
                    <div class="mb-3">
                        <label class="form-label">Catégorie *</label>
                        <select class="form-select" name="categorie_id" required>
                            <option value="">-- Sélectionnez une catégorie --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" data-field-error="categorie_id"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Titre *</label>
                        <input type="text" class="form-control" name="titre" required>
                        <div class="invalid-feedback" data-field-error="titre"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Prix estimatif (Ar) *</label>
                        <input type="number" class="form-control" name="prix_estimatif" min="0" required>
                        <div class="invalid-feedback" data-field-error="prix_estimatif"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Photos (1 ou plusieurs)</label>
                        <input type="file" class="form-control" name="photos[]" multiple accept="image/*">
                        <small class="text-muted">La première photo sera la photo principale</small>
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

<script>
(function () {
    const forms = document.querySelectorAll('form[data-ajax-form="objet"]');
    if (!forms.length) {
        return;
    }

    const pageFeedback = document.getElementById('mes-objets-feedback');
    const setPageAlert = (message, type) => {
        if (!pageFeedback) {
            return;
        }
        pageFeedback.innerHTML = '<div class="alert alert-' + type + '" role="alert">' + message + '</div>';
    };

    forms.forEach((form) => {
        const submitButton = form.querySelector('button[type="submit"]');
        const localFeedback = form.querySelector('.ajax-form-feedback');

        const setLocalAlert = (message, type) => {
            if (!localFeedback) {
                return;
            }
            localFeedback.innerHTML = '<div class="alert alert-' + type + '" role="alert">' + message + '</div>';
        };

        const clearFieldErrors = () => {
            form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
            form.querySelectorAll('[data-field-error]').forEach((el) => {
                el.textContent = '';
            });
        };

        const applyFieldErrors = (errors) => {
            Object.entries(errors || {}).forEach(([field, message]) => {
                const input = form.querySelector('[name="' + field + '"]');
                const errorBox = form.querySelector('[data-field-error="' + field + '"]');

                if (input) {
                    input.classList.add('is-invalid');
                }

                if (errorBox) {
                    errorBox.textContent = message;
                }
            });
        };

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            clearFieldErrors();
            form.classList.remove('was-validated');
            if (localFeedback) {
                localFeedback.innerHTML = '';
            }

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const originalText = submitButton ? submitButton.innerHTML : '';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validation...';
            }

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                const payload = await response.json();

                if (response.ok && payload.success) {
                    setPageAlert(payload.message || 'Opération réussie.', 'success');
                    window.location.href = payload.redirect || '/mes-objets';
                    return;
                }

                if (payload.errors) {
                    applyFieldErrors(payload.errors);
                }

                setLocalAlert(payload.message || 'Le formulaire contient des erreurs.', 'danger');
            } catch (error) {
                setLocalAlert('Erreur réseau, veuillez réessayer.', 'danger');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            }
        });
    });
})();
</script>
