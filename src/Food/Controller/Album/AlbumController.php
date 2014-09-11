<?php
namespace Food\Controller\Album;

use Fruit\Seed;
use Fruit\Model;
use Food\Model\Album\Album;
use Food\Model\Album\Photo;

class AlbumController extends Seed
{

    /**
     * 取得所有album資料
     *
     * @return JSON $allalbum = {Token :{Title : Title, Description : Description}}
     *         or
     *         JSON $end = {ending : 'no album'}
     */
    public function index()
    {
        $albums = Album::listAll();
        if ($albums != null) {
            foreach ($albums as $a) {
                $album = Album::load($a);
                $allalbum[$a] = [
                    'Title' => $album->getTitle(),
                    'Description' => $album->getDescription()
                ];
            }
            return json_encode($allalbum);
        } else {
            $end = [
                "ending" => "no albums"
            ];
            return json_encode($end);
        }
    }

    /**
     * 取得album($id)的所有照片資料
     *
     * @param string $id
     *            相簿id
     * @return JSON $allphoto = {Token : {Title : Title, Description : Description, Path : Path}}
     *         or
     *         JSON $end = {ending : 'no album'}
     */
    public function allphoto($id)
    {
        $album = Album::load($id);
        if ($album != null) {
            $aaa = $album->listPhoto();
            foreach ($aaa as $a) {
                $photo = Photo::load($a);
                $allphoto[$a] = [
                    'Title' => $photo->getTitle(),
                    'Description' => $photo->getDescription(),
                    'Path' => 'aboard/photo/show/' . $photo->getToken()
                ];
            }
            return json_encode($allphoto);
        } else {
            $end = [
                "ending" => "no album"
            ];
            return json_encode($end);
        }
    }

    /**
     * 取得album新增完成後資料
     *
     * @param
     *            POST string $title
     *            相簿名
     * @param
     *            POST string $desc
     *            相簿描述
     * @return JSON $end = {Title : Title, Description : Description,
     *         create : create, Token : Token}
     */
    public function create()
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $album = Album::create($t, $d);
        $end["0"] = [
            "title" => $album->getTitle(),
            "desc" => $album->getDescription(),
            "create" => $album->getCreate(),
            'Token' => $album->getToken()
        ];
        return json_encode($end);
    }

    /**
     * 取得編輯成功資訊
     *
     * @param string $id
     *            相簿id
     * @param
     *            POST string $title
     *            相簿名
     * @param
     *            POST string $desc
     *            相簿描述
     * @return JSON $end = {ending:'edit okay' or 'edit error'}
     */
    public function edit($id)
    {
        $t = $_POST["title"];
        $d = $_POST["desc"];
        $album = Album::load($id);
        if ($album != null) {
            $album->setTitle($t);
            $album->setDescription($d);
            $album->save();
            $end = [
                "ending" => 'edit okay'
            ];
            return json_encode($end);
        } else {
            $end = [
                "ending" => "edit error"
            ];
            return json_encode($end);
        }
    }

    /**
     * 取得刪除成功資訊
     *
     * @param string $id
     *            相簿id
     * @return JSON $end = {ending:'delete okay' or 'delete error'}
     */
    public function del($id)
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
                return json_encode($end);
            }
        } else {
            $end = [
                "ending" => "delete error"
            ];
            return json_encode($end);
        }
    }
}
