<?php

return array(
    'general' => array(
        'router' => 'Fruit\PuxRouter'
    ),

    'pux' => array(
        'mux' => implode(DIRECTORY_SEPARATOR, ['route', 'compiled.php'])
    ),

    'twig' => array(
        'template_dir' => BASE_DIR . '/templates',
        'debug'        => true,
        'cache_dir'    => '/tmp'
    ),
    
    'db' => array(
        'default' => array(
            'constr' => 'mysql:host=127.0.0.1;dbname=foodorder;charset=utf8',
            'user'   => 'foodorder',
            'pass'   => '1234'
        ),
    )
);
