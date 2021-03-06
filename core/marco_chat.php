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
		Title: Seccion con los contactos de Chat disponibles
		Ubicacion *[/core/marco_chat.php]*.  Presenta la lista de usuarios del sistema para poder entablar chat con ellos

	Ver tambien:
		<Seccion superior> | <Articulador>
	*/


/* ################################################################## */
/* ################################################################## */

?>

		<!-- INICIO DE MARCOS POPUP -->
		<div id='BarraFlotanteChat' class="FormularioPopUps">
			<?php
				abrir_ventana($MULTILANG_UsrLista,'#f2f2f2',''); 
				echo '<div align=center><br>'.$MULTILANG_UsuariosChat.'<br><br></div>';

				//Consulta los usuarios siempre y cuando tenga sesion activa
				if ($Sesion_abierta)
					{
						$resultado=ejecutar_sql("SELECT $ListaCamposSinID_usuario from ".$TablasCore."usuario WHERE login<>'$Login_usuario' ");

						//Presenta la lista de usuarios
						echo '<table cellspacing=0 cellpadding=4 width="100%">
									<tr>
										<td valign=middle align=center  bgcolor=darkgray>
											<font size=2 color=black>
												<b>'.$MULTILANG_Usuario.'</b>
											</font>
										</td>
										<td valign=middle align=center  bgcolor=darkgray>
											<font size=2 color=black>
												'.$MULTILANG_Nombre.'
											</font>
										</td>
										<td  bgcolor=darkgray valign=middle align=center>
											<font size=2 color=black>
												'.$MULTILANG_UsrAcceso.'
											</font>
										</td>
										<td bgcolor=darkgray></td>
									</tr>';
						while($usuarios_chat = $resultado->fetch())
							{
								echo '
									<tr>
										<td valign=middle align=left>
											<img src="img/icn_22.gif" border=0 align=absmiddle>
											<font size=2 color=black>
												<b>'.$usuarios_chat["login"].'</b>
											</font>
										</td>
										<td valign=middle align=left>
											<font size=2 color=black>
												'.$usuarios_chat["nombre"].'
											</font>
										</td>
										<td valign=middle align=left>
											<font size=2 color=black>
												'.$usuarios_chat["ultimo_acceso"].'
											</font>
										</td>
										<td valign=middle>
											<input type="Button"  class="BotonesEstado" value=" Chat >>> " onClick="chatWith(\''.$usuarios_chat["login"].'\'); OcultarPopUp(\'BarraFlotanteChat\'); ">
										</td>
									</tr>
								';
							}
						echo '</table>';
					}
			?>

			<?php
			abrir_barra_estado();
				echo '<input type="Button"  class="BotonesEstadoCuidado" value=" <<< '.$MULTILANG_IrEscritorio.' " onClick="OcultarPopUp(\'BarraFlotanteChat\')">';
			cerrar_barra_estado();
			cerrar_ventana();
			?>
		<!-- FIN DE MARCOS POPUP -->
		</div>
