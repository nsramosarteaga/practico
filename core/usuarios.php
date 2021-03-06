<?php
	/*
	Copyright (C) 2013  John F. Arroyave Gutiérrez
						unix4you2@gmail.com

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
	*/

			/*
				Title: Modulo usuarios
				Ubicacion *[/core/usuarios.php]*.  Archivo de funciones relacionadas con la administracion de usuarios y permisos del sistema.
			*/
?>
<?php
			/*
				Section: Administracion de permisos
				Funciones asociadas a la gestion de permisos, roles y demas posibilidades de acceso que puedan tener los usuarios en el aplicativo.
			*/
?>
<script language="Javascript">
	function buscar_texto_en_plantilla(texto,plantilla){
	   for(i=0; i<texto.length; i++){
		  if (plantilla.indexOf(texto.charAt(i),0)!=-1){
			 return 1;
		  }
	   }
	   return 0;
	} 

	function tiene_simbolos(texto){
		return buscar_texto_en_plantilla(texto,"!#$%&*");
	} 

	function tiene_numeros(texto){
		return buscar_texto_en_plantilla(texto,"0123456789");
	} 

	function tiene_letras(texto){
		return buscar_texto_en_plantilla(texto,"abcdefghyjklmnñopqrstuvwxyz");
	} 

	function tiene_minusculas(texto){
		return buscar_texto_en_plantilla(texto,"abcdefghyjklmnñopqrstuvwxyz");
	} 

	function tiene_mayusculas(texto){
		return buscar_texto_en_plantilla(texto,"ABCDEFGHYJKLMNÑOPQRSTUVWXYZ");
	} 

/* ################################################################## */
/* ################################################################## */
/*
	Function: seguridad_clave
	Retorna un valor asociado al nivel de seguridad de la clave recibida despues de buscar ciertos caracteres sobre esta.

	Variables de entrada:

		clave - Valor del campo clave digitado por el usuario

		(start code)
				if (tiene_numeros(clave)) seguridad += 10;
				if (tiene_minusculas (clave)) seguridad += 20;
				if (tiene_mayusculas(clave)) seguridad += 20;
				if (tiene_simbolos(clave)) seguridad += 20;
				if (tiene_minusculas(clave) && tiene_mayusculas(clave)) seguridad += 30;
				if (tiene_simbolos(clave) && (tiene_mayusculas(clave) || tiene_minusculas (clave))) seguridad += 10;
				if (clave.length <= 7) seguridad -= 40;
				if (clave.length >= 8) seguridad += 10;
		(end)

	Salida:
		Valor de la variable entera llamada seguridad.
		
			Ver tambien:
				<muestra_seguridad_clave> | <cambiar_clave>
*/
	function seguridad_clave(clave){
		var seguridad = 0;
		if (clave.length!=0)
			{
				if (tiene_numeros(clave)) seguridad += 10;
				if (tiene_minusculas (clave)) seguridad += 20;
				if (tiene_mayusculas(clave)) seguridad += 20;
				if (tiene_simbolos(clave)) seguridad += 20;
				if (tiene_minusculas(clave) && tiene_mayusculas(clave)) seguridad += 30;
				if (tiene_simbolos(clave) && (tiene_mayusculas(clave) || tiene_minusculas (clave))) seguridad += 10;
				if (clave.length <= 7) seguridad -= 40;
				if (clave.length >= 8) seguridad += 10;
			}
		if (seguridad>100) seguridad=100;
		if (seguridad<0) seguridad=0;
		return seguridad;
	}

/* ################################################################## */
/* ################################################################## */
/*
	Function: muestra_seguridad_clave
	Visualiza el valor asociado al nivel de seguridad de la clave sobre el formulario de diligenciamiento

	Variables de entrada:

		clave - Valor del campo clave digitado por el usuario
		formulario - Nombre del formulario sobre el que se actualiza el valor del campo seguridad.

		(start code)
			seguridad=seguridad_clave(clave);
			formulario.seguridad.value=seguridad;
		(end)

	Salida:
		Campo (visual del formulario) actualizado
		
	Ver tambien:
		<seguridad_clave> | <cambiar_clave>
*/
	function muestra_seguridad_clave(clave,formulario){
		seguridad=seguridad_clave(clave);
		formulario.seguridad.value=seguridad;
	}
</script>



<?php
/* ################################################################## */
/* ################################################################## */
/*
	Function: copiar_permisos
	Elimina los permisos definidos para un usuario y los reemplaza  con los permisos definidos actualmente para otro usuario

	Variables de entrada:

		usuariod - Usuario destino (al que seran copiados los permisos)
		usuarioo - Usuario oorigen (del que se toman los permisos como base para ser copiados)

		(start code)
			DELETE FROM ".$TablasCore."usuario_menu WHERE usuario='$usuariod'
			SELECT * FROM ".$TablasCore."usuario_menu WHERE usuario='$usuarioo'
			Repetir con cada permiso del usuario origen:
				INSERT INTO ".$TablasCore."usuario_menu VALUES (0,'$usuariod','$menuinsertar')
		(end)

	Salida:
		Permisos del usuario destino actualizados

	Ver tambien:
		<permisos_usuario> | <informes_usuario>
*/
if ($accion=="copiar_permisos")
	{
		// Elimina opciones existentes
		ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario_menu WHERE usuario=? ","$usuariod");
		// Copia permisos
		$resultado=ejecutar_sql("SELECT id,".$ListaCamposSinID_usuario_menu." FROM ".$TablasCore."usuario_menu WHERE usuario=? ","$usuarioo");
		while($registro = $resultado->fetch())
			{
				$menuinsertar=$registro["menu"];
				ejecutar_sql_unaria("INSERT INTO ".$TablasCore."usuario_menu (".$ListaCamposSinID_usuario_menu.") VALUES (?,?)","$usuariod$_SeparadorCampos_$menuinsertar");
			}
		auditar("Copia permisos de $usuarioo al usuario $usuariod");
		echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
			<input type="Hidden" name="accion" value="permisos_usuario">
			<input type="Hidden" name="usuario" value="'.$usuariod.'">
			</form>
			<script type="" language="JavaScript">
			alert("'.$MULTILANG_UsrCopia.'");
			document.cancelar.submit();  </script>';
	} 


