<?php

namespace Food\Library\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Thread;
use Food\Library\Guestbook\PostLibrary;

class ThreadLibrary extends Seed
{
    /**
     * listAll
     * @return array(
     *     thread => array(
     *         token(thread) => array(
     *             'token' => token,
     *             'title' => string,
     *             'post' => array(
     *                 token(post) => array(...)
     *             )
     *         )
     *      )
     * )
     */
    public static function index()
    {
        $thread = Thread::listAll();
        $msg = array();
        foreach ($thread as $t) {
            $data = Thread::load($t);
            $title =  $data->getTitle();
            $msg['thread'][$t] = array (
                'token' => $t,
                'title' => $title
            );
            foreach ($data->listPosts() as $p) {
                $msg['thread'][$t]['post'][$p] = PostLibrary::view($p);
            }
        }
        return $msg;
    }
    /**
     * 查看標題
     * @param  int $token 代碼
     * @return array(
     *     thread => array(
     *         'success' => bool,
     *         'string' => msg,
     *         'token' => token,
     *         'title' => string,
     *         'post' => array(
     *             'token'(post) => array(...)
     *         )
     *     )
     * )
     */
    public static function view($token = null)
    {
        if (is_null($token)) {
            $msg = array(
                'success' => false,
                'string' => 'Error. Token is null.'
            );
        } else {
            $thread = Thread::load($token);
            if ($thread instanceof Thread) {
                $msg['thread'] = array(
                    'success' => true,
                    'string' => 'Success. You can use thread to do something',
                    'token' => $thread->getToken(),
                    'title' => $thread->getTitle()
                );
                foreach ($thread->listPosts() as $p) {
                    $msg['thread']['post'][$p] = PostLibrary::view($p);
                }
            } else {
                $msg = array(
                    'success' => false,
                    'string' => 'Error. Could not find token #' .$token. '.'
                );
            }
        }
        return $msg;
    }
    /**
     * 新增標題
     * @param  string $title 標題
     * @return Thread object or null
     */
    public static function create($title)
    {
        $title_rule  = '/^.{1,128}$/';
        $title  = htmlentities(trim($title), ENT_QUOTES, 'UTF-8');
        if (preg_match($title_rule, $title)) {
            $thread = Thread::create($title);
            if ($thread instanceof Thread) {
                return $thread;
            }
        }
        $thread = null;
        return $thread;
    }

    /**
     * 修改標題
     * @param  Thread $t Thread object
     * @param  string $title 標題
     */
    public static function edit($t, $title)
    {
        $title_rule  = '/^.{1,128}$/';
        $title  = htmlentities(trim($title), ENT_QUOTES, 'UTF-8');
        if (preg_match($title_rule, $title)) {
            if ($t instanceof Thread) {
                $t->setTitle($title);
                $t->save();
            }
        }
    }
}
