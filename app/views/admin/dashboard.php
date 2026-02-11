<h2 class="mb-4"><i class="bi bi-speedometer2"></i> Tableau de bord</h2>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Utilisateurs</h6>
                        <h2 class="mb-0"><?= $stats['utilisateurs'] ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Échanges effectués</h6>
                        <h2 class="mb-0"><?= $stats['echanges'] ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-arrow-left-right" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Objets</h6>
                        <h2 class="mb-0"><?= $stats['objets'] ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Propositions</h6>
                        <h2 class="mb-0"><?= $stats['propositions'] ?></h2>
                    </div>
                    <div>
                        <i class="bi bi-chat-dots" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par catégorie -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Objets par catégorie</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Catégorie</th>
                                <th>Nombre d'objets</th>
                                <th>Pourcentage</th>
                                <th>Graphique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalObjets = array_sum(array_column($stats['categories'], 'nombre_objets'));
                            foreach ($stats['categories'] as $cat): 
                                $pourcentage = $totalObjets > 0 ? round(($cat['nombre_objets'] / $totalObjets) * 100, 1) : 0;
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($cat['nom']) ?></strong></td>
                                <td><?= $cat['nombre_objets'] ?></td>
                                <td><?= $pourcentage ?>%</td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: <?= $pourcentage ?>%;" 
                                             aria-valuenow="<?= $pourcentage ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            <?= $pourcentage ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th>Total</th>
                                <th><?= $totalObjets ?></th>
                                <th>100%</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
