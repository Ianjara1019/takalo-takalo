<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                <h4><i class="bi bi-arrow-left-right"></i> Connexion - Takalo-takalo</h4>
            </div>
            <div class="card-body p-4">
                <div id="login-feedback"></div>

                <form method="POST" action="/login" id="login-form" novalidate>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" data-field-error="email"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <div class="invalid-feedback" data-field-error="password"></div>
                    </div>

                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success" id="login-submit">
                            <i class="bi bi-box-arrow-in-right"></i> Se connecter
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Pas encore inscrit ? <a href="/inscription">Créer un compte</a></p>
            </div>
        </div>
        
        <div class="alert alert-info mt-3">
            <strong>Comptes de test :</strong><br>
            Email: jean.rakoto@email.com<br>
            Mot de passe: password123
        </div>
    </div>
</div>

<script>
(function () {
    const form = document.getElementById('login-form');
    if (!form) {
        return;
    }

    const submitButton = document.getElementById('login-submit');
    const feedback = document.getElementById('login-feedback');

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
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Connexion...';

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
                setAlert(payload.message || 'Connexion réussie.', 'success');
                if (payload.redirect) {
                    window.location.href = payload.redirect;
                }
                return;
            }

            if (payload.errors) {
                applyFieldErrors(payload.errors);
            }

            setAlert(payload.message || 'Échec de la connexion.', 'danger');
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

