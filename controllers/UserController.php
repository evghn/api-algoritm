<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\web\Response;


class UserController extends ActiveController
{
    public $modelClass = 'User';

    public function actions()
    {
        return parent::actions()['options'];
    }


    public function actionRegister()
    {
        $user = new User();
        $this->response->statusCode = 418;

        if ($this->request->isPost && $user->load($this->request->post(), '')) {
            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);
                $user->save(false);
                return $this->asJson([
                    'status' => true,
                    'message' => "Пользователь {$user->login} успешно зарегистрирован!",
                ]);
            }
            
            return $this->asJson([
                'status' => false,
                'errors' => $user->errors,
            ]);
        }

        return $this->asJson([
            'status' => false,
            'message' => 'Не корректный запрос!',
        ]);
    }


    public function actionLogin()
    {
        $model = new LoginForm();
        $this->response->statusCode = 418;

        if ($this->request->isPost && $model->load($this->request->post(), '')) {
            if ($model->login()) {
                return $this->asJson([
                    'status' => true,
                    'message' => 'Пользователь успешно аутентифицирован!',
                    'user' => (User::findOne([
                        'login' => $model->login,
                    ]))->attributes,
                ]);
            }
            
            return $this->asJson([
                'status' => false,
                'errors' => $model->errors,
            ]);
        }

        return $this->asJson([
            'status' => false,
            'message' => 'Не корректный запрос!',
        ]);
    }


    
}
