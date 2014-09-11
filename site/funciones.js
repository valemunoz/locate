function loadLista()
	{
		
		var id_usuario=document.getElementById("id").value;
		var desde=document.getElementById("desde").value;
		var hasta=document.getElementById("hasta").value;
		$("#resultado").html("<div><img src='img/load.GIF'></div>");
		$("#resultado").load("query.php", 
		{tip:1,id:id_usuario,desde:desde,hasta:hasta} 
	,function(){	
	}
	);
}

function OpenModal()
{
		$( "#grilla" ).dialog( "open" );
}
function OpenModal2()
{
		$( "#grilla2" ).dialog( "open" );
}
function mapaTrabajadores()
{
	
	
	limpiarMapa();
	
	$("#output").load("query.php", 
		{tipo:6} 
	,function(){	
		 OpenModalMapa();
		 //verMarcadores();
	}
	);
}

function detalleRegistro(registro)
{
	$("#grilla2").load("query.php", 
		{tipo:7,registro:registro} 
	,function(){	
		 OpenModal2();
		 //verMarcadores();
	}
	);
}
/*AGENDA*/
function loadAgendas()
{
	var id_us=document.getElementById("id").value;
		var desde=document.getElementById("desde").value;
		var hasta=document.getElementById("hasta").value;
		var estado=document.getElementById("est_fil").value;
		var visita=document.getElementById("visit_fil").value;
		$("#resultado").html("<div><img src='img/load.GIF'></div>");
		$("#resultado").load("query.php", 
		{tip:8,id:id_us,desde:desde,hasta:hasta,estado:estado,visita:visita} 
	,function(){	
	}
	);
}
function nuevaAgenda()
{
	$("#grilla2").load("query.php", 
		{tipo:9} 
	,function(){	
		 OpenModal2();
		 
	}
	);
}
function validaAgenda(tip_agenda)
{
	var us_agenda=document.getElementById("us_agenda").value;
	var fec_agenda=document.getElementById("fec_agenda").value;
	var hora_agenda=document.getElementById("hora").value;
	var visita_agenda=document.getElementById("visita_agenda").value;
	var motivo=0;
	try
	{
		motivo=document.getElementById("motivo").value;
	}catch(e){}
	var detalle=$.trim(document.getElementById("detalle").value);

	if($.trim(us_agenda)=="" || $.trim(fec_agenda)=="" || $.trim(visita_agenda)=="" )
	{
		$("#msg_agenda").html("Todos los campos son obligatorios.");
	}else
	{
			$("#msg_agenda").load("query.php", 
		{tipo:10,us:us_agenda, fec:fec_agenda,visita:visita_agenda,motivo:motivo,det:detalle,hora:hora_agenda} 
			,function(){
				if(tip_agenda==1)
				{
					loadAgendas();
				}
				if(tip_agenda==2)
					{
						loadAgendas();
						nuevaAgenda();
						
					}
			}
		);
	}
		
}
function CloseModal()
{
		$( "#grilla2" ).dialog( "close" );
}
function loadDetagenda(id_agenda)
{
	$("#grilla2").load("query.php", 
		{tipo:11,agenda:id_agenda} 
	,function(){	
		 OpenModal2();
		 
	}
	);
}
function validaAgendaUP(agenda)
{
	var us_agenda=document.getElementById("us_agenda").value;
	var fec_agenda=document.getElementById("fec_agenda").value;
	var hora=document.getElementById("hora").value;
	var visita_agenda=document.getElementById("visita_agenda").value;	
	var motivo=0;
	try
	{
		motivo=document.getElementById("motivo").value;
	}catch(e){}
	
	var detalle=$.trim(document.getElementById("detalle").value);
	var estado=$.trim(document.getElementById("est_agenda").value);
	if($.trim(us_agenda)=="" || $.trim(fec_agenda)=="" || $.trim(visita_agenda)=="" || $.trim(motivo)=="" || $.trim(detalle)=="")
	{
		$("#msg_agenda").html("Todos los campos son obligatorios.");
	}else
	{
			$("#msg_agenda").load("query.php", 
		{tipo:12,us:us_agenda, fec:fec_agenda,visita:visita_agenda,motivo:motivo,det:detalle,agenda:agenda,estado:estado,hora:hora} 
			,function(){
				loadAgendas();}
		);
	}
		
}

