<?php
include "../conexionBD.php";
session_start();

$idGrupo = $_GET['id'];
$codiAy = $_SESSION["usuario"];

// información del grupo
$sqlGrupo = "SELECT idGrupo, nombre, codiAyuntamiento, responsableDNI FROM Grupo WHERE idGrupo = '$idGrupo' AND codiAyuntamiento = '$codiAy'";
$resGrupo = mysqli_query($con, $sqlGrupo);

if (mysqli_num_rows($resGrupo) == 0) {
    echo "<p>Error: Grupo no encontrado o no tienes permisos para verlo.</p>";
    echo '<a href="grupos_ayuntamiento.php">Volver a la gestión de grupos</a>';
    exit();
}

$grupo = mysqli_fetch_assoc($resGrupo);

// información de los voluntarios del grupo
$sqlVoluntarios = "
    SELECT v.DNI, v.nombre, v.apellidos, v.telefono, v.email
    FROM Voluntario v
    JOIN Ayuda a ON v.DNI = a.DNI
    WHERE a.idGrupo = '$idGrupo'
    ORDER BY v.apellidos, v.nombre
";
$resVoluntarios = mysqli_query($con, $sqlVoluntarios);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Información del Grupo <?php echo $idGrupo; ?></title>
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

    <h1>Información del Grupo <?php echo $idGrupo; ?></h1>

    <div class="info-grupo">
        <h2>Información del Grupo</h2>
        <table>
            <tr>
                <th>ID Grupo</th>
                <td><?php echo $grupo['idGrupo']; ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?php echo $grupo['nombre']; ?></td>
            </tr>
            <tr>
                <th>Código Ayuntamiento</th>
                <td><?php echo $grupo['codiAyuntamiento']; ?></td>
            </tr>
            <tr>
                <th>Responsable DNI</th>
                <td><?php echo $grupo['responsableDNI']; ?></td>
            </tr>
        </table>
    </div>

    <h2>Voluntarios del Grupo</h2>

    <?php if (mysqli_num_rows($resVoluntarios) > 0): ?>
        <table>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Email</th>
            </tr>

            <?php while ($voluntario = mysqli_fetch_assoc($resVoluntarios)) { ?>
                <tr>
                    <td><?php echo $voluntario['DNI']; ?></td>
                    <td><?php echo $voluntario['nombre']; ?></td>
                    <td><?php echo $voluntario['apellidos']; ?></td>
                    <td><?php echo $voluntario['telefono']; ?></td>
                    <td><?php echo $voluntario['email']; ?></td>
                </tr>
            <?php } ?>
        </table>
        
        <p><strong>Total de voluntarios en este grupo: <?php echo mysqli_num_rows($resVoluntarios); ?></strong></p>
    <?php else: ?>
        <p>No hay voluntarios asignados a este grupo.</p>
    <?php endif; ?>

    <br>
    <a href="grupos_ayuntamiento.php">Volver a Gestión de Grupos</a>

</body>

</html>