<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\ContactForm;
use app\models\SignupForm;
use yii2keycloak\Keycloak\Keycloak;

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

    public function actionCallback()
    {
        $code = Yii::$app->request->get('code');
        $redirectUri = Yii::$app->params['keycloak']['redirect_uri'];

        $token = Keycloak::auth()->getToken($code, $redirectUri);
        $userInfo = Keycloak::user()->getUserInfo($token['access_token']);

        $user = User::findByEmail($userInfo['email']);
        
        if (!$user) {
            $user = User::createFromKeycloak($userInfo);
        }

        Yii::$app->user->login($user);

        Yii::$app->session->set('id_token', $token['id_token']);

        return $this->redirect(['site/about-me']);
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
        $clientId = Yii::$app->params['keycloak']['client_id'];
        $redirectUri = Yii::$app->params['keycloak']['redirect_uri'];
        $authUrl = Yii::$app->params['keycloak']['auth_url'];
        
        $query = http_build_query([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'scope' => 'openid email profile',
        ]);

        return $this->redirect($authUrl . '?' . $query);
    }


    public function actionKcLogout()
    {
        $idToken = Yii::$app->session->get('id_token');
        Yii::$app->session->removeAll(); // Clear all session data

        $logoutUrl = Yii::$app->params['keycloak']['logout_url'];
        $redirectUri = Yii::$app->params['keycloak']['redirect_uri_after_logout'] ?? 'http://localhost:8080';

        $url = $logoutUrl . '?' . http_build_query([
            'post_logout_redirect_uri' => $redirectUri,
            'id_token_hint' => $idToken,
        ]);

        return $this->redirect($url);
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
