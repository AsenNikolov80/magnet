<?php

namespace app\controllers;

use app\components\FileComponent;
use app\models\Category;
use app\models\City;
use app\models\Factura;
use app\models\InvoiceData;
use app\models\Place;
use app\models\Proforma;
use app\models\RecoverPassword;
use app\models\Ticket;
use app\models\User;
use app\models\UserCity;
use Yii;
use yii\db\Connection;
use yii\db\Query;
use yii\db\Transaction;
use yii\filters\AccessControl;
use yii\log\Logger;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use TCPDF;

class SiteController extends Controller
{
    const PAGE_SIZE = 12;

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
                            'prices',
                            'delete-place-confirmed',
                            'invoices',
                            'preview-invoice',
                            'conditions',
                            'cookies',
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $allow = true;
                            if (($action->id == 'edit-ads' && !Yii::$app->user->isUserCompany())
                                || $action->id == 'selected-ads' && !Yii::$app->user->isUser()
                            ) {
                                $allow = false;
                                $this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
                            }
                            if ($action->id == 'create-invoice' && !Yii::$app->user->isUserAdmin()) {
                                $allow = false;
                                $this->redirect(Yii::$app->urlManager->createUrl(['site/index']));
                            }
                            return $allow;
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
                            'prices',
                            'conditions',
                            'lost-password',
                            'recover',
                            'change-password',
                            'cookies'
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
            return $this->redirect(Yii::$app->urlManager->createUrl('site/profile'));
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
        $contactForm = Yii::$app->request->post('ContactForm');
        $contact = new ContactForm();
        if (!empty($contactForm) && $contact->checkCode($contactForm['verifyCode'])) {
            $contact->setAttributes($contactForm);
            $admins = User::findAll(['type' => USER::TYPE_ADMIN]);
            /* @var $admin User */
            foreach ($admins as $admin) {
                $to = $admin->email;
                if ($to) {
                    $contact->contact($to);
                }
            }
        }
        $contact = new ContactForm();
        $contact->generateCode();
        return $this->render('contact', ['contact' => $contact]);
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
                $user->paid_until = date('Y-m-d', time() - 86400);
            }
            try {
                if ($user->conditions == 0) {
                    $user->addError('conditions', 'Трябва да приемете общите условия, за да ползвате сайта като регистриран потребител!');
                    Yii::$app->session->setFlash('errorAttribute', 'Трябва да приемете общите условия, за да ползвате сайта като регистриран потребител!');
                    throw new \Exception('conditions not accepted!', Logger::LEVEL_ERROR);
                }
                $emailExists = User::findOne(['email' => $user->email]);
                if (!empty($emailExists)) {
                    $user->addError('email', 'Имейлът вече съществува!');
                    Yii::$app->session->setFlash('errorAttribute', 'Имейлът вече съществува!');
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
        $categories = Category::getCategoriesForDropdown();
        return $this->render('register', [
            'user' => $user,
            'regions' => $regions,
            'cities' => $cities,
            'categories' => $categories,
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

        // todo activate users on login
        $user->active = 1;
        $user->save();
        if (Yii::$app->user->isUserCompany())
            $user->setScenario(User::SCENARIO_REGISTER_COMPANY);
        if (Yii::$app->user->isUser())
            $user->setScenario(User::SCENARIO_REGISTER_USER);
        if (!empty(Yii::$app->request->post('User'))) {
//            $oldPicture = $user->picture;
            $user->setAttributes(Yii::$app->request->post('User'));
//            if (!empty($_FILES['User']['tmp_name']['picture'])) {
//                $fileInfo = getimagesize($_FILES['User']['tmp_name']['picture']);
//                if ($fileInfo[2] == IMAGETYPE_BMP
//                    || $fileInfo[2] == IMAGETYPE_GIF
//                    || $fileInfo[2] == IMAGETYPE_JPEG
//                    || $fileInfo[2] == IMAGETYPE_PNG
//                ) {
//                    if ($oldPicture) {
//                        $path = Yii::$app->basePath . '/web/profile_images/' . $oldPicture;
//                        if (file_exists($path))
//                            unlink($path);
//                    }
//                    $t = move_uploaded_file($_FILES['User']['tmp_name']['picture'], 'profile_images/' . $_FILES['User']['name']['picture']);
//                    $user->picture = $_FILES['User']['name']['picture'];
//                } else $user->picture = $oldPicture;
//            } else
//                $user->picture = $oldPicture;
            $user->last_updated = date('Y-m-d H:i:s');
            try {
                $additionalCityId = Yii::$app->request->post('additionalCityId');
                $flag = Yii::$app->request->post('additionalCity', 0);
                $user->addedPlace = $flag;
                $userCity = UserCity::findOne(['user_id' => $user->id]);
                if (!empty($additionalCityId) && $flag == 1) {
                    // set additional city
                    if (!$userCity) {
                        $userCity = new UserCity();
                        $userCity->user_id = $user->id;
                    }
                    $userCity->city_id = $additionalCityId;
                    $userCity->save();
                } else {
                    // delete additional city
                    if ($userCity) $userCity->delete();
                }
                $user->save();
                Yii::$app->session->setFlash('success', 'Успешно обновихте профила си!');
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Нещо се обърка, вероятно имейлът вече е регистриран!');
                Yii::error($e->getMessage());
                $user = $this->getCurrentUser();
            }
        }
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities();
        /* @var $selectedCommunityId */
        /* @var $selectedRegionId */
        extract($this->getRegionCommunityByCityId($user->city_id));
//        if (!Yii::$app->user->isUserActive()) {
//            if ($user->active == 0)
//                Yii::$app->session->setFlash('errorAttribute', 'Профилът Ви не е одобрен от администратор');
//            elseif ($user->paid_until < date('Y-m-d'))
//                Yii::$app->session->setFlash('errorAttribute', 'Не сте извършили плащане, за да може да ползвате нашите услуги');
//        }

        $categories = Category::getCategoriesForDropdown();
        $additionalCities = [];
        foreach ($cityRelations as $regId => $region) {
            foreach ($region as $commId => $community) {
                foreach ($community as $cityId) {
                    $additionalCities[$cityId] = $regions[$regId] . ', ' . $communities[$commId] . ', ' . $cities[$cityId];
                }
            }
        }
//        echo '<pre>' . print_r($additionalCities, true) . '</pre>';
//        die;
        return $this->render('profile', [
            'user' => $user,
            'regions' => $regions,
            'cities' => $cities,
            'categories' => $categories,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
            'selectedRegionId' => $selectedRegionId,
            'selectedCommunityId' => $selectedCommunityId,
            'additionalCities' => $additionalCities,
        ]);
    }

    public function actionAds()
    {
        $req = Yii::$app->request;
        $page = $req->get('page', 1);
        $postName = Yii::$app->request->post('name');
        $city = Yii::$app->request->post('city');
        $category = Yii::$app->request->post('category');
        $params = [
            ':type' => User::TYPE_COMPANY,
            ':date' => date('Y-m-d')
        ];
        // AND u.active=1
        $q = Place::find()->alias('t')
            ->innerJoin(User::tableName() . ' u', 'user_id=u.id AND u.type=:type AND t.paid_until>=:date', $params)
            ->orderBy('t.last_updated DESC')
            ->where(['t.active' => 1]);
        if ($postName) {
            $q->andWhere(['LIKE', 't.name', $postName]);
        }
        if ($city) {
            $q->andWhere(['t.city_id' => $city]);
        }
        if ($category) {
            $q->andWhere(['u.cat_id' => $category]);
        }
        $places = $q->limit(self::PAGE_SIZE)->offset(($page - 1) * self::PAGE_SIZE)->all();
        list($regions, $cities, $communities, $cityRelations) = $this->getListOfRegionsCities(true);

        // ->where(['u.active' => 1])
        $maxPages = (int)(new Query())->select('COUNT(p.id)')
            ->from(User::tableName() . ' u')
            ->innerJoin(Place::tableName() . ' p', 'p.user_id=u.id')
            ->andWhere(['u.type' => User::TYPE_COMPANY])->scalar();
        $maxPages = ceil($maxPages / self::PAGE_SIZE);
        return $this->render('ads', [
            'places' => $places,
            'regions' => $regions,
            'cities' => $cities,
            'communities' => $communities,
            'cityRelations' => $cityRelations,
            'city' => $city,
            'postName' => $postName,
            'page' => $page,
            'maxPages' => $maxPages,
            'selectedCategory' => $category ? $category : '',
            'categoryList' => Category::getCategoriesForDropdown(),
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
        $selectedPlaceId = Yii::$app->request->get('selectedPlace');
        $this->isUserOwnedPlace(Place::findOne($selectedPlaceId));
        $placeId = $selectedPlaceId;
        if (!empty($_POST['text'])) {
//            $placeId = Yii::$app->request->post('placeId');
            $targetPlace = Place::findOne($placeId);
            if ($this->isUserOwnedPlace($targetPlace)) {
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
                $targetPlace->last_updated = date('Y-m-d H:i:s');
                $targetPlace->save();
                User::sendEmailToUsersByCompany($user, false);
            }
        }
        $placesRaw = $user->getPlaces();
        $places = [];
        /* @var $place Place */
        foreach ($placesRaw as $place) {
            $places[$place->id] = $place->name;
        }

        if (empty($selectedPlaceId)) {
            if (!empty($placesRaw[0]))
                $selectedPlace = $placesRaw[0];
            else $selectedPlace = 0;
        } else {
            $selectedPlace = Place::findOne($selectedPlaceId);
        }
        list($tickets, $freeTextTickets) = $user->getTickets($selectedPlaceId);
        return $this->render('edit-ads', ['tickets' => $tickets, 'freeTextTickets' => $freeTextTickets, 'places' => $places, 'selectedPlace' => $selectedPlace]);
    }

    public function actionSelectedAds()
    {
        $user = $this->getCurrentUser();
//        $users = [];
//        $usersIds = (new Query())->select('u.id')
//            ->from(Ticket::tableName() . ' t')
//            ->innerJoin(User::tableName() . ' u', 't.id_user=u.id')
//            ->where(['u.city_id' => $user->city_id])
//            ->andWhere(['u.active' => 1])
//            ->column();
//        $users[] = User::find()->where(['IN', 'id', $usersIds])->all();
//        $users = $users[0];
        $places = Place::find()->where(['active' => 1])
            ->andWhere('paid_until>="' . date('Y-m-d') . '"')
            ->andWhere(['IN', 'city_id', $user->getSelectedCities()])->all();
        return $this->render('selected-ads', ['places' => $places, 'cityName' => $user->getCityName()]);
    }


    public function actionCreateInvoice()
    {
        $place = Place::findOne(intval($_GET['id']));
        $targetUser = $place->getUser();
        if ($place->checked == 0) {
            Yii::$app->session->setFlash('error', 'Нямате право на тази операция');
            return $this->redirect('site/index');
        }
        if ($place && $this->isUserOwnedPlace($place)) {
            $model = new InvoiceData();
            $model->getRecipientData($targetUser->id);
            $model->date = date('d.m.Y');

            $items = [];
            $items[0]['name'] = 'Абонамент за ползване на сайт до ' . date('d.m.Y', strtotime('+1 years,+7 days'));
            $items[0]['price'] = number_format($place->price / 1.2, 2);
            $items[0]['q'] = 1;

            $sum = round($items[0]['q'] * $items[0]['price'], 2);

            $fileName = Proforma::FILE_NAME . '_' . $place->id . '.pdf';
            $fileHandler = new FileComponent();
            $pdf = $fileHandler->preparePdfData();
            $path = $fileHandler->filePathProforma;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            if (!file_exists($path . $fileName)) {
                $file = fopen($path . $fileName, 'w');
                fclose($file);
            }

            $proforma = Proforma::findOne(['date' => date('Y-m-d'), 'place_id' => $place->id]);
            if (!$proforma) {
                $proforma = new Proforma();
                $proforma->place_id = $place->id;
                $proforma->date = date('Y-m-d');
                $proforma->paid = 0;
                $proforma->save();
            }
            $model->number = $proforma->id;
            $sumWord = $this->getSumWord(round($sum * 1.2, 2));
            $pdf->writeHTML($this->renderPartial('_proforma',
                ['model' => $model, 'items' => $items, 'type' => FileComponent::TYPE_PROFORMA, 'sum' => $sum, 'sumWord' => $sumWord]));
//        $pdf->lastPage();
            $pdf->Output($path . $fileName, 'F');
            $pdf->get();
        }
    }

    private function getSumWord($sum)
    {
        $sum *= 100;
        $sum = intval(round($sum));
        $lastDigitStot = $sum % 10;
        $sum /= 10;
        $firstDigitStot = $sum % 10;
        $sum /= 10;
        $lastDigitLev = $sum % 10;
        $sum /= 10;
        $firstDigitLev = $sum % 10;
        $stotString = '';
        $levString = '';
        switch ($firstDigitStot) {
            case 1:
                switch ($lastDigitStot) {
                    case 1:
                        $stotString = 'единадесет';
                        break;
                    case 2:
                        $stotString = 'дванадесет';
                        break;
                    case 3:
                        $stotString = 'тринадесет';
                        break;
                    case 4:
                        $stotString = 'четиринадесет';
                        break;
                    case 5:
                        $stotString = 'петнадесет';
                        break;
                    case 6:
                        $stotString = 'шестнадесет';
                        break;
                    case 7:
                        $stotString = 'седемнадесет';
                        break;
                    case 8:
                        $stotString = 'осемнадесет';
                        break;
                    case 9:
                        $stotString = 'деветнадесет';
                        break;
                    case 0:
                        $stotString = 'десет';
                        break;
                }
                break;
            case 2:
                $stotString = 'двадесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 3:
                $stotString = 'тридесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 4:
                $stotString = 'четиридесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 5:
                $stotString = 'петдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 6:
                $stotString = 'шестдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 7:
                $stotString = 'седемдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 8:
                $stotString = 'осемдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 9:
                $stotString = 'деветдесет';
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
            case 0:
                $stotString = $this->handleMoney($stotString, $lastDigitStot);
                break;
        }
        switch ($firstDigitLev) {
            case 1:
                switch ($lastDigitLev) {
                    case 1:
                        $levString = 'единадесет';
                        break;
                    case 2:
                        $levString = 'дванадесет';
                        break;
                    case 3:
                        $levString = 'тринадесет';
                        break;
                    case 4:
                        $levString = 'четиринадесет';
                        break;
                    case 5:
                        $levString = 'петнадесет';
                        break;
                    case 6:
                        $levString = 'шестнадесет';
                        break;
                    case 7:
                        $levString = 'седемнадесет';
                        break;
                    case 8:
                        $levString = 'осемнадесет';
                        break;
                    case 9:
                        $levString = 'деветнадесет';
                        break;
                    case 0:
                        $levString = 'десет';
                        break;
                }
                break;
            case 2:
                $levString = 'двадесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 3:
                $levString = 'тридесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 4:
                $levString = 'четиридесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 5:
                $levString = 'петдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 6:
                $levString = 'шестдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 7:
                $levString = 'седемдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 8:
                $levString = 'осемдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 9:
                $levString = 'деветдесет';
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
            case 0:
                $levString = $this->handleMoney($levString, $lastDigitLev, 'lev');
                break;
        }
        $stotString .= ' стотинки';
        $levString .= ' лева';
        return $levString . ', ' . $stotString;
    }

    private function handleMoney($stotString, $digit, $type = 'stot')
    {
        switch ($digit) {
            case 1:
                if ($type == 'lev') {
                    $stotString .= ' и един';
                } else {
                    $stotString .= ' и една';
                }
                break;
            case 2:
                if ($type == 'lev') {
                    $stotString .= ' и два';
                } else {
                    $stotString .= ' и две';
                }
                break;
            case 3:
                $stotString .= ' и три';
                break;
            case 4:
                $stotString .= ' и четири';
                break;
            case 5:
                $stotString .= ' и пет';
                break;
            case 6:
                $stotString .= ' и шест';
                break;
            case 7:
                $stotString .= ' и седем';
                break;
            case 8:
                $stotString .= ' и осем';
                break;
            case 9:
                $stotString .= ' и девет';
                break;
            case 0:
                if ($stotString == '') {
                    if ($type == 'lev') {
                        $stotString .= 'нула';
                    } else {
                        $stotString .= ' и нула';
                    }
                }
                break;
        }
        return $stotString;
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
            $place->active = 1;
            $place->date_created = date('Y-m-d');
            $place->paid_until = date('Y-m-d', strtotime('+7 days'));
            if ($place->save()) {
                $file = new FileComponent();
                $path = $file->imagesPath;
                if (!empty($_FILES['Place'])) {
                    move_uploaded_file($_FILES['Place']['tmp_name']['picture'],
                        $path . $_FILES['Place']['name']['picture']);
                    $place->picture = $_FILES['Place']['name']['picture'];
                    $place->save();
                }
                Yii::$app->session->setFlash('success', 'Успешно добавихте обекта "' . $place->name
                    . '"<br/>Обектът и обявите към него ще се виждат 7 дни, за да продължите да ползвате услугата, моля извършете плащане на проформа фактура, която ще получите по email!<br/><a href="' . Yii::$app->urlManager->createUrl(['site/view-place', 'id' => $place->id]) . '"><h3>Към обекта</h3></a>');
                User::sendEmailToAdminByPlace($place);
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
        $place = Place::findOne(Yii::$app->request->post('placeId'));
        if ($this->isUserOwnedPlace($place)) {
            $file = new FileComponent();
            if ($place->picture)
                unlink($file->imagesPath . $place->picture);
            $place->delete();
        }
        return $this->redirect(Yii::$app->urlManager->createUrl('site/places'));
    }

    public function actionPrices()
    {
        return $this->render('prices');
    }

    public function actionInvoices()
    {
        $user = $this->getCurrentUser();
        $invoices = $user->getInvoices();
        return $this->render('invoices', ['invoices' => $invoices]);
    }

    public function actionPreviewInvoice()
    {

        $factura = Factura::findOne(intval($_GET['id']));
        if ($factura) {
            $user = $factura->getUser();
            $file = new FileComponent($user);
            $path = $file->filePathFactura . $factura->path;
            $pdf = file_get_contents($path);
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . Proforma::FILE_NAME . '"');
            echo $pdf;
        }
    }

    public function actionConditions()
    {
        return $this->render('conditions');
    }

    public function actionLostPassword()
    {
        $req = Yii::$app->request;
        if (!empty($req->post('email')) && $req->post('forgotPass') == 'Изпрати') {
            $trans = Yii::$app->db->beginTransaction();
            $email = $req->post('email');
            try {
                $hash = md5($email . time() . mt_rand(1, 800000));
                $recover = new RecoverPassword();
                $recover->email = $email;
                $recover->hash = $hash;
                $recover->valid = date('Y-m-d H:i:s', time() + 3600);
                $recover->save();
                $trans->commit();
                $to = $email;
                $subject = 'Заявка за подновяване на парола!';
                $headers = "Content-Type: text/html;\r\n charset=utf-8";
                $msg = 'Заявихте нова парола за promobox-bg.com,<br/> моля кликнете
                <a href="' . Yii::$app->urlManager->createUrl(['site/recover', 'email' => $email, 'hash' => $hash]) . '">ТУК</a>, за да въведете нова парола.';
                mail($to, $subject, $msg, $headers);
                Yii::$app->session->setFlash('success', 'На посочения от Вас имейл бяха изпратени инструкции за подновяване на паролата!');
            } catch (\Exception $e) {
                $trans->rollBack();
                Yii::$app->session->setFlash('error', 'Проверете имейлът си, вероятно няма потребител с такъв имейл!');
            }
        }
        return $this->render('lost-password');
    }

    public function actionRecover()
    {
        $email = Yii::$app->request->get('email');
        $hash = Yii::$app->request->get('hash');
        $recover = RecoverPassword::findOne(['hash' => $hash]);
        if (empty($email)
            || empty($hash)
            || empty($recover)
            || $recover->email != $email
            || $recover->valid < date('Y-m-d H:i:s')
        ) {
            if ($recover) $recover->delete();
            Yii::$app->session->setFlash('error', 'Грешен линк!');
            return $this->redirect(Yii::$app->urlManager->createUrl('site/index'));
        }
        $user = $recover->getUser();
        return $this->render('recover', ['user' => $user]);
    }

    public function actionChangePassword()
    {
        if (Yii::$app->request->isPost
            && !empty(Yii::$app->request->post('User'))
            && !empty(Yii::$app->request->post('changePass'))
        ) {
            $req = Yii::$app->request;
            $postedUser = new User();
            $postedUser->setAttributes($req->post('User'));
            $postedUser->id = $req->post('User')['id'];
            $user = User::findOne($postedUser->id);
            $user->setScenario(User::SCENARIO_PASSWORD_CHANGE);
            if ($user
                && $user->email == $postedUser->email
                && $user->username == $postedUser->username
            ) {
                $user->password = Yii::$app->security->generatePasswordHash($postedUser->password);
                $user->save();
                Yii::$app->session->setFlash('success', 'Успешно променихте паролата си!');
                $recovers = RecoverPassword::findAll(['email' => $user->email]);
                foreach ($recovers as $recover) {
                    $recover->delete();
                }
                $this->redirect(Yii::$app->urlManager->createUrl('site/login'));
            }
        }
    }

    public function actionCookies()
    {
        return $this->render('cookies');
    }

    private function getListOfRegionsCities($emptyFirst = false)
    {
        $regions = [];
        $communities = [];
        $cities = [];
        $cityRelations = [];
        if ($emptyFirst) {
            $regions[0] = 'област...';
            $communities[0] = 'община...';
            $cities[0] = 'населено място...';
            $cityRelations[0][0][] = '0';
        }
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
