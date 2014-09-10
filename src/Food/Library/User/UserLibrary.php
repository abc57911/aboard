<?php
namespace Food\Library\User;

use Fruit\Seed;
use Food\Model\User\User;

class UserLibrary extends Seed
{
    /**取得所有user資料
     * @return array(
        array(
            'token'      => token,
            'name'       => name,
            'nickname'   => nickname,
            'email'      => email,
            'createtime' => createtime,
            'updatetime' => updatetime
        )...
    )
     */
    public function getAllUser()
    {
        $user;
        $users  = array();
        $tokens = User::listAll();

        foreach ($tokens as $key => $value) {
            $user = User::load($value);

            $users[$key] = array(
                'token'      => $value,
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            );
        }

        return $users;
    }

    /**依據使用者token取得資料
     * @param int $token 使用者token
     * @return array(
        'token'      => token,
        'name'       => name,
        'nickname'   => nickname,
        'email'      => email,
        'createtime' => createtime,
        'updatetime' => updatetime
    )
     */
    public function getUserByToken($token = null)
    {
        $user = User::load($token);

        if (!$user instanceof User) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        return array(
            'token'      => $token,
            'name'       => $user->getName(),
            'nick'       => $user->getNick(),
            'email'      => $user->getEmail(),
            'createtime' => $user->getCreateTime(),
            'updatetime' => $user->getUpdateTime()
        );
    }

    /**依據使用者名稱取得資料
     * @param string $name 使用者帳號
     * @return array(
        array(
            'token'      => token,
            'name'       => name,
            'nickname'   => nickname,
            'email'      => email,
            'createtime' => createtime,
            'updatetime' => updatetime
        )...
    )
     */
    public function getUserByName($name = null)
    {
        $user;
        $users = array();
        $tokens;

        if (!$name) {
            return array(
                'status' => false,
                'msg'    => '需要一個name參數'
            );
        }

        $tokens = User::listByName($name);

        if (!$tokens) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        foreach ($tokens as $key => $value) {
            $user = User::load($value);

            $users[$key] = array(
                'token'      => $value,
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            );
        }

        return $users;
    }

    /**依據使用者Email取得資料
     * @param string $email 使用者Email
     * @return array(
        array(
            'token'      => token,
            'name'       => name,
            'nickname'   => nickname,
            'email'      => email,
            'createtime' => createtime,
            'updatetime' => updatetime
        )...
    )
     */
    public function getUserByEmail($email = null)
    {
        $user;
        $users = array();
        $tokens;

        if (!$email) {
            return array(
                'status' => false,
                'msg'    => '需要一個email參數'
            );
        }

        $tokens = User::listByEmail($email);

        if (!$tokens) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        foreach ($tokens as $key => $value) {
            $user = User::load($value);

            $users[$key] = array(
                'token'      => $value,
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            );
        }

        return $users;
    }

    /**依據使用者Nick取得資料
     * @param string $nick 使用者Nick
     * @return array(
        array(
            'token'      => token,
            'name'       => name,
            'nickname'   => nickname,
            'email'      => email,
            'createtime' => createtime,
            'updatetime' => updatetime
        )...
    )
     */
    public function getUserByNick($nick = null)
    {
        $user;
        $users  = array();
        $tokens;

        if (!$nick) {
            return array(
                'status' => false,
                'msg'    => '需要一個nick參數'
            );
        }

        $tokens = User::listByNick($nick);

        if (!$tokens) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        foreach ($tokens as $key => $value) {
            $user = User::load($value);

            $users[$key] = array(
                'token'      => $value,
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            );
        }

        return $users;
    }

    /**新增使用者
     * @param string $name 使用者帳號(長度最少8最多16限小寫英文數字且不與資料庫的重複)
     * @param string $pass 使用者密碼 (字數長度最少8最多16只限小寫英文+數字)
     * @param string $nick 使用者暱稱 (長度最少2最多8只限中文)
     * @param string $email 使用者信箱 (email格式)
     * @return array(
        'token'  => token,
        'status' => bool(是否新增成功),
        'msg'    => string(訊息)
    )
     */
    public function addUser($name, $pass, $nick, $email)
    {
        $name  = $this->validatorFilterInput($name, '/^([0-9a-z]{8,16})$/');
        $pass  = $this->validatorFilterInput($pass, '/^([0-9a-z]{8,16})$/');
        $nick  = $this->validatorFilterInput($nick, '/^([\x7f-\xff]{6,24})$/');
        $email = $this->validatorFilterInput($email, '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/');

        if (!$name or !$pass or !$nick or !$email) {
            return array(
                'status' => false,
                'msg'    => '資料格式錯誤'
            );
        }

        if (User::listByName($name)) {
            return array(
                'status' => false,
                'msg'    => '帳號已經被使用'
            );
        }

        $user = User::create($name, $email, $nick, $pass);

        if ($user instanceof User) {
            $tokens = User::listByName($user->getName());
            return array(
                'token'  => $tokens[0],
                'status' => true,
                'msg'    => '新增成功'
            );
        }

        return 'error';
    }

    /**修改使用者
     * @param int $token 使用者token
     * @param string $password 使用者密碼 (字數長度最少8最多16只限小寫英文+數字)
     * @param string $nickname 使用者暱稱 (長度最少2最多8只限中文)
     * @param string $email 使用者信箱 (email格式)
     * @return array(
        'token'  => token,
        'status' => bool(是修改成功),
        'msg'    => string(訊息)
    )
     */
    public function editUser($token, $pass, $nick, $email)
    {
        $user  = User::load($token);
        $pass  = $this->validatorFilterInput($pass, '/^([0-9a-z]{8,16})$/');
        $nick  = $this->validatorFilterInput($nick, '/^([\x7f-\xff]{6,24})$/');
        $email = $this->validatorFilterInput($email, '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/');

        if (!$pass or !$nick or !$email) {
            return array(
                'status' => false,
                'msg'    => '資料格式錯誤'
            );
        }

        if (!$user instanceof User) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        $user->setEmail($email);
        $user->setNick($nick);
        $user->setPassword($pass);
        $user->save();

        return array(
            'token'  => $token,
            'status' => true,
            'msg'    => '修改成功'
        );
    }

    /**刪除使用者
     * @param int $token 使用者token
     * @return array(
        'status' => bool(是刪除改成功),
        'msg' => string(訊息)
    )
     */
    public function deleteUser($token = null)
    {
        $user  = User::load($token);

        if (!$user instanceof User) {
            return array(
                'status' => false,
                'msg'    => '查無使用者'
            );
        }

        $user->delete();

        return array(
            'status' => true,
            'msg'    => '刪除成功'
        );
    }

    /**過濾驗證
     * @param string $input
     * @param string $regexp
     * @return $input or false
     */
    private function validatorFilterInput($input, $regexp)
    {
        if (!preg_match($regexp, $input)) {
            return false;
        }

        return htmlentities(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}
