<?PHP
include("includes/funciones.php");
$data_server= explode("?",$_SERVER['HTTP_REFERER']);

if(strtolower($data_server[0])=="http://locate.chilemap.cl/")
{
	if($_REQUEST['tipo']==1)
	{
		$msg="Hemos recibido un mensaje desde la web coorporativa:<br> NOMBRE:".$_REQUEST['nombre']." <br> MAIL:".$_REQUEST['mail']." <br> MENSAJe:".$_REQUEST['msg']."";
		$envio=sendMail("contacto@mox-com.com",$msg,"Contacto desde Formulario");
		$CM_MAIL_CONTACTO_US="Hemos recibido tu mensaje, nos pondremos en contacto lo antes posible.<br><br><br><img src='http://locate.chilemap.cl/images/logo_places2.png'><br><strong>Equipo Locate</strong>";
		$envio2=sendMail($_REQUEST['mail'],$CM_MAIL_CONTACTO_US,"Contacto Locate");

	}
}

?>