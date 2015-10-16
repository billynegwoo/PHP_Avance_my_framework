<?php

$paths = array(
__DIR__,
__DIR__.'\controllers\\',
__DIR__.'\models\\',
__DIR__.'\views\\'
);

set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $paths));

function autoload($className)
{ 
    $class = explode('\\', $className);

    require($class[1].'.class.php');
}
spl_autoload_register('autoload');
?>