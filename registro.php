<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Voluntario</title>
    <link rel="stylesheet" href="css/login.css?v=2">
</head>
<body>

<div class="container">

    <div class="box">
        <h2>Registro de Voluntario</h2>

        <form action="procesar_registro.php" method="POST">

            <label>DNI:</label>
            <input type="text" name="DNI" maxlength="9" required>

            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>

            <label>Contraseña:</label>
            <input type="password" name="contraseña" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono">

            <label>Email:</label>
            <input type="email" name="email">

            <!-- MUNICIPIO -->
            <label>Municipio al que deseas apuntarte:</label>
            <input type="text" name="municipio" required>
            <small>(Ejemplo: Palma, Inca, Calvià…)</small>

            <button type="submit">Registrarse</button>
        </form>

        <p class="registro">
            <a href="index.php">Volver al inicio de sesión</a>
        </p>
    </div>

</div>

</body>
</html>
