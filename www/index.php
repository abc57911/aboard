<?php
require(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']));
define('BASE_DIR', dirname(__DIR__));
new Fruit\Config(dirname(__DIR__))->getRouter()->route($_SERVER['DOCUMENT_URI']);