/* ################################################################## */
/* ################################################################## */
/*
	Function: cambiar_clave
	Presenta formulario para actualizar la clave de un usuario

	Salida:
		Variables pasadas a la accion <actualizar_clave>

	Ver tambien:
		<actualizar_clave> | <muestra_seguridad_clave> | <seguridad_clave>

*/
if ($accion=="cambiar_clave")
	{
		echo '<div align="center">';
?>
		<div align="center">
		<DIV style="DISPLAY: block; OVERFLOW: auto; WIDTH: 100%; POSITION: relative; ">
			<form name="datos" action="<?php echo $ArchivoCORE; ?>" method="POST">
			<?php
				mensaje($MULTILANG_Importante,$MULTILANG_UsrDesPW,'60%','warning_icon.png','TextosEscritorio');
			?>
			<input type="hidden" name="accion" value="actualizar_clave">
			<br><font face="" size="3" color="Navy"><b><?php echo $MULTILANG_UsrCambioPW; ?></b></font>
			<table border="0" cellspacing="0" cellpadding="0"><tr>
				<td align="CENTER" valign="TOP">
					<table border="0" cellspacing="5" cellpadding="0" align="CENTER" style="font-family: Verdana, Tahoma, Arial; font-size: 10px; margin-top: 10px; margin-right: 10px; margin-left: 10px; margin-bottom: 10px;" class="link_menu">
						<tr>
							<td align="RIGHT"><b><?php echo $MULTILANG_UsrAnteriorPW; ?></b></td><td width="10"></td>
							<td>***********</td>
							<td></td>
						</tr>
						<tr>
							<td align="RIGHT"><b><?php echo $MULTILANG_UsrNuevoPW; ?></b></td><td width="10"></td>
							<td><input class="CampoTexto" type="password" name="clave1" size="27" maxlength="20" onkeyup="muestra_seguridad_clave(this.value, this.form)"></td>
							<td><?php echo $MULTILANG_UsrNivelPW; ?>: <input id="seguridad" value="0" size="3" name="seguridad" style="background:000000; border: 0px; text-decoration:italic;" class="CampoTexto" type="text" readonly onfocus="blur()">%</td>
						</tr>
						<tr>
							<td align="RIGHT"><b><?php echo $MULTILANG_UsrVerificaPW; ?></b></td><td width="10"></td>
							<td><input class="CampoTexto" type="password" name="clave2" size="27" maxlength="20" onkeypress="return FiltrarTeclas(this, event)"></td>
							<td></td>
						</tr>
						<tr>
							<td align="RIGHT">
								</form>
								<form action="<?php echo $ArchivoCORE; ?>" method="POST" style="height: 0px; padding-bottom: 0px; padding-top: 0px; margin-top: 0px; margin-bottom: 0px;" name="cancelar"><input type="Hidden" name="accion" value="Ver_menu"></form>
							</td><td width="5"></td>
							<td align="center" colspan=3>
								<br>
								<?php
									//Permite cambio solamente si es admin o el motor de autenticacion es practico
									if ($Auth_TipoMotor=="practico" || $Login_usuario=="admin")
										{
											echo '<input type="Button" name="" value="'.$MULTILANG_Actualizar.'" class="BotonesCuidado" onClick="document.datos.submit();">
											&nbsp;&nbsp;<input type="Button" onclick="document.cancelar.submit()" name="" value="'.$MULTILANG_Cancelar.'" class="Botones">';
										}
									else
										{
											echo '<br><h4>'.$MULTILANG_Importante.': '.$MULTILANG_UsrHlpNoPW.' ('.$Auth_TipoMotor.')</h4>';
										}
								?>
							</td>
							<td></td>
						</tr>
					</table>
				</td>
			</tr></table>
		</DIV>
		</div>
<?php
	}



/* ################################################################## */
/* ################################################################## */
/*
	Function: actualizar_clave
	Actualiza la clave de un usuario determinado

	Variables de entrada:

		Login_usuario - Variable de sesion con el UID/Login de usuario al que se desea actualizar la clave
		clave1 y clave2 - Valores ingresados para la nueva contrasena
		seguridad - Nivel de seguridad calculado para la contrasena

		(start code)
			"UPDATE ".$TablasCore."usuario SET clave=MD5('$clave1') WHERE login='$Login_usuario'"
		(end)

	Salida:
		Tabla de usuarios actualizada en el registro correspondiente
*/
if ($accion=="actualizar_clave")
	{
		$mensaje_error="";
		// Verifica campos nulos
		if ($clave1=="" || $clave2=="")
			$mensaje_error=$MULTILANG_UsrErrPW1.".<br>";
		// Verifica contrasena diferentes
		if ($clave1 != $clave2)
			$mensaje_error.=$MULTILANG_UsrErrPW2.".<br>";
		// Verifica nivel de seguridad
		if ($seguridad < 81)
			$mensaje_error.=$MULTILANG_UsrErrPW3.".<br>";

		if ($mensaje_error=="")
			{
				ejecutar_sql_unaria("UPDATE ".$TablasCore."usuario SET clave=MD5('$clave1') WHERE login=? ","$Login_usuario");
				auditar("Actualiza clave de acceso");
				echo '<script language="javascript"> document.core_ver_menu.submit(); </script>';
			}
		else
			{
				echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
					<input type="Hidden" name="accion" value="cambiar_clave">
					<input type="Hidden" name="error_titulo" value="'.$MULTILANG_ErrorDatos.'">
					<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
					</form>
					<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
			}
	}

	
/* ################################################################## */
/* ################################################################## */
/*
	Function: eliminar_informe_usuario
	Elimina un informe a un usuario determinado.

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea eliminar el permiso
		informe - ID del informe que se desea eliminar del perfil del usuario

		(start code)
			DELETE FROM usuario_informe WHERE informe=$informe AND usuario='$usuario'
		(end)

	Salida:
		Tabla de permisos actualizada al eliminar el registro correspondiente
	
	Ver tambien:
		<informes_usuario> | <agregar_informe_usuario>
*/
if ($accion=="eliminar_informe_usuario")
	{
		// Elimina el informe
		ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario_informe WHERE informe=? AND usuario=? ","$informe$_SeparadorCampos_$usuario");
		auditar("Elimina informe $informe a $usuario");
		echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST"><input type="Hidden" name="accion" value="informes_usuario"><input type="Hidden" name="usuario" value="'.$usuario.'"></form>
				<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
	}



/* ################################################################## */
/* ################################################################## */
/*
	Function: agregar_informe_usuario
	Agrega un informe definido en el sistema a un usuario determinado.

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea agregar el permiso
		informe - ID del informe que se desea agregar al perfil del usuario

		(start code)
			SELECT * FROM ".$TablasCore."usuario_informe WHERE usuario='$usuario' AND informe='$informe'
			INSERT INTO ".$TablasCore."usuario_informe VALUES (0,'$usuario','$informe')
		(end)

	Salida:
		Tabla de permisos actualizada al agregar el registro correspondiente

	Ver tambien:
		<eliminar_informe_usuario> | <informes_usuario>
*/
	if ($accion=="agregar_informe_usuario")
		{
			$mensaje_error="";
			// Busca si existe ese permiso para el usuario
			$resultado=ejecutar_sql("SELECT id,".$ListaCamposSinID_usuario_informe." FROM ".$TablasCore."usuario_informe WHERE usuario=? AND informe=? ","$usuario$_SeparadorCampos_$informe");
			$registro_menu = $resultado->fetch();
			if($registro_menu["informe"]!="")
				$mensaje_error=$MULTILANG_UsrErrInf;

			if ($mensaje_error=="")
				{
					// Guarda el permiso para el usuario
					ejecutar_sql_unaria("INSERT INTO ".$TablasCore."usuario_informe (".$ListaCamposSinID_usuario_informe.") VALUES (?,?)","$usuario$_SeparadorCampos_$informe");
					auditar("Agrega informe $informe al usuario $usuario");
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
							<input type="Hidden" name="accion" value="informes_usuario">
							<input type="Hidden" name="usuario" value="'.$usuario.'">
							</form>
							<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST"><input type="Hidden" name="accion" value="informes_usuario"><input type="Hidden" name="usuario" value="'.$usuario.'"></form>
							<script type="" language="JavaScript"> 
							window.alert("'.$mensaje_error.'");
							document.cancelar.submit();  </script>';
				}
		}



