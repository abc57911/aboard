<?php

namespace FoodTest\Model\Album;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use FoodTest\DataSet;
use FoodTest\Tool;
use Food\Model\Album\Album;

class AlbumTest extends PHPUnit_Extensions_Database_TestCase
{

    const TITLE = 'test';
    const TITLE2 = 'test2';
    const DESC = 'desc';
    const CREATE = 86400;

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'album' => [
                    [
                        'id' => 1,
                        'title' => self::TITLE,
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
    {
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'album' => [
                    [
                        'id' => 1,
                        'title' => self::TITLE2,
                        'description' => self::DESC,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('album'), 'Pre-Condition');
        $ret = Album::create(self::TITLE);
        $this->assertInstanceOf('Food\Model\Album\Album', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('album'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('album'), 'Pre-Condition');
        $ret = Album::load(1);
        $this->assertInstanceOf('Food\Model\Album\Album', $ret, 'Album loading failed');
        $ret = Album::load(2);
        $this->assertNull($ret, 'Loaded non-exist album');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('album'), 'Pre-Condition');
        $ret = Album::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('album'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('album'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('album'), 'Pre-Condition');
        $ret = Album::load(1);
        $ret->setTitle(self::TITLE2);
        $ret->setDescription(self::DESC);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('album', 'SELECT * FROM album');
        $expect = $this->getUpdatedDataSet()->getTable('album');
        $this->assertTablesEqual($expect, $actual, 'update failed');
    }

    public function testListAll()
    {
        $list = Album::listAll();
        $this->assertEquals(['1'], $list, 'Wtf are you listing!');
    }
}
