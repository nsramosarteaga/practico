﻿<?php
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
		Title: Idioma espanol
		Ubicacion *[/inc/idioma/es.php]*.  Incluye la definicion de variables utilizadas para presentar mensajes en el idioma correspondiente
		NOTA IMPORTANTE: Por cuestiones de rendimiento se recomienda la definicion usando comillas simples, usar las dobles solo cuando se requieran variables o caracteres especiales.
	*/

	// Cadena que describe el archivo de idioma para su escogencia
	$MULTILANG_DescripcionIdioma='Espanol';

	//Lexico general (palabras y frases comunes a varios modulos)
	$MULTILANG_Accion='Accion';
	$MULTILANG_Anonimo='An&oacute;nimo';
	$MULTILANG_Anterior='Anterior';
	$MULTILANG_Apagado='Apagado';
	$MULTILANG_Atencion='Atenci&oacute;n';
	$MULTILANG_Ayuda='Ayuda';
	$MULTILANG_Basedatos='Base de datos';
	$MULTILANG_CaracteresCaptcha='N&uacute;mero de caracteres para captcha?';
	$MULTILANG_CerrarSesion='Cerrar sesi&oacute;n';
	$MULTILANG_ConfiguracionGeneral='Configuraci&oacute;n General';
	$MULTILANG_ConfiguracionVarias='Configuraci&oacute;n de opciones varias';
	$MULTILANG_Continuar='Continuar';
	$MULTILANG_Contrasena='Contrase&ntilde;a';
	$MULTILANG_Correcto='Correcto';
	$MULTILANG_Detalles='Detalles';
	$MULTILANG_Encendido='Encendido';
	$MULTILANG_Error='Error';
	$MULTILANG_Importante='Importante';
	$MULTILANG_Ingresar='Ingresar';
	$MULTILANG_Instante='Instante';
	$MULTILANG_LlavePaso='Llave de paso';
	$MULTILANG_MotorBD='Motor de Base de Datos';
	$MULTILANG_Opcional='Opcional';
	$MULTILANG_Paso='Paso';
	$MULTILANG_Puerto='Puerto';
	$MULTILANG_Servidor='Servidor';
	$MULTILANG_TiempoCarga='Tiempo de carga';
	$MULTILANG_Tipo='Tipo';
	$MULTILANG_TipoMotor='Tipo de motor';
	$MULTILANG_Usuario='Usuario';
	$MULTILANG_Version='Versi&oacute;n';
	$MULTILANG_ZonaHoraria='Zona horaria';
	

	//Errores y avisos varios
	$MULTILANG_TituloInsExiste='ATENCION: La carpeta de instalaci&oacute;n existe en el servidor';
	$MULTILANG_TextoInsExiste='Este mensaje aparecer&aacute; de manera permanente a todos sus usuarios mientras usted no elimine el directorio utilizado durante el proceso de instalaci&oacute;n de Pr&aacute;ctico.  Es fundamental que la carpeta sea eliminada despu&eacute;s de finalizar una instalaci&oacute;n para evitar que algun usuario an&oacute;nimo inicie nuevamente el proceso sobreescribiendo archivos de configuraci&oacute;n o bases de datos con informaci&oacute;n de importancia para usted.<br><br>Si ya ha finalizado un proceso de instalaci&oacute;n de Pr&aacute;ctico para su uso en producci&oacute;n es importante que elimine esta carpeta antes de continuar.  Si no desea eliminar esta carpeta puede optar por renombrarla en instalaciones temporales o de prueba.<br><br>Si est&aacute; visualizando este mensaje al ejecutar este script por primera vez y desea realizar una instalaci&oacute;n nueva, puede iniciar el asistente haciendo <input type="button" Value="clic AQUI" Onclick="document.location=\'ins\'" class="BotonesCuidado">';
	$MULTILANG_ErrorTiempoEjecucion='Error en tiempo de ejecuci&oacute;n';
	$MULTILANG_ErrorModulo='El modulo central esta tratando de incluir un modulo ubicado en <b>mod/</b> pero no encuentra su punto de accceso.<br>Verifique el estado del m&oacute;dulo, consulte con su administrador o elimine el m&oacute;dulo en conflicto para evitar este mensaje.';

	//Ventana de login
	$MULTILANG_TituloLogin='Ingreso al sistema';
	$MULTILANG_CodigoSeguridad='Codigo de seguridad';
	$MULTILANG_IngreseCodigoSeguridad='Ingrese aqui el codigo de seguridad';
	$MULTILANG_AccesoExclusivo='El acceso a este software es exlusivo para usuarios registrados. Por su seguridad, nunca comparta su nombre de usuario y contrase&ntilde;a.';

	//Cierre de sesion
	$MULTILANG_SesionCerrada='Su sesi&oacute;n ha sido cerrada';
	$MULTILANG_TituloCierre='Esto puede ocasionarse por acciones ejecutadas por el usuario como';
	$MULTILANG_ExplicacionCierre='<li>Cerrar de manera voluntaria su sesi&oacute;n</li>
			<li>Dejar de usar el sistema durante un tiempo prolongado</li>
			<li>Tener abiertas varias ventanas del sistema al mismo tiempo en secciones restringidas por el administrador</li>
			<li>Su usuario o contrase&ntilde;a son inv&aacute;lidos para realizar alguna operaci&oacute;n</li>
			<li>Navegar utilizando enlaces o botones diferentes a los permitidos</li>
			<font color="#000000">
			<br><strong>Tambi&eacute;n por configuraciones o acciones de su equipo como:</strong><br>
			<font color="#808080">
			<li>Su navegador no est&aacute; soportando cookies</li>
			<li>Se ha lipiado la cach&eacute; cookies o sesiones del navegador mientras se usaba el sistema</li>
			<font color="#000000">
			<br><strong>Tambi&eacute;n por configuraciones del sistema como:</strong><br>
			<font color="#808080">
			<li>Haber finalizado un proceso de instalaci&oacute;n de la plataforma que requiere un reinicio de sesi&oacute;n</li>
			<li>La llave de paso de su usuario no corresponde a la llave solicitada por este sistema</li>
			<li>Las credenciales para firmar un registro de operaci&oacute;n no son v&aacute;lidas</li>';

	//Proceso de instalacion
	$MULTILANG_Instalacion='Proceso de instalaci&oacute;n';
	$MULTILANG_SubtituloPractico1='Generador de Aplicaciones WEB';
	$MULTILANG_SubtituloPractico2='Libre y multiplataforma';
	$MULTILANG_InstaladorAplicacion='Instalador de aplicaci&oacute;n';
	$MULTILANG_BienvenidaInstalacion='Bienvenido al proceso de instalaci&oacute;n';
	$MULTILANG_BienvenidaDescripcion='Este asistente le guiar&aacute; en cada paso de las configuraciones iniciales para el uso de Pr&aacute;ctico como un entorno visual para el desarrollo de aplicaciones web';
	$MULTILANG_ResumenLicencia='Esta herramienta es liberada bajo licencia GNU-GPL v2';
	$MULTILANG_AmpliacionLicencia='Una copia en l&iacute;nea de esta licencia puede ser encontrada en diferentes formatos e idiomas en el <a href="http://www.gnu.org/licenses/gpl-2.0.html">sitio web de la GNU</a>';
	$MULTILANG_ChequeoPreprocesador='Chequeo configuraci&oacute;n de preprocesador';
	$MULTILANG_VistaPreprocesador='Una vista de su configuraci&oacute;n de PHP se encuentra disponible en <b><a target="_blank" href="paso_i.php">[este enlace]</a>';
	$MULTILANG_CumplirRequisitos='Debe cumplir con lo siguiente';
	$MULTILANG_CumplirPDO='Extensi&oacute;n PDO activada';
	$MULTILANG_CumplirDrivers='Driver PDO para el tipo de base de datos deseada';
	$MULTILANG_CumplirGD='Extensi&oacute;n GD 2+ para manipulaci&oacute;n de gr&aacute;ficos y su soporte para FreeType 2+';
	$MULTILANG_ChequeoDirectorios1='Chequeo de directorios';
	$MULTILANG_ChequeoDirectorios2='Los siguientes archivos y directorios deben contar con permisos de escritura para que el aplicativo	pueda operar correctamente';
	$MULTILANG_ErrorEscritura='<b>Se han encontrado errores al tratar de escribir en los directorios de instalaci&oacute;n !!!</b>:<br>Las rutas indicadas deben pertenecer al usuario del webserver que ejecuta los scripts de Pr&aacute;ctico (generalmente apache<br>www, www-data u otro similar) y contar con permisos 755 para el caso de carpetas y 644 para los archivos.<br>Una forma r&aacute;pida de actualizar estos permisos puede ser ejecutando desde la raiz de Pr&aacute;ctico los comandos:<li>find . -type d -exec chmod 755 {} \;  &nbsp;&nbsp;(cambiar&aacute; todos los permisos de carpetas)<li>find . -type f -exec chmod 644 {} \;  &nbsp;&nbsp;(cambiar&aacute; todos los permisos de archivos)<li>chown -R www-data *  &nbsp;&nbsp;(asumiendo que www-data es el usuario que corre el servicio web)';
	$MULTILANG_ProbarNuevamente='Probar de nuevo';
	$MULTILANG_ConfiguracionDescripcion='Indique la configuraci&oacute;n deseada para el almacenamiento de aplicaciones e informaci&oacute;n de usuario generada por Pr&aacute;ctico, as&iacute; como otras opciones importantes de la herramienta.  Esta ventana ser&aacute; presentada s&oacute;lo una vez as&iacute; que aseg&uacute;rese de diligenciar y confirmar toda la informaci&oacute;n requerida';
	$MULTILANG_PuertoNoPredeterminado='(si no es el predeterminado)';
	$MULTILANG_AyudaTitMotor='MySQL y MariaDB';
	$MULTILANG_AyudaDesMotor='Son los motores oficiales.  Sobre ellos se hace el desarrollo y pruebas de la herramienta y aunque gracias a PDO usted podr&aacute; utilizar la herramienta en otros motores es probable que deba hacer ajustes a operaciones espec&iacute;ficas de &eacute;stos.';
	$MULTILANG_AyudaTitBD='La base de datos debe existir previamente';
	$MULTILANG_AyudaDesBD='Para motores diferentes a SQLite usted debe haber creado primero la base de datos.  Para SQLite solamente requiere especificar el nombre del archivo asociado a la BD (ej. practico.sqlite3) y Practico intentara crearlo por usted siempre y cuando tenga los permisos adecuados sobre su servidor web.';
	$MULTILANG_PrefijoCore='Prefijo tablas internas de Pr&aacute;ctico';
	$MULTILANG_PrefijoApp='Prefijo tablas de Aplicaci&oacute;n';
	$MULTILANG_AyudaTitPreCore='Se recomienda NO vac&iacute;o Ni Mayusculas';
	$MULTILANG_AyudaDesPreCore='';
	$MULTILANG_AyudaTitPreApp='Importante';
	$MULTILANG_AyudaDesPreApp='El prefijo utilizado para las tablas de aplicaci&oacute;n puede ser utilizado para separar diferentes instalaciones de Pr&aacute;ctico sobre una misma base de datos o tambi&eacute;n puede ser dejado vac&iacute;o para enlazar/integrar a Pr&aacute;ctico con otras aplicaciones pre-existentes. No se recomienda mayusculas para compatibilidad entre motores.';
	$MULTILANG_AyudaLlave='valor para firmar cuentas de usuario';
	$MULTILANG_NotasImportantesInst1='<u>IMPORTANTE 1</u>: La base de datos debe existir previamente para que Pr&aacute;ctico pueda conectarse a ella y generar las estructuras requeridas.  Consulte con su proveedor de hosting o administrador de sistemas c&oacute;mo crear una base de datos con privilegios suficientes para trabajar con Pr&aacute;ctico.<br><br><u>IMPORTANTE 2</u>: El instalador eliminar&aacute; todas las tablas existentes sobre la base de datos indicada y que coincidan con los nombres de tablas que utilizar&aacute; Pr&aacute;ctico.  Si usted considera que puede tener informaci&oacute;n importante en ellas se recomienda realizar una copia de seguridad antes de continuar.  Si desea compartir una misma base de datos entre diferentes instalaciones de Pr&aacute;ctico puede cambiar los prefijos de tabla utilizados por cada una.';
	$MULTILANG_ParametrosApp='Par&aacute;metros para su primera aplicaci&oacute;n';
	$MULTILANG_ParamNombreEmpresa='Nombre corto de su Organizaci&oacute;n o empresa';
	$MULTILANG_ParamNombreApp='Nombre de su aplicaci&oacute;n';
	$MULTILANG_ParamVersionApp='Versi&oacute;n inicial de su aplicaci&oacute;n';
	$MULTILANG_AyudaTitNomEmp='Nombre a desplegar en la parte superior';
	$MULTILANG_AyudaDesNomEmp='Este texto ser&aacute; utilizado en informes y espacios de la aplicaci&oacute;n que requieran un nombre corto para identificar su organizaci&oacute;n.';
	$MULTILANG_AyudaTitNomApp='Nombre descriptivo';
	$MULTILANG_AyudaDesNomApp='El nombre especificado aparecer&aacute; siempre en la parte superior de su aplicaci&oacute;n.';
	$MULTILANG_NotasImportantesInst2='<u>IMPORTANTE</u>: Otros parametros como nombre largo y corto de su empresa, fecha de lanzamiento, textos de licencia y creditos podran ser modificados posteriormente mediante las opciones disponibles para el usuario administrador.';
	$MULTILANG_AyudaTitCaptcha='Longitud de la palabra';
	$MULTILANG_AyudaDesCaptcha='Indica el n&uacute;mero de s&iacute;mbolos utilizados en la palabra de seguridad que deben ingresar los usuarios para cada acceso al sistema.';
	$MULTILANG_ModoDepuracion='Activar modo de depuraci&oacute;n?';
	$MULTILANG_AyudaTitDebug='Presentar errores y advertencias';
	$MULTILANG_AyudaDesDebug='Para sitios en producci&oacute;n esta opci&oacute;n debe estar apagada.  Cuando se enciende ense&ntilde;a durante la ejecuci&oacute;n de la aplicaci&oacute;n todos los errores y mensajes que puedan ser generados por el preprocesador de hipertexto - PHP';
	$MULTILANG_MotorAuth='Motor de autenticaci&oacute;n';
	$MULTILANG_AuthPractico='Interno (Tablas propias de Pr&aacute;ctico usando MD5)';
	$MULTILANG_AuthLDAP='LDAP (Servidor de directorio)';
	$MULTILANG_AyudaDesAuth='El uso de un motor de autenticaci&oacute;n diferente a Pr&aacute;ctico no excluye la creaci&oacute;n de los usuarios sobre la herramienta.  El motor externo servira como metodo para validar el login y clave correspondiente como un m&eacute;todo de autenticaci&oacute;n centralizado; pero el resto de caracter&iacute;sticas del perfil ser&aacute;n tomadas desde el usuario Pr&aacute;ctico.  El cambio de contrase&ntilde;a en Pr&aacute;ctico ser&aacute; deshabilitado para que sea controlada solamente por el motor externo.  El usuario admin seguir&aacute; siendo siempre aut&oacute;nomo para no perder control de acceso por errores de configuraci&oacute;n.';
	$MULTILANG_AyudaTitCript='Tipo de encripcion de claves usado por el motor';
	$MULTILANG_AyudaDesCript='Especifique el tipo de encripcion utilizado por el sistema de autenticacion que va a utilizar.  Pr&aacute;ctico encriptar&aacute; el valor de clave ingresado por el usuario antes de enviarla al motor a verificaci&oacute;n.';
	$MULTILANG_AlgoritmoCripto='Algoritmo de encripci&oacute;n';
	$MULTILANG_Dominio='Dominio';
	$MULTILANG_UO='Unidad organizacional o contexto';
	$MULTILANG_AyudaDesLdapIP='Indique la direccion IP del servidor de directorio o su nombre en caso de poder ser resuelto.';
	$MULTILANG_AyudaDesLdapDominio='Dominio utilizado por el servidor. Ejemplo: midominio.com.co  Con esto sera creada la cadena interna dc=midominio,dc=com,dc=co';
	$MULTILANG_AyudaDesLdapUO='Contexto de conexion del usuario. Debe existir sobre el servidor LDAP, ej: people, ventas, mercadeo, etc';
	$MULTILANG_TitInsPaso3='Escribiendo configuraci&oacute;n y conectando Base de Datos';
	$MULTILANG_DesInsPaso3='Se esta escribiendo el archivo de configuracion.php ubicado en /core con los par&aacute;metros por usted indicados y se est&aacute; probando la conexi&oacute;n a la base de datos indicada.';
	$MULTILANG_ErrorEscribirConfig='<b>Se han encontrado errores al tratar de escribir el archivo de configuraci&oacute;n !!!</b>:<br>Si lo desea una alternativa puede ser cambiar usted mismo los valores por defecto incluidos en el archivo core/configuracion.php.<br><br>Tambi&eacute;n puede cambiar los permisos al archivo de configuraci&oacute;n y probar nuevamente con este asistente.';
	$MULTILANG_ErrorConexBD='<b>Se han encontrado errores al conectar con la Base de Datos !!!</b>:<br>Verifique los valores ingresados en el paso anterior e intente nuevamente.';
	$MULTILANG_InfoPaso3='<b>Todo parace estar bien con su configuraci&oacute;n b&aacute;sica de PDO.</b><br>El ultimo paso consiste en indicar al asistente de instalaci&oacute;n como tratar su base de datos:<br><br>
				<li><b>1.</b> Agregar datos de inicio a la base de datos, esto incluye el usuario inicial (admin), menues y dem&aacute;s registros sobre las tablas Core de Pr&aacute;ctico.  Esta es la mejor opci&oacute;n para las instalaciones nuevas.
				<li><b>2.</b> Dejar la base de datos como est&aacute;, lo que indica que no debe ser ejecutada ninguna operaci&oacute;n sobre la base de datos.  Esta opci&oacute;n es &uacute;til cuando se intenta hacer una instalaci&oacute;n sobre una base de datos existente que contiene aplicaciones dise&ntilde;adas y usuarios activos.  Tambi&eacute;n puede entenderse como una base de datos en blanco para instalaciones nuevas que no tendr&aacute; siquiera usuarios para accesar ni opciones para seleccionar.';
	$MULTILANG_BtnAplicarBD='1. Agregar info inicial a la BD';
	$MULTILANG_BtnNoAplicarBD='2. No modificar la BD conectada';
	$MULTILANG_ExeScripts='Ejecutando scripts de base de datos (si aplica)';
	$MULTILANG_ErrorScripts='Error ejecutando la consulta sobre la base de datos';
	$MULTILANG_IrInstalacion='Ir a su instalaci&oacute;n de Pr&aacute;ctico';
	$MULTILANG_Totalejecutado='Total consultas ejecutadas';
	$MULTILANG_MsjFinal1='Si esta es una instalaci&oacute;n nueva puede ingresar al sistema mediante las credenciales<b> admin/admin</b> y cambiarlas luego por las que usted desee.';
	$MULTILANG_MsjFinal2='Recuerde eliminar por completo el directorio de instalaci&oacute;n (carpeta /ins)</b></u> para evitar que otra persona ejecute nuevamente estos scripts sobre un sistema en producci&oacute;n pudiendo ocasionar alg&uacute;n tipo de da&ntilde;o.';
	$MULTILANG_MsjFinal2='Resumen de operaciones ejecutadas';
?>