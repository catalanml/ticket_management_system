<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/assets/bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/auth/login.css">
    <script src="/js/auth/login.js" defer></script>
</head>
<body>
<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <h3>Bienvenido</h3>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="/login" method="post" id="loginForm" class="requires-validation" novalidate>
                        <div class="col-md-12">
                            <input class="form-control" type="email" name="email" placeholder="Correo" required>
                            <div class="invalid-feedback">Debes indicar tu correo!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="password" name="password" placeholder="Contraseña" required>
                            <div class="invalid-feedback">Debes indicar tu contraseña!</div>
                        </div>

                        <div class="form-button mt-3">
                            <button id="submit" type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
