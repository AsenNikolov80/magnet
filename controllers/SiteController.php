<?php

namespace app\controllers;

use app\components\FileComponent;
use app\models\City;
use app\models\InvoiceData;
use app\models\Place;
use app\models\Proforma;
use app\models\Ticket;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\log\Logger;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use TCPDF;

class SiteController extends Controller
{

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
                            'places',
                            'ads',
                            'view-profile',
                            'edit-ads',
                            'selected-ads',
                            'create-invoice',
                            'add-place',
                            'view-place',
                            'delete-place',
                            'delete-place-confirmed',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (($action->id == 'edit-ads' && !Yii::$app->user->isUserCompany())
                                || $action->id == 'selected-ads' && !Yii::$app->user->isUser()
                            ) {
                                return $this->redirect(Yii::$app->urlManager->createAbsoluteUrl(['site/index']));
                            }
                            return true;
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
                            'pds',
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
        $ads = Ticket::find()->all();
        $companies = User::findAll(['active' => 1, 'type' => User::TYPE_COMPANY]);
        return $this->render('index', ['ads' => $ads, 'companies' => $companies]);
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
            if ($user->scenario == User::SCENARIO_REGISTER_COMPANY) {
                $user->paid_until = date('Y-m-d', time() + 1209600);
            }
            try {
                $emailExists = User::findOne(['email' => $user->email]);
                if (!empty($emailExists)) {
                    $user->addError('email', 'Имейлът вече съществува!');
                    throw new \Exception('email already exists', Logger::LEVEL_ERROR);
                }
                $user->save();
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($user->password);
                $user->save(false);
                Yii::$app->session->setFlash('success', 'Успешно се регистрирахте в системата!');
                if ($user->scenario == User::SCENARIO_REGISTER_COMPANY) {
                    $user->paid_amount = 30;
                    $user->save();
                    User::sendEmailToAdminByCompany($user);
                }
                return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
            } catch (\Exception $e) {
                Yii::$app->log->getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
                Yii::$app->session->setFlash('error', 'Нещо се обърка, опитайте отново!');
            }
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
        if (Yii::$app->user->isUserCompany())
            $user->setScenario(User::SCENARIO_REGISTER_COMPANY);
        if (Yii::$app->user->isUser())
            $user->setScenario(User::SCENARIO_REGISTER_USER);
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
                        if (file_exists($path))
                            unlink($path);
                    }
                    $t = move_uploaded_file($_FILES['User']['tmp_name']['picture'], 'profile_images/' . $_FILES['User']['name']['picture']);
                    $user->picture = $_FILES['User']['name']['picture'];
                } else $user->picture = $oldPicture;
            } else
                $user->picture = $oldPicture;
            $user->last_updated = date('Y-m-d H:i:s');
            $user->save();
        }
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        /* @var $selectedCommunityId */
        /* @var $selectedRegionId */
        extract($this->getRegionCommunityByCityId($user->city_id));
        if (!Yii::$app->user->isUserActive()) {
            if ($user->active == 0)
                Yii::$app->session->setFlash('errorAttribute', 'Профилът Ви не е одобрен от администратор');
            if ($user->paid_until < date('Y-m-d'))
                Yii::$app->session->setFlash('errorAttribute', 'Не сте извършили плащане, за да може да ползвате нашите услуги');
        }
        return $this->render('profile', [
            'user' => $user,
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
            'selectedRegionId' => $selectedRegionId,
            'selectedCommunityId' => $selectedCommunityId,
        ]);
    }

    public function actionAds()
    {
        $q = User::find()->where(['active' => 1])
            ->andWhere(['type' => User::TYPE_COMPANY])
            ->andWhere('paid_until>=:date', [':date' => date('Y-m-d')])
            ->orderBy('last_updated DESC');
        $postName = Yii::$app->request->post('name');
        $city = Yii::$app->request->post('city');
        if ($postName) {
            $q->andWhere(['LIKE', 'name', $postName]);
        }
        if ($city) {
            $q->andWhere(['city_id' => $city]);
        }
        $companies = $q->all();
        $places = [];
        /* @var $company User */
        foreach ($companies as $company) {
            $placesPerCompany = $company->getPlaces();
            $places = array_merge($places, $placesPerCompany);
        }
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        return $this->render('ads', [
            'places' => $places,
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
            'city' => $city,
            'postName' => $postName,
        ]);
    }

    public function actionViewProfile()
    {
        $placeId = intval($_GET['id']);
        /* @var $place Place */
        $place = Place::findOne($placeId);
        if (!$place) {
            Yii::$app->session->setFlash('error', 'Няма такъв профил!');
            return $this->redirect(Yii::$app->urlManager->createUrl('site/ads'));
        }
        $company = $place->getUser();
        list($tickets, $freeTextTickets) = $company->getTickets($place->id);
        return $this->render('view-profile', ['place' => $place, 'tickets' => $tickets, 'freeTextTickets' => $freeTextTickets]);
    }

    public function actionEditAds()
    {
        $user = $this->getCurrentUser();
        if (!empty($_POST['text'])) {
            $placeId = Yii::$app->request->post('placeId');
            if ($this->isUserOwnedPlace(Place::findOne($placeId))) {
                $texts = array_filter(Yii::$app->request->post('text'));
                $texts['free'] = array_filter($texts['free']);
                $prices = array_filter(Yii::$app->request->post('price'));
                $updatedKeys = [];
                foreach ($texts as $i => $text) {
                    if ($i === 'free') {
                        // free text ads
                        foreach ($text as $key => $place) {
                            $ticket = Ticket::find()->where(['id' => $key])->andWhere(['type' => Ticket::TYPE_FREE])->one();
                            if (!$ticket) $ticket = new Ticket();
                            $ticket->id_place = $placeId;
                            $ticket->text = $place;
                            $ticket->type = Ticket::TYPE_FREE;
                            $ticket->save();
                            $updatedKeys[] = $ticket->id;
                        }
                    } else {
                        $ticket = Ticket::find()->where(['id' => $i])->andWhere(['type' => Ticket::TYPE_PRICE])->one();
                        if (!$ticket) $ticket = new Ticket();
                        $ticket->id_place = $placeId;
                        $ticket->price = $prices[$i];
                        $ticket->text = $text;
                        $ticket->type = Ticket::TYPE_PRICE;
                        $ticket->save();
                        $updatedKeys[] = $ticket->id;
                    }
                }
                if (empty($updatedKeys)) {
                    Ticket::deleteAll(['id_place' => $placeId]);
                } else {
                    $toBeDeleted = Ticket::find()->where(['NOT IN', 'id', $updatedKeys])->andWhere(['id_place' => $placeId])->all();
                    foreach ($toBeDeleted as $place) {
                        $place->delete();
                    }
                }
//                User::sendEmailToUsersByCompany($user, false);
            }
        }
        $placesRaw = $user->getPlaces();
        $places = [];
        /* @var $place Place */
        foreach ($placesRaw as $place) {
            $places[$place->id] = $place->name;
        }
        $selectedPlaceId = Yii::$app->request->get('selectedPlace');
        if (empty($selectedPlaceId)) {
            $selectedPlace = $placesRaw[0];
        } else {
            $selectedPlace = Place::findOne($selectedPlaceId);
        }
        list($tickets, $freeTextTickets) = $user->getTickets($selectedPlaceId);
        return $this->render('edit-ads', ['tickets' => $tickets, 'freeTextTickets' => $freeTextTickets, 'places' => $places, 'selectedPlace' => $selectedPlace]);
    }

    public function actionSelectedAds()
    {
        $user = $this->getCurrentUser();
        $users = [];
        $usersIds = (new Query())->select('u.id')
            ->from(Ticket::tableName() . ' t')
            ->innerJoin(User::tableName() . ' u', 't.id_user=u.id')
            ->where(['u.city_id' => $user->city_id])
            ->andWhere(['u.active' => 1])
            ->column();
        $users[] = User::find()->where(['IN', 'id', $usersIds])->all();
        $users = $users[0];
        $cityName = $user->getCityName();
        return $this->render('selected-ads', ['users' => $users, 'cityName' => $cityName]);
    }


    public function actionCreateInvoice()
    {
        $currentUser = $this->getCurrentUser();
        $model = new InvoiceData();
        $model->getRecipientData();
        $model->date = date('d.m.Y');

        $items = [];
        $items[0]['name'] = 'Абонамент за ползване на сайт до ' . date('d.m.Y', strtotime('+1 years,+7 days'));
        $items[0]['price'] = number_format($currentUser->paid_amount / 1.2, 2);
        $items[0]['q'] = 1;

        $fileName = Proforma::FILE_NAME;
        $fileHandler = new FileComponent();
        $pdf = $fileHandler->preparePdfData();
        $path = $fileHandler->filePathProforma . $currentUser->username . DIRECTORY_SEPARATOR;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!file_exists($path . $fileName)) {
            $file = fopen($path . $fileName, 'w');
            fclose($file);
        }

        $proforma = Proforma::findOne(['date' => date('Y-m-d'), 'user_id' => $currentUser->id]);
        if (!$proforma) {
            $proforma = new Proforma();
            $proforma->user_id = $currentUser->id;
            $proforma->date = date('Y-m-d');
            $proforma->paid = 0;
            $proforma->save();
        }
        $model->number = $proforma->id;
        $pdf->writeHTML($this->renderPartial('_proforma', ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_PROFORMA]));
