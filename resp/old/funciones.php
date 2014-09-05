<?php
include("connec.php");
function getDireccionGoogleLATLON($lat,$lon)
{
	$delay = 0;
	
	$base_url="http://maps.googleapis.com/maps/api/geocode/xml?";
  $geocode_pending = true;
  while ($geocode_pending) {
    
    $address=trim($direccion);
    //$request_url = $base_url . "&address=" . urlencode($address)."+chile&oe=utf-8&sensor=false";
    $request_url=$base_url."latlng=".$lat.",".$lon."&sensor=false";
    $xml = simplexml_load_file($request_url) or die("url not loading");    
    //print_r($xml);
    $status = $xml->status;
    if (strcmp($status, "OK") == 0) {
      // Successful geocode
      $geocode_pending = false;
      
      $total_r=$xml->result;
      
      $len_place=$xml;
      $i=1;
      foreach($len_place->result as $len)
      {
      	$direc = $len->formatted_address;
      	$tipo = $len->type;
      	//echo "total:".count($len->address_component);
      	for($i=0;$i<count($len->address_component);$i++)
      	{
      		$type=$len->address_component[$i]->type;
      		$type2=$len->address_component[$i]->type[0];
      		if(strtolower(trim($type))=="street_number")
      		{
      			$numero_municipal=$len->address_component[$i]->long_name;
      		}elseif(strtolower(trim($type))=="route")
      		{
      			$calle=$len->address_component[$i]->long_name;
      			$abrevia_calle=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="locality")
      		{
      			$ciudad=$len->address_component[$i]->long_name;
      			$abrevia_ciudad=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_3")
      		{
      			$comuna=$len->address_component[$i]->long_name;
      			$abrevia_comuna=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_1")
      		{
      			$region=$len->address_component[$i]->long_name;
      			$abrevia_region=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="country")
      		{
      			$pais=$len->address_component[$i]->long_name;
      			$abrevia_pais=$len->address_component[$i]->short_name;
      		}
      		
      		
      	}
      	//geometrias
      	$latitud=$len->geometry->location->lat;
      	$longitud=$len->geometry->location->lng;
      	$tipo_gis=$len->geometry->location_type;
      	
      	$dire=Array();
				$dire[]=$tipo;
				$dire[]=$direc;
				$dire[]=$numero_municipal;
				$dire[]=$calle;
				$dire[]=$comuna;
				$dire[]=$ciudad;
				$dire[]=$region;
				$dire[]=$latitud;
				$dire[]=$longitud;
				$dire[]=$tipo_gis;
				if(strtolower($tipo)=="street_address")
				{
      		$direccion_arr[]=$dire;
      	}
				$i++;
    	}      
    } 
    usleep($delay);
  }
 
	return $direccion_arr;
}
function getUsuario($mail)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_usuario,nombre,apellido,id_cliente,fecha_registro,mail,clave,estado,tipo_usuario,demo,app from mx_usuarios_obvii where mail like '".strtolower($mail)."' and estado=0";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	 
	   
	   $data[]=$row2[6];	 
	   $data[]=$row2[7];	 
	   $data[]=$row2[8];	 
	   $data[]=$row2[9];
	   $data[]=$row2[10];
	   	 
	}
	pg_close($dbPg);
	return $data;
}
function getUsuarioXId($id)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_usuario,nombre,apellido,id_cliente,fecha_registro,mail,clave,estado from mx_usuarios_obvii where id_usuario = ".$id." and estado=0";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	 
	   
	   $data[]=$row2[6];	 
	   $data[]=$row2[7];	 
	   	 
	}
	pg_close($dbPg);
	return $data;
}
function inicioSesion($mail,$nombre,$id_usuario,$cliente,$app,$pais)
{
	
	session_start();	
	//session_register('usuario');
	$_SESSION["usuario"] = $mail;
	$_SESSION["mail_usu"] = $mail;
	$_SESSION["id_usuario"] = $id_usuario;
	$_SESSION['fecha']=date("Y-m-d H:i:s");
	$_SESSION['nombre']=$nombre;
	$_SESSION['id_cliente']=$cliente;
	$_SESSION['app']=$app;	
	$_SESSION['pais_locate']=$pais;
}
function cerrar_sesion()
{
	session_start();
	unset($_SESSION["usuario"]); 
	unset($_SESSION["fecha"]); 
	unset($_SESSION["nombre"]); 
	unset($_SESSION["id_usuario"]); 
	unset($_SESSION['id_cliente']);
	unset($_SESSION['app']);
	unset($_SESSION['pais_locate']);
	//session_destroy();
}
function estado_sesion()
{
	session_start();
	
	$estado=1;
	$hoy=date("Y-m-d H:i:s");
	
	$tiempo= segundos($_SESSION['fecha'],$hoy);
	
	if(isset($_SESSION['usuario']) and trim($_SESSION['usuario'])!="" and $tiempo < 1000000)	//7200
  {
  	$estado=0;
  }
  
  return $estado;
}

