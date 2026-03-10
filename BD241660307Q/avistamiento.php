<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informar avistamiento</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <div class="container">
        <h2>Insertar Nueva Detección</h2>
        
        <form action="procesar_deteccion.php" method="POST">
            <div class="form-group">
                <label for="XIP">XIP del Gato:</label>
                <input type="text" id="XIP" name="XIP" required maxlength="15" placeholder="Ej: XIP001">
            </div>
            
            <div class="form-group">
                <label for="idColoniaDetectada">ID Colonia Detectada:</label>
                <input type="number" id="idColoniaDetectada" name="idColoniaDetectada" required min="1" placeholder="Ej: 1">
            </div>
            
            <div class="form-group">
                <label for="dataDeteccion">Fecha y Hora (opcional):</label>
                <input type="datetime-local" id="dataDeteccion" name="dataDeteccion">
                <small>Dejar en blanco para usar fecha/hora actual</small>
            </div>
            
            <button type="submit">Insertar Detección</button>
        </form>
    </div>
    <a href="../BD243478494F/grupos.php">Volver a grupos</a>
</body>
</html>