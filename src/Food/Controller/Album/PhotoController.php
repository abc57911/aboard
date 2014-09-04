<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;

class PhotoController extends Seed
{

    /**
     * @param string $id 相簿id
     * @param POST string title 圖片名
     * @param POST string desc 圖片描述
     * 取得上傳成功資訊
     * @return JSON {ending:'edit okay'or'file type error!'or'unknow error!'}
     */
    public function doupload($id)
    {
        if ($_FILES["file"]["error"] > 0) {
            $msg = '1';
        } else {
            if (file_exists($_FILES["file"]["name"])) {
                
                $msg = '2';
            } else {
                $album = Album::load($id);
                $t = $_POST["title"];
                $d = $_POST["desc"];
                $photo = Photo::create($album, $_FILES["file"]["tmp_name"], $t, $d);
                $end = [
                    "ending" => "upload ok"
                ];
                return json_encode($end);
                exit();
            }
        }
        switch ($msg) {
            case "1":
            case "2":
                $end = [
                "ending" => "file type error!"
                    ];
                return json_encode($end);
                break;
            default:
                $msg = "3";
                $end = [
                "ending" => "unknow error!"
                    ];
                return json_encode($end);
                break;
        }
    }

    /**
     * 取得編輯成功資訊
     *@param string $id 圖片id
     * @return JSON {ending:'edit okay'}
     */
    public function edit($id)
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $photo = Photo::load($id);
        $photo->setTitle($t);
        $photo->setDescription($d);
        $photo->save();
        $end = [
            "ending" => "edit okay"
        ];
        return json_encode($end);
    }

    /**
     * 取得刪除成功資訊
     *@param string $id 圖片id
     * @return JSON {ending:'delete okay'}
     */
    public function del($id)
    {
        $photo = Photo::load($id);
        $photo->delete();
        $end = [
            "ending" => "delete okay"
        ];
        return json_encode($end);
    }
    /**
     * 取得照片
     *@param string $id 圖片id
     * @return 圖片
     */

    public function show($id)
    {
        $photo = Photo::load($id);
        header('Content-Type: image/jpeg');
        $photo->readFile();
    }
}