function loadVisitas()
{
	var comuna="";
	try
	{
		comuna=document.getElementById("com_agenda").value;
	}catch(e){}
	var bus_agenda=document.getElementById("bus_agenda").value;
	var busqueda=1;
	if(document.getElementById("opc_agenda2").checked)
	{
	  busqueda=2;
	}
	
$("#visita_cont").load("query.php", 
		{tipo:13,comuna:comuna,query:bus_agenda,busqueda:busqueda} 
			,function(){
			}
		);

}

/*Item*/

function loadItem()
{
		var nombre=document.getElementById("nom_item").value;
		var estado=document.getElementById("est_fil").value;
		
		$("#resultado").html("<div><img src='img/load.GIF'></div>");
		$("#resultado").load("query.php", 
		{tip:14,nom:nombre,estado:estado} 
	,function(){	
	}
	);
}
function updateItem(id_item,est)
{
		
		$("#resultado").html("<div><img src='img/load.GIF'></div>");
		$("#resultado").load("query.php", 
		{tip:15,id:id_item,estado:est} 
	,function(){	
		loadItem();
	}
	);
}
function nuevoItem()
{
	$("#grilla2").load("query.php", 
		{tipo:16} 
	,function(){	
		 OpenModal2();
		 
	}
	);
}
function isImage(extension)
{
	
    switch(extension.toLowerCase()) 
    {
        case 'jpg': case 'jpeg':
            return true;
        break;
        default:
            return false;
        break;
    }
}
function saveItem()
{
	var nombre=document.getElementById("nom_input").value;
	var valor=document.getElementById("valor_input").value;
	var paso=false;
	if (typeof $("#i_file")[0].files[0] != "undefined")
  {
  	var file = $("#i_file")[0].files[0];
    //obtenemos el nombre del archivo
    var fileName = file.name;
    var ext=fileName.split(".");
    ext=ext[1];
    sizea=file.size;    
    var formData = new FormData($(".formulario")[0]);
    if(isImage(ext) && sizea <= 4000000)
    {
    	paso=true;
    }
  }else
  	{
  		paso=true;
  	}
  	if(valor!="" && !$.isNumeric(valor))
  	{
  		paso=false;
  	}
	  if(nombre!="" && paso)
  {
  	 $("#resultado").html("<div><img src='img/load.GIF'></div>");
										$("#resultado").load("query.php", 
										{tipo:17,nom:nombre, valor:valor} 
									,function(){
											if (typeof $("#i_file")[0].files[0] != "undefined")
  										{
  											CloseModal();
  											$("#resultado").html("<div><img src='img/load.GIF'></div>");
  										  var formData = new FormData($(".formulario")[0]);
  										  if(isImage(ext) && sizea <= 4000000)
  										  {
  										  			$.ajax({
  										      		    
  										      		    url: 'upload.php?nom='+nombre+'',
  										      		    type: 'POST',
  										      		    // Form data
  										      		    //datos del formulario
  										      		    data: formData,
  										      		    //necesario para subir archivos via ajax
  										      		    cache: false,
  										      		    contentType: false,
  										      		    processData: false,
  										      		    //mientras enviamos el archivo
  										      		    beforeSend: function(){
  										      		       //showMensaje("<img src='images/load_central.gif'>");   
  										      		    },
  										      		    //una vez finalizado correctamente
  										      		    success: function(data){
  										      		      CloseModal();
																			loadItem();
  										      		      
  										      		    },
  										      		    //si ha ocurrido un error
  										      		    error: function(){
  										      		    	//closeModalWeb();
  										      		      //showMensajeestatico("<div id=msg_alert>Error al subir archivo.</div>");
  										      		      alert("Error al subir la imagen");
  										      		       
  										      		    }
  										      		});
  										  	
  										  }else
  										  	{
  										  		alert("Formato archivo no valido");
  										  	}
  										}	else
  											{
  											  CloseModal();
													loadItem();
  											}
										
									}
									);
									
									
  }else
  	{
  		//alert("Debe ingresar un nombre para el Item, El valor debe ser numerico, la imagen adjunta debe ser JPG y pesar menos de 4 MG");
  		$("#msg_agenda").html("Debe ingresar un nombre para el Item<br> El valor debe ser numerico<br> La imagen adjunta debe ser JPG y pesar menos de 4 MG");
  	}

  

	
}

