<?php
session_start();
include "conexionBD.php";

$usuario = $_POST['usuario'];
$password = $_POST['password'];
$tipo = $_POST['tipo'];

if ($tipo == "ayuntamiento") {
    $sql = "SELECT codiAyuntamiento AS usuario, contraseña, municipio 
            FROM Ayuntamiento 
            WHERE codiAyuntamiento = '$usuario'";
} else {
    $sql = "SELECT DNI AS usuario, contraseña, nombre 
            FROM Voluntario 
            WHERE DNI = UPPER('$usuario')";
}

// Ejecutar consulta
$resultado = mysqli_query($con, $sql);

if ($resultado && mysqli_num_rows($resultado) === 1) {

    $datos = mysqli_fetch_assoc($resultado);

    if ($datos["contraseña"] === $password) { 
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["tipo"] = $tipo;

        if ($tipo == "ayuntamiento") {
            header("Location: BD241660307Q/colonias.php");
        } else {
            header("Location: BD243478494F/grupos.php");
        }
        exit();

    } else {
        echo "Contraseña incorrecta";
    }

} else {
    echo "Usuario no encontrado";
}

?>
