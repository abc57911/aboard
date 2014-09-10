<?php

namespace Food\Library\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Thread;
use Food\Library\Guestbook\PostLibrary;

class ThreadLibrary extends Seed
{
    /**
     * listAll
     * @return $msg = array(
     *    thread = array(
     *        token => token,
     *        title => string,
     *        post => array()
     *    )
     *)
     */
    public function index()
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
     * @return $msg = array(
     *     thread => array(
     *         success => bool,
     *         string => msg,
     *         token => token,
     *         title => string
     *         post => array()
     *     )
     * )
     */
    public function view($token = null)
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
     * @return $msg = array(
     *     success => bool,
     *     string => msg,
     *     token => token
     * )
     */
    public function create($title = null)
    {
        $title_rule  = '/^.{1,128}$/';
        $title  = htmlentities(trim($title), ENT_QUOTES, 'UTF-8');
        if (preg_match($title_rule, $title)) {
            $thread = Thread::create($title);
            if ($thread instanceof Thread) {
                $msg = array(
                    'success' => true,
                    'string' => 'Success added thread ' .$thread->getTitle(). '.',
                    'token' => $thread->getToken()
                );
            } else {
                $msg = array(
                    'success' => false,
                    'string' => 'Error. add thread ' .$title. ' failed. Please try again.'
                );
            }
        } else {
            $msg = array(
                'success' => false,
                'string' => 'Error. Input string is not valid.'
            );
        }
        return $msg;
    }
    /**
     * 刪除標題
     * @param  int $token 代碼
     * @return $msg = array(
     *     success => bool,
     *     string => msg
     * )
     */
    public function delete($token = null)
    {
        if (is_null($token)) {
            $msg = array(
                'success' => false,
                'string' => 'Error. Token is null.'
            );
        } else {
            $thread = Thread::load($token);
            if ($thread instanceof Thread) {
                $thread->delete();
                $msg = array(
                    'success' => true,
                    'string' => 'Success deleted thread.'
                );
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
     * 修改標題
     * @param  int $token 代碼
     * @param  string $title 標題
     * @return $msg = array(
     *     success => bool,
     *     string => msg,
     *     token => token
     * )
     */
    public function edit($token = null, $title = null)
    {
        $title_rule  = '/^.{1,128}$/';
        $title  = htmlentities(trim($title), ENT_QUOTES, 'UTF-8');
        if (is_null($token)) {
            $msg = array(
                'success' => false,
                'string' => 'Error. Token is null.'
            );
        } elseif (preg_match($title_rule, $title)) {
            $thread = Thread::load($token);
            if ($thread instanceof Thread) {
                $thread->setTitle($title);
                $thread->save();
                $msg = array(
                    'success' => true,
                    'string' => 'Success updated thread.',
                    'token' =>$token
                );
            } else {
                $msg = array(
                    'success' => false,
                    'string' => 'Error. Could not find token #' .$token. '.'
                );
            }
        } else {
            $msg = array(
                'success' => false,
                'string' => 'Error. Input string is not valid.'
            );
        }
        return $msg;
    }
}
