<?php

namespace Food\Controller\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Post;
use Food\Model\Guestbook\Thread;

class PostController extends Seed
{
    public static function view($token = null)
    {
        if (is_null($token)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $post = Post::load($token);
            if (is_null($post)) {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
                );
            } elseif ($post->getToken() == $token) {
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess. You can use post to do something',
                    'token' => $post->getToken(),
                    'content' => $post->getContent(),
                    'create_time' => $post->getCreateTime(),
                    'update_time' => $post->getUpdateTime(),
                );
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token #' .$token. '.'
                );
            }
        }
        return json_encode($msg);
    }
    public function create($tid = null, $content = null)
    {
        $thread = Thread::load($tid);
        if (is_null($thread) || is_null($content)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $post = Post::create($thread, $content);
            if ($post->getContent() == $content) {
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess added post ' .$post->getContent(). '.',
                    'token' => $post->getToken()
                );
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. add post ' .$content. ' failed. Please try again.'
                );
            }
        }
        return json_encode($msg);
    }
    public function delete($token = null)
    {
        if (is_null($token)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $post = Post::load($token);
            if (is_null($post)) {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
                );
            } elseif ($post->getToken() == $token) {
                $post->delete();
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess deleted post.'
                );
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token #' .$token. '.'
                );
            }
        }
        return json_encode($msg);
    }
    public function edit($token = null, $content = null)
    {
        if (is_null($token) || is_null($content)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $post = Post::load($token);
            if (is_null($post)) {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
                );
            } elseif ($post->getToken() == $token) {
                $post->setContent($content);
                $post->save();
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess updated Post.',
                    'token' => $token
                );
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
                );
            }
        }
        return json_encode($msg);
    }
}
