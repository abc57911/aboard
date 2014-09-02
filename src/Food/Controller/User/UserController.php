<?php
namespace Food\Controller\User;

use DateTimeZone;
use Fruit\Seed;
use Food\Model\User\User;

use Fruit\ToolKit\Input;
use Fruit\ToolKit\Validator\RegexpRule;
use Fruit\ToolKit\Validator\EmailRule;

class UserController extends Seed
{
    public function __construct()
    {

    }

    //==================================
    //撈取所有使用者的資料並做需求使用。
    //撈取資料後可單純用來檢視使用者資料，
    //==================================
    /**取得所有user資料
     * @return JSON {
            users:user{
                name:name,
                nickname:nickname,
                email:email,
                createtime:createtime,
                updatetime:updatetime
            }
        }
     */
    public function getAllUser()
    {
        $users  = array();
        $user;
        $tokens = User::listAll();

        foreach ($tokens as $key => $value) {
            $user = User::load($value);

            $users[$key] = array(
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            );
        }

        return json_encode($users);
    }

    //==================================
    //撈取指定只用者的資料並做需求利用。
    //抓取指定使用者之後並做更新或刪除的動作，
    //或單純只是用來檢視資料。
    //==================================
    /**依據使用者名稱取得資料
     * @param string $name 使用者帳號
     * @return JSON user{name:name, nickname:nickname, email:email, createtime:createtime, updatetime:updatetime}
     */
    public function getUserByName($name = null)
    {
        $user;
        $token = User::listByName($name);

        if (!$token) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '查無使用者'
                )
            );
        }

        $user = User::load($token[0]);

        return json_encode(
            array(
                'name'       => $user->getName(),
                'nick'       => $user->getNick(),
                'email'      => $user->getEmail(),
                'createtime' => $user->getCreateTime(),
                'updatetime' => $user->getUpdateTime()
            )
        );
    }



    //==================================
    //註冊申請帳號之使用。
    //==================================
    /**新增使用者
     * @param POST string $name 使用者帳號(長度最少8最多16限小寫英文數字且不與資料庫的重複)
     * @param POST string $password 使用者密碼 (字數長度最少8最多16只限小寫英文+數字)
     * @param POST string $nickname 使用者暱稱 (長度最少2最多8只限中文)
     * @param POST string $email 使用者信箱 (email格式)
     * @return JSON {status:bool(是否新增成功), msg:string(訊息)}
     */
    public function addUser()
    {
        $user;
        $name;
        $email;
        $nick;
        $pass;
        $input      = new Input();
        $email_rule = new EmailRule();
        $name_rule  = new RegexpRule('/^([0-9a-z]{8,16})$/');
        $nick_rule  = new RegexpRule('/^([\x7f-\xff]{6,24})$/');
        $pass_rule  = new RegexpRule('/^([0-9a-z]{8,16})$/');

        $input
            ->add($name_rule, 'name', Input::POST)
            ->add($email_rule, 'email', Input::POST)
            ->add($nick_rule, 'nick', Input::POST)
            ->add($pass_rule, 'pass', Input::POST);

        if ($input->check() !== true) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '資料格式錯誤'
                )
            );
        }

        $name  = htmlentities(strip_tags(trim($_POST["name"])), ENT_QUOTES, 'UTF-8');
        $email = htmlentities(strip_tags(trim($_POST["email"])), ENT_QUOTES, 'UTF-8');
        $nick  = htmlentities(strip_tags(trim($_POST["nick"])), ENT_QUOTES, 'UTF-8');
        $pass  = htmlentities(strip_tags(trim($_POST["pass"])), ENT_QUOTES, 'UTF-8');

        if (User::listByName($name)) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '帳號已經被使用'
                )
            );
        }

        $user = User::create($name, $email, $nick, $pass);

        if ($user instanceof User) {
            return json_encode(
                array(
                    'status' => true,
                    'msg'    => '新增成功'
                )
            );
        }
    }

    //==================================
    //用以更新使用者資訊。
    //==================================
    /**修改使用者
     * @param string $name 使用者帳號
     * @param POST string $password 使用者密碼 (字數長度最少8最多16只限小寫英文+數字)
     * @param POST string $nickname 使用者暱稱 (長度最少2最多8只限中文)
     * @param POST string $email 使用者信箱 (email格式)
     * @return JSON {status:bool(是否修改成功), msg:string(訊息)}
     */
    public function editUser($name = null)
    {
        //模擬PUT轉POST
        parse_str(file_get_contents('php://input'), $_POST);

        $user;
        $email;
        $nick;
        $pass;
        $token;
        $input      = new Input();
        $email_rule = new EmailRule();
        $nick_rule  = new RegexpRule('/^([\x7f-\xff]{6,24})$/');
        $pass_rule  = new RegexpRule('/^([0-9a-z]{8,16})$/');

        $input
            ->add($email_rule, 'email', Input::POST)
            ->add($nick_rule, 'nick', Input::POST)
            ->add($pass_rule, 'pass', Input::POST);


        if ($input->check() !== true) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '資料格式錯誤'
                )
            );
        }

        $token = User::listByName($name);

        if (!$token) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '查無使用者'
                )
            );
        }

        $email = htmlentities(strip_tags(trim($_POST["email"])), ENT_QUOTES, 'UTF-8');
        $nick  = htmlentities(strip_tags(trim($_POST["nick"])), ENT_QUOTES, 'UTF-8');
        $pass  = htmlentities(strip_tags(trim($_POST["pass"])), ENT_QUOTES, 'UTF-8');

        $user = User::load($token[0]);
        $user->setEmail($email);
        $user->setNick($nick);
        $user->setPassword($pass);
        $user->save();

        return json_encode(
            array(
                'status' => true,
                'msg'    => '修改成功'
            )
        );
    }


    //==================================
    //單純的刪除使用。
    //==================================
    /**刪除使用者
     * @param string $name 使用者帳號
     * @return JSON {status:bool(是刪除改成功), msg:string(訊息)}
     */
    public function deleteUser($name = null)
    {
        parse_str(file_get_contents('php://input'), $_DELETE);

        $user;
        $token = User::listByName($name);

        if (!$token) {
            return json_encode(
                array(
                    'status' => false,
                    'msg'    => '查無使用者'
                )
            );
        }

        $user = User::load($token[0]);

        $user->delete();

        return json_encode(
            array(
                'status' => true,
                'msg'    => '刪除成功'
            )
        );
    }
}
