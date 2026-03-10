<?php
include "../conexionBD.php";

// Recoger datos del formulario
$accion = $_POST['accion'];
$latitud = $_POST['latitud'];
$longitud = $_POST['longitud'];
$descripcion = $_POST['descripcion'];
$comentarios = $_POST['comentarios'];
$idColonia = $_POST['idColonia'];

// ------------------- INSERTAR -------------------
if ($accion == "insertar") {
    if (!empty($latitud) && !empty($longitud) && !empty($idColonia)) {
        $consulta = "INSERT INTO Ubicacion (latitud, longitud, descripcion, comentarios, idColonia) 
                     VALUES ('$latitud', '$longitud', '$descripcion', '$comentarios', $idColonia)";
        
        if(mysqli_query($con, $consulta)){
            echo "Ubicación insertada correctamente.<br>";
        } else {
            echo "Error al insertar ubicación: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "Faltan datos para insertar (latitud, longitud e ID colonia son obligatorios).<br>";
    }
}
// ------------------- MODIFICAR -------------------
if ($accion == "modificar") {
    if (!empty($latitud) && !empty($longitud) && !empty($idColonia)) {
        $consulta = "UPDATE Ubicacion SET 
                     descripcion = '$descripcion', 
                     comentarios = '$comentarios' 
                     WHERE latitud = '$latitud' 
                     AND longitud = '$longitud' 
                     AND idColonia = $idColonia";
        
        if(mysqli_query($con, $consulta)){
            echo "Ubicación modificada correctamente.<br>";
        } else {
            echo "Error al modificar ubicación: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "Faltan datos para modificar (latitud, longitud e ID colonia son obligatorios).<br>";
    }
}
// ------------------- ELIMINAR -------------------
if ($accion == "eliminar") {
    if (!empty($latitud) && !empty($longitud) && !empty($idColonia)) {
        $consulta = "DELETE FROM Ubicacion 
                     WHERE latitud = '$latitud' 
                     AND longitud = '$longitud' 
                     AND idColonia = $idColonia";
        
        if(mysqli_query($con, $consulta)){
            echo "Ubicación eliminada correctamente.<br>";
        } else {
            echo "Error al eliminar ubicación: " . mysqli_error($con) . "<br>";
        }
    } else {
        echo "Debes indicar latitud, longitud e ID colonia para eliminar.<br>";
    }
}
// Volver atrás
echo '<br><a href="gatos.php?id=' . $idColonia . '">Volver</a>';