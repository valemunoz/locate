var CM_farma_turno=false;
var MX_tipo_busqueda=7;
//var MX_path="http://locate.chilemap.cl/app";
var MX_path="http://localhost/demos_moxup/locate";
function validarEmail( email ) {
	  var valido=true;
    expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if ( !expr.test(email) )
        valido=false;
        
   return valido;     
}
function mensaje(CM_mensaje)
{

	$( "#myPopup" ).html("<p>"+CM_mensaje+"</p>");
    $("#myPopup").popup("open");

}

function check()
{
	loadEspera("Localizando..");
	
	navigator.geolocation.getCurrentPosition (foundlocation,noLocation);
	

}
function foundlocation(pos)
	{
		
		
  	var lat = pos.coords.latitude;
  	var lng = pos.coords.longitude;
  	var accu=pos.coords.accuracy.toFixed(2);
  	
  	if(accu<=400)
  	{
  		deleteTodos();  	
  		$("#lsta_empresa").load("query.php", 
				{tipo:MX_tipo_busqueda,lat:lat,lon:lng,accu:accu,usuario:34} 
					,function(){
						loadRegistros();
						hideLoad();
					}
			);
		}else
		{
			hideLoad();
			mensaje("Ha ocurrido un problema con su GPS, por favor reviselo he intentelo nuevamente.");
			
		}
  	
	}
function noLocation()
{
	mensaje("Se produjo un error en la lectura de su posicion, por favor intentelo nuevamente.");
	hideLoad();
}
function current2()
{
	navigator.geolocation.getCurrentPosition (function (pos)
	{
		loadEspera("Localizando..");
  	MX_lat = pos.coords.latitude;
  	MX_lon = pos.coords.longitude;
  	MX_accuracy=pos.coords.accuracy.toFixed(2);
  	//$("#text_sup").html("Precis&iacute;on GPS: "+MX_accuracy+"mts");
  	$("#output").load("query.php", 
			{tipo:2,lat:MX_lat,lon:MX_lon,accu:MX_accuracy} 
				,function(){	
					hideLoad();
				}
		);
  	CM_vector_current.removeAllFeatures();   	
  	DibujarCirculoAdap('#3388CC','#3388CC',MX_accuracy,MX_lon,MX_lat,0.5);
  	DibujarCirculoAdap('red','red',2,MX_lon,MX_lat,1);
  	map.zoomToExtent(CM_vector_current.getDataExtent());
  	
  	
	});
}

function incioSesion()
{
	
	var clave=$.trim(document.getElementById("clave_ses").value);
	var mail=document.getElementById("mail_ses").value;
	
	var msg="";
	var valida=true;
	if($.trim(clave)=="" || $.trim(mail)=="")
	{		
		valida=false;
		msg="<strong>Todos los campos son obligatorios.</strong><br>";
	}
	if(!validarEmail(mail))
	{		
		msg=""+msg+" <strong>E-mail debe tener formato correcto.</strong><br>";
		valida=false;
	}
	if(!valida)
	{
		$("#msg_error_ses").load("query.php", 
			{tipo:6, clave:""+clave+"", mail:""+mail+""} 
				,function(){	
					$( "#msg_error_ses" ).html(msg);
				}
		);
		
	}else
	{
		
		
		$("#msg_error_ses").load("query.php", 
			{tipo:3, clave:""+clave+"", mail:""+mail+""} 
				,function(){	
				}
		);
		
	}
	
}
function cerrarSesion()
{
	//alert("cerrar");
	/*$("#text_supIz").html("");
	$("#list_menu").html('<li><a href="#m_sesion" data-rel="dialog" data-transition="pop" >Inicio Sesion</a></li>');
	$('#list_menu').listview('refresh');*/
	$("#msg_error_ses").load("query.php", 
			{tipo:4} 
				,function(){	
					window.location.href=MX_path;
				}
		);
		//$("#mypanel").panel( "close" );
		
}
function loadRegistros()
{
	$("#list_registros").load("query.php", 
			{tipo:5} 
				,function(){	
					
				}
	);
}

function checkEmpresa(id_empresa,lat,lon,acu,distancia)
{
	
	$("#m_empresa").dialog( "close" );
	$("#output").load("query.php", 
			{tipo:8, id_empresa:id_empresa, lat:lat, lon:lon , accu:acu,distancia:distancia } 
				,function(){	
					loadRegistros();
					
				}
	);
}
function loadEspera(LT_texto)
{
		$.mobile.loading( "hide" );
		$.mobile.loading( 'show', {
			text: LT_texto,
			textVisible: true,
			theme: 'b',
			html: ""
		});
}
function hideLoad()
{
	$.mobile.loading( "hide" );
}

