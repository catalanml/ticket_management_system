<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/bootstrap-5.3.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/auth/register.css">
    <script src="/js/auth/register.js" defer></script>
</head>
<body>
<div class="form-body">
    <div class="row">
        <div class="form-holder">
            <div class="form-content">
                <div class="form-items">
                    <h3>Registrate</h3>
                    <p>Completa este formulario para crear tu usuario en la plataforma de tareas</p>
                    <form action="/register" method="POST" id="registerForm" class="requires-validation" novalidate>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="firstname" placeholder="Nombres" required>

                            <div class="invalid-feedback">Debes indicar tu nombre!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="text" name="lastname" placeholder="Apellidos" required>

                            <div class="invalid-feedback">Debes indicar tus apellidos!</div>
                        </div>

                        <div class="col-md-12">
                            <input class="form-control" type="email" name="email" placeholder="E-mail" required>

                            <div class="invalid-feedback">Debes proporcionar una dirección de correo!</div>
                        </div>



                        <div class="col-md-12">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            <div class="invalid-feedback">Debes de elegir una contraseña!</div>
                        </div>


                        <div class="form-button mt-3">
                            <button id="submit" type="submit" class="btn btn-primary">Registrar!</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
