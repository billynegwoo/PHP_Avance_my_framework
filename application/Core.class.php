<?php
namespace MyFramework;

class Core
{
	static protected $_routing = [];
	static private $_render;
	static protected $_pdo;
	static protected $_layout = 'default';

	public function __construct(){
		$user = "root";
		$password = "";
		$database = "my_cinema";
		$socket = "loca";
		self::$_pdo = new \PDO("mysql:dbname=" . $database . ";unix_socket=" . $socket, $user, $password);
		self::$_pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,\PDO::FETCH_ASSOC);
	}

	private function setRouting($controller, $action){
		self::$_routing = [
		'controller' => $controller,
		'action' => $action,
		];
	}

	private function routing()
	{
		$req = self::$_pdo->prepare('SELECT * from `routes`');
		$req->execute();
		$routes = $req->fetchAll();
		foreach ($routes as $route) {
			if ($route['url'] === $_GET['_url']){
				$url = explode('/', $route['real_path']);
				$controller = $url[0];
				$action = $url[1];
				$this->setRouting($controller,$action);
				return;
			}
		}
		/*$routes = json_decode(file_get_contents(__DIR__.'\routes.json'));
		foreach ($routes as $key => $value) {
			if ($key === $_GET['_url']){
				$url = explode('/', $value);
				$controller = $url[0];
				$action = $url[1];
				$this->setRouting($controller,$action);
				return;
			}
		}*/

		$url = explode('/', $_GET['_url']);
		if(isset($url[1])){
			$controller = $url[0];
			$filename = __DIR__.'/controllers/'.ucfirst($controller).'Controller.class.php';
			$action = $url[1];
			if(file_exists($filename) && method_exists(__NAMESPACE__ . '\\'.ucfirst($controller).'Controller', $action.'Action'))
			{
				$this->setRouting($controller,$action);
				return;
			}else{
				$action = 'default';
				$controller = 'default';
				$this->setRouting($controller,$action);
			}
		}else{
			$action = 'default';
			$controller = 'default';
			$this->setRouting($controller,$action);
		}
	}



	protected function render($params = [])
	{
		
		$f = implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'application\views',
			self::$_routing['controller'], self::$_routing['action']]) . '.php';
		if (file_exists($f)) {
			$c = file_get_contents($f);

			foreach ($params as $k => $v) {
				$c = preg_replace("/\{\{\s*$k\s*\}\}/", $v, $c);
			}
			self::$_render = $c;
			
		}
		else {
			self::$_render = "Impossible de trouver la vue" . PHP_EOL;
		}

	}

	public function run()
	{
		ob_start();
		$this->routing();
		$c = __NAMESPACE__ . '\\' . ucfirst(self::$_routing['controller']) .
		'Controller';
		$o = new $c();
		if (method_exists($o, $a = self::$_routing['action'] . 'Action')) {
			$o->$a();
		}
		else {
			self::$_render = "Impossible de trouver la methode" . PHP_EOL;
		}
		echo self::$_render;
		$content = ob_get_clean();
		require_once(__DIR__.'/views/layout/'.self::$_layout.'.php');
	}
}

?>