<fieldset>
<legend>Men&uacute;</legend>

<table id=table_filtro style="text-align:center;">

             <tr>
                <td >
                    <a href="javascript:window.location='registros.php'"><img src="img/report_check.png" height=30px width=30px title="Check-in"></a>
                </td>
	 							<td >
                    <a href="javascript:window.location='accesos.php'"><img src="img/acceso.png" height=30px width=30px title="Log Accesos"></a>
                </td>
                <td >
                    <a href="javascript:window.location='intentos.php'"><img src="img/intentos.png" height=30px width=30px title="Intentos de Check-in"></a>
                </td>
                <td >
                    <a href="javascript:mapaTrabajadores();"><img src="img/maps.png" height=30px width=30px title="Ubicacion Trabajadores"></a>
                </td>
                <?php
                //echo "session ".$_SESSION['app_web'];
                if($_SESSION['app_web']==4 or $_SESSION['app_web']==5)
                {
                ?>
                <td >
                    <a href="javascript:window.location='agenda.php'"><img src="img/calendar.png" height=30px width=30px title="Agenda"></a>
                </td>
                <?php
              	}
              	if($_SESSION['app_web']==5)
                {
                ?>
                <td >
                    <a href="javascript:window.location='item.php'"><img src="img/item.png" height=30px width=30px title="Items"></a>
                </td>
                <?php
              	}
                
                ?>
                <td >
                    <a href="javascript:salir();"><img src="img/cancel.png" height=30px width=30px title="Cerrar Sesi&oacute;n"></a>
                </td>
               
            </tr>
		
</table>
</fieldset>