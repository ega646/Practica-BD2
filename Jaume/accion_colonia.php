<?php
include "../conexionBD.php";
session_start();

// Recoger datos del formulario
$accion = $_POST['accion'];
$id = $_POST['idColonia'];
$nombre = $_POST['nombre'];
$codi = $_POST['codiAyuntamiento'];


// ------------------- INSERTAR -------------------
if ($accion == "insertar") {
    if (!empty($nombre) && !empty($codi)) {
        $consulta = "INSERT INTO Colonia (nombre, codiAyuntamiento) VALUES ('$nombre', '$codi')";
        if(mysqli_query($con, $consulta)){
            echo "Colonia insertada correctamente.<br>";
        } else {
            echo "Error al insertar colonia.<br>";
        }
    } else {
        echo "Faltan datos para insertar.<br>";
    }
}
// ------------------- MODIFICAR -------------------
elseif ($accion == "modificar") {
    if (!empty($id) && !empty($nombre)) {
        // Verificar que la colonia pertenece a este ayuntamiento antes de modificarla
        $verificar = "SELECT idColonia FROM Colonia WHERE idColonia = $id AND codiAyuntamiento = '$codi'";
        $resultado = mysqli_query($con, $verificar);
        
        if (mysqli_num_rows($resultado) > 0) {
            $consulta = "UPDATE Colonia SET nombre = '$nombre' WHERE idColonia = $id AND codiAyuntamiento = '$codi'";
            if(mysqli_query($con, $consulta)){
                echo "Colonia modificada correctamente.<br>";
            } else {
                echo "Error al modificar colonia.<br>";
            }
        } else {
            echo "Error: La colonia no existe o no tienes permisos para modificarla.<br>";
        }
    } else {
        echo "Faltan datos para modificar.<br>";
    }
}
// ------------------- ELIMINAR -------------------
elseif ($accion == "eliminar") {
    if (!empty($id)) {
        // Verificar que la colonia pertenece a este ayuntamiento antes de eliminarla
        $verificar = "SELECT idColonia FROM Colonia WHERE idColonia = $id AND codiAyuntamiento = '$codi'";
        $resultado = mysqli_query($con, $verificar);
        
        if (mysqli_num_rows($resultado) > 0) {
            $consulta = "DELETE FROM Colonia WHERE idColonia = $id AND codiAyuntamiento = '$codi'";
            if(mysqli_query($con, $consulta)){
                echo "Colonia eliminada correctamente.<br>";
            } else {
                echo "Error al eliminar colonia.<br>";
            }
        } else {
            echo "Error: La colonia no existe o no tienes permisos para eliminarla.<br>";
        }
    } else {
        echo "Debes indicar un ID para eliminar.<br>";
    }
}
else {
    echo "Acción no válida.<br>";
}

// Volver atrás
echo '<br><a href="colonias.php">Volver</a>';