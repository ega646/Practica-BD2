<?php
include "../conexionBD.php";

$XIP = $_GET['id'];
$idColonia = $_GET['colonia'];

//información del gato
$sqlGato = "SELECT XIP, nombre, sexo, edad, aspecto, foto FROM Gato WHERE XIP = '$XIP'";
$resGato = mysqli_query($con, $sqlGato);

$gato = mysqli_fetch_assoc($resGato);

//información de pertenencia
$sqlHistorial = "
    SELECT p.idColonia, c.nombre, p.fechaInicio, p.fechaFin
    FROM Pertenencia p
    JOIN Colonia c ON p.idColonia = c.idColonia
    WHERE p.XIP = '$XIP'
    ORDER BY p.fechaInicio DESC
";

$resHistorial = mysqli_query($con, $sqlHistorial);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historial del Gato <?php echo $XIP; ?></title>
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
    <h1>Historial del Gato <?php echo $XIP; ?></h1>
    <div class="info-gato">
        <h2>Información del Gato</h2>
        <table>
            <tr>
                <th>XIP</th>
                <td><?php echo $gato['XIP']; ?></td>
            </tr>
            <tr>
                <th>nombre</th>
                <td><?php echo $gato['nombre']; ?></td>
            </tr>
            <tr>
                <th>Foto</th>
                <td>
                    <?php
                    $foto = $gato['foto'];
                    $ruta = "../img/" . $foto;
                    if ($foto && file_exists($ruta)): ?>
                        <img src="<?php echo $ruta; ?>">
                    <?php else: ?>
                        <div class="sin-foto">Sin foto</div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Aspecto</th>
                <td><?php echo $gato['aspecto'] ? $gato['aspecto'] : 'No disponible'; ?></td>
            </tr>
            <tr>
                <th>edad</th>
                <td><?php echo $gato['edad']; ?></td>
            </tr>
            <tr>
                <th>sexo</th>
                <td><?php echo $gato['sexo']; ?></td>
            </tr>  
        </table>
    </div>

    <h2>Colonias donde ha estado</h2>

    <table>
        <tr>
            <th>Nombre</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
        </tr>

        <?php while ($fila = mysqli_fetch_assoc($resHistorial)) { ?>
            <tr>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['fechaInicio']; ?></td>
                <td><?php echo $fila['fechaFin'] ? $fila['fechaFin'] : "Actual"; ?></td>
            </tr>
        <?php } ?>
    </table>

    <br>
    <a href="gatos.php?id=<?php echo $idColonia; ?>">Volver a Gatos</a>

</body>

</html>