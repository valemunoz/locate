<?php
//chdir("/usr/local/www/d_admin/chilemap.cl/www/coorporativo/includes");
function sendMail($para,$msg,$titulo)
{
	
	
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
	// Cabeceras adicionales
	$cabeceras .= 'From: contacto@mox-up.com' . "\r\n";
	$cabeceras .= 'Reply-To: contacto@chilemap.cl' . "\r\n";
	
	
	if(mail($para, $titulo, $msg, $cabeceras))
	{
		$envio=true;
	}else
	{
		$envio=false;
	}
	return $envio;
}

?>