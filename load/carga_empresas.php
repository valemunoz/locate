<?php
include("../connec.php");
$archivo="travel2.dat";
$vlineas = file("data/".$archivo."");      
$id_cliente=4;
/*
EL ARCHIVO DEBE CONTENER EL SIGUIENTE FORMATO:
*SER .DAT
*EL ID DEL CLIENTE SE ASIGNA EN LA VARIABLE ID_CLIENTE AL COMIENZO
*SEPARADO CADA CAMPO POR ;
*NOMBRE EMPRESA;CALLE;NUMERO_MUNICIPAL;COMUNA;LAT;LON
*/
function addEmpresa($data)
{
	$dbPg=pgSql_db();	
	$sql = utf8_encode("INSERT INTO mx_empresa(
             nombre, calle, numero_municipal, comuna, region, 
            latitud, longitud, geom, fecha_registro, otro, id_cliente)
    VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[3]."', 
            '".$data[4]."', '".$data[5]."', ST_GeomFromText('POINT(".$data[5]." ".$data[4].")',2276), '".date("Y-m-d H:i:s")."', '', ".$data[6].");");		
  $rs2 = pg_query($dbPg, $sql);
  echo "<br>".$sql;
	pg_close($dbPg);
}
foreach ($vlineas as $sLinea) 
{
		$data=Array();
		$dir_arr=Array();
		$sLinea=str_replace('"',"",$sLinea);
		$sLinea=strtolower($sLinea);
		$linea_datos=explode(";",$sLinea);		
		$nombre_empresa=trim(str_replace(";","",$linea_datos[0]));
		$calle=trim($linea_datos[1]);
		$numero=trim($linea_datos[2]);
		$comuna=trim($linea_datos[3]);		
		$lat=trim($linea_datos[4]);
		$lon=trim($linea_datos[5]);
		
		$data=array();
		$nom=explode("(",$nombre_empresa);
		$data[]=strtolower(trim($nom[0]));
		$data[]=strtolower($calle);
		$data[]=strtolower($numero);
		$data[]=strtolower($comuna);
		$data[]=$lat;
		$data[]=$lon;
		$data[]=$id_cliente;
		addEmpresa($data);
		
}
echo "<br><br> Listo!";
?>