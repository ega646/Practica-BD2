--REPAIR TABLE mysql.event;

SET GLOBAL event_scheduler = ON;

DELIMITER //

CREATE EVENT event_BackupDiarioPoblacion
ON SCHEDULE EVERY 1 DAY
STARTS TIMESTAMP(CURRENT_DATE, '23:59:59')
DO
BEGIN
    CALL BackupPoblacionColonias();
END //