/* ################################################################## */
/* ################################################################## */
/*
	Function: informes_usuario
	Despliega una lista con los informes definidos para un usuario determinado con la posibilidad de agregar mas informes

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea agregar el permiso

		(start code)
			SELECT ".$TablasCore."informe.* FROM ".$TablasCore."informe WHERE nivel_usuario<=".$Nivel_usuario
			SELECT ".$TablasCore."informe.* FROM ".$TablasCore."informe,".$TablasCore."usuario_informe WHERE ".$TablasCore."usuario_informe.informe=".$TablasCore."informe.id AND ".$TablasCore."usuario_informe.usuario='$usuario'
		(end)

	Salida:
		Listado de informes disponibles en el perfil del usuario

	Ver tambien:
		<eliminar_informe_usuario> | <agregar_informe_usuario>
*/
if ($accion=="informes_usuario")
				{
						echo '<div align="center"><br>';
						abrir_ventana($MULTILANG_UsrAdmInf,'f2f2f2','60%');
		?>

		<div align="center" class="TextosVentana">
		<DIV style="DISPLAY: block; OVERFLOW: auto; WIDTH: 100%; POSITION: relative; HEIGHT: 290px">
			<form name="datos" action="<?php echo $ArchivoCORE; ?>" method="POST">
			<input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
			<input type="hidden" name="accion" value="agregar_informe_usuario">
			<br><font face="" size="3" color="Navy"><b><?php echo $MULTILANG_UsrAgreInf; ?>: <?php echo $usuario; ?></b></font>
			<br><br>
				<select name="informe" class="Combos">
					<?php
						//Despliega opciones de informes para agregar, aunque solamente las que este por debajo del perfil del usuario
						//No se permite agregar opciones por encima del perfil actual del usuario
						$resultado=ejecutar_sql("SELECT ".$TablasCore."informe.* FROM ".$TablasCore."informe WHERE nivel_usuario<=".$Nivel_usuario." ");
						while($registro = $resultado->fetch())
							{
								echo '<option value="'.$registro["id"].'">'.$registro["titulo"].'</option>';
							}
					?>
				</select>

			<table width="90%" border="0" cellspacing="0" cellpadding="0" class="TextosVentana"><tr>
				<td align="RIGHT" valign="TOP">
					<table border="0" cellspacing="5" cellpadding="0" align="CENTER" style="font-family: Verdana, Tahoma, Arial; font-size: 10px; margin-top: 10px; margin-right: 10px; margin-left: 10px; margin-bottom: 10px;" class="link_menu">
						<tr>
							<td align="RIGHT">
									</form>
							</td><td width="5"></td>
							<td align="RIGHT">
									<input type="Button" name="" value=" <?php echo $MULTILANG_Agregar; ?> " class="BotonesCuidado" onClick="document.datos.submit()">
									&nbsp;&nbsp;<input type="Button" onclick="document.core_ver_menu.submit()" name="" value=" <?php echo $MULTILANG_Cancelar; ?> " class="Botones">
							</td>
						</tr>
					</table>
				</td>
			</tr></table>

		<font face="" size="3" color="Navy"><b><?php echo $MULTILANG_UsrInfDisp; ?></b></font><br><br>
		<?php
			echo '
			<table width="100%" border="0" cellspacing="5" align="CENTER" class="TextosVentana">
				<tr>
					<td align="LEFT" bgcolor="#D6D6D6"><b>ID</b></td>
					<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_Titulo.'</b></td>
					<td align="LEFT" bgcolor="#D6D6D6"><b>'.$MULTILANG_InfCategoria.'</b></td>
					<td align="left"></td>
				</tr>';

			$resultado=ejecutar_sql("SELECT ".$TablasCore."informe.* FROM ".$TablasCore."informe,".$TablasCore."usuario_informe WHERE ".$TablasCore."usuario_informe.informe=".$TablasCore."informe.id AND ".$TablasCore."usuario_informe.usuario=? ","$usuario");
			while($registro = $resultado->fetch())
				{
					echo '<tr>
							<td>'.$registro["id"].'</td>
							<td><strong>'.$registro["titulo"].'</strong></td>
							<td>'.$registro["categoria"].'</td>
							<td align="center">
									<form action="'.$ArchivoCORE.'" method="POST" name="f'.$registro["id"].'" id="f'.$registro["id"].'">
											<input type="hidden" name="accion" value="eliminar_informe_usuario">
											<input type="hidden" name="usuario" value="'.$usuario.'">
											<input type="hidden" name="informe" value="'.$registro["id"].'">
											<input type="button" value="'.$MULTILANG_Eliminar.'" class="BotonesCuidado" onClick="confirmar_evento(\''.$MULTILANG_UsrAdvDel.'\',f'.$registro["id"].');">
									</form>
							</td>
						</tr>';
				}
			echo '</table>';
		?>
		</DIV>
		</div>

		 <?php
		 				cerrar_ventana();
		 		}


/* ################################################################## */
/* ################################################################## */
/*
	Function: eliminar_permiso
	Elimina un permiso a un usuario determinado.

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea eliminar el permiso
		menu - ID del menu que se desea eliminar del perfil del usuario

		(start code)
			DELETE FROM usuario_menu WHERE menu=$menu AND usuario='$usuario'
		(end)

	Salida:
		Tabla de permisos actualizada al eliminar el registro correspondiente
	
	Ver tambien:
		<agregar_permiso>
*/
if ($accion=="eliminar_permiso")
	{
		// Elimina los datos de la opcion
		ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario_menu WHERE menu=? AND usuario=? ","$menu$_SeparadorCampos_$usuario");
		auditar("Elimina permiso $menu a $usuario");
		echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST"><input type="Hidden" name="accion" value="permisos_usuario"><input type="Hidden" name="usuario" value="'.$usuario.'"></form>
				<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
	}



/* ################################################################## */
/* ################################################################## */
/*
	Function: agregar_permiso
	Agrega un permiso a un usuario determinado.

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea agregar el permiso
		menu - ID del menu que se desea agregar del perfil del usuario

		(start code)
			SELECT * FROM ".$TablasCore."usuario_menu WHERE usuario='$usuario' AND menu='$menu'
			INSERT INTO ".$TablasCore."usuario_menu VALUES (0,'$usuario','$menu')
		(end)

	Salida:
		Tabla de permisos actualizada al agregar el registro correspondiente
	
	Ver tambien:
		<eliminar_permiso>
*/
	if ($accion=="agregar_permiso")
		{
			$mensaje_error="";
			// Busca si existe ese permiso para el usuario
			$resultado=ejecutar_sql("SELECT id,".$ListaCamposSinID_usuario_menu." FROM ".$TablasCore."usuario_menu WHERE usuario=? AND menu=? ","$usuario$_SeparadorCampos_$menu");
			$registro_menu = $resultado->fetch();
			if($registro_menu["menu"]!="")
				$mensaje_error=$MULTILANG_UsrErrInf;

			if ($mensaje_error=="")
				{
					// Guarda el permiso para el usuario
					ejecutar_sql_unaria("INSERT INTO ".$TablasCore."usuario_menu (".$ListaCamposSinID_usuario_menu.") VALUES (?,?)","$usuario$_SeparadorCampos_$menu");
					auditar("Agrega permiso $menu al usuario $usuario");
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
							<input type="Hidden" name="accion" value="permisos_usuario">
							<input type="Hidden" name="usuario" value="'.$usuario.'">
							</form>
							<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST"><input type="Hidden" name="accion" value="permisos_usuario"><input type="Hidden" name="usuario" value="'.$usuario.'"></form>
							<script type="" language="JavaScript"> 
							window.alert("'.$mensaje_error.'");
							document.cancelar.submit();  </script>';
				}
		}



