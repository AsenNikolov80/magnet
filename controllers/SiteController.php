<?php

namespace app\controllers;

use app\models\City;
use app\models\Ticket;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    public static $genders = ['мъж', 'жена'];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'logout',
                            'index',
                            'about',
                            'contact',
                            'welcome',
                            'profile',
                            'ads',
                            'view-profile',
                            'edit-ads',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return true;
//                            return $this->redirect(yii::$app->urlManager->createAbsoluteUrl(['site/index']));
                        }
                    ],
                    [
                        'actions' => [
                            'login',
                            'register',
                            'index',
                            'about',
                            'contact',
                            'ads',
                            'view-profile',
                        ],
                        'roles' => ['?'],
                        'allow' => true,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
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
        $companies = [];
        if (Yii::$app->user->isGuest) {
            $ads = Ticket::find()->all();
            $companies = User::findAll(['active' => 1, 'type' => User::TYPE_COMPANY]);
            return $this->render('index', ['ads' => $ads, 'companies' => $companies]);
        }
        return $this->render('index', ['companies' => $companies]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->urlManager->createUrl('site/welcome'));
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        return $this->render('contact', []);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionRegister()
    {
        $type = Yii::$app->request->get('type', null);
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        if ($type === null)
            return $this->render('register', [
                'regions' => $regions,
                'cities' => $cities,
                'communities' => $communities,
                'cityRelations' => $cityRelations
            ]);
        $user = new User();
        if ($type == 0) {
            $user->setScenario(User::SCENARIO_REGISTER_USER);
        } elseif ($type == 1) {
            $user->setScenario(User::SCENARIO_REGISTER_COMPANY);
        }
        $user->type = $type;
        if (!empty(Yii::$app->request->post('User'))) {
            $user->setAttributes(Yii::$app->request->post('User'));
            if ($user->save()) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->save(false);
                Yii::$app->session->setFlash('success', 'Успешно се регистрирахте в системата!');
                return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
            } else Yii::$app->session->setFlash('error', 'Нещо се обърка, опитайте отново!');
        }

        return $this->render('register', [
            'user' => $user,
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations
        ]);
    }

    public function actionWelcome()
    {
        $user = $this->getCurrentUser();
        if (Yii::$app->user->isUserCompany()) {
            return $this->render('welcome-company', ['user' => $user]);
        } elseif (Yii::$app->user->isUser()) {
            return $this->render('welcome-user', ['user' => $user]);
        }

        // admin view
        return $this->render('welcome', ['user' => $user]);
    }

    public function actionProfile()
    {
        /* @var $user User */
        $user = $this->getCurrentUser();
        if (!empty(Yii::$app->request->post('User'))) {
            $oldPicture = $user->picture;
            $user->setAttributes(Yii::$app->request->post('User'));
            if (!empty($_FILES['User']['tmp_name']['picture'])) {
                $fileInfo = getimagesize($_FILES['User']['tmp_name']['picture']);
                if ($fileInfo[2] == IMAGETYPE_BMP
                    || $fileInfo[2] == IMAGETYPE_GIF
                    || $fileInfo[2] == IMAGETYPE_JPEG
                    || $fileInfo[2] == IMAGETYPE_PNG
                ) {
                    if ($oldPicture) {
                        $path = Yii::$app->basePath . '/web/profile_images/' . $oldPicture;
                        unlink($path);
                    }
                    $t = move_uploaded_file($_FILES['User']['tmp_name']['picture'], 'profile_images/' . $_FILES['User']['name']['picture']);
                    $user->picture = $_FILES['User']['name']['picture'];
                } else $user->picture = $oldPicture;
            } else
                $user->picture = $oldPicture;
            $user->save();
        }
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        /* @var $selectedCommunityId */
        /* @var $selectedRegionId */
        extract($this->getRegionCommunityByCityId($user->city_id));
        $genders = [];
        foreach (self::$genders as $gender) {
            $genders[$gender] = $gender;
        }
        return $this->render('profile', [
            'user' => $user,
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
            'selectedRegionId' => $selectedRegionId,
            'selectedCommunityId' => $selectedCommunityId,
            'genders' => $genders
        ]);
    }

    public function actionAds()
    {
        return $this->render('ads', []);
    }

    public function actionViewProfile()
    {
        $companyId = intval($_GET['id']);
        /* @var $company User */
        $company = User::findOne($companyId);
        if (!$company)
            Yii::$app->session->setFlash('error', 'Няма такъв профил!');
        return $this->render('view-profile', ['company' => $company, 'tickets' => $company->getTickets()]);
    }

    public function actionEditAds()
    {
        $user = $this->getCurrentUser();
        if (!empty($_POST['text'])) {
            $texts = array_filter(Yii::$app->request->post('text'));
            $prices = array_filter(Yii::$app->request->post('price'));
            $updatedKeys = [];
            foreach ($texts as $i => $text) {
                $ticket = Ticket::findOne($i);
                if (!$ticket) $ticket = new Ticket();
                $ticket->id_user = Yii::$app->user->id;
                $ticket->price = $prices[$i];
                $ticket->text = $text;
                $ticket->save();
                $updatedKeys[] = $ticket->id;
            }
            if (empty($updatedKeys)) {
                Ticket::deleteAll(['id_user' => Yii::$app->user->id]);
            } else {
                $toBeDeleted = Ticket::find()->where(['NOT IN', 'id', $updatedKeys])->all();
                foreach ($toBeDeleted as $item) {
                    $item->delete();
                }
            }
        }
        return $this->render('edit-ads', ['tickets' => $user->getTickets()]);
    }

    private function getListOfRegionsCities()
    {
        $regions = [];
        $communities = [];
        $cities = [];
        $cityRelations = [];
        $citiesArray = (new Query())->select('c.id AS c_id, c.name as c_name, com.id AS com_id, com.name as com_name, r.id AS reg_id, r.name as reg_name')
            ->from(City::tableName() . ' c')
            ->innerJoin('communities com', 'c.community_id=com.id')
            ->innerJoin('regions r', 'com.region_id=r.id')->all();
        foreach ($citiesArray as $item) {
            $cityRelations[$item['reg_id']][$item['com_id']][] = $item['c_id'];
            $cities[$item['c_id']] = $item['c_name'];
            $communities[$item['com_id']] = $item['com_name'];
            $regions[$item['reg_id']] = $item['reg_name'];
        }

        return [$regions, $cities, $communities, $cityRelations];
    }

    /**
     * @return null|\yii\web\Response|static|User
     */
    private function getCurrentUser()
    {
        $user = User::findOne(Yii::$app->user->id);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Няма такъв потребител!');
            return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
        }
        return $user;
    }

    /**
     * @param $cityId
     * @param bool $asIndexedArray
     * @return array|bool - community and region
     */
    private function getRegionCommunityByCityId($cityId, $asIndexedArray = false)
    {
        $result = (new Query())
            ->select('c.community_id as selectedCommunityId, com.region_id as selectedRegionId')
            ->from(City::tableName() . ' c')
            ->innerJoin('communities com', 'c.community_id=com.id')
            ->where(['c.id' => $cityId])->one();
        if ($asIndexedArray) {
            $result = array_values($result);
        }
        return $result;
    }
}
