<?php
define('BASE_DIR', dirname(__DIR__)); // set upper directory as base dir
require implode(DIRECTORY_SEPARATOR, [BASE_DIR, 'vendor', 'autoload.php']);

$mux = new Pux\Mux;

$mux->any('/guestbook/thread/',['Food\Controller\Guestbook\ThreadController','index']);
$mux->get('/guestbook/thread/view/:token',['Food\Controller\Guestbook\ThreadController','view']);
$mux->get('/guestbook/thread/create/:title',['Food\Controller\Guestbook\ThreadController','create']);
$mux->get('/guestbook/thread/delete/:token',['Food\Controller\Guestbook\ThreadController','delete']);
$mux->get('/guestbook/thread/edit/:token/:title',['Food\Controller\Guestbook\ThreadController','edit']);

$mux->get('/guestbook/post/view/:token',['Food\Controller\Guestbook\PostController','view']);
$mux->get('/guestbook/post/create/:tid/:content',['Food\Controller\Guestbook\PostController','create']);
$mux->get('/guestbook/post/delete/:token',['Food\Controller\Guestbook\PostController','delete']);
$mux->get('/guestbook/post/edit/:token/:content',['Food\Controller\Guestbook\PostController','edit']);



$mux->get('/users', ['Food\Controller\User\UserController', 'getAllUser']);

$mux->post('/user', ['Food\Controller\User\UserController', 'addUser']);

$mux->get(
    '/user/:name',
    ['Food\Controller\User\UserController', 'getUserByName'],
    ['require' => [ 'name' => '([a-z0-9]{8,16})']]
);

$mux->put(
    '/user/:name',
    ['Food\Controller\User\UserController', 'editUser'],
    ['require' => [ 'name' => '([a-z0-9]{8,16})']]
);

$mux->delete(
    '/user/:name',
    ['Food\Controller\User\UserController', 'deleteUser'],
    ['require' => [ 'name' => '([a-z0-9]{8,16})']]
);


// owen
$mux->get('/album/index', [
    'Food\Controller\Album\AlbumController',
    'index'
]);
$mux->get('/album/create', [
    'Food\Controller\Album\AlbumController',
    'create'
]);
$mux->get('/album/edit', [
    'Food\Controller\Album\AlbumController',
    'edit'
]);
$mux->get('/album/del', [
    'Food\Controller\Album\AlbumController',
    'del'
]);
$mux->get('/photo/create', [
    'Food\Controller\Album\PhotoController',
    'create'
]);
$mux->get('/photo/edit', [
    'Food\Controller\Album\PhotoController',
    'edit'
]);
$mux->get('/photo/del', [
    'Food\Controller\Album\PhotoController',
    'del'
]);
$mux->get('/photo/chosen', [
    'Food\Controller\Album\PhotoController',
    'chosen'
]);

$mux->sort();

return $mux;
