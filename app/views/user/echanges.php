<h2 class="mb-4"><i class="bi bi-repeat"></i> Mes échanges</h2>

<ul class="nav nav-tabs mb-4" id="echangesTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="recues-tab" data-bs-toggle="tab" data-bs-target="#recues" type="button">
            <i class="bi bi-inbox"></i> Propositions reçues 
            <span class="badge bg-primary"><?= count($propositionsRecues) ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="envoyees-tab" data-bs-toggle="tab" data-bs-target="#envoyees" type="button">
            <i class="bi bi-send"></i> Propositions envoyées
            <span class="badge bg-secondary"><?= count($propositionsEnvoyees) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content" id="echangesTabsContent">
    <!-- Propositions reçues -->
    <div class="tab-pane fade show active" id="recues" role="tabpanel">
        <?php if (empty($propositionsRecues)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Aucune proposition reçue pour le moment.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($propositionsRecues as $prop): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-<?= $prop['statut'] == 'en_attente' ? 'warning' : ($prop['statut'] == 'accepte' ? 'success' : 'danger') ?> text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($prop['prenom'] . ' ' . $prop['nom']) ?>
                                </span>
                                <span class="badge bg-light text-dark">
                                    <?= ucfirst(str_replace('_', ' ', $prop['statut'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Proposition d'échange</h6>
                            
                            <div class="row">
                                <div class="col-6 text-center">
                                    <p class="small text-muted mb-1">Il propose :</p>
                                    <?php if ($prop['photo_propose']): ?>
                                        <img src="/uploads/<?= $prop['photo_propose'] ?>" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                    <?php endif; ?>
                                    <p class="mb-1"><strong><?= htmlspecialchars($prop['objet_propose_titre']) ?></strong></p>
                                    <p class="text-success mb-0"><?= number_format($prop['objet_propose_prix'], 0, ',', ' ') ?> Ar</p>
                                </div>
                                
                                <div class="col-6 text-center">
                                    <p class="small text-muted mb-1">Contre votre :</p>
                                    <?php if ($prop['photo_demande']): ?>
                                        <img src="/uploads/<?= $prop['photo_demande'] ?>" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                    <?php endif; ?>
                                    <p class="mb-1"><strong><?= htmlspecialchars($prop['objet_demande_titre']) ?></strong></p>
                                    <p class="text-success mb-0"><?= number_format($prop['objet_demande_prix'], 0, ',', ' ') ?> Ar</p>
                                </div>
                            </div>
                            
                            <?php if ($prop['message']): ?>
                                <hr>
                                <p class="mb-0 small"><i class="bi bi-chat-left-text"></i> <?= nl2br(htmlspecialchars($prop['message'])) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($prop['statut'] == 'en_attente'): ?>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100" role="group">
                                <a href="/proposition/accepter/<?= $prop['id'] ?>" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir accepter cet échange ?')">
                                    <i class="bi bi-check-circle"></i> Accepter
                                </a>
                                <a href="/proposition/refuser/<?= $prop['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir refuser cette proposition ?')">
                                    <i class="bi bi-x-circle"></i> Refuser
                                </a>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="card-footer bg-transparent text-muted small">
                            <i class="bi bi-clock"></i> <?= date('d/m/Y à H:i', strtotime($prop['updated_at'])) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Propositions envoyées -->
    <div class="tab-pane fade" id="envoyees" role="tabpanel">
        <?php if (empty($propositionsEnvoyees)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> Vous n'avez pas encore envoyé de propositions.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($propositionsEnvoyees as $prop): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header bg-<?= $prop['statut'] == 'en_attente' ? 'info' : ($prop['statut'] == 'accepte' ? 'success' : 'secondary') ?> text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-person"></i> Pour <?= htmlspecialchars($prop['prenom'] . ' ' . $prop['nom']) ?>
                                </span>
                                <span class="badge bg-light text-dark">
                                    <?= ucfirst(str_replace('_', ' ', $prop['statut'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="text-muted mb-3">Votre proposition</h6>
                            
                            <div class="row">
                                <div class="col-6 text-center">
                                    <p class="small text-muted mb-1">Vous proposez :</p>
                                    <?php if ($prop['photo_propose']): ?>
                                        <img src="/uploads/<?= $prop['photo_propose'] ?>" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                    <?php endif; ?>
                                    <p class="mb-1"><strong><?= htmlspecialchars($prop['objet_propose_titre']) ?></strong></p>
                                    <p class="text-success mb-0"><?= number_format($prop['objet_propose_prix'], 0, ',', ' ') ?> Ar</p>
                                </div>
                                
                                <div class="col-6 text-center">
                                    <p class="small text-muted mb-1">Contre :</p>
                                    <?php if ($prop['photo_demande']): ?>
                                        <img src="/uploads/<?= $prop['photo_demande'] ?>" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                    <?php endif; ?>
                                    <p class="mb-1"><strong><?= htmlspecialchars($prop['objet_demande_titre']) ?></strong></p>
                                    <p class="text-success mb-0"><?= number_format($prop['objet_demande_prix'], 0, ',', ' ') ?> Ar</p>
                                </div>
                            </div>
                            
                            <?php if ($prop['message']): ?>
                                <hr>
                                <p class="mb-0 small"><i class="bi bi-chat-left-text"></i> <?= nl2br(htmlspecialchars($prop['message'])) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer bg-transparent text-muted small">
                            <i class="bi bi-clock"></i> Envoyée le <?= date('d/m/Y à H:i', strtotime($prop['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
