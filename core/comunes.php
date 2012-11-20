<?php
	/*
		Title: Libreria base 
		Ubicacion *[/core/comunes.php]*.  Archivo que contiene las funciones de uso global.
	*/
	/*
		Section: Funciones asociadas a las operaciones con bases de datos - Ejecucion de consultas
	*/

/* ################################################################## */
/* ################################################################## */
	function ejecutar_sql($query,$param="")
		{
			/*
				Function: ejecutar_sql
				Ejecuta consultas que retornan registros (SELECTs).

				Variables de entrada:

					query - Consulta preformateada para ser ejecutada en el motor
					param - Lista de parametros que deben ser preparados para el query separados por coma
					
				Salida:
					Retorna mensaje en pantalla con la descripcion devuelta por el driver en caso de error
					Retorna una variable con el arreglo de resultados en caso de ser exitosa la consulta
			*/
			global $ConexionPDO;
			try
				{
					$consulta = $ConexionPDO->prepare($query);
					$consulta->execute();
					return $consulta;
					//return $consulta->fetchAll();
				}
			catch( PDOException $ErrorPDO)
				{
					mensaje('Error durante la ejecuci&oacute;n',$ErrorPDO->getMessage(),'90%','icono_error.png','TextosEscritorio');
					return 1;
				}
		}



/* ################################################################## */
/* ################################################################## */
	function ejecutar_sql_unaria($query,$param="")
		{
			/*
				Function: ejecutar_sql_unaria
				Ejecuta consultas que no retornan registros tales como CREATE, INSERT, DELETE, UPDATE entre otros.

				Variables de entrada:

					query - Consulta preformateada para ser ejecutada en el motor
					param - Lista de parametros que deben ser preparados para el query separados por coma

				Salida:
					Retorna una cadena que contiene una descripcion de error PDO en caso de error y agrega un mensaje en pantalla con la descripcion devuelta por el driver
					Retorna una cadena vacia si la consulta es ejecutada sin problemas.
			*/
			global $ConexionPDO;
			try
				{
					$consulta = $ConexionPDO->prepare($query);
					$consulta->execute();
					return "";
				}
			catch( PDOException $ErrorPDO)
				{
					echo '<script language="JavaScript"> alert("Ha ocurrido un error interno durante la ejecucion del Query: '.$query.'\n\nEl motor ha devuelto: '.$ErrorPDO->getMessage().'.\n\nPongase en contacto con el administrador del sistema y comunique este mensaje.");  </script>';					
					//mensaje('Error durante la ejecuci&oacute;n',$ErrorPDO->getMessage(),'90%','icono_error.png','TextosEscritorio');
					return $ErrorPDO->getMessage();
				}
		}



/* ################################################################## */
/* ################################################################## */
	function ejecutar_sql_procedimiento($procedimiento)
		{
			/*
				Function: ejecutar_sql_procedimiento
				Ejecuta procedimientos almacenados por la base de datos

				Variables de entrada:

					procedimiento - Procedimiento que debe residir en la base de datos y que ha de ser ejecutado

				Salida:
					Retorna 0 en caso de tener problemas con la ejecucion del procedimiento
					Retorna una cadena vacia si el procedimiento es llamado y ejecutado sin problemas
			*/
			global $ConexionPDO;
			try
				{
					$ConexionPDO->exec($procedimiento);
					return "";
				}
			catch(PDOException $e)
				{
					return $e->getMessage();
				}
		}



/* ################################################################## */
/* ################################################################## */
	function existe_valor($tabla,$campo,$valor)
		{
			/*
				Function: existe_valor
				Busca dentro de alguna tabla para verificar si existe o no un valor determinado.  Funcion utilizada para validacion de unicidad de valores en formularios de datos.
				
				Variables de entrada:

					tabla - Nombre de la tabla donde se desea buscar.
					campo - Campo de la tabla sobre el cual se desea comparar la existencia del valor.
					valor - Valor a buscar dentro del campo.
					
				Salida:
					Retorna 1 en caso de encontrar un valor dentro de la tabla y campo especificadas y que coincida con el parametro buscado
					Retorna 0 cuando no se encuentra un valor en la tabla que coincida con el buscado
			*/
			global $ConexionPDO;
			$consulta = $ConexionPDO->prepare("SELECT $campo FROM $tabla WHERE $campo='$valor'");
			$consulta->execute();
			$registro = $consulta->fetch();
			if ($registro[0]!="")
				{
					return 1;
				}
			else
				{
					return 0;
				}
		}



/* ################################################################## */
/* ################################################################## */
	/*
		Section: Funciones asociadas al retorno de informacion sobre la conexion y estructura de la BD
	*/
/* ################################################################## */
/* ################################################################## */
	function informacion_conexion()
		{
			/*
				Function: informacion_conexion
				Imprime la informacion asociada a la conexion establecida mediante PDO.

				Ver tambien:
				<imprimir_drivers_disponibles> | <Definicion de conexion PDO>
			*/
			echo "<hr><center><blink><b><font color=yellow>Informaci&oacute;n de conexi&oacute;n:</font></b></blink><br>";
			echo "Driver: ".$ConexionPDO->getAttribute(PDO::ATTR_DRIVER_NAME)."<br>";
			echo "Versi&oacute;n del servidor: ".$ConexionPDO->getAttribute(PDO::ATTR_SERVER_VERSION)."<br>";
			echo "Estado: ".$ConexionPDO->getAttribute(PDO::ATTR_CONNECTION_STATUS)."<br>";
			echo "Versi&oacute;n del cliente: ".$ConexionPDO->getAttribute(PDO::ATTR_CLIENT_VERSION)."<br>";
			echo "Informaci&oacute;n adicional: ".$ConexionPDO->getAttribute(PDO::ATTR_SERVER_INFO)."<hr>";
		}



/* ################################################################## */
/* ################################################################## */
	function imprimir_drivers_disponibles()
		{
			/*
				Function: imprimir_drivers_disponibles
				Imprime el arreglo devuelto por la funcion getAvailableDrivers() para conocer los drivers soportados por la instalacion actual de PHP del lado del servidor.

				Salida:
					Listado de drivers PDO soportados
				
				Ver tambien:
				<informacion_conexion>
			*/
			
			/*foreach(PDO::getAvailableDrivers() as $driver)
				{
					echo "<hr>".$driver;
				}*/
			print_r(PDO::getAvailableDrivers());
		}



/* ################################################################## */
/* ################################################################## */
	function consultar_tablas($prefijo="")
		{
			/*
				Function: consultar_tablas
				Determina las tablas en la base de datos activa para la conexion dependiendo del motor utilizado.

				Variables de entrada:

					prefijo - Prefijo del nombre de tablas que seran retornadas

				Salida:
					Resultado de un query con las tablas  o falso en caso de error
				
				Ver tambien:
				<Definicion de conexion PDO>
			*/
			global $ConexionPDO;
			global $MotorBD;
			global $BaseDatos;

			if($MotorBD=="sqlsrv" || $MotorBD=="mssql" || $MotorBD=="ibm" || $MotorBD=="dblib" || $MotorBD=="odbc" || $MotorBD=="sqlite2" || $MotorBD=="sqlite3")
					$consulta = "SELECT name FROM sysobjects WHERE xtype='U';";
			if($MotorBD=="oracle")
					$consulta = "SELECT table_name FROM cat;";  //  Si falla probar con esta:  $consulta = "SELECT table_name FROM tabs;";
			if($MotorBD=="ifmx" || $MotorBD=="fbd")
					$consulta = "SELECT RDB$RELATION_NAME FROM RDB$RELATIONS WHERE RDB$SYSTEM_FLAG = 0 AND RDB$VIEW_BLR IS NULL ORDER BY RDB$RELATION_NAME;";
			if($MotorBD=="mysql")
					$consulta = "SHOW tables FROM ".$BaseDatos." ";
			if($MotorBD=="pg")
					$consulta = "SELECT relname AS name FROM pg_stat_user_tables ORDER BY relname;";

			try
				{
					$consulta_tablas=ejecutar_sql($consulta);
					return $consulta_tablas;
				}
			catch( PDOException $ErrorPDO)
				{
					mensaje('Error durante la ejecuci&oacute;n',$ErrorPDO->getMessage(),'90%','icono_error.png','TextosEscritorio');
					return false;
				}
		}



