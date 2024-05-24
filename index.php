<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wickles</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-primary {
            width: 100%;
            background-color: #E9967A;
            border-color: #E9967A;
        }
        .mensaje {
            margin-top: 15px;
            color: red;
            text-align: center;
        }
    </style>
    <script>
    $(document).ready(function() {
        $('#login_form').submit(function(event) {
            event.preventDefault();
            var email = $('#email').val();
            var password = $('#password').val();

            if (email.trim() === '' || password.trim() === '') {
                $('#mensaje').text('Por favor, completa todos los campos.').show();
                return;
            }

            $.ajax({
                type: 'POST',
                url: 'index_procesar.php',
                data: {
                    email: email,
                    password: password
                },
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'bienvenido.php';
                    } else {
                        $('#mensaje').text('Usuario o contraseña incorrectos.').show();
                    }
                }
            });
        });
    });
    </script>
</head>
<body>

    <div class="container">
        <h2>Iniciar sesión</h2>
        <form id="login_form" method="POST">
            <div class="form-group">
                <label for="email">Correo electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
        <div id="mensaje" class="mensaje"></div>
    </div>

</body>
</html>