/* ################################################################## */
/* ################################################################## */
/*
	Function: permisos_usuario
	Despliega una lista con las opciones de menu definidas para un usuario determinado con la posibilidad de agregar o eliminar

	Variables de entrada:

		usuario - UID/Login de usuario al que se desea agregar el permiso

		(start code)
			SELECT login FROM ".$TablasCore."usuario WHERE login<>'admin' AND login<>'$usuario' ORDER BY login
			SELECT ".$TablasCore."menu.* FROM ".$TablasCore."menu WHERE nivel_usuario<=".$Nivel_usuario
			SELECT ".$TablasCore."menu.* FROM ".$TablasCore."menu,".$TablasCore."usuario_menu WHERE ".$TablasCore."usuario_menu.menu=".$TablasCore."menu.id AND ".$TablasCore."usuario_menu.usuario='$usuario'
		(end)

	Salida:
		Listado de opciones de menu disponibles en el perfil del usuario

	Ver tambien:
		<informes_usuario>
*/
if ($accion=="permisos_usuario")
				{
						echo '<div align="center"><br>';
						abrir_ventana($MULTILANG_UsrAdmPer,'f2f2f2','60%');
		?>

		<div align="center" class="TextosVentana">
			<form name="datoscopia" action="<?php echo $ArchivoCORE; ?>" method="POST">
			<input type="hidden" name="usuariod" value="<?php echo $usuario; ?>">
			<input type="hidden" name="accion" value="copiar_permisos">

			<br><font face="" size="3" color="#971515"><b><?php echo $MULTILANG_UsrCopiaPer; ?>: </b></font>
				<select name="usuarioo" class="selector_01" >
						<option value=""><?php echo $MULTILANG_UsrDelPer; ?></option>
						<?php
							$resultado=ejecutar_sql("SELECT login FROM ".$TablasCore."usuario WHERE login<>'admin' AND login<>? ORDER BY login","$usuario");
							while($registro = $resultado->fetch())
								{
									echo '<option value="'.$registro["login"].'">'.$registro["login"].'</option>';
								}
						?>
				</select>
					<input type="Button" name="" value="<?php echo $MULTILANG_Ejecutar; ?>" class="BotonesCuidado" onClick="document.datoscopia.submit()">
			</form>
		<br><hr>

		<DIV style="DISPLAY: block; OVERFLOW: auto; WIDTH: 100%; POSITION: relative; HEIGHT: 290px">
			<form name="datos" action="<?php echo $ArchivoCORE; ?>" method="POST">
			<input type="hidden" name="usuario" value="<?php echo $usuario; ?>">
			<input type="hidden" name="accion" value="agregar_permiso">
			<br><font face="" size="3" color="Navy"><b><?php echo $MULTILANG_UsrAgreOpc; ?>: <?php echo $usuario; ?></b></font>
			<br><br>
				<select name="menu" class="Combos">
					<?php
						//Despliega opciones de menu para agregar, aunque solamente las que este por debajo del perfil del usuario
						//No se permite agregar opciones por encima del perfil actual del usuario
						$resultado=ejecutar_sql("SELECT ".$TablasCore."menu.* FROM ".$TablasCore."menu WHERE nivel_usuario<=".$Nivel_usuario." ");
						while($registro = $resultado->fetch())
							{
								echo '<option value="'.$registro["id"].'">'.$registro["texto"].'</option>';
							}
					?>
				</select>

			<table width="90%" border="0" cellspacing="0" cellpadding="0" class="TextosVentana"><tr>
				<td align="RIGHT" valign="TOP">
					<table border="0" cellspacing="5" cellpadding="0" align="CENTER" style="font-family: Verdana, Tahoma, Arial; font-size: 10px; margin-top: 10px; margin-right: 10px; margin-left: 10px; margin-bottom: 10px;" class="link_menu">
						<tr>
							<td align="RIGHT">
									</form>
							</td><td width="5"></td>
							<td align="RIGHT">
									<input type="Button" name="" value=" <?php echo $MULTILANG_Agregar; ?> " class="BotonesCuidado" onClick="document.datos.submit()">
									&nbsp;&nbsp;<input type="Button" onclick="document.core_ver_menu.submit()" name="" value=" <?php echo $MULTILANG_Cancelar; ?> " class="Botones">
							</td>
						</tr>
					</table>
				</td>
			</tr></table>

		<font face="" size="3" color="Navy"><b><?php echo $MULTILANG_UsrSecc; ?></b></font><br><br>
		<?php
			echo '
			<table width="100%" border="0" cellspacing="5" align="CENTER" class="TextosVentana">
				<tr>
					<td align="LEFT" bgcolor="#D6D6D6"><b>ID</b></td>
					<td align="LEFT"></td>
					<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_Etiqueta.'</b></td>
					<td align="LEFT" bgcolor="#D6D6D6"><b>'.$MULTILANG_Tipo.'</b></td>
					<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_MnuComando.'</b></td>
					<td align="left"></td>
				</tr>';

			$resultado=ejecutar_sql("SELECT ".$TablasCore."menu.* FROM ".$TablasCore."menu,".$TablasCore."usuario_menu WHERE ".$TablasCore."usuario_menu.menu=".$TablasCore."menu.id AND ".$TablasCore."usuario_menu.usuario=? ","$usuario");
			while($registro = $resultado->fetch())
				{
					echo '<tr>
							<td>'.$registro["id"].'</td>
							<td align=right><img src="img/'.$registro["imagen"].'" border=0 alt="" valign="absmiddle" align="absmiddle" width="14" height="13" ></td>
							<td><strong>'.$registro["texto"].'</strong></td>
							<td>'.$registro["tipo_comando"].'</td>
							<td>'.$registro["comando"].'</td>
							<td align="center">
									<form action="'.$ArchivoCORE.'" method="POST" name="f'.$registro["id"].'" id="f'.$registro["id"].'">
											<input type="hidden" name="accion" value="eliminar_permiso">
											<input type="hidden" name="usuario" value="'.$usuario.'">
											<input type="hidden" name="menu" value="'.$registro["id"].'">
											<input type="button" value="'.$MULTILANG_Eliminar.'" class="BotonesCuidado" onClick="confirmar_evento(\''.$MULTILANG_UsrAdvDel.'\',f'.$registro["id"].');">
									</form>
							</td>
						</tr>';
				}
			echo '</table>';
		?>
		</DIV>
		</div>

		 <?php
		 				cerrar_ventana();
		 		}



/* ################################################################## */
/* ################################################################## */
			/*
				Section: Operaciones basicas de administracion
				Funciones asociadas al mantenimiento de la informacion de usuarios: Adicion, edicion, eliminacion, cambios de estado.
			*/
/* ################################################################## */
/* ################################################################## */
	if ($accion=="eliminar_usuario")
		{
			/*
				Function: eliminar_usuario
				Elimina completamente un usuario del sistema.   Tambien elimina todos los permisos que puedan haber sido definidos para el.

				Variables minimas de entrada:
					uid_especifico - Login del usuario

				Proceso simplificado:
					(start code)
						DELETE FROM ".$TablasCore."usuario WHERE login='$uid_especifico'
						DELETE FROM ".$TablasCore."usuario_menu WHERE usuario='$uid_especifico'
					(end)

				Salida de la funcion:
					* Tabla de usuarios actualizada al eliminar el registro asociado

				Ver tambien:
					<listar_usuarios> | <agregar_usuario> | <cambiar_estado_usuario>
			*/
			ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario WHERE login=? ","$uid_especifico");
			ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario_menu WHERE usuario=? ","$uid_especifico");
			ejecutar_sql_unaria("DELETE FROM ".$TablasCore."usuario_informe WHERE usuario=? ","$uid_especifico");
			auditar("Elimina el usuario $uid_especifico");
			echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
		}



/* ################################################################## */
/* ################################################################## */
	if ($accion=="cambiar_estado_usuario")
		{
			/*
				Function: cambiar_estado_usuario
				Permite inhabilitar un usuario en el sistema sin tener que eliminarlo completamente o habilitarlo cuando ya se encuentre inhabilitado previamente.   Al actualizar el estado del usuario como Habilitado tambien se actualiza la ultima fecha de ingreso como la actual para controles de login posteriores.

				Variables minimas de entrada:
					uid_especifico - Login del usuario
					estado - Estado actual del usuario: 1=Activo, 0=Inactivo

				Proceso simplificado:
					(start code)
						if ($estado==1)
							$consulta = "UPDATE ".$TablasCore."usuario SET estado=0 WHERE login='$uid_especifico'";
						else
							$consulta = "UPDATE ".$TablasCore."usuario SET estado=1, ultimo_acceso='$fecha_operacion' WHERE login='$uid_especifico'";
					(end)

				Salida de la funcion:
					* Usuario con estado diferente (contrario) al recibido

				Ver tambien:
					<listar_usuarios> | <eliminar_usuario>
			*/
			if ($estado==1)
				ejecutar_sql_unaria("UPDATE ".$TablasCore."usuario SET estado=0 WHERE login=? ","$uid_especifico");
			else
				ejecutar_sql_unaria("UPDATE ".$TablasCore."usuario SET estado=1, ultimo_acceso=? WHERE login=? ","$fecha_operacion$_SeparadorCampos_$uid_especifico");
			auditar("Cambia estado del usuario $uid_especifico");
			echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
		}


