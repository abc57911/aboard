<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Album;

class AlbumController extends Seed
{

    public function index()
    {
        $aaa = Album::listAll();
        $img = array();
        foreach ($aaa as $a)
        {
            $album = Album::load($a);
            $allalbum[$a] = [$album->getTitle(),$album->getDescription(),$album->getToken()];
            
        }
        var_dump($allalbum);
        exit();
    }

    public function create()
    {
        $title = 'ccc';
        $desc = 'aaa';
        Album::create($title, $desc);
    }

    public function edit()
    {
        $i = '3';
        $t = 'AAA';
        $d = 'BBB';
        $aaa = Album::load($i);
        $aaa->setTitle($t);
        $aaa->setDescription($d);
        $aaa->save();
    }

    public function del()
    {
        $i = '3';
        $aaa = Album::load($i);
        $aaa->delete();
    }
}
