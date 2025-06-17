<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\SignupForm;
use yii\authclient\AuthAction;
use app\models\User;
use yii\authclient\ClientInterface;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function actions()   //declare external action classes without writing their logic directly inside the controller.
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
            'error' => [    //Purpose: Displays the error page.
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [  //Purpose: Displays the captcha page.
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Registration successful! You can now log in.');
            return $this->redirect(['site/login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function onAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();
        $id = $attributes['sub'];

        $user = User::find()->where(['keycloak_id' => $id])->one();

        if (!$user) {
            $user = new User();
            $user->keycloak_id = $id;
            $user->name = $attributes['preferred_username'] ?? 'Unknown';
            $user->email = $attributes['email'] ?? null;
            $user->access_token = $client->getAccessToken()->getToken();
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->save();
        }

        Yii::$app->user->login($user, 3600 * 24 * 30);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAboutMe()
    {
        return $this->render('about');
    }
}
