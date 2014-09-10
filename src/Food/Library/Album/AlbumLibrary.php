<?php
namespace Food\Library\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Album;
use Food\Model\Album\Photo;

class AlbumLibrary extends Seed
{

    /**
     * 取得所有album資料
     *
     * @return array $allalbum
     *         [
     *         Title => Title,
     *         Description => Description,
     *         Token => Token
     *         ];
     */
    public static function allalbum()
    {
        $albums = Album::listAll();
        foreach ($albums as $a) {
            $album = Album::load($a);
            $allalbum[$a] = [
                'Title' => $album->getTitle(),
                'Description' => $album->getDescription(),
                'Token' => $album->getToken()
            ];
        }
        return $allalbum;
    }

    /**
     * 取得album($id)的所有照片資料
     *
     * @param string $id
     *            相簿id
     * @return array $allphoto
     *         [
     *         Title => Title,
     *         Description => Description,
     *         Path => Path
     *         ];
     *        
     */
    public static function allphoto($id)
    {
        $album = Album::load($id);
        $aaa = $album->listPhoto();
        foreach ($aaa as $a) {
            $photo = Photo::load($a);
            $allphoto[$a] = [
                'Title' => $photo->getTitle(),
                'Description' => $photo->getDescription(),
                'Path' => 'aboard/photo/show/' . $photo->getToken()
            ];
        }
        return $allphoto;
    }

    /**
     * 取得album新增完成後資料
     *
     * @param
     *            string title 相簿名
     * @param
     *            string desc 相簿描述
     * @return array $aaa
     *         [
     *         Title => Title,
     *         Description => Description,
     *         Token => Token
     *         ];
     */
    public static function create($title, $desc)
    {
        $t = $title;
        $d = $desc;
        $album = Album::create($t, $d);
        $aaa["0"] = [
            "title" => $album->getTitle(),
            "desc" => $album->getDescription(),
            "create" => $album->getCreate()
        ];
        return $aaa;
    }

    /**
     * 取得編輯成功資訊
     *
     * @param string $id
     *            相簿id
     * @param
     *            string title 相簿名
     * @param
     *            string desc 相簿描述
     * @return array $end = [ending => 'edit okay']
     */
    public static function edit($id)
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
        return $end;
    }

    /**
     * 取得刪除成功資訊
     *
     * @param string $id
     *            相簿id
     * @return array $end = [ending => 'delete okay']
     */
    public static function del($id)
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
        return $end;
    }
}
