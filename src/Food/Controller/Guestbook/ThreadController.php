<?php

namespace Food\Controller\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Thread;
use Food\Controller\Guestbook\PostController;

class ThreadController extends Seed
{
    public function index()
    {
        $thread = Thread::listAll();
        $msg = array();
        foreach ($thread as $t) {
            $data = Thread::load($t);
            $title =  $data->getTitle();
            $msg[$t][0] = array (
                'token' => $t,
                'title' => $title
            );
            foreach ($data->listPosts() as $p) {
                $msg[$t][] = json_decode(PostController::view($p));
            }
        }
        return json_encode($msg);
    }
    public function view($token = null)
    {
        if (is_null($token)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $thread = Thread::load($token);
            if ($thread->getToken() == $token) {
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess. You can use thread to do something',
                    'token' => $thread->getToken(),
                    'title' => $thread->getTitle()
                );
                foreach ($thread->listPosts() as $p) {
                    $msg[] = json_decode(PostController::view($p));
                }
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. Could not find token ' .$token. '.'
                );
            }
        }
        return json_encode($msg);
    }
    public function create($title = null)
    {
        if (is_null($title)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $thread = Thread::create($title);
            if ($thread->getTitle() == $title) {
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess added thread ' .$thread->getTitle(). '.',
                    'token' => $thread->getToken()
                );
            } else {
                $msg = array(
                    'seccess' => false,
                    'string' => 'Error. add thread ' .$title. ' failed. Please try again.'
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
            $thread = Thread::load($token);
            if ($thread->getToken() == $token) {
                $thread->delete();
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess deleted thread.'
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
    public function edit($token = null, $title = null)
    {
        if (is_null($token) || is_null($title)) {
            $msg = array(
                'seccess' => false,
                'string' => 'Error. Could not find insert value.'
            );
        } else {
            $thread = Thread::load($token);
            if ($thread->getToken() == $token) {
                $thread->setTitle($title);
                $thread->save();
                $msg = array(
                    'seccess' => true,
                    'string' => 'Seccess updated thread.',
                    'token' =>$token
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
