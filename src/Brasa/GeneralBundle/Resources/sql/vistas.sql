SELECT
	`car_cuenta_cobrar`.`codigo_cuenta_cobrar_pk` AS `codigoCuentaCobrarPk`,
	`car_cuenta_cobrar`.`codigo_cliente_fk` AS `codigo_cliente_fk`,
	`car_cuenta_cobrar`.`codigo_asesor_fk` AS `codigoAsesorFk`,
	`car_cuenta_cobrar`.`codigo_cuenta_cobrar_tipo_fk` AS `codigoCuentaCobrarTipoFk`,
	`car_cuenta_cobrar`.`numero_documento` AS `numeroDocumento`,
	`car_cuenta_cobrar`.`fecha` AS `fecha`,
	`car_cuenta_cobrar`.`fecha_vence` AS `fechaVence`,
	`car_cuenta_cobrar`.`plazo` AS `plazo`,
	`car_cuenta_cobrar`.`valor_original` AS `valorOriginal`,
	`car_cuenta_cobrar`.`abono` AS `abono`,
	`car_cuenta_cobrar`.`saldo` AS `saldo`,
	`car_cuenta_cobrar`.`codigo_factura` AS `codigoFactura`,
	`car_cuenta_cobrar`.`soporte` AS `soporte`,
	`car_cuenta_cobrar`.`grupo` AS `grupo`,
	`car_cuenta_cobrar`.`subgrupo` AS `subgrupo`,
	(
		to_days(now()) - to_days(
			`car_cuenta_cobrar`.`fecha_vence`
		)
	) AS `diasVencida`,
	(
		CASE
		WHEN (
			(
				to_days(now()) - to_days(
					`car_cuenta_cobrar`.`fecha_vence`
				)
			) < 1
		) THEN
			_utf8 'Por Vencer'
		ELSE
			_utf8 'Vencida'
		END
	) AS `tipoVencimiento`,
	(
		CASE
		WHEN (
			(
				to_days(now()) - to_days(
					`car_cuenta_cobrar`.`fecha_vence`
				)
			) < 1
		) THEN
			_utf8 'Por Vencer'
		ELSE
			(
				CASE
				WHEN (
					(
						to_days(now()) - to_days(
							`car_cuenta_cobrar`.`fecha_vence`
						)
					) BETWEEN 0
					AND 30
				) THEN
					_utf8 '30'
				ELSE
					(
						CASE
						WHEN (
							(
								to_days(now()) - to_days(
									`car_cuenta_cobrar`.`fecha_vence`
								)
							) BETWEEN 31
							AND 60
						) THEN
							_utf8 '60'
						ELSE
							(
								CASE
								WHEN (
									(
										to_days(now()) - to_days(
											`car_cuenta_cobrar`.`fecha_vence`
										)
									) BETWEEN 61
									AND 90
								) THEN
									_utf8 '90'
								ELSE
									(
										CASE
										WHEN (
											(
												to_days(now()) - to_days(
													`car_cuenta_cobrar`.`fecha_vence`
												)
											) BETWEEN 91
											AND 180
										) THEN
											_utf8 '180'
										ELSE
											_utf8 'Mas de 180'
										END
									)
								END
							)
						END
					)
				END
			)
		END
	) AS `rango`,
	`car_cliente`.`nombre_corto` AS `nombreCliente`,
	`car_cliente`.`nit` AS `nitCliente`,
	`gen_asesor`.`nombre` AS `nombreAsesor`,
	`car_cuenta_cobrar_tipo`.`nombre` AS `tipoCuentaCobrar`
FROM
	(
		(
			(
				`car_cuenta_cobrar`
				LEFT JOIN `car_cuenta_cobrar_tipo` ON (
					(
						`car_cuenta_cobrar`.`codigo_cuenta_cobrar_tipo_fk` = `car_cuenta_cobrar_tipo`.`codigo_cuenta_cobrar_tipo_pk`
					)
				)
			)
			LEFT JOIN `car_cliente` ON (
				(
					`car_cuenta_cobrar`.`codigo_cliente_fk` = `car_cliente`.`codigo_cliente_pk`
				)
			)
		)
		LEFT JOIN `gen_asesor` ON (
			(
				`car_cuenta_cobrar`.`codigo_asesor_fk` = `gen_asesor`.`codigo_asesor_pk`
			)
		)
	)
WHERE
	(
		`car_cuenta_cobrar`.`saldo` > 0
	)