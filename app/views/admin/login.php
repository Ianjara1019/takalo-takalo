<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow">
            <div class="card-header bg-dark text-white text-center">
                <h4><i class="bi bi-shield-lock"></i> Administration</h4>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="/admin/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control" id="username" name="username" value="admin2" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <small class="text-muted">Accès réservé aux administrateurs</small>
            </div>
        </div>
        
        <div class="alert alert-secondary mt-3">
            <strong>Compte par défaut :</strong><br>
            Utilisateur: admin<br>
            Mot de passe: admin123
        </div>
    </div>
</div>
