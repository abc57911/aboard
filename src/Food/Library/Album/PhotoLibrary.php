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
     * @return array $end = [
     *         'Title' => Title,
     *         'Description' => Description,
     *         'Token' => Token
     *         ]
     *         or
     *         $end = [ending =>'file type error!'or'unknow error!']
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
                    'Title' => $photo->getTitle(),
                    'Description' => $photo->getDescription(),
                    'Token' => $photo->getToken()
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
     * @return array $end = [ending => 'edit okay' or 'edit error']
     */
    public static function edit($id, $title, $desc)
    {
        $t = $title;
        $d = $desc;
        $photo = Photo::load($id);
        if ($photo != null) {
            $photo->setTitle($t);
            $photo->setDescription($d);
            $photo->save();
            $end = [
                "ending" => "edit okay"
            ];
            return $end;
        } else {
            $end = [
                "ending" => "edit error"
            ];
            return $end;
        }
    }

    /**
     * 取得刪除成功資訊
     *
     * @param string $id
     *            圖片id
     * @return array $end = [ending => 'delete okay' or 'delete error' ]
     */
    public static function del($id)
    {
        $photo = Photo::load($id);
        if ($photo != null) {
            $photo->delete();
            $end = [
                "ending" => "delete okay"
            ];
            return $end;
        } else {
            $end = [
                "ending" => "delete error"
            ];
            return $end;
        }
    }

    /**
     * 取得照片
     *
     * @param string $id
     *            圖片id
     * @return 圖片 or array $end = [ending => 'no image']
     */
    public static function show($id)
    {
        $photo = Photo::load($id);
        if ($photo != null) {
            header('Content-Type: image/jpeg');
            $photo->readFile();
        } else {
            $end = [
                "ending" => "no image"
            ];
            return $end;
        }
    }

    /**
     * 取得照片明細
     *
     * @param string $id
     *            圖片id
     * @return 圖片 or array $photomsg =
     *         [
     *         Title => Title,
     *         Description => Description,
     *         Token => Token,
     *         Path => Path
     *         ];
     *         or array $end = [ending => 'no image']
     */
    public static function picmsg($id)
    {
        $photo = Photo::load($id);
        if ($photo != null) {
            $photomsg = [
                'Title' => $photo->getTitle(),
                'Description' => $photo->getDescription(),
                'Token' => $photo->getToken(),
                'Path' => 'aboard/photo/show/' . $photo->getToken()
            ];
            return $photomsg;
        } else {
            $end = [
                "ending" => "no image"
            ];
            return $end;
        }
    }
}
