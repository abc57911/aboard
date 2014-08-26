<?php

namespace Food\Model\Guestbook;

use PDO;
use Fruit\Seed;

/**
 * 討論串
 */
class Thread extends Seed
{
    private $id;

    /**
     * 討論串的標題
     */
    public $title;

    protected function __construct ($i, $t)
    {
        $this->id = $i;
        $this->title = $t;
    }

    /**
     * 新增一個討論串
     *
     * @param string $title 標題
     * @return Thread 物件
     */
    public static function create($title)
    {
        $db = self::getConfig()->getDb();
        
        $sql = 'INSERT INTO thread (title) VALUES (?)';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $title, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $ret = new self($id, $title);
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 用代碼取得討論串
     *
     * @param mixed $token 代碼
     * @return Thread 物件。找不到會回傳 null
     */
    public static function load($token)
    {
        $db = self::getConfig()->getDb();
        
        $sql = 'SELECT * FROM thread WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $token, PDO::PARAM_INT);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res != null) {
                $ret = new self($res['id'], $res['title']);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 刪除討論串
     */
    public function delete()
    {
        if ($this->id == null) {
            return;
        }

        $db = self::getConfig()->getDb();
        
        $sql = 'DELETE FROM thread WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
        $this->id = null;
    }
    
    /**
     * 把對討論串的修改回存
     */
    public function save()
    {
        if ($this->id == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE thread SET title = ? WHERE id = ?';
        $stmt = $db->prepare($sql);
        $stmt->bindvalue(1, $this->title);
        $stmt->bindValue(2, $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 取得這個討論串的代碼
     *
     * @return 代碼
     */
    public function getToken()
    {
        return $this->id;
    }
}
