<?php
include("../funciones.php");
$data=getRegistros();
$estado_web=estado_sesion_web();
if($estado_web!=0)
{
	?>
	<script>
		window.location="login.php";
	</script>
	<?php 
	
}
$empresa=getCliente($_SESSION['id_cliente_web']);
//print_r($data);
?>
<html>
	<head>
		<link type="text/css" rel="stylesheet" href="css/rounded.css" />
		
		<!--<link type="text/css" rel="stylesheet" href="/ws/sites/css/blitzer/jquery-ui-1.10.3.custom.min.css" />-->
		<link href="http://fonts.googleapis.com/css?family=Oxygen" rel="stylesheet" type="text/css" />
		<link rel="shortcut icon" href="img/point.png">
		<title>Agenda ::: Locate By Chilemap</title>
		<!--<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> -->
<script src="funciones.js"></script>
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
 		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	 	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	 	
	 	 	<script src="http://www.chilemap.cl/OpenLayers/OpenLayers.js"></script>
	 	<script src="http://www.chilemap.cl/js/funciones_api.js"></script>

		<script>
			 $(function() {
    $( "#grilla" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
     $( "#grilla2" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });

  });
		 $(function() {
    		$( "#desde" ).datepicker({dateFormat:"yy-mm-dd"});
  			});	

		 $(function() {
    		$( "#hasta" ).datepicker({dateFormat:"yy-mm-dd"});
  			});	
  			


		</script>
		
		<style>
			#mapa
				{
					width:500px !important;
					height:400px !important;
				
				}
				#resultado
				{
					height:60% !important;
					overflow:auto;
					width:112%;
				}
.ui-dialog
{
	background-color:#F3F3F3;
	border-bottom: 1px solid #7EB6E2;
	color: #1F5988;
  font-size: 15px;
  height:500px !important;
  width:530px !important;
  
  text-align:center !important
}
#popup_CM3
{
	z-index:6000 !important;
}
			</style>
			
			<script>
				function salir()
				{
					$("#contenido").load("query.php", 
						{tipo:3} 
							,function(){	
							}
					);
				}
				</script>
		</head>
	<body>
		<div class="img_left"><a target=BLANK_ href="http://www.chilemap.cl"><img src="../images/logo_places.png"></a></div>
			<div class="img_right"><img src="http://locate.chilemap.cl/img_cli/<?=$empresa[2]?>"></div>
			
		<div id="contenido">
			
<div id="buscador">
<?php
include("header.php");
?>
</div>
<br>
<div id="buscador">
<fieldset>
<legend>Filtro Item</legend>

<table id=table_filtro>

            <tr>
            	<td>
                    
                </td>
                <td>
                    Nombre
                </td>
                
								<td>
                    Estado
                </td>
                
               
            </tr>
		<tr>
			<td><a href="javascript:nuevoItem();"><img src="img/add.png"></a></td>
			
			<td><input class="input_filtro" autocomplete=off type="text" name="nom_item" id="nom_item" maxlength="12"/> </td>
			
		<td><select id="est_fil" name="est_fil">
				<option value=10 selected>Todos</option>
						<option value=0 >Activo</option>
						<option value=1 >Inactivo</option>
						
					</select> </td>
		
		<td><input type="submit" value="Buscar" ONCLICK="javascript:loadItem();" /></td>
	</tr>	
</table>
<input type="hidden" name="formSent" value="1"/>

</fieldset>
</div>
<div id="spacer" style="height:50px"></div>
		<div id="resultado">	

	</div>
	<div id="grilla" title="Mapa">
  <div id="mapa">
  </div>
	</div>
	<div id="grilla2" title="Registros">

	</div>
<script>
	var CM_farma_turno=false;
	loadItem();
	//init("mapa");
	</script>
	
	
	</body>
</html>
