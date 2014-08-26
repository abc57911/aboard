<?php

namespace FoodTest\Model\Guestbook;

use PHPUnit_Extensions_Database_TestCase;
use FoodTest\DataSet;
use FoodTest\Tool;
use Food\Model\Guestbook\Thread;

class ThreadTest extends PHPUnit_Extensions_Database_TestCase
{

    const TITLE = 'test';

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => 'test title']
                ]
            ]
        );
    }

    public function getUpdatedDataSet()
    {
        return new DataSet(
            [
                'thread' => [
                    ['id' => 1, 'title' => self::TITLE]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::create(self::TITLE);
        $this->assertInstanceOf('Food\Model\Guestbook\Thread', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('thread'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $this->assertInstanceOf('Food\Model\Guestbook\Thread', $ret, 'Thread loading failed');
        $ret = Thread::load(2);
        $this->assertNull($ret, 'Loaded non-exist thread');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('thread'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('thread'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('thread'), 'Pre-Condition');
        $ret = Thread::load(1);
        $ret->setTitle(self::TITLE);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable('thread', 'SELECT * FROM thread');
        $expect = $this->getUpdatedDataSet()->getTable('thread');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }
}
