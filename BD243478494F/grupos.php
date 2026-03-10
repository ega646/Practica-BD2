<?php
session_start();
include "../conexionBD.php";

// Comprobar que el usuario está autenticado y es voluntario
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] !== "voluntario") {
    echo "Acceso no permitido.";
    exit();
}

$dni = $_SESSION["usuario"];

// Obtener el grupo del voluntario logueado
$consulta = "
    SELECT g.idGrupo, g.nombre, g.codiAyuntamiento, g.responsableDNI
    FROM Grupo g
    JOIN Ayuda a ON g.idGrupo = a.idGrupo
    WHERE a.DNI = '$dni'
    LIMIT 1
";
$resultado = mysqli_query($con, $consulta);

if (!$resultado || mysqli_num_rows($resultado) === 0) {
    echo "<h2>No estás asignado a ningún grupo.</h2>";
    exit();
}

$grupo = mysqli_fetch_assoc($resultado);

$esResponsable = ($grupo['responsableDNI'] === $dni);

$idGrupo = (int) $grupo['idGrupo'];
$nombreGrupo = htmlspecialchars($grupo['nombre'], ENT_QUOTES, 'UTF-8');
$codiAyuntamiento = htmlspecialchars($grupo['codiAyuntamiento'], ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi Grupo de Voluntariado - <?php echo $nombreGrupo; ?></title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>

<body>
<div class="logout-container">
                    <a href="../index.php" class="logout-btn">Cerrar Sesión</a>
                </div>
<h1>Gestión de Mi Grupo: <?php echo $nombreGrupo; ?></h1>
<p><strong>ID del grupo:</strong> <?php echo $idGrupo; ?> |
   <strong>Ayuntamiento:</strong> <?php echo $codiAyuntamiento; ?></p>

<!-- ============================================= -->
<!--  FORMULARIO DE GESTIÓN (SOLO RESPONSABLE)    -->
<!-- ============================================= -->
<?php if ($esResponsable): ?>

    <h2>Gestión de Miembros del Grupo (solo responsable)</h2>

    <form method="post" action="accion_grupo.php">
        <label>Acción:</label>
        <select name="accion" required>
            <option value="modificar">Modificar datos</option>
            <option value="eliminar">Eliminar miembro del grupo</option>
        </select><br><br>

        <label>DNI del voluntario:</label>
        <input type="text" name="DNI" maxlength="9" required><br><br>

        <label>Nombre:</label>
        <input type="text" name="nombre"><br><br>

        <label>Apellidos:</label>
        <input type="text" name="apellidos"><br><br>

        <label>Contraseña:</label>
        <input type="password" name="contraseña"><br><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <label>Email:</label>
        <input type="email" name="email"><br><br>

        <button type="submit">Ejecutar</button>
    </form>

    <hr>

    <!-- ============================================= -->
    <!--  FORMULARIO PARA CREAR REPORTES (RESPONSABLE) -->
    <!-- ============================================= -->
    <h2>Crear Reporte del Grupo (solo responsable)</h2>

    <form action="accion_reporte.php" method="POST">
        <input type="hidden" name="idGrupo" value="<?php echo $idGrupo; ?>">

        <label>Asunto:</label>
        <input type="text" name="asunto" required><br><br>

        <label>Cuerpo del reporte:</label><br>
        <textarea name="cuerpo" rows="4" cols="50" required></textarea><br><br>

        <button type="submit" name="accion" value="insertarReporte">Crear reporte</button>
    </form>

<?php else: ?>

    <h3>No eres el responsable del grupo. Solo puedes visualizar los miembros y reportes.</h3>

<?php endif; ?>

<!-- ============================================= -->
<!--  TABLA DE MIEMBROS DEL GRUPO                  -->
<!-- ============================================= -->
<h2>Miembros del Grupo</h2>

<?php
$consultaVol = "
    SELECT v.DNI, v.nombre, v.apellidos, v.telefono, v.email
    FROM Voluntario v
    JOIN Ayuda a ON v.DNI = a.DNI
    WHERE a.idGrupo = $idGrupo
";

$resVol = mysqli_query($con, $consultaVol);

echo "<table border='1' cellpadding='5'>
    <tr>
        <th>DNI</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Teléfono</th>
        <th>Email</th>
    </tr>";

while ($fila = mysqli_fetch_assoc($resVol)) {
    echo "<tr>
            <td>".htmlspecialchars($fila['DNI'])."</td>
            <td>".htmlspecialchars($fila['nombre'])."</td>
            <td>".htmlspecialchars($fila['apellidos'])."</td>
            <td>".htmlspecialchars($fila['telefono'])."</td>
            <td>".htmlspecialchars($fila['email'])."</td>
          </tr>";
}

echo "</table>";
?>

<hr>

<!-- ============================================= -->
<!--  LISTA DE REPORTES (TODOS LOS MIEMBROS)       -->
<!-- ============================================= -->
<h2>Reportes del Grupo</h2>

<?php
$sqlReportes = "
    SELECT codiReporte, fecha, asunto, cuerpo
    FROM Reporte 
    WHERE idGrupo = $idGrupo
    ORDER BY fecha DESC, codiReporte DESC
";

$resRep = mysqli_query($con, $sqlReportes);

if (mysqli_num_rows($resRep) > 0) {
    echo "<table border='1' cellpadding='5'>
        <tr>
            <th>Fecha</th>
            <th>Asunto</th>
            <th>Reporte</th>";

    if ($esResponsable) echo "<th>Acción</th>";

    echo "</tr>";

    while ($r = mysqli_fetch_assoc($resRep)) {

        echo "<tr>
                <td>".htmlspecialchars($r['fecha'])."</td>
                <td>".htmlspecialchars($r['asunto'])."</td>
                <td>".nl2br(htmlspecialchars($r['cuerpo']))."</td>";

        // SOLO EL RESPONSABLE VE EL BOTÓN DE ELIMINAR
        if ($esResponsable) {
            echo "<td>
                    <form action='accion_reporte.php' method='POST'>
                        <input type='hidden' name='accion' value='borrarReporte'>
                        <input type='hidden' name='codiReporte' value='".$r['codiReporte']."'>
                        <button type='submit' class='eliminar'>Eliminar</button>
                    </form>
                  </td>";
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No hay reportes en este grupo todavía.</p>";
}
?>

<h2>Avistamiento felino</h2>
    <?php 
        echo '<br><a href="../BD241660307Q/avistamiento.php">Informar de avistamiento</a>';
    ?>
</body>
</html>
