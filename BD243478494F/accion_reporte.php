<?php
session_start();
include "../conexionBD.php";

// Comprobar login y tipo de usuario
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] !== "voluntario") {
    echo "Acceso no permitido.";
    exit();
}

$dni = $_SESSION["usuario"];
$accion = $_POST['accion'] ?? "";

// Validar acción
if ($accion !== "insertarReporte" && $accion !== "borrarReporte") {
    echo "Acción no válida.";
    exit();
}

// Obtener grupo real del voluntario
$consultaGrupo = "
    SELECT g.idGrupo, g.responsableDNI
    FROM Grupo g
    JOIN Ayuda a ON g.idGrupo = a.idGrupo
    WHERE a.DNI = '$dni'
    LIMIT 1
";
$resGrupo = mysqli_query($con, $consultaGrupo);

if (!$resGrupo || mysqli_num_rows($resGrupo) == 0) {
    echo "No perteneces a ningún grupo.";
    exit();
}

$grupo = mysqli_fetch_assoc($resGrupo);
$idGrupo = $grupo['idGrupo'];
$responsable = $grupo['responsableDNI'];

// Comprobar si el usuario es responsable
if ($dni !== $responsable) {
    echo "No tienes permisos para gestionar reportes.";
    exit();
}

/* =====================================================
   INSERTAR REPORTE
   ===================================================== */
if ($accion === "insertarReporte") {

    $asunto = trim($_POST['asunto'] ?? "");
    $cuerpo = trim($_POST['cuerpo'] ?? "");

    if ($asunto === "" || $cuerpo === "") {
        echo "Todos los campos son obligatorios.<br>";
        exit();
    }

    $fechaHoy = date("Y-m-d");

    $sql = "
        INSERT INTO Reporte (fecha, asunto, cuerpo, idGrupo)
        VALUES ('$fechaHoy', '$asunto', '$cuerpo', '$idGrupo')
    ";

    if (mysqli_query($con, $sql)) {
        echo "Reporte creado correctamente.<br>";
    } else {
        echo "Error al crear el reporte.<br>";
    }

    echo '<br><a href="grupos.php">Volver</a>';
    exit();
}


/* =====================================================
   BORRAR REPORTE
   ===================================================== */
if ($accion === "borrarReporte") {

    $codiReporte = $_POST['codiReporte'] ?? "";

    if (empty($codiReporte)) {
        echo "Debes indicar el código del reporte a borrar.<br>";
        exit();
    }

    // Confirmar que ese reporte pertenece al grupo del responsable
    $check = "
        SELECT codiReporte 
        FROM Reporte 
        WHERE codiReporte = '$codiReporte' AND idGrupo = '$idGrupo'
        LIMIT 1
    ";

    $resCheck = mysqli_query($con, $check);

    if (!$resCheck || mysqli_num_rows($resCheck) == 0) {
        echo "No puedes borrar este reporte (no existe o no pertenece a tu grupo).<br>";
        exit();
    }

    // Borrar reporte
    $sql = "DELETE FROM Reporte WHERE codiReporte = '$codiReporte'";

    if (mysqli_query($con, $sql)) {
        echo "Reporte eliminado correctamente.<br>";
    } else {
        echo "Error al eliminar el reporte.<br>";
    }

    echo '<br><a href="grupos.php">Volver</a>';
    exit();
}

?>
