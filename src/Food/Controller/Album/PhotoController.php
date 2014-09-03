<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;

class PhotoController extends Seed
{

    public function upload()
    {
        $loader = new \Twig_Loader_Filesystem(BASE_DIR . '/templates');
        $twig = new \Twig_Environment($loader);
        $result[0] = '';
        echo $twig->render('upload.html', array(
            'res' => $result
        ));
    }

    public function doupload($id)
    {
        if ($_FILES["file"]["error"] > 0) {
            $msg = '1';
            echo '1';
        } else {
            if (file_exists($_FILES["file"]["name"])) {
                
                $msg = '2';
                echo '2';
            } else {
                $album = Album::load($id);
                $t = $_POST["title"];
                $d = $_POST["desc"];
                $photo = Photo::create($album, $_FILES["file"]["tmp_name"], $t, $d);
                $end = [
                    "end" => "ok"
                ];
                return json_encode($end);
                exit();
            }
        }
        switch ($msg) {
            case "1":
            case "2":
                echo "files type error!";
                break;
            default:
                $msg = "3";
                echo "call Owen!";
                break;
        }
    }

    public function edit($id)
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $photo = Photo::load($id);
        $photo->setTitle($t);
        $photo->setDescription($d);
        $photo->save();
        $end = [
        "end" => "ok"
            ];
        return json_encode($end);
    }

    public function del($id)
    {
        $photo = Photo::load($id);
        $photo->delete();
        $end = [
        "end" => "ok"
            ];
        return json_encode($end);
    }

    public function show($id)
    {
        $photo = Photo::load($id);
        header('Content-Type: image/jpeg');
        $photo->readFile();
    }
}
