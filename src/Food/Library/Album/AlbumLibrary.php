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
     *         or array $end = [ending => 'no albums']
     */
    public static function allalbum()
    {
        $albums = Album::listAll();
        if ($albums != null) {
            foreach ($albums as $a) {
                $album = Album::load($a);
                $allalbum[$a] = [
                    'Title' => $album->getTitle(),
                    'Description' => $album->getDescription(),
                    'Token' => $album->getToken()
                ];
            }
            return $allalbum;
        } else {
            $end = [
                "ending" => "no albums"
            ];
            return $end;
        }
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
     *         Token => Token,
     *         Path => Path
     *         ];
     *         or
     *         array $end = [ending => 'no album']
     */
    public static function allphoto($id)
    {
        $album = Album::load($id);
        if ($album != null) {
            $aaa = $album->listPhoto();
            foreach ($aaa as $a) {
                $photo = Photo::load($a);
                $allphoto[$a] = [
                    'Title' => $photo->getTitle(),
                    'Description' => $photo->getDescription(),
                    'Token' => $photo->getToken(),
                    'Path' => 'aboard/photo/show/' . $photo->getToken()
                ];
            }
            return $allphoto;
        } else {
            $end = [
                "ending" => "no album"
            ];
            return $end;
        }
    }

    /**
     * 取得album($id)的相簿資料
     *
     * @param string $id
     *            相簿id
     * @return array $albummsg
     *         [
     *         Title => Title,
     *         Description => Description,
     *         Token => Token,
     *         create => create
     *         ];
     *         or
     *         array $end = [ending => 'no album']
     */
    public static function albummsg($id)
    {
        $album = Album::load($id);
        if ($album != null) {
            $albummsg = [
                'Title' => $album->getTitle(),
                'Description' => $album->getDescription(),
                'Token' => $album->getToken(),
                'Create' => $album->getCreate()
            ];
        } else {
            $end = [
                "ending" => "no album"
            ];
            return $end;
        }
    }

    /**
     * 取得album新增完成後資料
     *
     * @param string $title
     *            相簿名
     * @param string $desc
     *            相簿描述
     * @return array $end
     *         [
     *         Title => Title,
     *         Description => Description,
     *         create => create,
     *         Token => Token
     *         ];
     */
    public static function create($title, $desc)
    {
        $t = $title;
        $d = $desc;
        $album = Album::create($t, $d);
        $end["0"] = [
            "title" => $album->getTitle(),
            "desc" => $album->getDescription(),
            "create" => $album->getCreate(),
            'Token' => $album->getToken()
        ];
        return $end;
    }

    /**
     * 取得編輯成功資訊
     *
     * @param string $id
     *            相簿id
     * @param string $title
     *            相簿名
     * @param string $desc
     *            相簿描述
     * @return array $end = [ending => 'edit okay' or 'edit error']
     */
    public static function edit($id, $title, $desc)
    {
        $t = $title;
        $d = $desc;
        $album = Album::load($id);
        if ($album != null) {
            $album->setTitle($t);
            $album->setDescription($d);
            $album->save();
            $end = [
                "ending" => 'edit okay'
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
     *            相簿id
     * @return array $end = [ending => 'delete okay' or 'delete error']
     */
    public static function del($id)
    {
        $album = Album::load($id);
        if ($album != null) {
            $album->delete();
            $end = [
                "ending" => "delete okay"
            ];
            $aaa = $album->listPhoto();
            foreach ($aaa as $a) {
                $photo = Photo::load($a);
                $photo->delete();
                return $end;
            }
        } else {
            $end = [
                "ending" => "delete error"
            ];
            return $end;
        }
    }
}

