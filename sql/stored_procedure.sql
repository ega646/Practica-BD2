DELIMITER //
CREATE PROCEDURE BackupPoblacionColonias()
BEGIN
    DECLARE backup_fecha DATE;
    DECLARE contador INT DEFAULT 0;
    DECLARE acabar INT DEFAULT FALSE;
    
    -- Variables para Ayuntamiento
    DECLARE v_codiAyuntamiento VARCHAR(10);
    DECLARE v_municipio VARCHAR(50);
    DECLARE v_direccion VARCHAR(100);
    DECLARE v_telefono VARCHAR(15);
    DECLARE v_email VARCHAR(50);
    DECLARE v_codiIsla INT;
    
    -- Variables para Colonia
    DECLARE v_idColonia INT;
    DECLARE v_nombre_colonia VARCHAR(50);
    DECLARE v_codiAyuntamiento_col VARCHAR(10);
    
    -- Variables para Gato
    DECLARE v_XIP VARCHAR(15);
    DECLARE v_nombre_gato VARCHAR(30);
    DECLARE v_sexo ENUM('Macho','Hembra');
    DECLARE v_edad SMALLINT UNSIGNED;
    DECLARE v_aspecto VARCHAR(200);
    DECLARE v_foto VARCHAR(20);
    
    -- Variables para Ubicacion
    DECLARE v_idUbicacion INT;
    DECLARE v_latitud DECIMAL(10, 4);
    DECLARE v_longitud DECIMAL(11, 4);
    DECLARE v_descripcion VARCHAR(200);
    DECLARE v_comentarios VARCHAR(200);
    DECLARE v_idColonia_ub INT;
    
    -- Cursores
    DECLARE cur_ayuntamiento CURSOR FOR 
        SELECT codiAyuntamiento, municipio, direccion, telefono, email, codiIsla 
        FROM Ayuntamiento;
    
    DECLARE cur_colonia CURSOR FOR 
        SELECT idColonia, nombre, codiAyuntamiento 
        FROM Colonia;
    
    DECLARE cur_gato CURSOR FOR 
        SELECT XIP, nombre, sexo, edad, aspecto, foto 
        FROM Gato;
    
    DECLARE cur_ubicacion CURSOR FOR 
        SELECT idUbicacion, latitud, longitud, descripcion, comentarios, idColonia 
        FROM Ubicacion;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET acabar = TRUE;
    
    SET backup_fecha = CURDATE();
    
    -- Verificar si ya se realizó la copia de hoy
    SELECT COUNT(*) INTO contador 
    FROM Backup_Colonias_Poblacion 
    WHERE fechaBackup = backup_fecha
    LIMIT 1;
    
    IF contador = 0 THEN
        -- ========== COPIAR AYUNTAMIENTOS ==========
        OPEN cur_ayuntamiento;
        etiqueta_ayuntamiento: LOOP
            FETCH cur_ayuntamiento INTO v_codiAyuntamiento, v_municipio, v_direccion, v_telefono, v_email, v_codiIsla;
            IF acabar THEN LEAVE etiqueta_ayuntamiento; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'AYUNTAMIENTO|',
                    v_codiAyuntamiento, '|',
                    v_municipio, '|',
                    IFNULL(v_direccion, ''), '|',
                    IFNULL(v_telefono, ''), '|',
                    IFNULL(v_email, ''), '|',
                    v_codiIsla
                )
            );
        END LOOP;
        CLOSE cur_ayuntamiento;
        SET acabar = FALSE;
        
        -- ========== COPIAR COLONIAS ==========
        OPEN cur_colonia;
        etiqueta_colonia: LOOP
            FETCH cur_colonia INTO v_idColonia, v_nombre_colonia, v_codiAyuntamiento_col;
            IF acabar THEN LEAVE etiqueta_colonia; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'COLONIA|',
                    v_idColonia, '|',
                    v_nombre_colonia, '|',
                    v_codiAyuntamiento_col
                )
            );
        END LOOP;
        CLOSE cur_colonia;
        SET acabar = FALSE;
        
        -- ========== COPIAR GATOS ==========
        OPEN cur_gato;
        etiqueta_gato: LOOP
            FETCH cur_gato INTO v_XIP, v_nombre_gato, v_sexo, v_edad, v_aspecto, v_foto;
            IF acabar THEN LEAVE etiqueta_gato; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'GATO|',
                    v_XIP, '|',
                    IFNULL(v_nombre_gato, ''), '|',
                    v_sexo, '|',
                    IFNULL(v_edad, ''), '|',
                    IFNULL(v_aspecto, ''), '|',
                    IFNULL(v_foto, '')
                )
            );
        END LOOP;
        CLOSE cur_gato;
        SET acabar = FALSE;
        
        -- ========== COPIAR UBICACIONES ==========
        OPEN cur_ubicacion;
        etiqueta_ubicacion: LOOP
            FETCH cur_ubicacion INTO v_idUbicacion, v_latitud, v_longitud, v_descripcion, v_comentarios, v_idColonia_ub;
            IF acabar THEN LEAVE etiqueta_ubicacion; END IF;
            
            INSERT INTO Backup_Colonias_Poblacion (fechaBackup, datos_completos)
            VALUES (
                backup_fecha,
                CONCAT(
                    'UBICACION|',
                    v_idUbicacion, '|',
                    v_latitud, '|',
                    v_longitud, '|',
                    IFNULL(v_descripcion, ''), '|',
                    IFNULL(v_comentarios, ''), '|',
                    v_idColonia_ub
                )
            );
        END LOOP;
        CLOSE cur_ubicacion;
        
        SELECT 'Copia de seguridad realizada exitosamente' AS Resultado;
    ELSE
        SELECT 'La copia de seguridad para hoy ya fue realizada' AS Resultado;
    END IF;
END //