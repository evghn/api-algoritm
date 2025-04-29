<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property int $age
 * @property string $sex
 */
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'email', 'age', 'sex'], 'required'],
            [['age'], 'integer', 'min' => 15, 'max' => 100],
            
            [['email'], 'string', 'max' => 255],
            [['email'], 'email'],

            [['password'], 'string', 'max' => 255, 'min' => 6],
            [['login'], 'string', 'max' => 255, 'min' => 4],

            [['password', 'login'], 'match', 'pattern' => '/^[a-z0-9]+$/i', 'message' => 'Только латиница и цифры' ],
            [['sex'], 'string', 'max' => 10],
            [['login'], 'unique'],
            ["sex", "validateSex"]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'email' => 'Email',
            'age' => 'Возраст',
            'sex' => 'Пол',
        ];
    }


    public function validateSex($attribute, $params)
    {
        $value = [
            "м", 
            "ж", 
            "мужской", 
            "женский", 
            "муж", 
            "муж.", 
            "жен",
            "жен.",
            "male", 
            "female"
        ];
       

        if (!in_array($this->$attribute, $value)) {            
            $this->addError($attribute, 'Пол содержит не допустимое значение!');
        }
    }


    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }



    public static function findByUsername($login)
    {
        return self::findOne(['login' => $login]);
    }


}
