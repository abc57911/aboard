<?php

namespace Food\Library\Guestbook;

use Food\Model\Album\Album;
use Food\Model\Album\Photo;
use Food\Model\Guestbook\PostPlug;
use Food\Library\Album\PhotoLibrary;

class PostPlugLibrary
{
    public static function addPostAlbum()
    {
        // Create Album and set default
        $album = Album::load(99999);
        if ($album == null) {
            $album = PostPlug::albumCreate();
        }

        return $album;
    }

    public static function verifyPost($post_plug, $content)
    {
        if ($_FILES['file']['name'] != null || $post_plug instanceof PostPlug) {
            $content_rule  = '/^.{0,65535}$/';
        } else {
            $content_rule  = '/^.{1,65535}$/';
        }
        $content  = htmlentities(trim($content), ENT_QUOTES, 'UTF-8');
        if (!preg_match($content_rule, $content)) {
            $msg = array(
                'status' => false,
                'msg' => 'Error. The content does not conform to rule.',
                'token'  => null
            );

            return $msg;
        }

        return $content;
    }

    public static function checkUpload()
    {
        if ($_FILES['file']['name'] != null) {
            $aid = self::addPostAlbum()->getToken();
            $title = 'Post photo';
            $desc = null;
            $upload = PhotoLibrary::doupload($aid, $title, $desc);
            $photo_obj = Photo::load($upload['Token']);

            return $photo_obj;
        }

        return false;
    }
}