/* ################################################################## */
/* ################################################################## */
	if ($accion=="resetear_clave")
		{
			/*
				Function: resetear_clave
				Restablece la contrasena de un usuario por la nueva ingresada

				Variables minimas de entrada:
					uid_especifico - Login del usuario
					nueva_clave - Nueva clave para el usuario

				Salida de la funcion:
					* Usuario con su clave actualizada

				Ver tambien:
					<listar_usuarios> | <eliminar_usuario>
			*/
			ejecutar_sql_unaria("UPDATE ".$TablasCore."usuario SET clave=MD5('$nueva_clave') WHERE login=? ","$uid_especifico");
			auditar("Restablece clave de acceso para $uid_especifico");
			echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
		}


/* ################################################################## */
/* ################################################################## */
	if ($accion=="guardar_usuario")
		{
			/*
				Function: guardar_usuario
				Almacena la informacion basica de un usuario en la base de datos

				Variables minimas de entrada:
					login - Nickname o login para el usuario.  Debe ser un identificador unico y no existir ya en el sistema
					nombre - Nombre completo del usuario.
					clave - Contrasena sin encriptar del usuario

				Proceso simplificado:
					(start code)
						SELECT login as uid_db FROM ".$TablasCore."usuario WHERE login='$login'
						INSERT INTO ".$TablasCore."usuario VALUES ('$login','$clavemd5','$nombre','$descripcion',$estado,'$nivel','$correo','$fecha_operacion','$pasomd5')"
					(end)

				Salida de la funcion:
					* Usuario registrado en el sistema.  El proceso agrega ademas las claves en MD5 y la llave de paso definida en el archivo de <Libreria base> 

				Ver tambien:
					<agregar_usuario> | <eliminar_usuario>
			*/
			$mensaje_error="";

			// Verifica que no existe el usuario
			$resultado_usuario=ejecutar_sql("SELECT login FROM ".$TablasCore."usuario WHERE login=? ","$login");
			$registro_usuario = $resultado_usuario->fetch();
			if ($registro_usuario["login"]!="")
				$mensaje_error=$MULTILANG_UsrErrCrea1;

			// Verifica campos nulos
			if ($nombre=="" || $login=="" || $clave=="")
				$mensaje_error=$MULTILANG_UsrErrCrea2;

			// Verifica contrasenas:  longitud e igualdad de la verificacion
			if ($clave!=$clave1)
				$mensaje_error=$MULTILANG_UsrErrPW2;
			if (strlen($clave)<6)
				$mensaje_error=$MULTILANG_UsrErrCrea3;

			if ($mensaje_error=="")
				{
					// Inserta datos del usuario
					$clavemd5=MD5($clave);
					$pasomd5=MD5($LlaveDePaso);
					ejecutar_sql_unaria("INSERT INTO ".$TablasCore."usuario (".$ListaCamposSinID_usuario.") VALUES (?,?,?,?,?,?,?,?,?)","$login$_SeparadorCampos_$clavemd5$_SeparadorCampos_$nombre$_SeparadorCampos_$descripcion$_SeparadorCampos_$estado$_SeparadorCampos_$nivel$_SeparadorCampos_$correo$_SeparadorCampos_$fecha_operacion$_SeparadorCampos_$pasomd5");
					auditar("Agrega usuario $login para $nombre");
					echo '<script type="" language="JavaScript"> document.core_ver_menu.submit();  </script>';
				}
			else
				{
					echo '<form name="cancelar" action="'.$ArchivoCORE.'" method="POST">
						<input type="Hidden" name="accion" value="agregar_usuario">
						<input type="Hidden" name="error_titulo" value="'.$MULTILANG_ErrorDatos.'">
						<input type="Hidden" name="error_descripcion" value="'.$mensaje_error.'">
						</form>
						<script type="" language="JavaScript"> document.cancelar.submit();  </script>';
				}
		}



/* ################################################################## */
/* ################################################################## */
if ($accion=="agregar_usuario")
	{
			/*
				Function: agregar_usuario
				Presenta el formulario base para la adicion de usuarios al sistema.

				Salida de la funcion:
					* Llamada al proceso <guardar_usuario> para almacenar la informacion correspondiente al nuevo usuario.

				Ver tambien:
					<listar_usuarios> | <permisos_usuario> | <eliminar_usuario> | <cambiar_estado_usuario> | <muestra_seguridad_clave> | <seguridad_clave>
			*/
		echo '<div align="center">';
		abrir_ventana($MULTILANG_UsrAdicion,'f2f2f2','');
?>

			<table width="500" border="0" cellspacing="5" cellpadding="0" align="center" class="TextosVentana">
					<tr>
						<td valign="top"><img src="img/info_icon.png" alt="" border="0">
						</td>
						<td valign="top"><strong><?php echo $MULTILANG_Importante; ?>:<br></strong>
						<?php echo $MULTILANG_UsrDesPW; ?>
						</td>
					</tr>
				</table>


		<!-- VALOR MD5 PARA VACIO:  d41d8cd98f00b204e9800998ecf8427e-->
					<form name="datos" action="<?php echo $ArchivoCORE; ?>" method="POST">
					<input type="hidden" name="accion" value="guardar_usuario">
					<table border="0" cellspacing="5" cellpadding="0" align="CENTER" style="font-family: Verdana, Tahoma, Arial; font-size: 10px; margin-top: 10px; margin-right: 10px; margin-left: 10px; margin-bottom: 10px;"  class="TextosVentana">
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_UsrLogin; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="Text" name="login" size="11" maxlength="20"  onkeypress="return validar_teclado(event, 'alfanumerico');" >
							<a href="#" title="<?php echo $MULTILANG_FrmObligatorio; ?>" name=""><img src="img/icn_12.gif" border=0></a>
							<a href="#" title="<?php echo $MULTILANG_Ayuda; ?>" name="<?php echo $MULTILANG_UsrDesLogin; ?>"><img src="img/icn_10.gif" border=0></a>
							</td>
						</tr>
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_UsrNombre; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="Text" name="nombre" size="30" maxlength="200" onkeypress="return validar_teclado(event, 'alfanumerico');">
							<a href="#" title="<?php echo $MULTILANG_FrmObligatorio; ?>" name=""><img src="img/icn_12.gif" border=0></a>
							</td>
						</tr>
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_Contrasena; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="password" name="clave" size="11" maxlength="20" onkeyup="muestra_seguridad_clave(this.value, this.form)">
							<a href="#" title="<?php echo $MULTILANG_FrmObligatorio; ?>" name=""><img src="img/icn_12.gif" border=0></a>
							&nbsp;&nbsp; <?php echo $MULTILANG_UsrNivelPW; ?>: <input id="seguridad" value="0" size="3" name="seguridad" style="border: 0px; background-color:ffffff; text-decoration:italic;" class="CampoTexto" type="text" readonly onfocus="blur()">%
							</td>
						</tr>
						<!--
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_InfDescripcion.' '.$MULTILANG_MotorAuth; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="Text" name="descripcion" size="30" maxlength="200" onkeypress="return validar_teclado(event, 'alfanumerico');"></td>
						</tr>
						-->
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_UsrVerificaPW; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="password" name="clave1" size="11" maxlength="20">
							<a href="#" title="Campo obligatorio" name=""><img src="img/icn_12.gif" border=0></a>
							</td>
						</tr>
						<tr>
							<td align="RIGHT" valign="TOP"><?php echo $MULTILANG_Correo; ?></td><td width="20"></td>
							<td><input class="CampoTexto" type="Text" name="correo" size="30" maxlength="200" onkeypress="return FiltrarTeclas(this, event)">
								<a href="#" title="<?php echo $MULTILANG_UsrTitCorreo; ?>" name="<?php echo $MULTILANG_UsrDesCorreo; ?>"><img src="img/icn_10.gif" border=0></a>
							</td>
						</tr>
						<tr>
							<td align="RIGHT"><?php echo $MULTILANG_UsrEstado; ?></td><td width="20"></td>
							<td>
								<select name="estado" class="selector_01">
										<option value="1"><?php echo $MULTILANG_Habilitado; ?></option>
										<option value="0"><?php echo $MULTILANG_Deshabilitado; ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="RIGHT" valign="TOP"><strong><?php echo $MULTILANG_UsrNivel; ?></strong></td><td width="10"></td>
							<td>
								<!-- Caracteres UNICODE para el combo
									Revisar: http://www.okelmann.com/homepage/unicode.htm -->
								<select  name="nivel" id="nivel"  style="font-family: Verdana, Tahoma, Arial; font-weight: normal; font-size: 10px; width: 100px; background-color: #FFFFFF; color: #000000;">
									<option value="1">&#9733;</option>
									<option value="2">&#9733;&#9733;</option>
									<option value="3">&#9733;&#9733;&#9733;</option>
									<option value="4">&#9733;&#9733;&#9733;&#9733;</option>
									<option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; SuperAdmin</option>
								</select>
								<a href="#" title="<?php echo $MULTILANG_UsrTitNivel; ?>" name="<?php echo $MULTILANG_UsrDesNivel; ?>"><img src="img/icn_10.gif" border=0></a>
							</td>
						</tr>
						<tr>
							<td align="RIGHT"></td><td width="20">
									</form>
							</td>
							<td>
									<input type="Button" onclick="document.datos.submit()" name="" value="<?php echo $MULTILANG_Guardar; ?>" class="Botones">
									&nbsp;&nbsp;<input type="Button" onclick="document.core_ver_menu.submit()" name="" value="<?php echo $MULTILANG_Cancelar; ?>" class="Botones">
							</td>
						</tr>
					</table>
		 <?php
		 				cerrar_ventana();
		 		}
		 ?>

