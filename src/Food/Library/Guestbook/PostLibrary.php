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
     * @return $msg = array(
     *     success => bool,
     *     string => msg,
     *     token => token,
     *     content => string,
     *     create_time => time,
     *     update_time => time
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
     * @return $msg = array(
     *     success => bool,
     *     string => msg,
     *     token => token
     * )
     */
    public function create($tid = null, $content = null)
    {
        $thread = Thread::load($tid);
        $content_rule  = '/^.{1,65535}$/';
        $content  = htmlentities(trim($content), ENT_QUOTES, 'UTF-8');
        if (is_null($thread)) {
            $msg = array(
                'success' => false,
                'string' => 'Error. Could not find thread.'
            );
        } elseif (preg_match($content_rule, $content)) {
            $post = Post::create($thread, $content);
            if ($post instanceof Post) {
                $msg = array(
                    'success' => true,
                    'string' => 'Success added post ' .$post->getContent(). '.',
                    'token' => $post->getToken()
                );
            } else {
                $msg = array(
                    'success' => false,
                    'string' => 'Error. add post ' .$content. ' failed. Please try again.'
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
     * 刪除內容
     * @param  int $token 代碼
     * @return $msg = array(
     *     success => bool,
     *     string = >msg
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
            $post = Post::load($token);
            if ($post instanceof Post) {
                $post->delete();
                $msg = array(
                    'success' => true,
                    'string' => 'Success deleted post.'
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
     * 修改內容
     * @param  int $token 代碼
     * @param  string $content 內容
     * @return $msg = array(
     *     success => bool,
     *     string => msg,
     *     token => token
     * )
     */
    public function edit($token = null, $content = null)
    {
        $content_rule  = '/^.{1,65535}$/';
        $content  = htmlentities(trim($content), ENT_QUOTES, 'UTF-8');
        if (is_null($token)) {
            $msg = array(
                'success' => false,
                'string' => 'Error. Token is null.'
            );
        } elseif (preg_match($content_rule, $content)) {
            $post = Post::load($token);
            if ($post instanceof Post) {
                $post->setContent($content);
                $post->save();
                $msg = array(
                    'success' => true,
                    'string' => 'Success updated Post.',
                    'token' => $token
                );
            } else {
                $msg = array(
                    'success' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
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