function checkOut(registro,detalle,img1,img2)
{
	//alert(registro);
	loadEspera("Cargando..");
	$("#output").load("query.php", 
			{tipo:9, registro:registro,detalle:detalle,img:img1,img2:img2} 
				,function(){	
					loadRegistros();
					$("#mypanel").panel( "close" );
					mensaje("Check-Out realizado");
					hideLoad();
					loadAgendas();
				}
	);
}

function checkOut2(registro,empresa)
{
	$("#t_id_empresa").html(registro);
	$("#t_empresa").html(empresa);
	$.mobile.changePage('#m_checkout',  {transition: 'pop', role: 'dialog'});
	
}
function checkOut3()
{

	var detalle=$.trim(document.getElementById("text_detalle").value);
	var registro=$.trim($("#t_id_empresa").html());
	if(registro=="")
	{
		$("#msg_error_check").html("Ocurrio un problema, por favor intentelo nuevamente.");
	}else
	{
		$("#m_checkout").dialog( "close" );
		checkOut(registro,detalle,'','');
	}
	
}
function checkOut_5(registro,detalle,img1,img2,detalle2,detalle3,prod_id,prod_ini,prod_fin,prod_val)
{
	//alert(registro);
	loadEspera("Cargando..");
	$("#output").load("query.php", 
			{tipo:12, registro:registro,detalle:detalle,img:img1,img2:img2,detalle2:detalle2,detalle3:detalle3,prod_id:prod_id,prod_ini:prod_ini,prod_fin:prod_fin,prod_val:prod_val} 
				,function(){	
					loadRegistros();
					$("#mypanel").panel( "close" );
					mensaje("Check-Out realizado");
					hideLoad();
					loadAgendas();
				}
	);
}

function verDetalle(registro,fi,ft,det,id_reg)
{
	
	loadEspera("Cargando..");
	$("#fi_empresa").html(fi);
	
	$("#ft_empresa").html(ft);
	$("#dt_empresa").html(det);
	$("#t_empresa").html(registro);
	$("#output").load("query.php", 
			{tipo:10, registro:id_reg} 
				,function(){	
					hideLoad();
					$.mobile.changePage('#m_det_checkout',  {transition: 'pop', role: 'dialog'});
					
				}
	);
	
	
}
function checkOut3b(registro,empresa)
{
	
	$("#t_id_empresa2").html(registro);
	$("#t_empresa2").html(empresa);
	$.mobile.changePage('#m_checkout2',  {transition: 'pop', role: 'dialog'});
$(document).ready(function(){
 
    var fileExtension = "";
    //función que observa los cambios del campo file y obtiene información
    $('#i_file').change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#i_file")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
        //alert("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</span>");
        
    });
 
    //al enviar el formulario
    $('#but_check').click(function(){
        //información del formulario
        var formData = new FormData($(".formulario")[0]);
        var message = "";    
        var detalle=$.trim(document.getElementById("text_detalle2").value);
        if (typeof $("#i_file")[0].files[0] != "undefined" || typeof $("#i_file2")[0].files[0] != "undefined")
        {
        	var imagen_b=false;
        	var imagen_a=false;
        	if(typeof $("#i_file")[0].files[0] != "undefined" )
        	{
						var file = $("#i_file")[0].files[0];
        		//obtenemos el nombre del archivo
        		var fileName = file.name;
        		var ext=fileName.split(".");
        		sizea=file.size;
        		imagen_a=isImage(ext[1]);
        		archivo1=''+registro+'.'+ext[1]+'';
        	}else
        		{
        			imagen_a=true;
        			sizea=1000000;
       			  ext="";
       			  archivo1="";
        		}
        	if(typeof $("#i_file2")[0].files[0] != "undefined" )
        	{
					
        		var file2 = $("#i_file2")[0].files[0];
        		//obtenemos el nombre del archivo
        		var fileName2 = file2.name;
						var ext2=fileName2.split(".");
						sizeb=file2.size;
						imagen_b=isImage(ext2[1]);
						archivo2=''+registro+'_2.'+ext2[1]+'';
					}else
						{
							sizeb=1000000;
							imagen_b=true;
							ext2="";
							archivo2="";
						}
					//hacemos la petición ajax  
					//alert(""+sizea+" <= 4000000  && "+sizeb+"<=4000000 && "+imagen_a+" && "+imagen_b+"");
        	if(sizea <= 4000000  && sizeb<=4000000 && imagen_a && imagen_b)
        	{
        		$.ajax({
        		    url: 'upload.php?names='+registro+'',  
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
        		       loadEspera("Subiendo..");       
        		    },
        		    //una vez finalizado correctamente
        		    success: function(data){
        		       
        		       $("#m_checkout2").dialog( "close" );
        		       checkOut(registro,detalle,archivo1,archivo2);
        		       hideLoad();
        		    },
        		    //si ha ocurrido un error
        		    error: function(){
        		       mensaje("Error al subir las imagenes");
        		       hideLoad();
        		    }
        		});
      		}else
      			{
      				alert("Los archivos cargados son muy pesados(Max 4MB) o bien no es una imagen JPG");
      			}
				}else
				{
							hideLoad();
        		  $("#m_checkout2").dialog( "close" );
        		  checkOut(registro,detalle,'','');
				}
				
        
    });
})
	
}

 
 
