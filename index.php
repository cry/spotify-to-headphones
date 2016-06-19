<?php session_start();

	require __DIR__ . '/vendor/autoload.php';

	use \CareyLi\s2h;
	use \utilphp\util;

	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();

	$model = new s2h\Model();
	$view = new \League\Plates\Engine("./src/templates");
	$controller = new s2h\Controller($model, $view);

	$router = new \Klein\Klein();

	/*
	 * Check for first run, redirect if so.
	 */

	if ($model->isFirstRun() && strpos(util::get_current_url(), 'first-run') === FALSE) {
		header("Location: ./first-run");
		exit;
	}

	$router->respond('GET', '/', function($request) use ($controller) {
		$controller->index();
	});

	$router->with('/first-run', function() use ($router, $controller, $model) {

		$router->respond('GET', '/?', function($request) use ($controller) {
			$controller->firstRun();
		});

		$router->respond('POST', '/?', function($request) use ($controller) {
			$controller->firstRunSave();
		});

	});

	$router->with('/settings', function() use ($router, $controller) {

		$router->response('GET', '/', function($request) use ($controller) {
			$controller->settings();
		});

	});

	$router->dispatch();
?>