<?php
include("../connec.php");
$archivo="usuario_travel.dat";
$vlineas = file("data/".$archivo."");      
$id_cliente=4;
/*
EL ARCHIVO DEBE CONTENER EL SIGUIENTE FORMATO:
*SER .DAT
*EL ID DEL CLIENTE SE ASIGNA EN LA VARIABLE ID_CLIENTE AL COMIENZO
*SEPARADO CADA CAMPO POR ;
*NOMBRE,APELLIDO,MAIL,CLAVE,TIPO_USUARIO (0NORMAIL 1=ADMIN)
*/
function addUsuario($data)
{
	$dbPg=pgSql_db();	
	$sql = utf8_encode("INSERT INTO mx_usuarios_obvii(
            nombre, id_cliente, apellido, fecha_registro, mail, 
            clave, estado, tipo_usuario)
    VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".date("Y-m-d H:i:s")."', '".$data[3]."', 
            '".$data[4]."', '".$data[5]."', '".$data[6]."');");		
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
		$nombre=trim(strtolower($linea_datos[0]));
		$apellido=trim($linea_datos[1]);
		$mail=trim($linea_datos[2]);
		$clave=trim($linea_datos[3]);		
		$tipo_usuario=trim($linea_datos[4]);
		
		
		$data=array();
		
		$data[]=strtolower($nombre);
		$data[]=$id_cliente;
		$data[]=strtolower($apellido);
		$data[]=strtolower($mail);
		$data[]=$clave;
		$data[]=0;
		$data[]=$tipo_usuario;
		
		addUsuario($data);
		
}
echo "<br><br> Listo!";
?>