DELIMITER $$

ALTER DEFINER=`root`@`localhost` EVENT `evRhuHorarioAcceso` ON SCHEDULE EVERY 1 DAY STARTS '2016-04-08 14:55:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL spRhuHorarioAcceso(NOW())$$

DELIMITER ;