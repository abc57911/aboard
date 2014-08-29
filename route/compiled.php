<?php return Pux\Mux::__set_state(array(
   'routes' => 
  array (
    0 => 
    array (
      0 => true,
      1 => '#^    /album/allphoto
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\AlbumController',
        1 => 'allphoto',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /album/allphoto
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/album/allphoto',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /album/allphoto
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/album/allphoto/:id',
      ),
    ),
    1 => 
    array (
      0 => true,
      1 => '#^    /photo/doupload
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\PhotoController',
        1 => 'doupload',
      ),
      3 => 
      array (
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /photo/doupload
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/photo/doupload',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /photo/doupload
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/photo/doupload/:id',
      ),
    ),
    2 => 
    array (
      0 => true,
      1 => '#^    /photo/edit
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\PhotoController',
        1 => 'edit',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /photo/edit
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/photo/edit',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /photo/edit
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/photo/edit/:id',
      ),
    ),
    3 => 
    array (
      0 => true,
      1 => '#^    /photo/show
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\PhotoController',
        1 => 'show',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /photo/show
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/photo/show',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /photo/show
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/photo/show/:id',
      ),
    ),
    4 => 
    array (
      0 => true,
      1 => '#^    /album/edit
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\AlbumController',
        1 => 'edit',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /album/edit
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/album/edit',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /album/edit
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/album/edit/:id',
      ),
    ),
    5 => 
    array (
      0 => true,
      1 => '#^    /photo/del
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\PhotoController',
        1 => 'del',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /photo/del
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/photo/del',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /photo/del
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/photo/del/:id',
      ),
    ),
    6 => 
    array (
      0 => true,
      1 => '#^    /album/del
    /(?P<id>[^/]+?)
$#xs',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\AlbumController',
        1 => 'del',
      ),
      3 => 
      array (
        'method' => 1,
        'variables' => 
        array (
          0 => 'id',
        ),
        'regex' => '    /album/del
    /(?P<id>[^/]+?)
',
        'tokens' => 
        array (
          0 => 
          array (
            0 => 3,
            1 => '/album/del',
          ),
          1 => 
          array (
            0 => 2,
            1 => '/',
            2 => '[^/]+?',
            3 => 'id',
          ),
        ),
        'compiled' => '#^    /album/del
    /(?P<id>[^/]+?)
$#xs',
        'pattern' => '/album/del/:id',
      ),
    ),
    7 => 
    array (
      0 => false,
      1 => '/guestbook/thread/create',
      2 => 
      array (
        0 => 'Food\\Controller\\Guestbook\\ThreadController',
        1 => 'create',
      ),
      3 => 
      array (
        'method' => 1,
      ),
    ),
    8 => 
    array (
      0 => false,
      1 => '/album/create',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\AlbumController',
        1 => 'create',
      ),
      3 => 
      array (
        'method' => 1,
      ),
    ),
    9 => 
    array (
      0 => false,
      1 => '/album/index',
      2 => 
      array (
        0 => 'Food\\Controller\\Album\\AlbumController',
        1 => 'index',
      ),
      3 => 
      array (
        'method' => 1,
      ),
    ),
  ),
   'staticRoutes' => 
  array (
  ),
   'routesById' => 
  array (
  ),
   'submux' => 
  array (
  ),
   'id' => NULL,
   'expand' => true,
)); /* version */