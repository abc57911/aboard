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

        return json_encode($allalbum);
    }

    public function allphoto($id)
    {
        $album = Album::load($id);
        $aaa = $album->listPhoto();
        foreach ($aaa as $a) {
            $photo = Photo::load($a);
            $allphoto[$a] = [
                $photo->getTitle(),
                $photo->getDescription(),
                'aboard/photo/show/' . $photo->getToken()
            ];
        }
        return json_encode($allphoto);
}