<?php


/* ################################################################## */
if ($accion=="ver_seguimiento_monitoreo")		
				{
			/*
				Function: ver_seguimiento_monitoreo
				Presenta las ultimas 30 acciones del sistema, permitiendo su actualizacion automatica cada 5 segundos

				Proceso simplificado:
					(start code)
						SELECT * FROM ".$TablasCore."auditoria ORDER BY fecha DESC, hora DESC LIMIT 0,30
					(end)

				Salida de la funcion:
					* Listado de operaciones realizadas actualizado automaticamente

				Ver tambien:
					<listar_usuarios> | <ver_seguimiento_especifico>
			*/
						echo '<div align="center"><br>';
				abrir_ventana($MULTILANG_UsrAudit1,'#BDB9B9','90%');
					echo '<form name="datos" action="'.$ArchivoCORE.'" method="POST">
						</form>';
				echo '<table width="100%" border="0" cellspacing="2" align="CENTER" class="TextosVentana">
					<tr>
						<td align="center" bgcolor="#d6d6d6"><b>ID</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrLogin.'</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrAudDes.'</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Fecha.' (AAAA-MM-DD)</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Hora.' (HH-MM-SS)</b></td>
					</tr>';


				$resultado=ejecutar_sql("SELECT id,".$ListaCamposSinID_auditoria." FROM ".$TablasCore."auditoria ORDER BY fecha DESC, hora DESC LIMIT 0,30");
				while($registro = $resultado->fetch())
					{
						echo '<tr><td align="center">'.$registro["id"].'</td>
								<td>'.$registro["usuario_login"].'</td>
								<td>'.$registro["accion"].'</td>
								<td align="center">'.$registro["fecha"].'</td>
								<td align="center">'.$registro["hora"].'</td>
							</tr>';
					}
				echo '</table>
				<script type="text/JavaScript">
					setTimeout("document.ver_auditoria_monitoreo.submit();",5000);
				</script>
				<form action="'.$ArchivoCORE.'" method="POST" name="ver_auditoria_monitoreo"  style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="hidden" name="accion" value="ver_seguimiento_monitoreo">
				</form>
				';
				abrir_barra_estado();
				echo '<input type="Button" onclick="document.core_ver_menu.submit()" value=" << '.$MULTILANG_IrEscritorio.' " class="BotonesEstado">';
				cerrar_barra_estado();
				cerrar_ventana();
				 }