function upItem(name_file,id_producto)
{
	var nombre=document.getElementById("nom_input").value;
	var valor=document.getElementById("valor_input").value;
	var paso=false;
	if (typeof $("#i_file")[0].files[0] != "undefined")
  {
  	var file = $("#i_file")[0].files[0];
    //obtenemos el nombre del archivo
    var fileName = file.name;
    var ext=fileName.split(".");
    ext=ext[1];
    sizea=file.size;    
    var formData = new FormData($(".formulario")[0]);
    if(isImage(ext) && sizea <= 4000000)
    {
    	paso=true;
    }
  }else
  	{
  		paso=true;
  	}
  	if(valor!="" && !$.isNumeric(valor))
  	{
  		paso=false;
  	}
	  if(nombre!="" && paso)
  {
  	 $("#resultado").html("<div><img src='img/load.GIF'></div>");
										$("#resultado").load("query.php", 
										{tipo:19,nom:nombre, valor:valor, id_producto:id_producto} 
									,function(){
											if (typeof $("#i_file")[0].files[0] != "undefined")
  										{
  											CloseModal();
  											$("#resultado").html("<div><img src='img/load.GIF'></div>");
  											
  										  var formData = new FormData($(".formulario")[0]);
  										  if(isImage(ext) && sizea <= 4000000)
  										  {
  										  			$.ajax({
  										      		    
  										      		    url: 'uploadb.php?nom='+name_file+'&prod='+id_producto+'',
  										      		    type: 'POST',
  										      		    // Form data
  										      		    //datos del formulario
  										      		    data: formData,
  										      		    //necesario para subir archivos via ajax
  										      		    cache: false,
  										      		    contentType: false,
  										      		    processData: false,
  										      		    //mientras enviamos el archivo
  										      		    beforeSend: function(){
  										      		       //showMensaje("<img src='images/load_central.gif'>");   
  										      		    },
  										      		    //una vez finalizado correctamente
  										      		    success: function(data){
  										      		      CloseModal();
																			loadItem();
  										      		      
  										      		    },
  										      		    //si ha ocurrido un error
  										      		    error: function(){
  										      		    	//closeModalWeb();
  										      		      //showMensajeestatico("<div id=msg_alert>Error al subir archivo.</div>");
  										      		      alert("Error al subir la imagen");
  										      		       
  										      		    }
  										      		});
  										  	
  										  }else
  										  	{
  										  		alert("Formato archivo no valido");
  										  	}
  										}	else
  											{
  											  CloseModal();
													loadItem();
  											}
										
									}
									);
									
									
  }else
  	{
  		//alert("Debe ingresar un nombre para el Item, El valor debe ser numerico, la imagen adjunta debe ser JPG y pesar menos de 4 MG");
  		$("#msg_agenda").html("Debe ingresar un nombre para el Item<br> El valor debe ser numerico<br> La imagen adjunta debe ser JPG y pesar menos de 4 MG");
  	}

  

	
}
function vistaPrevProd(id_producto)
{
	$("#grilla2").load("query.php", 
		{tipo:18, id_pro:id_producto} 
	,function(){	
		 OpenModal2();
		 
	}
	);
}
/*fin item*/


/*Inicio lugares*/
function filtrar_em()
{
	
	
	var nombre=$.trim(document.getElementById("nom_em").value);
	var estado=$.trim(document.getElementById("em_estado").value);
	$("#result2").load("qr_lugares.php", 
						{tipo:1, nombre:nombre,estado:estado} 
							,function(){
									
							}
	);
}	
function nuevaEmpresa()
{
	$("#grilla_mapa").load("qr_lugares.php", 
						{tipo:2} 
							,function(){
									OpenModalMapa();
							}
	);
	
}
function saveEmpresa()
{
		var nombre=$.trim(document.getElementById("nombre_em").value);
		
		var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			var region=$.trim(document.getElementById("reg_em").value);
	var comuna=$.trim(document.getElementById("com_em").value);
	var latitud=$.trim(document.getElementById("lat_em").value);
	
	
	var longitud=$.trim(document.getElementById("lon_em").value);
	var msg="";
	var valida=true;

	if($.trim(nombre)=="" || $.trim(latitud)=="" ||$.trim(longitud)=="")
	{		
		valida=false;
		msg="<strong>Nombre, latitud y longitud son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#msg_error_add").load("qr_lugares.php", 
							{tipo:5, nombre:nombre,calle:calle, numero:numero, region:region,comuna:comuna,latitud:latitud,longitud:longitud} 
								,function(){
									CloseModalMapa();
										filtrar_em();
								}
		);
	}
}