/* ################################################################## */
/* ################################################################## */
	function consultar_columnas($tabla)
		{
			/*
				Function: consultar_columnas
				Devuelve un vector con los nombres de las columnas de una tabla

				Variables de entrada:

					tabla - Nombre de la tabla de la que se desea consultar los nombre de columnas o campos
					
				Salida:
					Vector de campos/columnas
				
				Ver tambien:
				<consultar_tablas>
			*/
			$resultado=ejecutar_sql("SELECT * FROM ".$tabla);
			$columnas = array();
			foreach($resultado->fetch(PDO::FETCH_ASSOC) as $key=>$val)
				{
					$columnas[] = $key;
				}
			return $columnas;
		}



/* ################################################################## */
/* ################################################################## */
	function consultar_bases_de_datos()
		{
			/*
				Function: consultar_bases_de_datos
				Determina las bases de datos existentes dependiendo del motor utilizado.

				Salida:
					Resultado de un query con las bases de datos o falso en caso de error
				
				Ver tambien:
				<Definicion de conexion PDO> | <consultar_tablas>
			*/
			global $ConexionPDO;
			global $MotorBD;
			global $BaseDatos;

			if($MotorBD=="sqlsrv" || $MotorBD=="mssql" || $MotorBD=="ibm" || $MotorBD=="dblib" || $MotorBD=="odbc" || $MotorBD=="sqlite2" || $MotorBD=="sqlite3")
				$consulta = "SELECT name FROM sys.Databases;";
			if($MotorBD=="oracle")
				$consulta = 'SELECT * FROM v$database;';  //Si falla intentar con este: $consulta = "SELECT * FROM user_tablespaces";
			if($MotorBD=="ifmx" || $dbtype=="fbd")
				$consulta = "";
			if($MotorBD=="mysql")
				$consulta = "SHOW DATABASES;";
			if($MotorBD=="pg")
				$consulta = "SELECT datname AS name FROM pg_database;";

			try
				{
					$consulta_basesdatos = $ConexionPDO->prepare($consulta);
					$consulta_basesdatos->execute();
					return $consulta_basesdatos;
				}
			catch( PDOException $ErrorPDO)
				{
					mensaje('Error durante la ejecuci&oacute;n',$ErrorPDO->getMessage(),'90%','icono_error.png','TextosEscritorio');
					return false;
				}
	}



/* ################################################################## */
/* ################################################################## */
	function ContarRegistros($tabla)
		{
			global $ConexionPDO;
			$consulta = $ConexionPDO->prepare("SELECT count(*) FROM $tabla");
			$consulta->execute();
			$filas = $consulta->fetchColumn();
			return $filas;
		}



/* ################################################################## */
/* ################################################################## */
	/*
		Section: Funciones asociadas a la creacion de elementos graficos (ventanas, etc)
	*/
/* ################################################################## */
/* ################################################################## */
	function ventana_login()
	  {
		/*
			Function: ventana_login
			Despliega la ventana de ingreso al sistema con el formulario para usuario, contrasena y captcha.
		*/
		  global $ArchivoCORE;
			echo '
					<br><br>
					<div align="center">
					';
			abrir_ventana('Ingreso al sistema','#EADEDE','620');
			?>
						<div align="center">
						<form name="login_usuario" method="POST" action="<?php echo $ArchivoCORE; ?>" style="margin-top: 0px; margin-bottom: 0px;" onsubmit="if (document.login_usuario.captcha.value=='' || document.login_usuario.uid.value=='' || document.login_usuario.clave.value=='') { alert('Debe diligenciar los valores necesarios (Usuario, Clave y Codigo de seguridad).'); return false; }">
						<input type="Hidden" name="accion" value="Iniciar_login">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center"><tr>
								<td align="center">
										<table width="100%" border="0" cellspacing="10" cellpadding="0" class="TextosVentana" align="center">
										<tr>
											<td align="right"><font face="Verdana,Tahoma, Arial" style="font-size: 9px;">Usuario&nbsp;</td>
											<td><input type="text" name="uid" size="18" class="CampoTexto" class="keyboardInput"></td>
										</tr>
										<tr>
											<td align="right"><font face="Verdana,Tahoma, Arial" style="font-size: 9px;">Contrase&ntilde;a&nbsp;</td>
											<td><input type="password" name="clave" size="18" class="CampoTexto keyboardInput" class="keyboardInput" style="border-width: 1px; font-size: 9px; font-family: VErdana, Tahoma, Arial;"></td>
										</tr>
										<tr>
											<td align="right" valign="middle"><font face="Verdana,Tahoma, Arial" style="font-size: 9px;">Codigo de seguridad</td>
											<td valign="middle">
											<img src="core/captcha.php">
											</td>
										</tr>
										<tr>
											<td width="150" align="right" valign="middle"><font face="Verdana,Tahoma, Arial" style="font-size: 9px;">Ingrese aqui el codigo de seguridad</td>
											<td valign="middle">
											<img src="img/tango_go-next.png" align="absmiddle"> <input type="text" name="captcha" size="7" maxlength=6 style="border-width: 1px; font-size: 9px; font-family: VErdana, Tahoma, Arial;">
											</td>
										</tr>
										<tr>
											<td></td>
											<td>
											<input type="image" src="img/ingresa.gif">
											</td>
										</tr>
										</table>
								</td>
								<td align="center">
										<img src="img/practico_login.png" alt="" border="0">
								</td>
						</tr></table>
						</form>
						<script language="JavaScript"> login_usuario.uid.focus(); </script>
						</div>
						
			<?php
			mensaje('Importante','El acceso a este software es exlusivo para usuarios registrados. Por su seguridad, nunca comparta su nombre de usuario y contrase&ntilde;a.','100%','../img/tango_dialog-information.png','TextosVentana');
			cerrar_ventana();
			echo '</div>';
	  }



/* ################################################################## */
/* ################################################################## */
	function abrir_ventana($titulo,$fondo,$ancho='100%')
	  {
		global $PlantillaActiva;
		/*
			Procedure: abrir_ventana
			Abre los espacios de trabajo dinamicos sobre el contenedor principal donde se despliega informacion

			Variables de entrada:

				titulo - Nombre de la ventana a visualizar en la parte superior.  Acepta modificadores HTML.
				fondo - Color de fondo de la ventana en formato Hexadecimal. Si no es enviado se crea transparente.  Si llega un nombre de imagen es usado.
				ancho - Ancho del espacio de trabajo definido en pixels o porcentaje sobre el contenedor principal.
				
			Ver tambien:
			<cerrar_ventana>	
		*/

		// Determina si fue enviado un nombre de archivo como fondo y lo usa
		$ruta_fondo_imagen='';
		$color_fondo='';
		if (strpos($fondo, ".png") || strpos($fondo, ".jpg") || strpos($fondo, ".gif"))
			$ruta_fondo_imagen='skin/'.$PlantillaActiva.'/img/'.$fondo;
		else
			$color_fondo=$fondo;

		echo '
			<table width="'.$ancho.'" border="0" cellspacing="0" cellpadding="0" class="EstiloVentana">
				<tr>
					<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
									<td><img src="skin/'.$PlantillaActiva.'/img/bar_i.gif" border=0 alt=" "></td>
									<td width="100%" align="CENTER" background="skin/'.$PlantillaActiva.'/img/bar_c.jpg">
										<font face="" style="font-family: Verdana, Tahoma, Arial; font-size: 10px; color: Black;"><b>
												'.$titulo.'
										</b></font>
									</td>
									<td><img src="skin/'.$PlantillaActiva.'/img/bar_d.gif " border=0 alt=""></td>
							</tr></table>
					</td>
				</tr>
				<tr>
					<td width="100%" align="CENTER">
							<table width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bgcolor="'.$color_fondo.'" BACKGROUND="'.$ruta_fondo_imagen.'" class="TextosVentana"><tr><td>
				';
	  }



/* ################################################################## */
/* ################################################################## */
	function cerrar_ventana()
	  {
		/*
			Function: cerrar_ventana
			Cierra los espacios de trabajo dinamicos generados por <abrir_ventana>	

			Ver tambien:
			<abrir_ventana>	
		*/
			echo '
							</td></tr></table>
					</td>
				</tr>
			</table>
				';		  
	  }