/* ################################################################## */
if ($accion=="ver_seguimiento_general")
				{
			/*
				Function: ver_seguimiento_general
				Presenta un filtro para navegar tablas de auditoria con todas las operaciones sobre el sistema

				Proceso simplificado:
					(start code)
						SELECT login FROM ".$TablasCore."usuario ORDER BY login
						SELECT * FROM ".$TablasCore."auditoria WHERE fecha>='$anoi$mesi$diai' AND fecha<= '$anof$mesf$diaf' AND accion LIKE '%$accionbuscar%' AND usuario_login LIKE '%$usuario%' ORDER BY fecha DESC, hora DESC LIMIT $inicio_reg,$fin_reg
					(end)

				Salida de la funcion:
					* Listado de operaciones realizadas que cumple el filtro de busqueda

				Ver tambien:
					<listar_usuarios> | <ver_seguimiento_especifico> | <ver_seguimiento_monitoreo>
			*/

				echo '<div align="center"><br>';
				abrir_ventana($MULTILANG_UsrAudUsrs,'#BDB9B9','90%');
				if (@$inicio_reg=="") $inicio_reg=0;
				if (@$fin_reg=="") $fin_reg=50;
					echo ' <br><div align="center">
								<form name="datos" action="'.$ArchivoCORE.'" method="POST">
								&nbsp;&nbsp;'.$MULTILANG_UsrAudAccion.'
								<input type="text" class="CampoTexto" name="accionbuscar" value="'.@$accionbuscar.'" size="30" maxlength="200"> '.$MULTILANG_UsrAudUsuario.'
								<select name="usuario" class="Combos">
									<option value="">'.$MULTILANG_Cualquiera.'</option>';
										$resultado=ejecutar_sql("SELECT login FROM ".$TablasCore."usuario ORDER BY login");
										while($registro = $resultado->fetch())
											{
												echo '<option value="'.$registro["login"].'">'.$registro["login"].'</option>';
											}
					echo '		</select>	<br>
								&nbsp;&nbsp;'.$MULTILANG_UsrAudDesde.' <select name="diai" class="selector_01" >';
													for ($i=1;$i<=date("j")-1;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													if ($i<10)
															echo '<option value="0'.date("j").'" selected>0'.date("j").'</option>';
													else
															echo '<option value="'.date("j").'" selected>'.date("j").'</option>';
													for ($i=date("j")+1;$i<=31;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													echo '</select>
														<select name="mesi" class="selector_01" >';
													for ($i=1;$i<=date("n")-1;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													if ($i<10)
															echo '<option value="0'.date("n").'" selected>0'.date("n").'</option>';
													else
															echo '<option value="'.date("n").'" selected>'.date("n").'</option>';
													for ($i=date("n")+1;$i<=12;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													echo '</select>';
									
								echo ' &nbsp;'.$MULTILANG_UsrAudHasta.' &nbsp;&nbsp;<select name="diaf" class="selector_01" >';
													for ($i=1;$i<=date("j")-1;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													if ($i<10)
															echo '<option value="0'.date("j").'" selected>0'.date("j").'</option>';
													else
															echo '<option value="'.date("j").'" selected>'.date("j").'</option>';
													for ($i=date("j")+1;$i<=31;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													echo '</select>
														<select name="mesf" class="selector_01" >';
													for ($i=1;$i<=date("n")-1;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
													if ($i<10)
															echo '<option value="0'.date("n").'" selected>0'.date("n").'</option>';
													else
															echo '<option value="'.date("n").'" selected>'.date("n").'</option>';
													for ($i=date("n")+1;$i<=12;$i++)
														{
																if ($i<10)
																		echo '<option value="0'.$i.'">0'.$i.'</option>';
																else
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
														
														
													echo '</select><br> '.$MULTILANG_UsrAudAno.': 
														<select name="ano" class="selector_01" >';
													for ($i=2003;$i<=date("Y")-1;$i++)
														{
																echo '<option value="'.$i.'">'.$i.'</option>';
														}
															echo '<option value="'.date("Y").'" selected>'.date("Y").'</option>';
													for ($i=date("Y")+1;$i<=2005;$i++)
														{
																		echo '<option value="'.$i.'">'.$i.'</option>';
														}
							echo '
									</select><br>
								<input type="hidden" name="accion" value="ver_seguimiento_general">
								&nbsp;&nbsp;'.$MULTILANG_UsrAudIniReg.'
								<input type="text" class="CampoTexto"  name="inicio_reg" value="'.$inicio_reg.'" size="4" maxlength="6">
								'.$MULTILANG_UsrAudVisual.' 
								<input type="text" class="CampoTexto" name="fin_reg" value="'.$fin_reg.'" size="4" maxlength="6"> '.$MULTILANG_TblRegistros.'
								&nbsp;&nbsp;<input type="submit" value=" '.$MULTILANG_Ejecutar.' >> " class="Botones">&nbsp;&nbsp;
						</form>
					</div>';
				echo '<table width="100%" border="0" cellspacing="2" align="CENTER" class="TextosVentana">
					<tr>
						<td align="center" bgcolor="#d6d6d6"><b>ID</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrLogin.'</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrAudDes.'</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Fecha.' (AAAA-MM-DD)</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Hora.' (HH-MM-SS)</b></td>
					</tr>';

				@$anoi = $ano;
				@$anof = $ano;
				if ($anoi=="") {$anoi=date("Y"); $anof=$anoi;}
				if (@$mesi=="") {$mesi=date("n"); $mesf=$mesi;}
				if (@$diai=="") {$diai=date("j"); $diaf=$diai;}

				$resultado=@ejecutar_sql("SELECT id,".$ListaCamposSinID_auditoria." FROM ".$TablasCore."auditoria WHERE fecha>='$anoi$mesi$diai' AND fecha<= '$anof$mesf$diaf' AND accion LIKE '%$accionbuscar%' AND usuario_login LIKE '%$usuario%' ORDER BY fecha DESC, hora DESC LIMIT $inicio_reg,$fin_reg");
				while($registro = $resultado->fetch())
					{
						echo '<tr><td align="center">'.$registro["id"].'</td>
								<td>'.$registro["usuario_login"].'</td>
								<td>'.$registro["accion"].'</td>
								<td align="center">'.$registro["fecha"].'</td>
								<td align="center">'.$registro["hora"].'</td>
							</tr>';
					}
				echo '</table>
				<form action="'.$ArchivoCORE.'" method="POST" name="ver_auditoria_monitoreo"  style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="hidden" name="accion" value="ver_seguimiento_monitoreo">
				</form>
				';

				abrir_barra_estado();
				echo '<input type="Button" onclick="document.core_ver_menu.submit()" value=" << '.$MULTILANG_IrEscritorio.' " class="BotonesEstado">';
				echo '<input type="Button" onclick="document.ver_auditoria_monitoreo.submit()" value=" '.$MULTILANG_UsrAudMonit.' " class="BotonesEstadoCuidado">';
				cerrar_barra_estado();
				cerrar_ventana();
				 }
/* ################################################################## */
if ($accion=="ver_seguimiento_especifico")
				{
			/*
				Function: ver_seguimiento_especifico
				Presenta las ultimas operaciones realizadas por un usuario.  Por defecto las ultimas 50 acciones.

				Variables minimas de entrada:
					uid_especifico - Login del usuario
					inicio_reg - Numero de registro de inicio
					fin_reg - Numero de registros a visualizar

				Proceso simplificado:
					(start code)
						SELECT * FROM ".$TablasCore."auditoria WHERE usuario_login='$uid_especifico' ORDER BY fecha DESC, hora DESC LIMIT $inicio_reg,$fin_reg"
					(end)

				Salida de la funcion:
					* Listado de operaciones realizadas por el usuario

				Ver tambien:
					<listar_usuarios> | <ver_seguimiento_general>
			*/
						echo '<div align="center"><br>';
				abrir_ventana($MULTILANG_UsrAudHisto,'#BDB9B9','90%');
				if ($inicio_reg=="") $inicio_reg=0;
				if ($fin_reg=="") $fin_reg=50;
					echo ' <br><div align="right">
								<form name="datos" action="'.$ArchivoCORE.'" method="POST">
								<input type="hidden" name="accion" value="ver_seguimiento_especifico">
								<input type="hidden" name="uid_especifico" value="'.$uid_especifico.'">
								&nbsp;&nbsp;'.$MULTILANG_UsrAudIniReg.'
								<input type="text" class="CampoTexto" name="inicio_reg" value="'.$inicio_reg.'" size="4" maxlength="6">
								'.$MULTILANG_UsrAudVisual.'
								<input type="text" class="CampoTexto" name="fin_reg" value="'.$fin_reg.'" size="4" maxlength="6"> '.$MULTILANG_TblRegistros.'
								&nbsp;&nbsp;<input type="submit" value="'.$MULTILANG_Ejecutar.' >>>" class="Botones">&nbsp;&nbsp;
						</form>
					</div>';
				echo '<table width="100%" border="0" cellspacing="0" align="CENTER" class="TextosVentana">
					<tr>
						<td align="center" bgcolor="#d6d6d6"><b>ID</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrLogin.'</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrAudDes.'</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Fecha.' (AAAA-MM-DD)</b></td>
						<td align="center" bgcolor="#d6d6d6"><b>'.$MULTILANG_Hora.' (HH-MM-SS)</b></td>
					</tr>';
				$resultado=ejecutar_sql("SELECT id,".$ListaCamposSinID_auditoria." FROM ".$TablasCore."auditoria WHERE usuario_login=? ORDER BY fecha DESC, hora DESC LIMIT $inicio_reg,$fin_reg","$uid_especifico");
				while($registro = $resultado->fetch())
					{
						echo '<tr><td align="center">'.$registro["id"].'</td>
								<td>'.$registro["usuario_login"].'</td>
								<td>'.$registro["accion"].'</td>
								<td align="center">'.$registro["fecha"].'</td>
								<td align="center">'.$registro["hora"].'</td>
							</tr>';
					}
				echo '</table>';
				abrir_barra_estado();
				echo '<input type="Button" onclick="document.core_ver_menu.submit()" value=" << '.$MULTILANG_IrEscritorio.' " class="BotonesEstado">';
				cerrar_barra_estado();
				cerrar_ventana();
				echo '<br>';
				 }
/* ################################################################## */
/* ################################################################## */
			/*
				Section: Informes
				Funciones que presentan informacion de los usuarios o permisos como listados.
			*/
/* ################################################################## */
/* ################################################################## */
if ($accion=="listar_usuarios")
				{
			/*
				Function: listar_usuarios
				Presenta el listado de usuarios (excluido el usuario admin) con posibilidad de filtrado y operaciones basicas.

				Variables minimas de entrada:
					nombre_filtro - Nombre del usuario (o parte)
					login_filtro - Parte del UID o Login

				Proceso simplificado:
					(start code)
						SELECT * FROM ".$TablasCore."usuario WHERE (login LIKE '%$login_filtro%') AND (nombre LIKE '%$nombre_filtro%' ) AND login<>'admin' ORDER BY login,nombre";
					(end)

				Salida de la funcion:
					* Listado de usuarios filtrado por algun criterio y ordenado por login y nombre

				Ver tambien:
					<agregar_usuario> | <permisos_usuario> | <eliminar_usuario> | <cambiar_estado_usuario>
			*/
				echo "<a href='javascript:abrir_ventana_popup(\"http://www.youtube.com/embed/0KzASMtKRcc\",\"VideoTutorial\",\"toolbar=no, location=no, directories=no, status=no, menubar=no ,scrollbars=no, resizable=yes, fullscreen=no, width=640, height=480\");'><img src='img/icono_screencast.png' alt='ScreenCast-VideoTutorial'></a>";

				abrir_ventana($MULTILANG_UsrLista,'f2f2f2','');

				echo '<br>
		<div align="center">
			<form name="form_crear_usuario" action="'.$ArchivoCORE.'" method="POST"  style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
				<input type="hidden" name="accion" value="agregar_usuario">
			</form>
			<form action="'.$ArchivoCORE.'" method="POST">
					'.$MULTILANG_UsrLisNombre.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="CampoTexto" type="Text" value="'.@$nombre_filtro.'" name="nombre_filtro" size="20">
				<input type="hidden" name="accion" value="listar_usuarios">
				<input type="submit" value="'.$MULTILANG_Ejecutar.' >>>" class="Botones">
			</form>
			<form action="'.$ArchivoCORE.'" method="POST">
					'.$MULTILANG_UsrLisLogin.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input class="CampoTexto" type="Text" value="'.@$login_filtro.'" name="login_filtro" size="20">
				<input type="hidden" name="accion" value="listar_usuarios">
				<input type="submit" value="'.$MULTILANG_Ejecutar.' >>>" class="Botones">
			</form>
		';

			if (@$nombre_filtro == "" && @$login_filtro=="")
			{
				echo '<br>'.$MULTILANG_UsrFiltro;
			}
			else
			{
				echo '
				<table border="0" cellspacing="3" align="CENTER" class="TextosVentana">
					<tr>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrLogin.'</b></td>
						<td align="LEFT" bgcolor="#D6D6D6"><b>'.$MULTILANG_Nombre.'</b></td>
						<td align="left" bgcolor="#d6d6d6"><b>'.$MULTILANG_UsrAcceso.'</b></td>
						<td align="left"></td>
						<td align="left"></td>
						<td align="left"></td>
						<td align="left"></td>
						<td align="left"></td>
					</tr>
					';
				$resultado=ejecutar_sql("SELECT ".$ListaCamposSinID_usuario." FROM ".$TablasCore."usuario WHERE (login LIKE '%$login_filtro%') AND (nombre LIKE '%$nombre_filtro%' ) AND login<>'admin' ORDER BY login,nombre");
				$i=0;
				while($registro = $resultado->fetch())
					{
						echo '<tr><td align="left">'.$registro["login"].'</td>
								<td>'.$registro["nombre"].'</td>
								<td>'.$registro["ultimo_acceso"].'</td>
								<td align="center">
										<form action="'.$ArchivoCORE.'" method="POST">
												<input type="hidden" name="accion" value="cambiar_estado_usuario">
												<input type="hidden" name="uid_especifico" value="'.$registro["login"].'">
												<input type="hidden" name="estado" value="'.$registro["estado"].'">
												<input type="Submit" value="';
												if ($registro["estado"]==1) echo $MULTILANG_Suspender;
												else echo $MULTILANG_Habilitar;
												echo '" class="Botones">
										</form>
								</td>
								<td align="center">
										<form action="'.$ArchivoCORE.'" method="POST"  name="f'.$i.'">
												<input type="hidden" name="accion" value="eliminar_usuario">
												<input type="hidden" name="uid_especifico" value="'.$registro["login"].'">
												<input type="Button"   onClick="confirmar_evento(\''.$MULTILANG_UsrAdvSupr.'\',f'.$i.');" name="" value="'.$MULTILANG_Eliminar.'" class="BotonesCuidado">
										</form>
								</td>
								<td align="center">
										<form action="'.$ArchivoCORE.'" method="POST">
												<input type="hidden" name="accion" value="permisos_usuario">
												<input type="hidden" name="usuario" value="'.$registro["login"].'">
												<input type="Submit" value="'.$MULTILANG_UsrAddMenu.'" class="Botones">
										</form>
								</td>
								<td align="center">
										<form action="'.$ArchivoCORE.'" method="POST">
												<input type="hidden" name="accion" value="informes_usuario">
												<input type="hidden" name="usuario" value="'.$registro["login"].'">
												<input type="Submit" value="'.$MULTILANG_UsrAddInfo.'" class="Botones">
										</form>
								</td>
								<td align="center">
										<form action="'.$ArchivoCORE.'" method="POST">
												<input type="hidden" name="accion" value="ver_seguimiento_especifico">
												<input type="hidden" name="uid_especifico" value="'.$registro["login"].'">
												<input type="Submit" value="'.$MULTILANG_UsrAuditoria.'" class="Botones">
										</form>
								</td>
							<tr>
								<td colspan=3>
								</td>
								<td colspan=5 align=center>
										<form action="'.$ArchivoCORE.'" method="POST" style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
												<input type="hidden" name="accion" value="resetear_clave">
												<input type="hidden" name="uid_especifico" value="'.$registro["login"].'">
												<input type="Submit" value="'.$MULTILANG_UsrReset.' -->" class="BotonesCuidado">
												'.$MULTILANG_UsrNuevoPW.': <input type="text" name="nueva_clave" size=12 value="'.TextoAleatorio(10).'">
										</form>
								</td>
							</tr>
							<tr>
								<td colspan=8><hr>
								</td>
							</tr>
							</tr>';
							$i++;
					}
				echo '</table>';
				
			} // Fin sino filtro

	echo '
		</div>
				<form action="'.$ArchivoCORE.'" method="POST" name="ver_auditoria_general"  style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="hidden" name="accion" value="ver_seguimiento_general">
				</form>
				<form action="'.$ArchivoCORE.'" method="POST" name="ver_auditoria_monitoreo"  style="display:inline; height: 0px; border-width: 0px; width: 0px; padding: 0; margin: 0;">
					<input type="hidden" name="accion" value="ver_seguimiento_monitoreo">
				</form>
		';
				abrir_barra_estado();
				echo '<input type="Button" onclick="document.core_ver_menu.submit()" value=" << '.$MULTILANG_IrEscritorio.' " class="BotonesEstado">';
				echo '<input type="Button" onclick="document.form_crear_usuario.submit()" value=" '.$MULTILANG_UsrAgregar.' " class="BotonesEstadoCuidado">';
				echo '<input type="Button" onclick="document.ver_auditoria_general.submit()" value=" '.$MULTILANG_UsrVerAudit.' " class="BotonesEstado">';
				echo '<input type="Button" onclick="document.ver_auditoria_monitoreo.submit()" value=" '.$MULTILANG_UsrAudMonit.' " class="BotonesEstadoCuidado">';
				cerrar_barra_estado();
				cerrar_ventana();
			 }
		?>
