<?php

	ini_set("session.auto_start", "1") ? 0 : session_start();

	require __DIR__ . '/vendor/autoload.php';

	use \CareyLi\s2h;
	use \utilphp\util;

	$whoops = new \Whoops\Run;
	$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();

	$model = new s2h\Model();
	$view = new \League\Plates\Engine("./src/templates");
	$controller = new s2h\Controller($model, $view);
	$ajax = new s2h\AjaxController($model);

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

	$router->respond('GET', '/spotify_callback/?', function($request) use ($controller) {
		$controller->spotify_oauth();
	});

	$router->with('/first-run', function() use ($router, $controller, $model) {

		$router->respond('GET', '/?', function($request) use ($controller) {
			$controller->firstRun();
		});

		$router->respond('POST', '/?', function($request) use ($controller) {
			$controller->firstRunSave();
		});

	});

	$router->with('/ajax', function() use ($router, $ajax, $model) {

		$router->respond('GET', '/playlist_songs/[:user]/[:playlist]/[:html]?', function($request) use ($ajax) {

			if ($request->html === "html") {
				$ajax->html_playlist_songs($request->user, $request->playlist);
			} else {
				$ajax->playlist_songs($request->user, $request->playlist);
			}

		});

		$router->respond('GET', '/queue_album/[:artist]/[:album]', function($request) use ($ajax) {
			$ajax->queue_album(base64_decode($request->artist), base64_decode($request->album));
		});

	});

	$router->with('/settings', function() use ($router, $controller) {

		$router->respond('GET', '/', function($request) use ($controller) {
			$controller->settings();
		});

	});

	$router->dispatch();
?>