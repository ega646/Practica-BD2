<?php
include "../conexionBD.php";

// Recoger datos del formulario
$accion = $_POST['accion'];
$XIP = $_POST['XIP'];
$nombre = $_POST['nombre'];
$sexo = $_POST['sexo'];
$edad = $_POST['edad'];
$aspecto = $_POST['aspecto'];
$foto = $_POST['foto'];
$idColonia = $_POST['idColonia'];

// ------------------- INSERTAR -------------------
if ($accion == "insertar") {
    if (!empty($XIP)) {
        $consultaG = "INSERT INTO Gato (XIP,nombre, sexo,edad, aspecto, foto) VALUES ('$XIP','$nombre','$sexo','$edad', '$aspecto', '$foto')";
        if (mysqli_query($con, $consultaG)) {
            // Insertar en Pertenencia con fechaInicio actual
            $fechaHoy = date('Y-m-d');
            $consultaP = "INSERT INTO Pertenencia (XIP, idColonia, fechaInicio, fechaFin) VALUES ('$XIP', $idColonia, '$fechaHoy', NULL)";
            if (mysqli_query($con, $consultaP)) {
                echo "Gato insertado correctamente.<br>";
            } else {
                echo "Error al insertar pertenencia.<br>";
            }
        } else {
            echo "Error al insertar gato.<br>";
        }
    } else {
        echo "Falta el XIP para insertar.<br>";
    }
}
// ------------------- MODIFICAR -------------------
elseif ($accion == "modificar") {
    if (!empty($XIP) && !empty($idColonia)) {
        // Verificar que el gato pertenece ACTUALMENTE a esta colonia (fechaFin NULL)
        $verificar = "SELECT XIP FROM Pertenencia WHERE XIP = '$XIP' AND idColonia = $idColonia AND fechaFin IS NULL";
        $resultado = mysqli_query($con, $verificar);

        if (mysqli_num_rows($resultado) > 0) {
            $consulta = "UPDATE Gato SET nombre='$nombre', sexo='$sexo', edad='$edad', aspecto='$aspecto', foto='$foto' WHERE XIP='$XIP'";
            if (mysqli_query($con, $consulta)) {
                echo "Gato modificado correctamente.<br>";
            } else {
                echo "Error al modificar gato.<br>";
            }
        } else {
            echo "Error: El gato no existe o no pertenece actualmente a esta colonia.<br>";
        }
    } else {
        echo "Faltan datos para modificar.<br>";
    }
}
// ------------------- ELIMINAR -------------------
elseif ($accion == "eliminar") {
    if (!empty($XIP) && !empty($idColonia)) {
        // Verificar que el gato pertenece ACTUALMENTE a esta colonia (fechaFin NULL)
        $verificar = "SELECT XIP FROM Pertenencia WHERE XIP = '$XIP' AND idColonia = $idColonia AND fechaFin IS NULL";
        $resultado = mysqli_query($con, $verificar);

        if (mysqli_num_rows($resultado) > 0) {
            // Para eliminar, establecer fechaFin en la fecha actual (en lugar de eliminar el registro)
            $fechaHoy = date('Y-m-d');
            $consultaP = "UPDATE Pertenencia SET fechaFin = '$fechaHoy' WHERE XIP='$XIP' AND idColonia=$idColonia AND fechaFin IS NULL";
            
            if (mysqli_query($con, $consultaP)) {
                // Verificar si el gato está activo en otras colonias
                $consultaC = "SELECT COUNT(*) as cnt FROM Pertenencia WHERE XIP='$XIP' AND fechaFin IS NULL";
                $resultadoC = mysqli_query($con, $consultaC);
                $numeroC = mysqli_fetch_assoc($resultadoC);
                
                if ($numeroC['cnt'] == 0) {
                    //eliminar el gato si no está activo en ninguna colonia
                    $consultaG = "DELETE FROM Gato WHERE XIP='$XIP'";
                    mysqli_query($con, $consultaG);
                    echo "Gato eliminado completamente del sistema.<br>";
                }
                echo "Gato eliminado de la colonia correctamente (fechaFin establecida).<br>";
            } else {
                echo "Error al eliminar gato de la colonia.<br>";
            }
        } else {
            echo "Error: El gato no existe o no pertenece actualmente a esta colonia.<br>";
        }
    } else {
        echo "Faltan datos para eliminar.<br>";
    }
} else {
    echo "Acción no válida.<br>";
}

// Volver atrás
echo "<br><a href='gatos.php?id=$idColonia'>Volver a Gatos</a>";