function segundos($hora_inicio,$hora_fin){
$hora_i=substr($hora_inicio,11,2);
$minutos_i=substr($hora_inicio,14,2);
$año_i=substr($hora_inicio,0,4);
$mes_i=substr($hora_inicio,5,2);
$dia_i=substr($hora_inicio,8,2);
$hora_f=substr($hora_fin,11,2);
$minutos_f=substr($hora_fin,14,2);
$año_f=substr($hora_fin,0,4);
$mes_f=substr($hora_fin,5,2);
$dia_f=substr($hora_fin,8,2);
$diferencia_seg=mktime($hora_f,$minutos_f,0,$mes_f,$dia_f,$año_f) - mktime($hora_i,$minutos_i,0,$mes_i,$dia_i,$año_i);
return $diferencia_seg;
}
function getEmpresaRadio($lat,$lon,$radio,$cliente)//retorna distancia en metros
{
	$dbPg=pgSql_db();		
	
	$sql="select id_empresa,nombre,calle,numero_municipal,comuna,region,latitud,longitud,ST_Distance(
  ST_GeographyFromText('POINT(".$lon." ".$lat.")'), 
  ST_GeographyFromText(st_AsText(geom))
  ) as radio, otro from mx_empresa where estado=0 and id_cliente=".$cliente." and ST_Distance(
  ST_GeographyFromText('POINT(".$lon." ".$lat.")'), 
  ST_GeographyFromText(st_AsText(geom))
  ) <= ".$radio." order by radio";
	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo $sql;
	while ($rowCalle = pg_fetch_row($rsCalle))
	{		
	
		$direc=Array();
		$direc[]=$rowCalle[0];
		$direc[]=$rowCalle[1];
		$direc[]=$rowCalle[2];
		$direc[]=$rowCalle[3];
		$direc[]=$rowCalle[4];
		$direc[]=$rowCalle[5];
		$direc[]=$rowCalle[6];
		$direc[]=$rowCalle[7];
		$direc[]=round($rowCalle[8],2);//distancia en metros
		$direc[]=$rowCalle[9];
		$direcciones[]=$direc;
		
		
	}	
	pg_close($dbPg);
  return $direcciones;
}

