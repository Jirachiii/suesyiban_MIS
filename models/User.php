<?php
 
namespace app\models;

use Yii;
 
class User extends \yii\base\Object implements \yii\web\IdentityInterface {
 
    // public $id;
    public $XH_ID;
    public $XH_PW;
    public $status;
    public $AuthKey;
    public $accessToken;
    public $Name;
    public $phone;
    //public $created_at;
    //public $updated_at;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $user = self::findById($id);
        if ($user) {
            return new static($user);
        }
        return null;
    }
 
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $user = UserTb::find()->where(array('accessToken' => $token))->one();
        if ($user) {
            return new static($user);
        }
        return null;
    }
 
    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $user = UserTb::find()->where(array('XH_ID' => $username))->one();
        if ($user) {
            return new static($user);
        }
 
        return null;
    }
 
    public static function findById($id) {
        $user = UserTb::find()->where(array('XH_ID' => $id))->asArray()->one();
        if ($user) {
            return new static($user);
        }
 
        return null;
    }

 

    public function getId() {
        return $this->XH_ID;
    }
    
    
    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->AuthKey;
    }
 
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->AuthKey === $authKey;
    }
 
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     * 在创建用户的时候，也需要对密码进行操作
     */
    public function validatePassword($password) {
        //一
        return $this->XH_PW === md5($password);
 
        //方法二：YII自带的验证
        //return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
 
}