<?php
namespace Food\Controller\Guestbook;

use Fruit\Seed;

class PostPlugExample extends Seed
{
    public function index()
    {
        include('../templates/postplug.html');
    }
}