function addRegistroObvii($data)
{
	$dbPg=pgSql_db();	
	$sql=utf8_encode("INSERT INTO mx_registro_obvii(
            id_usuario, fecha_hora, latitud, longitud, id_empresa, distancia,precision,estado,id_cliente)
    VALUES ('".$data[0]."', '".getFecha()."', '".$data[1]."', '".$data[2]."', '".$data[3]."','".$data[4]."','".$data[5]."',".$data[6].",".$data[7].");");            
  //echo "<br>".$sql;
  $rsCalle = pg_query($dbPg, $sql);	
}
function getRegistrosFiltro($qr)
{
	$dbPg=pgSql_db();		
	
	$sql="select id_registro,id_usuario,fecha_hora, latitud, longitud,id_empresa,distancia,precision,fecha_check_out,detalle from mx_registro_obvii";	
	if(trim($qr)!="")
	{
		$sql .=" where ".$qr."";
	}
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
		$data[]=$rowCalle[8];
		$data[]=$rowCalle[9];
		
		$datas[]=$data;
	}	
	pg_close($dbPg);
  return $datas;
}
function getEmpresa($id_empresa)
{
	$dbPg=pgSql_db();		
	
	$sql="select nombre,calle,numero_municipal,comuna,region, latitud, longitud,otro from mx_empresa where id_empresa=".$id_empresa."";	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
		
		
	}	
	pg_close($dbPg);
  return $data;
}

function getRegistros()
{
	$dbPg=pgSql_db();		
	
	$sql="select id_registro,id_usuario,fecha_hora, latitud, longitud,id_empresa,distancia from mx_registro_obvii";	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		
		$datas[]=$data;
	}	
	pg_close($dbPg);
  return $datas;
}
function getEmpresas()
{
	$dbPg=pgSql_db();		
	
	$sql="select nombre,calle,numero_municipal,comuna,region, latitud, longitud,otro,id_empresa from mx_empresa";	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
	  $data_final[$rowCalle[8]]=$data;
		
	}	
	pg_close($dbPg);
  return $data_final;
}

function inicioSesion_web($mail,$nombre,$id_usuario,$cliente,$app,$mx_lug,$pais,$usuario)
{
	
	session_start();
	
	//session_register('usuario');
	$_SESSION["usuario_web"] = $mail;
	$_SESSION["id_usuario_web"] = $id_usuario;
	$_SESSION['fecha_web']=date("Y-m-d H:i:s");
	$_SESSION['nombre_web']=$nombre;
	$_SESSION['id_cliente_web']=$cliente;
	$_SESSION['app_web']=$app;	
	$_SESSION['mx_lugares']=$mx_lug;	
	$_SESSION['pais_web']=$pais;	
	$_SESSION['tipo_us_web']=$usuario;
	
	
	
}
function cerrar_sesion_web()
{
	session_start();
	unset($_SESSION["usuario_web"]); 
	unset($_SESSION["fecha_web"]); 
	unset($_SESSION["nombre_web"]); 
	unset($_SESSION["id_usuario_web"]); 
	unset($_SESSION['id_cliente_web']);
	unset($_SESSION['app_web']);
	unset($_SESSION['mx_lugares']);
	unset($_SESSION['pais_web']);
	unset($_SESSION['tipo_us_web']);
	//session_destroy();
}
function estado_sesion_web()
{
	session_start();
	
	$estado=1;
	$hoy=date("Y-m-d H:i:s");
	
	$tiempo= segundos($_SESSION['fecha_web'],$hoy);
	
	if(isset($_SESSION['usuario_web']) and trim($_SESSION['usuario_web'])!="" and $tiempo < 7200)	//7200
  {
  	$estado=0;
  }
  
  return $estado;
}
function addregUs($data)
{
	$dbPg=pgSql_db();
	$sql="INSERT INTO mx_registro_usuario_locate(
            id_usuario, fecha, estado, usuario, clave,id_cliente)
    VALUES (".$data[0].", '".getFecha()."', ".$data[1].", '".$data[2]."', '".$data[3]."',".$data[4].");";
  $rsCalle = pg_query($dbPg, $sql);	  
  pg_close($dbPg);  
}
function getAccesos($qr)
{
	$dbPg=pgSql_db();		
	
	$sql="select id_reg_usuario,id_usuario,fecha, estado, usuario,clave from mx_registro_usuario_locate";	
	if(trim($qr)!="")
	{
		$sql .=" where ".$qr."";
	}
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];

		
		$datas[]=$data;
	}	
	pg_close($dbPg);
  return $datas;
}