//comprobamos si el archivo a subir es una imagen
//para visualizarla una vez haya subido
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
function verDetalleAgenda(registro,visita,detalle,direc,otro,hora)
{
	loadEspera("Cargando..");
	$("#ag_visita").html(visita);	
	$("#ag_hora").html(hora);	
	$("#ag_detalle").html(detalle);
	$("#ag_direccion").html(direc);
	$("#ag_otro").html(otro);
	$("#t_agenda").html(registro);
	$.mobile.changePage('#m_det_agenda',  {transition: 'pop', role: 'dialog'});
	
	
}
function loadAgendas()
{
	loadEspera("Cargando Agendas ...");
	$("#list_registros_agenda").load("query.php", 
			{tipo:11} 
				,function(){	
					hideLoad();
				}
	);
}

function verMapa(lat,lon,texto)
{
	addMarcadores(lon,lat,texto,'iconos/point5.png',30,30);
  moverCentro(lat,lon,17); 
  $("#mypanel").panel( "close" );
  
}

function checkOut5(registro,empresa)
{
	
	$("#t_id_empresa5").html(registro);
	$("#t_empresa5").html(empresa);
	$.mobile.changePage('#m_checkout5',  {transition: 'pop', role: 'dialog'});
$(document).ready(function(){
 
    var fileExtension = "";
    //función que observa los cambios del campo file y obtiene información
    $('#i_filea').change(function()
    {
        //obtenemos un array con los datos del archivo
        var file = $("#i_filea")[0].files[0];
        //obtenemos el nombre del archivo
        var fileName = file.name;
        //obtenemos la extensión del archivo
        fileExtension = fileName.substring(fileName.lastIndexOf('.') + 1);
        //obtenemos el tamaño del archivo
        var fileSize = file.size;
        //obtenemos el tipo de archivo image/png ejemplo
        var fileType = file.type;
        //mensaje con la información del archivo
        //alert("<span class='info'>Archivo para subir: "+fileName+", peso total: "+fileSize+" bytes.</span>");
        
    });
 
    //al enviar el formulario
    $('#but_check2').click(function(){
        //información del formulario
        var formData = new FormData($(".formulario")[0]);
        var message = "";    
        var detalle=$.trim(document.getElementById("text_detalle_a").value);
        var detalleb=$.trim(document.getElementById("text_detalle_b").value);
        var detallec=$.trim(document.getElementById("text_detalle_c").value);
        
        var tot_productos=$.trim(document.getElementById("tot_pro").value);
        if(tot_productos > 0)
        {
        	var prod_id="";
        	var prod_ini="";
        	var prod_fin="";
        	var prod_val="";
        	for(i=0;i<tot_productos;i++)
        	{
        		if(i>0)
        		{
        			prod_id +="|";
        			prod_ini +="|";
        			prod_fin +="|";
        			prod_val +="|";
        		}
        		prod_id +=$.trim(document.getElementById("pro_"+i).value);
        		prod_ini +=$.trim(document.getElementById("pr_"+i).value);
        		prod_fin +=$.trim(document.getElementById("prf_"+i).value);
        		prod_val +=$.trim(document.getElementById("prv_"+i).value);
        	}
        	
        }
        if (typeof $("#i_filea")[0].files[0] != "undefined" || typeof $("#i_fileb")[0].files[0] != "undefined")
        {
        	var imagen_b=false;
        	var imagen_a=false;
        	if(typeof $("#i_filea")[0].files[0] != "undefined" )
        	{
						var file = $("#i_filea")[0].files[0];
        		//obtenemos el nombre del archivo
        		var fileName = file.name;
        		var ext=fileName.split(".");
        		sizea=file.size;
        		imagen_a=isImage(ext[1]);
        		archivo1=''+registro+'.'+ext[1]+'';
        		
        	}else
        		{
        			imagen_a=true;
        			sizea=1000000;
       			  ext="";
       			  archivo1="";
        		}
        	if(typeof $("#i_fileb")[0].files[0] != "undefined" )
        	{
					
        		var file2 = $("#i_fileb")[0].files[0];
        		//obtenemos el nombre del archivo
        		var fileName2 = file2.name;
						var ext2=fileName2.split(".");
						sizeb=file2.size;
						imagen_b=isImage(ext2[1]);
						archivo2=''+registro+'_2.'+ext2[1]+'';
					}else
						{
							sizeb=1000000;
							imagen_b=true;
							ext2="";
							archivo2="";
						}
					//hacemos la petición ajax  
					//alert(""+sizea+" <= 4000000  && "+sizeb+"<=4000000 && "+imagen_a+" && "+imagen_b+"");
        	if(sizea <= 4000000  && sizeb<=4000000 && imagen_a && imagen_b)
        	{
        		var formData = new FormData();

					//formData.append("username", "Groucho");
					//formData.append("accountnum", 123456); // number 123456 is immediately converted to string "123456"

					// HTML file input user's choice...
					formData.append("i_filea", $("#i_filea")[0].files[0]);
					formData.append("i_fileb", $("#i_fileb")[0].files[0]);
        		$.ajax({
        		    url: 'uploadb.php?names='+registro+'',  
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
        		       loadEspera("Subiendo..");       
        		    },
        		    //una vez finalizado correctamente
        		    success: function(data){
        		       
        		       $("#m_checkout5").dialog( "close" );
        		       
        		       
        		       checkOut_5(registro,detalle,archivo1,archivo2,detalleb,detallec,prod_id,prod_ini,prod_fin,prod_val);
        		       hideLoad();
        		    },
        		    //si ha ocurrido un error
        		    error: function(){
        		       mensaje("Error al subir las imagenes");
        		       hideLoad();
        		    }
        		});
      		}else
      			{
      				alert("Los archivos cargados son muy pesados(Max 4MB) o bien no es una imagen JPG");
      			}
				}else
				{
							hideLoad();
        		  $("#m_checkout5").dialog( "close" );
        		  checkOut_5(registro,detalle,'','','','',prod_id,prod_ini,prod_fin,prod_val);
				}
				
        
    });
    
    
})
	
}
function ordenarAgenda()
{
	loadEspera("Organizando Agenda..");	
	navigator.geolocation.getCurrentPosition (function (pos)
	{
		
  	MX_lat = pos.coords.latitude;
  	MX_lon = pos.coords.longitude;
  	MX_accuracy=pos.coords.accuracy.toFixed(2);
  	$("#list_registros_agenda").load("query.php", 
			{tipo:11,lat:MX_lat,lon:MX_lon} 
				,function(){	
					hideLoad();
				}
		);
  	CM_vector_current.removeAllFeatures();   	
  	DibujarCirculoAdap('#3388CC','#3388CC',MX_accuracy,MX_lon,MX_lat,0.5);
  	DibujarCirculoAdap('red','red',2,MX_lon,MX_lat,1);
  	map.zoomToExtent(CM_vector_current.getDataExtent());
  	
  	
	},function(err){hideLoad();});
}

