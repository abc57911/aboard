<?php

namespace Food\Controller\Guestbook;


class PostPlugController extends Seed
{

    public function index()
    {

    }

    public function view()
    {

    }

    /**
     * 新增標題
     *
     * @param string $title 標題
     * @return array(
     *     'success' => bool,
     *     'string' => msg,
     *     'token' => token
     */
    public function threadCreate($title = null)
    {

    }

    /**
     * 修改標題
     *
     * @param  int $token 代碼
     * @param  string $title 標題
     * @return array(
     *     'success' => bool,
     *     'string' => msg,
     *     'token' => token
     * )
     */
    public function threadEdit($token = null, $title = null)
    {

    }

    /** 
     * 新增內容
     * @param  int $tid 代碼
     * @param  string $content 內容
     * @return JSON { 'success':bool, 'string':msg, 'token':token}
     */
    public function postCreate($tid = null, $content = null)
    {

    }

    /** 
     * 修改內容
     * @param  int $token 代碼
     * @param  string $content 內容
     * @return JSON { 'success':bool, 'string':msg, 'token':token }
     */
    public function postEdit($token = null, $content = null)
    {

    }
}