/* ################################################################## */
/* ################################################################## */
	function abrir_barra_estado($alineacion="CENTER")
	  {
		 global $PlantillaActiva;
		/*
			Procedure: abrir_barra_estado
			Abre los espacios para despliegue de informacion en la parte inferior de los objetos tales como botones o mensajes

			Variables de entrada:

				alineacion - Alineacion que tendran los objetos en la barra (center, left, right).  Por defecto CENTER cuando no es recibido el parametro
				
			Ver tambien:
			<cerrar_barra_estado>	
		*/

		echo '
			<table width="100%" border="0" cellspacing="0" cellpadding="1" class="EstiloBarraEstado">
				<tr>
					<td width="100%" align="'.$alineacion.'">
				';
	  }



/* ################################################################## */
/* ################################################################## */
	function obtener_microtime()
		{
		/*
			Function: obtener_microtime
			Obtiene un tiempo en microsegundos utilizado para calcular tiempos de inicio y fin de operaciones
		*/
			list($useg, $seg) = explode(" ", microtime());
			return ((float)$useg + (float)$seg);
		}



/* ################################################################## */
/* ################################################################## */
	function cerrar_barra_estado()
	  {
		/*
			Function: cerrar_barra_estado
			Cierra los espacios de trabajo dinamicos generados por <abrir_barra_estado>

			Ver tambien:
			<abrir_barra_estado>	
		*/
			echo '
					</td>
				</tr>
			</table>
				';		  
	  }



