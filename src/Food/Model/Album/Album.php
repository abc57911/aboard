<?php

namespace Food\Model\Album;

use PDO;
use DateTime;
use Fruit\Seed;

class Album extends Seed
{
    private $id;
    private $title;
    private $desc;
    private $create;

    protected function __construct($i, $t, $d, $c)
    {
        $this->id = $i;
        $this->title = $t;
        $this->desc = $d;
        $this->create = $c;
    }

    private static function bind($s, $p, $v = null, $t = PDO::PARAM_STR)
    {
        if ($v == null) {
            $s->bindValue($p, $v, PDO::PARAM_NULL);
        } else {
            $s->bindValue($p, $v, $t);
        }
    }

    /**
     * 建立相簿
     *
     * @param string $title title
     * @param string $desc description or null
     * @return Album object or null
     */
    public static function create($title, $desc = null)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO album (title, description) VALUES (?,?)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $title, PDO::PARAM_STR);
        self::bind($stmt, 2, $desc);

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }
        $stmt->closeCursor();
        return null;
    }

    /**
     * 取得相簿
     *
     * @param mixed $id token
     * @return Album object
     */
    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT title, description, UNIX_TIMESTAMP(create_time) FROM album WHERE id = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1], $res[2]);
            }
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 刪除
     */
    public function delete()
    {
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM album WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }

    /**
     * 儲存
     */
    public function save()
    {
        if ($this->id == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE album SET title = ?, description = ? WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->title, PDO::PARAM_STR);
        self::bind($stmt, 2, $this->desc, PDO::PARAM_STR);
        $stmt->bindValue(3, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 設定標題
     *
     * @param string $t title
     */
    public function setTitle($t)
    {
        $this->title = $t;
    }

    /**
     * 設定說明
     *
     * @param string $d description
     */
    public function setDescription($d)
    {
        $this->desc = $d;
    }

    /**
     * 取得標題
     *
     * @return string as title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 取得說明
     *
     * @return string as description
     */
    public function getDescription()
    {
        return $this->desc;
    }

    /**
     * 取得代碼
     *
     * @return mixed token
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 取得日期
     *
     * @return DateTime object
     */
    public function getCreate()
    {
        return new DateTime('@' . $this->create);
    }

    /**
     * 取得所有相簿
     *
     * @return array of tokens or null
     */
    public static function listAll()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM album';

        $stmt = $db->prepare($sql);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 取得相簿中的所有圖片
     *
     * @return array of tokens to Photo
     */
    public function listPhoto()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT path FROM photo WHERE aid = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        $ret = null;
        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }
}
