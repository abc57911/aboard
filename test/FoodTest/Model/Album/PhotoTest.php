<?php

namespace FoodTest\Model\Photo;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use FoodTest\DataSet;
use FoodTest\Tool;
use Food\Model\Album\Album;
use Food\Model\Album\Photo;

class PhotoTest extends PHPUnit_Extensions_Database_TestCase
{

    const TITLE = 'test';
    const TITLE2 = 'test2';
    const DESC = 'desc';
    const CREATE = 86400;
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';

    private $fn;
    
    public function __construct()
    {
        $this->fn = TEST_DIR . '/files/';
    }

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
                        'title' => 'test',
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 2,
                        'title' => 'test2',
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ]
                ],
                'photo' => [
                    [
                        'path' => self::FN,
                        'mime' => 'image/jpeg',
                        'title' => self::TITLE,
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'aid' => 1
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
                        'title' => 'test',
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ],
                    [
                        'id' => 2,
                        'title' => 'test2',
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s')
                    ]
                ],
                'photo' => [
                    [
                        'path' => self::FN,
                        'mime' => 'image/jpeg',
                        'title' => self::TITLE2,
                        'description' => self::DESC,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'aid' => 2
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('photo'), 'Pre-Condition');
        $ret = Photo::create(Album::load(1), $this->fn . self::FN2, self::TITLE);
        $this->assertInstanceOf('Food\Model\Album\Photo', $ret);
        $this->assertTrue(file_exists($ret->getPath()), 'Can not find photo file');
        $this->assertEquals(2, $this->getConnection()->getRowCount('photo'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('photo'), 'Pre-Condition');
        $ret = Photo::load(self::FN);
        $this->assertInstanceOf('Food\Model\Album\Photo', $ret, 'Photo loading failed');
        $ret = Photo::load(self::FN2);
        $this->assertNull($ret, 'Loaded non-exist photo');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('photo'), 'Pre-Condition');
        $ret = Photo::load(self::FN);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('photo'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('photo'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('photo'), 'Pre-Condition');
        $ret = Photo::load(self::FN);
        $ret->setTitle(self::TITLE2);
        $ret->setDescription(self::DESC);
        $ret->setAlbum(Album::load(2));
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('photo', 'SELECT * FROM photo');
        $expect = $this->getUpdatedDataSet()->getTable('photo');
        $this->assertTablesEqual($expect, $actual, 'update failed');
    }
}
