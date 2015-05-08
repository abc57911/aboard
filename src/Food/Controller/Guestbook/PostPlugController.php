<?php

namespace Food\Controller\Guestbook;

use Fruit\Seed;
use Food\Model\Guestbook\Post;
use Food\Model\Guestbook\Thread;
use Food\Model\Guestbook\PostPlug;
use Food\Model\Album\Photo;
use Food\Model\Album\Album;
use Food\Library\Guestbook\PostLibrary;
use Food\Library\Guestbook\PostPlugLibrary;
use Food\Library\Guestbook\ThreadLibrary;
use Food\Library\Album\AlbumLibrary;
use Food\Library\Album\PhotoLibrary;

class PostPlugController extends Seed
{
    /**
     * 取得所有標題及留言
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
    public function getAllThread()
    {
        $threadAll = ThreadLibrary::index();

        foreach ($threadAll['thread'] as $thread_key => $t) {
            if (isset($t['post'])) {
                $arr = $t['post'];
                foreach ((array)$arr as $key => $p) {
                    $post = Post::load($key);
                    $post_plug = PostPlug::loadByPost($post);
                    if ($post_plug instanceof PostPlug) {
                        $threadAll['thread'][$thread_key]['post'][$key]['photoID'] = $post_plug->getPhotoID();
                    } else {
                        $threadAll['thread'][$thread_key]['post'][$key]['photoID'] = null;
                    }
                }
            }
        }

        header('Content-Type: application/json; charset=utf-8');
        return json_encode($threadAll);
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
    public function getThread($token = null)
    {
        $thread = ThreadLibrary::view($token);
        if (isset($thread['thread']['post'])) {
            $arr = $thread['thread']['post'];
            foreach ((array)$arr as $key => $p) {
                $post = Post::load($key);
                $post_plug = PostPlug::loadByPost($post);
                if ($post_plug instanceof PostPlug) {
                    $thread['thread']['post'][$key]['photoID'] = $post_plug->getPhotoID();
                } else {
                    $thread['thread']['post'][$key]['photoID'] = null;
                }
            }
        }
        return json_encode($thread);
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
            $thread = ThreadLibrary::create($title);
            if ($thread instanceof Thread) {
                $msg = array(
                    'status' => true,
                    'msg'    => 'Success. Thread was created.',
                    'thread' => array(
                        'token'  => $thread->getToken(),
                        'title'  => $thread->getTitle()
                    )
                );
            } else {
                $msg = array(
                    'status' => false,
                    'msg' => 'Error. Failed to create. Try agian later.',
                    'token' => null
                );
            }
        }

        header('Content-Type: application/json; charset=utf-8');
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
                'msg' => 'Error. token or title is null.',
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
        header('Content-Type: application/json; charset=utf-8');
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
     * 新增留言或圖片
     *
     * @param  POST int $tid thread's token
     * @param  POST string $content content
     * @param  FILES $_FILES['file'] photo
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function postCreate()
    {
        $tid = $_POST['tid'];
        $content = $_POST['content'];

        // Create Post
        $thread = Thread::load($tid);
        if (!$thread instanceof Thread) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. Not found the thread.',
                'token'  => null
            );

            return json_encode($msg);
        }
        
        $content = PostPlugLibrary::verifyPost($post = null, $content);
        if (is_array($content)) {

            return json_encode($content);
        }
        
        $post_obj = Post::create($thread, $content);
        $photo_obj = PostPlugLibrary::checkUpload();

        if ($photo_obj) {
            $post_plug = PostPlug::create($photo_obj, $post_obj);
            $msg = array(
                'status' => true,
                'msg' => 'Success. Post and photo was created.',
                'token' => $post_plug->getToken()
            );
        } else {
            $msg = array(
                'status' => true,
                'msg' => 'Success. Post was created.',
                'token' => null
            );

        }

        return json_encode($msg);
    }

    /**
     * 修改留言
     * @param  POST int $token post token
     * @param  POST string $content content
     * @param  FILES $_FILES['file'] photo
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function postEdit()
    {
        $post = (int)$_POST['token'];
        $content = $_POST['content'];

        $post_obj = Post::load($post);
        $post_plug = PostPlug::loadByPost($post_obj);
        $content = PostPlugLibrary::verifyPost($post_plug, $content);

        if (!$post_obj instanceof Post) {
            $msg = array(
                'status' => false,
                'msg' => 'Not found the post.',
                'token' => null
            );

            return json_encode($msg);
        } else {
            $post_obj->setContent($content);
            $post_obj->save();
            $msg = array(
                'status' => true,
                'msg' => 'Sucess. The post was edited',
                'token' => $post
            );
        }

        return json_encode($msg);
    }

    /**
     * 刪除留言
     *
     * @param  POST int $token token
     * @return JSON { 'status': bool, 'msg': string }
     */
    public function postDelete()
    {
        $post = (int)$_POST['token'];
        $post_obj = Post::load($post);

        if (!$post_obj instanceof Post) {
            $msg = array(
                'status' =>false,
                'msg' => 'Error. Failed to delete.',
                'token' => $post_plug->getToken()
            );

            return json_encode($msg);
        }
        $post_plug = PostPlug::loadByPost($post_obj);
        if ($post_plug instanceof PostPlug) {
            $photoID = $post_plug->getPhotoID();
        }
        $photo_obj = Photo::load($photoID);
        $photo_obj->delete();
        $post_obj->delete();

        
        $msg = array(
            'status' =>true,
            'msg' => 'Success. Post was deleted.',
            'token' => $post_plug->getToken()
        );

        return json_encode($msg);
    }

