<?php
namespace MyFramework;

class DefaultModel extends Core
{
	public function getLogin()
	{
		return  'Julien';
 	}

	public function setLogin()
	{
		if (array_key_exists('login', $_POST)){
			return $_POST['login'];
		}
	}
}
?>