/* ################################################################## */
/* ################################################################## */
	function mensaje($titulo,$texto,$ancho,$icono,$estilo)
	  {
		/*
			Function: mensaje
			Funcion generica para la presentacion de mensajes.  Ver variables para personalizacion.

			Variables de entrada:

				titulo - Texto que aparece en resaltado como encabezado del texto.  Acepta modificadores HTML.
				texto - Mensaje completo a desplegar en formato de texto normal.  Acepta modificadores HTML.
				icono - Imagen que acompana el texto ubicada al lado izquierdo.  Tamano y formato libre.
				ancho - Ancho del espacio de trabajo definido en pixels o porcentaje sobre el contenedor principal.
				estilo - Especifica el punto donde sera publicado el mensaje para definir la hoja de estilos correspondiente.
		*/
		echo '<table width="'.$ancho.'" border="0" cellspacing="5" cellpadding="0" align="center" class="'.$estilo.'">
				<tr>
					<td valign="top"><img src="img/'.$icono.'" alt="" border="0">
					</td>
					<td valign="top"><strong>'.$titulo.':<br></strong>
					'.$texto.'
					</td>
				</tr>
			</table>';
	  }


	function cargar_objeto_texto_corto($registro_campos)
		{
			$salida='';
			$nombre_campo=$registro_campos["campo"];
			$tipo_entrada="text"; // Se cambia a date si se trata de un campo con validacion de fecha

			// Define cadenas de longitud de campo
			$cadena_longitud_visual=' size="20" ';
			$cadena_longitud_permitida='';

			// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
			$cadena_valor='';
			if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
			if ($campobase!="" && $valorbase!="") $cadena_valor=' value="'.$registro_datos_formulario["$nombre_campo"].'" ';

			// Define cadenas en caso de tener validaciones
			$cadena_validacion='';
			$cadena_fechas='';
			if ($registro_campos["validacion_datos"]!="" && $registro_campos["validacion_datos"]!="fecha")
				$cadena_validacion=' onkeypress="return validar_teclado(event, \''.$registro_campos["validacion_datos"].'\');" ';
			if ($registro_campos["validacion_datos"]=="fecha")
				{
					$cadena_longitud_visual=' size="11" ';
					$tipo_entrada="date";
				}

			// Define si muestra o no teclado virtual
			$cadena_clase_teclado="";
			if ($registro_campos["teclado_virtual"])
				$cadena_clase_teclado="keyboardInput";

			// Muestra el campo
			$salida.='<input type="'.$tipo_entrada.'" name="'.$registro_campos["campo"].'" '.$cadena_valor.' '.$cadena_longitud_visual.' '.$cadena_longitud_permitida.' class="CampoTexto '.$cadena_clase_teclado.'" '.$cadena_validacion.' '.$registro_campos["solo_lectura"].'  >';

			// Muestra boton de busqueda cuando el campo sea usado para esto
			if ($registro_campos["etiqueta_busqueda"]!="")
				{
					$salida.= '<input type="Button" class="BotonesEstado" value="'.$registro_campos["etiqueta_busqueda"].'" onclick="document.datos.valorbase.value=document.datos.'.$registro_campos["campo"].'.value;document.datos.accion.value=\'cargar_objeto\';document.datos.submit()">';
					$salida.= '<input type="hidden" name="objeto" value="frm:'.$formulario.'">';
					$salida.= '<input type="Hidden" name="en_ventana" value="'.$en_ventana.'" >';
					$salida.= '<input type="Hidden" name="campobase" value="'.$registro_campos["campo"].'" >';
					$salida.= '<input type="Hidden" name="valorbase" '.$cadena_valor.'>';
				}

			// Muestra indicadores de obligatoriedad o ayuda
			if ($registro_campos["valor_unico"] == "1") $salida.= '<a href="#" title="El valor ingresado no acepta duplicados" name="El sistema validar&aacute; la informaci&oacute;n ingresada en este campo, en caso de ya existir en la base de datos no se permitir&aacute; su ingreso."><img src="img/key.gif" border=0 border=0 align="absmiddle"></a>';
			if ($registro_campos["obligatorio"]) $salida.= '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
			if ($registro_campos["ayuda_titulo"] != "") $salida.= '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
			return $salida;
		}


	function cargar_objeto_texto_largo($registro_campos)
		{
			$salida='';
			$nombre_campo=$registro_campos["campo"];

			// Define cadenas de tamano de campo
			$cadena_ancho_visual=' cols="'.$registro_campos["ancho"].'" ';
			$cadena_alto_visual=' rows="'.$registro_campos["alto"].'" ';
			$cadena_longitud_visual=$cadena_ancho_visual.$cadena_alto_visual;

			// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
			$cadena_valor='';
			if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
			if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

			// Muestra el campo
			$salida.= '<textarea name="'.$registro_campos["campo"].'" '.$cadena_longitud_visual.' class="AreaTexto" '.$registro_campos["solo_lectura"].'  >'.$cadena_valor.'</textarea>';

			// Muestra indicadores de obligatoriedad o ayuda
			if ($registro_campos["obligatorio"]) $salida.= '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
			if ($registro_campos["ayuda_titulo"] != "") $salida.= '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
			return $salida;
		}


	function cargar_objeto_texto_formato($registro_campos,$existe_campo_textoformato)
		{
			$salida='';
			$nombre_campo=$registro_campos["campo"];

			// Define cadenas de tamano de campo
			$cadena_ancho_visual=' cols="'.$registro_campos["ancho"].'" ';
			$cadena_alto_visual=' rows="'.$registro_campos["alto"].'" ';
			$cadena_longitud_visual=$cadena_ancho_visual.$cadena_alto_visual;

			// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
			$cadena_valor='';
			if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
			if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

			// Muestra el campo
			$salida.= '<textarea name="'.$registro_campos["campo"].'" '.$cadena_longitud_visual.' class="ckeditor" '.$registro_campos["solo_lectura"].'  >'.$cadena_valor.'</textarea>';
			
			// Define las barras posibles para el editor
			$barra_documento="['Source','-','NewPage','DocProps','Preview','Print','-','Templates']";
			$barra_basica="['Bold', 'Italic', 'Underline', 'Strike', 'Subscript','Superscript','-','RemoveFormat']";
			$barra_parrafo="['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl']";
			$barra_enlaces="['Link','Unlink','Anchor']";
			$barra_estilos="['Styles','Format','Font','FontSize']";
			$barra_portapapeles="['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo']";
			$barra_edicion="['Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt']";
			$barra_insertar="['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe']";
			$barra_colores="['TextColor','BGColor']";
			$barra_formularios="['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']";
			$barra_otros="['Maximize', 'ShowBlocks']";

			// Construye las barras de herramientas de acuerdo a la seleccion del usuario
			$barra_editor.="['-']";
			if ($registro_campos["barra_herramientas"]=="0")
				{
					$barra_editor.=",".$barra_documento;
					$barra_editor.=",".$barra_basica;
					$barra_editor.=",".$barra_parrafo;
				}
			if ($registro_campos["barra_herramientas"]=="1")
				{
					$barra_editor.=",".$barra_documento;
					$barra_editor.=",".$barra_basica;
					$barra_editor.=",".$barra_parrafo;
					$barra_editor.=",".$barra_enlaces;
					$barra_editor.=",".$barra_estilos;
				}
			if ($registro_campos["barra_herramientas"]=="2")
				{
					$barra_editor.=",".$barra_documento;
					$barra_editor.=",".$barra_basica;
					$barra_editor.=",".$barra_parrafo;
					$barra_editor.=",".$barra_enlaces;
					$barra_editor.=",".$barra_estilos;
					$barra_editor.=",".$barra_portapapeles;
					$barra_editor.=",".$barra_edicion;
				}
			if ($registro_campos["barra_herramientas"]=="3")
				{
					$barra_editor.=",".$barra_documento;
					$barra_editor.=",".$barra_basica;
					$barra_editor.=",".$barra_parrafo;
					$barra_editor.=",".$barra_enlaces;
					$barra_editor.=",".$barra_estilos;
					$barra_editor.=",".$barra_portapapeles;
					$barra_editor.=",".$barra_edicion;
					$barra_editor.=",".$barra_insertar;
					$barra_editor.=",".$barra_colores;
				}
			if ($registro_campos["barra_herramientas"]=="4")
				{
					$barra_editor.=",".$barra_documento;
					$barra_editor.=",".$barra_basica;
					$barra_editor.=",".$barra_parrafo;
					$barra_editor.=",".$barra_enlaces;
					$barra_editor.=",".$barra_estilos;
					$barra_editor.=",".$barra_portapapeles;
					$barra_editor.=",".$barra_edicion;
					$barra_editor.=",".$barra_insertar;
					$barra_editor.=",".$barra_colores;
					$barra_editor.=",".$barra_formularios;
					$barra_editor.=",".$barra_otros;
				}
			// Aplica el script del ckeditor al campo
			if (!$existe_campo_textoformato)
				$salida.= '<script type="text/javascript" src="inc/ckeditor/ckeditor.js"></script>';
			$salida.= '	<script type="text/javascript">
						CKEDITOR.replace( \''.$registro_campos["campo"].'\', {	toolbar : [ '.$barra_editor.' ] } );
						CKEDITOR.config.width = '.$registro_campos["ancho"].';
						CKEDITOR.config.height = '.$registro_campos["alto"].';
					</script>';

			// Muestra indicadores de obligatoriedad o ayuda
			if ($registro_campos["obligatorio"]) $salida.= '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
			if ($registro_campos["ayuda_titulo"] != "") $salida.= '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
			
			//Activa booleana de existencia de tipo de campo para evitar doble inclusion de javascript
			$existe_campo_textoformato=1;
			return $salida;
		}


	function cargar_objeto_lista_seleccion($registro_campos)
		{
			$salida='';
			$nombre_campo=$registro_campos["campo"];

			// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
			if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

			// Muestra el campo
			$salida.= '<select name="'.$registro_campos["campo"].'" class="Combos" >';

			// Toma los valores desde la lista de opciones (cuando es estatico)
			$opciones_lista = explode(",", $registro_campos["lista_opciones"]);
			$valores_lista = explode(",", $registro_campos["lista_opciones"]);
			
			// Si se desea tomar los valores del combo desde una tabla hace la consulta
			if ($registro_campos["origen_lista_opciones"]!="" && $registro_campos["origen_lista_valores"]!="")
				{
					$nombre_tabla_opciones = explode(".", $registro_campos["origen_lista_opciones"]);
					$nombre_tabla_opciones = $nombre_tabla_opciones[0];
					$campo_valores=$registro_campos["origen_lista_valores"];
					$campo_opciones=$registro_campos["origen_lista_opciones"];

					// Consulta los campos para el tag select
					$resultado_opciones=ejecutar_sql("SELECT $campo_valores as valores, $campo_opciones as opciones FROM $nombre_tabla_opciones WHERE 1 ORDER BY $campo_opciones");
					while ($registro_opciones = $resultado_opciones->fetch())
						{
							$opciones_lista[] = $registro_opciones["opciones"];
							$valores_lista[] = $registro_opciones["valores"];
						}
				}

			for ($i=0;$i<count($opciones_lista);$i++)
				{
					// Determina si la opcion a agregar es la misma del valor del registro
					$cadena_predeterminado='';
					if ($opciones_lista[$i]==$cadena_valor)
						$cadena_predeterminado=' SELECTED ';
					$salida.= "<option value='".$valores_lista[$i]."' ".$cadena_predeterminado.">".$opciones_lista[$i]."</option>";
				}

			$salida.= '</select>';

			// Muestra indicadores de obligatoriedad o ayuda
			if ($registro_campos["valor_unico"] == "1") $salida.= '<a href="#" title="El valor ingresado no acepta duplicados" name="El sistema validar&aacute; la informaci&oacute;n ingresada en este campo, en caso de ya existir en la base de datos no se permitir&aacute; su ingreso."><img src="img/key.gif" border=0 border=0 align="absmiddle"></a>';
			if ($registro_campos["obligatorio"]) $salida.= '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
			if ($registro_campos["ayuda_titulo"] != "") $salida.= '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
			return $salida;
		}


/* ################################################################## */
/* ################################################################## */
		function cargar_formulario($formulario,$en_ventana=1,$campobase="",$valorbase="")
		  {
				global $ConexionPDO,$ArchivoCORE,$TablasCore;

				echo '
				<script type="text/javascript">
					function AgregarElemento(columna,fila,elemento)
						{
							//carga dinamicamente objetos html a marcos
							var capa = document.getElementById(ubicacion);
							var zona = document.createElement("po");
							zona.innerHTML = elemento;
							capa.appendChild(zona);
						}
				</script>
				<!--<input type=button onclick=\'AgregarElemento("1","1","hola");\'>-->';

				// Busca datos del formulario
				$consulta_formulario=ejecutar_sql("SELECT * FROM ".$TablasCore."formulario WHERE id='$formulario'");
				$registro_formulario = $consulta_formulario->fetch();

				//Si no encuentra formulario presenta error
				if ($registro_formulario["id"]=="")	mensaje("ERROR EN TIEMPO DE EJECUCION","El objeto (formulario $formulario) asociado a esta solicitud no existe.  Consulte con su administrador del sistema.<br>","70%","icono_error.png","TextosEscritorio");

				// En caso de recibir un campo base y valor base se hace la busqueda para recuperar la informacion
				if ($campobase!="" && $valorbase!="")
					{
						$consulta_datos_formulario = $ConexionPDO->prepare("SELECT * FROM ".$registro_formulario["tabla_datos"]." WHERE $campobase='$valorbase'");
						$consulta_datos_formulario->execute();
						$registro_datos_formulario = $consulta_datos_formulario->fetch();
					}
				if ($en_ventana) abrir_ventana($registro_formulario["titulo"],'f2f2f2','');
				// Muestra ayuda en caso de tenerla
				$imagen_ayuda="info_icon.png";
				if ($registro_formulario["ayuda_imagen"]!="") $imagen_ayuda=$registro_formulario["ayuda_imagen"];
				if ($registro_formulario["ayuda_titulo"]!="" || $registro_formulario["ayuda_texto"]!="" || $registro_formulario["ayuda_imagen"]!="")
					mensaje($registro_formulario["ayuda_titulo"],$registro_formulario["ayuda_texto"],'100%',$imagen_ayuda,'TextosVentana');

				//Inicia el formulario de datos
				echo '<form name="datos" action="'.$ArchivoCORE.'" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="Hidden" name="accion" value="guardar_datos_formulario">
					<input type="Hidden" name="formulario" value="'.$formulario.'">';

				//Booleana que determina si se debe incluir el javascript de ckeditor
				$existe_campo_textoformato=0;


				$limite_inferior=-9999; // Peso inferior a tener en cuenta en el query
				$limite_superior=+9999; // Peso superior a tener en cuenta en el query
				//Busca todos los objetos marcados como fila_unica=1
				$consulta_obj_fila_unica=ejecutar_sql("SELECT * FROM ".$TablasCore."formulario_objeto WHERE formulario='$formulario' AND fila_unica='1' ORDER BY peso");
				$paso_por_no_fila_unica=0;
				while ($registro_obj_fila_unica = $consulta_obj_fila_unica->fetch())
					{
						$peso_primera_fila_unica=$registro_obj_fila_unica["peso"];
						//Crea DIVs como capas para cada columna del formulario
						for ($cl=1;$cl<=$registro_formulario["columnas"];$cl++)
							{
								//echo '<div id="columna'.$cl.'" style="float: left;">Marco'.$cl.'</div>';
							}
					} // Fin mientras seleccion fila_unica




						// Inicia la tabla con los campos
						echo '<table border=5 class="TextosVentana" width="100%"><tr>';
						for ($cl=1;$cl<=$registro_formulario["columnas"];$cl++)
							{
								//Busca los elementos de formulario con peso menor o igual al peso de la fila unica_actual
								$consulta_campos=ejecutar_sql("SELECT * FROM ".$TablasCore."formulario_objeto WHERE formulario='$formulario' AND columna='$cl' AND visible=1 ORDER BY peso");
								$expansion_columnas="";
								$numero_columnas_expandir=$registro_formulario["columnas"]*2;
								if ($registro_campos["fila_unica"]=="1") 
									{
										$expansion_columnas=" colspan='".$numero_columnas_expandir."' ";
									}

								//Inicia columna de formulario
								echo '<td '.$expansion_columnas.' valign=top align=center>';
									// Crea los campos definidos por cada columna de formulario
									echo '<table border=1 class="TextosVentana">';
									while ($registro_campos = $consulta_campos->fetch())
										{
											//Crea la fila y celda donde va el campo
											// Si el objeto tiene expansion en toda la fila entonces no crea columna con etiqueta y la pone sobre el directamente
											if ($registro_campos["fila_unica"]=="1")
												echo '<tr>
													<td valign=top>'.$registro_campos["titulo"].'<br>';
											else
												echo '<tr>
													<td align="right" valign=top>'.$registro_campos["titulo"].'</td>
													<td valign=top>';

														// Formatea cada campo de acuerdo a su tipo
														if ($registro_campos["tipo"]=="texto_corto") $objeto_formateado = cargar_objeto_texto_corto($registro_campos);
														if ($registro_campos["tipo"]=="texto_largo") $objeto_formateado = cargar_objeto_texto_largo($registro_campos);
														if ($registro_campos["tipo"]=="texto_formato") { $objeto_formateado = cargar_objeto_texto_formato($registro_campos,$existe_campo_textoformato); $existe_campo_textoformato=1; }
														if ($registro_campos["tipo"]=="lista_seleccion") $objeto_formateado = cargar_objeto_lista_seleccion($registro_campos);

														//Imprime el objeto
														echo $objeto_formateado;

											// Cierra la fila y celda donde se puso el objeto
											echo '</td></tr>';
										}
									// Cierra tabla de campos en la columna
									echo '</table>';
								echo '</td>'; //Fin columna de formulario
							}
						// Finaliza la tabla con los campos
						echo '</tr></table>';



			// Si tiene botones agrega barra de estado y los ubica
			$consulta_botones = $ConexionPDO->prepare("SELECT * FROM ".$TablasCore."formulario_boton WHERE formulario='$formulario' AND visible=1 ORDER BY peso");
			$consulta_botones->execute();

			if($consulta_botones->rowCount()>0)
				{
					abrir_barra_estado();
					while ($registro_botones = $consulta_botones->fetch())
						{
							//Define el tipo de boton de acuerdo al tipo de accion como Submit, Reset o Button
							$tipo_boton="Button";
							if ($registro_botones["tipo_accion"]=="interna_guardar")
								{
									$tipo_boton="Submit";
								}
							if ($registro_botones["tipo_accion"]=="interna_limpiar")
								{
									$tipo_boton="Reset";
								}
							if ($registro_botones["tipo_accion"]=="interna_escritorio")
								{
									$tipo_boton="Button";
									$comando_javascript="document.core_ver_menu.submit()";
								}
							if ($registro_botones["tipo_accion"]=="interna_eliminar")
								{
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='eliminar_datos_formulario';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="interna_cargar")
								{
									echo '<input type="hidden" name="objeto" value="'.$registro_botones["accion_usuario"].'">';
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='cargar_objeto';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="externa_formulario")
								{
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='".$registro_botones["accion_usuario"]."';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="externa_javascript")
								{
									$tipo_boton="Button";
									$comando_javascript=$registro_botones["accion_usuario"];
								}
							if ($comando_javascript!="" && $tipo_boton!="Reset")
								{
									$cadena_javascript='onclick="'.@$comando_javascript.'"';
								}
							echo '<input type="'.$tipo_boton.'"  class="'.$registro_botones["estilo"].'" value="'.$registro_botones["titulo"].'" '.$cadena_javascript.' >';
						}
					cerrar_barra_estado();
				}

			//Cierra todo el formulario
			echo '</form>';
			if ($en_ventana) cerrar_ventana();
		  }





/* ################################################################## */
/* ################################################################## */
		function cargar_formulario_old($formulario,$en_ventana=1,$campobase="",$valorbase="")
		  {
				global $ConexionPDO,$ArchivoCORE,$TablasCore;

				// Busca datos del formulario
				$consulta_formulario=ejecutar_sql("SELECT * FROM ".$TablasCore."formulario WHERE id='$formulario'");
				$registro_formulario = $consulta_formulario->fetch();

				//Si no encuentra formulario presenta error
				if ($registro_formulario["id"]=="")	mensaje("ERROR EN TIEMPO DE EJECUCION","El objeto (formulario $formulario) asociado a esta solicitud no existe.  Consulte con su administrador del sistema.<br>","70%","icono_error.png","TextosEscritorio");

				// En caso de recibir un campo base y valor base se hace la busqueda para recuperar la informacion
				if ($campobase!="" && $valorbase!="")
					{
						$consulta_datos_formulario = $ConexionPDO->prepare("SELECT * FROM ".$registro_formulario["tabla_datos"]." WHERE $campobase='$valorbase'");
						$consulta_datos_formulario->execute();
						$registro_datos_formulario = $consulta_datos_formulario->fetch();
					}
				if ($en_ventana) abrir_ventana($registro_formulario["titulo"],'f2f2f2','');
				// Muestra ayuda en caso de tenerla
				$imagen_ayuda="info_icon.png";
				if ($registro_formulario["ayuda_imagen"]!="") $imagen_ayuda=$registro_formulario["ayuda_imagen"];
				if ($registro_formulario["ayuda_titulo"]!="" || $registro_formulario["ayuda_texto"]!="" || $registro_formulario["ayuda_imagen"]!="")
					mensaje($registro_formulario["ayuda_titulo"],$registro_formulario["ayuda_texto"],'100%',$imagen_ayuda,'TextosVentana');

				//Inicia el formulario de datos
				echo '<form name="datos" action="'.$ArchivoCORE.'" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="Hidden" name="accion" value="guardar_datos_formulario">
					<input type="Hidden" name="formulario" value="'.$formulario.'">';

				//Booleana que determina si se debe incluir el javascript de ckeditor
				$existe_campo_textoformato=0;

				// Inicia la tabla con los campos
				echo '<table border=0 class="TextosVentana" width="100%"><tr>';
				for ($cl=1;$cl<=$registro_formulario["columnas"];$cl++)
					{
						$consulta_campos=ejecutar_sql("SELECT * FROM ".$TablasCore."formulario_objeto WHERE formulario='$formulario' AND columna='$cl' AND visible=1 ORDER BY peso");
						$expansion_columnas="";
						$numero_columnas_expandir=$registro_formulario["columnas"]*2;
						if ($registro_campos["fila_unica"]) 
							{
								$expansion_columnas=" colspan='".$numero_columnas_expandir."' ";
							}

						//Inicia columna de formulario
						echo '<td '.$expansion_columnas.' valign=top align=center>';
							// Crea los campos definidos por cada columna de formulario
							echo '<table border=0 class="TextosVentana">';
							while ($registro_campos = $consulta_campos->fetch())
								{
									//Crea la fila y celda donde va el campo
									// Si el objeto tiene expansion en toda la fila entonces no crea columna con etiqueta y la pone sobre el directamente
									if ($registro_campos["fila_unica"]=="1")
										echo '<tr>
											<td valign=top>'.$registro_campos["titulo"].'<br>';
									else
										echo '<tr>
											<td align="right" valign=top>'.$registro_campos["titulo"].'</td>
											<td valign=top>';

									// Despliega cada campo de acuerdo a su tipo
									/* CAMPOS TIPO texto_corto %%%%%%%%%%%%%%%%%%%%%%*/
									if ($registro_campos["tipo"]=="texto_corto")
										{
											$nombre_campo=$registro_campos["campo"];
											$tipo_entrada="text"; // Se cambia a date si se trata de un campo con validacion de fecha

											// Define cadenas de longitud de campo
											$cadena_longitud_visual=' size="20" ';
											$cadena_longitud_permitida='';

											// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
											$cadena_valor='';
											if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
											if ($campobase!="" && $valorbase!="") $cadena_valor=' value="'.$registro_datos_formulario["$nombre_campo"].'" ';

											// Define cadenas en caso de tener validaciones
											$cadena_validacion='';
											$cadena_fechas='';
											if ($registro_campos["validacion_datos"]!="" && $registro_campos["validacion_datos"]!="fecha")
												$cadena_validacion=' onkeypress="return validar_teclado(event, \''.$registro_campos["validacion_datos"].'\');" ';
											if ($registro_campos["validacion_datos"]=="fecha")
												{
													$cadena_longitud_visual=' size="11" ';
													$tipo_entrada="date";
												}						

											// Define si muestra o no teclado virtual
											$cadena_clase_teclado="";
											if ($registro_campos["teclado_virtual"])
												$cadena_clase_teclado="keyboardInput";

											// Muestra el campo
											echo '<input type="'.$tipo_entrada.'" name="'.$registro_campos["campo"].'" '.$cadena_valor.' '.$cadena_longitud_visual.' '.$cadena_longitud_permitida.' class="CampoTexto '.$cadena_clase_teclado.'" '.$cadena_validacion.' '.$registro_campos["solo_lectura"].'  >';

											// Muestra boton de busqueda cuando el campo sea usado para esto
											if ($registro_campos["etiqueta_busqueda"]!="")
												{
													echo '<input type="Button" class="BotonesEstado" value="'.$registro_campos["etiqueta_busqueda"].'" onclick="document.datos.valorbase.value=document.datos.'.$registro_campos["campo"].'.value;document.datos.accion.value=\'cargar_objeto\';document.datos.submit()">';
													echo '<input type="hidden" name="objeto" value="frm:'.$formulario.'">';
													echo '<input type="Hidden" name="en_ventana" value="'.$en_ventana.'" >';
													echo '<input type="Hidden" name="campobase" value="'.$registro_campos["campo"].'" >';
													echo '<input type="Hidden" name="valorbase" '.$cadena_valor.'>';
												}

											// Muestra indicadores de obligatoriedad o ayuda
											if ($registro_campos["valor_unico"] == "1") echo '<a href="#" title="El valor ingresado no acepta duplicados" name="El sistema validar&aacute; la informaci&oacute;n ingresada en este campo, en caso de ya existir en la base de datos no se permitir&aacute; su ingreso."><img src="img/key.gif" border=0 border=0 align="absmiddle"></a>';
											if ($registro_campos["obligatorio"]) echo '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
											if ($registro_campos["ayuda_titulo"] != "") echo '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
										}
									/* FIN CAMPOS TIPO texto_corto %%%%%%%%%%%%%%%%%%%%%%*/

									/* CAMPOS TIPO texto_largo %%%%%%%%%%%%%%%%%%%%%%*/
									if ($registro_campos["tipo"]=="texto_largo")
										{
											$nombre_campo=$registro_campos["campo"];

											// Define cadenas de tamano de campo
											$cadena_ancho_visual=' cols="'.$registro_campos["ancho"].'" ';
											$cadena_alto_visual=' rows="'.$registro_campos["alto"].'" ';
											$cadena_longitud_visual=$cadena_ancho_visual.$cadena_alto_visual;

											// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
											$cadena_valor='';
											if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
											if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

											// Muestra el campo
											echo '<textarea name="'.$registro_campos["campo"].'" '.$cadena_longitud_visual.' class="AreaTexto" '.$registro_campos["solo_lectura"].'  >'.$cadena_valor.'</textarea>';

											// Muestra indicadores de obligatoriedad o ayuda
											if ($registro_campos["obligatorio"]) echo '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
											if ($registro_campos["ayuda_titulo"] != "") echo '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
										}
									/* FIN CAMPOS TIPO texto_largo %%%%%%%%%%%%%%%%%%%%%%*/

									/* CAMPOS TIPO texto_formato %%%%%%%%%%%%%%%%%%%%%%*/
									if ($registro_campos["tipo"]=="texto_formato")
										{
											$nombre_campo=$registro_campos["campo"];

											// Define cadenas de tamano de campo
											$cadena_ancho_visual=' cols="'.$registro_campos["ancho"].'" ';
											$cadena_alto_visual=' rows="'.$registro_campos["alto"].'" ';
											$cadena_longitud_visual=$cadena_ancho_visual.$cadena_alto_visual;

											// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
											$cadena_valor='';
											if ($registro_campos["valor_predeterminado"]!="") $cadena_valor=' value="'.$registro_campos["valor_predeterminado"].'" ';
											if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

											// Muestra el campo
											echo '<textarea name="'.$registro_campos["campo"].'" '.$cadena_longitud_visual.' class="ckeditor" '.$registro_campos["solo_lectura"].'  >'.$cadena_valor.'</textarea>';
											
											// Define las barras posibles para el editor
											$barra_documento="['Source','-','NewPage','DocProps','Preview','Print','-','Templates']";
											$barra_basica="['Bold', 'Italic', 'Underline', 'Strike', 'Subscript','Superscript','-','RemoveFormat']";
											$barra_parrafo="['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl']";
											$barra_enlaces="['Link','Unlink','Anchor']";
											$barra_estilos="['Styles','Format','Font','FontSize']";
											$barra_portapapeles="['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo']";
											$barra_edicion="['Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt']";
											$barra_insertar="['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe']";
											$barra_colores="['TextColor','BGColor']";
											$barra_formularios="['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']";
											$barra_otros="['Maximize', 'ShowBlocks']";

											// Construye las barras de herramientas de acuerdo a la seleccion del usuario
											$barra_editor.="['-']";
											if ($registro_campos["barra_herramientas"]=="0")
												{
													$barra_editor.=",".$barra_documento;
													$barra_editor.=",".$barra_basica;
													$barra_editor.=",".$barra_parrafo;
												}
											if ($registro_campos["barra_herramientas"]=="1")
												{
													$barra_editor.=",".$barra_documento;
													$barra_editor.=",".$barra_basica;
													$barra_editor.=",".$barra_parrafo;
													$barra_editor.=",".$barra_enlaces;
													$barra_editor.=",".$barra_estilos;
												}
											if ($registro_campos["barra_herramientas"]=="2")
												{
													$barra_editor.=",".$barra_documento;
													$barra_editor.=",".$barra_basica;
													$barra_editor.=",".$barra_parrafo;
													$barra_editor.=",".$barra_enlaces;
													$barra_editor.=",".$barra_estilos;
													$barra_editor.=",".$barra_portapapeles;
													$barra_editor.=",".$barra_edicion;
												}
											if ($registro_campos["barra_herramientas"]=="3")
												{
													$barra_editor.=",".$barra_documento;
													$barra_editor.=",".$barra_basica;
													$barra_editor.=",".$barra_parrafo;
													$barra_editor.=",".$barra_enlaces;
													$barra_editor.=",".$barra_estilos;
													$barra_editor.=",".$barra_portapapeles;
													$barra_editor.=",".$barra_edicion;
													$barra_editor.=",".$barra_insertar;
													$barra_editor.=",".$barra_colores;
												}
											if ($registro_campos["barra_herramientas"]=="4")
												{
													$barra_editor.=",".$barra_documento;
													$barra_editor.=",".$barra_basica;
													$barra_editor.=",".$barra_parrafo;
													$barra_editor.=",".$barra_enlaces;
													$barra_editor.=",".$barra_estilos;
													$barra_editor.=",".$barra_portapapeles;
													$barra_editor.=",".$barra_edicion;
													$barra_editor.=",".$barra_insertar;
													$barra_editor.=",".$barra_colores;
													$barra_editor.=",".$barra_formularios;
													$barra_editor.=",".$barra_otros;
												}
											// Aplica el script del ckeditor al campo
											if (!$existe_campo_textoformato)
												echo '<script type="text/javascript" src="inc/ckeditor/ckeditor.js"></script>';
											echo '	<script type="text/javascript">
														CKEDITOR.replace( \''.$registro_campos["campo"].'\', {	toolbar : [ '.$barra_editor.' ] } );
														CKEDITOR.config.width = '.$registro_campos["ancho"].';
														CKEDITOR.config.height = '.$registro_campos["alto"].';
													</script>';

											// Muestra indicadores de obligatoriedad o ayuda
											if ($registro_campos["obligatorio"]) echo '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
											if ($registro_campos["ayuda_titulo"] != "") echo '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
											
											//Activa booleana de existencia de tipo de campo para evitar doble inclusion de javascript
											$existe_campo_textoformato=1;
										}
									/* FIN CAMPOS TIPO texto_formato %%%%%%%%%%%%%%%%%%%%%%*/

									/* CAMPOS TIPO lista_seleccion %%%%%%%%%%%%%%%%%%%%%%*/
									if ($registro_campos["tipo"]=="lista_seleccion")
										{
											$nombre_campo=$registro_campos["campo"];

											// Define cadena en caso de tener valor predeterminado o el valor tomado desde el registro buscado
											if ($campobase!="" && $valorbase!="") $cadena_valor=$registro_datos_formulario["$nombre_campo"];

											// Muestra el campo
											echo '<select name="'.$registro_campos["campo"].'" class="Combos" >';

											// Toma los valores desde la lista de opciones (cuando es estatico)
											$opciones_lista = explode(",", $registro_campos["lista_opciones"]);
											$valores_lista = explode(",", $registro_campos["lista_opciones"]);
											
											// Si se desea tomar los valores del combo desde una tabla hace la consulta
											if ($registro_campos["origen_lista_opciones"]!="" && $registro_campos["origen_lista_valores"]!="")
												{
													$nombre_tabla_opciones = explode(".", $registro_campos["origen_lista_opciones"]);
													$nombre_tabla_opciones = $nombre_tabla_opciones[0];
													$campo_valores=$registro_campos["origen_lista_valores"];
													$campo_opciones=$registro_campos["origen_lista_opciones"];

													// Consulta los campos para el tag select
													$resultado_opciones=ejecutar_sql("SELECT $campo_valores as valores, $campo_opciones as opciones FROM $nombre_tabla_opciones WHERE 1 ORDER BY $campo_opciones");
													while ($registro_opciones = $resultado_opciones->fetch())
														{
															$opciones_lista[] = $registro_opciones["opciones"];
															$valores_lista[] = $registro_opciones["valores"];
														}
												}

											for ($i=0;$i<count($opciones_lista);$i++)
												{
													// Determina si la opcion a agregar es la misma del valor del registro
													$cadena_predeterminado='';
													if ($opciones_lista[$i]==$cadena_valor)
														$cadena_predeterminado=' SELECTED ';
													echo "<option value='".$valores_lista[$i]."' ".$cadena_predeterminado.">".$opciones_lista[$i]."</option>";
												}

											echo '</select>';

											// Muestra indicadores de obligatoriedad o ayuda
											if ($registro_campos["valor_unico"] == "1") echo '<a href="#" title="El valor ingresado no acepta duplicados" name="El sistema validar&aacute; la informaci&oacute;n ingresada en este campo, en caso de ya existir en la base de datos no se permitir&aacute; su ingreso."><img src="img/key.gif" border=0 border=0 align="absmiddle"></a>';
											if ($registro_campos["obligatorio"]) echo '<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0 align="absmiddle"></a>';
											if ($registro_campos["ayuda_titulo"] != "") echo '<a href="#" title="'.$registro_campos["ayuda_titulo"].'" name="'.$registro_campos["ayuda_texto"].'"><img src="img/icn_10.gif" border=0 border=0 align="absmiddle"></a>';
										}
									/* FIN CAMPOS TIPO lista_seleccion %%%%%%%%%%%%%%%%%%%%%%*/


									// Cierra la fila y celda donde se puso el objeto
									echo '</td></tr>';
								}
							// Cierra tabla de campos en la columna
							echo '</table>';
						echo '</td>'; //Fin columna de formulario
					}
				// Finaliza la tabla con los campos
				echo '</tr></table>';

			// Si tiene botones agrega barra de estado y los ubica
			$consulta_botones = $ConexionPDO->prepare("SELECT * FROM ".$TablasCore."formulario_boton WHERE formulario='$formulario' AND visible=1 ORDER BY peso");
			$consulta_botones->execute();

			if($consulta_botones->rowCount()>0)
				{
					abrir_barra_estado();
					while ($registro_botones = $consulta_botones->fetch())
						{
							//Define el tipo de boton de acuerdo al tipo de accion como Submit, Reset o Button
							$tipo_boton="Button";
							if ($registro_botones["tipo_accion"]=="interna_guardar")
								{
									$tipo_boton="Submit";
								}
							if ($registro_botones["tipo_accion"]=="interna_limpiar")
								{
									$tipo_boton="Reset";
								}
							if ($registro_botones["tipo_accion"]=="interna_escritorio")
								{
									$tipo_boton="Button";
									$comando_javascript="document.core_ver_menu.submit()";
								}
							if ($registro_botones["tipo_accion"]=="interna_eliminar")
								{
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='eliminar_datos_formulario';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="interna_cargar")
								{
									echo '<input type="hidden" name="objeto" value="'.$registro_botones["accion_usuario"].'">';
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='cargar_objeto';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="externa_formulario")
								{
									$tipo_boton="Button";
									$comando_javascript="document.datos.accion.value='".$registro_botones["accion_usuario"]."';document.datos.submit()";
								}
							if ($registro_botones["tipo_accion"]=="externa_javascript")
								{
									$tipo_boton="Button";
									$comando_javascript=$registro_botones["accion_usuario"];
								}
							if ($comando_javascript!="" && $tipo_boton!="Reset")
								{
									$cadena_javascript='onclick="'.@$comando_javascript.'"';
								}
							echo '<input type="'.$tipo_boton.'"  class="'.$registro_botones["estilo"].'" value="'.$registro_botones["titulo"].'" '.$cadena_javascript.' >';
						}
					cerrar_barra_estado();
				}

			//Cierra todo el formulario
			echo '</form>';
			if ($en_ventana) cerrar_ventana();
		  }



/* ################################################################## */
/* ################################################################## */
function cargar_informe($informe,$en_ventana=1,$formato="htm",$estilo="Informes")
	{
		global $ConexionPDO,$ArchivoCORE,$TablasCore,$Nombre_Aplicacion;

		// Busca datos del informe
		$consulta_informe=ejecutar_sql("SELECT * FROM ".$TablasCore."informe WHERE id='$informe'");
		$registro_informe=$consulta_informe->fetch();

		//Si no encuentra informe presenta error
		if ($registro_informe["id"]=="") mensaje("ERROR EN TIEMPO DE EJECUCION","El objeto (informe $informe) asociado a esta solicitud no existe.  Consulte con su administrador del sistema.<br>","70%","icono_error.png","TextosEscritorio");

		if ($en_ventana)
			{
				echo '<input type="Button" onclick="document.core_ver_menu.submit()" value=" <<< Volver a mi escritorio " class="Botones">';
				abrir_ventana($Nombre_Aplicacion.' - '.$registro_informe["titulo"],'f2f2f2',$registro_informe["ancho"]);
			}

		// Si se ha definido un tamano fijo entonces crea el marco
		if ($registro_informe["ancho"]!="" && $registro_informe["alto"]!="")
			echo '<DIV style="DISPLAY: block; OVERFLOW: auto; POSITION: relative; WIDTH: '.$registro_informe["ancho"].'; HEIGHT: '.$registro_informe["alto"].'">';

			// Crea encabezado por tipo de formato:  1=html   2=Excel
			if($formato=="htm")
				{
					echo '
						<html>
						<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" style="font-size: 12px; font-family: Verdana, Tahoma, Arial;">';

					// Si no tiene ancho o alto se asume que es para impresion y agrega titulo
					if ($registro_informe["ancho"]=="" || $registro_informe["alto"]=="")
						echo '<table class="'.$estilo.'">
							<thead><tr><td>
							'.$Nombre_Aplicacion.' - '.$registro_informe["titulo"].'
							</td></tr></thead></table>';

					// Pone encabezados de informe
					/*if ($registro_informe[filtro_cliente]!="")
						echo 'Empresa: '.$cliente.'  -  ';
					if ($registro_informe[filtro_fecha]!="")
						echo 'Desde '.$anoi.'/'.$mesi.'/'.$diai.' Hasta '.$anof.'/'.$mesf.'/'.$diaf.'';*/
					//echo '</font></div>';
				}

			if($formato=="xls")
				{
					$fecha = date("d-m-Y");
					$tituloinforme=trim($registro_informe["titulo"]);
					$tituloinforme="Informe";
					$nombrearchivo=$tituloinforme."_".$fecha;
					header('Content-type: application/vnd.ms-excel');
					header("Content-Disposition: attachment; filename=$nombrearchivo.xls");
					header("Pragma: no-cache");
					header("Expires: 0");
				}

			// Inicia construccion de consulta dinamica
			$numero_columnas=0;
			//Busca los CAMPOS definidos para el informe
			$consulta="SELECT ";
			$consulta_campos=ejecutar_sql("SELECT * FROM ".$TablasCore."informe_campos WHERE informe='$informe'");
			while ($registro_campos = $consulta_campos->fetch())
				{
					//Si tiene alias definido lo agrega
					$posfijo_campo="";
					if ($registro_campos["valor_alias"]!="") $posfijo_campo=" as ".$registro_campos["valor_alias"];
					//Agrega el campo a la consulta
					$consulta.=$registro_campos["valor_campo"].$posfijo_campo.",";
				}
			// Elimina la ultima coma en el listado de campos
			$consulta=substr($consulta, 0, strlen($consulta)-1);

			//Busca las TABLAS definidas para el informe
			$consulta.=" FROM ";
			$consulta_tablas=ejecutar_sql("SELECT * FROM ".$TablasCore."informe_tablas WHERE informe='$informe'");
			while ($registro_tablas = $consulta_tablas->fetch())
				{
					//Si tiene alias definido lo agrega
					$posfijo_tabla="";
					if ($registro_tablas["valor_alias"]!="") $posfijo_tabla=" as ".$registro_tablas["valor_alias"];
					//Agrega tabla a la consulta
					$consulta.=$registro_tablas["valor_tabla"].$posfijo_tabla.",";
				}
			// Elimina la ultima coma en el listado de tablas
			$consulta=substr($consulta, 0, strlen($consulta)-1);

			// Busca las CONDICIONES para el informe
			$consulta.=" WHERE ";
			$consulta_condiciones=ejecutar_sql("SELECT * FROM ".$TablasCore."informe_condiciones WHERE informe='$informe' ORDER BY peso");
			$hay_condiciones=0;
			while ($registro_condiciones = $consulta_condiciones->fetch())
				{
					//Agrega condicion a la consulta
					$consulta.=" ".$registro_condiciones["valor_izq"]." ".$registro_condiciones["operador"]." ".$registro_condiciones["valor_der"]." ";
					$hay_condiciones=1;
				}
			if (!$hay_condiciones)
			$consulta.=" 1 ";

			/*
			if ($registro_informe[filtro_cliente]!="")
				{
					$campocliente=$registro_informe[filtro_cliente];	
					$consulta.= " AND $campocliente = '$cliente'";
				}

			if ($registro_informe[filtro_fecha]!="")
				{
					$campofecha=$registro_informe[filtro_fecha];	
					if ($registro_informe[motor]=="mysql")
						$consulta.= " AND $campofecha BETWEEN '$anoi$mesi$diai' AND '$anof$mesf$diaf'";
				}

			if ($registro_informe[filtro_texto]!="")
				{
					$campotexto=$registro_informe[filtro_texto];
					$consulta.= " AND $campotexto = '$filtrotexto' ";
				}
				
			if ($registro_informe[agrupamiento]!="")
				{
					$campoagrupa=$registro_informe[agrupamiento];	
					$consulta.= " GROUP BY $campoagrupa";
				}
				
			if ($registro_informe[ordenamiento]!="")
				{
					$campoorden=$registro_informe[ordenamiento];
					$consulta.= " ORDER BY $campoorden";
				}
			*/


			if($formato=="htm")
				echo '<table class="'.$estilo.'">
					<thead><tr>';
			if($formato=="xls")
				echo '<table class="font-size: 11px; font-family: Verdana, Tahoma, Arial;">
					<thead><tr>';

			// Imprime encabezados de columna
			$resultado_columnas=ejecutar_sql($consulta);
			$numero_columnas=0;
			foreach($resultado_columnas->fetch(PDO::FETCH_ASSOC) as $key=>$val)
				{
					echo '<th align="LEFT">'.$key.'</th>';
					$numero_columnas++;
				}
			echo '</tr></thead><tbody>';

			// Imprime registros del resultado
			$numero_filas=0;
			$consulta_ejecucion=ejecutar_sql($consulta);
			while($registro_informe=$consulta_ejecucion->fetch())
				{
					echo '<tr>';
					for ($i=0;$i<$numero_columnas;$i++)
						echo '<td align=left>'.$registro_informe[$i].'</td>';
					echo '</tr>';
					$numero_filas++;
				}
			echo '</tbody>';
			if ($formato=="htm")
				echo '<tfoot>
					<tr><td colspan='.$numero_columnas.'>
						<b>Total registros encontrados: </b>'.$numero_filas.'
					</td></tr>
				</tfoot>';
			echo '</table>';

			if($formato=="htm")
				echo '
					</body>
					</html>';

		// Si se ha definido un tamano fijo entonces cierra el marco
		if ($registro_informe["ancho"]!="" && $registro_informe["alto"]!="")
			echo '</DIV>';

		if ($en_ventana) cerrar_ventana();
	}



/* ################################################################## */
/* ################################################################## */
	/*
		Section: Acciones a ser ejecutadas (si aplica) en cada cargue de la herramienta
	*/
/* ################################################################## */
/* ################################################################## */
	if ($accion=="cambiar_estado_campo")
		{		
			/*
				Function: cambiar_estado_campo
				Abre los espacios de trabajo dinamicos sobre el contenedor principal donde se despliega informacion

				Variables de entrada:

					tabla - Nombre de la tabla que contiene el registro a actualizar.
					campo - Nombre del campo que sera actualizado.
					id - Identificador unico del campo a ser actualizado.
					valor - Valor a ser asignado en el campo del registro cuyo identificador coincida con el recibido.

				Salida:

					Valor actualizado en el campo y retorno al escritorio de la aplicacion.  En caso de error se retorna al escritorio sin realizar cambios ante el fallo del query.
			*/

			$mensaje_error="";
			if ($mensaje_error=="")
				{
					ejecutar_sql_unaria("UPDATE ".$TablasCore."$tabla SET $campo = $valor WHERE id = '$id'");
					ejecutar_sql_unaria("INSERT INTO ".$TablasCore."auditoria VALUES (0,'$Login_usuario','Cambia estado del campo $campo en formulario $formulario','$fecha_operacion','$hora_operacion')");

					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="'.$accion_retorno.'">
						<input type="Hidden" name="nombre_tabla" value="'.$nombre_tabla.'">
						<input type="Hidden" name="formulario" value="'.$formulario.'">
						<input type="Hidden" name="informe" value="'.$informe.'">
						<input type="Hidden" name="popup_activo" value="'.$popup_activo.'">
						<script type="" language="JavaScript">
						//setTimeout ("document.cancelar.submit();", 10); 
						document.cancelar.submit();
						</script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="editar_formulario">
						<input type="Hidden" name="nombre_tabla" value="'.$nombre_tabla.'">
						<input type="Hidden" name="formulario" value="'.$formulario.'">
						<input type="Hidden" name="informe" value="'.$informe.'">
						<input type="Hidden" name="error_titulo" value="Problema en los datos ingresados">
						<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
						</form>
						<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
		}

?>
