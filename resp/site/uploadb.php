<?php
include("../funciones.php");
$est_sesion=estado_sesion_web();
if($est_sesion==0)
{
	//comprobamos que sea una petición ajax
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
	{
		 
	   $name_fi=$_REQUEST['nom'];
	    //obtenemos el archivo a subir
	    $file = $_FILES['i_file']['name'];
	    

	    //comprobamos si existe un directorio para subir el archivo
	    //si no es así, lo creamos
	    if(!is_dir("img_cli/")) 
	        mkdir("img_cli/", 0777);
	     
	    //comprobamos si el archivo ha subido
	    $name_file=explode(".",$file);
	    if ($file && move_uploaded_file($_FILES['i_file']['tmp_name'],"img_cli/".$name_fi.".".$name_file[1]))
	    {
	    	sleep(2);
	    	// El archivo
				$nombre_archivo = "img_cli/".$name_fi.".".$name_file[1];
				$porcentaje = 0.2;
				
				// Tipo de contenido
				header('Content-Type: image/jpeg');
				
				// Obtener nuevas dimensiones
				list($ancho, $alto) = getimagesize($nombre_archivo);
				$nuevo_ancho = $ancho * $porcentaje;
				$nuevo_alto = $alto * $porcentaje;
				
				/*if($nuevo_ancho> 500 or $nuevo_alto>400 )
				{
					$nuevo_ancho =500;
					$nuevo_alto = 400;
				}*/
				// Redimensionar
				$imagen_p = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);
				$imagen = imagecreatefromjpeg($nombre_archivo);
				imagecopyresampled($imagen_p, $imagen, 0, 0, 0, 0, $nuevo_ancho, $nuevo_alto, $ancho, $alto);
				imagejpeg($imagen_p, "img_cli/".$name_fi.".".$name_file[1], 50);
				updateProductoQR(" path_img='img_cli' , imagen='".$name_fi.".".$name_file[1]."'",$_REQUEST['prod']);
	    }else
	    {
	    	echo "ERROR";
	    	
	    }

	    
	}else{
	    throw new Exception("Error Processing Request", 1);    
	}
}