DELIMITER //

CREATE TRIGGER trg_Deteccion_Albirament
BEFORE INSERT ON Deteccion
FOR EACH ROW
BEGIN
    DECLARE colonia_actual INT;
    
    -- Obtener la colonia actual del gato (última pertenencia con fechaFin = NULL)
    SELECT idColonia INTO colonia_actual
    FROM Pertenencia
    WHERE XIP = NEW.XIP AND fechaFin IS NULL
    ORDER BY fechaInicio DESC
    LIMIT 1;
    
    -- Si se ve en otra colonia no habitual
    IF colonia_actual IS NOT NULL AND colonia_actual != NEW.idColoniaDetectada THEN
        -- Insertar en ALBIRAMENT
        INSERT INTO ALBIRAMENT (XIP, idColoniaNova, observacions)
        VALUES (NEW.XIP, NEW.idColoniaDetectada, 
                CONCAT('Gato detectado en colonia diferente. Colonia anterior: ', colonia_actual));
        
        -- Actualizar la pertenencia del gato a la nueva colonia
        UPDATE Pertenencia 
        SET fechaFin = CURDATE()
        WHERE XIP = NEW.XIP AND fechaFin IS NULL;
        
        INSERT INTO Pertenencia (XIP, idColonia, fechaInicio)
        VALUES (NEW.XIP, NEW.idColoniaDetectada, CURDATE());
    END IF;
END //