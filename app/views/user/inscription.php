<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="bi bi-person-plus"></i> Inscription</h4>
            </div>
            <div class="card-body p-4">
                <div id="inscription-feedback"></div>

                <form method="POST" action="/inscription" id="inscription-form" novalidate>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                            <div class="invalid-feedback" data-field-error="nom"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                            <div class="invalid-feedback" data-field-error="prenom"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" data-field-error="email"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe *</label>

                        <div class="input-group">
                            <input type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required
                                minlength="8"
                                pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}">

                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <small class="text-muted">
                            8+ caractères avec majuscule, minuscule, chiffre et caractère spécial.
                        </small>

                        <div class="invalid-feedback" data-field-error="password"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" id="telephone" name="telephone">
                        <div class="invalid-feedback" data-field-error="telephone"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse" rows="2"></textarea>
                        <div class="invalid-feedback" data-field-error="adresse"></div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success" id="inscription-submit">
                            <i class="bi bi-check-circle"></i> S'inscrire
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Déjà inscrit ? <a href="/login">Se connecter</a></p>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('inscription-form');
    if (!form) {
        return;
    }

    const submitButton = document.getElementById('inscription-submit');
    const feedback = document.getElementById('inscription-feedback');

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
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Validation...';

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
                setAlert(payload.message || 'Inscription réussie.', 'success');

                if (payload.redirect) {
                    window.location.href = payload.redirect;
                }

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
<script>
document.getElementById("togglePassword").addEventListener("click", function () {
    const passwordInput = document.getElementById("password");
    const icon = this.querySelector("i");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        passwordInput.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
});
</script>
