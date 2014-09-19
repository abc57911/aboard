<?php

namespace FoodTest\Model\Guestbook;

use DateTime;
use PHPUnit_Extensions_Database_TestCase;
use FoodTest\DataSet;
use FoodTest\Tool;
use Food\Model\Guestbook\PostPlug;
use Food\Model\Album\Album;
use Food\Model\Album\Photo;
use Food\Model\Guestbook\Post;

class PostPlugTest extends PHPUnit_Extensions_Database_TestCase
{
    const POSTID = 1;
    const CREATE = 86400;
    const FN = 'test.jpg';
    const FN2 = 'test2.jpg';
    const TITLE = 'test';
    const DESC = 'desc';
    const CONTENT = 'content';

    public function getConnection()
    {
        return $this->createDefaultDBConnection(Tool::getConfig()->getDb(), "default");
    }

    public function getDataSet()
    {
        $d = new DateTime('@' . self::CREATE);
        return new DataSet(
            [
                'post_plug' => [
                    [
                        'id' => 1,
                        'photo_id' => self::FN,
                        'post_id' => self::POSTID,
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
                    ],
                    [
                        'path' => self::FN2,
                        'mime' => 'image/jpeg',
                        'title' => self::TITLE,
                        'description' => null,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'aid' => 1
                    ]
                ],
                'thread' => [
                    ['id' => 1, 'title' => 'test title']
                ],
                'post' => [
                    [
                        'id' => 1,
                        'content' => self::CONTENT,
                        'create_time' => $d->format('Y-m-d H:i:s'),
                        'update_time' => null,
                        'tid' => 1
                    ],
                ]
            ]
        );

    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post_plug'), 'Pre-Condition');
        $ret = PostPlug::create(Photo::load(self::FN), Post::load(1));
        $this->assertInstanceOf('Food\Model\Guestbook\PostPlug', $ret, 'PostPlug loading failed');
        $this->assertEquals(2, $this->getConnection()->getRowCount('post_plug'), 'Inserting failed');
    }

    public function testGet()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post_plug'), 'Pre-Condition');
        $ret = PostPlug::load(1);
        $this->assertInstanceOf('Food\Model\Guestbook\PostPlug', $ret, 'PostPlug loading failed');
        $this->assertInstanceOf('Food\Model\Album\Photo', $ret->getPhoto(), 'Photo loading failed');
        $this->assertInstanceOf('Food\Model\Guestbook\Post', $ret->getPost(), 'Post loading failed');
        $ret = PostPlug::load(2);
        $this->assertNull($ret);
    }

    public function testDelete()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post_plug'), 'Pre-Condition');
        $ret = PostPlug::load(1);
        $ret->delete();
        $this->assertNull($ret->getToken());
        $this->assertNull($ret->getPhoto());
        $this->assertNull($ret->getPost());
        $this->assertEquals(0, $this->getConnection()->getRowCount('post_plug'));
    }

    public function testUpdate()
    {
        $this->assertEquals(1, $this->getConnection()->getRowCount('post_plug'), 'Pre-Condition');
        $ret = PostPlug::load(1);
        $ret->setPhoto(Photo::load(self::FN2));
        $ret->save();
        $this->assertEquals($ret->getPhotoId(), self::FN2);
    }

    public function testList()
    {
        $expect = ['1'];
        $this->assertEquals($expect, PostPlug::listAll());
    }
}
