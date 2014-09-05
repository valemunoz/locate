<?php include("../funciones.php"); $est_sesion=estado_sesion_web(); 
$data_server= explode("?",$_SERVER['HTTP_REFERER']); 
$CM_path_base="http://locate.chilemap.cl/app/site"; 

//$CM_path_base="http://localhost/demos_moxup/locate";

if(substr(strtolower($data_server[0]),0,strlen($CM_path_base))==$CM_path_base)
{

if($_REQUEST['tipo']==1 and $est_sesion==0) //lista de empresas
	{
		$cliente=$_SESSION['id_cliente_web'];
		$estado=$_REQUEST['estado'];
		$nombre=$_REQUEST['nombre'];
		
		if(trim($cliente)!="")
		{
			$query .=" and id_cliente=".$cliente.""	;
		}
		if(trim($estado)!="" and $estado!=10)
		{
			$query .=" and estado=".$estado.""	;
		}
		if(trim($nombre)!="")
		{
			$query .=" and nombre ilike '%".$nombre."%'"	;
		}	
		$query .=" order by nombre";
		
		$empresas=getEmpresaQR($query);
		//print_r($usuarios);
		?>
		<table border=1 id="table_resul" class="bordered">
			<!--	<tr class="titulo">-->
				<tr>
					<th style="width:5%;">ID</th>				
					<th style="width:15%;">NOMBRE</th>				
					<th style="width:20%;">DIRECCION</th>
					<th style="width:15%;">LATITUD</th>				
					<TH style="width:15%;">LONGITUD</TH>
								
					<TH style="width:15%;">PANEL</TH>
				</tr>
			<?php
			foreach($empresas as $i=> $us)
			{
			
				?>
				<tr>
					<td style="width:5%;"><?=$us[0]?></td>				
					<td style="width:15%;"><?=ucwords($us[1])?></td>				
					<td style="width:20%;"><?=ucwords($us[2])?> #<?=ucwords($us[3])?>, <?=ucwords($us[4])?></td>
					<td style="width:15%;"><?=$us[6]?></td>				
					<Td style="width:15%;"><?=$us[7]?></Td>				
					<Td style="width:15%;">
						
						<a href="javascript:loadEmpresa(<?=$us[0]?>);">Editar</a>	
						|<a href="javascript:limpiarMapa();verMapa(<?=$us[6]?>,<?=$us[7]?>);">Mapa</a>	
						<?php
						if($us[10]==0)
						{
							?>
							| <a href="javascript:upEstadoEmpresa(1,<?=$us[0]?>);">Bajar</a>	
							<?php
						}else
						{
							?>
							| <a href="javascript:upEstadoEmpresa(0,<?=$us[0]?>);">Subir</a>	
							<?php
							
						}
						?>
						</Td>
				</tr>
				<?php
			}
			?>
			</table>
		<?php
	}elseif($_REQUEST['tipo']==2 and $est_sesion==0)//nuevo Empresa
	{		
		
		
		$empresas=getEmpresasXquery(" and id_cliente=".$_SESSION['id_cliente_web']."");
		//echo "cont:".count($empresas);
		//echo "ses:".$_SESSION['mx_lugares'];
		if(count($empresas) < $_SESSION['mx_lugares'] or $_SESSION['mx_lugares']==0 or $_SESSION['mx_lugares']=="")
		{
			
			if(strtolower($_SESSION['pais_web'])=="per")
			{
				$pais="peru";
			
			}else
			{
				$pais="chile";
				$departamentos=getRegiones();
				if(count($departamentos)>0)
				{
					$comuna=getComunaxRegion($departamentos[0][1]);
				}
			}
			?>
			<table border=1 id="table_resul" class="bordered">
				
				<tr><td>Nombre</td><td><input id="nombre_em" name="nombre_em" type="text" value=""></td></tr>		
				
				<tr><td>Calle</td><td><input type="text" id="calle_em" name="calle_em" value=""></td></tr>
				<tr><td>Numero</td><td><input type="text" id="num_em" name="num_em" value=""></td></tr>
				
				<tr><td>Departamento/Ciudad</td><td>
					<?php
					if(count($departamentos)>0)
					{
						?>
						<select id="reg_em" onchange="loadComunas();">
						<?php
						foreach($departamentos as $dep)
						{
							?>
							<option value="<?=strtolower($dep[1])?>"><?=ucwords(strtolower($dep[0]))?></option>
							<?php
						}
						?>
						</select>
						<?php
					}else
					{
						?>
						<input type="text" id=reg_em name=reg_em>
						<?php
					}
					?>
					
					</td></tr>
				<tr><td>Comuna/Distrito</td><td>
					<div id=com_text>
					<?php
					if(count($comuna)>0)
					{
						if(count($comuna)>1)
						{
							?>
						<select id="com_em" >
						<?php
						foreach($comuna as $com)
						{
							?>
							<option value="<?=strtolower($com[0])?>"><?=ucwords(strtolower($com[0]))?></option>
							<?php
						}
						?>
						</select>
						<?php
						}else
						{
								?>
					
								<input type="text" id="com_em" name="com_em" value="<?=$comuna[0][0]?>" readonly=true>
								<?php
						}
					}else
					{
						?>
						<input type="text" id="com_em" name="com_em" value="">
						<?php
					}
					?>
				</div>
				</td></tr>		
				<tr><td>Latitud</td><td><input type="text" id="lat_em" name="lat_em" value=""></td></tr>
				<tr><td>Longitud</td><td><input type="text" id="lon_em" name="lon_em" value=""></td></tr>
				<tr><td></td><td><input type="button" onclick="BuscarGeo();" value="GEO"><input type="button" onclick="limpiarMapa();verMapa(document.getElementById('lat_em').value,document.getElementById('lon_em').value);" value="Ver Mapa"><input type="button" onclick="saveEmpresa();" value="Registrar"></td></tr>
			</table>
			<div id="msg_error_add" class="msg_error"></div>
				<?php
	 }else
	 {
	 	echo "Ha cumplido con la cuota de Lugares de su plan que es de ".$_SESSION['doom_mx_lug']." lugares.<br> Para modificarlo solicite un upgrade de su plan en <a href='http://localhost/doomla/demos/check/site/cuenta.php'>Renovar</a>";
	 }
	}elseif($_REQUEST['tipo']==3 and $est_sesion==0)//get comuna segun region
	{
		$comuna=getComunaxRegion($_REQUEST['region']);
		if(count($comuna)>1)
		{
		?>
			
						<select id="com_em" >
						<?php
						foreach($comuna as $com)
						{
							?>
							<option value="<?=strtolower($com[0])?>"><?=ucwords(strtolower($com[0]))?></option>
							<?php
						}
						?>
						</select>
						
		<?php
	}elseif(count($comuna)==1)
		{
		?>
			<input type="text" id="com_em" name="com_em" value="<?=$comuna[0][0]?>" readonly=true>
					
						
		<?php
	}
	}elseif($_REQUEST['tipo']==4 and $est_sesion==0)//busca GEO
	{
		
		$direccion_completa=trim("".$_REQUEST['calle']." ".$_REQUEST['numero']." ".$_REQUEST['comuna']."");
		$direcciones=getDireccionExacta($direccion_completa,1);
		
	
		
		
		if(count($direcciones)>0)
		{
			?>
			<script>
				
				document.getElementById("lat_em").value="<?=$direcciones[0][6]?>";				
				document.getElementById("lon_em").value="<?=$direcciones[0][7]?>";
				</script>
			<?php
			
			echo "OK exacta";
		}else //busca en OSM
		{
			$direc=buscarDireccionOSM($direccion_completa." ".$_SESSION['pais_web']);
			//print_r($direc);
			
			if(count($direc)>0 and trim($direc[0][1])!="")
			{
				//calle,numero_municipal,latitud,longitud,comuna,id_comuna,region,id_region,query_completa,geom,origen
				$data[]="Chile";
				$data[]=$direc[0][3];
				$data[]=$direc[0][2];
				$data[]=$direc[0][7];
				$data[]=$direc[0][8];
				$data[]=$direc[0][5];
				$data[]=0;
				$data[]=$direc[0][4];
				$data[]=0;
				$data[]="".$direc[0][3]." ".$direc[0][2]." ".$direc[0][4]."";
				//addDireccion($data,2);
				?>
			<script>
				
				document.getElementById("lat_em").value="<?=$direc[0][1]?>";
				
				document.getElementById("lon_em").value="<?=$direc[0][0]?>";
				</script>
			<?php
			echo "OK osm ".$_SESSION['pais_web'];
			}else //GOOOGLE
			{
				
				$direc=getDireccionGoogle($direccion_completa." ".$_SESSION['pais_web']);
				//print_r($direc);
				if(count($direc)>0 and trim($direc[0][7])!="")
				{
					//calle,numero_municipal,latitud,longitud,comuna,id_comuna,region,id_region,query_completa,geom,origen
					$data=array();
				$data[]="Chile";
				$data[]=$direc[0][3];
				$data[]=$direc[0][2];
				$data[]=$direc[0][7];
				$data[]=$direc[0][8];
				$data[]=$direc[0][5];
				$data[]=0;
				$data[]=$direc[0][4];
				$data[]=0;
				$data[]="".$direc[0][3]." ".$direc[0][2]." ".$direc[0][4]."";
				//addDireccion($data,2);
					
					?>
				<script>
					
					document.getElementById("lat_em").value="<?=trim($direc[0][7])?>";
					
					document.getElementById("lon_em").value="<?=trim($direc[0][8])?>";
					</script>
					
				<?php
				echo "OK google".$_SESSION['pais_web'];
			}else
			{
				echo "No se encuentran coordenadas";
			}
			}
		}
	}elseif($_REQUEST['tipo']==5 and $est_sesion==0)//almacena empresa
	{
		
		$data=array();
		$data[]=$_REQUEST['nombre'];
		$data[]=$_REQUEST['calle'];
		$data[]=$_REQUEST['numero'];
		$data[]=$_REQUEST['comuna'];
		$reg=getRegionesQR(" and id_region =".$_REQUEST['region']."");
		$data[]=$reg[0][0];
		$data[]=$_REQUEST['latitud'];
		$data[]=$_REQUEST['longitud'];
		$data[]=$_SESSION['id_cliente_web'];
		$data[]=$_SESSION['pais_web'];
		addEmpresa($data);
	}elseif($_REQUEST['tipo']==6 and $est_sesion==0)//Editar Empresa
	{		
		$id=$_REQUEST['empresa'];
		$empresa=getEmpresaQR(" and id_empresa=".$id."");
		$clientes=getCliente(0);
		?>
		<table border=1 id="table_resul" class="bordered">
			
			<tr><td>Nombre</td><td><input id="nombre_em" name="nombre_em" type="text" value="<?=$empresa[0][1]?>"></td></tr>		

			<tr><td>Calle</td><td><input type="text" id="calle_em" name="calle_em" value="<?=$empresa[0][2]?>"></td></tr>
			<tr><td>Numero</td><td><input type="text" id="num_em" name="num_em" value="<?=$empresa[0][3]?>"></td></tr>
			<tr><td>Region</td><td><input type="text" id="reg_em" name="reg_em" value="<?=$empresa[0][5]?>"></td></tr>
			<tr><td>Comuna</td><td><input type="text" id="com_em" name="com_em" value="<?=$empresa[0][4]?>"></td></tr>		
			<tr><td>Latitud</td><td><input type="text" id="lat_em" name="lat_em" value="<?=$empresa[0][6]?>"></td></tr>
			<tr><td>Longitud</td><td><input type="text" id="lon_em" name="lon_em" value="<?=$empresa[0][7]?>"></td></tr>
			<tr><td></td><td><input type="button" onclick="BuscarGeo();" value="GEO"><input type="button" onclick="limpiarMapa();verMapa2();" value="Ver Mapa"><input type="button" onclick="updateEmpresa(<?=$id?>);" value="Guardar"></td></tr>
		</table>
		<div id="msg_error_add" class="msg_error"></div>
			<?php
	}elseif($_REQUEST['tipo']==7 and $est_sesion==0)//update Empresa
	{
		
		$data[]=$_REQUEST['nombre'];
		$data[]=$_REQUEST['calle'];
		$data[]=$_REQUEST['numero'];
		$data[]=$_REQUEST['comuna'];
		$data[]=$_REQUEST['region'];
		$data[]=$_REQUEST['latitud'];
		$data[]=$_REQUEST['longitud'];
		
		
		updateEmpresa("nombre='".$_REQUEST['nombre']."', calle='".$_REQUEST['calle']."', numero_municipal='".$_REQUEST['numero']."', comuna='".$_REQUEST['comuna']."',  region='".$_REQUEST['region']."',  latitud='".$_REQUEST['latitud']."',  longitud='".$_REQUEST['longitud']."', geom=ST_GeomFromText('POINT(".$_REQUEST['longitud']." ".$_REQUEST['latitud'].")',2276)",$_REQUEST['empresa']);
	}elseif($_REQUEST['tipo']==8 and $est_sesion==0)//update estado  Cliente
	{		
		
		
		updateEmpresa("estado=".$_REQUEST['estado']." ",$_REQUEST['empresa']);
	}
}
		?>