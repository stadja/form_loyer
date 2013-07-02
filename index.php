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

	$maxLoyer = ($size * $mediane * 1.15);


	// on fait la réponse
	$answer = '';

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
	$answer .= '<br/><br/>Le loyer maximal au-dessus duquel un propri&eacute;taire francilien ne pourra pas relouer son bien sera, selon le minist&egrave;re du logement, au maximum du loyer median + 15%.';
	$answer .= '<br/><br/>Par ailleurs, vous pourrez n&eacute;gocier avec votre propri&eacute;taire actuel de baisser votre loyer en-dessous de ce plafond. En cas de refus, une commission de concertation tentera de trouver un arrangement, avant qu&rsquo;un juge tranche au final en cas de d&eacute;saccord.'; 
	$answer .= '<br/><br/>Pour l\'instant, seules les donn&eacute;es de l\'agglom&eacute;ration parisienne sont disponibles. On ne peut donc pas r&eacute;aliser cette simulation ailleurs en France. Mais les autres observatoires n&eacute;cessaires &agrave; l\'application de la loi seront mis en place d\'ici 2014.';
	$answer .= '</small>';


	// $answer .= 'Vous habitez dans la commune '.$response['data']['lieu'];
	// $answer .= '<br/>Vous êtes donc dans la zone de loyer <b>'.$response['data']['zone'].'</b>';

	// if (!$response['data']['mediane']) {
	// 	$answer .= '<br/>Désolé, nous ne connaissons pas le loyer moyen pour un habitat de votre type dans cette zone de loyer.';
	// } else {
	// 	$answer .= '<br/>Le loyer moyen dans cette zone pour un habitat de type '.$type.' est de <b>'.$mediane.'&euro;</b> / m&sup2;';

	// 	$answer .= '<br/><br/> Vous devriez payer un loyer maximum de '.$maxLoyer.'&euro;';

	// 	if ($maxLoyer >= $actual) {
	// 		$answer .= ' -&gt; Votre loyer est dans les normes.';
	// 	} else {
	// 		$answer .= ' -&gt; vous payez donc <b>'.($actual - $maxLoyer).'&euro;</b> de trop.';
	// 	}
	// }

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
