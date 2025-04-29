<?php

namespace app\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionError()
    {
        $this->response->statusCode = 400;
        return $this->asJson([
            'status' => false,
            'message' => 'Ошибка запроса',
        ]);
    }

    public function actionIndex()
    {
        return $this->asJson([
            'status' => true,
            'message' => 'The API is working!',
        ]);
    }
}