function getIntentosRegistro($qr)
{
	$dbPg=pgSql_db();		
	
	$sql="select id_registro,id_usuario,fecha_hora, latitud, longitud,id_empresa,distancia,precision from mx_registro_obvii";	
	if(trim($qr)!="")
	{
		$sql .=" where ".$qr."";
	}
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];

		
		$datas[]=$data;
	}	
	pg_close($dbPg);
  return $datas;
}
function getCliente($id)
{
	$dbPg=pgSql_db();		
	
	$sql="select  nombre, estado,logo,app,fecha_inicio,fecha_termino,max_lugares,max_usuarios,pais from mx_cliente where id_cliente=".$id."";	
	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
		$data[]=$rowCalle[8];
		
		
	
	}	
	pg_close($dbPg);
  return $data;
}
function getUsuarioXCliente($id_cliente)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_usuario,nombre,apellido,id_cliente,fecha_registro,mail,clave,estado from mx_usuarios_obvii where id_cliente = ".$id_cliente." and estado=0 order by nombre";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	 
	   
	   $data[]=$row2[6];	 
	   $data[]=$row2[7];	 
	   	 $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function updateRegistro($id_registro,$qr)
{
	$dbPg=pgSql_db();	
	$sql2 = "update mx_registro_obvii set ".$qr." where id_registro=".$id_registro."";		
  $rs2 = pg_query($dbPg, $sql2);
  pg_close($dbPg);
}
function addArchivo($data)
{
	$dbPg=pgSql_db();
	if(trim($data[3])!="")
	{
		$detalle=$data[3];
	}else
	{
		$detalle="";
	}
$sql="INSERT INTO mx_archivos_obvii(
             id_registro, fecha_subida, nombre, path,detalle)
    VALUES (".$data[0].", '".getFecha()."', '".$data[1]."', '".$data[2]."','".$detalle."');";
$rs2 = pg_query($dbPg, $sql);
  pg_close($dbPg);
}
function getArchivosReg($id_registro)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_archivo,id_registro, fecha_subida, nombre, path, detalle from mx_archivos_obvii where id_registro=".$id_registro."";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	
	   	 $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function getAgendasGis($qr,$lon,$lat)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_agenda, id_usuario, fecha_registro, fecha_agenda, id_empresa, 
       id_categoria_usuario, estado, descripcion
  FROM mx_agenda_obvii where 1=1 ";		
  if(trim($qr)!="")
  {
  	$sql2 .=$qr;
  }
//echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	
	   $data[]=$row2[6];	
	   $data[]=$row2[7];	
	   $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function getAgendas($qr)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_agenda, id_usuario, fecha_registro, fecha_agenda, id_empresa, 
       id_categoria_usuario, estado, descripcion
  FROM mx_agenda_obvii where 1=1 ";		
  if(trim($qr)!="")
  {
  	$sql2 .=$qr;
  }
//echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	
	   $data[]=$row2[6];	
	   $data[]=$row2[7];	
	   $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function getEmpresasXCliente($id_cliente)
{
	$dbPg=pgSql_db();		
	
	$sql="select nombre,calle,numero_municipal,comuna,region, latitud, longitud,otro,id_empresa from mx_empresa where id_cliente=".$id_cliente." and estado=0 order by nombre";	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
		$data[]=$rowCalle[8];
	  $data_final[]=$data;
		
	}	
	pg_close($dbPg);
  return $data_final;
}
function getCategoriaUsuario($id_cliente)
{
	$dbPg=pgSql_db();			
	$sql="SELECT id_categoria_usuario, nombre, estado, id_cliente FROM mx_categoria_usuario_obvii where id_cliente=".$id_cliente." and estado=0 order by nombre";	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){				
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data_final[]=$data;
		
	}	
	pg_close($dbPg);
  return $data_final;
}
function addAgenda($data)
{
	$dbPg=pgSql_db();			
	$sql="INSERT INTO mx_agenda_obvii(
            id_usuario, fecha_registro, fecha_agenda, id_empresa, 
            id_categoria_usuario, estado, descripcion,id_cliente)
    VALUES ('".$data[0]."', '".getFecha()."', '".$data[1]."', '".$data[2]."', 
            '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."');";	
	$rsCalle = pg_query($dbPg, $sql);	

	pg_close($dbPg);
  return $data_final;
}
function getFecha()
{
	$fecha=date("Y-m-d H:i:s");
	$fecha_actual2 = strtotime ( '-4 hours ' , strtotime ( $fecha ) ) ;
	$fec = date ( 'Y-m-d H:i:s' , $fecha_actual2 );
	return $fec;
}
function upAgenda($qr,$id)
{
	$dbPg=pgSql_db();			
	$sql="update mx_agenda_obvii set ".$qr." where id_agenda=".$id.";";	
	$rsCalle = pg_query($dbPg, $sql);	

	pg_close($dbPg);
  return $data_final;
}
function getEmpresasXquery($qr)
{
	$dbPg=pgSql_db();		
	
	$sql="select nombre,calle,numero_municipal,comuna,region, latitud, longitud,otro,id_empresa from mx_empresa where 1=1";	
	if(trim($qr)!="")
	{
		$sql .=$qr;
	}
	$rsCalle = pg_query($dbPg, utf8_encode($sql));	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];	
		$data=array();	
		$data[]=$rowCalle[0];
		$data[]=$rowCalle[1];
		$data[]=$rowCalle[2];
		$data[]=$rowCalle[3];
		$data[]=$rowCalle[4];
		$data[]=$rowCalle[5];
		$data[]=$rowCalle[6];
		$data[]=$rowCalle[7];
		$data[]=$rowCalle[8];
	  $data_final[]=$data;
		
	}	
	pg_close($dbPg);
  return $data_final;
}
function getUsuarioXQuery($qr)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_usuario,nombre,apellido,id_cliente,fecha_registro,mail,clave,estado from mx_usuarios_obvii where 1=1";		
	if(trim($qr)!="")
	{
		$sql2 .=$qr;
	}
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	 
	   
	   $data[]=$row2[6];	 
	   $data[]=$row2[7];	 
	   	 $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function getComunas()
{
	$dbPg=pgSql_db2();
	$sql2 = "select nombre,id_comuna from gis_comuna order by nombre";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$comunas=array();
			$comunas[]=$row2[0];
			$comunas[]=$row2[1];
			$com[]=$comunas;
		}
		pg_close($dbPg);
		return $com;
}
function getProductos($id_cliente,$estado)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_producto, id_cliente, nombre, fecha_registro, estado FROM mx_producto_obvii where id_cliente=".$id_cliente." and estado=".$estado." order by nombre";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   	
	   	 $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function addProductoDet($data)
{
	$dbPg=pgSql_db();	
	$sql2 = "INSERT INTO mx_producto_detalle_obvii(
             id_producto, id_usuario, fecha_registro, id_cliente, 
            inicio, fin, venta, estado, comentario, id_registro)
    VALUES (".$data[0].", ".$data[1].", '".$data[2]."', ".$data[3].", 
            ".$data[4].", ".$data[5].", ".$data[6].", ".$data[7].", '".$data[8]."', ".$data[9].");";		
  $rs2 = pg_query($dbPg, $sql2);
  pg_close($dbPg);
}
function getProductosRegistro($registro)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_det_prod, id_producto, id_usuario, id_cliente, inicio, fin, 
       venta, estado, comentario, id_registro, fecha_registro
  FROM mx_producto_detalle_obvii where id_registro=".$registro."  order by id_producto";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	
	   $data[]=$row2[6];	
	   $data[]=$row2[7];	
	   $data[]=$row2[8];	
	   $data[]=$row2[9];	
	   $data[]=$row2[10];	
	   	
	   	 $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function getProducto($id)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_producto, id_cliente, nombre, fecha_registro, estado FROM mx_producto_obvii where id_producto=".$id."";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   	
	  
	}
	pg_close($dbPg);
	return $data;
}
function getProductosQR($qr)
{
	$dbPg=pgSql_db();	
	$sql2 = "SELECT id_producto, id_cliente, nombre, fecha_registro, estado FROM mx_producto_obvii where 1=1 ";		
  if(trim($qr)!="")
  {
  	$sql2 .=$qr;
  }
//echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   
	   $datos[]=$data;
	}
	pg_close($dbPg);
	return $datos;
}
function updateProductoQR($qr,$id)
{
	$dbPg=pgSql_db();	
	$sql2 = "update mx_producto_obvii set ".$qr." where id_producto=".$id."";		
	//echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);
}
function addProducto($data)
{
	$dbPg=pgSql_db();	
	$sql2 = "INSERT INTO mx_producto_obvii(
            id_cliente, nombre, fecha_registro, estado)
    VALUES (".$data[0].", '".$data[1]."', '".getFecha()."', 0);";		
	echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);
}
function getFechaDia()
{
	$fecha=date("Y-m-d H:i:s");
	$fecha_actual2 = strtotime ( '-4 hours ' , strtotime ( $fecha ) ) ;
	$fec = date ( 'Y-m-d' , $fecha_actual2 );
	return $fec;
}

