<?php
require('init.php');
require(implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']));
Food\Model\Guestbook\Thread::fertilize(FoodTest\Tool::getConfig());
