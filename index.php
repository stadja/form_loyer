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
		$response['message'] = 'Loyer obligatoire';
	} else {
		$actual = $_POST['actual'];
	}

	// gestion superficie
	if (!isset($_POST['size']) || !is_numeric($_POST['size'])) {
		$response['status'] = 'error';
		$response['message'] = 'Superficie obligatoire';
	} else {
		$size = $_POST['size'];
	}

	// gestion zipcode
	if (!isset($_POST['zipcode']) || !is_numeric($_POST['zipcode']) || !is_in_paris($app, $_POST['zipcode'])) {
		$response['status'] = 'error';
		$response['message'] = 'Le code postal doit être dans l\'agglom&eacute;ration parisienne';
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


	// on fait la réponse
	$answer = '';
	$answer .= 'Vous habitez dans la commune '.$response['data']['lieu'];
	$answer .= '<br/>Vous êtes donc dans la zone de loyer <b>'.$response['data']['zone'].'</b>';

	if (!$response['data']['mediane']) {
		$answer .= '<br/>Désolé, nous ne connaissons pas le loyer moyen pour un habitat de votre type dans cette zone de loyer.';
	} else {
		$answer .= '<br/>Le loyer moyen dans cette zone pour un habitat de type '.$type.' est de <b>'.$mediane.'&euro;</b> / m&sup2;';

		$maxLoyer = ($size * $mediane * 1.15);
		$answer .= '<br/><br/> Vous devriez payer un loyer maximum de '.$maxLoyer.'&euro;';

		if ($maxLoyer >= $actual) {
			$answer .= ' -&gt; Votre loyer est dans les normes.';
		} else {
			$answer .= ' -&gt; vous payez donc <b>'.($actual - $maxLoyer).'&euro;</b> de trop.';
		}
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
