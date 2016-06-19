<?php

namespace CareyLi\s2h;

/**
 * Main controller
 */
class Controller
{

	private $model;
	private $view;

    public function __construct(Model $model, \League\Plates\Engine $view)
    {
    	$this->model = $model;
    	$this->view = $view;
    }

    public function index() {
    	echo $this->view->render("index");

    	$this->model->getSpotifyKeys();
    }

    public function firstRun() {
    	if (!$this->model->isFirstRun()) {
			header("Location: /");
			exit;
		}

		echo $this->view->render("firstrun");
    }

    public function firstRunSave() {
    	$request = $_POST;

		if (count(array_diff(["headphones_key", "client_id", "client_secret"], array_keys($request))) > 0) {
			throw new \Exception("Insufficent arguments passed to save settings.");
		}

		if ($this->model->updateSettings($request)) {
			echo '<meta http-equiv="refresh" content="0; url=./">';
			exit;
		}
    }

    public function settings() {
    	echo $this->view->render("settings");
    }
}