function ordenarAgendaReloj()
{
	loadEspera("Organizando Agenda..");	
	$("#list_registros_agenda").load("query.php", 
			{tipo:13} 
				,function(){	
					hideLoad();
				}
		);
}

function checkOutPedido()
{
	$("#m_checkout5").dialog( "close" );
	var registro=$("#t_id_empresa5").html();
	var empresa=$("#t_empresa5").html();
	var detalle=$.trim(document.getElementById("text_detalle_b").value);
	     var tot_productos=$.trim(document.getElementById("tot_pro").value);
        if(tot_productos > 0)
        {
        	var prod_id="";
        	
        	var prod_cantidad="";
        	
        	for(i=0;i<tot_productos;i++)
        	{
        		if(i>0)
        		{
        			prod_id +="|";
        			prod_cantidad +="|";
        			
        		}
        		prod_id +=$.trim(document.getElementById("pro_"+i).value);
        		prod_cantidad +=$.trim(document.getElementById("cant_"+i).value);
        		
        	}
        	
        }
        
        loadEspera("Procesando..");
			$("#output").load("query.php", 
			{tipo:14, registro:registro,detalle:detalle,prod_id:prod_id,prod_cant:prod_cantidad,empresa:empresa} 
				,function(){				
					
					$("#mypanel").panel( "close" );
					loadRegistros();					
					hideLoad();
					loadAgendas();
					mensaje("Check-Out realizado");					
					
				}
	);
        
}