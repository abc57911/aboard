<?php
namespace Food\Library\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;

class PhotoLibrary extends Seed
{

    /**
     *
     * @param string $id
     *            相簿id
     * @param string $title
     *            圖片名
     * @param string $desc
     *            圖片描述
     *            取得上傳成功資訊
     * @return array $end = [ending => 'edit okay'or'file type error!'or'unknow error!']
     */
    public static function doupload($id, $title, $desc)
    {
        if ($_FILES["file"]["error"] > 0) {
            $msg = '1';
        } else {
            if (file_exists($_FILES["file"]["name"])) {
                
                $msg = '2';
            } else {
                $album = Album::load($id);
                $t = $title;
                $d = $desc;
                $photo = Photo::create($album, $_FILES["file"]["tmp_name"], $t, $d);
                $end = [
                    "ending" => "upload ok"
                ];
                return $end;
                exit();
            }
        }
        switch ($msg) {
            case "1":
            case "2":
                $end = [
                    "ending" => "file type error!"
                ];
                return $end;
                break;
            default:
                $msg = "3";
                $end = [
                    "ending" => "unknow error!"
                ];
                return $end;
                break;
        }
    }

    /**
     * 取得編輯成功資訊
     * 
     * @param string $id
     *            圖片id
     * @param string $title
     *            圖片名
     * @param string $desc
     *            圖片描述
     * @return array $end = [ending => 'edit okay']
     */
    public static function edit($id, $title, $desc)
    {
        $t = $title;
        $d = $desc;
        $photo = Photo::load($id);
        $photo->setTitle($t);
        $photo->setDescription($d);
        $photo->save();
        $end = [
            "ending" => "edit okay"
        ];
        return $end;
    }

    /**
     * 取得刪除成功資訊
     * 
     * @param string $id
     *            圖片id
     * @return array $end = [ending => 'delete okay']
     */
    public static function del($id)
    {
        $photo = Photo::load($id);
        $photo->delete();
        $end = [
            "ending" => "delete okay"
        ];
        return $end;
    }

    /**
     * 取得照片
     * 
     * @param string $id
     *            圖片id
     * @return 圖片
     */
    public static function show($id)
    {
        $photo = Photo::load($id);
        header('Content-Type: image/jpeg');
        $photo->readFile();
    }
}
