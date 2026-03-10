<?php
session_start();
include "../conexionBD.php";

/* =========================================
   COMPROBAR SESIÓN Y TIPO DE USUARIO
========================================= */
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] !== "ayuntamiento") {
    echo "Acceso no permitido.";
    exit();
}

$codiAyuntamiento = $_SESSION["usuario"];

/* =========================================
   OBTENER VOLUNTARIOS DEL BORSÍ
========================================= */
$sqlBorsi = "
    SELECT b.idBorsi, v.DNI, v.nombre, v.apellidos, v.telefono, v.email, b.fechaInscripcion
    FROM Borsi b
    JOIN Voluntario v ON b.DNI = v.DNI
    WHERE b.codiAyuntamiento = '$codiAyuntamiento'
    ORDER BY b.fechaInscripcion ASC
";

$resBorsi = mysqli_query($con, $sqlBorsi);

/* =========================================
   OBTENER GRUPOS DEL AYUNTAMIENTO
========================================= */
$sqlGrupos = "
    SELECT idGrupo, nombre
    FROM Grupo
    WHERE codiAyuntamiento = '$codiAyuntamiento'
";

$resGrupos = mysqli_query($con, $sqlGrupos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Borsí de Voluntarios</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="container">

    <div class="box">
        <h2>Borsí de Voluntarios</h2>
        <p><strong>Ayuntamiento:</strong> <?php echo htmlspecialchars($codiAyuntamiento); ?></p>

        <!-- ============================= -->
        <!-- TABLA DE VOLUNTARIOS EN BORSÍ -->
        <!-- ============================= -->
        <?php if (mysqli_num_rows($resBorsi) > 0): ?>

            <table>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Fecha inscripción</th>
                    <th>Asignar a grupo</th>
                </tr>

                <?php while ($v = mysqli_fetch_assoc($resBorsi)): ?>
                    <tr>
                        <td><?= htmlspecialchars($v['DNI']) ?></td>
                        <td><?= htmlspecialchars($v['nombre']) ?></td>
                        <td><?= htmlspecialchars($v['apellidos']) ?></td>
                        <td><?= htmlspecialchars($v['email']) ?></td>
                        <td><?= htmlspecialchars($v['fechaInscripcion']) ?></td>

                        <td>
                            <form action="accion_borsi.php" method="POST">
                                <input type="hidden" name="DNI" value="<?= $v['DNI'] ?>">

                                <select name="idGrupo" required>
                                    <option value="">Seleccionar grupo</option>
                                    <?php
                                    mysqli_data_seek($resGrupos, 0);
                                    while ($g = mysqli_fetch_assoc($resGrupos)):
                                    ?>
                                        <option value="<?= $g['idGrupo'] ?>">
                                            <?= htmlspecialchars($g['nombre']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                                <button type="submit" name="accion" value="asignar">
                                    Añadir
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>

        <?php else: ?>
            <p>No hay voluntarios inscritos en el borsí.</p>
        <?php endif; ?>

        <p class="registro">
            <a href="../BD241660307Q/grupos_ayuntamiento.php">Volver a grupos</a>
        </p>
    </div>

</div>

</body>
</html>
