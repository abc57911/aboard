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

return $mux;