/*Lugares*/


function getEmpresaQR($qr)
{
	$dbPg=pgSql_db();	
	$sql2 = "select id_empresa,nombre,calle,numero_municipal,comuna,region,latitud,longitud,fecha_registro,id_cliente,estado from mx_empresa where 1=1";		
	if($qr!="")
	{
		$sql2 .=" ".$qr;
	}
	//echo $sql2;
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
	{			
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	
	   $data[]=$row2[5];	
	   $data[]=$row2[6];	
	   $data[]=$row2[7];	
	   $data[]=$row2[8];	
	   $data[]=$row2[9];	
	   $data[]=$row2[10];	
	   $datos[]=$data;
	   	 
	}
	pg_close($dbPg);
	return $datos;
}

function addEmpresa($data)
{
	$dbPg=pgSql_db();	
	$sql="INSERT INTO mx_empresa(
             nombre, calle, numero_municipal, comuna, region, 
            latitud, longitud, geom, fecha_registro, otro, id_cliente,estado,pais)
    VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', 
            '".$data[5]."', '".$data[6]."', ST_GeomFromText('POINT(".$data[6]." ".$data[5].")',2276), '".getFecha()."', '', '".$data[7]."',0,'".$data[8]."');";
 
 $rs2 = pg_query($dbPg, $sql);           
 pg_close($dbPg);
}

function updateEmpresa($qr, $id)
{
	$dbPg=pgSql_db();	
	$sql="update mx_empresa set ".$qr." where id_empresa=".$id.""; 
 $rs2 = pg_query($dbPg, $sql);           
 pg_close($dbPg);
}

function getRegiones()
{
	$dbPg=pgSql_db2();
	
  $sql2 = "select nombre,id_region from gis_region order by nombre";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$region=array();
			$region[]=$row2[0];
			$region[]=$row2[1];
			$regiones[]=$region;
		}
		pg_close($dbPg);
		
		return $regiones;
}
function getComunaxRegion($id)
{
	$dbPg=pgSql_db2();
	$sql2 = "select nombre,id_comuna from gis_comuna where id_region =".$id." order by nombre";		
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$com=array();
			$com[]=$row2[0];
			$com[]=$row2[1];
			$comunas[]=$com;
		}
		pg_close($dbPg);
		return $comunas;
}


