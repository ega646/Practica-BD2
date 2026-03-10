<?php
session_start();
include "../conexionBD.php";

// Recibir datos del formulario
$XIP = $_POST['XIP'];
$idColoniaDetectada = $_POST['idColoniaDetectada'];
$dataDeteccion = isset($_POST['dataDeteccion']) ? $_POST['dataDeteccion'] : null;

// Validar datos básicos
if (empty($XIP) || empty($idColoniaDetectada)) {
    echo "Error: XIP y Colonia son campos obligatorios";
    exit();
}

// Verificar que el gato existe
$sql_gato = "SELECT XIP FROM Gato WHERE XIP = '$XIP'";
$resultado_gato = mysqli_query($con, $sql_gato);

if (!$resultado_gato || mysqli_num_rows($resultado_gato) === 0) {
    echo "Error: El gato con XIP '$XIP' no existe";
    exit();
}

// Verificar que la colonia existe
$sql_colonia = "SELECT idColonia FROM Colonia WHERE idColonia = $idColoniaDetectada";
$resultado_colonia = mysqli_query($con, $sql_colonia);

if (!$resultado_colonia || mysqli_num_rows($resultado_colonia) === 0) {
    echo "Error: La colonia con ID $idColoniaDetectada no existe";
    exit();
}

// Preparar la consulta de inserción
if ($dataDeteccion) {
    // Validar y formatear la fecha si se proporcionó
    $dataDeteccion = mysqli_real_escape_string($con, $dataDeteccion);
    $sql = "INSERT INTO Deteccion (XIP, idColoniaDetectada, dataDeteccion) 
            VALUES ('$XIP', $idColoniaDetectada, '$dataDeteccion')";
} else {
    // Usar fecha/hora actual por defecto
    $sql = "INSERT INTO Deteccion (XIP, idColoniaDetectada) 
            VALUES ('$XIP', $idColoniaDetectada)";
}

// Ejecutar la inserción
$resultado = mysqli_query($con, $sql);

if ($resultado) {
    // Éxito - redirigir según el tipo de usuario
        echo "Detección insertada correctamente";
} else {
    // Error en la inserción
    echo "Error al registrar la detección: " . mysqli_error($con);
}
// Volver atrás
echo "<br><a href='avistamiento.php?'>Volver</a>";
?>