<?php

namespace MyFramework;

class DefaultController extends DefaultModel
{
	public function defaultAction()
	{
		$this->render(['prenom' => $this->getLogin()]);
	}
	public function connexionAction(){
		$this->render(['login'=> $this->getLogin(),
			'url'=> $_SERVER['REQUEST_URI']]);
	}
}
?>