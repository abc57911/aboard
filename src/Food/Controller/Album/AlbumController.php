<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Album;
use Food\Model\Album\Photo;

class AlbumController extends Seed
{

    public function index()
    {
        $albums = Album::listAll();
        foreach ($albums as $a) {
            $album = Album::load($a);
            $allalbum[$a] = [
                $album->getTitle(),
                $album->getDescription(),
                $album->getToken()
            ];
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

    public function create()
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $album = Album::create($t, $d);
        $aaa["0"] = [
            "title" => $album->getTitle(),
            "desc" => $album->getDescription(),
            "create" => $album->getCreate()
        ];
        return json_encode($aaa);
    }

    public function edit($id)
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $album = Album::load($id);
        $album->setTitle($t);
        $album->setDescription($d);
        $album->save();
        $end = [
            "ending" => 'edit okay'
        ];
        return json_encode($end);
    }

    public function del($id)
    {
        $album = Album::load($id);
        $aaa = $album->listPhoto();
        foreach ($aaa as $a) {
            $photo = Photo::load($a);
            $photo->delete();
        }
        $album->delete();
        $end = [
            "ending" => 'delete okay'
        ];
        return json_encode($end);
    }
}
