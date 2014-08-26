<?php

namespace Food\Model\Album;

use PDO;
use DateTime;
use Fruit\Seed;

class Photo extends Seed
{
    private $path;
    private $mime;
    private $title;
    private $desc;
    private $create;
    private $aid;
    private $album_obj_cache;

    protected function __construct($p, $m, $t, $d, $c, $a)
    {
        $this->path = $p;
        $this->mime = $m;
        $this->title = $t;
        $this->desc = $d;
        $this->create = $c;
        $this->aid = $a;
        $this->album_obj_cache = null;
    }

    private static function bind($s, $p, $v = null, $t = PDO::PARAM_STR)
    {
        if ($v == null) {
            $s->bindValue($p, $v, PDO::PARAM_NULL);
        } else {
            $s->bindValue($p, $v, $t);
        }
    }

    private static function generate($f)
    {
        $base_dir = self::getConfig()->get('dir', 'upload');
        $prefix = self::getConfig()->get('prefix', 'upload');
        return tempnam($base_dir, $prefix);
    }

    private static function fn($f)
    {
        $base_dir = self::getConfig()->get('dir', 'upload');
        return $base_dir . DIRECTORY_SEPARATOR . $f;
    }

    /**
     * 新增相片
     *
     * @param Album $album album
     * @param string $path path to tmpfile
     * @param string $title title
     * @param string $desc description or null
     * @return Photo object or null
     */
    public static function create($album, $path, $title, $desc = null)
    {
        $db = self::getConfig()->getDb();
        $sql = 'INSERT INTO photo ';
        $sql .= '(path, title, description, mime, aid) VALUES (?,?,?,?,?)';

        if (!is_file($path)) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);
        if (substr($mime, 0, 6) != 'image/') {
            return null;
        }
        $fn = self::generate($path);

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, basename($fn));
        $stmt->bindValue(2, $title);
        self::bind($stmt, 3, $desc);
        $stmt->bindValue(4, $mime);
        $stmt->bindValue(5, $album->getToken());

        if ($stmt->execute()) {
            $stmt->closeCursor();
            copy($path, $fn);
            return self::load(basename($fn));
        }

        $stmt->closeCursor();
        return null;
    }

    /**
     * 取得相片
     *
     * @param mixed $id token
     * @return Photo object
     */
    public static function load($id)
    {
        $db = self::getConfig()->getDb();
        $sql = 'SELECT path, mime, title, description, UNIX_TIMESTAMP(create_time), aid ';
        $sql .= 'FROM photo WHERE path = ?';
        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $id);
        $ret = null;

        if ($stmt->execute()) {
            $res = $stmt->fetch(PDO::FETCH_NUM);
            if ($res != null) {
                $ret = new self($res[0], $res[1], $res[2], $res[3], $res[4], $res[5]);
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
        if ($this->path == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'DELETE FROM photo WHERE path = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->path);
        if ($stmt->execute()) {
            unlink(self::fn($this->path));
            $this->path = null;
        }
        $stmt->closeCursor();
    }

    /**
     * 儲存
     */
    public function save()
    {
        if ($this->path == null) {
            return;
        }
        $db = self::getConfig()->getDb();
        $sql = 'UPDATE photo SET title = ?, description = ?, aid = ? WHERE path = ?';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(1, $this->title);
        self::bind($stmt, 2, $this->desc);
        $stmt->bindValue(3, $this->aid);
        $stmt->bindValue(4, $this->path);
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
     * 取得代碼
     *
     * @return mixed token
     */
    public function getToken()
    {
        return $this->path;
    }

    /**
     * 取得標題
     *
     * @return string of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * 取得說明
     *
     * @return string of description or null
     */
    public function getDescription()
    {
        return $this->desc;
    }

    /**
     * 取得檔案位置
     *
     * @return string of file path
     */
    public function getPath()
    {
        return self::fn($this->path);
    }

    /**
     * 直接輸出檔案 (readfile)
     */
    public function readFile()
    {
        readfile(self::fn($this->path));
    }

    /**
     * 取得時間
     *
     * @return DateTime object
     */
    public function getCreate()
    {
        return new DateTime('@' . $this->create);
    }

    /**
     * 取得 MIME
     *
     * @return string of mime type
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * 取得相簿
     *
     * @return Album object or null
     */
    public function getAlbum()
    {
        if ($this->album_obj_cache == null) {
            $this->album_obj_cache = Album::load($this->aid);
        }
        return $this->album_obj_cache;
    }

    /**
     * 設定相簿
     *
     * @param Album $a Album object
     */
    public function setAlbum($a)
    {
        $this->album_obj_cache = $a;
        $this->aid = $a->getToken();
    }
}
