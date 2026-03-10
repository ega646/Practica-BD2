<?php
include "../conexionBD.php";
session_start();

// Recoger acción seleccionada
$accion = $_POST['accion'];

// DNI del voluntario objetivo (el que será modificado / añadido / eliminado)
$DNI = $_POST['DNI'] ?? "";

// Datos opcionales para modificación
$nombre = $_POST['nombre'] ?? "";
$apellidos = $_POST['apellidos'] ?? "";
$contraseña = $_POST['contraseña'] ?? "";
$telefono = $_POST['telefono'] ?? "";
$email = $_POST['email'] ?? "";

// DNI del voluntario que está logueado (el que realiza la acción)
$miDNI = $_SESSION["usuario"];

/* =====================================================
   OBTENER EL GRUPO REAL Y EL RESPONSABLE
   ===================================================== */
$consultaGrupo = "
    SELECT g.idGrupo, g.responsableDNI
    FROM Grupo g
    JOIN Ayuda a ON g.idGrupo = a.idGrupo
    WHERE a.DNI = '$miDNI'
";

$resGrupo = mysqli_query($con, $consultaGrupo);

if (!$resGrupo || mysqli_num_rows($resGrupo) == 0) {
    echo "No perteneces a ningún grupo.";
    exit();
}

$grupo = mysqli_fetch_assoc($resGrupo);
$idGrupo = $grupo["idGrupo"];
$responsable = $grupo["responsableDNI"];

/* =====================================================
   VALIDAR PERMISOS — SOLO EL RESPONSABLE PUEDE GESTIONAR
   ===================================================== */
if ($miDNI !== $responsable) {
    echo "No tienes permisos para realizar esta acción.";
    exit();
}

/* =====================================================
   INSERTAR — AÑADIR VOLUNTARIO AL GRUPO
   ===================================================== */
if ($accion == "insertar") {

    if (!empty($DNI)) {

        // Comprobar que el voluntario exista
        $check = "SELECT DNI FROM Voluntario WHERE DNI = '$DNI'";
        $resCheck = mysqli_query($con, $check);

        if (mysqli_num_rows($resCheck) == 0) {
            echo "El voluntario no existe. Debe estar creado en la tabla Voluntario primero.<br>";
            exit();
        }

        // Insertar relación en Ayuda
        $sql = "INSERT INTO Ayuda (idGrupo, DNI) VALUES ('$idGrupo', '$DNI')";

        if (mysqli_query($con, $sql)) {
            echo "Voluntario añadido al grupo correctamente.<br>";
        } else {
            echo "Error al añadir al grupo. Puede que ya pertenezca.<br>";
        }

    } else {
        echo "Debes indicar un DNI.<br>";
    }
}

/* =====================================================
   MODIFICAR — ACTUALIZAR DATOS DEL VOLUNTARIO
   ===================================================== */
if ($accion == "modificar") {

    if (!empty($DNI)) {

        $updates = [];

        if (!empty($nombre))        $updates[] = "nombre = '$nombre'";
        if (!empty($apellidos))     $updates[] = "apellidos = '$apellidos'";
        if (!empty($contraseña))    $updates[] = "contraseña = '$contraseña'";
        if (!empty($telefono))      $updates[] = "telefono = '$telefono'";
        if (!empty($email))         $updates[] = "email = '$email'";

        if (count($updates) > 0) {

            $sql = "UPDATE Voluntario SET " . implode(", ", $updates) . " WHERE DNI = '$DNI'";

            if (mysqli_query($con, $sql)) {
                echo "Datos modificados correctamente.<br>";
            } else {
                echo "Error al modificar datos.<br>";
            }

        } else {
            echo "No has introducido ningún dato para modificar.<br>";
        }

    } else {
        echo "Debes indicar un DNI.<br>";
    }
}

/* =====================================================
   ELIMINAR — SACAR VOLUNTARIO DEL GRUPO
   ===================================================== */
if ($accion == "eliminar") {

    if (!empty($DNI)) {

        $sql = "
            DELETE FROM Ayuda
            WHERE idGrupo = '$idGrupo' AND DNI = '$DNI'
        ";

        if (mysqli_query($con, $sql)) {
            echo "Voluntario eliminado del grupo.<br>";
        } else {
            echo "Error al eliminar del grupo.<br>";
        }

    } else {
        echo "Debes indicar un DNI.<br>";
    }
}

echo '<br><a href="grupos.php">Volver</a>';
?>
