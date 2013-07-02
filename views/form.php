<!DOCTYPE html>
<html>
<head>
	<title>Formulaire des loyers</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Bootstrap -->
	<link href="<?php echo $app->config('assets_path'); ?>/css/bootstrap.css" rel="stylesheet" media="screen">
	<link href="<?php echo $app->config('assets_path'); ?>/css/bootstrap-responsive.css" rel="stylesheet" media="screen">
</head>
<body>

	<form id='form_loyer' action="" method="POST" class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="inputZipcode">Code postal*</label>
			<div class="controls">
				<input type="text" name="zipcode" id="inputZipcode" placeholder="ex: 75011, 92240...">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputSize">Superficie*</label>
			<div class="controls">
				<input type="text" name="size" id="inputSize" placeholder="en m&sup2;">m&sup2;
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputType">Type d'appartement*</label>
			<div class="controls">
				<select name="type" id="inputType">
					<option value="T1">T1</option>
					<option value="T2">T2</option>
					<option value="T3">T3</option>
					<option value="T4 et plus">T4 et plus</option>
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="inputActual">Loyer actuel*</label>
			<div class="controls">
				<input type="text" name="actual" id="inputActual" placeholder="en &euro;">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn">Lancer le calcul</button>
			</div>
		</div>
	</form>

	<div class="alert alert-error" style='display: none;'>
	</div>

	<div class="alert alert-info" style='display: none;'>
	</div>

	<script src="<?php echo $app->config('assets_path'); ?>/js/jquery.min.js"></script>
	<script src="<?php echo $app->config('assets_path'); ?>/js/bootstrap.min.js"></script>
	<script src="<?php echo $app->config('assets_path'); ?>/js/post.js"></script>
</body>
</html>
