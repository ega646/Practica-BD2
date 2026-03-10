<?php
include "../conexionBD.php";
session_start();

// Recoger datos del formulario
$accion = $_POST['accion'];
$id = $_POST['idGrupo'];
$nombre = $_POST['nombre'];
$codiAyuntamiento = $_POST['codiAyuntamiento'];
$responsableDNI = $_POST['responsableDNI'];

// ------------------- INSERTAR -------------------
if ($accion == "insertar") {
    if (!empty($nombre) && !empty($codiAyuntamiento)) {
        // Construir la parte de responsableDNI según si está vacío o no
        if (empty($responsableDNI)) {
            $consulta = "INSERT INTO Grupo (nombre, codiAyuntamiento, responsableDNI) 
                         VALUES ('$nombre', '$codiAyuntamiento', NULL)";
        } else {
            $consulta = "INSERT INTO Grupo (nombre, codiAyuntamiento, responsableDNI) 
                         VALUES ('$nombre', '$codiAyuntamiento', '$responsableDNI')";
        }

        if (mysqli_query($con, $consulta)) {
            echo "Grupo insertado correctamente.<br>";
        } else {
            echo "Error al insertar grupo: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "Faltan datos para insertar.<br>";
    }
}
// ------------------- MODIFICAR -------------------
elseif ($accion == "modificar") {
    if (!empty($id) && !empty($nombre)) {
        // Verificar que el grupo pertenece a este ayuntamiento antes de modificarlo
        $verificar = "SELECT idGrupo FROM Grupo WHERE idGrupo = $id AND codiAyuntamiento = '$codiAyuntamiento'";
        $resultado = mysqli_query($con, $verificar);

        if (mysqli_num_rows($resultado) > 0) {
            // Construir la parte de responsableDNI según si está vacío o no
            if (empty($responsableDNI)) {
                $consulta = "UPDATE Grupo SET 
                             nombre = '$nombre', 
                             responsableDNI = NULL 
                             WHERE idGrupo = $id AND codiAyuntamiento = '$codiAyuntamiento'";
            } else {
                $consulta = "UPDATE Grupo SET 
                             nombre = '$nombre', 
                             responsableDNI = '$responsableDNI' 
                             WHERE idGrupo = $id AND codiAyuntamiento = '$codiAyuntamiento'";
            }

            if (mysqli_query($con, $consulta)) {
                echo "Grupo modificado correctamente.<br>";
            } else {
                echo "Error al modificar grupo: " . mysqli_error($con) . "<br>";
            }
        } else {
            echo "Error: El grupo no existe o no tienes permisos para modificarlo.<br>";
        }
    } else {
        echo "Faltan datos para modificar.<br>";
    }
}
// ------------------- ELIMINAR -------------------
elseif ($accion == "eliminar") {
    if (!empty($id)) {
        // Verificar que el grupo pertenece a este ayuntamiento antes de eliminarlo
        $verificar = "SELECT idGrupo FROM Grupo WHERE idGrupo = $id AND codiAyuntamiento = '$codiAyuntamiento'";
        $resultado = mysqli_query($con, $verificar);

        if (mysqli_num_rows($resultado) > 0) {
            $consulta = "DELETE FROM Grupo WHERE idGrupo = $id AND codiAyuntamiento = '$codiAyuntamiento'";
            if (mysqli_query($con, $consulta)) {
                echo "Grupo eliminado correctamente.<br>";
            } else {
                echo "Error al eliminar grupo: " . mysqli_error($con) . "<br>";
            }
        } else {
            echo "Error: El grupo no existe o no tienes permisos para eliminarlo.<br>";
        }
    } else {
        echo "Debes indicar un ID para eliminar.<br>";
    }
} else {
    echo "Acción no válida.<br>";
}

// Volver atrás
echo '<br><a href="grupos_ayuntamiento.php">Volver</a>';
