<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\ContactForm;
use yii2keycloak\Keycloak\Keycloak;

class SiteController extends Controller
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

    public function actionUserList()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $user = Yii::$app->user->identity;

        if (!$user->getIsAdmin()) {
            throw new \yii\web\ForbiddenHttpException('Access Denied. Only admins can access this page.');
        }

        $users = Keycloak::admin()->getAllUsers();
        return $this->render('user-list', ['users' => $users]);
    }

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

    public function actionAboutMe()
    {
        return $this->render('about');
    }

    public function actionUserView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        if (!Yii::$app->user->identity->getIsAdmin()) {
            throw new \yii\web\ForbiddenHttpException('Access Denied. Only admins can view users.');
        }

        $admin = Keycloak::admin();
        $user = Keycloak::admin()->getUserById($id);
        $sessions = $admin->getUserSessions($id);
        return $this->render('user-view', [
            'user' => $user,
            'sessions' => $sessions,
        ]);
    }

}
