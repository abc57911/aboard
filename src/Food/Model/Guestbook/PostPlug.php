<?php

namespace Food\Model\Guestbook;

use Food\Model\Album\Photo;
use Food\Model\Guestbook\Post;
use Fruit\Seed;
use PDO;

class PostPlug extends Seed
{
    private $id;
    private $PostID;
    private $PhotoID;
    private $post_obj_cache;
    private $photo_obj_cache;

    protected function __construct($id, $PostID, $PhotoID)
    {
        $this->id      = $id;
        $this->PostID  = $PostID;
        $this->PhotoID = $PhotoID;

        $this->post_obj_cache  = null;
        $this->photo_obj_cache = null;
    }

    /**
     * 新增留言圖片
     *
     * @param Photo $Photo_obj object
     * @param object $Post_obj object
     * @return PostPlug object or null
     */
    public static function create(Photo $Photo_obj, Post $Post_obj)
    {
        $db   = self::getConfig()->getDb();
        $sql  = 'INSERT INTO post_plug (post_id, photo_id) VALUES (?, ?)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $Photo_obj->getToken());
        $stmt->bindValue(2, $Post_obj->getToken());

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    /**
     * 取得留言圖片
     *
     * @param mixed $id
     * @return PostPlug object or null
     */
    public static function load($id)
    {
        $db  = self::getConfig()->getDb();
        $sql = 'SELECT post_id, photo_id FROM post_plug WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);

        if (!$stmt->execute()) {
            return null;
        }

        $res = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();

        if ($res != null) {
            return new self($id, $res[0], $res[1]);
        }

        return null;

    }

    /**
     * 設定圖片ID
     *
     * @param Photo $Photo_obj object
     */
    public function setPhoto(Photo $Photo_obj)
    {
        $this->photo_obj_cache = $Photo_obj;
        $this->PhotoID         = $Photo_obj->getToken();
    }

    /**
     * 刪除該筆資料（留言與圖片）
     */
    public function delete()
    {
        $db  = self::getConfig()->getDb();
        $sql = 'DELETE FROM post_plug WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        if ($stmt->execute()) {
            $this->id      = null;
            $this->PostID  = null;
            $this->PhotoID = null;
        }

    }

    /**
     * 儲存
     */
    public function save()
    {
        $db  = self::getConfig()->getDb();
        $sql = 'UPDATE post_plug SET ';
        $sql .= 'post_id = ?, photo_id = ? ';
        $sql .= 'WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->PostID);
        $stmt->bindValue(2, $this->PhotoID);
        $stmt->bindValue(3, $this->id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 取得token
     *
     * @return mixed token
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 取得PhotoID
     *
     * @return string PhotoID
     */
    public function getPhotoId()
    {
        return $this->PhotoID;
    }

    /**
     * 取得Photo object
     *
     * @return Photo object
     */
    public function getPhoto()
    {
        if ($this->photo_obj_cache == null) {
            $this->photo_obj_cache = Photo::load($this->PhotoID);
        }
        return $this->photo_obj_cache;
    }

    /**
     * 取得Post object
     *
     * @return Post object
     */
    public function getPost()
    {
        if ($this->post_obj_cache == null) {
            $this->post_obj_cache = Post::load($this->PostID);
        }
        return $this->post_obj_cache;
    }

    /**
     * 取得所有留言的Photo
     *
     * @return array of tokens
     */
    public function listAll()
    {
        $db  = self::getConfig()->getDb();
        $sql = 'SELECT id FROM post_plug';

        $stmt = $db->prepare($sql);
        $ret  = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }
}