function getDireccionExacta($direccion,$limite)
{
	$dbPg=pgSql_db2();		
	
	$sql=utf8_encode("select id_direccion,calle,segmento,numero_municipal,comuna,region,latitud,longitud,query_completa,origen from gis_direccion where query_completa like '%".strtolower(trim($direccion))."%'");
	if($limite > 0)
	{
		$sql .=" limit ".$limite."";
	}
	$rsCalle = pg_query($dbPg, $sql);	
	//echo "<br>".$sql;
	while ($rowCalle = pg_fetch_row($rsCalle)){		
		//$arr_callesComuna[$rowCalle[0]] = $rowCalle[1];
		$direc=Array();
		$direc[]=$rowCalle[0];
		$direc[]=$rowCalle[1];
		$direc[]=$rowCalle[2];
		$direc[]=$rowCalle[3];
		$direc[]=$rowCalle[4];
		$direc[]=$rowCalle[5];
		$direc[]=$rowCalle[6];
		$direc[]=$rowCalle[7];
		$direc[]=$rowCalle[8];  
		 
		$direc[]=1;
		$direc[]=$rowCalle[9]; 
		$direcciones[]=$direc;
		
		
	}	
	pg_close($dbPg);
  return $direcciones;
}
function getDireccionGoogle($direccion)
{
	
	
	$delay = 0;
	//$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
	$base_url="http://maps.googleapis.com/maps/api/geocode/xml?";
  $geocode_pending = true;
  while ($geocode_pending) {
    //$address = "pasaje u 2113 chile";
    $address=trim($direccion);
    $request_url = $base_url . "&address=" . urlencode($address)."!&oe=utf-8&sensor=false";
   // echo "<br>".$request_url;
    $xml = simplexml_load_file($request_url) or die("url not loading");    
    //print_r($xml);
    $status = $xml->status;
    $geocode_pending = false;
    if (strcmp($status, "OK") == 0) {
      // Successful geocode
      $geocode_pending = false;
      
      $total_r=$xml->result;
      
      $len_place=$xml;
      $i=1;
      foreach($len_place->result as $len)
      {
      	$direc = $len->formatted_address;
      	$tipo = $len->type;
      	//echo "total:".count($len->address_component);
      	for($i=0;$i<count($len->address_component);$i++)
      	{
      		$type=$len->address_component[$i]->type;
      		$type2=$len->address_component[$i]->type[0];
      		if(strtolower(trim($type))=="street_number")
      		{
      			$numero_municipal=$len->address_component[$i]->long_name;
      		}elseif(strtolower(trim($type))=="route")
      		{
      			$calle=$len->address_component[$i]->long_name;
      			$abrevia_calle=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="locality")
      		{
      			$ciudad=$len->address_component[$i]->long_name;
      			$abrevia_ciudad=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_3")
      		{
      			$comuna=$len->address_component[$i]->long_name;
      			$abrevia_comuna=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="administrative_area_level_1")
      		{
      			$region=$len->address_component[$i]->long_name;
      			$abrevia_region=$len->address_component[$i]->short_name;
      		}elseif(strtolower(trim($type2))=="country")
      		{
      			$pais=$len->address_component[$i]->long_name;
      			$abrevia_pais=$len->address_component[$i]->short_name;
      		}
      		
      		
      	}
      	//geometrias
      	$latitud=$len->geometry->location->lat;
      	$longitud=$len->geometry->location->lng;
      	$tipo_gis=$len->geometry->location_type;
      	
      	$dire=Array();
				$dire[]=$tipo;
				$dire[]=$direc;
				$dire[]=$numero_municipal;
				$dire[]=$calle;
				$dire[]=$comuna;
				$dire[]=$ciudad;
				$dire[]=$region;
				$dire[]=$latitud;
				$dire[]=$longitud;
				$dire[]=$tipo_gis;
      	$direccion_arr[]=$dire;
				$i++;
    	}      
    } 
    usleep($delay);
  }
 
	return $direccion_arr;
}
function buscarDireccionOSM($query)
{
	
	$delay = 0;
	
	$base_url="http://nominatim.openstreetmap.org/search?";
  $geocode_pending = true;
  while ($geocode_pending) {
    //$address = "pasaje u 2113 chile";
    $address=trim($direccion);
    $request_url = $base_url . "q=".urldecode($query)."&format=xml&polygon=1&addressdetails=1";
    //echo "<br>".$request_url;
    $xml = simplexml_load_file($request_url) or die("url not loading");    
   // print_r($xml);
    //$status = $xml->status;
    $geocode_pending=false;
    //echo count($xml->place);
    $lonlat_arr=array();
    
    foreach($xml->place as $place)
    {
    	$place=$xml->place;
    	
    	if(strtolower($place->country)=="chile")
    	{
    		
    		$lonlat=Array();
    		$lonlat[]=$place['lon'];
    		$lonlat[]=$place['lat'];
    		$lonlat[]=$place->house_number;
    		$lonlat[]=$place->road;
    		$lonlat[]=$place->city;
    		$lonlat[]=$place->country;
    		$lonlat[]=$place->state;
				$lonlat[]=$place['lat'];
    		$lonlat[]=$place['lon'];
    		$lonlat_arr[]=$lonlat;
    	}
    	//echo "<br>".$longitud;
    	//print_r($xml_result);
    }
  }
  return $lonlat_arr;
}
function getRegionesQR($qr)
{
	$dbPg=pgSql_db2();
	
	$sql2 = "select nombre,id_region from gis_region where 1=1";		
	if(trim($qr)!="")
	{
		$sql2 .=$qr;
	}
  $rs2 = pg_query($dbPg, $sql2);

	while ($row2 = pg_fetch_row($rs2))
		{
			$region=array();
			$region[]=$row2[0];
			$region[]=$row2[1];
			$regiones[]=$region;
		}
		pg_close($dbPg);
		
		return $regiones;
}

function distanciaPtos($lon,$lat,$lon_dest,$lat_dest)
{
	$dbPg=pgSql_db();		
	
	$sql="select ST_Distance(
  ST_GeographyFromText('POINT(".$lon." ".$lat.")'), 
  ST_GeographyFromText('POINT(".$lon_dest." ".$lat_dest.")')
  ) as radio";
	
	$rsCalle = pg_query($dbPg, $sql);	
	//echo $sql;
	$distancia=0;
	while ($rowCalle = pg_fetch_row($rsCalle))
	{
		$distancia=$rowCalle[0];
	}
	return $distancia;
}

