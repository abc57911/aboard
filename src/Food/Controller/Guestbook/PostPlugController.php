<?php

namespace Food\Controller\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Thread;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;
use Food\Library\Guestbook\PostLibrary;
use Food\Library\Guestbook\ThreadLibrary;
use Food\Library\Album\AlbumLibrary;
use Food\Library\Album\PhotoLibrary;

use Food\Model\Guestbook\PostPlug;

class PostPlugController extends Seed
{
    /**
     * 列出所有標題及留言
     *
     * @return JSON { 'thread': {
     *     'token': token,
     *     'title': string,
     *     'post': {
     *         'token': {
     *             'success': bool,
     *             'string': string,
     *             'token': int,
     *             'content': string,
     *             'create_time': {...},
     *             'update_time': {...} or null,
     *             'photoID': string
     *            }
     *        }
     *    }   
     *}
     */
    public function index()
    {
        $msg = ThreadLibrary::index();
        $photo = PostPlug::listAll();
        foreach ($photo as $key) {
            $data = PostPlug::load($key);
            $postID = $data->getPostID();
            $photoID = $data->getPhotoID();
            $msg['thread'][1]['post'][$postID]['photoID'] = $photoID;
        }
        return json_encode($msg);
    }

    /**
     * 查看標題
     *
     * @param  int $token token
     * @return JSON { 'thread': {
     *     'success': bool,
     *     'string': string,
     *     'token': token,
     *     'title': string,
     *     'post': {
     *         'token': {
     *             'success': bool,
     *             'string': string,
     *             'token': int,
     *             'content': string,
     *             'create_time': {...},
     *             'update_time': {...} or null,
     *             'photoID': string
     *            }
     *        }
     *    }   
     *}
     */
    public function view($token = null)
    {
        $msg = ThreadLibrary::view($token);
        return json_encode($msg);
    }

    /**
     * 新增標題
     *
     * @param string $title title
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function threadCreate($title = null)
    {
        if ($title == null) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. title is null.',
                'token' => null
            );
        } else {
            $data = new ThreadLibrary;
            $thread = $data->create($title);
            if ($thread instanceof Thread) {
                $msg = array(
                    'status' => true,
                    'msg' => 'Success. Thread was created.',
                    'token' => $thread->getToken()
                );
            } else {
                $msg = array(
                    'status' => false,
                    'msg' => 'Error. Failed to create. Try agian later.',
                    'token' => null
                );
            }
        }
        return json_encode($msg);

    }

    /**
     * 修改標題
     *
     * @param  Thread $token token
     * @param  string $title title
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function threadEdit($token = null, $title = null)
    {
        if ($token == null || $title == null) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. token and title is null.',
                'token' => null
            );
        } else {
            $t = Thread::load($token);
            if ($t instanceof Thread) {
                $thread = ThreadLibrary::edit($t, $title);
                $msg = array(
                    'status' => true,
                    'msg' => 'Success. Thread was edited.',
                    'token' => $t->getToken()
                );
            } else {
                $msg = array(
                    'status' => false,
                    'msg' => 'Error. Failed to edit. Try agian later.',
                    'token' => null
                );
            }
        }
        return json_encode($msg);
    }

    /**
     * 刪除標題
     *
     * @param  int $token token
     * @return JSON { 'status': bool, 'msg': string}
     */
    public function threadDelete($token = null)
    {
        if ($token == null) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. token is null'
            );
        } else {
            $thread = Thread::load($token);
            if ($thread instanceof Thread) {
                $thread->delete();
                $msg = array(
                    'status' => true,
                    'msg' => 'Success. Thread was deleted.'
                );
            } else {
                $msg = array(
                    'status' => false,
                    'msg' => 'Error. Not found token #'.$token.'.'
                );
            }
        }
        return json_encode($msg);
    }

    /**
     * 新增內容
     *
     * @param  int $tid token
     * @param  string $content content
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function postCreate($tid = null, $content = null)
    {
        // Create Album
        if (isset($_FILES['file'])) {
            $data = Album::load(99999);
            if ($data == null) {
                $title = 'Post album';
                $desc = 'Save post photo';
                $data = PostPlug::albumCreate($title, $desc);
            }
            $aid = 99999;
            $title = 'Post photo';
            $desc = null;
            $upload = PhotoLibrary::doupload($aid, $title, $desc);
        }
        

    }

    /**
     * 修改內容
     * @param  int $token token
     * @param  string $content content
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function postEdit($token = null, $content = null)
    {

    }

    /**
     * 刪除留言
     *
     * @param  int $token token
     * @return JSON { 'status': bool, 'msg': string }
     */
    public function postDelete($token = null)
    {

    }

    /**
     * 新增圖片
     *
     * @para
     */
    public function photoCreate()
    {

    }

    public function photoEdit()
    {

    }

    public function photoDelete()
    {

    }
}
