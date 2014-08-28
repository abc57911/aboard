<?php

namespace FoodTest\Model\User;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use FoodTest\DataSet;
use FoodTest\Tool;
use Food\Model\User\User;

class UserTest extends PHPUnit_Extensions_Database_TestCase
{

    const NAME = 'test name';
    const EMAIL = 'test email';
    const EMAIL2 = 'test email 2';
    const NICK = 'test nick';
    const NICK2 = 'test nick2';
    const PASS = 'test pass';
    const PASS2 = 'test pass2';
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
                'member' => [
                    [
                        'id' => 1,
                        'name' => self::NAME,
                        'email' => self::EMAIL,
                        'nick' => self::NICK,
                        'pass' => self::PASS,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => null
                    ]
                ]
            ]
        );
    }

    public function getUpdatedDataSet($u)
    {
        $u->setTimezone(new \DateTimeZone('+08:00'));
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'member' => [
                    [
                        'id' => 1,
                        'name' => self::NAME,
                        'email' => self::EMAIL2,
                        'nick' => self::NICK2,
                        'pass' => self::PASS2,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => $u->format('Y-m-d H:i:s')
                    ]
                ]
            ]
        );
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('member'), 'Pre-Condition');
        $ret = User::create(self::NAME, self::EMAIL2, self::NICK2, self::PASS2);
        $this->assertInstanceOf('Food\Model\User\User', $ret);
        $this->assertEquals(2, $this->getConnection()->getRowCount('member'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('member'), 'Pre-Condition');
        $ret = User::load(1);
        $this->assertInstanceOf('Food\Model\User\User', $ret, 'User loading failed');
        $ret = User::load(2);
        $this->assertNull($ret, 'Loaded non-exist user');
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('member'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
        $this->assertEquals(0, $this->getConnection()->getRowCount('member'), 'Deleting failed');
        $ret->delete();
        $this->assertEquals(0, $this->getConnection()->getRowCount('member'), 'Unknown record deleted');
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('member'), 'Pre-Condition');
        $ret = User::load(1);
        $ret->setPassword(self::PASS2);
        $ret->setNick(self::NICK2);
        $ret->setEmail(self::EMAIL2);
        $ret->save();
        $actual = $this->getConnection()->createQueryTable(
            'member',
            'SELECT * FROM member WHERE id = 1'
        );
        $expect = $this->getUpdatedDataSet($ret->getUpdateTime())->getTable('member');
        $this->assertTablesEqual($expect, $actual, 'update failed');
        $ret->delete();
        $ret->save();
        $this->assertNull($ret->getToken(), 'Does not clear token after deleting');
    }
}
