<?php
include("../funciones.php");
$est_sesion=estado_sesion_web();
//$CM_path_base="http://localhost/demos_moxup/locate/site";
$CM_path_base="http://locate.chilemap.cl/app/site/";
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
if(substr(strtolower($data_server[0]),0,strlen($CM_path_base))==$CM_path_base)
{

if($_REQUEST['tip']==1 and $est_sesion==0)
{
	$id_us=$_REQUEST['id'];
	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$qr=" estado=0 and id_cliente=".$_SESSION['id_cliente_web']." ";
	if($_SESSION['tipo_us_web']==2)
	{
		$qr .="and id_usuario like '".$_SESSION['id_usuario_web']."' ";
	}
	if(is_numeric($id_us))
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="id_usuario ilike '".$id_us."' ";
	}
	if(trim($desde)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha_hora >='".$desde." 00:00:00' ";
	}
	if(trim($hasta)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha_hora <='".$hasta." 23:59:59' ";
	}
	$qr .=" order by fecha_hora desc";
$data=getRegistrosFiltro($qr);
?>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>
				<th style="width:5%;"></th>
				<th style="width:5%;">USUARIO</th>
				<th style="width:15%;">NOMBRE</th>
				<TH style="width:15%;">FECHA</TH>
				<th style="width:20%;">EMPRESA</th>
				<th style="width:15%;">CIUDAD</th>
				<!--th>LATITUD</th>
				<th>LONGITUD</th>
				<TH>DISTANCIA</TH>
				<TH>PRECISION</TH-->
				<TH style="width:25%;">MAPA</TH>
				
				
			</tr>
			<?PHP
				$fila=0;
				$filaStyle="gridFila";
				$empresas=getEmpresas();
				foreach($data as $i => $dat)
				{
					//$dat_empresa=getEmpresa($dat[5]);
					$dat_empresa=$empresas[$dat[5]];
					if($fila%2==0)
						$filaStyle="gridFila";
					else
						$filaStyle="gridFilaB";
					$fila++;
					$pres=trim($dat[7]);
					if($pres=="")
					{
						$pres="No registra este dato";
					}else
					{
						$pres .="mts";
					}
					$fecha_actual=trim($dat[2]);
					//$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fecha_actual ) ) ;
					//$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
					$usuario=getUsuarioXId($dat[1]);
					?>
					<!--<tr class="titulo2">--> 
					<tr>
						<td><?=$i+1;?></td>
						<td><?=$dat[1]?></td>
						<td><?=ucwords($usuario[1])?> <?=ucwords($usuario[2])?></td>
						<TD><?=$fecha_actual?></TD>
						<TD><?=ucwords($dat_empresa[0])?></TD>
						<TD><?=ucwords($dat_empresa[4])?></TD>
						<!--td><?=$dat[3]?></td>
						<td><?=$dat[4]?></td>
						<TD><?=$dat[6]?> mts</TD>
						<TD><?=$pres?></TD-->
						<!-- addMarcador('img/place.png','30,30',<?=$dat[3]?>,<?=$dat[4]?>,'','Check-in Usuario');-->
						<TD><input type="button" onclick="detalleRegistro(<?=$dat[0]?>);" value="Detalle"><input type="button" id="opener" value="Ver Mapa" onclick="limpiarMapa();addMarcador('img/place.png','30,30',<?=$dat_empresa[5]?>,<?=$dat_empresa[6]?>,'','<?=ucwords($dat_empresa[0])?><br> <?=ucwords($dat_empresa[1])?> #<?=$dat_empresa[2]?><br><?=ucwords($dat_empresa[4])?>');verMarcadores();OpenModalMapa();"></TD>
					</tr>
					<?php
				}
				?>
		</table>
		
		<?php
}elseif($_REQUEST['tipo']==2)
{
	
	$usuario=getUsuario($_REQUEST['mail']);
	
	if(count($usuario)>0)
	{
		
		$cliente=getCliente($usuario[3]);
		if($_REQUEST['clave']==$usuario[6] and ($usuario[8]==1 or $usuario[8]==2) and $cliente[1]==0)
		{
			inicioSesion_web($_REQUEST['mail'],$usuario[1],$usuario[0],$usuario[3],$usuario[10],$cliente[6],$cliente[8],$usuario[8]);		
			?>
		<script>
			window.location="registros.php";
		</script>
		<?php
		}else
		{
			if( $cliente[1]!=0 and count($usuario)>0)
			{
				echo "El cliente esta dado de baja. Por favor contactese con el proveedor";
			}else
			{
				echo "El usuario no existe. Por favor intentelo nuevamente.";
			}
		}
	}else
	{
		echo "El usuario no existe. Por favor intentelo nuevamente.";
	}
}elseif($_REQUEST['tipo']==3)
{
	cerrar_sesion_web();
	?>
	<script>
		window.location="login.php";
	</script>
	<?php
}elseif($_REQUEST['tipo']==4 and $est_sesion==0)
{
	$id_us=$_REQUEST['id'];
	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$estado=$_REQUEST['est'];
	$qr="";
	if(is_numeric($estado) and $estado !=10)
	{
		
		$qr .="estado = ".$estado." ";
	}else
	{
		$qr .="estado !=2 ";
	}
	$qr .=" and id_cliente=".$_SESSION['id_cliente_web']." ";
	if(is_numeric($id_us))
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		
		$qr .="id_usuario = ".$id_us." ";
	}
	if(trim($desde)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha >='".$desde." 00:00:00' ";
	}
	if(trim($hasta)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha <='".$hasta." 23:59:59' ";
	}
	$qr .=" order by fecha desc";
