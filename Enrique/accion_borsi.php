<?php
session_start();
include "../conexionBD.php";

/* =========================================
   COMPROBAR SESIÓN
========================================= */
if (!isset($_SESSION["usuario"]) || $_SESSION["tipo"] !== "ayuntamiento") {
    echo "Acceso no permitido.";
    exit();
}

$codiAyuntamiento = $_SESSION["usuario"];

/* =========================================
   COMPROBAR DATOS POST
========================================= */
if (!isset($_POST["accion"]) || $_POST["accion"] !== "asignar") {
    echo "Acción no válida.";
    exit();
}

if (empty($_POST["DNI"]) || empty($_POST["idGrupo"])) {
    echo "Faltan datos.";
    exit();
}

$dni = mysqli_real_escape_string($con, $_POST["DNI"]);
$idGrupo = (int) $_POST["idGrupo"];

/* =========================================
   COMPROBAR QUE EL GRUPO ES DEL AYUNTAMIENTO
========================================= */
$sqlGrupo = "
    SELECT idGrupo 
    FROM Grupo 
    WHERE idGrupo = $idGrupo
      AND codiAyuntamiento = '$codiAyuntamiento'
";

$resGrupo = mysqli_query($con, $sqlGrupo);

if (mysqli_num_rows($resGrupo) === 0) {
    echo "El grupo no pertenece a tu ayuntamiento.";
    exit();
}

/* =========================================
   COMPROBAR QUE EL VOLUNTARIO ESTÁ EN EL BORSÍ
========================================= */
$sqlBorsi = "
    SELECT idBorsi 
    FROM Borsi
    WHERE DNI = '$dni'
      AND codiAyuntamiento = '$codiAyuntamiento'
";

$resBorsi = mysqli_query($con, $sqlBorsi);

if (mysqli_num_rows($resBorsi) === 0) {
    echo "El voluntario no está en el borsí de este ayuntamiento.";
    exit();
}

/* =========================================
   COMPROBAR QUE NO ESTÁ YA EN ESE GRUPO
========================================= */
$sqlExiste = "
    SELECT 1
    FROM Ayuda
    WHERE DNI = '$dni'
      AND idGrupo = $idGrupo
";

$resExiste = mysqli_query($con, $sqlExiste);

if (mysqli_num_rows($resExiste) > 0) {
    echo "El voluntario ya pertenece a ese grupo.";
    exit();
}

/* =========================================
   INSERTAR EN EL GRUPO
========================================= */
$sqlInsert = "
    INSERT INTO Ayuda (observaciones, idGrupo, DNI)
    VALUES (NULL, $idGrupo, '$dni')
";

if (!mysqli_query($con, $sqlInsert)) {
    echo "Error al añadir al grupo.";
    exit();
}

/* =========================================
   ELIMINAR DEL BORSÍ
========================================= */
$sqlDelete = "
    DELETE FROM Borsi
    WHERE DNI = '$dni'
      AND codiAyuntamiento = '$codiAyuntamiento'
";

mysqli_query($con, $sqlDelete);

/* =========================================
   REDIRECCIÓN
========================================= */
header("Location: borsi.php");
exit();
