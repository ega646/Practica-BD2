<?php
include "conexionBD.php";

// Recoger datos del formulario
$DNI        = strtoupper(trim($_POST['DNI']));
$nombre     = trim($_POST['nombre']);
$apellidos  = trim($_POST['apellidos']);
$contraseña = trim($_POST['contraseña']);
$telefono   = trim($_POST['telefono']);
$email      = trim($_POST['email']);
$municipio  = trim($_POST['municipio']);

// ===============================
// 1️⃣ Comprobar que el municipio existe
// ===============================
$sqlAyto = "
    SELECT codiAyuntamiento
    FROM Ayuntamiento
    WHERE municipio = '$municipio'
";
$resAyto = mysqli_query($con, $sqlAyto);

if (!$resAyto || mysqli_num_rows($resAyto) === 0) {
    echo "❌ El municipio indicado no existe.";
    exit();
}

$ayto = mysqli_fetch_assoc($resAyto);
$codiAyuntamiento = $ayto['codiAyuntamiento'];

// ===============================
// 2️⃣ Insertar voluntario
// ===============================

// Comprobar si ya existe el voluntario
$sqlCheckVol = "SELECT DNI FROM Voluntario WHERE DNI = '$DNI'";
$resCheckVol = mysqli_query($con, $sqlCheckVol);

if (mysqli_num_rows($resCheckVol) > 0) {
    echo "❌ Ya existe un voluntario con ese DNI.";
    exit();
}

$sqlVol = "
    INSERT INTO Voluntario (DNI, nombre, apellidos, contraseña, telefono, email)
    VALUES ('$DNI', '$nombre', '$apellidos', '$contraseña', '$telefono', '$email')
";

if (!mysqli_query($con, $sqlVol)) {
    echo "❌ Error al crear el voluntario.";
    exit();
}

// ===============================
// 3️⃣ Comprobar que NO está ya en un grupo de ese ayuntamiento
// ===============================
$sqlGrupo = "
    SELECT g.idGrupo
    FROM Grupo g
    JOIN Ayuda a ON g.idGrupo = a.idGrupo
    WHERE a.DNI = '$DNI'
      AND g.codiAyuntamiento = '$codiAyuntamiento'
";
$resGrupo = mysqli_query($con, $sqlGrupo);

if (mysqli_num_rows($resGrupo) > 0) {
    echo "❌ El voluntario ya pertenece a un grupo de este ayuntamiento.";
    exit();
}

// ===============================
// 4️⃣ Insertar en el Borsí municipal
// ===============================
$fecha = date('Y-m-d');

$sqlBorsi = "
    INSERT INTO Borsi (codiAyuntamiento, DNI, fechaInscripcion)
    VALUES ('$codiAyuntamiento', '$DNI', '$fecha')
";

if (!mysqli_query($con, $sqlBorsi)) {
    echo "❌ Error al inscribir al voluntario en el borsí.";
    exit();
}

// ===============================
// 5️⃣ Éxito
// ===============================
echo "✅ Registro completado correctamente.<br>";
echo "Has sido inscrito en el borsí municipal de <strong>$municipio</strong>.<br><br>";
echo '<a href="index.php">Ir al login</a>';
?>