function BuscarGeo()
{
	var valida=true;
	var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			
	var comuna=$.trim(document.getElementById("com_em").value);
	if($.trim(calle)=="" || $.trim(numero)=="" ||$.trim(comuna)=="")
	{		
		valida=false;
		msg="<strong>Calle Numero y Comuna son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$( "#msg_error_add" ).html("Buscando...");
	$("#msg_error_add").load("qr_lugares.php", 
							{tipo:4, calle:calle, numero:numero,comuna:comuna} 
								,function(){
						
								}
		);
	}
}
function loadEmpresa(id_empresa)
{
	$("#grilla_def").load("qr_lugares.php", 
							{tipo:6, empresa:id_empresa} 
								,function(){
						OpenModalReg();
								}
		);
}

function updateEmpresa(empresa)
{
		var nombre=$.trim(document.getElementById("nombre_em").value);
		
		
		var calle=$.trim(document.getElementById("calle_em").value);
		var numero=$.trim(document.getElementById("num_em").value);
			var region=$.trim(document.getElementById("reg_em").value);
	var comuna=$.trim(document.getElementById("com_em").value);
	var latitud=$.trim(document.getElementById("lat_em").value);
	
	
	var longitud=$.trim(document.getElementById("lon_em").value);
	var msg="";
	var valida=true;

	if($.trim(nombre)=="" || $.trim(latitud)=="" ||$.trim(longitud)=="")
	{		
		valida=false;
		msg="<strong>Nombre, latitud y longitud son campos son obligatorios.</strong><br>";
	}
	
	if(!valida)
	{
		
		$( "#msg_error_add" ).html(msg);
	}else
	{
		$("#output").load("qr_lugares.php", 
							{tipo:7, empresa:empresa,nombre:nombre,calle:calle, numero:numero, region:region,comuna:comuna,latitud:latitud,longitud:longitud} 
								,function(){
									CloseModalReg();
										filtrar_em();
								}
		);
	}
}

function loadComunas()
{
	var region=document.getElementById("reg_em").value;
	$("#com_text").load("qr_lugares.php", 
			{tipo:3, region:region} 
				,function(){	
					
				}
		);
}
function verMapa(lat_geo,lon_geo)
{
	
	limpiarMapa();	
	limpiarPuntosDrag();		
	addMarcadorVector(lon_geo,lat_geo,'','img/place.png',30,30);
	moverCentro2(lon_geo,lat_geo,18);
	activarDrag();	
	OpenModal();
	
	
	
}
function OpenModalMapa()
{
		$( "#grilla_mapa" ).dialog( "open" );
		
}

function OpenModalReg()
{
		$( "#grilla_def" ).dialog( "open" );
}
function CloseModalReg()
{
		$( "#grilla_def" ).dialog( "close" );
}
function CloseModalMapa()
{
		$( "#grilla_mapa" ).dialog( "close" );
}
var LC_lat="lat_em";
var LC_lon="lon_em";
function moveDrag(feature, pixel)
{
	
    		lon=feature.geometry['x'];
    		lat=feature.geometry['y'];
    		lonlat=new OpenLayers.LonLat(lon,lat).transform(
        new OpenLayers.Projection("EPSG:900913"), // de WGS 1984
        new OpenLayers.Projection("EPSG:4326") // a Proyección Esférica Mercator
  			);
    		
         document.getElementById(LC_lon).value=lonlat.lon;
         document.getElementById(LC_lat).value=lonlat.lat;
  
}
function verMapa2()
{
	lat_geo=document.getElementById('lat_em').value;
	lon_geo=document.getElementById('lon_em').value;
	limpiarMapa();		
	limpiarPuntosDrag();		
	//alert(""+lon_geo+","+lat_geo+"");
	addMarcadorVector(lon_geo,lat_geo,'','img/place.png',30,30);
	//moverCentro(lat_geo,lon_geo,13);
	moverCentro2(lon_geo,lat_geo,17);
	activarDrag();	
	OpenModal();	
}
function upEstadoEmpresa(estado,empresa)
{
	$("#output").load("qr_lugares.php", 
							{tipo:8, empresa:empresa,estado:estado} 
								,function(){
								
										filtrar_em();
								}
		);
}
function CloseModal3()
{
		$( "#grilla" ).dialog( "close" );
}
/*FIN LUGARES EMPRESA*/


