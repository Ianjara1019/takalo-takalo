<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="card-title"><?= htmlspecialchars($objet['titre']) ?></h2>
                        <span class="badge bg-info"><?= htmlspecialchars($objet['categorie_nom']) ?></span>
                        <span class="badge bg-<?= $objet['statut'] == 'disponible' ? 'success' : 'warning' ?>">
                            <?= ucfirst($objet['statut']) ?>
                        </span>
                    </div>
                    <div>
                        <h3 class="text-success mb-0"><?= number_format($objet['prix_estimatif'], 0, ',', ' ') ?> Ar</h3>
                    </div>
                </div>
                
                <!-- Photos -->
                <?php if (!empty($photos)): ?>
                <div id="carouselPhotos" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($photos as $index => $photo): ?>
                        <div class="carousel-item <?= $index == 0 ? 'active' : '' ?>">
                            <img src="<?= $photo['chemin'] ?>" class="d-block w-100" alt="Photo" style="max-height: 400px; object-fit: contain;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($photos) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselPhotos" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselPhotos" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <h5>Description</h5>
                <p class="text-muted"><?= nl2br(htmlspecialchars($objet['description'])) ?></p>
                
                <hr>
                
                <h5>Propriétaire</h5>
                <p>
                    <i class="bi bi-person"></i> <?= htmlspecialchars($objet['prenom'] . ' ' . $objet['nom']) ?>
                </p>
            </div>
        </div>
        
        <!-- Historique d'appartenance -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Historique d'appartenance</h5>
            </div>
            <div class="card-body">
                <?php if (empty($historique)): ?>
                    <p class="text-muted mb-0">Aucun historique disponible.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($historique as $h): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= htmlspecialchars($h['prenom'] . ' ' . $h['nom']) ?></h6>
                                    <p class="mb-0 small text-muted">
                                        Du <?= date('d/m/Y à H:i', strtotime($h['date_debut'])) ?>
                                        <?php if ($h['date_fin']): ?>
                                            au <?= date('d/m/Y à H:i', strtotime($h['date_fin'])) ?>
                                        <?php else: ?>
                                            <span class="badge bg-success">Propriétaire actuel</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <?php if ($objet['proprietaire_id'] != $_SESSION['user_id'] && $objet['statut'] == 'disponible'): ?>
        <!-- Proposer un échange -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-arrow-left-right"></i> Proposer un échange</h5>
            </div>
            <div class="card-body">
                <div id="proposition-feedback"></div>

                <form method="POST" action="/proposition/creer" id="proposition-form" novalidate>
                    <input type="hidden" name="objet_demande_id" value="<?= $objet['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Choisissez un de vos objets *</label>
                        <select class="form-select" name="objet_propose_id" required>
                            <option value="">-- Sélectionnez un objet --</option>
                            <?php foreach ($mesObjets as $monObjet): ?>
                                <?php if ($monObjet['statut'] == 'disponible'): ?>
                                <option value="<?= $monObjet['id'] ?>">
                                    <?= htmlspecialchars($monObjet['titre']) ?> (<?= number_format($monObjet['prix_estimatif'], 0, ',', ' ') ?> Ar)
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback" data-field-error="objet_propose_id"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Message (optionnel)</label>
                        <textarea class="form-control" name="message" rows="3" placeholder="Ajoutez un message pour le propriétaire..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100" id="proposition-submit">
                        <i class="bi bi-send"></i> Envoyer la proposition
                    </button>
                </form>
                
                <?php if (empty($mesObjets)): ?>
                <div class="alert alert-warning mt-3 mb-0">
                    <small>Vous devez d'abord ajouter vos propres objets pour proposer un échange.</small>
                    <a href="/mes-objets" class="btn btn-sm btn-warning mt-2 w-100">Ajouter un objet</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            <?php if ($objet['proprietaire_id'] == $_SESSION['user_id']): ?>
                Cet objet vous appartient.
            <?php else: ?>
                Cet objet n'est plus disponible pour l'échange.
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('proposition-form');
    if (!form) {
        return;
    }

    const submitButton = document.getElementById('proposition-submit');
    const feedback = document.getElementById('proposition-feedback');

    const setAlert = (message, type) => {
        feedback.innerHTML = '<div class="alert alert-' + type + '" role="alert">' + message + '</div>';
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
        feedback.innerHTML = '';

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Envoi...';

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
                setAlert(payload.message || 'Proposition envoyée.', 'success');
                window.location.href = payload.redirect || window.location.pathname;
                return;
            }

            if (payload.errors) {
                applyFieldErrors(payload.errors);
            }

            setAlert(payload.message || 'Le formulaire contient des erreurs.', 'danger');
        } catch (error) {
            setAlert('Erreur réseau, veuillez réessayer.', 'danger');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
})();
</script>
