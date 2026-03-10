<?php
include "../conexionBD.php";

/* Obtener id de colonia */
$idColonia = $_GET['id'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Gatos</title>
    <link rel="stylesheet" href="../css/ayuntamiento.css">
</head>

<body>
    <header>
        <div class="header-container">
            <nav class="navbar">
                <a href="colonias.php">Gestión de Colonias</a>
                <a href="grupos_ayuntamiento.php">Gestión de Grupos</a>
                <div class="logout-container">
                    <a href="../index.php" class="logout-btn">Cerrar Sesión</a>
                </div>
            </nav>
        </div>
    </header>

    <h1>Gestión de Gatos - Colonia <?php echo $idColonia; ?></h1>

    <!-- FORMULARIO -->
    <h2>Acciones Gato</h2>

    <form method="post" action="accion_gato.php">
        <input type="hidden" name="idColonia" value="<?php echo $idColonia; ?>">
        <label>Acción:</label>
        <select name="accion" required>
            <option value="insertar">Insertar</option>
            <option value="modificar">Modificar</option>
            <option value="eliminar">Eliminar</option>
        </select><br>

        <label>XIP Gato:</label>
        <input type="text" name="XIP"><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="30"><br>

        <label>Sexo:</label>
        <select name="sexo">
            <option value="">-- Seleccionar --</option>
            <option value="Macho">Macho</option>
            <option value="Hembra">Hembra</option>
        </select><br>

        <label>Edad:</label>
        <input type="number" name="edad" min="0" max="30"><br>

        <label>Aspecto:</label>
        <input type="text" name="aspecto" maxlength="200"><br>

        <label>Foto (nombre archivo):</label>
        <input type="text" name="foto" maxlength="20"><br>

        <input type="submit" value="Ejecutar">
    </form>

    <!-- TABLA GATOS -->
    <h2>Gatos de la Colonia</h2>

    <?php
    $consulta = "SELECT g.XIP, g.nombre, g.foto
            FROM Gato g
            JOIN Pertenencia p ON g.XIP = p.XIP
            WHERE p.idColonia = $idColonia
              AND p.fechaFin IS NULL";

    $resultado = mysqli_query($con, $consulta);

    echo "<table>
    <tr>
        <th>XIP</th>
        <th>Nombre</th>
        <th>Foto</th>
        <th>Acciones</th>
    </tr>";
    while ($fila = mysqli_fetch_array($resultado)) {
        $foto = $fila['foto'];
        $imagenPath = "../img/" . $foto;
    ?>
        <tr>
            <td><?php echo $fila['XIP']; ?></td>
            <td><?php echo $fila['nombre'] ? $fila['nombre'] : 'No disponible'; ?></td>
            <td>
                <?php if ($foto && file_exists($imagenPath)): ?>
                    <img src="<?php echo $imagenPath; ?>" alt="Foto de <?php echo $fila['XIP']; ?>" class="foto-gato">
                <?php else: ?>
                    <div class="sin-foto">Sin foto</div>
                <?php endif; ?>
            </td>
            <td><a href="gato.php?id=<?php echo $fila["XIP"]; ?>&colonia=<?php echo $idColonia; ?>">Historial</a></td>
        </tr>
    <?php
    }
    echo "</table>";
    ?>

    <!-- FORMULARIO PARA GESTIONAR UBICACIONES -->
    <h2>Acciones Ubicación</h2>

    <form method="post" action="accion_ubicacion.php">
        <input type="hidden" name="idColonia" value="<?php echo $idColonia; ?>">

        <label>Acción:</label>
        <select name="accion" required>
            <option value="insertar">Insertar</option>
            <option value="modificar">Modificar</option>
            <option value="eliminar">Eliminar</option>
        </select><br>

        <label>Latitud:</label>
        <input type="text" name="latitud" maxlength="20" required><br>

        <label>Longitud:</label>
        <input type="text" name="longitud" maxlength="20" required><br>

        <label>Descripción:</label>
        <textarea name="descripcion" rows="3" maxlength="200"></textarea><br>

        <label>Comentarios:</label>
        <textarea name="comentarios" rows="3" maxlength="200"></textarea><br>

        <input type="submit" value="Ejecutar">
    </form>
    <!-- TABLA UBICACIONES -->
    <h2>Lugares de suministración</h2>
    <?php
    $consultaUbicaciones = "SELECT u.latitud,u.longitud,u.descripcion,u.comentarios 
            FROM Ubicacion u
            WHERE idColonia = $idColonia ORDER BY idUbicacion";
    $resultUbicaciones = mysqli_query($con, $consultaUbicaciones);

    echo "<table>
    <tr>
        <th>latitud</th>
        <th>longitud</th>
        <th>descripción</th>
        <th>comentarios</th>
    </tr>";
    while ($fila = mysqli_fetch_array($resultUbicaciones)) {
    ?>
        <tr>
            <td><?php echo $fila['latitud']; ?></td>
            <td><?php echo $fila['longitud']; ?></td>
            <td><?php echo $fila['descripcion']; ?></td>
            <td><?php echo $fila['comentarios']; ?></td>
        </tr>
    <?php
    }
    echo "</table>";
    ?>

    <br>
    <a href="colonias.php">Volver a Colonias</a>

</body>

</html>