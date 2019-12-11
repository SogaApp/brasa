DELIMITER $$

USE `bdbrasa`$$

DROP PROCEDURE IF EXISTS `spRhuHorarioAcceso`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spRhuHorarioAcceso`(IN fecha DATE)
BEGIN
	DECLARE terminada BOOLEAN DEFAULT FALSE;
	DECLARE codigoContrato INTEGER;
	DECLARE codigoEmpleado INTEGER;
	DECLARE codigoHorarioPeriodo INTEGER;
	DECLARE codigoHorario INTEGER;
	DECLARE codigoTurno VARCHAR(5);
	DECLARE diaSemana INTEGER;
	DECLARE salidaDiaSiguiente TINYINT DEFAULT 0;
	DECLARE horaEntradaTurno TIME;
	DECLARE horaSalidaTurno TIME;
	DECLARE fechaSalida DATE;
	DECLARE generaHoraExtra TINYINT;
	
	DECLARE c1 CURSOR FOR SELECT codigo_contrato_pk, codigo_empleado_fk FROM rhu_contrato WHERE fecha_hasta <= fecha OR indefinido = 1;
	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET terminada = TRUE;
	#Crear periodo
	INSERT INTO rhu_horario_periodo (fecha_periodo, estado_generado) VALUES (fecha, 1);
	SELECT codigo_horario_periodo_pk INTO codigoHorarioPeriodo FROM rhu_horario_periodo WHERE fecha_periodo = fecha;
	SELECT DAYOFWEEK(fecha) INTO diaSemana;
	OPEN c1;
	c1_loop: LOOP
	FETCH c1 INTO codigoContrato, codigoEmpleado;
		IF `terminada` THEN LEAVE c1_loop; END IF;
		SELECT codigo_horario_fk INTO codigoHorario FROM rhu_empleado WHERE codigo_empleado_pk = codigoEmpleado;	
		IF diaSemana = 2 THEN
			SELECT lunes INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		IF diaSemana = 3 THEN
			SELECT martes INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		IF diaSemana = 4 THEN
			SELECT miercoles INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		IF diaSemana = 5 THEN
			SELECT jueves INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;		
		IF diaSemana = 6 THEN
			SELECT viernes INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		IF diaSemana = 7 THEN
			SELECT sabado INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;
		IF diaSemana = 1 THEN
			SELECT domingo INTO codigoTurno FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		END IF;	
		
		SELECT genera_hora_extra INTO generaHoraExtra FROM rhu_horario WHERE codigo_horario_pk = codigoHorario;
		
		SELECT salida_dia_siguiente, hora_desde, hora_hasta INTO salidaDiaSiguiente, horaEntradaTurno, horaSalidaTurno FROM rhu_turno WHERE codigo_turno_pk = codigoTurno COLLATE utf8_spanish_ci;
		IF salidaDiaSiguiente = 1 THEN
			SET fechaSalida = ADDDATE(fecha, INTERVAL 1 DAY);
		ELSE
			SET fechaSalida = fecha;
		END IF;		
		INSERT INTO rhu_horario_acceso (codigo_horario_periodo_fk, codigo_empleado_fk, codigo_turno_fk, hora_entrada_turno, hora_salida_turno, salida_dia_siguiente, fecha_entrada, fecha_salida, genera_hora_extra) VALUES (codigoHorarioPeriodo, codigoEmpleado, codigoTurno, horaEntradaTurno, horaSalidaTurno, salidaDiaSiguiente, fecha, fechaSalida, generaHoraExtra);
	END LOOP c1_loop;
	CLOSE c1;
	#DECLARE done BOOLEAN DEFAULT FALSE;
	#DECLARE uid integer;
	#DECLARE newdate integer;
	#DECLARE c1 cursor for SELECT id,timestamp from employers ORDER BY id ASC;
	#DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = TRUE;
	#open c1;
	#c1_loop: LOOP
	#fetch c1 into uid,newdate;
	#        IF `done` THEN LEAVE c1_loop; END IF; 
	#        UPDATE calendar SET timestamp = newdate  WHERE id=uid;
	#END LOOP c1_loop;
	#CLOSE c1;
END$$

DELIMITER ;



DELIMITER $$

USE `bdbrasa`$$

DROP PROCEDURE IF EXISTS `spRhuHorarioRegistro`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `spRhuHorarioRegistro`(IN codigoEmpleadoParametro INTEGER, IN fecha DATE, IN hora TIME, IN tipo TINYINT)
BEGIN		
	DECLARE codigoEmpleado INTEGER DEFAULT 0;
	DECLARE codigoHorarioPeriodo INTEGER DEFAULT 0;	
	DECLARE codigoHorarioPeriodoAnterior INTEGER DEFAULT 0;	
	DECLARE fechaAnterior DATE;
	DECLARE codigoHorarioAcceso INTEGER DEFAULT 0;
	DECLARE codigoHorarioAccesoAnterior INTEGER DEFAULT 0;	
	DECLARE entrada TINYINT;
	DECLARE salida TINYINT;
	DECLARE duracion DOUBLE DEFAULT 0;
	DECLARE generaHoraExtra TINYINT DEFAULT 0;	
	
	DECLARE fechaHoraEntrada DATETIME;
	DECLARE fechaHoraEntradaPago DATETIME;
	DECLARE fechaHoraEntradaTurno DATETIME;
	DECLARE fechaEntradaTurno DATE;
	DECLARE horaEntradaTurno TIME;
	DECLARE entradaTarde TINYINT DEFAULT 0;
	DECLARE diferenciaEntrada DOUBLE;	
	
	DECLARE fechaHoraSalida DATETIME;	
	DECLARE fechaHoraSalidaPago DATETIME;	
	DECLARE fechaHoraSalidaTurno DATETIME;
	DECLARE fechaSalidaTurno DATE;
	DECLARE horaSalidaTurno TIME;		
	DECLARE salidaAntes TINYINT DEFAULT 0;
	DECLARE diferenciaSalida DOUBLE;
	
	
	SELECT codigo_horario_periodo_pk INTO codigoHorarioPeriodo FROM rhu_horario_periodo WHERE fecha_periodo = fecha;
	#Si hay periodo de la fecha
	IF codigoHorarioPeriodo <> 0 THEN		
		SELECT codigo_empleado_pk INTO codigoEmpleado FROM rhu_empleado WHERE codigo_empleado_pk = codigoEmpleadoParametro;
		#Si el empleado existe
		IF codigoEmpleado <> 0 THEN		
			#Verificar salida pendiente del dia anterior
			SET fechaAnterior = ADDDATE(fecha, INTERVAL -1 DAY);			 	 
			SELECT codigo_horario_periodo_pk INTO codigoHorarioPeriodoAnterior FROM rhu_horario_periodo WHERE fecha_periodo = fechaAnterior;			
			IF codigoHorarioPeriodoAnterior <> 0 THEN
				SELECT codigo_horario_acceso_pk INTO codigoHorarioAccesoAnterior FROM rhu_horario_acceso WHERE codigo_horario_periodo_fk = codigoHorarioPeriodoAnterior AND codigo_empleado_fk = codigoEmpleado AND salida_dia_siguiente = 1 AND estado_entrada = 1 AND estado_salida = 0;
			END IF;
			#Si no existen salidas pendientes del dia anterior
			IF codigoHorarioAccesoAnterior = 0 THEN
				SELECT codigo_horario_acceso_pk, estado_entrada, fecha_entrada, hora_entrada_turno, estado_salida, fecha_salida, hora_salida_turno, genera_hora_extra INTO codigoHorarioAcceso, entrada, fechaEntradaTurno, horaEntradaTurno, salida, fechaSalidaTurno, horaSalidaTurno, generaHoraExtra  FROM rhu_horario_acceso WHERE codigo_horario_periodo_fk = codigoHorarioPeriodo AND codigo_empleado_fk = codigoEmpleado;
				#Si el empleado tiene registro creado para ese dia
				IF codigoHorarioAcceso <> 0 THEN
					#Si la accion es una entrada
					IF tipo = 0 THEN
						#Si no ha entrado
						IF entrada = 0 THEN
							SET fechaHoraEntradaTurno = CONCAT(fechaEntradaTurno,' ',horaEntradaTurno);
							SET fechaHoraEntrada = CONCAT(fecha,' ',hora);						
							IF fechaHoraEntrada >  fechaHoraEntradaTurno THEN							
								SET entradaTarde = 1;			
								SET diferenciaEntrada = TIMEDIFF(fechaHoraEntrada, fechaHoraEntradaTurno);
								SET diferenciaEntrada = (diferenciaEntrada * 60) + MINUTE(TIMEDIFF(fechaHoraEntrada, fechaHoraEntradaTurno));																																									
								SET fechaHoraEntradaPago = fechaHoraEntrada;
							ELSE
								SET fechaHoraEntradaPago = fechaHoraEntradaTurno;
							END IF;
							UPDATE rhu_horario_acceso SET estado_entrada = 1, fecha_entrada = fechaHoraEntrada, entrada_tarde = entradaTarde, duracion_entrada_tarde =  diferenciaEntrada, fecha_entrada_pago = fechaHoraEntradaPago WHERE codigo_horario_acceso_pk = codigoHorarioAcceso;											
						END IF;
					END IF;
					#Si la accion es una salida
					IF tipo = 1 THEN	
						IF entrada = 1 THEN					
							#Verificar que no tenga salida							
							IF salida = 0 THEN								
								SET fechaHoraSalidaTurno = CONCAT(fechaSalidaTurno,' ',horaSalidaTurno);
								SET fechaHoraSalida = CONCAT(fecha,' ',hora);
								IF fechaHoraSalida < fechaHoraSalidaTurno THEN
									SET salidaAntes = 1;	
									SET diferenciaSalida = TIMEDIFF(fechaHoraSalidaTurno, fechaHoraSalida);	
									SET diferenciaSalida = (diferenciaSalida * 60) + MINUTE(TIMEDIFF(fechaHoraSalidaTurno, fechaHoraSalida));										
									SET fechaHoraSalidaPago = fechaHoraSalida;								
								ELSE 
									IF generaHoraExtra = 1 THEN
										SET fechaHoraSalidaPago = fechaHoraSalida;
									ELSE
										SET fechaHoraSalidaPago = fechaHoraSalidaTurno;
									END IF;
									
								END IF;	
								SELECT fecha_entrada INTO fechaHoraEntrada FROM rhu_horario_acceso WHERE codigo_horario_acceso_pk = codigoHorarioAcceso;
								SET duracion = TIMEDIFF(fechaHoraSalida, fechaHoraEntrada);	
								SET duracion = (duracion * 60) + MINUTE(TIMEDIFF(fechaHoraSalida, fechaHoraEntrada));																																																		
								UPDATE rhu_horario_acceso SET estado_salida = 1, fecha_salida = fechaHoraSalida, salida_antes = salidaAntes, duracion_salida_antes = diferenciaSalida, duracion_registro = duracion, fecha_salida_pago = fechaHoraSalidaPago WHERE codigo_horario_acceso_pk = codigoHorarioAcceso;																						
							END IF;	
						END IF;
					END IF;										
				END IF;
			END IF;		
		END IF;
		#insert into prueba values (codigoEmpleado);	
	END IF;	
	
END$$

DELIMITER ;