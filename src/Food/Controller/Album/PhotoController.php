<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;

class PhotoController extends Seed
{

    public function index()
    {}

    public function create()
    {
        $id = '2';
        $album = Album::load($id);
        $path = self::getConfig()->base() . '/image/ccc.jpg';
        $title = 'aaa';
        $desc = 'bbb';
        $photo = Photo::create($album, $path, $title);
    }

    public function edit()
    {
        $id = 'ul_1Qiqld';
        $t = '0101';
        $d = 'tyjudrj躲';
        $photo = Photo::load($id);
        $photo->setTitle($t);
        $photo->setDescription($d);
        $photo->save();
    }

    public function del()
    {
        $id = 'ul_g1PCTN';
        $photo = Photo::load($id);
        $photo->delete();
    }
    public function chosen()
    {
        $id = 'ul_1Qiqld';
        $photo = Photo::load($id);
        $aaa["0"] = array(
            "title" => $photo->getTitle(),
            "desc" => $photo->getDescription()
        );
        echo json_encode($aaa,JSON_UNESCAPED_UNICODE);
        $photo->readFile();

        
    }
}
