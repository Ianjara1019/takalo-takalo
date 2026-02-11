<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-grid"></i> Objets disponibles</h2>
    </div>
</div>

<!-- Barre de recherche -->
<div class="row mb-4">
    <div class="col-12">
        <form action="/recherche" method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" class="form-control" name="keyword" placeholder="Rechercher dans le titre..." value="<?= htmlspecialchars($keyword ?? '') ?>">
            </div>
            <div class="col-md-4">
                <select class="form-select" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= isset($selected_categorie) && $selected_categorie == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Rechercher
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($objets)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Aucun objet disponible pour le moment.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($objets as $objet): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card card-objet h-100">
                <?php if ($objet['photo_principale']): ?>
                    <img src="/uploads/<?= $objet['photo_principale'] ?>" class="card-img-top" alt="<?= htmlspecialchars($objet['titre']) ?>" style="height: 200px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light text-muted d-flex align-items-center justify-content-center" style="height: 200px;">
                        <i class="bi bi-image" style="font-size: 3rem;"></i>
                    </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($objet['titre']) ?></h5>
                    <p class="card-text text-muted small"><?= substr(htmlspecialchars($objet['description']), 0, 100) ?>...</p>
                    <p class="card-text">
                        <span class="badge bg-info"><?= htmlspecialchars($objet['categorie_nom']) ?></span><br>
                        <span class="text-success fw-bold"><?= number_format($objet['prix_estimatif'], 0, ',', ' ') ?> Ar</span>
                    </p>
                    <p class="card-text small text-muted">
                        <i class="bi bi-person"></i> Proposé par <?= htmlspecialchars($objet['prenom'] . ' ' . $objet['nom']) ?>
                    </p>
                </div>
                
                <div class="card-footer bg-transparent">
                    <a href="/objet/<?= $objet['id'] ?>" class="btn btn-primary w-100">
                        <i class="bi bi-eye"></i> Voir les détails
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
