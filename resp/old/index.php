<?php
include("funciones.php");
require_once("Mobile_Detect.php");
$est_sesion=estado_sesion();
$pais=$_SESSION['pais_locate'];
if($pais=="")
$pais="CHL";
$lon="-70.656235";
$lat="-33.458943";
$zoom=10;
if(strtolower($pais)=="per")
{
	$lon="-77.041752";
	$lat="-12.052364";	
	$zoom=13;
}
$detect = new Mobile_Detect;
?>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <title>Locate by Chilemap.cl</title>
        <link rel="apple-touch-icon-precomposed" href="iconos/locate_icon.png"/>
        <link rel="shortcut icon" sizes="56x56" href="iconos/locate_icon.png">
        <link rel="shortcut icon" href="images/point.png">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
				<meta name="author" content="Chilemap.cl" />     
        <link rel="stylesheet" href="css/style_mob.css">        
         <link rel="stylesheet" href="css/themes/theme.css" />
         
  			<link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css" />
        <link rel="stylesheet" href="css/jquery.mobile.structure-1.4.0-rc.1.min.css" />
        <script src="js/jquery-1.10.2.min.js"></script> 
				<script src="js/jquery.mobile-1.4.0-rc.1.min.js"></script> 
				
        <link rel="stylesheet" href="css/style.mobile.css" type="text/css">
        <link rel="stylesheet" href="css/style.mobile-jq.css" type="text/css">
   			<script type="text/javascript" src="http://www.chilemap.cl/OpenLayers/OpenLayers.js"></script>        
   			
        <script src="js/funciones_api_mobile_basica.js"></script>
        <script type="text/javascript" src="js/funciones.js"></script>