//        $pdf->lastPage();
        $pdf->Output($path . $fileName, 'F');
        $pdf->get();
    }

    public function actionPlaces()
    {
        $user = User::getUser(Yii::$app->user->id);
        $places = $user->getPlaces();
        return $this->render('places', ['user' => $user, 'places' => $places]);
    }

    public function actionAddPlace()
    {
        if (!empty($_POST['Place'])) {
            $place = new Place();
            $place->setAttributes(Yii::$app->request->post('Place'));
            $user = User::getUser(Yii::$app->user->id);
            $place->user_id = $user->id;
            if ($place->save()) {
                $file = new FileComponent();
                $path = $file->imagesPath;
                move_uploaded_file($_FILES['Place']['tmp_name']['picture'],
                    $path . $_FILES['Place']['name']['picture']);
                $place->picture = $_FILES['Place']['name']['picture'];
                $place->save();
                Yii::$app->session->setFlash('success', 'Успешно добавихте обекта ' . $place->name);
            }
        }
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        return $this->render('add-place', [
            'place' => new Place(),
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
        ]);
    }

    public function actionViewPlace()
    {
        $place = Place::findOne(Yii::$app->request->get('id'));
        if ($this->isUserOwnedPlace($place)) {
            if (!empty($_POST['Place'])) {
                $file = new FileComponent();
                $oldPicture = $place->picture;
                $place->setAttributes(Yii::$app->request->post('Place'));
                if (empty($_FILES['Place']['name']['picture'])) {
                    $place->picture = $oldPicture;
                } else {
                    if ($oldPicture)
                        unlink($file->imagesPath . $oldPicture);
                    $file->saveNewImage($file->imagesPath);
                    $place->picture = $_FILES['Place']['name']['picture'];
                }
                $place->save();
                $place = Place::findOne($place->id);
            }
            list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
            /* @var $selectedCommunityId */
            /* @var $selectedRegionId */
            extract($this->getRegionCommunityByCityId($place->city_id));
            return $this->render('view-place', [
                'place' => $place,
                'regions' => $regions,
                'cities' => $cities,
                'communities' => $communities,
                'cityRelations' => $cityRelations,
                'selectedCommunityId' => $selectedCommunityId,
                'selectedRegionId' => $selectedRegionId
            ]);
        }
    }

    public function actionDeletePlace()
    {
        $place = Place::findOne(Yii::$app->request->get('id'));
        if ($this->isUserOwnedPlace($place)) {
            return $this->renderPartial('_delete-place', ['place' => $place]);
        }
    }

    public function actionDeletePlaceConfirmed()
    {
        if (Yii::$app->request->isAjax) {
            $place = Place::findOne(Yii::$app->request->post('placeId'));
            if ($this->isUserOwnedPlace($place)) {
                $file = new FileComponent();
                if ($place->picture)
                    unlink($file->imagesPath . $place->picture);
                $place->delete();
            }
            return $this->redirect(Yii::$app->urlManager->createUrl('site/places'));
        }
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

    /**
     * @param Place $place
     */
    private function isUserOwnedPlace(Place $place)
    {
        $user = $this->getCurrentUser();
        if (!$place || !in_array($place, $user->getPlaces())) {
            return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
        }
        return true;
    }
}