$data=getAccesos($qr);
?>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>
				<th style="width:5%;"></th>
				<th style="width:5%;">USUARIO</th>
				<th style="width:15%;">NOMBRE</th>
				<TH style="width:15%;">FECHA</TH>
				<th style="width:30%;">ESTADO</th>				
				
				
				
			</tr>
			<?PHP
				$fila=0;
				$filaStyle="gridFila";
				
				foreach($data as $i => $dat)
				{
					//$dat_empresa=getEmpresa($dat[5]);
					if($dat[3]==0)
						$est_text="Exitoso";
					elseif($dat[3]==1)
						$est_text="Error Clave";
					
						
					if($fila%2==0)
						$filaStyle="gridFila";
					else
						$filaStyle="gridFilaB";
					$fila++;
					
					$fecha_actual=trim($dat[2]);
					//$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fecha_actual ) ) ;
					//$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
					$usuario=getUsuarioXId($dat[1]);
					?>
					<!--<tr class="titulo2">--> 
					<tr>
						<td><?=$i+1;?></td>
						<td><?=$dat[1]?></td>
						<td><?=ucwords($usuario[1])?> <?=ucwords($usuario[2])?></td>
						<TD><?=$fecha_actual?></TD>
						<TD><?=$est_text?></TD>
						
						
					</tr>
					<?php
				}
				?>
		</table>
		
		<?php
}elseif($_REQUEST['tipo']==5 and $est_sesion==0) //intentos
{
	$id_us=$_REQUEST['id'];
	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	
	$qr=" estado=1 and id_cliente=".$_SESSION['id_cliente_web']."";
	
	if(is_numeric($id_us))
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		
		$qr .="id_usuario like '".$id_us."' ";
	}
	if(trim($desde)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha_hora >='".$desde." 00:00:00' ";
	}
	if(trim($hasta)!="")
	{
		if($qr!="")
		{
			$qr .="and ";
		}
		$qr .="fecha_hora <='".$hasta." 23:59:59' ";
	}
	
	$qr .=" order by fecha_hora desc";
