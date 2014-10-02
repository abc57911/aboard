<?php

namespace Food\Library\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Post;
use Food\Model\Guestbook\Thread;

class PostLibrary extends Seed
{
    /**
     * 查看內容
     * @param  int $token 代碼
     * @return array(
     *     'success' => bool,
     *     'string' => msg,
     *     'token' => token,
     *     'content' => string,
     *     'create_time' => time,
     *     'update_time' => time
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
            $post = Post::load($token);
            if ($post instanceof Post) {
                $msg = array(
                    'success' => true,
                    'string' => 'Success. You can use post to do something',
                    'token' => $post->getToken(),
                    'content' => $post->getContent(),
                    'create_time' => $post->getCreateTime(),
                    'update_time' => $post->getUpdateTime(),
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
     * 新增內容
     * @param  int $tid 代碼
     * @param  string $content 內容
     * @return Post object or null
     */
    public static function create($tid, $content)
    {
        $thread = Thread::load($tid);
        $content_rule  = '/^.{1,65535}$/';
        $content  = htmlentities(trim($content), ENT_QUOTES, 'UTF-8');
        if ($thread instanceof Thread) {
            if (preg_match($content_rule, $content)) {
                $post = Post::create($thread, $content);
                if ($post instanceof Post) {
                    return $post;
                }
            }
        }
        $post = null;
        return $post;
    }

    /** 
     * 修改內容
     * @param  Post $p Post object
     * @param  string $content 內容
     */
    public static function edit($p, $content)
    {
        $content_rule  = '/^.{1,65535}$/';
        $content  = htmlentities(trim($content), ENT_QUOTES, 'UTF-8');
        if (preg_match($content_rule, $content)) {
            if ($p instanceof Post) {
                $p->setContent($content);
                $p->save();
            }
        }
    }
}
