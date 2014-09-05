
<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
</head>
<body>

	<div data-role="page" id="dialog-success" data-dom-cache="true"><!-- dialog-->

		<div data-role="header" data-theme="e">
			<h1>Galeria</h1>
		</div><!-- /header -->

		<div data-role="content" data-theme="e">
			<h5>Valor: $<?=$_REQUEST['valor']?></h5>
			<img src="site/img_cli/<?=$_REQUEST['img']?>">
		</div>
	</div>

	<div data-role="page" id="page-success"><!-- dialog-->

</body>
</html>