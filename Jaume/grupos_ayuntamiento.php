<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Colonias</title>
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

    <h1>Gestión de Grupos</h1>

    <!-- FORMULARIO PARA GESTIONAR GRUPOS -->
    <h2>Acciones Grupo</h2>

    <?php
    include "../conexionBD.php";
    session_start();
    $codiAy = $_SESSION["usuario"];
    ?>

    <form method="post" action="accion_grupo_ayuntamiento.php">

        <label>Acción:</label>
        <select name="accion" required>
            <option value="insertar">Insertar</option>
            <option value="modificar">Modificar</option>
            <option value="eliminar">Eliminar</option>
        </select>
        <br>

        <label>ID Grupo:</label>
        <input type="number" name="idGrupo" placeholder="(Solo necesario para modificar o eliminar)"><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="50"><br>

        <input type="hidden" name="codiAyuntamiento" value="<?php echo $codiAy; ?>">

        <label>Responsable DNI:</label>
        <input type="text" name="responsableDNI" maxlength="9"><br>

        <input type="submit" value="Ejecutar">
    </form>

    <!-- TABLA DE GRUPOS -->
    <h2>Listado de Grupo</h2>

    <?php
    $consulta2 = "SELECT idGrupo, nombre, codiAyuntamiento, responsableDNI
    FROM Grupo
    WHERE codiAyuntamiento = '$codiAy'
    ORDER BY nombre";
    $resultado2 = mysqli_query($con, $consulta2);

    echo "<table>";
    while ($fila = mysqli_fetch_array($resultado2)) {
    ?>
        <tr>
            <td><?php echo $fila['idGrupo']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['codiAyuntamiento']; ?></td>
            <td><?php echo $fila['responsableDNI']; ?></td>
            <td><a href="grupo_ayuntamiento.php?id=<?php echo $fila["idGrupo"]; ?>">Ver</a></td>
        </tr> <?php
            }
            echo "</table>";
                ?>

    <h2>Asignar voluntarios</h2>
    <?php
    echo '<br><a href="../BD243478494F/borsi.php">Ir al borsí</a>';
    ?>
</body>

</html>