    /**
     * 留言新增圖片
     *
     * @param POST int $token post's token
     * @param  FILES $_FILES['file'] photo
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function photoCreate()
    {
        $post = (int)$_POST['token'];

        $post_obj = Post::load($post);
        if ($post_obj == null) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. Not found the post.',
                'token' => null
            );

            return json_encode($msg);
        }

        $verify_photo = PostPlug::loadByPost($post_obj);
        if ($verify_photo instanceof PostPlug) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. Can not create again.',
                'token' => null
            );
            
            return json_encode($msg);
        }

        if (isset($_FILES['file'])) {
            $photo_obj = PostPlugLibrary::checkUpload();
            if ($photo_obj) {
                $post_plug = PostPlug::create($photo_obj, $post_obj);
                $msg = array(
                    'status' => true,
                    'msg' => 'Success. Photo was created.',
                    'token' => $post_plug->getToken()
                );
            } else {
                $msg = array(
                    'status' => false,
                    'msg' => 'Error. Unselected file.',
                    'token' => null
                );

                return json_encode($msg);
            }
        }

        return json_encode($msg);
    }

    /**
     * 留言更換圖片
     *
     * @param POST int $token post's token
     * @param  FILES $_FILES['file'] photo
     * @return JSON { 'status': bool, 'msg': string, 'token': token }
     */
    public function photoEdit()
    {
        $post = (int)$_POST['token'];

        $post_obj = Post::load($post);
        if (!$post_obj instanceof Post) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. Not found the post.',
                'token' => null
            );
            
            return json_encode($msg);
        }
        $post_plug = PostPlug::loadByPost($post_obj);
        if (!$post_plug instanceof PostPlug) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. The post has no photo.',
                'token' => null
            );

        }
        $photoID = $post_plug->getPhotoID();
        $photo_obj = Photo::load($photoID);
        $photo_obj->delete();

        $upload = PhotoLibrary::doupload($aid, $title, $desc);
        $post_plug->setPhoto($upload['Token']);
        $post_plug->save();
        $msg = array(
            'status' => true,
            'msg' => 'Success. Photo was edited.',
            'token' => $post_plug->getToken()
        );

        return json_encode($msg);
    }


    /**
     * 留言刪除圖片
     *
     * @param POST int $token post's token
     * @return JSON { 'status': bool, 'msg': string}
     */
    public function photoDelete()
    {
        $post = (int)$_POST['token'];
        $post_obj = Post::load($post);
        if (!$post_obj instanceof Post) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. Not found the post.'
            );
            
            return json_encode($msg);
        }
        $post_plug = PostPlug::loadByPost($post_obj);
        $photoID = $post_plug->getPhotoID();
        $photo_obj = Photo::load($photoID);

        $content = $post_obj->getContent();
        if ($content == null) {
            $this->postDelete();
            $msg = array(
                'status' => true,
                'msg' => 'Success. The post was deleted.'
            );

            return json_encode($msg);
        }
        $post_plug->delete();
        $photo_obj->delete();

        $msg = array(
            'status' => true,
            'msg' => 'Success. Photo was deleted.'
        );

        return json_encode($msg);
    }
}
