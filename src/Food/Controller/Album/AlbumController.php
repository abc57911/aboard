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
     * @return JSON {
     *         [
     *           Title:Title,
     *           Description:Description,
     *           Token:Token
     *         ];
     *         }
     */
    public function index()
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
        return json_encode($allalbum);
    }

    /**
     * 取得album($id)的所有照片資料
     *@param string $id 相簿id
     * @return JSON {
     *         [
     *           Title:Title,
     *           Description:Description,
     *           Path:Path
     *         ];
     *         }
     */   
    public function allphoto($id)
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
        return json_encode($allphoto);
    }

    /**
     * 取得album新增完成後資料
     * @param POST string title 相簿名
     * @param POST string desc 相簿描述
     * @return JSON {
     *         [
     *           Title:Title,
     *           Description:Description,
     *           Token:Token
     *         ];
     *         }
     */    
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

    /**
     * 取得編輯成功資訊
     *@param string $id 相簿id
     * @param POST string title 相簿名
     * @param POST string desc 相簿描述
     * @return JSON {'edit okay'}
     */
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

    /**
     * 取得刪除成功資訊
     *@param string $id 相簿id
     * @return JSON {'delete okay'}
     */
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