$data=getIntentosRegistro($qr);
?>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>
				<th style="width:5%;"></th>
				<th style="width:5%;">USUARIO</th>
				<th style="width:15%;">NOMBRE</th>
				<TH style="width:15%;">FECHA</TH>
				<th style="width:30%;">Ver Mapa</th>				
				
				
				
			</tr>
			<?PHP
				$fila=0;
				$filaStyle="gridFila";
				
				foreach($data as $i => $dat)
				{
					//$dat_empresa=getEmpresa($dat[5]);
					
					if($fila%2==0)
						$filaStyle="gridFila";
					else
						$filaStyle="gridFilaB";
					$fila++;
					
					$fecha_actual=trim($dat[2]);
					$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fecha_actual ) ) ;
					$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
					$usuario=getUsuarioXId($dat[1]);
					?>
					<!--<tr class="titulo2">--> 
					<tr>
						<td><?=$i+1;?></td>
						<td><?=$dat[1]?></td>
						<td><?=ucwords($usuario[1])?> <?=ucwords($usuario[2])?></td>
						<TD><?=$fecha_actual?></TD>
						<TD><input type="button" id="opener" value="Ver Mapa" onclick="limpiarMapa();addMarcador('img/place.png','30,30',<?=$dat[3]?>,<?=$dat[4]?>,'','Intento de Registro');verMarcadores();OpenModal();"></TD>
						
						
					</tr>
					<?php
				}
				?>
		</table>
		
		<?php
}elseif($_REQUEST['tipo']==6 and $est_sesion==0) //mapa
{
	$usuarios=getUsuarioXCliente($_SESSION['id_cliente_web']);
	//print_r($usuarios);
	$i=0;
	foreach($usuarios as $us)
	{
		$id_us=$us[0];
		$qr=" id_usuario like '".$id_us."' and id_cliente=".$_SESSION['id_cliente_web']." order by fecha_hora desc limit 1";
		$registro=getRegistrosFiltro($qr);
		//print_r($registro);
		$icono="img/point2.png";
		if($registro[0][8]=="")
		{
			$icono="img/point3.png";
		}
		
		if(count($registro)>0)
		{
			$fecha=$registro[0][2];
			$id_em=$registro[0][5];
			$empresa=getEmpresa($id_em);
			
			$nom_empresa=$empresa[0];
			$lat=$empresa[5];
			$lon=$empresa[6];
			$html="<div class=texto_pop>".ucwords($us[1])." ".ucwords($us[2])."</div>";
			$html .="<div class=texto_pop2>Empresa:".ucwords($nom_empresa)."</div>";
			$html .="<div class=texto_pop2>Fecha: ".$fecha."</div>";
			
			if(trim($lat)!="")
			{
			?>
			<script>
				addMarcador('<?=$icono?>','30,30','<?=$lat?>','<?=$lon?>','','<?=$html?>');				
			</script>
			<?php
			}
			$i++;
		}
	}
	if($i>0)
	{
		?>
			<script>
			verMarcadores();
			</script>
			<?php
	}
}elseif($_REQUEST['tipo']==7 and $est_sesion==0) //detalle registro
{
	//echo "registro".$_REQUEST['registro'];
	$detalle=getRegistrosFiltro(" id_registro=".$_REQUEST['registro']."");
	$documento=getArchivosReg($_REQUEST['registro']);
?>
<div id=cont_grilla>
<table id=table_filtro>
	<tr><td width=30%><strong>Check-In</strong></strong></td><td width=70%><?=$detalle[0][2]?></td></tr>
	<tr><td><strong>Check-Out</strong></td><td><?=$detalle[0][8]?></td></tr>
	<?php
	if($_SESSION['app_web']==4)
	{
	?>
	<tr><td><strong>Detalle</strong></td><td><?=$detalle[0][9]?></td></tr>
	<?php
	}
	?>
	<?php
	foreach($documento as $doc)
	{
	?>
		<tr><td><strong>Documento :</strong></td><td><a href="<?=$doc[4]?><?=$doc[3]?>" target=_BLANK><img width=50% src="<?=$doc[4]?><?=$doc[3]?>"></a></td></tr>
	<?php
	if($_SESSION['app_web']==5)
	{
		?>
		<tr><td><strong>Comentario : </strong></td><td><?=$doc[5]?></td></tr>
		<?php
	}
	}
	?>
</table>
<?php
	if($_SESSION['app_web']==5)
	{
		$prod_detalle=getProductosRegistro($_REQUEST['registro']);
		$html2="<br><h5>Inventario</h5>";
		if(count($prod_detalle)>0)
		{
			$html2 .="<table><tr align=center class=txt_header><td align=left width=30%>Producto</td><td width=20%>Inicio</td><td width=20%>Fin</td><td width=20% >valor</td></tr>";
			foreach($prod_detalle as $prod_d)
			{
				$producto=getProducto($prod_d[1]);
				$html2 .="<tr ><td align=left width=30%><h5>".ucwords($producto[2])."</h5></td><td align=center width=20%><h5>".$prod_d[4]."</h5></td><td align=center width=20%><h5>".$prod_d[5]."</h5></td><td align=center width=20%><h5>".$prod_d[6]."</h5></td></tr>";
			}
			$html2 .="</table>";
		}
			$html2 .="<br>Comentario: ".$prod_d[8]."";
	}
	?>
	<?=$html2?>
</div>
	<?php
}elseif($_REQUEST['tip']==8 and $est_sesion==0)
{
	$id_us=$_REQUEST['id'];
	$desde=$_REQUEST['desde'];
	$hasta=$_REQUEST['hasta'];
	$estado=$_REQUEST['estado'];
	$visita=$_REQUEST['visita'];
		if($estado!=10)
	{
		$qr=" and estado=".$estado." ";
	}else
	{
		$qr="";
	}
	
	if(trim($visita)!="")
	{
		$empr=getEmpresasXquery(" and (nombre ilike '%".$visita."%' or otro ilike '%".$visita."%') and id_cliente=".$_SESSION['id_cliente_web']."");
		//print_r($empr);
		if(count($empr)>0)
		{
			foreach($empr as $e)
			{
				$id_emp[]=$e[8];
			}
			$qr  .=" and id_empresa in(".implode($id_emp,',').")";
		}else
		{
			$qr .=" and 1=2";
		}
			
		
	}
		if(trim($id_us)!="")
	{
		$empr=getUsuarioXQuery(" and (nombre ilike '%".$id_us."%' or apellido ilike '%".$id_us."%') and id_cliente=".$_SESSION['id_cliente_web']."");
		//print_r($empr);
		if(count($empr)>0)
		{
			foreach($empr as $e)
			{
				$id_emp[]=$e[0];
			}
			$qr  .=" and id_usuario in(".implode($id_emp,',').")";
		}else
		{
			$qr .=" and 1=2";
		}
			
		
	}
	if(is_numeric($id_us))
	{

		$qr .=" and id_usuario = '".$id_us."' ";
	}
	if(trim($desde)!="")
	{

		$qr .=" and fecha_agenda >='".$desde." 00:00:00' ";
	}
	if(trim($hasta)!="")
	{
		
		$qr .=" and fecha_agenda <='".$hasta." 23:59:59' ";
	}

	$qr .=" and id_cliente=".$_SESSION['id_cliente_web']."";
	if($_SESSION['tipo_us_web']==2)
	{
		$qr .=" and id_usuario=".$_SESSION["id_usuario_web"]." ";
	}
	$qr .=" order by fecha_agenda";
  $data=getAgendas($qr);
  
?>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>
				<th style="width:5%;"></th>
				
				<th style="width:25%;">USUARIO</th>
				<TH style="width:10%;">FECHA</TH>
				<th style="width:25%;">VISITA</th>
				<th style="width:40%;">DETALLE</th>				
								
				<TH style="width:5%;">PANEL</TH>
				
				
			</tr>
			<?php
			foreach($data as $dat)
			{
				$usuario=getUsuarioXId($dat[1]);
				$empresa=getEmpresa($dat[4]);
				$color="";
				if($dat[6]==2)
					$color="color_tabla_visita";
				if($dat[6]==1)	
					$color="color_tabla_inactivo";
				?>
				<tr id="<?=$color?>">
				<td style="width:5%;"><?=$dat[0]?></td>				
				<td style="width:15%;"><?=ucwords($usuario[1])?> <?=ucwords($usuario[2])?></td>
				<Td style="width:15%;"><?=$dat[3]?></Td>
				<td style="width:20%;"><?=$empresa[0]?></td>
				<td style="width:15%;"><?=$dat[7]?></td>				
				
				<Td style="width:25%;"><a href="javascript:loadDetagenda(<?=$dat[0]?>);"><img src="img/find.png"></a></Td>
				
				
			</tr>
				<?php
			}
			?>
		</table>
		
		<?php
}elseif($_REQUEST['tipo']==9 and $est_sesion==0)
{
	$usuarios=getUsuarioXCliente($_SESSION['id_cliente_web']);
	$visitas=getEmpresasXCliente($_SESSION['id_cliente_web']);
	$motivos=getCategoriaUsuario($_SESSION['id_cliente_web']);
	$comunas=getComunas();
	//print_r($comunas);
?>
	<script>
				 $(function() {
    		$( "#fec_agenda" ).datepicker({dateFormat:"yy-mm-dd"});
    		$('#hora').timepicker({ 'timeFormat': 'H:i:s' });
  			});	
  			
	</script>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>			
				<td>Usuario</td>
				<td>
					<select id="us_agenda" name="us_agenda">
						<?php
						if($_SESSION['tipo_us_web']==1)
						{
							foreach($usuarios as $us)
							{
								?>
								<option value='<?=$us[0]?>'><?=$us[0]?>-<?=ucwords($us[1])?> <?=ucwords($us[2])?></option>
								<?php
							}
						}else
						{
							?>
							<option value='<?=$_SESSION["id_usuario_web"]?>'><?=$_SESSION['nombre_web']?></option>
							<?php
						}
						?>
					</select>
				</td>
			</tr>
			<tr>			
				<td>Fecha agenda</td>
				<td>
					<input type="text" class="input_filtro" class="date start" autocomplete=off id="fec_agenda" name="fec_agenda" maxlength="50" class="txtFecha"/> | 
					Hora <input type="text" class="input_filtro" autocomplete=off id="hora" name="hora" maxlength="5" class="txtFecha"/>
					</td>
			</tr>
			<?php
			if(strtolower($_SESSION['pais_web'])=="chl" or trim($_SESSION['pais_web'])=="")
			{
			?>
				<tr>			
					<td>Comuna</td>
					<td>
						<select id="com_agenda" name="com_agenda" onchange="loadVisitas();">
							<option value='' selected>Todas</option>
							<?php
							foreach($comunas as $com)
							{
								?>
								
								<option value='<?=$com[0]?>'><?=ucwords(strtolower($com[0]))?></option>
								<?php
							}
							?>
						
						</select><input type="button" value="Filtrar" onclick='loadVisitas();'>
					</td>
				</tr>
			<?php
			}
			?>
			<tr>			
				<td>Busqueda</td>
				<td><input type="text" id='bus_agenda' name='bus_agenda'><input type="radio" name="group2" id="opc_agenda" checked=true>Nombre <input type="radio" name="group2" id="opc_agenda2" value="">Otro <input type="button" value="Filtar" onclick="loadVisitas();"></td>
			</tr>
			<tr>			
				<td>Visita</td>
				<td>
					<div id="visita_cont">
					<select id="visita_agenda" name="visita_agenda">
						<?php
						foreach($visitas as $vis)
						{
							?>
							<option value='<?=$vis[8]?>'><?=ucwords($vis[0])?></option>
							<?php
						}
						?>
					</select>
				</div>
				</td>
			</tr>
				<?php
					if(count($motivos)>0)
					{
						?>
			<tr>			
				<td>Motivo</td>
				<td>
				   
						<select id="motivo" name="motivo">
							<?php
							foreach($motivos as $mot)
							{
								?>
								<option value='<?=$mot[0]?>'><?=ucwords($mot[1])?></option>
								<?php
							}
							?>
						</select>
						
				</td>
			</tr>
			<?php
					}
					?>
			<tr>			
				<td>Detalle</td>
				<td><textarea name='detalle' id='detalle' rows="5" cols="40">

</textarea></td>
			
			</tr>
			<tr>
				<td></td><td><input type="button" value="Agendar y Salir" onclick="validaAgenda(1);"><input type="button" value="Agendar +" onclick="validaAgenda(2);"></td>
			</tr>
		</table>
		<div id='msg_agenda' class="msg_error"></div>
<?php		
}elseif($_REQUEST['tipo']==10 and $est_sesion==0)
{
	$data=array();
	$data[]=$_REQUEST['us'];
	$data[]=$_REQUEST['fec'];
	$data[]=$_REQUEST['visita'];
	$data[]=$_REQUEST['motivo'];
	$data[]=0;
	$data[]=$_REQUEST['det'];
	$data[]=$_SESSION['id_cliente_web'];
	if(trim($_REQUEST['hora'])!="")
	{
		$data[]=$_REQUEST['hora'];
	}else
	{
		$data[]="00:00";
	}
	addAgenda($data);
	?>
	<script>
		CloseModal();
		</script>
	<?php
}elseif($_REQUEST['tipo']==11 and $est_sesion==0)
{
	$agenda=getAgendas(" and id_agenda=".$_REQUEST['agenda']."");
	//print_r($agenda);
	$usuario=getUsuarioXId($agenda[0][1]);
	//print_r($usuario);
	$visitas=getEmpresasXCliente($_SESSION['id_cliente_web']);
	$motivos=getCategoriaUsuario($_SESSION['id_cliente_web']);
	$estado=$agenda[0][6];
	if($estado==0)
	  $check1="selected";
	  if($estado==1)
	  $check2="selected";
	  if($estado==2)
	  $check3="selected";
	//print_r($visitas);
?>
	<script>
				 $(function() {
    		$( "#fec_agenda" ).datepicker({dateFormat:"yy-mm-dd"});
    		$('#hora').timepicker({ 'timeFormat': 'H:i:s' });
  			});	
	</script>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>			
				<td>Usuario</td>
				<td>
					<input type="text" readonly=true id=us_agenda name=us_agenda value="<?=ucwords($usuario[1])?> <?=ucwords($usuario[2])?>">
				</td>
			</tr>
			<tr>			
				<td>Fecha agenda</td>
				<td><input type="text" class="input_filtro" autocomplete=off id="fec_agenda" name="fec_agenda" maxlength="50" value="<?=$agenda[0][3]?>" class="txtFecha"/>
					 | 
					Hora <input type="text" class="input_filtro" autocomplete=off id="hora" name="hora" maxlength="5" class="txtFecha" value="<?=$agenda[0][8]?>" />
					
					</td>
			</tr>
			<tr>			
				<td>Visita</td>
				<td>
					<select id="visita_agenda" name="visita_agenda">
						<?php
						foreach($visitas as $vis)
						{
							if($agenda[0][4]== $vis[8])
							{
							?>
								<option value='<?=$vis[8]?>' selected><?=ucwords($vis[0])?></option>
							<?php
							}
							else
							{
							?>
								<option value='<?=$vis[8]?>'><?=ucwords($vis[0])?></option>
							<?php
							}
						}
						?>
					</select>
				</td>
			</tr>
			<?php
			if(count($motivos)>0)
					{
						?>
		
			<tr>			
				<td>Motivo</td>
				<td>
					<select id="motivo" name="motivo">
						<?php
						foreach($motivos as $mot)
						{
							if($agenda[0][5]== $mot[0])
							{
							?>
								<option value='<?=$mot[0]?>' selected><?=ucwords($mot[1])?></option>
							<?php
							}
							else
							{
							?>
								<option value='<?=$mot[0]?>'><?=ucwords($mot[1])?></option>
							<?php
							}
						}
						?>
					</select>
				</td>
			</tr>
						<?php
					}			
					?>
			<tr>			
				<td>Estado</td>
				<td>
					<select id="est_agenda" name="est_agenda">
						<option value=0 <?=$check1?>>Activo</option>
						<option value=1 <?=$check2?>>Inactivo</option>
						<option value=2 <?=$check3?>>Visitado</option>
					</select>
				</td>
			</tr>
			<tr>			
				<td>Detalle</td>
				<td><textarea name='detalle' id='detalle' rows="5" cols="40" >
<?=$agenda[0][7]?>
</textarea></td>
			
			</tr>
			<tr>
				<td></td><td><input type="button" value="Guardar" onclick="validaAgendaUP(<?=$_REQUEST['agenda']?>);"></td>
			</tr>
		</table>
		<div id='msg_agenda' class="msg_error"></div>
<?php		
}elseif($_REQUEST['tipo']==12 and $est_sesion==0)
{
 $hora=$_REQUEST['hora'];
 if(trim($hora)=="")
 {
 	$hora="00:00:00";
}
	upAgenda("hora='".$hora."', estado=".$_REQUEST['estado'].", fecha_agenda='".$_REQUEST['fec']."', id_empresa=".$_REQUEST['visita'].", id_categoria_usuario=".$_REQUEST['motivo'].", descripcion='".$_REQUEST['det']."'",$_REQUEST['agenda']);
	?>
	<script>
		CloseModal();
		</script>
	<?php
}elseif($_REQUEST['tipo']==13 and $est_sesion==0) //load visitas por comuna
{
	$qr=" and id_cliente=".$_SESSION['id_cliente_web']." and comuna ilike '%".$_REQUEST['comuna']."%'";
	if(trim($_REQUEST['query'])!="")
	{
		if(trim($_REQUEST['busqueda'])==1)
			$qr .=" and nombre ilike '%".$_REQUEST['query']."%'";
		if(trim($_REQUEST['busqueda'])==2)
			$qr .=" and otro ilike '%".$_REQUEST['query']."%'";	
	}
	$visitas=getEmpresasXquery($qr);
	?>
	<select id="visita_agenda" name="visita_agenda">
						<?php
						foreach($visitas as $vis)
						{
							?>
							<option value='<?=$vis[8]?>'><?=ucwords($vis[0])?></option>
							<?php
						}
						?>
					</select>
	<?php
}elseif($_REQUEST['tip']==14 and $est_sesion==0)
{
	$nombre=trim($_REQUEST['nom']);
	$estado=$_REQUEST['estado'];
	
	if($estado!=10)
	{
		$qr=" and estado=".$estado." ";
	}else
	{
		$qr=" and estado!=2";
	}
	if(trim($nombre)!="")
	{
		$qr .=" and nombre ilike '%".$nombre."%'";
	}
	$qr .=" and id_cliente=".$_SESSION['id_cliente_web']."";
	$qr .=" order by nombre";
  $data=getProductosQR($qr);
  
  
  
?>
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>
				<th style="width:5%;"></th>				
				<th style="width:50%;">NOMBRE</th>
				<TH style="width:10%;">FECHA</TH>
				<th style="width:10%;">ESTADO</th>				
				<TH style="width:25%;">PANEL</TH>
				
				
			</tr>
			<?php
			foreach($data as $dat)
			{
				$est="Activo";
				$img="down.png";
				$txt_img="Desactivar Item";
				$onclick="javascript:updateItem(".$dat[0].",1);";
				if($dat[4]==1)
				{
				  $est="Inactivo";
				  $img="up.png";
				  $txt_img="Activar Item";
				  $onclick="javascript:updateItem(".$dat[0].",0);";
				}
				?>
				<tr>
				<td style="width:5%;"><?=$dat[0]?></td>				
				<td style="width:15%;"><?=ucwords($dat[2])?></td>
				<Td style="width:15%;"><?=$dat[3]?></Td>
				<td style="width:5%;"><?=$est?></td>
				<Td style="width:40%;">
					<img src="img/find.png" onclick="vistaPrevProd(<?=$dat[0]?>);" class=mano title="Ver/Editar Item">
					<?php
					if($_SESSION['tipo_us_web']==1)
					{
					?>
					<a href="<?=$onclick?>"><img src="img/<?=$img?>" title=<?=$txt_img?>></a>
					<img src="img/cancel.png" height=24 width=24 onclick='javascript:updateItem(<?=$dat[0]?>,2);' class=mano></Td>
				  <?php
					}
				  ?>
				
			</tr>
				<?php
			}
			?>
		</table>
		
		<?php
}elseif($_REQUEST['tip']==15 and $est_sesion==0) //bajar/subir Item
{
	$item=$_REQUEST['id'];
	$est=$_REQUEST['estado'];
	updateProductoQR("estado=".$est."",$item);	
}elseif($_REQUEST['tipo']==16 and $est_sesion==0)
{
	$id_cliente=$_SESSION['id_cliente_web'];
	
?>
		<form enctype="multipart/form-data" class="formulario">
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>			
				<td>Nombre</td>
				<td><input type="text" id="nom_input" name="nom_input"></td>
			</tr>		
			<tr>			
				<td>Valor</td>
				<td><input type="text" id="valor_input" name="valor_input"></td>
			</tr>		
			<tr>			
				<td>Imagen</td>
				<td><input type="file" id="i_file" name="i_file"></td>
			</tr>		
			<tr>
				<td></td><td><input type="button" id="but_check" name="but_check"  value="Guardar" onclick="saveItem();"></td>
			</tr>
		</table>
	</form>
		<div id='msg_agenda' class="msg_error"></div>
<?php		
}elseif($_REQUEST['tipo']==17 and $est_sesion==0) // nuevo producto item
{
	$nombre=$_REQUEST['nom'];
	$valor=$_REQUEST['valor'];
	if(!is_numeric($valor))
	{
		$valor=0;
	}
	$id_cliente=$_SESSION['id_cliente_web'];
	$data=array();
	$data[]=$id_cliente;
	$data[]=trim($nombre);
	$data[]=trim($valor);
	addProducto($data);
}elseif($_REQUEST['tipo']==18 and $est_sesion==0) //edit producto item
{
	$producto=getProductosQR(" and id_producto=".$_REQUEST['id_pro']."");
	$readonly="";
	if($_SESSION['tipo_us_web']!=1)
	{
		$readonly="readonly";
	}
	?>
	<form enctype="multipart/form-data" class="formulario">
		<table border=1 id="table_resul" class="bordered">
		<!--	<tr class="titulo">-->
			<tr>			
				<td>Nombre</td>
				<td><input type="text" id="nom_input" name="nom_input" value="<?=$producto[0][2]?>" <?=$readonly?>></td>
			</tr>		
			<tr>			
				<td>Valor</td>
				<td><input type="text" id="valor_input" name="valor_input" value="<?=$producto[0][5]?>" <?=$readonly?>></td>
			</tr>		
			<?php
			$name_file="";
			if(trim($producto[0][7])!="")
			{
				$name_file=$producto[0][7];
			?>
			<tr>			
				<td>Imagen</td>
				<td><img src="<?=$CM_path_base?>/<?=$producto[0][6]?>/<?=$producto[0][7]?>" width=300 height=200></td>
			</tr>	
			<?php
		}
		if($_SESSION['tipo_us_web']==1)
		{
			?>
			<tr>			
				<td>Nueva Imagen</td>
				<td><input type="file" id="i_file" name="i_file" ></td>
			</tr>		
			<tr>
				<td></td><td><input type="button" id="but_check" name="but_check"  value="Guardar" onclick="upItem('<?=$name_file?>',<?=$_REQUEST['id_pro']?>);"></td>
			</tr>
			<?php
			
		}
			?>
			
		</table>
	</form>
		<div id='msg_agenda' class="msg_error"></div>
	<?php
}elseif($_REQUEST['tipo']==19 and $est_sesion==0) //update producto item
{	
	updateProductoQR(" nombre='".$_REQUEST['nom']."', valor=".$_REQUEST['valor']."",$_REQUEST['id_producto']);
}
}
?>