function getAgendaGEO($qr,$lon,$lat)
{
	$dbPg=pgSql_db();		
	
	$sql="select agenda.id_agenda, agenda.id_usuario, agenda.fecha_registro, agenda.fecha_agenda, agenda.id_empresa, 
       agenda.id_categoria_usuario, agenda.estado, agenda.descripcion, ST_Distance(
  ST_GeographyFromText(st_astext(empre.geom)), 
  ST_GeographyFromText('POINT(".$lon." ".$lat.")')
  ) as radio from mx_agenda_obvii as agenda,mx_empresa as empre where agenda.id_empresa=empre.id_empresa";
  
  if($qr!="")
  {
  	$sql .=$qr;
  }
  $sql .=" order by radio";
  
  $rsCalle = pg_query($dbPg, $sql);	
	//echo $sql;
	$distancia=0;
	while ($row2 = pg_fetch_row($rsCalle))
	{
		$data=array();
		 $data[]=$row2[0];
		 $data[]=$row2[1];
	   $data[]=$row2[2];
	   $data[]=$row2[3];	
	   $data[]=$row2[4];	   
	   $data[]=$row2[5];	
	   $data[]=$row2[6];	
	   $data[]=$row2[7];	
	   $data[]=$row2[8];	
	   $datos[]=$data;
	}
	return $datos;
}
/* Fin lugares*/
?>