<link rel="stylesheet" type="text/css" href="popmobile/style/addtohomescreen.css">
<script src="popmobile/src/addtohomescreen.js"></script>
        
    </head>
    <body onload=inicio();>
		
    <div data-role="page" id="mappage" data-theme="a">
			 <div data-role="panel" id="mypanel" data-theme="a" style="z-index:99999;" data-position="left" data-display="overlay">
    	  	<a href="#mypanel" data-iconpos="top" data-rel="close" data-icon="delete" data-role="button" data-iconpos="notext"></a>
    			
			  	  <div data-role="collapsible" data-collapsed="false" >
    				  <h3><span id="usuario_nom">Menú</span></h3>
							<ul data-role="listview" data-inset="false" id="list_menu">
								<?php
								if($est_sesion==0)
								{
									?>
									
									<li><a href="javascript:cerrarSesion();" data-rel="dialog" data-transition="pop" >Cerrar Sesion</a></li>    	  	  		
									
									<?php
								}else
								{
									?>
									<li><a href="#m_sesion" data-rel="dialog" data-transition="pop" >Inicio Sesion</a></li>    	  	  		
									<?php
								}
								?>
			  	    </ul>			    	
			  	  </div>
			  	  
			  	  <?php
			  	  if($_SESSION['app']==4 or $_SESSION['app']==5)
			  	  {
			  	  	
			  	  	
			  	  	?>
			  	  	

			  	  	<div data-role="collapsible" data-collapsed="false" id="colap_list_agenda">
			  	  		
    					  <h3><span id="usuario_nom">Agenda del día</span></h3>    					
								
								<ul data-role="listview"  data-theme="b"  data-filter="true" data-filter-placeholder="Buscar agenda..." data-inset="false" id="list_registros_agenda">	
			  	  	  </ul>			    	
			  	  	</div>			  	  
			  	  <?php
			  		}
			  	  ?>
			  	  <?php
			  	  if($est_sesion==0)
			  	  {
			  	  	?>
			  	  	<div data-role="collapsible" data-collapsed="false" id="colap_list">
    					  <h3><span id="usuario_nom">Ultimos Registros</span></h3>    					
								<ul data-role="listview" data-theme="b" data-inset="false" data-scroll="true" id="list_registros">		
										
			  	  	  </ul>			    	
			  	  	</div>			  	  
			  	  <?php
			  		}
			  	  ?>
				
				</div><!-- /panel -->	
   		<div data-role="header" >
   			 <a href="#mypanel" data-role="button" data-icon="bars" data-iconpos="notext" data-theme="b" data-iconshadow="false" data-inline="true">Bars</a>
      		<h1><img src="images/logo_places3.png"></h1>      		 
      		<a href="javascript:location.reload();" data-role="button" data-icon="refresh" data-iconpos="notext" data-theme="b" data-iconshadow="false" data-inline="true">Bars</a>
   		</div>    		
   	  <div data-role="content" id="contenido" >
   	  	
    	  	<div data-role="popup" id="myPopup">
							<p>This is a completely basic popup, no options set.						</p>
					</div>
					<div class="ui-bar ui-bar-a" style="text-align:right;">
						<?php
						
						$usuario="";
						if($est_sesion==0)
						{
							$usuario="bienvenido ".$_SESSION['nombre'];
						}
						?>
						<h3 id="text_supIz" ><?=ucwords($usuario)?></h3>
    				<h3 id="text_sup" >...</h3>
					</div>
    	    <div id="map"></div>            
    	</div>
    	
    	<div data-role="footer" data-position="fixed">
				<div data-role="navbar">
    			<ul id="list_inf">
        	<?php
						if($est_sesion==0)
						{
						?>
            		<li><a href="javascript:check();"><img src="images/c3.png" width=25px height=25px></a></li>
            		<li><a href="javascript:current2();"><img src="images/c4.png" width=25px height=25px></a></a></li>
          	<?php
        		}else
        		{
        			?>
        			<li><a href="#m_sesion" data-rel="dialog" data-transition="pop" id="link_ses">Inicio Sesion</a></li>
        			<?php
        		}
          	?>  			
	        </ul>
		    </div><!-- /navbar -->
						<h4 id="text_inf"> @2014 Chilemap.cl  
							<?php
							if($detect->isAndroidOS())
							{
								?>
								 <img onclick="javascript:window.location.href='http://locate.chilemap.cl/app/Locate.apk';" src="iconos/android.png" width="20px" height=20px>
								<?php
							}
							?>
							
							</h4>
				
    	</div>
    	  
 </div>
 
 <div data-role="page" id="m_sesion" data-theme="a">
			<?php
			include("mod_sesion.php");
			?>				
 </div><!-- /page SESION -->
 <div data-role="page" id="m_empresa" data-theme="a">
			<div data-role="header" >   			 
      		<h1><img src="images/logo_places3.png"></h1>      		 
   		</div>  

				<div data-role="content" data-theme="b" id="cont_registro">
					<h4>Empresas</h4>
					
    			<ul data-role="listview" data-filter="true" data-filter-placeholder="Buscar Empresa..." data-inset="true" id="lsta_empresa">	
					</ul>
				</div><!-- /content -->
				<br><br>
 </div><!-- /page SESION -->			
 <div data-role="page" id="m_checkout" data-theme="a">
			<div data-role="header" >   			 
      		<h1>Check-out <span id="t_id_empresa"> </span>- <span id="t_empresa"></span></h1>  
      		
   		</div>  
				<div data-role="content" data-theme="b" id="cont_registro">
					<label for="textarea"><h2>Detalles</h2></label>
					<textarea cols="20" rows="10" maxlength=500 name="text_detalle" id="text_detalle"></textarea>
					<div id="msg_error_check" class="msg_error"></div>
					<input type="button" onclick="checkOut3();" value="Check-out">
    			  		
				</div><!-- /content -->
				
 </div><!-- /page SESION -->				
 <div data-role="page" id="m_checkout2" data-theme="a">
			<div data-role="header" >   			 
      		<h1>Check-out <span id="t_id_empresa2"> </span>- <span id="t_empresa2"></span></h1>  
      		
   		</div>  
				<div data-role="content" data-theme="b" id="cont_registro">
					 <form enctype="multipart/form-data" class="formulario">
					<label for="file">File:</label>
					<input type="file" name="i_file" id="i_file" value="">
					<label for="file">File:</label>
					<input type="file" name="i_file2" id="i_file2" value="">
					<label for="textarea"><h2>Detalles</h2></label>
					<textarea cols="20" rows="10" maxlength=500 name="text_detalle2" id="text_detalle2"></textarea>
					<div id="msg_error_check2" class="msg_error"></div>
					<input type="button" id="but_check" value="Check-out">
				</form>
    			  		
				</div><!-- /content -->
				
 </div><!-- /page SESION -->		
 <?php
  if($_SESSION['app']==5)
  {
  	$productos=getProductos($_SESSION['id_cliente'],0);
  	?>		
  <div data-role="page" id="m_checkout5" data-theme="a">
			<div data-role="header" >   			 
      		<h1>Check-out <span id="t_id_empresa5"> </span>- <span id="t_empresa5"></span></h1>  
      		
   		</div>  

				<div data-role="content" data-theme="b" id="cont_registro">
					 <form enctype="multipart/form-data" id="form2" name="form2" class="formulario">
					<label for="file">Documento 1:</label>
					<input type="file" name="i_filea" id="i_filea" value="">
					<label for="textarea"><h4>Agregar Comentario</h4></label>
					<textarea cols="20" rows="10" maxlength=500 name="text_detalle_a" id="text_detalle_a"></textarea>
					<label for="file">Documento 2:</label>
					<input type="file" name="i_fileb" id="i_fileb" value="">
					<label for="textarea"><h4>Agregar Comentario</h4></label>
					<textarea cols="20" rows="10" maxlength=500 name="text_detalle_b" id="text_detalle_b"></textarea>
					<div id="msg_error_check5" class="msg_error"></div>
					<div data-role="collapsible" data-collapsed="false" id="colap_list_agenda">
			  	  		
    					  <h3>Inventario</span></h3>    					
		<table data-role="table" id="table-column-toggle" data-mode="columntoggle" class="ui-responsive table-stroke">
     <thead>
       <tr>
         <th data-priority="2">Producto</th>         
         <th data-priority="3">Inicio</th>         
         <th data-priority="4">Fin</th>   
         <th data-priority="5">Venta</th>
       </tr>
     </thead>
     <tbody>
     	<?php
     	$i=0;
     	foreach($productos as $produc)
     	{
     	?>
       <tr>         
         <td><?=$produc[2]?><input type="hidden" id="pro_<?=$i?>" name="tot_pro" value="<?=$produc[0]?>"></td>
         <td><input type="number" id="pr_<?=$i?>" name="pr_<?=$i?>" data-clear-btn="true" pattern="[0-9]*" value="0"></td>
         <td><input type="number" id="prf_<?=$i?>" name="prf_<?=$i?>" data-clear-btn="true" pattern="[0-9]*" value="0"></td>
         <td><input type="number" id="prv_<?=$i?>" name="prv_<?=$i?>" data-clear-btn="true" pattern="[0-9]*" value="0"></td>
       </tr>
       <?php
       $i++;
     		}
       ?>
       
     </tbody>
   </table>
   <input type="hidden" id="tot_pro" name="tot_pro" value="<?=$i?>">
					<label for="textarea"><h4>Comentario</h4></label>
					<textarea cols="20" rows="10" maxlength=500 name="text_detalle_c" id="text_detalle_c"></textarea>	
			  	  	</div>			  	  
					<input type="button" id="but_check2" value="Check-out">
				</form>
    			  		
				</div><!-- /content -->
<?php
	}
