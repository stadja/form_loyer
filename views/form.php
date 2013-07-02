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
	<div class="container" style="margin: 0px auto; max-width: 700px;">
		<h4>
			Franciliens, calculez votre nouveau loyer gr&acirc;ce &agrave; la loi Duflot
		</h4>
		    <div class="alert alert-info">
		    Le projet de loi Duflot pr&eacute;voit d&rsquo;encadrer les loyers en 2014. Gr&acirc;ce au simulateur de Mediapart, qui a eu acc&egrave;s aux donn&eacute;es de l&rsquo;OLAP (Observatoire des loyers de l&rsquo;agglom&eacute;ration parisienne), calculez le loyer maximal que vous payerez pour votre futur appartement. Ou le montant que vous pourrez exiger de votre actuel propri&eacute;taire.
		    </div>
		<a href='#' id='newCalc' style='display: none;'>Nouveau Calcul</a>
		<form id='form_loyer' action="" method="POST" class="form-horizontal">
			<div class="control-group">
				<label class="control-label" for="inputZipcode">Le code postal de votre appartement*</label>
				<div class="controls">
					<input type="text" name="zipcode" id="inputZipcode" placeholder="ex: 75011, 92240...">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputSize">Sa superficie*</label>
				<div class="controls">
					<input type="text" name="size" id="inputSize" placeholder="en m&sup2;">m&sup2;
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputType">Le type de bien*</label>
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
				<label class="control-label" for="inputActual">Votre loyer actuel*</label>
				<div class="controls">
					<input type="text" name="actual" id="inputActual" placeholder="en &euro;">
				</div>
			</div>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn">Simuler votre futur loyer maximal</button>
				</div>
			</div>
		</form>

		<div class="alert alert-error" style='display: none;'>
		</div>

		<div class="alert alert-success" style='display: none;'>
		</div>
	</div>
	<script src="<?php echo $app->config('assets_path'); ?>/js/jquery.min.js"></script>
	<script src="<?php echo $app->config('assets_path'); ?>/js/bootstrap.min.js"></script>
	<script src="<?php echo $app->config('assets_path'); ?>/js/post.js"></script>
</body>
</html>
