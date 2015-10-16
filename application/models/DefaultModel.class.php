<?php
namespace MyFramework;

class DefaultModel extends Core
{
	public function getLogin()
	{
		if (array_key_exists('login', $_POST)){
			return $_POST['login'];
		}
 	}
}
?>