?>				
 </div><!-- /page SESION -->	
 <div data-role="page" id="m_det_checkout" data-theme="a">
			<div data-role="header" >   			 
      		<h1>Detalle <span id="t_empresa"></span></h1>  
      		
   		</div>  
				<div data-role="content" data-theme="b" id="cont_registro">
					<label for="textarea"><h5>Check-In: <span id="fi_empresa"></span></h5> </label>
					<label for="textarea"><h5>Check-out: <span id="ft_empresa"></span></h5></label>
					<label for="textarea"><h5>Detalle: <span id="dt_empresa"></span></h5></label>
					<label for="textarea"><h5>Documentos: <span id="doc_empresa"></span></h5></label>
					<label for="textarea"><h5>Otros: <span id="dt_otros"></span></h5></label>
				  
				</div><!-- /content -->
				
 </div><!-- /page SESION -->			
 <div data-role="page" id="m_det_agenda" data-theme="a">
			<div data-role="header" >   			 
      		<h1>Detalle <span id="t_agenda"></span></h1>  
      		
   		</div>  
   		
				<div data-role="content" data-theme="b" id="cont_agenda">
					
					<label for="textarea" class="txt_pop"><h4>Visita : <span id="ag_visita"></span></h4> </label>
					<label for="textarea" class="txt_pop"><h4>Direccion: <span id="ag_direccion"></span></h4></label>
					<label for="textarea" class="txt_pop"><h4>Detalle: <span id="ag_detalle"></span></h4></label>
					<label for="textarea" class="txt_pop"><h4>Otros: <span id="ag_otro"></span></h4></label>
					
					
			
				</div><!-- /content -->
			
 </div><!-- /page SESION -->			
      <script>
			
				function inicio()
				{	
   				init(<?=$lon?>,<?=$lat?>,<?=$zoom?>);
   				<?php
						if($est_sesion==0)
						{
						?>
   						loadRegistros();
   						loadAgendas();
   					<?php
   					}else
   					{
   						?>
   			
   						   						 
   						 //$("#link_ses").click();
   						 $.mobile.changePage('#m_sesion', 'pop', true, true);
   						 //$.mobile.changePage('#m_sesion',  {transition: 'pop', role: 'dialog'});
   							//$.mobile.changePage("#m_sesion", { transition: "pop",role: "dialog" })
   						<?php
   					}
   					?>
   				//current2();
   				addToHomescreen();
				}		
			</script>

<div id='output'></div>
    </body>
</html>
