<?php
namespace Food\Controller\Guestbook;

use Fruit\Seed;

class Example extends Seed
{
    public function index()
    {
        include('../templates/example.html');
    }
}
