<?php
namespace backend\models;

use yii\base\Model;
use common\models\Adminuser;

/**
 * Signup form
 */
class ResetpwdForm extends Model
{
    public $password;
    public $password_repeat;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat','compare','compareAttribute'=>'password','message'=>'两次输入密码不一致'],

        ];
    }

    public function attributeLabels()
    {
        return [

            'password' => '密码',
            'password_repeat' => '重新输入密码',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function resetpassword($id)
    {
//        var_dump($id);exit;
        if (!$this->validate()) {
            return null;
        }
        
       $adminuser = Adminuser::findOne($id);
//        var_dump($id);exit;
        $adminuser->setPassword($this->password);
//        var_dump($adminuser->errors);exit;
        $adminuser->removePasswordResetToken();
        $adminuser->password = '*';
        $adminuser->password_reset_token = '';
//        $adminuser->save();
//        var_dump($adminuser->errors);exit;
        return $adminuser->save() ? true : false;
    }
}
