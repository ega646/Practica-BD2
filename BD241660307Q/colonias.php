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


    <h1>Gestión de Colonias</h1>

    <!-- FORMULARIO PARA GESTIONAR COLONIAS -->
    <h2>Acciones Colonia</h2>

    <form method="post" action="accion_colonia.php">

        <label>Acción:</label>
        <select name="accion" required>
            <option value="insertar">Insertar</option>
            <option value="modificar">Modificar</option>
            <option value="eliminar">Eliminar</option>
        </select>
        <br>

        <label>ID Colonia:</label>
        <input type="number" name="idColonia" placeholder="(Solo necesario para modificar o eliminar)"><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" maxlength="50"><br>

        <input type="hidden" name="codiAyuntamiento" value="<?php 
            session_start();
            echo $_SESSION["usuario"];
        ?>">

        <input type="submit" value="Ejecutar">
    </form>


    <!-- TABLA DE COLONIAS -->
    <h2>Listado de Colonias</h2>

    <?php
    include "../conexionBD.php";
    // Ya iniciamos sesión arriba, no es necesario hacerlo de nuevo
    $codiAy = $_SESSION["usuario"];
    $consulta = "SELECT idColonia, nombre, codiAyuntamiento FROM Colonia WHERE codiAyuntamiento = '$codiAy'";
    $resultado = mysqli_query($con, $consulta);

    echo "<table>";
    while ($fila = mysqli_fetch_array($resultado)) {
    ?>
        <tr>
            <td><?php echo $fila['idColonia']; ?></td>
            <td><?php echo $fila['nombre']; ?></td>
            <td><?php echo $fila['codiAyuntamiento']; ?></td>
            <td><a href="gatos.php?id=<?php echo $fila["idColonia"]; ?>">Gestionar</a></td>
        </tr> <?php
            }
            echo "</table>";
                ?>
</body>

</html>