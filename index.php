<?php

require 'vendor/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->config(array(
	'templates.path' => './views/',
	'assets_path' => '/form_loyer/assets',
	'zipcodes' => include 'assets/data/zipcodes.php',
	'medianes' => include 'assets/data/medianes.php'
));

$data = array('app' => $app);

/**
* Affichage du formulaire
**/
$app->get('/', function () use ($app, $data) {
	$app->render('form.php', $data);
});


/**
* Calcul de la réponse
**/
$app->post('/', function () use ($app, $data) {

	$response = array('status' => 'success');

	// gestion loyer
	if (!isset($_POST['actual']) || !is_numeric($_POST['actual'])) {
		$response['status'] = 'error';
		$response['message'] = '<strong>Loyer actuel</strong> obligatoire';
	} else {
		$actual = $_POST['actual'];
	}

	// gestion superficie
	if (!isset($_POST['size']) || !is_numeric($_POST['size'])) {
		$response['status'] = 'error';
		$response['message'] = '<strong>Superficie</strong> obligatoire';
	} else {
		$size = $_POST['size'];
	}

	// gestion zipcode
	if (!isset($_POST['zipcode']) || !is_numeric($_POST['zipcode']) || !is_in_paris($app, $_POST['zipcode'])) {
		$response['status'] = 'error';
		$response['message'] = '<strong>Le code postal</strong> doit être dans l\'agglom&eacute;ration parisienne';
	} else {
		$zipcode = is_in_paris($app, $_POST['zipcode']);
	}

	if ($response['status'] == 'error') {
		echo json_encode($response);
		return;
	}

	$type = $_POST['type'];

	$response['data'] = array();
	$response['data']['zone'] = $zipcode[0];
	$response['data']['lieu'] = $zipcode[1];
	$mediane = get_mediane($app, $type, $response['data']['zone']);
	$response['data']['mediane'] = $mediane;

	$maxLoyer = round($size * $mediane * 1.15);
	$medianLoyer = ($size * $mediane);


	// on fait la réponse
	$answer = '';

	if (!$response['data']['mediane']) {
		$answer .= 'Désolé, nous ne connaissons pas le loyer moyen pour un habitat de votre type dans cette zone de loyer.';
	} else {
		$answer .= '<b>Votre futur loyer maximal :</b>';
		$answer .= '<br/>'.$maxLoyer.' euros';

		if ($maxLoyer >= $actual) {
			$answer .= '<br/>Votre loyer est donc dans les normes';
		} else {
			$answer .= '<br/><b>Soit une baisse de </b><br/>'.($actual - $maxLoyer).' euros';
		}

		$answer .= '<br/><br/><small>';
		$answer .= '<h6>Explications du calcul</h6>';
		$answer .= 'Le loyer median pour un '.$type.' dans votre zone ('.$response['data']['zone'].') est de <b>'.$mediane.' euros</b> / m&sup2;';
		if ($maxLoyer >= $actual) {
			$answer .= '<br/>Vous êtes à '.round(100 - ($actual*100/$medianLoyer)).'% en dessous du loyer médian de votre zone';
		} else {
			$answer .= '<br/>Vous êtes à '.round(($actual*100/$medianLoyer) - 100).'% au dessus du loyer médian de votre zone';
		}
		$answer .= '<br/><br/>Selon le ministère, un propriétaire qui souhaite relouer son bien ne pourra pas dépasser le loyer médian + 15%.';
		$answer .= '<br/><br/>Par ailleurs, vous pourrez n&eacute;gocier avec votre propri&eacute;taire actuel de baisser votre loyer en-dessous de ce plafond. En cas de refus, une commission de concertation tentera de trouver un arrangement, avant qu&rsquo;un juge tranche au final en cas de d&eacute;saccord.'; 
		$answer .= '<br/><br/>Pour l\'instant, seules les donn&eacute;es de l\'agglom&eacute;ration parisienne sont disponibles. On ne peut donc pas r&eacute;aliser cette simulation ailleurs en France. Mais les autres observatoires n&eacute;cessaires &agrave; l\'application de la loi seront mis en place d\'ici 2014.';
		$answer .= '<br/><br/>Cet encadrement des loyers ne concerne que lecteur locatif priv&eacute;, pas le logement social.';
		$answer .= '</small>';
	}

	$response['message'] = $answer;
	echo json_encode($response);
	return;

});

$app->run();

function is_in_paris($app, $zipcode){
	$zipcodes = $app->config('zipcodes');
	return isset($zipcodes[$zipcode]) ?  $zipcodes[$zipcode] : FALSE;
}

function get_mediane($app, $type, $zone){
	$medianes = $app->config('medianes');
	return $medianes[$type][$zone];
}
