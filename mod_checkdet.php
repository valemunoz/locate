<div data-role="header" >
   			 
      		<h1><img src="images/logo_places3.png"></h1>      		 
   		</div>  

				<div data-role="content" data-theme="a" id="cont_registro">

					<p>
						<label for="text-basic">E-mail</label>
						<input type="text" name="mail_ses" id="mail_ses" value="<?=$_SESSION["mail_usu"]?>">

						<label for="textarea">Contrase&ntilde;a</label>
						<input type="password" name="clave_ses" id="clave_ses" value="">
						<div id="msg_error_ses" class="msg_error"></div>
						<input type="button" onclick="incioSesion();" value="Ir">
					</p>
				</div><!-- /content -->

