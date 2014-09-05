<?php
include("funciones.php");
//$CM_path_base="http://localhost/demos_moxup/locate";
$CM_path_base="http://locate.chilemap.cl/app";
$CM_radio=400;
$CM_accu_max=100;
$CM_PATH_IMG=$CM_path_base."/files/";
$est_sesion=estado_sesion();
$data_server= explode("?",$_SERVER['HTTP_REFERER']);
if(substr(strtolower($data_server[0]),0,strlen($CM_path_base))==$CM_path_base)
{

if($_REQUEST['tipo']==1 and $est_sesion==0)
{
	$dat=getDireccionGoogleLATLON($_REQUEST['lat'],$_REQUEST['lon']);
	//$archivo = file_get_contents("http://93.92.170.65/ws_ovbii/funciones.php?tipo=1&id_usuario=1&lat=-33.444298&lon=-70.578171&radio=5000000"); 
	//$xml=new SimpleXMLElement($archivo);
	//echo "http://93.92.170.65/ws_ovbii/funciones.php?tipo=1&id_usuario=".$_SESSION['id_usuario']."&lat=".$_REQUEST['lat']."&lon=".$_REQUEST['lon']."&radio=1000000&precision=".$_REQUEST['accu']."&id_cliente=".$_SESSION['id_cliente']."";
	if($_REQUEST['accu'] >= $CM_accu_max)
	{
		$CM_radio=$CM_radio+$_REQUEST['accu'];
	}
	
	$datos_empresa=getEmpresaRadio($_REQUEST['lat'],$_REQUEST['lon'],$CM_radio,$_SESSION['id_cliente']);
	$empresa=$datos_empresa[0][1];
	$distancia=$datos_empresa[0][8];
	
	/*
	$xml = simplexml_load_file("http://93.92.170.65/ws_ovbii/funciones.php?tipo=1&id_usuario=".$_SESSION['id_usuario']."&lat=".$_REQUEST['lat']."&lon=".$_REQUEST['lon']."&radio=".$CM_accu_max."&precision=".$_REQUEST['accu']."&id_cliente=".$_REQUEST['id_cliente']."") or die("url not loading"); 
	foreach($xml->DATA as $len)    
	{
		//print_r($len);
		//echo $len->EMPRESA;
		$empresa=ucwords($len->EMPRESA);
		$distancia=$len->DISTANCIA_METROS;
	}
	*/
	if(trim($empresa)!="")
	{
			$data=array();
			$data[]=$_SESSION['id_usuario'];
			$data[]=$datos_empresa[0][6];
			$data[]=$datos_empresa[0][7];
			$data[]=$datos_empresa[0][0];
			$data[]=$datos_empresa[0][8];
			$data[]=$_REQUEST['accu'];
			$data[]=0;
			$data[]=$_SESSION['id_cliente'];
			addRegistroObvii($data);
	?>
		<script>
			addMarcadores(<?=$datos_empresa[0][7]?>,<?=$datos_empresa[0][6]?>,'Ultimo registro <?=$empresa?>','iconos/point.png',30,30);
  		moverCentro(<?=$datos_empresa[0][6]?>,<?=$datos_empresa[0][7]?>,17);  	
  		//Precis&iacute;on GPS: <?=$_REQUEST['accu']?>mts|
  		var txt ="Cerca de: <?=$dat[0][3]?> #<?=$dat[0][2]?>,<?=$dat[0][4]?>";
			$("#text_sup").html(txt);
  		mensaje("Haz sido registrado correctamente en: <?=ucwords($empresa)?>");
		</script>
	<?php
	}else
	{
		$data=array();
			$data[]=$_SESSION['id_usuario'];
			$data[]=$_REQUEST['lat'];
			$data[]=$_REQUEST['lon'];
			$data[]=0;
			$data[]=0;
			$data[]=$_REQUEST['accu'];
			$data[]=1;
			$data[]=$_SESSION['id_cliente'];
			addRegistroObvii($data);
	?>
		<script>
			//Precis&iacute;on GPS: <?=$_REQUEST['accu']?>mts|
			var txt ="Cerca de: <?=$dat[0][3]?> #<?=$dat[0][2]?>,<?=$dat[0][4]?>";
			$("#text_sup").html(txt);
  		mensaje("No se encontraron empresas para registrar.");
		</script>
	<?php
	}
}elseif($_REQUEST['tipo']==1 and $est_sesion!=0)
{
	?>
	<script>
		window.location.href="<?=$CM_path_base?>";
	</script>
	<?php
}elseif($_REQUEST['tipo']==2)
{
	$dat=getDireccionGoogleLATLON($_REQUEST['lat'],$_REQUEST['lon']);
	?>
		<script>
			//Precis&iacute;on GPS: <?=$_REQUEST['accu']?>mts|
			var txt ="Cerca de: <?=$dat[0][3]?> #<?=$dat[0][2]?>,<?=$dat[0][4]?>";
			$("#text_sup").html(txt);
  		
		</script>
	<?php
}
elseif($_REQUEST['tipo']==3)//Usuario
{
	$datos=getUsuario(strtolower($_REQUEST['mail']));
	//print_r($datos);
	$data=array();
	if(count($datos)>0)
	{
		$cliente=getCliente($datos[3]);
	}
	
	if(count($datos)>0 and $datos[6]== $_REQUEST['clave'] and $cliente[1]==0 and $datos[9]=="f" and $cliente[4] <= getFecha() and $cliente[5] >= getFecha())
	{
		inicioSesion($_REQUEST['mail'],"".$datos[1]." ".$datos[2]."",$datos[0],$datos[3],$datos[10],$cliente[8]);
		$data[]=$datos[0];
		$data[]=0;
		$data[]=$_REQUEST['mail'];
		$data[]=$_REQUEST['clave'];
		$data[]=$datos[3];
		addregUs($data);		
	?>
		<script>
			window.location.href=MX_path;
		</script>
		<?php
	}else
	{
		if( $cliente[1]!=0 and count($datos)>0)
		{
			echo "El cliente esta dado de baja. Por favor contactese con el proveedor";
		}elseif($datos[9])
		{
			echo "El usuario no tiene autorizacion";
		}else
		{
			echo "Usuario no encontrado. Por favor intentelo nuevamente";
			if(count($datos)>0)
				$data[]=$datos[0];
			else
				$data[]=0;
			$data[]=1;
			$data[]=$_REQUEST['mail'];
			$data[]=$_REQUEST['clave'];
			if(count($datos)>0)
				$data[]=$datos[3];
			else
					$data[]=0;
			addregUs($data);
		}
	}
}elseif($_REQUEST['tipo']==4)//Cerrar sesion
{
	$est_sesion=estado_sesion();
	cerrar_sesion();
}elseif($_REQUEST['tipo']==5) //load registros por usuario
{
	
	$registros=getRegistrosFiltro(" id_usuario like '".$_SESSION['id_usuario']."' and estado=0 and id_cliente=".$_SESSION['id_cliente']." and fecha_hora >='".getFechaDia()."' order by fecha_hora desc");
	//print_r($registros);
	foreach($registros as $i => $reg)
	{		
		$empr=getEmpresa($reg[5]);
		$fecha_actual=trim($reg[2]);
		//$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fecha_actual ) ) ;
		//$fec = date ( 'd-m-Y H:i:s' , $fecha_actual2 );
		
		if(trim($reg[8])=="" and $_SESSION['app']==1) //usuario + check-in
		{
		?>
		
		<li class="txt_lista"><a href="javascript:checkOut(<?=$reg[0]?>,'','','');">
			<img src="images/exit.png" alt="Buy" class="ui-li-icon"><h3><?=ucwords($empr[0])?></h3> <p>Check-in: <?=$fecha_actual?></p></a>
		</li>
	<?php
		}elseif($_SESSION['app']==0) //usuario sin checkin
		{
			$fec_termino=trim($reg[8]);
			//$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fec_termino ) ) ;
			//$fec_ter = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
		?>
		
		<li class="txt_lista">
			<h3><?=ucwords($empr[0])?></h3> <p>Fecha: <?=$fecha_actual?></p>
		</li>
		<?php
			
		}elseif(trim($reg[8])!="" and ($_SESSION['app']==1 or $_SESSION['app']==2 or ($_SESSION['app']==3 or $_SESSION['app']==4 or $_SESSION['app']==5)))
		{
			$fec_termino=trim($reg[8]);
			//$fecha_actual2 = strtotime ( '-3 hours ' , strtotime ( $fec_termino ) ) ;
			//$fec_ter = date ( 'd-m-Y H:i:s' , $fecha_actual2 );
		?>
		
		<li class="txt_lista"><a href="javascript:verDetalle('<?=$empr[0]?>','<?=$fecha_actual?>','<?=$fec_termino?>','<?=$reg[9]?>',<?=$reg[0]?>);">
		<h3><?=ucwords($empr[0])?></h3> <p>Check-in: <?=$fecha_actual?></p><p>  Check-out: <?=$fec_termino?></p></a>
		</li>
		<?php
			
		}elseif(trim($reg[8])=="" and $_SESSION['app']==2) //usuario + check-in
		{
			?>
		
			<li class="txt_lista"><a href="javascript:checkOut2(<?=$reg[0]?>,'<?=$empr[0]?>');" >
				<img src="images/exit.png" alt="Buy" class="ui-li-icon"><h3><?=ucwords($empr[0])?></h3> <p>Check-in: <?=$fecha_actual?></p></a>
			</li>
			<?php
		}elseif(trim($reg[8])=="" and ($_SESSION['app']==3 or $_SESSION['app']==4)) //usuario + check-in
		{
			?>
		
			<li class="txt_lista"><a href="javascript:checkOut3b(<?=$reg[0]?>,'<?=$empr[0]?>');" >
				<img src="images/exit.png" alt="Buy" class="ui-li-icon"><h3><?=ucwords($empr[0])?></h3> <p>Check-in: <?=$fecha_actual?></p></a>
			</li>
			<?php
		}elseif(trim($reg[8])=="" and ($_SESSION['app']==5)) //usuario + check-in+agenda + stock
		{
			
			?>
		
			<li class="txt_lista"><a href="javascript:checkOut5(<?=$reg[0]?>,'<?=$empr[0]?>');" >
				<img src="images/exit.png" alt="Buy" class="ui-li-icon"><h3><?=ucwords($empr[0])?></h3> <p>Check-in: <?=$fecha_actual?></p></a>
			</li>
			<?php
		}
	}
	?>

	<script>
		$('#list_registros').listview('refresh');
	</script>
	<?php
}elseif($_REQUEST['tipo']==6) //log registro
{
	$data=array();
		$data[]=0;
		$data[]=2;
		$data[]=$_REQUEST['mail'];
		$data[]=$_REQUEST['clave'];
		$data[]=0;
		addregUs($data);
}elseif($_REQUEST['tipo']==7 and $est_sesion==0)
{
	$dat=getDireccionGoogleLATLON($_REQUEST['lat'],$_REQUEST['lon']);
	if($_REQUEST['accu'] >= $CM_accu_max)
	{
		$CM_radio=$CM_radio+$_REQUEST['accu'];
	}
	
	$datos_empresa=getEmpresaRadio($_REQUEST['lat'],$_REQUEST['lon'],$CM_radio,$_SESSION['id_cliente']);
	
	
	if(count($datos_empresa)>0)
	{
		
		foreach($datos_empresa as $emp)
		{
			$distancia=$emp[8];
			?>
			<li><a href="javascript:checkEmpresa(<?=$emp[0]?>,<?=$_REQUEST['lat']?>,<?=$_REQUEST['lon']?>,<?=$_REQUEST['accu']?>,<?=$distancia?>);"><?=ucwords($emp[1])?></a></li>
			<?php
		}
		
		?>
		<script>
			//Precis&iacute;on GPS: <?=$_REQUEST['accu']?>mts|
			var txt ="Cerca de: <?=$dat[0][3]?> #<?=$dat[0][2]?>,<?=$dat[0][4]?>";
			$("#text_sup").html(txt);
			
			$.mobile.changePage('#m_empresa',  {transition: 'pop', role: 'dialog'});
			$('#lsta_empresa').listview('refresh');
  		
  		
		</script>
		<?php
		
	}else
	{
			$data=array();
			$data[]=$_SESSION['id_usuario'];
			$data[]=$_REQUEST['lat'];
			$data[]=$_REQUEST['lon'];
			$data[]=0;
			$data[]=0;
			$data[]=$_REQUEST['accu'];
			$data[]=1;
			$data[]=$_SESSION['id_cliente'];
			addRegistroObvii($data);
		?>
		<script>
			//Precis&iacute;on GPS: <?=$_REQUEST['accu']?>mts|
			var txt ="Cerca de: <?=$dat[0][3]?> #<?=$dat[0][2]?>,<?=$dat[0][4]?>";
			$("#text_sup").html(txt);
  		mensaje("No se encontraron empresas para registrar.");
		</script>
		<?php
	}
	
	

}elseif($_REQUEST['tipo']==8 and $est_sesion==0)
{
	
			$empresa=$_REQUEST['id_empresa'];
			$datos_empresa=getEmpresa($empresa);
			$data=array();
			$data[]=$_SESSION['id_usuario'];
			$data[]=$datos_empresa[5];
			$data[]=$datos_empresa[6];
			$data[]=$empresa;
			$data[]=$_REQUEST['distancia'];
			$data[]=$_REQUEST['accu'];
			$data[]=0;
			$data[]=$_SESSION['id_cliente'];
			
			addRegistroObvii($data);
			?>
			<script>
			addMarcadores(<?=$datos_empresa[6]?>,<?=$datos_empresa[5]?>,'Ultimo registro <?=$datos_empresa[0]?>','iconos/point3.png',32,32);
  		moverCentro(<?=$datos_empresa[5]?>,<?=$datos_empresa[6]?>,17);    	
  		mensaje("Haz sido registrado correctamente en: <?=ucwords($datos_empresa[0])?>");
		</script>
			<?php

}elseif($_REQUEST['tipo']==9 and $est_sesion==0) //checkout (A)
{
	if(trim($_REQUEST['detalle'])=="")
	{
		updateRegistro($_REQUEST['registro'],"fecha_check_out='".getFecha()."'");	
	}else
	{
		updateRegistro($_REQUEST['registro'],"fecha_check_out='".getFecha()."', detalle='".$_REQUEST['detalle']."'");	
	}
	
	if($_REQUEST['img']!="")
	{
		//print_r($_REQUEST);
		$data=array();
		$data[]=$_REQUEST['registro'];
		$data[]=$_REQUEST['img'];
		$data[]=$CM_PATH_IMG;
		addArchivo($data);
	}
	if($_REQUEST['img2']!="")
	{
		//print_r($_REQUEST);
		$data=array();
		$data[]=$_REQUEST['registro'];
		$data[]=$_REQUEST['img2'];
		$data[]=$CM_PATH_IMG;
		addArchivo($data);
	}
	$registro=getIntentosRegistro(" id_registro=".$_REQUEST['registro']."");
	$agenda=getAgendas(" and id_usuario=".$_SESSION["id_usuario"]." and id_empresa=".$registro[0][5]." and fecha_agenda='".date("Y-m-d")."'");
	upAgenda(" estado=2",$agenda[0][0]);
	
}elseif($_REQUEST['tipo']==10 and $est_sesion==0)
{
	$documentos=getArchivosReg($_REQUEST['registro']);
	
	foreach($documentos as $doc)
	{
		$html .="<br><img width=30% height=30% src='".$doc[4]."".$doc[3]."'>";
		if($_SESSION['app']==5)
		{
			$html .="<br>Comentario:".$doc[5]."<br>";
		}
	}
	if($_SESSION['app']==5)
	{
		$prod_detalle=getProductosRegistro($_REQUEST['registro']);
		$html2="<br><h5>Inventario</h5>";
		$html2 .="<table><tr align=center class=txt_header><td align=left width=30%>Producto</td><td width=20%>Inicio</td><td width=20%>Fin</td><td width=20% >valor</td></tr>";
		foreach($prod_detalle as $prod_d)
		{
			$producto=getProducto($prod_d[1]);
			$html2 .="<tr ><td align=left width=30%><h5>".ucwords($producto[2])."</h5></td><td align=center width=20%><h5>".$prod_d[4]."</h5></td><td align=center width=20%><h5>".$prod_d[5]."</h5></td><td align=center width=20%><h5>".$prod_d[6]."</h5></td></tr>";
		}
		$html2 .="</table>";
		
		$html2 .="<br>Comentario: ".$prod_d[8]."";
	}
	?>
	<script>
		$("#doc_empresa").html("<?=$html?>");
		$("#dt_otros").html("<?=$html2?>");
		</script>
	<?php
	
}elseif($_REQUEST['tipo']==11) //load agendas por usuario
{
	if(isset($_REQUEST['lon']) and isset($_REQUEST['lat']))
	{
		 $agenda=getAgendaGEO(" and agenda.id_usuario=".$_SESSION["id_usuario"]." and agenda.fecha_agenda = '".getFechaDia()."' and agenda.estado!=1",$_REQUEST['lon'],$_REQUEST['lat']);
		 
	}else
	{		
		$agenda=getAgendas(" and id_usuario=".$_SESSION["id_usuario"]." and fecha_agenda = '".getFechaDia()."' and estado!=1");
	}
	?>
	<div class="ui-bar ui-bar-a" style="text-align:left;">
		<img src="images/map.png" onclick="ordenarAgenda();" id="icon_li"> <img id="icon_li2" src="images/refresh.png" onclick="loadAgendas();">
	</div>
	<?php
	foreach($agenda as $ag)
	{
		$datos_empresa=getEmpresa($ag[4]);
		$distancia="";
		if(isset($_REQUEST['lon']) and isset($_REQUEST['lat']))
		{
			//$dist=distanciaPtos($datos_empresa[6],$datos_empresa[5],$_REQUEST['lon'],$_REQUEST['lat']);
			$dist=$ag[8];
			if($dist < 1000)
			{
				$dist=round($dist,2);
				$distancia="".$dist."mts";
			}else
			{
				$dist=$dist/1000;
				$dist=round($dist,2);
				$distancia=$dist."km";
			}
		}
		
		$color="txt_lista";
		$direccion="".ucwords($datos_empresa[1])." ".$datos_empresa[2].", ".ucwords($datos_empresa[3])."";
		
		if($ag[6]==0)
		{
			$color="txt_lista_activo";
		}
		if($ag[6]==2)
		{
			$color="txt_lista_visita";
		}
		$texto_html="<div id=cont_pop><div id=titulo1>".ucwords($datos_empresa[0])."</div>";
		$texto_html .="<div id=titulo2>".ucwords($datos_empresa[1])." ".ucwords($datos_empresa[2]).", ".ucwords($datos_empresa[3])."</div></div>";
		
		
	?>	
				<li class="<?=$color?>"><a class="<?=$color?>" href="javascript:verDetalleAgenda('<?=ucwords($datos_empresa[0])?>','<?=ucwords($datos_empresa[0])?>','<?=ucwords($ag[7])?>','<?=$direccion?>','<?=ucwords($datos_empresa[7])?>');"><?=ucwords(strtolower($datos_empresa[0]))?> <span class=txt_mini><?=$distancia?><span></a>
					<a href="javascript:verMapa(<?=$datos_empresa[5]?>,<?=$datos_empresa[6]?>,'<?=$texto_html?>');" data-rel="popup" data-icon="search" data-position-to="window" data-transition="pop">Mapa</a>
				</li>
	<?php
	}
	
	?>

	<script>
		$('#list_registros_agenda').listview('refresh');
	</script>
	<?php
}elseif($_REQUEST['tipo']==12 and $est_sesion==0) //checkout 5
{
	updateRegistro($_REQUEST['registro'],"fecha_check_out='".getFecha()."'");	
	//add detalle producto
  $producto=explode("|",$_REQUEST['prod_id']);
  $producto_ini=explode("|",$_REQUEST['prod_ini']);
  $producto_fin=explode("|",$_REQUEST['prod_fin']);
  $producto_val=explode("|",$_REQUEST['prod_val']);
  foreach($producto as $i => $pro)
  {
  	$data=array();
  	$data[]=$pro;
  	$data[]=$_SESSION["id_usuario"];
  	$data[]=getFecha();
  	$data[]=$_SESSION["id_cliente"];
  	$data[]=$producto_ini[$i];
  	$data[]=$producto_fin[$i];
  	$data[]=$producto_val[$i];
  	$data[]=0;
  	$data[]=$_REQUEST['detalle3'];
  	$data[]=$_REQUEST['registro'];  	
  	
  	addProductoDet($data);	
  }
	
	/*if(trim($_REQUEST['detalle'])=="")
	{
		updateRegistro($_REQUEST['registro'],"fecha_check_out='".getFecha()."'");	
	}else
	{
		updateRegistro($_REQUEST['registro'],"fecha_check_out='".getFecha()."', detalle='".$_REQUEST['detalle']."'");	
	}*/
	
	if($_REQUEST['img']!="")
	{
		//print_r($_REQUEST);
		$data=array();
		$data[]=$_REQUEST['registro'];
		$data[]=$_REQUEST['img'];
		$data[]=$CM_PATH_IMG;
		$data[]=$_REQUEST['detalle'];
		addArchivo($data);
	}
	if($_REQUEST['img2']!="")
	{
		//print_r($_REQUEST);
		$data=array();
		$data[]=$_REQUEST['registro'];
		$data[]=$_REQUEST['img2'];
		$data[]=$CM_PATH_IMG;
		$data[]=$_REQUEST['detalle2'];
		addArchivo($data);
	}
	$registro=getIntentosRegistro(" id_registro=".$_REQUEST['registro']."");
	$agenda=getAgendas(" and id_usuario=".$_SESSION["id_usuario"]." and id_empresa=".$registro[0][5]." and fecha_agenda='".date("Y-m-d")."'");
	upAgenda(" estado=2",$agenda[0][0]);
	
}
}
?>