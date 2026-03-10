<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link rel="stylesheet" href="css/login.css?v=2">

</head>
<body>

<div class="container">

    <div class="box">
        <h2>Iniciar Sesión</h2>

        <form action="procesar_login.php" method="POST">

            <label>Usuario (Ayuntamiento o Voluntario):</label>
            <input type="text" name="usuario" required>

            <label>Contraseña:</label>
            <input type="password" name="password" required>

            <label>Tipo de usuario:</label>
            <select name="tipo" required>
                <option value="ayuntamiento">Ayuntamiento</option>
                <option value="voluntario">Voluntario</option>
            </select>

            <button type="submit">Entrar</button>
        </form>

        <p class="registro">
            ¿No tienes cuenta?
            <a href="registro.php">Regístrate aquí</a>
        </p>
    </div>

</div>

</body>
</html>
