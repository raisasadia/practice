<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class DemoController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [   //Purpose: Restricts the logout action so only logged-in users can access it.
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],   // @ = logged-in users
                    ],
                ],
            ],
            'verbs' => [    //Purpose: Prevents people from logging out via URL like GET /site/logout.
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],   // only allow POST requests
                ],
            ],
        ];
    }

    public function actionDemonstrate()
    {
        return $this->render('demo');
    }
   
}
