<?php

namespace Food\Model\User;

use PDO;
use DateTime;
use Fruit\Seed;

/**
 * 文章
 */
class User extends Seed
{
    private $id;
    private $name;
    private $email;
    private $nick;
    private $pass;
    private $create;
    private $update;

    protected function __construct($i, $n, $e, $k, $p, $c, $u)
    {
        $this->id = $i;
        $this->name = $n;
        $this->email = $e;
        $this->nick = $k;
        $this->pass = $p;
        $this->create = $c;
        $this->update = $u;
    }

    /**
     * 新增一個使用者
     *
     * @param string $name name (varchar 128)
     * @param string $email email (varchar 128)
     * @param string $nick nick name (varchar 128)
     * @param string $pass password (varchar 32)
     */
    public static function create($name, $email, $nick, $pass)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO member (name, email, nick, pass) VALUES (?,?,?,?)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $email);
        $stmt->bindValue(3, $nick);
        $stmt->bindValue(4, $pass);

        if ($stmt->execute()) {
            $id = $db->lastInsertId();
            $stmt->closeCursor();
            return self::load($id);
        }

        $stmt->closeCursor();
        return null;
    }

    /**
     * 取得使用者
     *
     * @param mixed $id token to get user
     * @return User object, or null
     */
    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT name, email, nick, pass, ';
        $sql .= 'UNIX_TIMESTAMP(create_time), UNIX_TIMESTAMP(update_time) FROM member WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($id, $res[0], $res[1], $res[2], $res[3], $res[4], $res[5]);
            }
        }

        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 存檔
     */
    public function save()
    {
        $this->update = (new DateTime())->getTimestamp();
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE member SET ';
        $sql .= 'name = ?, email = ?, nick = ?, pass = ?, update_time = FROM_UNIXTIME(?) ';
        $sql .= 'WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->name);
        $stmt->bindValue(2, $this->email);
        $stmt->bindValue(3, $this->nick);
        $stmt->bindValue(4, $this->pass);
        $stmt->bindValue(5, $this->update);
        $stmt->bindValue(6, $this->id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 刪除
     */
    public function delete()
    {
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM member WHERE id = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->id);
        if ($stmt->execute()) {
            $this->id = null;
        }
        $stmt->closeCursor();
    }

    /**
     * 取得代碼
     * @return mixed token
     */
    public function getToken()
    {
        return $this->id;
    }

    /**
     * 設定密碼
     *
     * @param string $p password
     */
    public function setPassword($p)
    {
        $this->pass = $p;
    }

    /**
     * 修改䁥稱
     *
     * @param string $n nickname
     */
    public function setNick($n)
    {
        $this->nick = $n;
    }

    /**
     * 修改 email
     *
     * @param string $e email
     */
    public function setEmail($e)
    {
        $this->email = $e;
    }

    /**
     * 取得名稱
     *
     * @return string of user name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 取得䁥稱
     *
     * @return string of nick name
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * 取得email
     *
     * @return string of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * 取得創帳號時間
     *
     * @return DateTime object
     */
    public function getCreateTime()
    {
        return new DateTime('@' . $this->create);
    }

    /**
     * 取得修改時間
     *
     * @return DateTime object or null
     */
    public function getUpdateTime()
    {
        if ($this->update == null) {
            return null;
        }
        return new DateTime('@' . $this->update);
    }

    /**
     * 確認密碼
     *
     * @param string $p password to check
     * @return bool true if password is right
     */
    public function validatePassword($p)
    {
        return $p === $this->pass;
    }

    /**
     * 取得所有使用者
     *
     * @return array of tokens
     */
    public static function listAll()
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM member';
        
        $stmt = $db->prepare($sql);
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 用名稱搜尋
     *
     * @param string $name user name
     * @return array of tokens or null
     */
    public static function listByName($name)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM member WHERE name LIKE ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, '%' . $name . '%');
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 用名稱搜尋
     *
     * @param string $email email address
     * @return array of tokens or null
     */
    public static function listByEmail($email)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM member WHERE email LIKE ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, '%' . $email . '%');
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }

    /**
     * 用名稱搜尋
     *
     * @param string $nick nick name
     * @return array of tokens or null
     */
    public static function listByNick($nick)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT id FROM member WHERE nick LIKE ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, '%' . $nick . '%');
        $ret = null;

        if ($stmt->execute()) {
            $ret = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        }
        $stmt->closeCursor();
        return $ret;
    }
}
